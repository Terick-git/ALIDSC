<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ImageService
{
    /**
     * Upload et optimise une image (redimension + conversion WebP si dispo).
     *
     * @return string Chemin relatif (avec extension cohérente au contenu réel)
     */
    public function uploadOptimise(UploadedFile $file, string $dossier, int $largeurMax = 1920, int $qualite = 85): string
    {
        $baseSansExt   = $dossier . '/' . Str::random(40);
        $cheminComplet = Storage::disk('public')->path($baseSansExt);

        if (!Storage::disk('public')->exists($dossier)) {
            Storage::disk('public')->makeDirectory($dossier);
        }

        $image = $this->chargerImage($file->getRealPath());
        $image = $this->redimensionner($image, $largeurMax);
        $ext   = $this->sauvegarder($image, $cheminComplet, $qualite);
        imagedestroy($image);

        return $baseSansExt . '.' . $ext;
    }

    /**
     * Upload une vignette carrée (crop centré).
     */
    public function uploadVignette(UploadedFile $file, string $dossier, int $taille = 400): string
    {
        $baseSansExt   = $dossier . '/' . Str::random(40);
        $cheminComplet = Storage::disk('public')->path($baseSansExt);

        if (!Storage::disk('public')->exists($dossier)) {
            Storage::disk('public')->makeDirectory($dossier);
        }

        $source     = $this->chargerImage($file->getRealPath());
        $largeurSrc = imagesx($source);
        $hauteurSrc = imagesy($source);

        $cote = min($largeurSrc, $hauteurSrc);
        $x    = (int) (($largeurSrc - $cote) / 2);
        $y    = (int) (($hauteurSrc - $cote) / 2);

        $vignette = imagecreatetruecolor($taille, $taille);
        imagealphablending($vignette, false);
        imagesavealpha($vignette, true);

        imagecopyresampled(
            $vignette, $source,
            0, 0, $x, $y,
            $taille, $taille, $cote, $cote
        );

        $ext = $this->sauvegarder($vignette, $cheminComplet, 85);

        imagedestroy($source);
        imagedestroy($vignette);

        return $baseSansExt . '.' . $ext;
    }

    public function supprimer(?string $chemin): void
    {
        if ($chemin && Storage::disk('public')->exists($chemin)) {
            Storage::disk('public')->delete($chemin);
        }
    }

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
     * Sauvegarde l'image dans le format le plus adapté.
     *
     * @return string Extension réellement écrite ('webp' ou 'jpg')
     */
    private function sauvegarder($image, string $cheminBaseSansExt, int $qualite = 85): string
    {
        // WebP si disponible (PHP >= 5.4 avec GD compilé)
        if (function_exists('imagewebp')) {
            imagewebp($image, $cheminBaseSansExt . '.webp', $qualite);
            return 'webp';
        }

        // Fallback JPEG (universel)
        imagejpeg($image, $cheminBaseSansExt . '.jpg', $qualite);
        return 'jpg';
    }
}