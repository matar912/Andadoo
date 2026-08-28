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

const categories = [
    { key: 'citadine', label: 'Citadines', from: '25 000' },
    { key: 'berline', label: 'Berlines', from: '35 000' },
    { key: 'suv', label: 'SUV & 4x4', from: '45 000' },
    { key: 'minibus', label: 'Minibus', from: '60 000' },
];
</script>

<template>
    <AppLayout>
        <!-- Hero -->
        <section class="overflow-hidden bg-paper-50">
            <div class="mx-auto grid max-w-6xl items-center gap-12 px-6 py-16 md:py-20 lg:grid-cols-2">
                <div>
                    <h1 class="font-display text-4xl font-bold leading-[1.1] text-forest-700 md:text-5xl">
                        Réservez votre voiture, en toute <em class="text-gold-600 not-italic font-serif italic">liberté</em>.
                    </h1>
                    <p class="mt-5 max-w-md text-forest-500/75">
                        Andadoo vous accompagne partout au Sénégal avec des véhicules fiables, un accueil
                        garanti à l'aéroport et des tarifs transparents.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-8">
                        <div class="flex items-center gap-3">
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-forest-500/8 text-forest-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" /></svg>
                            </span>
                            <div>
                                <p class="font-display text-sm font-semibold text-forest-700">Sécurité assurée</p>
                                <p class="text-xs text-forest-500/60">Flotte entretenue par Andadoo</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-forest-500/8 text-forest-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            </span>
                            <div>
                                <p class="font-display text-sm font-semibold text-forest-700">Réservation rapide</p>
                                <p class="text-xs text-forest-500/60">En ligne, avant votre départ</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Illustration + widget de recherche -->
                <div class="relative">
                    <div class="absolute -right-6 -top-6 h-40 w-40 rounded-full bg-gold-400/25 blur-2xl" />
                    <div class="relative overflow-hidden rounded-xl2 bg-forest-500 p-10">
                        <div class="absolute inset-0 opacity-25" style="background-image: radial-gradient(circle at 20% 15%, #EEB548 0, transparent 45%);" />
                        <svg viewBox="0 0 200 100" class="relative mx-auto w-full max-w-xs text-paper-50/90" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 70h4l8-16a8 8 0 0 1 7-4h50a8 8 0 0 1 7 4l10 16h4a6 6 0 0 1 6 6v6a4 4 0 0 1-4 4h-8m-96 0h-8a4 4 0 0 1-4-4v-6a6 6 0 0 1 6-6Z" />
                            <circle cx="55" cy="80" r="8" />
                            <circle cx="140" cy="80" r="8" />
                            <path stroke-linecap="round" d="M38 58h80" />
                        </svg>
                    </div>

                    <form @submit.prevent="submitSearch" class="card relative -mt-8 mx-4 space-y-4 p-6 md:mx-8">
                        <div class="flex gap-2 border-b border-forest-500/10 pb-3">
                            <span class="rounded-full bg-forest-500 px-3 py-1.5 font-display text-xs font-semibold text-white">Location de voiture</span>
                            <span class="rounded-full px-3 py-1.5 font-display text-xs font-medium text-forest-500/60">Avec chauffeur</span>
                            <span class="rounded-full px-3 py-1.5 font-display text-xs font-medium text-forest-500/60">Transfert aéroport</span>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="text-xs font-display font-semibold uppercase tracking-wide text-forest-300">Arrivée</label>
                                <input v-model="search.start_at" type="date" class="mt-1 w-full rounded-lg border-forest-500/15 text-sm focus:border-gold-500 focus:ring-gold-500" />
                            </div>
                            <div>
                                <label class="text-xs font-display font-semibold uppercase tracking-wide text-forest-300">Restitution</label>
                                <input v-model="search.end_at" type="date" class="mt-1 w-full rounded-lg border-forest-500/15 text-sm focus:border-gold-500 focus:ring-gold-500" />
                            </div>
                        </div>

                        <button type="submit" class="btn-primary w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            Rechercher
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Bandeau de reassurance -->
        <section class="border-y border-forest-500/10 bg-white">
            <div class="mx-auto grid max-w-6xl gap-8 px-6 py-10 sm:grid-cols-2 lg:grid-cols-4">
                <div v-for="item in [
                    ['Large choix de véhicules', 'Des citadines aux SUV, trouvez le véhicule qui vous convient.'],
                    ['Meilleurs tarifs', 'Des prix transparents, sans frais cachés.'],
                    ['Présence partout', 'Récupérez votre voiture là où vous en avez besoin.'],
                    ['Support dédié', 'Une équipe disponible pour vous assister.'],
                ]" :key="item[0]">
                    <p class="font-display text-sm font-semibold text-forest-700">{{ item[0] }}</p>
                    <p class="mt-1 text-sm text-forest-500/60">{{ item[1] }}</p>
                </div>
            </div>
        </section>

        <!-- Categories + Comment ca marche -->
        <section class="mx-auto max-w-6xl px-6 py-20">
            <div class="grid gap-10 lg:grid-cols-[1fr_320px]">
                <div>
                    <div class="flex items-baseline justify-between">
                        <h2 class="font-display text-2xl font-bold text-forest-700">Nos catégories de véhicules</h2>
                        <Link href="/vehicules" class="font-display text-sm font-medium text-gold-600 hover:text-gold-700">Voir tous les véhicules &rarr;</Link>
                    </div>
                    <p class="mt-1 text-sm text-forest-500/60">Des véhicules adaptés à tous vos besoins.</p>

                    <div class="mt-8 grid gap-5 sm:grid-cols-2">
                        <Link
                            v-for="cat in categories"
                            :key="cat.key"
                            :href="`/vehicules?category=${cat.key}`"
                            class="card overflow-hidden"
                        >
                            <div class="flex h-28 items-center justify-center bg-paper-100 font-display text-forest-300">{{ cat.label }}</div>
                            <div class="p-4">
                                <p class="font-display font-semibold text-forest-700">{{ cat.label }}</p>
                                <p class="mt-0.5 text-xs text-forest-500/60">À partir de {{ cat.from }} FCFA / jour</p>
                            </div>
                        </Link>
                    </div>
                </div>

                <div class="rounded-xl2 bg-forest-700 p-7 text-paper-100">
                    <h3 class="font-display text-lg font-bold text-white">Comment ça marche ?</h3>
                    <ol class="mt-6 space-y-6">
                        <li v-for="(step, i) in [
                            ['Choisissez votre véhicule', 'Sélectionnez le véhicule qui correspond à vos besoins.'],
                            ['Réservez en ligne', 'Choisissez vos dates et validez votre réservation en quelques clics.'],
                            ['Récupérez et profitez', 'Récupérez votre véhicule et profitez pleinement de votre trajet.'],
                        ]" :key="step[0]" class="flex gap-4">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gold-500 font-display text-sm font-bold text-white">{{ i + 1 }}</span>
                            <div>
                                <p class="font-display text-sm font-semibold text-white">{{ step[0] }}</p>
                                <p class="mt-1 text-xs text-paper-100/70">{{ step[1] }}</p>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-6 pb-20 text-center">
            <h2 class="font-display text-2xl font-bold text-forest-700">Prêt à organiser votre arrivée ?</h2>
            <Link href="/vehicules" class="btn-primary mt-6 inline-flex">Découvrir la flotte Andadoo</Link>
        </section>
    </AppLayout>
</template>
