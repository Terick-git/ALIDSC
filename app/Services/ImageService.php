<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ImageService
{
    /**
     * Upload et optimise une image (redimension + conversion WebP)
     */
    public function uploadOptimise(UploadedFile $file, string $dossier, int $largeurMax = 1920, int $qualite = 85): string
    {
        $nomFichier = Str::random(40) . '.webp';
        $chemin = $dossier . '/' . $nomFichier;
        $cheminComplet = Storage::disk('public')->path($chemin);

        if (!Storage::disk('public')->exists($dossier)) {
            Storage::disk('public')->makeDirectory($dossier);
        }

        $image = $this->chargerImage($file->getRealPath());
        $image = $this->redimensionner($image, $largeurMax);
        $this->sauvegarderWebp($image, $cheminComplet, $qualite);
        imagedestroy($image);

        return $chemin;
    }

    /**
     * Upload une vignette carrée (crop centré)
     */
    public function uploadVignette(UploadedFile $file, string $dossier, int $taille = 400): string
    {
        $nomFichier = Str::random(40) . '.webp';
        $chemin = $dossier . '/' . $nomFichier;
        $cheminComplet = Storage::disk('public')->path($chemin);

        if (!Storage::disk('public')->exists($dossier)) {
            Storage::disk('public')->makeDirectory($dossier);
        }

        $source = $this->chargerImage($file->getRealPath());
        $largeurSrc = imagesx($source);
        $hauteurSrc = imagesy($source);

        // Calcul du crop centré
        $cote = min($largeurSrc, $hauteurSrc);
        $x = (int) (($largeurSrc - $cote) / 2);
        $y = (int) (($hauteurSrc - $cote) / 2);

        $vignette = imagecreatetruecolor($taille, $taille);
        imagealphablending($vignette, false);
        imagesavealpha($vignette, true);

        imagecopyresampled(
            $vignette, $source,
            0, 0, $x, $y,
            $taille, $taille, $cote, $cote
        );

        $this->sauvegarderWebp($vignette, $cheminComplet, 85);

        imagedestroy($source);
        imagedestroy($vignette);

        return $chemin;
    }

    /**
     * Supprimer une image
     */
    public function supprimer(?string $chemin): void
    {
        if ($chemin && Storage::disk('public')->exists($chemin)) {
            Storage::disk('public')->delete($chemin);
        }
    }

    /**
     * Charge une image depuis un fichier (JPG, PNG, WebP, GIF)
     */
    private function chargerImage(string $chemin)
    {
        $type = exif_imagetype($chemin);

        return match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($chemin),
            IMAGETYPE_PNG  => imagecreatefrompng($chemin),
            IMAGETYPE_GIF  => imagecreatefromgif($chemin),
            IMAGETYPE_WEBP => imagecreatefromwebp($chemin),
            default => throw new RuntimeException('Format d\'image non supporté.'),
        };
    }

    /**
     * Redimensionne proportionnellement si l'image est trop large
     */
    private function redimensionner($image, int $largeurMax)
    {
        $largeur = imagesx($image);
        $hauteur = imagesy($image);

        if ($largeur <= $largeurMax) {
            return $image;
        }

        $nouvelleLargeur = $largeurMax;
        $nouvelleHauteur = (int) (($hauteur / $largeur) * $nouvelleLargeur);

        $nouvelle = imagecreatetruecolor($nouvelleLargeur, $nouvelleHauteur);

        // Préserver la transparence (PNG)
        imagealphablending($nouvelle, false);
        imagesavealpha($nouvelle, true);

        imagecopyresampled(
            $nouvelle, $image,
            0, 0, 0, 0,
            $nouvelleLargeur, $nouvelleHauteur,
            $largeur, $hauteur
        );

        imagedestroy($image);

        return $nouvelle;
    }

    /**
     * Sauvegarde une image en WebP
     */
    private function sauvegarderWebp($image, string $cheminComplet, int $qualite = 85): void
    {
        if (!function_exists('imagewebp')) {
            // Fallback JPEG si WebP n'est pas dispo
            imagejpeg($image, $cheminComplet, $qualite);
            return;
        }

        imagewebp($image, $cheminComplet, $qualite);
    }
}