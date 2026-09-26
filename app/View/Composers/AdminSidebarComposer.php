<?php

namespace App\View\Composers;

use App\Models\Adherent;
use App\Models\Message;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class AdminSidebarComposer
{
    public const CACHE_KEY = 'admin_sidebar_counts';
    public const CACHE_TTL = 60;

    public function compose(View $view): void
    {
        $view->with('sidebarCounts', self::getCounts());
    }

    public static function getCounts(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return [
                'messages_non_lus'     => Message::where('lu', false)->count(),
                'adherents_en_attente' => Adherent::where('statut_demande', 'en_attente')->count(),
            ];
        });
    }
}
