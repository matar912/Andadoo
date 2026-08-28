<script setup>
const props = defineProps({ status: String });

const steps = [
    { key: 'en_attente', label: 'Demande envoyée' },
    { key: 'confirmee', label: 'Confirmée par GO\u2019CAR' },
    { key: 'en_cours', label: 'Séjour en cours' },
    { key: 'terminee', label: 'Terminée' },
];

const order = ['en_attente', 'confirmee', 'en_cours', 'terminee'];

function stepState(key) {
    if (props.status === 'annulee') return 'cancelled';
    const current = order.indexOf(props.status);
    const idx = order.indexOf(key);
    if (idx < current) return 'done';
    if (idx === current) return 'current';
    return 'upcoming';
}
</script>

<template>
    <div v-if="status === 'annulee'" class="flex items-center gap-3 rounded-xl2 border border-red-200 bg-red-50 px-4 py-3">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5 text-red-600">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <p class="text-sm font-medium text-red-700">Cette demande a été refusée ou annulée.</p>
    </div>

    <ol v-else class="flex items-start">
        <li v-for="(s, i) in steps" :key="s.key" class="flex flex-1 flex-col items-center text-center last:flex-none">
            <div class="flex w-full items-center">
                <div
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 font-display text-xs font-semibold transition-colors"
                    :class="{
                        'border-gold-500 bg-gold-500 text-white': stepState(s.key) === 'done',
                        'border-gold-500 bg-white text-gold-600 ring-4 ring-gold-500/15': stepState(s.key) === 'current',
                        'border-forest-500/15 bg-white text-forest-500/30': stepState(s.key) === 'upcoming',
                    }"
                >
                    <svg v-if="stepState(s.key) === 'done'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                    </svg>
                    <span v-else>{{ i + 1 }}</span>
                </div>
                <div
                    v-if="i < steps.length - 1"
                    class="h-0.5 flex-1 transition-colors"
                    :class="stepState(s.key) === 'done' ? 'bg-gold-500' : 'bg-forest-500/10'"
                />
            </div>
            <p
                class="mt-2 max-w-[6.5rem] text-xs font-display font-medium"
                :class="stepState(s.key) === 'upcoming' ? 'text-forest-500/40' : 'text-forest-500'"
            >
                {{ s.label }}
            </p>
        </li>
    </ol>
</template>
