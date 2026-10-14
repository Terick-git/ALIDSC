<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Reçu de don — {{ $donation->reference }}</title>
<style>
    @page { margin: 0; }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 11pt;
        color: #22384D;
        line-height: 1.5;
    }

    /* ─── En-tête ────────────────────────────────────── */
    .header {
        background: #10263B;
        color: #FFFFFF;
        padding: 30px 40px;
    }
    .header table { width: 100%; }
    .header-left  { text-align: left;  vertical-align: middle; }
    .header-right { text-align: right; vertical-align: middle; }

    .logo {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: #FFFFFF;
        padding: 3px;
    }
    .brand {
        display: inline-block;
        vertical-align: middle;
        margin-left: 12px;
    }
    .brand-name {
        font-size: 20pt;
        font-weight: bold;
        color: #FFFFFF;
        letter-spacing: -0.5px;
    }
    .brand-tag {
        font-size: 8pt;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.55);
        margin-top: 2px;
    }
    .doc-type {
        font-size: 9pt;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.6);
        margin-bottom: 4px;
    }
    .doc-title {
        font-size: 22pt;
        font-weight: bold;
        color: #FFFFFF;
        letter-spacing: -0.5px;
    }

    /* ─── Corps ──────────────────────────────────────── */
    .content { padding: 40px; }

    .meta-table {
        width: 100%;
        margin-bottom: 30px;
        border-bottom: 2px solid #10263B;
        padding-bottom: 20px;
    }
    .meta-table td {
        vertical-align: top;
        padding: 0;
    }
    .meta-table .left  { text-align: left; }
    .meta-table .right { text-align: right; }

    .meta-label {
        font-size: 8pt;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #6B7E92;
        margin-bottom: 4px;
    }
    .meta-value {
        font-size: 11pt;
        font-weight: bold;
        color: #10263B;
    }
    .meta-value-mono {
        font-family: DejaVu Sans Mono, monospace;
        font-size: 10pt;
        color: #10263B;
    }

    /* ─── Donateur ──────────────────────────────────── */
    .section-title {
        font-size: 9pt;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #0B6FB8;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #CFDCE8;
    }

    .info-table {
        width: 100%;
        margin-bottom: 30px;
    }
    .info-table td {
        padding: 8px 0;
        vertical-align: top;
        border-bottom: 1px solid #E4ECF3;
    }
    .info-table td.label {
        width: 40%;
        color: #6B7E92;
        font-size: 10pt;
    }
    .info-table td.value {
        font-weight: bold;
        color: #10263B;
        font-size: 10pt;
    }

    /* ─── Montant ──────────────────────────────────── */
    .amount-box {
        background: #EEF5FB;
        border-left: 5px solid #0B6FB8;
        padding: 25px 30px;
        margin-bottom: 30px;
    }
    .amount-label {
        font-size: 9pt;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #6B7E92;
        margin-bottom: 6px;
    }
    .amount-value {
        font-size: 32pt;
        font-weight: bold;
        color: #10263B;
        letter-spacing: -1px;
        line-height: 1;
    }
    .amount-impact {
        font-size: 11pt;
        color: #22384D;
        margin-top: 12px;
    }
    .amount-impact strong { color: #1F7A50; }

    /* ─── Mention ──────────────────────────────────── */
    .mention {
        background: #F4F8FC;
        border: 1px solid #CFDCE8;
        padding: 18px 20px;
        margin-bottom: 30px;
        font-size: 10pt;
        color: #22384D;
        line-height: 1.6;
    }
    .mention strong { color: #10263B; }

    /* ─── Pied ─────────────────────────────────────── */
    .footer {
        border-top: 1px solid #CFDCE8;
        padding: 25px 40px;
        font-size: 8.5pt;
        color: #6B7E92;
        text-align: center;
        line-height: 1.6;
    }
    .footer strong {
        color: #10263B;
        font-size: 9pt;
        letter-spacing: 1px;
    }
    .footer-slogan {
        color: #0B6FB8;
        font-style: italic;
        margin-top: 6px;
    }
    .footer-legal {
        margin-top: 12px;
        font-size: 7.5pt;
        color: #9AA9B8;
    }
</style>
</head>
<body>

{{-- ═══════════════════════════════════════════════════
     EN-TÊTE
     ═══════════════════════════════════════════════════ --}}
<div class="header">
    <table>
        <tr>
            <td class="header-left">
                <img src="{{ public_path('images/logo-alidsc.jpg') }}" alt="ALIDSC" class="logo">
                <div class="brand">
                    <div class="brand-name">ALIDSC</div>
                    <div class="brand-tag">Étudiez · Rêvez · Réussissez</div>
                </div>
            </td>
            <td class="header-right">
                <div class="doc-type">Reçu officiel</div>
                <div class="doc-title">Don</div>
            </td>
        </tr>
    </table>
</div>

{{-- ═══════════════════════════════════════════════════
     CORPS
     ═══════════════════════════════════════════════════ --}}
<div class="content">

    {{-- Méta --}}
    <table class="meta-table">
        <tr>
            <td class="left">
                <div class="meta-label">Référence</div>
                <div class="meta-value-mono">{{ $donation->reference }}</div>
            </td>
            <td class="right">
                <div class="meta-label">Date du don</div>
                <div class="meta-value">
                    {{ ($donation->paid_at ?? $donation->created_at)->translatedFormat('d F Y') }}
                </div>
            </td>
        </tr>
    </table>

    {{-- Donateur --}}
    <div class="section-title">Donateur</div>
    <table class="info-table">
        <tr>
            <td class="label">Nom complet</td>
            <td class="value">{{ $donation->nom }}</td>
        </tr>
        <tr>
            <td class="label">Email</td>
            <td class="value">{{ $donation->email }}</td>
        </tr>
        <tr>
            <td class="label">Téléphone</td>
            <td class="value">{{ $donation->telephone }}</td>
        </tr>
    </table>

    {{-- Montant --}}
    <div class="amount-box">
        <div class="amount-label">Montant du don</div>
        <div class="amount-value">{{ $donation->montant_formate }}</div>
        <div class="amount-impact">
            Équivaut à <strong>{{ $donation->kit_equivalent }}
            {{ $donation->kit_equivalent > 1 ? 'kits scolaires' : 'kit scolaire' }}</strong>
            pour {{ $donation->kit_equivalent > 1 ? 'des enfants' : 'un enfant' }} de Maroua.
        </div>
    </div>

    {{-- Détails paiement --}}
    <div class="section-title">Détails du paiement</div>
    <table class="info-table">
        <tr>
            <td class="label">Moyen de paiement</td>
            <td class="value">{{ $donation->moyen_label }}</td>
        </tr>
        <tr>
            <td class="label">Statut</td>
            <td class="value" style="color: #1F7A50;">✓ Payé</td>
        </tr>
        @if($donation->cinetpay_transaction_id)
        <tr>
            <td class="label">Transaction CinetPay</td>
            <td class="value" style="font-family: DejaVu Sans Mono, monospace; font-size: 9pt;">
                {{ $donation->cinetpay_transaction_id }}
            </td>
        </tr>
        @endif
    </table>

    {{-- Mention légale --}}
    <div class="mention">
        <strong>Reçu de don officiel.</strong>
        L'ALIDSC est une association à but non lucratif régie par la Loi N°90/053 du 19 décembre 1990.
        Ce reçu atteste de votre générosité et de son utilisation pour nos actions de terrain à Maroua.
        Merci de votre engagement à nos côtés.
    </div>

</div>

{{-- ═══════════════════════════════════════════════════
     PIED
     ═══════════════════════════════════════════════════ --}}
<div class="footer">
    <strong>ALIDSC</strong> — Association de Lutte contre les Inégalités et les Décrochages Scolaires au Cameroun
    <div class="footer-slogan">« Étudiez, Rêvez, Réussissez. »</div>
    <div class="footer-legal">
        Maroua, Pitoire (Ancien décanat), derrière la CNPS — Extrême-Nord, Cameroun<br>
        contact@alidsc.org — 690 856 383 — alidsc.org
    </div>
</div>

</body>
</html>