<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({ email: '', password: '', remember: false });

function submit() {
    form.post(window.location.pathname, { onFinish: () => form.reset('password') });
}
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-forest-900 px-6">
        <div class="w-full max-w-sm rounded-xl2 border border-white/10 bg-forest-700 p-8">
            <span class="mb-4 flex h-10 w-10 items-center justify-center rounded-full bg-paper-50 p-1.5">
                <img src="/images/logo-icon.png" alt="Andadoo" class="h-full w-full object-contain" />
            </span>
            <p class="font-display text-xs font-semibold uppercase tracking-widest text-paper-300">Andadoo</p>
            <h1 class="mt-1 font-display text-xl font-bold text-white">Portail interne</h1>
            <p class="mt-1 text-sm text-paper-100/60">Accès réservé aux administrateurs Andadoo.</p>

            <form @submit.prevent="submit" class="mt-6 space-y-4">
                <div>
                    <label class="text-xs font-display font-semibold uppercase tracking-wide text-paper-100/60">Identifiant</label>
                    <input v-model="form.email" type="email" required autofocus
                        class="mt-1 w-full rounded-lg border-white/10 bg-forest-900 text-white placeholder-paper-100/30 focus:border-gold-400 focus:ring-gold-400" />
                    <p v-if="form.errors.email" class="mt-1 text-xs text-gold-400">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label class="text-xs font-display font-semibold uppercase tracking-wide text-paper-100/60">Mot de passe</label>
                    <input v-model="form.password" type="password" required
                        class="mt-1 w-full rounded-lg border-white/10 bg-forest-900 text-white focus:border-gold-400 focus:ring-gold-400" />
                    <p v-if="form.errors.password" class="mt-1 text-xs text-gold-400">{{ form.errors.password }}</p>
                </div>

                <label class="flex items-center gap-2 text-sm text-paper-100/70">
                    <input v-model="form.remember" type="checkbox" class="rounded border-white/20 bg-forest-900 text-gold-500 focus:ring-gold-400" />
                    Rester connecté sur cet appareil
                </label>

                <button type="submit" :disabled="form.processing"
                    class="w-full rounded-full bg-gold-500 py-3 font-display font-semibold text-white transition hover:bg-gold-600 disabled:opacity-60">
                    Accéder au tableau de bord
                </button>
            </form>
        </div>
    </div>
</template>
