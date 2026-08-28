# Andadoo — Guide d'installation (à suivre dans l'ordre)

Ce dossier contient uniquement les fichiers **métier** du projet Andadoo (migrations, modèles,
contrôleurs, pages Vue). Il vient se greffer sur une installation Laravel + Breeze standard.
Suivez les étapes **dans l'ordre**, sans en sauter — la plupart des erreurs rencontrées viennent
d'une étape oubliée.

## Étape 1 — Créer le projet Laravel de base

```bash
composer create-project laravel/laravel andadoo
cd andadoo
composer require laravel/breeze --dev
php artisan breeze:install vue
npm install
```

## Étape 2 — Copier les fichiers de ce scaffold

Copiez ces fichiers **en écrasant** ceux du projet fraîchement créé quand ils existent déjà :

| Fichier / dossier du scaffold | Destination dans votre projet |
|---|---|
| `database/migrations/2026_01_01_*.php` | `database/migrations/` |
| `app/Models/*.php` | `app/Models/` |
| `app/Http/Controllers/*.php` (sauf `Admin/` et `Auth/`) | `app/Http/Controllers/` |
| `app/Http/Controllers/Admin/*.php` | `app/Http/Controllers/Admin/` |
| `app/Http/Controllers/Auth/*.php` | `app/Http/Controllers/Auth/` **(écrase les fichiers Breeze — copiez le fichier ENTIER, ne cherchez pas la ligne à changer)** |
| `app/Http/Middleware/EnsureUserHasRole.php` | `app/Http/Middleware/` |
| `app/Console/Commands/MakeAdminCommand.php` | `app/Console/Commands/` |
| `config/andadoo.php` | `config/` |
| `routes/web.php` | `routes/` **(écrase celui de Breeze)** |
| `resources/js/Pages/*` | `resources/js/Pages/` |
| `resources/js/Layouts/*` | `resources/js/Layouts/` |
| `resources/js/Components/*` | `resources/js/Components/` |
| `resources/js/composables/*` | `resources/js/composables/` |
| `resources/css/app.css` | `resources/css/app.css` |
| `tailwind.config.js` | `tailwind.config.js` |

## Étape 3 — Enregistrer le middleware de rôle

