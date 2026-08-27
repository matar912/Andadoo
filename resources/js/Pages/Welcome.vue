<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const search = useForm({
    formula: 'transfert_plus_location',
    flight_number: '',
    start_at: '',
    end_at: '',
});

function submitSearch() {
    search.get('/vehicules', { preserveState: true });
}
</script>

<template>
    <AppLayout>
        <!-- Hero : "carte d'embarquement" -->
        <section class="relative overflow-hidden bg-night-500">
            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 20%, #F2A24A 0, transparent 45%), radial-gradient(circle at 80% 0%, #5F988E 0, transparent 40%);" />

            <div class="relative mx-auto max-w-6xl px-6 py-20 md:py-28">
                <p class="font-display text-sm font-semibold uppercase tracking-widest text-runway-400">
                    Diaspora &amp; voyageurs &mdash; reservez avant de partir
                </p>
                <h1 class="mt-4 max-w-2xl font-display text-4xl font-bold leading-tight text-white md:text-5xl">
                    Votre voiture vous attend a l'aeroport, avant meme votre atterrissage.
                </h1>
                <p class="mt-5 max-w-xl text-lg text-sand-100/85">
                    GO'CAR est une entreprise privee senegalaise avec sa propre flotte. Reservez en ligne
                    votre transfert et votre location pour tout le sejour &mdash; un chauffeur GO'CAR vous
                    accueille des la sortie de l'avion.
                </p>

                <!-- Carte de recherche façon carte d'embarquement -->
                <form @submit.prevent="submitSearch" class="card mt-10 grid gap-4 p-6 md:grid-cols-[1.2fr_1fr_1fr_1fr_auto] md:items-end">
                    <div>
                        <label class="text-xs font-display font-semibold uppercase tracking-wide text-night-300">Formule</label>
                        <select v-model="search.formula" class="mt-1 w-full rounded-lg border-night-500/15 font-sans text-night-700 focus:border-runway-500 focus:ring-runway-500">
                            <option value="transfert_simple">Transfert aeroport simple</option>
                            <option value="transfert_plus_location">Transfert + location du sejour</option>
                            <option value="longue_duree">Location longue duree / tour</option>
                            <option value="location_locale">Location classique (residents)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-display font-semibold uppercase tracking-wide text-night-300">N&deg; de vol (optionnel)</label>
                        <input v-model="search.flight_number" type="text" placeholder="AF 718" class="mt-1 w-full rounded-lg border-night-500/15 focus:border-runway-500 focus:ring-runway-500" />
                    </div>
                    <div>
                        <label class="text-xs font-display font-semibold uppercase tracking-wide text-night-300">Arrivee</label>
                        <input v-model="search.start_at" type="date" class="mt-1 w-full rounded-lg border-night-500/15 focus:border-runway-500 focus:ring-runway-500" />
                    </div>
                    <div>
                        <label class="text-xs font-display font-semibold uppercase tracking-wide text-night-300">Restitution</label>
                        <input v-model="search.end_at" type="date" class="mt-1 w-full rounded-lg border-night-500/15 focus:border-runway-500 focus:ring-runway-500" />
                    </div>
                    <button type="submit" class="btn-primary h-fit w-full md:w-auto">Voir les vehicules</button>
                </form>
            </div>
        </section>

        <!-- Personas -->
        <section class="mx-auto max-w-6xl px-6 py-20">
            <h2 class="font-display text-2xl font-bold text-night-500">Pense d'abord pour vous</h2>
            <p class="mt-2 max-w-xl text-night-500/70">Deux profils guident chaque decision produit ; le troisieme reste bienvenu.</p>

            <div class="mt-10 grid gap-6 md:grid-cols-3">
                <div class="card p-6">
                    <span class="inline-block rounded-full bg-runway-500/10 px-3 py-1 font-display text-xs font-semibold text-runway-600">Priorite</span>
                    <h3 class="mt-4 font-display text-lg font-semibold text-night-500">Diaspora senegalaise</h3>
                    <p class="mt-2 text-sm text-night-500/70">Reservez depuis l'etranger, retrouvez votre chauffeur a la sortie, gardez le vehicule tout le sejour.</p>
                </div>
                <div class="card p-6">
                    <span class="inline-block rounded-full bg-runway-500/10 px-3 py-1 font-display text-xs font-semibold text-runway-600">Priorite</span>
                    <h3 class="mt-4 font-display text-lg font-semibold text-night-500">Touriste etranger</h3>
                    <p class="mt-2 text-sm text-night-500/70">Self-drive ou chauffeur-guide GO'CAR, itineraires suggeres (Lac Rose, Saly, Ile de Goree...).</p>
                </div>
                <div class="card p-6">
                    <span class="inline-block rounded-full bg-night-500/10 px-3 py-1 font-display text-xs font-semibold text-night-500">Aussi servi</span>
                    <h3 class="mt-4 font-display text-lg font-semibold text-night-500">Client local</h3>
                    <p class="mt-2 text-sm text-night-500/70">Location classique courte ou longue duree, paiement Wave / Orange Money.</p>
                </div>
            </div>
        </section>

        <!-- Formules -->
        <section id="formules" class="bg-sand-100/60 py-20">
            <div class="mx-auto max-w-6xl px-6">
                <h2 class="font-display text-2xl font-bold text-night-500">Un seul contrat, tout le sejour</h2>
                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    <div class="card p-6">
                        <h3 class="font-display font-semibold text-night-500">1. Reservez avant de partir</h3>
                        <p class="mt-2 text-sm text-night-500/70">Choisissez votre vehicule GO'CAR et payez en ligne (carte ou mobile money).</p>
                    </div>
                    <div class="card p-6">
                        <h3 class="font-display font-semibold text-night-500">2. Accueil a l'aeroport</h3>
                        <p class="mt-2 text-sm text-night-500/70">Un chauffeur salarie GO'CAR vous attend avec votre nom et votre photo dans l'app web.</p>
                    </div>
                    <div class="card p-6">
                        <h3 class="font-display font-semibold text-night-500">3. Gardez le vehicule</h3>
                        <p class="mt-2 text-sm text-night-500/70">Prolongez ou modifiez votre location en ligne jusqu'a votre retour.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-6 py-20 text-center">
            <h2 class="font-display text-2xl font-bold text-night-500">Prêt a organiser votre arrivee ?</h2>
            <Link href="/vehicules" class="btn-primary mt-6 inline-flex">Decouvrir la flotte GO'CAR</Link>
        </section>
    </AppLayout>
</template>
