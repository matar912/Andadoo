<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
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

function isActive(href) {
    const path = page.url.split('?')[0];
    return href === '/' ? path === '/' : path.startsWith(href);
}

// Navigation horizontale classique en haut, alignee sur la maquette de marque.
const navItems = computed(() => user()
    ? [
        { href: '/', label: 'Accueil' },
        { href: '/vehicules', label: 'Véhicules' },
        { href: '/mes-reservations', label: 'Mes réservations' },
        { href: '/profil', label: 'Profil' },
    ]
    : [{ href: '/', label: 'Accueil' }]);
</script>

<template>
    <div class="min-h-screen bg-paper-50">
        <ToastContainer />

        <header class="sticky top-0 z-40 border-b border-forest-500/10 bg-paper-50/95 backdrop-blur">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <Link href="/" class="flex items-center gap-2.5 font-display text-lg font-bold tracking-tight text-forest-700">
                    <img src="/images/logo-icon.png" alt="Andadoo" class="h-9 w-9 object-contain" />
                    ANDADOO
                </Link>

                <nav class="hidden items-center gap-8 font-display text-sm font-medium text-forest-700 md:flex">
                    <Link
                        v-for="item in navItems"
                        :key="item.label"
                        :href="item.href"
                        class="relative pb-1 transition-colors hover:text-gold-600"
                        :class="isActive(item.href) ? 'text-forest-700' : 'text-forest-500/70'"
                    >
                        {{ item.label }}
                        <span v-if="isActive(item.href)" class="absolute inset-x-0 -bottom-[17px] h-0.5 rounded-full bg-gold-500" />
                    </Link>
                </nav>

                <!-- Connecte : profil + deconnexion -->
                <div v-if="user()" class="hidden items-center gap-3 md:flex">
                    <span class="text-sm text-forest-500/70">{{ user().name }}</span>
                    <Link href="/logout" method="post" as="button" class="btn-primary text-sm">Déconnexion</Link>
                </div>

                <!-- Invite : bouton Connexion, comme sur la maquette -->
                <div v-else class="hidden items-center gap-4 md:flex">
                    <Link href="/register" class="text-sm font-display font-medium text-forest-500 hover:text-gold-600">Créer un compte</Link>
                    <Link href="/login" class="btn-primary text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H3" />
                        </svg>
                        Connexion
                    </Link>
                </div>

                <button @click="mobileOpen = !mobileOpen" class="flex h-9 w-9 items-center justify-center rounded-full text-forest-500 md:hidden" aria-label="Menu">
                    <svg v-if="!mobileOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <Transition name="slide">
                <div v-if="mobileOpen" class="border-t border-forest-500/10 bg-paper-50 px-6 py-4 md:hidden">
                    <nav class="flex flex-col gap-1">
                        <Link
                            v-for="item in navItems"
                            :key="item.label"
                            :href="item.href"
                            @click="mobileOpen = false"
                            class="rounded-lg px-3 py-2 text-left font-display text-sm font-medium text-forest-700 transition-colors hover:bg-forest-500/5"
                        >
                            {{ item.label }}
                        </Link>
                        <Link v-if="user()" href="/logout" method="post" as="button" class="rounded-lg px-3 py-2 text-left font-display text-sm font-medium text-forest-500/60">
                            Déconnexion
                        </Link>
                        <template v-else>
                            <Link href="/register" @click="mobileOpen = false" class="rounded-lg px-3 py-2 text-left font-display text-sm font-medium text-forest-700">Créer un compte</Link>
                            <Link href="/login" @click="mobileOpen = false" class="btn-primary mt-1 justify-center text-sm">Connexion</Link>
                        </template>
                    </nav>
                </div>
            </Transition>
        </header>

        <main>
            <slot />
        </main>

        <footer class="mt-24 border-t border-forest-500/10 bg-forest-700 text-paper-100">
            <div class="mx-auto grid max-w-6xl gap-8 px-6 py-12 md:grid-cols-3">
                <div>
                    <p class="flex items-center gap-2 font-display text-lg font-bold text-white">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-paper-50 p-1">
                            <img src="/images/logo-icon.png" alt="Andadoo" class="h-full w-full object-contain" />
                        </span>
                        ANDADOO
                    </p>
                    <p class="mt-2 text-sm text-paper-100/70">
                        Location de véhicules avec accueil et prise en charge. Flotte propre, chauffeurs Andadoo.
                    </p>
                    <p class="mt-1 font-display text-xs uppercase tracking-widest text-gold-400">Votre route, notre engagement</p>
                </div>
                <div>
                    <p class="font-display text-sm font-semibold text-white">Diaspora & voyageurs</p>
                    <p class="mt-2 text-sm text-paper-100/70">Réservez avant votre départ, tout est prêt à votre arrivée à Dakar.</p>
                </div>
                <div>
                    <p class="font-display text-sm font-semibold text-white">Contact</p>
                    <p class="mt-2 text-sm text-paper-100/70">contact@andadoo.sn &middot; +221 XX XXX XX XX</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.slide-enter-active, .slide-leave-active { transition: all 0.2s ease; }
.slide-enter-from, .slide-leave-to { opacity: 0; transform: translateY(-8px); }
</style>
