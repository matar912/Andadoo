<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    reservation: Object,
    alreadyPaid: Boolean,
    pendingPayment: Object,
    mobileMoneyNumbers: Object,
});

const methods = [
    { key: 'wave', label: 'Wave' },
    { key: 'orange_money', label: 'Orange Money' },
    { key: 'free_money', label: 'Free Money' },
];

const form = useForm({ method: 'wave', transaction_ref: '' });

const selectedNumber = computed(() => props.mobileMoneyNumbers[form.method]);

function submit() {
    form.post(`/reservations/${props.reservation.id}/paiement`);
}
</script>

<template>
    <AppLayout>
        <section class="mx-auto max-w-lg px-6 py-14">
            <Link :href="`/reservations/${reservation.id}`" class="text-sm font-display font-medium text-forest-500/60 hover:text-gold-600">&larr; Retour à ma réservation</Link>

            <h1 class="mt-4 font-display text-2xl font-bold text-forest-700">Paiement</h1>
            <p class="mt-1 text-sm text-forest-500/60">
                {{ reservation.vehicle.brand }} {{ reservation.vehicle.model }} &middot;
                <span class="font-display font-semibold text-gold-600">{{ reservation.total_price }} FCFA</span>
            </p>

            <div v-if="alreadyPaid" class="card mt-8 p-6 text-center">
                <p class="font-display font-semibold text-emerald-700">Cette réservation est déjà payée. Merci !</p>
            </div>

            <div v-else-if="pendingPayment" class="card mt-8 p-6 text-center">
                <p class="font-display font-semibold text-gold-700">Un paiement est déjà en cours de vérification.</p>
                <p class="mt-2 text-sm text-forest-500/60">
                    Méthode : {{ pendingPayment.method }} — notre équipe vous notifiera dès confirmation.
                </p>
            </div>

            <form v-else @submit.prevent="submit" class="card mt-8 space-y-5 p-6">
                <div>
                    <label class="text-xs font-display font-semibold uppercase tracking-wide text-forest-300">Moyen de paiement</label>
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        <button
                            v-for="m in methods"
                            :key="m.key"
                            type="button"
                            @click="form.method = m.key"
                            class="rounded-lg border px-4 py-3 text-sm font-display font-medium transition"
                            :class="form.method === m.key ? 'border-gold-500 bg-gold-500/10 text-gold-700' : 'border-forest-500/15 text-forest-500'"
                        >
                            {{ m.label }}
                        </button>
                    </div>
                </div>

                <div class="rounded-lg bg-paper-100 p-4 text-sm text-forest-500/80">
                    <p>Envoyez <strong>{{ reservation.total_price }} FCFA</strong> via {{ methods.find(m => m.key === form.method)?.label }} au numéro :</p>
                    <p class="mt-1 font-display text-lg font-bold text-forest-700">{{ selectedNumber || 'Numéro à venir — contactez le support' }}</p>
                </div>

                <div>
                    <label class="text-xs font-display font-semibold uppercase tracking-wide text-forest-300">
                        Référence / code de la transaction reçue
                    </label>
                    <input v-model="form.transaction_ref" type="text" placeholder="Ex : TX-84213-WAVE" class="mt-1 w-full rounded-lg border-forest-500/15 focus:border-gold-500 focus:ring-gold-500" />
                    <p v-if="form.errors.transaction_ref" class="mt-1 text-xs text-red-600">{{ form.errors.transaction_ref }}</p>
                </div>

                <button type="submit" :disabled="form.processing" class="btn-primary w-full">J'ai effectué le paiement</button>
                <p class="text-center text-xs text-forest-500/50">
                    Notre équipe vérifie chaque paiement manuellement avant confirmation — vous serez notifié.
                </p>
            </form>
        </section>
    </AppLayout>
</template>
