<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    items: { type: Array, required: true }, // [{ href, label, method? }]
    theme: { type: String, default: 'light' }, // 'light' (client) | 'dark' (admin)
});

const page = usePage();

function isActive(href) {
    const path = page.url.split('?')[0];
    if (href === '/') return path === '/';
    return path === href || (href.length > 1 && path.startsWith(href));
}
</script>

<template>
    <nav class="fixed right-5 top-1/2 z-30 hidden -translate-y-1/2 lg:block" aria-label="Navigation principale">
        <ul
            class="flex flex-col gap-1 rounded-2xl border p-2 shadow-lg backdrop-blur"
            :class="theme === 'dark' ? 'border-white/10 bg-night-700/90' : 'border-night-500/10 bg-white/90'"
        >
            <li v-for="item in items" :key="item.label">
                <Link
                    :href="item.href"
                    :method="item.method"
                    :as="item.method ? 'button' : undefined"
                    class="relative block whitespace-nowrap rounded-xl px-4 py-2.5 text-right text-sm font-display font-medium transition-colors duration-150"
                    :class="isActive(item.href)
                        ? (theme === 'dark' ? 'bg-runway-500 text-white' : 'bg-runway-500 text-white')
                        : (theme === 'dark' ? 'text-sand-100/60 hover:bg-white/5 hover:text-white' : 'text-night-500/70 hover:bg-night-500/5 hover:text-night-700')"
                >
                    {{ item.label }}
                    <!-- Marque de position : la page ou l'on se trouve -->
                    <span
                        v-if="isActive(item.href)"
                        class="absolute -left-1 top-1/2 h-1.5 w-1.5 -translate-y-1/2 -translate-x-2 rounded-full"
                        :class="theme === 'dark' ? 'bg-runway-300' : 'bg-runway-600'"
                    />
                </Link>
            </li>
        </ul>
    </nav>
</template>