Dans `bootstrap/app.php` :

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias(['role' => \App\Http\Middleware\EnsureUserHasRole::class]);
})
```

## Étape 4 — Partager le rôle et le lien admin avec le frontend

Dans `app/Http/Middleware/HandleInertiaRequests.php`, remplacez la méthode `share()` par :

```php
public function share(Request $request): array
{
    return [
        ...parent::share($request),
        'auth' => ['user' => $request->user()?->only('id', 'name', 'email', 'role')],
        'adminPath' => config('andadoo.admin_path'),
        'flash' => ['success' => fn () => $request->session()->get('success')],
    ];
}
```

## Étape 5 — Base de données

```bash
cp .env.example .env
php artisan key:generate
```
Configurez `DB_*` dans `.env` (PostgreSQL ou MySQL), puis :
```bash
php artisan migrate
```

## Étape 5bis — Photos de véhicules

Les photos uploadées par l'admin sont servies directement par Laravel via une route dédiée
(`/vehicule-photo/{chemin}`), **sans lien symbolique `storage:link`** — ce mécanisme pose souvent
problème sous Windows sans droits administrateur. Rien à configurer, ça fonctionne dès l'upload.

## Étape 6 — Créer votre compte administrateur

**C'est la seule façon de créer un admin — il n'y a volontairement aucune inscription admin
publique**, contrairement aux clients qui s'inscrivent librement sur `/register`.

```bash
php artisan andadoo:make-admin
```
La commande vous demande nom / e-mail / mot de passe en interactif et crée le compte
(`role = admin`). Si l'e-mail existe déjà comme compte client, elle propose de le promouvoir.

## Étape 7 — Définir le lien admin secret

Dans `.env` :
```
ADMIN_PANEL_PATH=un-segment-non-devinable
```
Sans cette ligne, un chemin par défaut (`andadoo-portail-9f3k`) est utilisé — changez-le avant la
mise en production. Ce chemin n'est **jamais affiché** nulle part sur le site public : c'est ce
qui le rend structurellement différent de l'accès visiteur, en plus d'avoir son propre écran de
connexion et son propre contrôle de rôle.

## Étape 8 — Lancer le projet

```bash
npm run dev
# dans un autre terminal :
php artisan serve
```

- Site client : http://127.0.0.1:8000
- Portail admin : http://127.0.0.1:8000/{ADMIN_PANEL_PATH}/login

---

## Comment ça fonctionne (les deux circuits d'accès)

| | **Client** | **Administrateur** |
|---|---|---|
| Entrée | `/register`, `/login` — liés dans le menu du site | `/{ADMIN_PANEL_PATH}/login` — **jamais lié nulle part** dans l'interface |
| Création de compte | Auto-inscription publique | Uniquement via `php artisan andadoo:make-admin` |
| Ce qu'il peut faire | Parcourir les véhicules **disponibles**, réserver, suivre ses réservations, éditer son profil | Ajouter/modifier/retirer des véhicules, valider ou refuser chaque réservation |
| Garde-fou technique | Route protégée par `auth` + `verified` | Route protégée par `auth` + `role:admin` ; le login public refuse toute session pour un compte admin, et réciproquement le login admin refuse un compte client |

**Le flux métier central**, côté produit :
1. L'admin ajoute des véhicules et les marque `disponible` (`/{admin}/vehicules`) — c'est **la
   seule** source de vérité sur ce qu'un client peut voir et réserver.
2. Un client connecté réserve un véhicule disponible → la réservation naît `en_attente`, **jamais
   confirmée automatiquement**.
3. L'admin valide ou refuse chaque demande (`/{admin}/reservations`).
4. Le client suit le statut de sa demande sur sa propre page de réservation (et uniquement la
   sienne — un contrôle d'accès empêche de consulter la réservation d'un autre client).

## À propos du paiement

**Le paiement n'est pas implémenté pour l'instant, volontairement.** Les tables `payments` et le
modèle `Payment` existent dans la base (migration incluse) pour ne pas avoir à re-modéliser plus
tard, mais aucune page ni aucun contrôleur ne les utilise aujourd'hui : une réservation se crée et
se valide sans paiement en ligne. Quand vous serez prêt à l'activer (Stripe pour les cartes,
PayDunya/CinetPay pour Wave/Orange Money), il suffira de brancher un `PaymentController` sur les
réservations confirmées — aucune migration à refaire.

## Modèle de données

Voir le diagramme ERD fourni dans la conversation. Points clés :
- Aucune table ne lie un véhicule à un propriétaire tiers : la flotte est 100% Andadoo.
- `drivers` référence `users` (role = `driver`) : ce sont des employés, pas des comptes externes.
- `partners` sert uniquement à la distribution commerciale (agences, hôtels), jamais à l'apport de véhicules.

## Fondations UX ajoutées

- **`composables/useToast.js`** + **`Components/ToastContainer.vue`** : notifications non-bloquantes
  qui remplacent les `alert()` navigateur. Déjà branchées dans `AppLayout` et `AdminLayout` — tout
  message flash Laravel (`->with('success', ...)`) devient automatiquement un toast.
- **`Components/ConfirmDialog.vue`** : modale de confirmation stylée, utilisée pour les actions
  destructives (retrait d'un véhicule, refus d'une réservation) à la place de `confirm()`.
- **`Components/ReservationTimeline.vue`** : visualisation en étapes du statut d'une réservation.
- Menu mobile (hamburger) dans `AppLayout.vue` — absent jusqu'ici, corrigé.
- États de chargement (skeletons) et état vide actionnable dans le catalogue (`Vehicles/Index.vue`).

## Corrections et ajouts de cette session

- **Disponibilité par dates, pas par statut global** : un véhicule "disponible" peut désormais
  être déjà réservé du 12 au 15 tout en restant réservable du 20 au 25. La validation d'une
  réservation par l'admin ne bloque plus plus le véhicule entier — c'est
  `Vehicle::isAvailableBetween()` qui vérifie les chevauchements de dates à chaque nouvelle
  demande. Le formulaire de réservation affiche aussi les périodes déjà prises.
- **Notifications dédoublées corrigées** : chaque action admin (valider/refuser une réservation,
  retirer un véhicule) ne déclenche plus qu'un seul toast au lieu de deux.
- **Retrait d'un véhicule déjà réservé** : l'action fonctionnait déjà côté serveur mais rien ne le
  montrait — la ligne s'estompe visuellement désormais et le bouton "Retirer" disparaît.
- **Navigation repensée** : les liens de navigation (client et admin) sont désormais un rail
  vertical fixé à droite de l'écran (à partir des écrans larges), avec un repère de couleur sur
  l'onglet actif. En dessous de cette taille d'écran, un menu déroulant reste accessible via le
  bouton hamburger.
- **Page "Mes réservations"** ajoutée côté client (`/mes-reservations`).
- **Page de détail véhicule** ajoutée (`/vehicules/{id}`), avec la photo en grand.
- Petites transitions et retours tactiles (boutons, lignes de tableau) pour une interface plus fluide.

1. Seeders de démonstration (quelques véhicules + réservations d'exemple) pour tester sans tout saisir à la main.
2. Étendre le style "wizard" (étapes) au formulaire de réservation lui-même (actuellement un seul long formulaire).
3. Tableaux admin : recherche, tri, pagination visible (actuellement pagination silencieuse côté backend).
4. Activation du paiement (voir ci-dessus) quand le besoin sera confirmé.
5. 2FA sur le compte admin (Laravel Fortify) avant mise en production.

## Identité visuelle

- **Logo** : `public/images/logo-icon.png` (le mark seul, utilisé dans les en-têtes et le rail
  admin) et `public/images/logo-full.png` (mark + nom, disponible pour d'autres usages : documents,
  e-mails). Une fois le scaffold copié dans votre projet Laravel, ces deux fichiers doivent être
  copiés dans `public/images/` pour que les balises `<img src="/images/...">` les trouvent.
- **Palette** (`tailwind.config.js`) : `forest` (vert profond, texte et fonds sombres), `paper`
  (fond clair chaud), `gold` (accent). Extraite directement des couleurs du logo fourni.
- **Typographie** : Space Grotesk (titres) + Inter (texte courant), inchangée.

## Prochaines étapes suggérées

1. Seeders de démonstration (quelques véhicules + réservations d'exemple) pour tester sans tout saisir à la main.
2. Étendre le style "wizard" (étapes) au formulaire de réservation lui-même (actuellement un seul long formulaire).
3. Tableaux admin : recherche, tri, pagination visible (actuellement pagination silencieuse côté backend).
4. Activation du paiement quand le besoin sera confirmé.
5. 2FA sur le compte admin (Laravel Fortify) avant mise en production.
