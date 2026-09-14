<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';

const props = defineProps({ payments: Object });
const page = usePage();
const adminBase = () => `/${page.props.adminPath ?? ''}`;
const confirmDialog = ref(null);
const pending = ref(null);

const methodLabels = { carte: 'Carte', wave: 'Wave', orange_money: 'Orange Money', free_money: 'Free Money' };
const statusStyle = {
    en_attente: 'bg-gold-500/15 text-gold-300',
    reussi: 'bg-emerald-500/15 text-emerald-300',
    echoue: 'bg-red-500/15 text-red-300',
    rembourse: 'bg-white/10 text-paper-100/60',
};
const statusLabels = { en_attente: 'À vérifier', reussi: 'Confirmé', echoue: 'Rejeté', rembourse: 'Remboursé' };

function confirmPayment(payment) {
    pending.value = payment.id;
    router.patch(`${adminBase()}/paiements/${payment.id}/confirmer`, {}, { preserveScroll: true, onFinish: () => (pending.value = null) });
}

async function rejectPayment(payment) {
    const ok = await confirmDialog.value.open({
        title: 'Rejeter ce paiement ?',
        message: 'Le client verra que son paiement n\'a pas été validé et devra retenter la démarche.',
        confirmLabel: 'Rejeter',
        danger: true,
    });
    if (!ok) return;
    pending.value = payment.id;
    router.patch(`${adminBase()}/paiements/${payment.id}/rejeter`, {}, { preserveScroll: true, onFinish: () => (pending.value = null) });
}
</script>

<template>
    <AdminLayout>
        <ConfirmDialog ref="confirmDialog" />

        <h1 class="font-display text-2xl font-bold text-white">Paiements</h1>
        <p class="mt-1 text-sm text-paper-100/60">
            Chaque paiement déclaré par un client reste "à vérifier" tant que vous n'avez pas confirmé la réception réelle des fonds.
        </p>

        <div v-if="payments.data.length" class="mt-8 overflow-x-auto rounded-xl2 border border-white/10">
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead class="bg-forest-700 text-paper-100/60">
                    <tr>
                        <th class="px-4 py-3">Client</th>
                        <th class="px-4 py-3">Véhicule</th>
                        <th class="px-4 py-3">Méthode</th>
                        <th class="px-4 py-3">Référence</th>
                        <th class="px-4 py-3">Montant</th>
                        <th class="px-4 py-3">Statut</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 bg-forest-700/40">
                    <tr v-for="p in payments.data" :key="p.id" class="transition-colors duration-150 hover:bg-white/[0.03]">
                        <td class="px-4 py-3 text-white">{{ p.reservation?.client?.name }}</td>
                        <td class="px-4 py-3 text-paper-100/80">{{ p.reservation?.vehicle?.brand }} {{ p.reservation?.vehicle?.model }}</td>
                        <td class="px-4 py-3 text-paper-100/80">{{ methodLabels[p.method] }}</td>
                        <td class="px-4 py-3 text-paper-100/60">{{ p.transaction_ref || '—' }}</td>
                        <td class="px-4 py-3 text-paper-100/80">{{ p.amount }} FCFA</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs" :class="statusStyle[p.status]">{{ statusLabels[p.status] }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span v-if="pending === p.id" class="text-xs text-paper-100/40">Traitement...</span>
                            <template v-else-if="p.status === 'en_attente'">
                                <button @click="confirmPayment(p)" class="mr-3 font-display text-emerald-400 hover:text-emerald-300">Confirmer</button>
                                <button @click="rejectPayment(p)" class="font-display text-gold-400 hover:text-gold-300">Rejeter</button>
                            </template>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p v-else class="mt-16 text-center text-sm text-paper-100/50">Aucun paiement pour le moment.</p>
    </AdminLayout>
</template>
