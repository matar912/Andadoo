<script setup>
defineProps({
    modelValue: String,
    label: String,
    type: { type: String, default: 'text' },
    error: String,
    autofocus: Boolean,
    placeholder: String,
    icon: { type: String, default: 'user' }, // user | mail | lock | phone
});
defineEmits(['update:modelValue']);

const paths = {
    user: 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z',
    mail: 'M2.25 6.75c0-.966.784-1.75 1.75-1.75h16c.966 0 1.75.784 1.75 1.75v10.5a1.75 1.75 0 0 1-1.75 1.75H4A1.75 1.75 0 0 1 2.25 17.25V6.75Zm1.87-.25 7.88 5.51 7.88-5.51H4.12ZM3.75 8.15v9.1c0 .138.112.25.25.25h16a.25.25 0 0 0 .25-.25v-9.1l-7.87 5.51a.75.75 0 0 1-.86 0L3.75 8.15Z',
    lock: 'M12 1.5a4.5 4.5 0 0 0-4.5 4.5v3H6A2.25 2.25 0 0 0 3.75 11.25v8.25A2.25 2.25 0 0 0 6 21.75h12a2.25 2.25 0 0 0 2.25-2.25v-8.25A2.25 2.25 0 0 0 18 9h-1.5V6A4.5 4.5 0 0 0 12 1.5Zm3 7.5V6a3 3 0 1 0-6 0v3h6Z',
    phone: 'M2.25 4.5a2.25 2.25 0 0 1 2.25-2.25h2.086c.55 0 1.02.398 1.11.94l.879 5.276a1.125 1.125 0 0 1-.57 1.183l-1.756.878a17.25 17.25 0 0 0 7.72 7.72l.878-1.756a1.125 1.125 0 0 1 1.183-.57l5.277.88c.541.09.94.558.94 1.109V19.5a2.25 2.25 0 0 1-2.25 2.25H18C9.34 21.75 2.25 14.66 2.25 6V4.5Z',
};
</script>

<template>
    <div>
        <label v-if="label" class="text-xs font-display font-semibold uppercase tracking-wide text-forest-300">{{ label }}</label>
        <div class="relative mt-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-forest-300">
                <path :d="paths[icon]" />
            </svg>
            <input
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                :type="type"
                :autofocus="autofocus"
                :placeholder="placeholder"
                class="w-full rounded-lg border-forest-500/15 pl-10 text-forest-700 placeholder-forest-300/60 focus:border-gold-500 focus:ring-gold-500"
            />
        </div>
        <p v-if="error" class="mt-1 text-xs text-red-600">{{ error }}</p>
    </div>
</template>
