<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    vehicles: Object,
    filters: Object,
});

const loading = ref(false);
const categories = ['berline', 'suv', '4x4', 'minibus', 'citadine'];

function applyFilter(category) {
    const next = props.filters.category === category ? undefined : category;
    router.get('/vehicules', { ...props.filters, category: next }, {
        preserveState: true,
        replace: true,
        onStart: () => (loading.value = true),
        onFinish: () => (loading.value = false),
    });
}

function resetFilters() {
    router.get('/vehicules', {}, { onStart: () => (loading.value = true), onFinish: () => (loading.value = false) });
}
</script>

<template>
    <AppLayout>
        <section class="mx-auto max-w-6xl px-6 py-14">
            <h1 class="font-display text-3xl font-bold text-forest-500">La flotte Andadoo</h1>
            <p class="mt-2 text-forest-500/70">Tous les véhicules appartiennent à Andadoo et sont entretenus par nos équipes.</p>

            <div class="mt-6 flex flex-wrap gap-2">
                <button
                    v-for="c in categories"
                    :key="c"
                    @click="applyFilter(c)"
                    class="rounded-full border px-4 py-2 text-sm font-display capitalize transition"
                    :class="filters.category === c ? 'border-gold-500 bg-gold-500 text-white' : 'border-forest-500/15 text-forest-500 hover:border-gold-400'"
                >
                    {{ c }}
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
                    <Link :href="`/vehicules/${vehicle.id}`" class="flex h-40 items-center justify-center bg-paper-100">
                        <img v-if="vehicle.photo_path" :src="`/vehicule-photo/${vehicle.photo_path}`" :alt="`${vehicle.brand} ${vehicle.model}`" class="h-full w-full object-cover" />
                        <span v-else class="font-display text-forest-300">{{ vehicle.brand }} {{ vehicle.model }}</span>
                    </Link>
                    <div class="p-5">
                        <p class="font-display text-lg font-semibold text-forest-500">{{ vehicle.brand }} {{ vehicle.model }}</p>
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
                <p class="mt-4 font-display font-semibold text-forest-500">Aucun véhicule pour ce filtre</p>
                <p class="mt-1 max-w-sm text-sm text-forest-500/60">
                    Notre flotte évolue régulièrement — essayez une autre catégorie ou revenez bientôt.
                </p>
                <button @click="resetFilters" class="btn-secondary mt-5 text-sm">Réinitialiser les filtres</button>
            </div>
        </section>
    </AppLayout>
</template>
