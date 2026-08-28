<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MakeAdminCommand extends Command
{
    // Seul moyen de creer un compte admin : jamais via une page publique.
    // Usage : php artisan andadoo:make-admin
    protected $signature = 'andadoo:make-admin';

    protected $description = "Cree (ou promeut) un compte administrateur Andadoo";

    public function handle(): int
    {
        $this->info("Creation d'un compte administrateur Andadoo");
        $this->line('---------------------------------------------');

        $name = $this->ask('Nom complet');
        $email = $this->ask('E-mail');
        $password = $this->secret('Mot de passe (min. 8 caracteres)');
        $confirm = $this->secret('Confirmer le mot de passe');

        $validator = Validator::make(compact('name', 'email', 'password'), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());

            return self::FAILURE;
        }

        if ($password !== $confirm) {
            $this->error('Les deux mots de passe ne correspondent pas.');

            return self::FAILURE;
        }

        $existing = User::where('email', $email)->first();

        if ($existing) {
            if (! $this->confirm("Un compte avec cet e-mail existe deja (role actuel : {$existing->role}). Le promouvoir admin et changer son mot de passe ?")) {
                $this->warn('Annule.');

                return self::SUCCESS;
            }

            $existing->update(['role' => 'admin', 'password' => Hash::make($password)]);
            $this->info("✔ {$existing->email} est maintenant administrateur.");

            return self::SUCCESS;
        }

        User::create([
            'uuid' => (string) Str::uuid(),
            'name' => $name,
            'email' => $email,
            'role' => 'admin',
            'password' => Hash::make($password),
        ]);

        $this->info("✔ Compte administrateur cree pour {$email}.");
        $this->line('Connectez-vous via : /'.config('andadoo.admin_path').'/login');

        return self::SUCCESS;
    }
}
