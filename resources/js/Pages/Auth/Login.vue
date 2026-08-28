<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AuthSplitLayout from '@/Layouts/AuthSplitLayout.vue';
import IconInput from '@/Components/IconInput.vue';

defineProps({ status: String });

const form = useForm({ email: '', password: '', remember: false });

function submit() {
    form.post('/login', { onFinish: () => form.reset('password') });
}

const features = [
    'Chauffeur GO\u2019CAR qui vous reconnaît à la sortie',
    'Un seul contrat pour tout le séjour',
    'Paiement sécurisé, carte ou mobile money',
];
</script>

<template>
    <AuthSplitLayout
        eyebrow="Carte d'accès Andadoo"
        title="Votre place vous attend."
        subtitle="Connectez-vous pour retrouver vos réservations et organiser votre prochaine arrivée à Dakar."
    >
        <template #aside>
            <ul class="space-y-3">
                <li v-for="f in features" :key="f" class="flex items-start gap-2 text-sm text-paper-100/80">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="mt-0.5 h-4 w-4 shrink-0 text-gold-400">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                    </svg>
                    {{ f }}
                </li>
            </ul>
        </template>

        <p class="font-display text-xs font-semibold uppercase tracking-widest text-gold-600 lg:hidden">Andadoo</p>
        <h2 class="mt-2 font-display text-2xl font-bold text-forest-500">Se connecter</h2>
        <p class="mt-1 text-sm text-forest-500/60">Accédez à votre espace pour réserver.</p>

        <div v-if="status" class="mt-4 rounded-lg bg-gold-500/10 px-4 py-2 text-sm text-gold-700">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="mt-7 space-y-5">
            <IconInput v-model="form.email" type="email" icon="mail" label="E-mail" placeholder="vous@exemple.com" autofocus :error="form.errors.email" />
            <IconInput v-model="form.password" type="password" icon="lock" label="Mot de passe" placeholder="••••••••" :error="form.errors.password" />

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 text-forest-500">
                    <input v-model="form.remember" type="checkbox" class="rounded border-forest-500/30 text-gold-500 focus:ring-gold-500" />
                    Rester connecté
                </label>
                <Link href="/forgot-password" class="font-display font-medium text-forest-500 hover:text-gold-600">Mot de passe oublié ?</Link>
            </div>

            <button type="submit" :disabled="form.processing" class="btn-primary w-full">
                <span v-if="!form.processing">Se connecter</span>
                <span v-else class="flex items-center gap-2">
                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" /><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z" /></svg>
                    Connexion...
                </span>
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-forest-500/70">
            Pas encore de compte ?
            <Link href="/register" class="font-display font-semibold text-gold-600 hover:text-gold-700">Créer un compte</Link>
        </p>
    </AuthSplitLayout>
</template>
