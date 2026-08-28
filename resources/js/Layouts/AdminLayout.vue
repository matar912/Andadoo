<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import ToastContainer from '@/Components/ToastContainer.vue';
import { useToast } from '@/composables/useToast';

const page = usePage();
const adminBase = () => `/${page.props.adminPath ?? ''}`;
const toast = useToast();
const mobileOpen = ref(false);

watch(
    () => page.props.flash?.success,
    (msg) => { if (msg) toast.success(msg); }
);

const navItems = computed(() => [
    { href: `${adminBase()}`, label: 'Tableau de bord' },
    { href: `${adminBase()}/vehicules`, label: 'Flotte' },
    { href: `${adminBase()}/reservations`, label: 'Réservations' },
]);

function isActive(href) {
    const path = page.url.split('?')[0];
    return path === href || (href !== adminBase() && path.startsWith(href));
}
</script>

<template>
    <div class="min-h-screen bg-forest-900">
        <ToastContainer />

        <!-- Menu admin en haut, pleinement responsive : liens visibles des md,
             menu deroulant en dessous. -->
        <header class="sticky top-0 z-40 border-b border-white/10 bg-forest-700">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <div class="flex items-center gap-8">
                    <span class="flex items-center gap-2 font-display text-sm font-semibold uppercase tracking-widest text-paper-300">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-paper-50 p-1">
                            <img src="/images/logo-icon.png" alt="Andadoo" class="h-full w-full object-contain" />
                        </span>
                        Andadoo &middot; Portail interne
                    </span>
                    <nav class="hidden items-center gap-1 md:flex">
                        <Link
                            v-for="item in navItems"
                            :key="item.label"
                            :href="item.href"
                            class="relative rounded-full px-4 py-2 font-display text-sm font-medium transition-colors duration-150"
                            :class="isActive(item.href) ? 'bg-gold-500 text-white' : 'text-paper-100/70 hover:bg-white/5 hover:text-white'"
                        >
                            {{ item.label }}
                        </Link>
                    </nav>
                </div>

                <Link :href="`${adminBase()}/logout`" method="post" as="button" class="hidden text-sm font-display text-paper-100/60 hover:text-gold-400 md:inline-block">
                    Déconnexion
                </Link>

                <button @click="mobileOpen = !mobileOpen" class="flex h-9 w-9 items-center justify-center rounded-full text-paper-100 md:hidden" aria-label="Menu">
                    <svg v-if="!mobileOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <Transition name="slide">
                <div v-if="mobileOpen" class="border-t border-white/10 bg-forest-700 px-6 py-4 md:hidden">
                    <nav class="flex flex-col gap-1">
                        <Link
                            v-for="item in navItems"
                            :key="item.label"
                            :href="item.href"
                            @click="mobileOpen = false"
                            class="rounded-lg px-3 py-2 font-display text-sm font-medium"
                            :class="isActive(item.href) ? 'bg-gold-500 text-white' : 'text-paper-100/70 hover:bg-white/5'"
                        >
                            {{ item.label }}
                        </Link>
                        <Link :href="`${adminBase()}/logout`" method="post" as="button" class="rounded-lg px-3 py-2 text-left font-display text-sm font-medium text-paper-100/50">
                            Déconnexion
                        </Link>
                    </nav>
                </div>
            </Transition>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-8 text-paper-50 sm:px-6 sm:py-10">
            <slot />
        </main>
    </div>
</template>

<style scoped>
.slide-enter-active, .slide-leave-active { transition: all 0.2s ease; }
.slide-enter-from, .slide-leave-to { opacity: 0; transform: translateY(-8px); }
</style>
