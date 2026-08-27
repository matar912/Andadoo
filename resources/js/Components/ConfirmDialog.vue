<script setup>
import { ref } from 'vue';

const visible = ref(false);
const options = ref({ title: '', message: '', danger: false, confirmLabel: 'Confirmer' });
let resolvePromise = null;

function open(opts) {
    options.value = { title: '', message: '', danger: false, confirmLabel: 'Confirmer', ...opts };
    visible.value = true;
    return new Promise((resolve) => { resolvePromise = resolve; });
}

function confirm() {
    visible.value = false;
    resolvePromise?.(true);
}
function cancel() {
    visible.value = false;
    resolvePromise?.(false);
}

defineExpose({ open });
</script>

<template>
    <Teleport to="body">
        <Transition name="fade">
            <div v-if="visible" class="fixed inset-0 z-50 flex items-center justify-center bg-night-900/50 px-4" @click.self="cancel">
                <Transition name="pop" appear>
                    <div v-if="visible" class="w-full max-w-sm rounded-xl2 bg-white p-6 shadow-xl">
                        <h3 class="font-display text-lg font-semibold text-night-700">{{ options.title }}</h3>
                        <p class="mt-2 text-sm text-night-500/70">{{ options.message }}</p>
                        <div class="mt-6 flex justify-end gap-3">
                            <button @click="cancel" class="rounded-full px-4 py-2 text-sm font-display font-medium text-night-500 hover:bg-night-500/5">
                                Annuler
                            </button>
                            <button
                                @click="confirm"
                                class="rounded-full px-4 py-2 text-sm font-display font-semibold text-white"
                                :class="options.danger ? 'bg-red-600 hover:bg-red-700' : 'bg-runway-500 hover:bg-runway-600'"
                            >
                                {{ options.confirmLabel }}
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.15s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
.pop-enter-active { transition: all 0.18s cubic-bezier(0.34, 1.56, 0.64, 1); }
.pop-enter-from { opacity: 0; transform: scale(0.94) translateY(4px); }
</style>
