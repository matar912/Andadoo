<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ stats: Object, recent_reservations: Array });
const page = usePage();
const adminBase = () => `/${page.props.adminPath ?? ''}`;

const statusLabels = {
    en_attente: 'En attente',
    confirmee: 'Confirmee',
    en_cours: 'En cours',
    terminee: 'Terminee',
    annulee: 'Annulee',
};
</script>

<template>
    <AdminLayout>
        <h1 class="font-display text-2xl font-bold text-white">Tableau de bord</h1>
        <p class="mt-1 text-sm text-paper-100/60">Vue d'ensemble de la flotte et des demandes de reservation.</p>

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <div class="rounded-xl2 border border-white/10 bg-forest-700 p-5">
                <p class="text-xs uppercase tracking-wide text-paper-100/50">Vehicules</p>
                <p class="mt-2 font-display text-2xl font-bold text-white">{{ stats.vehicles_total }}</p>
            </div>
            <div class="rounded-xl2 border border-white/10 bg-forest-700 p-5">
                <p class="text-xs uppercase tracking-wide text-paper-100/50">Disponibles</p>
                <p class="mt-2 font-display text-2xl font-bold text-white">{{ stats.vehicles_available }}</p>
            </div>
            <div class="rounded-xl2 border border-gold-500/30 bg-gold-500/10 p-5">
                <p class="text-xs uppercase tracking-wide text-gold-300">A valider</p>
                <p class="mt-2 font-display text-2xl font-bold text-gold-300">{{ stats.reservations_pending }}</p>
            </div>
            <div class="rounded-xl2 border border-white/10 bg-forest-700 p-5">
                <p class="text-xs uppercase tracking-wide text-paper-100/50">En cours</p>
                <p class="mt-2 font-display text-2xl font-bold text-white">{{ stats.reservations_active }}</p>
            </div>
            <div class="rounded-xl2 border border-white/10 bg-forest-700 p-5">
                <p class="text-xs uppercase tracking-wide text-paper-100/50">CA du mois</p>
                <p class="mt-2 font-display text-2xl font-bold text-white">{{ stats.revenue_month }} FCFA</p>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <Link :href="`${adminBase()}/vehicules/nouveau`" class="btn-primary">+ Ajouter un vehicule</Link>
            <Link :href="`${adminBase()}/reservations`" class="rounded-full border border-white/15 px-6 py-3 font-display text-sm font-semibold text-white hover:border-gold-400">
                Traiter les reservations en attente
            </Link>
        </div>

        <h2 class="mt-10 font-display text-lg font-semibold text-white">Dernieres demandes</h2>
        <div class="mt-4 overflow-x-auto rounded-xl2 border border-white/10">
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead class="bg-forest-700 text-paper-100/60">
                    <tr>
                        <th class="px-4 py-3">Client</th>
                        <th class="px-4 py-3">Vehicule</th>
                        <th class="px-4 py-3">Statut</th>
                        <th class="px-4 py-3">Montant</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 bg-forest-700/40">
                    <tr v-for="r in recent_reservations" :key="r.id">
                        <td class="px-4 py-3 text-white">{{ r.client?.name }}</td>
                        <td class="px-4 py-3 text-paper-100/80">{{ r.vehicle?.brand }} {{ r.vehicle?.model }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-white/10 px-2 py-1 text-xs text-paper-100/80">{{ statusLabels[r.status] }}</span>
                        </td>
                        <td class="px-4 py-3 text-paper-100/80">{{ r.total_price }} FCFA</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
