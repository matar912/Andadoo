<script setup>
import { Link, router } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    vehicles: Object,
    filters: Object,
});

const loading = ref(false);
const showAdvanced = ref(false);
const categories = [
    ['berline', 'Berline'], ['suv', 'SUV'], ['4x4', '4x4'], ['minibus', 'Minibus'], ['citadine', 'Citadine'],
];

// Formulaire local pour tous les criteres ; on ne renvoie au serveur que sur "Rechercher"
// ou un clic de categorie rapide (pas de requete a chaque frappe).
const form = reactive({
    q: props.filters.q ?? '',
    category: props.filters.category ?? '',
    transmission: props.filters.transmission ?? '',
    seats: props.filters.seats ?? '',
    price_min: props.filters.price_min ?? '',
    price_max: props.filters.price_max ?? '',
});

function search() {
    router.get('/vehicules', { ...form }, {
        preserveState: true,
        replace: true,
        onStart: () => (loading.value = true),
        onFinish: () => (loading.value = false),
    });
}

function applyCategory(category) {
    form.category = form.category === category ? '' : category;
    search();
}

function resetFilters() {
    Object.assign(form, { q: '', category: '', transmission: '', seats: '', price_min: '', price_max: '' });
    search();
}

const statusBadge = {
    en_location: { label: 'Actuellement en location', style: 'bg-gold-500/10 text-gold-700' },
    maintenance: { label: 'En maintenance', style: 'bg-forest-500/10 text-forest-600' },
};
</script>

