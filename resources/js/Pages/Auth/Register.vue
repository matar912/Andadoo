<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AuthSplitLayout from '@/Layouts/AuthSplitLayout.vue';
import IconInput from '@/Components/IconInput.vue';

const form = useForm({
    name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post('/register', { onFinish: () => form.reset('password', 'password_confirmation') });
}

const steps = [
    ['Créez votre compte', 'Une minute, depuis n\u2019importe quel pays.'],
    ['Réservez votre véhicule', 'Formule transfert, séjour ou location locale.'],
    ['Votre chauffeur vous attend', 'Nom et photo transmis avant votre arrivée.'],
];
</script>

<template>
    <AuthSplitLayout
        eyebrow="Nouveau passager"
        title="Embarquement en 3 étapes."
        subtitle="Créez votre compte pour réserver votre transfert et votre location, avant même d'avoir décollé."
    >
        <template #aside>
            <ol class="space-y-4">
                <li v-for="(s, i) in steps" :key="s[0]" class="flex gap-3">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full border border-runway-400/60 font-display text-xs font-semibold text-runway-400">
                        {{ i + 1 }}
                    </span>
                    <div>
                        <p class="font-display text-sm font-semibold text-white">{{ s[0] }}</p>
                        <p class="text-xs text-sand-100/60">{{ s[1] }}</p>
                    </div>
                </li>
            </ol>
        </template>

        <p class="font-display text-xs font-semibold uppercase tracking-widest text-runway-600 lg:hidden">GO'CAR</p>
        <h2 class="mt-2 font-display text-2xl font-bold text-night-500">Créer un compte</h2>
        <p class="mt-1 text-sm text-night-500/60">Diaspora, touriste ou résident : un seul compte suffit.</p>

        <form @submit.prevent="submit" class="mt-7 space-y-5">
            <IconInput v-model="form.name" icon="user" label="Nom complet" placeholder="Aïssatou Diop" autofocus :error="form.errors.name" />
            <IconInput v-model="form.email" type="email" icon="mail" label="E-mail" placeholder="vous@exemple.com" :error="form.errors.email" />
            <IconInput v-model="form.phone" type="tel" icon="phone" label="Téléphone (avec indicatif)" placeholder="+33 6 12 34 56 78" :error="form.errors.phone" />
            <IconInput v-model="form.password" type="password" icon="lock" label="Mot de passe" placeholder="8 caractères minimum" :error="form.errors.password" />
            <IconInput v-model="form.password_confirmation" type="password" icon="lock" label="Confirmer le mot de passe" placeholder="••••••••" />

            <button type="submit" :disabled="form.processing" class="btn-primary w-full">
                <span v-if="!form.processing">Créer mon compte</span>
                <span v-else class="flex items-center gap-2">
                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" /><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z" /></svg>
                    Création...
                </span>
            </button>

            <p class="text-center text-xs text-night-500/50">
                En créant un compte, vous acceptez que vos informations soient utilisées uniquement
                pour votre accueil et vos réservations GO'CAR.
            </p>
        </form>

        <p class="mt-6 text-center text-sm text-night-500/70">
            Déjà un compte ?
            <Link href="/login" class="font-display font-semibold text-runway-600 hover:text-runway-700">Se connecter</Link>
        </p>
    </AuthSplitLayout>
</template>
