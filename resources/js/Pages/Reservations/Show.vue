<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ReservationTimeline from '@/Components/ReservationTimeline.vue';

const props = defineProps({ reservation: Object });

const statusInfo = {
    en_attente: { label: 'En attente de validation par GO\'CAR', style: 'bg-runway-500/10 text-runway-700' },
    confirmee: { label: 'Confirmée', style: 'bg-emerald-500/10 text-emerald-700' },
    en_cours: { label: 'En cours', style: 'bg-sky-500/10 text-sky-700' },
    terminee: { label: 'Terminée', style: 'bg-night-500/10 text-night-500' },
    annulee: { label: 'Refusée / annulée', style: 'bg-red-500/10 text-red-700' },
};
</script>

<template>
    <AppLayout>
        <section class="mx-auto max-w-2xl px-6 py-14">
            <h1 class="font-display text-2xl font-bold text-night-500">Votre demande de réservation</h1>

            <div class="card mt-6 overflow-hidden">
                <div class="bg-night-500 p-6 text-sand-100">
                    <p class="font-display text-xs uppercase tracking-widest text-runway-400">GO'CAR</p>
                    <p class="mt-2 font-display text-xl font-bold text-white">
                        {{ reservation.vehicle.brand }} {{ reservation.vehicle.model }}
                    </p>
                </div>

                <div class="space-y-6 p-6">
                    <ReservationTimeline :status="reservation.status" />

                    <p v-if="reservation.status === 'en_attente'" class="text-sm text-night-500/70">
                        Votre demande a bien été enregistrée. L'équipe GO'CAR vérifie la disponibilité du véhicule
                        et du chauffeur, puis confirme votre réservation — vous recevrez une notification dès validation.
                    </p>
                    <p v-else-if="reservation.status === 'confirmee'" class="text-sm text-night-500/70">
                        Votre réservation est confirmée. Le chauffeur GO'CAR vous contactera avant votre arrivée.
                    </p>
                    <p v-else-if="reservation.status === 'annulee'" class="text-sm text-night-500/70">
                        Cette demande n'a pas pu être confirmée. Contactez le support pour plus de détails ou faites une nouvelle demande.
                    </p>

                    <dl class="grid grid-cols-2 gap-4 border-t border-night-500/10 pt-6 text-sm">
                        <div>
                            <dt class="text-night-500/50">Prise en charge</dt>
                            <dd class="text-night-500">{{ reservation.pickup_location }}</dd>
                        </div>
                        <div>
                            <dt class="text-night-500/50">Total</dt>
                            <dd class="text-night-500">{{ reservation.total_price }} FCFA</dd>
                        </div>
                        <div>
                            <dt class="text-night-500/50">Arrivée</dt>
                            <dd class="text-night-500">{{ reservation.start_at?.slice(0, 10) }}</dd>
                        </div>
                        <div>
                            <dt class="text-night-500/50">Restitution</dt>
                            <dd class="text-night-500">{{ reservation.end_at?.slice(0, 10) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <Link href="/vehicules" class="btn-secondary mt-6 inline-flex">Retour au catalogue</Link>
        </section>
    </AppLayout>
</template>
