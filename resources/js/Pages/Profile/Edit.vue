<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ user: Object });

const infoForm = useForm({
    name: props.user.name,
    email: props.user.email,
    phone: props.user.phone ?? '',
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

function saveInfo() {
    infoForm.put('/profil');
}
function savePassword() {
    passwordForm.put('/profil/mot-de-passe', {
        onSuccess: () => passwordForm.reset(),
    });
}
</script>

<template>
    <AppLayout>
        <section class="mx-auto max-w-2xl px-6 py-14">
            <h1 class="font-display text-2xl font-bold text-night-500">Mon profil</h1>
            <p class="mt-1 text-sm text-night-500/70">Vos informations, utilisées pour l'accueil à l'aéroport et vos réservations.</p>

            <form @submit.prevent="saveInfo" class="card mt-6 space-y-4 p-6">
                <h2 class="font-display font-semibold text-night-500">Informations personnelles</h2>
                <div>
                    <label class="text-xs font-display font-semibold uppercase tracking-wide text-night-300">Nom complet</label>
                    <input v-model="infoForm.name" type="text" required class="mt-1 w-full rounded-lg border-night-500/15 focus:border-runway-500 focus:ring-runway-500" />
                    <p v-if="infoForm.errors.name" class="mt-1 text-xs text-red-600">{{ infoForm.errors.name }}</p>
                </div>
                <div>
                    <label class="text-xs font-display font-semibold uppercase tracking-wide text-night-300">E-mail</label>
                    <input v-model="infoForm.email" type="email" required class="mt-1 w-full rounded-lg border-night-500/15 focus:border-runway-500 focus:ring-runway-500" />
                    <p v-if="infoForm.errors.email" class="mt-1 text-xs text-red-600">{{ infoForm.errors.email }}</p>
                </div>
                <div>
                    <label class="text-xs font-display font-semibold uppercase tracking-wide text-night-300">Téléphone</label>
                    <input v-model="infoForm.phone" type="tel" placeholder="+33 6 12 34 56 78" class="mt-1 w-full rounded-lg border-night-500/15 focus:border-runway-500 focus:ring-runway-500" />
                </div>
                <button type="submit" :disabled="infoForm.processing" class="btn-primary">Enregistrer</button>
            </form>

            <form @submit.prevent="savePassword" class="card mt-6 space-y-4 p-6">
                <h2 class="font-display font-semibold text-night-500">Mot de passe</h2>
                <div>
                    <label class="text-xs font-display font-semibold uppercase tracking-wide text-night-300">Mot de passe actuel</label>
                    <input v-model="passwordForm.current_password" type="password" required class="mt-1 w-full rounded-lg border-night-500/15 focus:border-runway-500 focus:ring-runway-500" />
                    <p v-if="passwordForm.errors.current_password" class="mt-1 text-xs text-red-600">{{ passwordForm.errors.current_password }}</p>
                </div>
                <div>
                    <label class="text-xs font-display font-semibold uppercase tracking-wide text-night-300">Nouveau mot de passe</label>
                    <input v-model="passwordForm.password" type="password" required class="mt-1 w-full rounded-lg border-night-500/15 focus:border-runway-500 focus:ring-runway-500" />
                    <p v-if="passwordForm.errors.password" class="mt-1 text-xs text-red-600">{{ passwordForm.errors.password }}</p>
                </div>
                <div>
                    <label class="text-xs font-display font-semibold uppercase tracking-wide text-night-300">Confirmer</label>
                    <input v-model="passwordForm.password_confirmation" type="password" required class="mt-1 w-full rounded-lg border-night-500/15 focus:border-runway-500 focus:ring-runway-500" />
                </div>
                <button type="submit" :disabled="passwordForm.processing" class="btn-secondary">Changer le mot de passe</button>
            </form>
        </section>
    </AppLayout>
</template>
