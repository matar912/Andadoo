<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';

const props = defineProps({ reservations: Object });
const page = usePage();
const adminBase = () => `/${page.props.adminPath ?? ''}`;
const confirmDialog = ref(null);
const pending = ref(null);

const statusStyle = {
    en_attente: 'bg-runway-500/15 text-runway-300',
    confirmee: 'bg-emerald-500/15 text-emerald-300',
    en_cours: 'bg-sky-500/15 text-sky-300',
    terminee: 'bg-white/10 text-sand-100/60',
    annulee: 'bg-red-500/15 text-red-300',
};
const statusLabels = {
    en_attente: 'En attente de validation',
    confirmee: 'Confirmée',
    en_cours: 'En cours',
    terminee: 'Terminée',
    annulee: 'Refusée / annulée',
};

function approve(r) {
    pending.value = r.id;
    // Le toast de confirmation vient automatiquement du message flash Laravel
    // (voir AdminLayout) : pas besoin de le declencher deux fois ici.
    router.patch(`${adminBase()}/reservations/${r.id}/valider`, {}, {
        preserveScroll: true,
        onFinish: () => (pending.value = null),
    });
}

async function refuse(r) {
    const ok = await confirmDialog.value.open({
        title: 'Refuser cette demande ?',
        message: `${r.client?.name} sera informé que sa demande pour ${r.vehicle?.brand} ${r.vehicle?.model} n'a pas été retenue.`,
        confirmLabel: 'Refuser',
        danger: true,
    });
    if (!ok) return;

    pending.value = r.id;
    router.patch(`${adminBase()}/reservations/${r.id}/refuser`, {}, {
        preserveScroll: true,
        onFinish: () => (pending.value = null),
    });
}
</script>

<template>
    <AdminLayout>
        <ConfirmDialog ref="confirmDialog" />

        <h1 class="font-display text-2xl font-bold text-white">Réservations</h1>
        <p class="mt-1 text-sm text-sand-100/60">
            Chaque demande client reste "en attente" tant qu'elle n'a pas été validée ici.
        </p>

        <div v-if="reservations.data.length" class="mt-8 overflow-x-auto rounded-xl2 border border-white/10">
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead class="bg-night-700 text-sand-100/60">
                    <tr>
                        <th class="px-4 py-3">Client</th>
                        <th class="px-4 py-3">Véhicule</th>
                        <th class="px-4 py-3">Dates</th>
                        <th class="px-4 py-3">Statut</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 bg-night-700/40">
                    <tr v-for="r in reservations.data" :key="r.id" class="transition-colors duration-150 hover:bg-white/[0.03]">
                        <td class="px-4 py-3 text-white">{{ r.client?.name }}</td>
                        <td class="px-4 py-3 text-sand-100/80">{{ r.vehicle?.brand }} {{ r.vehicle?.model }}</td>
                        <td class="px-4 py-3 text-sand-100/60">{{ r.start_at?.slice(0, 10) }} → {{ r.end_at?.slice(0, 10) }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs" :class="statusStyle[r.status]">{{ statusLabels[r.status] }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <template v-if="r.status === 'en_attente'">
                                <span v-if="pending === r.id" class="text-xs text-sand-100/40">Traitement...</span>
                                <template v-else>
                                    <button @click="approve(r)" class="mr-3 font-display text-emerald-400 transition-colors hover:text-emerald-300">Valider</button>
                                    <button @click="refuse(r)" class="font-display text-runway-400 transition-colors hover:text-runway-300">Refuser</button>
                                </template>
                            </template>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-else class="mt-16 flex flex-col items-center rounded-xl2 border border-dashed border-white/10 py-16 text-center">
            <p class="font-display font-semibold text-white">Aucune réservation pour le moment</p>
            <p class="mt-1 max-w-sm text-sm text-sand-100/50">Les demandes des clients apparaîtront ici dès qu'ils réserveront un véhicule disponible.</p>
        </div>
    </AdminLayout>
</template>
