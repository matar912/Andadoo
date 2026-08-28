<?php

return [

    /*
     * Segment d'URL pour le portail d'administration. Volontairement absent de
     * toute navigation publique — communiqué directement à l'équipe interne
     * (lien envoyé par email/Slack, jamais un lien cliquable sur le site).
     * A definir en production dans .env avec une valeur non devinable.
     */
    'admin_path' => env('ADMIN_PANEL_PATH', 'andadoo-portail-9f3k'),

];
