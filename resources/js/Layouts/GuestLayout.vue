<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen flex-col items-center justify-center bg-paper-50 px-6 py-12">
        <Link href="/" class="mb-8 flex items-center gap-2 font-display text-xl font-bold text-forest-500">
            <img src="/images/logo-icon.png" alt="Andadoo" class="h-9 w-9 object-contain" />
            Andadoo
        </Link>

        <div class="w-full max-w-md">
            <slot />
        </div>
    </div>
</template>
