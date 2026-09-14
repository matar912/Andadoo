<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({ reservations: Object });

const statusStyle = {
    en_attente: 'bg-gold-500/10 text-gold-700',
    confirmee: 'bg-emerald-500/10 text-emerald-700',
    en_cours: 'bg-sky-500/10 text-sky-700',
    terminee: 'bg-forest-500/10 text-forest-500',
    annulee: 'bg-red-500/10 text-red-700',
};
const statusLabels = {
    en_attente: 'En attente',
    confirmee: 'Confirmée',
    en_cours: 'En cours',
    terminee: 'Terminée',
    annulee: 'Refusée',
};
</script>

<template>
    <AppLayout>
        <section class="mx-auto max-w-3xl px-6 py-14">
            <h1 class="font-display text-2xl font-bold text-forest-500">Mes réservations</h1>
            <p class="mt-1 text-sm text-forest-500/70">L'historique et le suivi de toutes vos demandes Andadoo.</p>

            <div v-if="reservations.data.length" class="mt-8 space-y-3">
                <Link
                    v-for="r in reservations.data"
                    :key="r.id"
                    :href="`/reservations/${r.id}`"
                    class="card flex items-center justify-between p-5 transition hover:shadow-md"
                >
                    <div>
                        <p class="font-display font-semibold text-forest-700">{{ r.vehicle.brand }} {{ r.vehicle.model }}</p>
                        <p class="mt-1 text-sm text-forest-500/60">{{ r.start_at?.slice(0, 10) }} → {{ r.end_at?.slice(0, 10) }}</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-display font-semibold" :class="statusStyle[r.status]">
                        {{ statusLabels[r.status] }}
                    </span>
                </Link>
            </div>

            <div v-else class="mt-16 flex flex-col items-center rounded-xl2 border border-dashed border-forest-500/15 py-16 text-center">
                <p class="font-display font-semibold text-forest-500">Aucune réservation pour l'instant</p>
                <p class="mt-1 max-w-sm text-sm text-forest-500/60">Parcourez la flotte Andadoo et réservez votre prochain séjour.</p>
                <Link href="/vehicules" class="btn-primary mt-5">Voir les véhicules</Link>
            </div>
        </section>
    </AppLayout>
</template>
