<?php

namespace App\Support;

use Mews\Purifier\Facades\Purifier;

class Html
{
    /**
     * Sanitize HTML from the WYSIWYG editor before rendering.
     *
     * Autorise uniquement les balises produites par TipTap : p, h2, h3,
     * strong, em, s, ul, ol, li, blockquote, a, hr, code, br.
     */
    public static function clean(?string $html): string
    {
        if (blank($html)) {
            return '';
        }

        return Purifier::clean($html, 'wysiwyg');
    }
}