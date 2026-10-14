<?php

namespace App\Services;

use App\Models\Donation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class CinetPayService
{
    public function __construct(
        protected string $apiKey = '',
        protected string $siteId = '',
        protected string $secret = '',
        protected string $baseUrl = '',
        protected string $currency = 'XOF',
        protected string $channels = 'ALL',
    ) {
        $this->apiKey   = config('services.cinetpay.apikey', '');
        $this->siteId   = config('services.cinetpay.site_id', '');
        $this->secret   = config('services.cinetpay.secret', '');
        $this->baseUrl  = rtrim(config('services.cinetpay.base_url', 'https://api-checkout.cinetpay.com/v2'), '/');
        $this->currency = config('services.cinetpay.currency', 'XOF');
        $this->channels = config('services.cinetpay.channels', 'ALL');
    }

    /**
     * Initialise une transaction CinetPay et retourne payment_url.
     *
     * @return array{success: bool, payment_url?: string, token?: string, message: string}
     */
    public function initialiser(Donation $donation): array
    {
        // ⚠️ URL signée valable 24h : impossible pour un tiers de deviner
        //    l'URL de retour et de consulter les données du donateur.
        $returnUrl = URL::temporarySignedRoute(
            'don.merci',
            now()->addHours(24),
            ['reference' => $donation->reference]
        );

        $payload = [
            'apikey'        => $this->apiKey,
            'site_id'       => $this->siteId,
            'transaction_id' => $donation->reference,
            'amount'        => $donation->montant,
            'currency'      => $this->currency,
            'description'   => 'Don ALIDSC — ' . $donation->reference,
            'return_url'    => $returnUrl,
            'notify_url'    => route('don.webhook'),
            'channels'      => $this->channels,
            'lang'          => 'fr',
            'customer_name'     => $donation->nom,
            'customer_email'    => $donation->email,
            'customer_phone_number' => $donation->telephone,
            'customer_address'  => 'Maroua',
            'customer_city'     => 'Maroua',
            'customer_country'  => 'CM',
            'customer_state'    => 'Extrême-Nord',
            'customer_zip_code' => '00000',
        ];

        try {
            $response = Http::timeout(20)
                ->acceptJson()
                ->post($this->baseUrl . '/payment', $payload);

            $data = $response->json();

            if (($data['code'] ?? null) === '201' && !empty($data['data']['payment_url'])) {
                return [
                    'success'     => true,
                    'payment_url' => $data['data']['payment_url'],
                    'token'       => $data['data']['payment_token'] ?? null,
                    'message'     => $data['message'] ?? 'OK',
                ];
            }

            Log::warning('CinetPay init failed', ['response' => $data, 'donation_id' => $donation->id]);

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Erreur inconnue CinetPay',
            ];
        } catch (\Throwable $e) {
            Log::error('CinetPay exception', [
                'donation_id' => $donation->id,
                'exception'   => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => 'Impossible de joindre CinetPay.'];
        }
    }

    public function verifier(string $transactionId): array
    {
        try {
            $response = Http::timeout(20)
                ->acceptJson()
                ->post($this->baseUrl . '/payment/check', [
                    'apikey'         => $this->apiKey,
                    'site_id'        => $this->siteId,
                    'transaction_id' => $transactionId,
                ]);

            $data = $response->json();

            return [
                'success' => ($data['code'] ?? null) === '00',
                'statut'  => $data['data']['status'] ?? 'UNKNOWN',
                'data'    => $data,
            ];
        } catch (\Throwable $e) {
            Log::error('CinetPay check exception', [
                'transaction_id' => $transactionId,
                'exception'      => $e->getMessage(),
            ]);

            return ['success' => false, 'statut' => 'ERROR', 'data' => []];
        }
    }
}