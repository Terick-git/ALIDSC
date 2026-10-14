<?php

namespace App\Services;

use Illuminate\Support\Str;

class TwoFactorService
{
    protected const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    protected const DIGITS = 6;
    protected const PERIOD = 30;
    protected const WINDOW = 1; // ±1 période (±30s)

    /**
     * Génère un secret base32 de 32 caractères (160 bits).
     */
    public function generateSecret(): string
    {
        $random = random_bytes(20);
        return $this->base32Encode($random);
    }

    /**
     * Vérifie un code TOTP pour un secret donné.
     * Accepte le code actuel, ±1 période (tolérance de 30s).
     */
    public function verify(string $secret, string $code): bool
    {
        $code = preg_replace('/\s+/', '', $code);

        if (!ctype_digit($code) || strlen($code) !== self::DIGITS) {
            return false;
        }

        $now = time();

        for ($i = -self::WINDOW; $i <= self::WINDOW; $i++) {
            $timestamp = $now + ($i * self::PERIOD);
            if (hash_equals($this->generateCode($secret, $timestamp), $code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Génère l'URI otpauth:// pour les apps d'authentification.
     */
    public function getQrCodeUri(string $secret, string $email, string $issuer = 'ALIDSC'): string
    {
        $label = rawurlencode($issuer . ':' . $email);
        $params = http_build_query([
            'secret'    => $secret,
            'issuer'    => $issuer,
            'algorithm' => 'SHA1',
            'digits'    => self::DIGITS,
            'period'    => self::PERIOD,
        ]);

        return "otpauth://totp/{$label}?{$params}";
    }

    /**
     * Génère le SVG du QR code à partir de l'URI otpauth://.
     *
     * Génération 100 % locale (aucun secret n'est envoyé à un tiers).
     */
    public function getQrCodeSvg(string $otpauthUri, int $size = 220): string
    {
        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle($size),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );

        return (new \BaconQrCode\Writer($renderer))->writeString($otpauthUri);
    }

    /**
     * Génère 8 codes de secours (format XXXX-XXXX).
     */
    public function generateRecoveryCodes(int $count = 8): array
    {
        return collect(range(1, $count))
            ->map(fn () => strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)))
            ->all();
    }

    // ─────────────────────────────────────────────────────
    // Implémentation TOTP (RFC 6238)
    // ─────────────────────────────────────────────────────

    protected function generateCode(string $secret, int $timestamp): string
    {
        $counter = (int) floor($timestamp / self::PERIOD);
        $key     = $this->base32Decode($secret);
        $binary  = pack('N*', 0) . pack('N*', $counter);

        $hash   = hash_hmac('sha1', $binary, $key, true);
        $offset = ord($hash[19]) & 0x0F;

        $part = (
            ((ord($hash[$offset])     & 0x7F) << 24) |
            ((ord($hash[$offset + 1]) & 0xFF) << 16) |
            ((ord($hash[$offset + 2]) & 0xFF) << 8)  |
             (ord($hash[$offset + 3]) & 0xFF)
        );

        $otp = $part % (10 ** self::DIGITS);

        return str_pad((string) $otp, self::DIGITS, '0', STR_PAD_LEFT);
    }

    protected function base32Encode(string $input): string
    {
        $output   = '';
        $buffer   = 0;
        $bitsLeft = 0;

        for ($i = 0; $i < strlen($input); $i++) {
            $buffer = ($buffer << 8) | ord($input[$i]);
            $bitsLeft += 8;

            while ($bitsLeft >= 5) {
                $bitsLeft -= 5;
                $output .= self::ALPHABET[($buffer >> $bitsLeft) & 0x1F];
            }
        }

        if ($bitsLeft > 0) {
            $output .= self::ALPHABET[($buffer << (5 - $bitsLeft)) & 0x1F];
        }

        return $output;
    }

    protected function base32Decode(string $input): string
    {
        $input    = strtoupper(str_replace('=', '', $input));
        $output   = '';
        $buffer   = 0;
        $bitsLeft = 0;

        for ($i = 0; $i < strlen($input); $i++) {
            $pos = strpos(self::ALPHABET, $input[$i]);
            if ($pos === false) {
                continue;
            }

            $buffer = ($buffer << 5) | $pos;
            $bitsLeft += 5;

            if ($bitsLeft >= 8) {
                $bitsLeft -= 8;
                $output .= chr(($buffer >> $bitsLeft) & 0xFF);
            }
        }

        return $output;
    }
}