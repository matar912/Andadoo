<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import NavRail from '@/Components/NavRail.vue';
import ToastContainer from '@/Components/ToastContainer.vue';
import { useToast } from '@/composables/useToast';

const page = usePage();
const user = () => page.props.auth?.user;
const mobileOpen = ref(false);
const toast = useToast();

watch(
    () => page.props.flash?.success,
    (msg) => { if (msg) toast.success(msg); }
);

// Le rail de navigation n'a de sens qu'une fois connecte : avant ca, il n'y a
// que la page d'accueil et les boutons connexion/inscription a proposer.
const navItems = computed(() => [
    { href: '/', label: 'Accueil' },
    { href: '/vehicules', label: 'Véhicules' },
    { href: '/mes-reservations', label: 'Mes réservations' },
    { href: '/profil', label: 'Profil' },
    { href: '/logout', label: 'Déconnexion', method: 'post' },
]);
</script>

<template>
    <div class="min-h-screen bg-sand-50">
        <ToastContainer />

        <!-- Rail vertical a droite : uniquement pour les utilisateurs connectes -->
        <NavRail v-if="user()" :items="navItems" theme="light" />

        <header class="sticky top-0 z-40 border-b border-night-500/10 bg-sand-50/90 backdrop-blur">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <Link href="/" class="flex items-center gap-2 font-display text-xl font-bold text-night-500">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-runway-500 text-white">GC</span>
                    GO'CAR
                </Link>

                <!-- Connecte : juste le nom ici, la navigation vit dans le rail a droite -->
                <div v-if="user()" class="hidden items-center gap-2 lg:flex">
                    <span class="text-sm text-night-500/70">{{ user().name }}</span>
                </div>

                <!-- Invite : boutons connexion / inscription toujours visibles, a toute taille d'ecran -->
                <div v-else class="flex items-center gap-2 sm:gap-3">
                    <Link href="/login" class="text-sm font-display font-medium text-night-500 hover:text-runway-600">Se connecter</Link>
                    <Link href="/register" class="btn-primary text-sm">Créer un compte</Link>
                </div>

                <!-- Menu mobile/tablette : uniquement utile aux utilisateurs connectes (le rail est cache en dessous de xl) -->
                <button v-if="user()" @click="mobileOpen = !mobileOpen" class="flex h-9 w-9 items-center justify-center rounded-full text-night-500 lg:hidden" aria-label="Menu">
                    <svg v-if="!mobileOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Panneau mobile/tablette : memes items que le rail, empiles verticalement -->
            <Transition name="slide">
                <div v-if="mobileOpen && user()" class="border-t border-night-500/10 bg-sand-50 px-6 py-4 lg:hidden">
                    <nav class="flex flex-col gap-1">
                        <Link
                            v-for="item in navItems"
                            :key="item.label"
                            :href="item.href"
                            :method="item.method"
                            :as="item.method ? 'button' : undefined"
                            @click="mobileOpen = false"
                            class="rounded-lg px-3 py-2 text-left font-display text-sm font-medium text-night-500 transition-colors hover:bg-night-500/5"
                        >
                            {{ item.label }}
                        </Link>
                    </nav>
                </div>
            </Transition>
        </header>

        <main :class="{ 'lg:pr-32': user() }">
            <slot />
        </main>

        <footer class="mt-24 border-t border-night-500/10 bg-night-500 text-sand-100">
            <div class="mx-auto grid max-w-6xl gap-8 px-6 py-12 md:grid-cols-3">
                <div>
                    <p class="font-display text-lg font-bold text-white">GO'CAR</p>
                    <p class="mt-2 text-sm text-sand-100/70">
                        Location de véhicules avec accueil et prise en charge. Flotte propre, chauffeurs GO'CAR.
                    </p>
                </div>
                <div>
                    <p class="font-display text-sm font-semibold text-white">Diaspora & voyageurs</p>
                    <p class="mt-2 text-sm text-sand-100/70">Réservez avant votre départ, tout est prêt à votre arrivée à Dakar.</p>
                </div>
                <div>
                    <p class="font-display text-sm font-semibold text-white">Contact</p>
                    <p class="mt-2 text-sm text-sand-100/70">contact@gocar.sn &middot; +221 XX XXX XX XX</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.slide-enter-active, .slide-leave-active { transition: all 0.2s ease; }
.slide-enter-from, .slide-leave-to { opacity: 0; transform: translateY(-8px); }
</style>
