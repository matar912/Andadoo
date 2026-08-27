<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ vehicle: Object, bookedRanges: Array });

const form = useForm({
    vehicle_id: props.vehicle.id,
    formula: 'transfert_plus_location',
    with_driver: true,
    flight_number: '',
    pickup_location: 'Aeroport International Blaise Diagne (AIBD)',
    dropoff_location: '',
    start_at: '',
    end_at: '',
});

function submit() {
    form.post('/reservations');
}
</script>

<template>
    <AppLayout>
        <section class="mx-auto max-w-4xl px-6 py-14">
            <h1 class="font-display text-2xl font-bold text-night-500">Finaliser la reservation</h1>
            <p class="mt-1 text-night-500/70">{{ vehicle.brand }} {{ vehicle.model }} &middot; {{ vehicle.daily_price }} FCFA / jour</p>

            <form @submit.prevent="submit" class="mt-8 overflow-hidden rounded-xl2 border border-night-500/10 bg-white shadow-sm">
                <!-- Souche "carte d'embarquement" : signature visuelle du produit -->
                <div class="grid gap-6 bg-night-500 p-6 text-sand-100 md:grid-cols-[1fr_auto]">
                    <div>
                        <p class="font-display text-xs uppercase tracking-widest text-runway-400">Carte d'accueil GO'CAR</p>
                        <p class="mt-2 font-display text-xl font-bold text-white">{{ vehicle.brand }} {{ vehicle.model }}</p>
                        <p class="mt-1 text-sm text-sand-100/70">Chauffeur GO'CAR salarie &middot; vehicule de la flotte propre</p>
                    </div>
                    <div class="flex items-center gap-3 border-l border-dashed border-sand-100/30 pl-6">
                        <img v-if="vehicle.photo_path" :src="`/vehicule-photo/${vehicle.photo_path}`" :alt="vehicle.model" class="h-14 w-20 rounded-lg object-cover" />
                        <div v-else class="flex h-12 w-12 items-center justify-center rounded-full bg-runway-500 font-display font-bold text-white">GC</div>
                    </div>
                </div>

                <div class="grid gap-6 p-6 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-display font-semibold uppercase tracking-wide text-night-300">Formule</label>
                        <select v-model="form.formula" class="mt-1 w-full rounded-lg border-night-500/15 focus:border-runway-500 focus:ring-runway-500">
                            <option value="transfert_simple">Transfert aeroport simple</option>
                            <option value="transfert_plus_location">Transfert + location du sejour</option>
                            <option value="longue_duree">Location longue duree / tour</option>
                            <option value="location_locale">Location classique</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <input id="with_driver" v-model="form.with_driver" type="checkbox" class="rounded border-night-500/30 text-runway-500 focus:ring-runway-500" />
                        <label for="with_driver" class="text-sm text-night-500">Avec chauffeur GO'CAR</label>
                    </div>

                    <div>
                        <label class="text-xs font-display font-semibold uppercase tracking-wide text-night-300">N&deg; de vol (optionnel)</label>
                        <input v-model="form.flight_number" type="text" placeholder="AF 718" class="mt-1 w-full rounded-lg border-night-500/15 focus:border-runway-500 focus:ring-runway-500" />
                    </div>

                    <div>
                        <label class="text-xs font-display font-semibold uppercase tracking-wide text-night-300">Point de prise en charge</label>
                        <input v-model="form.pickup_location" type="text" class="mt-1 w-full rounded-lg border-night-500/15 focus:border-runway-500 focus:ring-runway-500" />
                    </div>

                    <div>
                        <label class="text-xs font-display font-semibold uppercase tracking-wide text-night-300">Date d'arrivee</label>
                        <input v-model="form.start_at" type="date" class="mt-1 w-full rounded-lg border-night-500/15 focus:border-runway-500 focus:ring-runway-500" />
                        <p v-if="form.errors.start_at" class="mt-1 text-xs text-red-600">{{ form.errors.start_at }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-display font-semibold uppercase tracking-wide text-night-300">Date de restitution</label>
                        <input v-model="form.end_at" type="date" class="mt-1 w-full rounded-lg border-night-500/15 focus:border-runway-500 focus:ring-runway-500" />
                        <p v-if="form.errors.end_at" class="mt-1 text-xs text-red-600">{{ form.errors.end_at }}</p>
                    </div>

                    <div v-if="bookedRanges?.length" class="md:col-span-2">
                        <p class="text-xs font-display font-semibold uppercase tracking-wide text-night-300">Déjà réservé sur ces périodes</p>
                        <div class="mt-1 flex flex-wrap gap-2">
                            <span v-for="(r, i) in bookedRanges" :key="i" class="rounded-full bg-night-500/5 px-3 py-1 text-xs text-night-500/70">
                                {{ r.start }} → {{ r.end }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-night-500/10 bg-sand-50 px-6 py-4">
                    <p class="text-sm text-night-500/60">Vous recevrez la photo et le nom de votre chauffeur avant l'arrivee.</p>
                    <button type="submit" :disabled="form.processing" class="btn-primary">Confirmer la reservation</button>
                </div>
            </form>
        </section>
    </AppLayout>
</template>