<template>
    <AppLayout>
        <section class="mx-auto max-w-6xl px-6 py-14">
            <h1 class="font-display text-3xl font-bold text-forest-700">La flotte Andadoo</h1>
            <p class="mt-2 text-forest-500/70">Tous les véhicules appartiennent à Andadoo et sont entretenus par nos équipes.</p>

            <!-- Barre de recherche -->
            <div class="card mt-6 p-5">
                <form @submit.prevent="search" class="flex flex-col gap-4 lg:flex-row lg:items-end">
                    <div class="flex-1">
                        <label class="text-xs font-display font-semibold uppercase tracking-wide text-forest-300">Marque ou modèle</label>
                        <input v-model="form.q" type="text" placeholder="Toyota, Corolla..." class="mt-1 w-full rounded-lg border-forest-500/15 focus:border-gold-500 focus:ring-gold-500" />
                    </div>
                    <div>
                        <label class="text-xs font-display font-semibold uppercase tracking-wide text-forest-300">Transmission</label>
                        <select v-model="form.transmission" class="mt-1 w-full rounded-lg border-forest-500/15 focus:border-gold-500 focus:ring-gold-500 lg:w-40">
                            <option value="">Toutes</option>
                            <option value="manuelle">Manuelle</option>
                            <option value="automatique">Automatique</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-display font-semibold uppercase tracking-wide text-forest-300">Places min.</label>
                        <input v-model="form.seats" type="number" min="1" placeholder="4" class="mt-1 w-full rounded-lg border-forest-500/15 focus:border-gold-500 focus:ring-gold-500 lg:w-24" />
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            Rechercher
                        </button>
                        <button type="button" @click="showAdvanced = !showAdvanced" class="btn-secondary" :class="{ '!border-gold-500 !text-gold-600': showAdvanced }">
                            Filtres avancés
                        </button>
                    </div>
                </form>

                <!-- Filtres avances : prix -->
                <Transition name="fade">
                    <div v-if="showAdvanced" class="mt-4 grid gap-4 border-t border-forest-500/10 pt-4 sm:grid-cols-2">
                        <div>
                            <label class="text-xs font-display font-semibold uppercase tracking-wide text-forest-300">Prix min. / jour (FCFA)</label>
                            <input v-model="form.price_min" type="number" min="0" placeholder="0" class="mt-1 w-full rounded-lg border-forest-500/15 focus:border-gold-500 focus:ring-gold-500" />
                        </div>
                        <div>
                            <label class="text-xs font-display font-semibold uppercase tracking-wide text-forest-300">Prix max. / jour (FCFA)</label>
                            <input v-model="form.price_max" type="number" min="0" placeholder="100 000" class="mt-1 w-full rounded-lg border-forest-500/15 focus:border-gold-500 focus:ring-gold-500" />
                        </div>
                    </div>
                </Transition>
            </div>

            <!-- Categories rapides -->
            <div class="mt-4 flex flex-wrap items-center gap-2">
                <button
                    v-for="[value, label] in categories"
                    :key="value"
                    @click="applyCategory(value)"
                    class="rounded-full border px-4 py-2 text-sm font-display transition"
                    :class="form.category === value ? 'border-gold-500 bg-gold-500 text-white' : 'border-forest-500/15 text-forest-500 hover:border-gold-400'"
                >
                    {{ label }}
                </button>
                <button v-if="form.q || form.category || form.transmission || form.seats || form.price_min || form.price_max" @click="resetFilters" class="text-sm font-display font-medium text-forest-500/50 hover:text-gold-600">
                    Réinitialiser
                </button>
            </div>

            <!-- Etat de chargement : skeletons pendant le filtrage -->
            <div v-if="loading" class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="n in 6" :key="n" class="card overflow-hidden">
                    <div class="h-40 animate-pulse bg-forest-500/10" />
                    <div class="space-y-3 p-5">
                        <div class="h-4 w-2/3 animate-pulse rounded bg-forest-500/10" />
                        <div class="h-3 w-1/2 animate-pulse rounded bg-forest-500/10" />
                        <div class="h-8 w-full animate-pulse rounded bg-forest-500/10" />
                    </div>
                </div>
            </div>

            <div v-else-if="vehicles.data.length" class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="vehicle in vehicles.data" :key="vehicle.id" class="card overflow-hidden transition hover:shadow-md">
                    <Link :href="`/vehicules/${vehicle.id}`" class="relative flex h-40 items-center justify-center bg-paper-100">
                        <img v-if="vehicle.photo_path" :src="`/vehicule-photo/${vehicle.photo_path}`" :alt="`${vehicle.brand} ${vehicle.model}`" class="h-full w-full object-cover" />
                        <span v-else class="font-display text-forest-300">{{ vehicle.brand }} {{ vehicle.model }}</span>
                        <span v-if="statusBadge[vehicle.status]" class="absolute left-2 top-2 rounded-full px-2 py-1 text-[11px] font-display font-semibold" :class="statusBadge[vehicle.status].style">
                            {{ statusBadge[vehicle.status].label }}
                        </span>
                    </Link>
                    <div class="p-5">
                        <p class="font-display text-lg font-semibold text-forest-700">{{ vehicle.brand }} {{ vehicle.model }}</p>
                        <p class="mt-1 text-sm capitalize text-forest-500/60">{{ vehicle.category }} &middot; {{ vehicle.seats }} places &middot; {{ vehicle.transmission }}</p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="font-display text-xl font-bold text-gold-600">{{ vehicle.daily_price }} FCFA<span class="text-sm font-normal text-forest-500/50">/jour</span></span>
                            <Link :href="`/reservations/nouvelle?vehicle_id=${vehicle.id}`" class="btn-secondary text-sm">Réserver</Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Etat vide actionnable, plutot qu'une simple phrase -->
            <div v-else class="mt-16 flex flex-col items-center rounded-xl2 border border-dashed border-forest-500/15 py-16 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" class="h-12 w-12 text-forest-500/25">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 0h-12" />
                </svg>
                <p class="mt-4 font-display font-semibold text-forest-700">Aucun véhicule pour cette recherche</p>
                <p class="mt-1 max-w-sm text-sm text-forest-500/60">
                    Essayez d'élargir vos critères — un autre modèle ou une fourchette de prix plus large.
                </p>
                <button @click="resetFilters" class="btn-secondary mt-5 text-sm">Réinitialiser les filtres</button>
            </div>
        </section>
    </AppLayout>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.15s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
