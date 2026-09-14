<?php

return [

    /*
     * Segment d'URL pour le portail d'administration. Volontairement absent de
     * toute navigation publique — communiqué directement à l'équipe interne
     * (lien envoyé par email/Slack, jamais un lien cliquable sur le site).
     * A definir en production dans .env avec une valeur non devinable.
     */
    'admin_path' => env('ADMIN_PANEL_PATH', 'andadoo-portail-9f3k'),

    /*
     * Numeros marchands mobile money affiches au client sur la page de
     * paiement. En attendant un agregateur (CinetPay/PayDunya), le client
     * envoie manuellement puis declare la transaction pour verification.
     */
    'payment_numbers' => [
        'wave' => env('WAVE_MERCHANT_NUMBER'),
        'orange_money' => env('ORANGE_MONEY_MERCHANT_NUMBER'),
        'free_money' => env('FREE_MONEY_MERCHANT_NUMBER'),
    ],

];
