<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ vehicle: Object, bookedRanges: Array, options: Array, partners: Array });

const form = useForm({
    vehicle_id: props.vehicle.id,
    formula: 'transfert_plus_location',
    with_driver: true,
    flight_number: '',
    pickup_location: 'Aeroport International Blaise Diagne (AIBD)',
    dropoff_location: '',
    start_at: '',
    end_at: '',
    partner_id: '',
    options: [],
});

// Nombre de jours et estimation du prix en direct, options comprises.
// Le montant definitif reste calcule et verifie cote serveur.
const days = computed(() => {
    if (!form.start_at || !form.end_at) return 0;
    const diff = (new Date(form.end_at) - new Date(form.start_at)) / 86400000;
    return Math.max(1, Math.round(diff));
});
const optionsTotal = computed(() =>
    props.options.filter((o) => form.options.includes(o.id)).reduce((sum, o) => sum + Number(o.extra_price), 0)
);
const estimatedTotal = computed(() => days.value * Number(props.vehicle.daily_price) + optionsTotal.value);

function toggleOption(id) {
    const i = form.options.indexOf(id);
    if (i === -1) form.options.push(id);
    else form.options.splice(i, 1);
}

function submit() {
    form.post('/reservations');
}
</script>

<template>
    <AppLayout>
        <section class="mx-auto max-w-4xl px-6 py-14">
            <h1 class="font-display text-2xl font-bold text-forest-500">Finaliser la reservation</h1>
            <p class="mt-1 text-forest-500/70">{{ vehicle.brand }} {{ vehicle.model }} &middot; {{ vehicle.daily_price }} FCFA / jour</p>

            <form @submit.prevent="submit" class="mt-8 overflow-hidden rounded-xl2 border border-forest-500/10 bg-white shadow-sm">
                <!-- Souche "carte d'embarquement" : signature visuelle du produit -->
                <div class="grid gap-6 bg-forest-500 p-6 text-paper-100 md:grid-cols-[1fr_auto]">
                    <div>
                        <p class="font-display text-xs uppercase tracking-widest text-gold-400">Carte d'accueil Andadoo</p>
                        <p class="mt-2 font-display text-xl font-bold text-white">{{ vehicle.brand }} {{ vehicle.model }}</p>
                        <p class="mt-1 text-sm text-paper-100/70">Chauffeur Andadoo salarie &middot; vehicule de la flotte propre</p>
                    </div>
                    <div class="flex items-center gap-3 border-l border-dashed border-paper-100/30 pl-6">
                        <img v-if="vehicle.photo_path" :src="`/vehicule-photo/${vehicle.photo_path}`" :alt="vehicle.model" class="h-14 w-20 rounded-lg object-cover" />
                        <img v-else src="/images/logo-icon.png" alt="Andadoo" class="h-12 w-12 object-contain" />
                    </div>
                </div>

                <div class="grid gap-6 p-6 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-display font-semibold uppercase tracking-wide text-forest-300">Formule</label>
                        <select v-model="form.formula" class="mt-1 w-full rounded-lg border-forest-500/15 focus:border-gold-500 focus:ring-gold-500">
                            <option value="transfert_simple">Transfert aeroport simple</option>
                            <option value="transfert_plus_location">Transfert + location du sejour</option>
                            <option value="longue_duree">Location longue duree / tour</option>
                            <option value="location_locale">Location classique</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <input id="with_driver" v-model="form.with_driver" type="checkbox" class="rounded border-forest-500/30 text-gold-500 focus:ring-gold-500" />
                        <label for="with_driver" class="text-sm text-forest-500">Avec chauffeur Andadoo</label>
                    </div>

                    <div>
                        <label class="text-xs font-display font-semibold uppercase tracking-wide text-forest-300">N&deg; de vol (optionnel)</label>
                        <input v-model="form.flight_number" type="text" placeholder="AF 718" class="mt-1 w-full rounded-lg border-forest-500/15 focus:border-gold-500 focus:ring-gold-500" />
                    </div>

                    <div>
                        <label class="text-xs font-display font-semibold uppercase tracking-wide text-forest-300">Point de prise en charge</label>
                        <input v-model="form.pickup_location" type="text" class="mt-1 w-full rounded-lg border-forest-500/15 focus:border-gold-500 focus:ring-gold-500" />
                    </div>

                    <div>
                        <label class="text-xs font-display font-semibold uppercase tracking-wide text-forest-300">Date d'arrivee</label>
                        <input v-model="form.start_at" type="date" class="mt-1 w-full rounded-lg border-forest-500/15 focus:border-gold-500 focus:ring-gold-500" />
                        <p v-if="form.errors.start_at" class="mt-1 text-xs text-red-600">{{ form.errors.start_at }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-display font-semibold uppercase tracking-wide text-forest-300">Date de restitution</label>
                        <input v-model="form.end_at" type="date" class="mt-1 w-full rounded-lg border-forest-500/15 focus:border-gold-500 focus:ring-gold-500" />
                        <p v-if="form.errors.end_at" class="mt-1 text-xs text-red-600">{{ form.errors.end_at }}</p>
                    </div>

                    <div v-if="bookedRanges?.length" class="md:col-span-2">
                        <p class="text-xs font-display font-semibold uppercase tracking-wide text-forest-300">Déjà réservé sur ces périodes</p>
                        <div class="mt-1 flex flex-wrap gap-2">
                            <span v-for="(r, i) in bookedRanges" :key="i" class="rounded-full bg-forest-500/5 px-3 py-1 text-xs text-forest-500/70">
                                {{ r.start }} → {{ r.end }}
                            </span>
                        </div>
                    </div>

                    <!-- Options facultatives -->
                    <div v-if="options?.length" class="md:col-span-2">
                        <p class="text-xs font-display font-semibold uppercase tracking-wide text-forest-300">Options</p>
                        <div class="mt-2 grid gap-2 sm:grid-cols-2">
                            <label
                                v-for="o in options"
                                :key="o.id"
                                class="flex cursor-pointer items-center justify-between rounded-lg border px-3 py-2 text-sm transition"
                                :class="form.options.includes(o.id) ? 'border-gold-500 bg-gold-500/5' : 'border-forest-500/15'"
                            >
                                <span class="flex items-center gap-2">
                                    <input type="checkbox" :checked="form.options.includes(o.id)" @change="toggleOption(o.id)" class="rounded border-forest-500/30 text-gold-500 focus:ring-gold-500" />
                                    {{ o.name }}
                                </span>
                                <span class="text-forest-500/60">+{{ o.extra_price }} FCFA</span>
                            </label>
                        </div>
                    </div>

                    <!-- Partenaire (facultatif) -->
                    <div v-if="partners?.length" class="md:col-span-2">
                        <label class="text-xs font-display font-semibold uppercase tracking-wide text-forest-300">Réservé via une agence partenaire (optionnel)</label>
                        <select v-model="form.partner_id" class="mt-1 w-full rounded-lg border-forest-500/15 focus:border-gold-500 focus:ring-gold-500">
                            <option value="">Aucune</option>
                            <option v-for="p in partners" :key="p.id" :value="p.id">{{ p.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-forest-500/10 bg-paper-50 px-6 py-4">
                    <div>
                        <p class="text-sm text-forest-500/60">Vous recevrez la photo et le nom de votre chauffeur avant l'arrivee.</p>
                        <p v-if="days" class="mt-1 font-display text-sm font-semibold text-forest-700">
                            Estimation : {{ days }} jour(s) &times; {{ vehicle.daily_price }} FCFA
                            <span v-if="optionsTotal"> + {{ optionsTotal }} FCFA d'options</span>
                            = <span class="text-gold-600">{{ estimatedTotal.toLocaleString('fr-FR') }} FCFA</span>
                        </p>
                    </div>
                    <button type="submit" :disabled="form.processing" class="btn-primary">Confirmer la reservation</button>
                </div>
            </form>
        </section>
    </AppLayout>
</template>
