<?php
// Extrait a fusionner manuellement dans VOTRE config/services.php existant
// (ne remplace pas le fichier : ajoutez juste cette entree dans le tableau
// retourne par le fichier, a cote de 'postmark', 'resend', 'slack', etc.)

'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
],
