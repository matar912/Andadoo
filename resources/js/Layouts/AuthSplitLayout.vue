<script setup>
defineProps({
    eyebrow: { type: String, default: '' },
    title: { type: String, default: '' },
    subtitle: { type: String, default: '' },
});
</script>

<template>
    <div class="grid min-h-screen lg:grid-cols-2">
        <!-- Souche de carte d'embarquement : panneau gauche, signature visuelle -->
        <aside class="relative hidden overflow-hidden bg-forest-500 px-12 py-14 text-paper-100 lg:flex lg:flex-col">
            <div
                class="pointer-events-none absolute inset-0 opacity-25"
                style="background-image: radial-gradient(circle at 15% 15%, #F2A24A 0, transparent 40%), radial-gradient(circle at 85% 85%, #5F988E 0, transparent 45%);"
            />

            <!-- Souche perforee : simule le detachement du billet, collee au bord droit de CE panneau -->
            <div
                class="pointer-events-none absolute inset-y-0 -right-px w-px"
                style="background-image: radial-gradient(circle, theme(colors.paper.50) 5px, transparent 5.5px); background-size: 100% 22px; background-position: center;"
            />

            <div class="relative flex items-center gap-2 font-display text-lg font-bold text-white">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-paper-50 p-1.5">
                    <img src="/images/logo-icon.png" alt="Andadoo" class="h-full w-full object-contain" />
                </span>
                Andadoo
            </div>

            <div class="relative mt-auto max-w-sm">
                <p class="font-display text-xs font-semibold uppercase tracking-[0.2em] text-gold-400">{{ eyebrow }}</p>
                <h1 class="mt-4 font-display text-3xl font-bold leading-tight text-white">{{ title }}</h1>
                <p class="mt-4 text-sm leading-relaxed text-paper-100/70">{{ subtitle }}</p>

                <div class="mt-8">
                    <slot name="aside" />
                </div>
            </div>

            <!-- Piste en pointillés reliant l'aéroport de départ à Dakar -->
            <div class="relative mt-10 flex items-center gap-3 text-xs text-paper-100/50">
                <span class="font-display tracking-widest">DEPART</span>
                <span class="relative h-px flex-1 overflow-hidden bg-[repeating-linear-gradient(to_right,theme(colors.paper.100/40)_0,theme(colors.paper.100/40)_5px,transparent_5px,transparent_11px)]">
                    <span class="absolute -top-[7px] h-3.5 w-3.5 animate-fly text-gold-400">✈</span>
                </span>
                <span class="font-display tracking-widest text-gold-400">DSS · DAKAR</span>
            </div>
        </aside>

        <!-- Panneau formulaire -->
        <main class="flex flex-col justify-center bg-paper-50 px-6 py-14 sm:px-12 lg:px-16">
            <div class="mx-auto w-full max-w-sm">
                <slot />
            </div>
        </main>
    </div>
</template>

<style scoped>
@keyframes fly {
    0%, 100% { left: 2%; }
    50% { left: 92%; }
}
.animate-fly {
    animation: fly 5s ease-in-out infinite;
}
@media (prefers-reduced-motion: reduce) {
    .animate-fly { animation: none; left: 50%; }
}
</style>
