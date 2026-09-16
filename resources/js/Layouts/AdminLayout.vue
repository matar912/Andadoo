<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import ToastContainer from '@/Components/ToastContainer.vue';
import { useToast } from '@/composables/useToast';

const page = usePage();
const adminBase = () => `/${page.props.adminPath ?? ''}`;
const toast = useToast();
const mobileOpen = ref(false);
const user = () => page.props.auth?.user;

watch(() => page.props.flash?.success, (msg) => { if (msg) toast.success(msg); });
watch(() => page.props.flash?.error, (msg) => { if (msg) toast.error(msg); });

// Regroupe le menu par section, a l'image d'un back-office professionnel :
// pilotage global d'un cote, gestion operationnelle de l'autre.
const navGroups = computed(() => [
    {
        label: 'Pilotage',
        items: [{ href: `${adminBase()}`, label: 'Tableau de bord', icon: 'grid' }],
    },
    {
        label: 'Gestion',
        items: [
            { href: `${adminBase()}/vehicules`, label: 'Flotte', icon: 'car' },
            { href: `${adminBase()}/reservations`, label: 'Réservations', icon: 'calendar' },
            { href: `${adminBase()}/paiements`, label: 'Paiements', icon: 'card' },
            { href: `${adminBase()}/options`, label: 'Options', icon: 'tag' },
            { href: `${adminBase()}/partenaires`, label: 'Partenaires', icon: 'handshake' },
        ],
    },
]);

const icons = {
    grid: 'M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z',
    car: 'M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 0h-12',
    calendar: 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5',
    card: 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-9-9.75h16.5a1.5 1.5 0 0 1 1.5 1.5v9a1.5 1.5 0 0 1-1.5 1.5H3.75a1.5 1.5 0 0 1-1.5-1.5v-9a1.5 1.5 0 0 1 1.5-1.5Z',
    tag: 'M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.169.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z M6 6h.008v.008H6V6Z',
    handshake: 'M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m4.5-9L16.5 3m0 0L12 7.5M16.5 3v13.5',
};

function isActive(href) {
    const path = page.url.split('?')[0];
    return path === href || (href !== adminBase() && path.startsWith(href));
}
</script>

<template>
    <div class="flex min-h-screen bg-forest-900">
        <ToastContainer />

        <!-- Sidebar desktop -->
        <aside class="fixed inset-y-0 left-0 z-40 hidden w-64 flex-col bg-forest-700 lg:flex">
            <div class="flex items-center gap-2 px-6 py-6">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-paper-50 p-1.5">
                    <img src="/images/logo-icon.png" alt="Andadoo" class="h-full w-full object-contain" />
                </span>
                <div>
                    <p class="font-display text-sm font-bold text-white">ANDADOO</p>
                    <p class="text-[11px] uppercase tracking-widest text-gold-400">Portail interne</p>
                </div>
            </div>

            <nav class="mt-4 flex-1 space-y-6 overflow-y-auto px-4">
                <div v-for="group in navGroups" :key="group.label">
                    <p class="px-3 text-[11px] font-display font-semibold uppercase tracking-widest text-paper-100/35">{{ group.label }}</p>
                    <div class="mt-2 space-y-0.5">
                        <Link
                            v-for="item in group.items"
                            :key="item.label"
                            :href="item.href"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-display text-sm font-medium transition-colors duration-150"
                            :class="isActive(item.href) ? 'bg-gold-500 text-white' : 'text-paper-100/70 hover:bg-white/5 hover:text-white'"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-5 w-5 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" :d="icons[item.icon]" />
                            </svg>
                            {{ item.label }}
                        </Link>
                    </div>
                </div>
            </nav>

            <div class="flex items-center gap-3 border-t border-white/10 px-4 py-4">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gold-500 font-display text-sm font-bold text-white">
                    {{ (user()?.name ?? 'A').charAt(0) }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-display font-semibold text-white">{{ user()?.name ?? 'Admin' }}</p>
                    <p class="text-[11px] uppercase tracking-widest text-paper-100/40">Administrateur</p>
                </div>
                <Link :href="`${adminBase()}/logout`" method="post" as="button" title="Déconnexion" class="text-paper-100/50 hover:text-gold-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3H21" />
                    </svg>
                </Link>
            </div>
        </aside>

        <!-- Topbar mobile -->
        <header class="fixed inset-x-0 top-0 z-40 flex items-center justify-between border-b border-white/10 bg-forest-700 px-4 py-3 lg:hidden">
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-paper-50 p-1">
                    <img src="/images/logo-icon.png" alt="Andadoo" class="h-full w-full object-contain" />
                </span>
                <p class="font-display text-sm font-bold text-white">ANDADOO</p>
            </div>
            <button @click="mobileOpen = !mobileOpen" class="text-paper-100" aria-label="Menu">
                <svg v-if="!mobileOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </header>

        <!-- Panneau mobile -->
        <Transition name="fade">
            <div v-if="mobileOpen" class="fixed inset-0 z-30 bg-forest-900/60 lg:hidden" @click="mobileOpen = false">
                <nav class="h-full w-72 space-y-6 bg-forest-700 px-4 pt-20 pb-6" @click.stop>
                    <div v-for="group in navGroups" :key="group.label">
                        <p class="px-3 text-[11px] font-display font-semibold uppercase tracking-widest text-paper-100/35">{{ group.label }}</p>
                        <div class="mt-2 space-y-0.5">
                            <Link
                                v-for="item in group.items"
                                :key="item.label"
                                :href="item.href"
                                @click="mobileOpen = false"
                                class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-display text-sm font-medium"
                                :class="isActive(item.href) ? 'bg-gold-500 text-white' : 'text-paper-100/70'"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-5 w-5 shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" :d="icons[item.icon]" />
                                </svg>
                                {{ item.label }}
                            </Link>
                        </div>
                    </div>
                    <Link :href="`${adminBase()}/logout`" method="post" as="button" class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-display text-sm font-medium text-paper-100/50">
                        Déconnexion
                    </Link>
                </nav>
            </div>
        </Transition>

        <main class="min-h-screen flex-1 px-4 py-6 pt-20 text-paper-50 sm:px-6 lg:pl-72 lg:pt-8">
            <div class="mx-auto max-w-6xl">
                <slot />
            </div>
        </main>
    </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.15s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
