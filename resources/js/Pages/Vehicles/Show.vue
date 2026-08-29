<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({ vehicle: Object });

function getPhotoUrl(photoPath) {
    if (!photoPath) return null;
    return photoPath.startsWith('http') ? photoPath : `/vehicule-photo/${photoPath}`;
}
</script>

<template>
    <AppLayout>
        <section class="mx-auto max-w-4xl px-6 py-14">
            <Link href="/vehicules" class="text-sm font-display font-medium text-forest-500/60 hover:text-gold-600">&larr; Retour au catalogue</Link>

            <div class="card mt-4 overflow-hidden md:grid md:grid-cols-2">
                <div class="flex h-64 items-center justify-center bg-paper-100 md:h-full">
                    <img v-if="vehicle.photo_path" :src="getPhotoUrl(vehicle.photo_path)" :alt="`${vehicle.brand} ${vehicle.model}`" class="h-full w-full object-cover" />
                    <span v-else class="font-display text-forest-300">{{ vehicle.brand }} {{ vehicle.model }}</span>
                </div>

                <div class="p-6">
                    <p class="font-display text-xs uppercase tracking-widest text-gold-600 capitalize">{{ vehicle.category }}</p>
                    <h1 class="mt-1 font-display text-2xl font-bold text-forest-700">{{ vehicle.brand }} {{ vehicle.model }}</h1>
                    <p class="mt-1 text-sm text-forest-500/60">{{ vehicle.year }} &middot; {{ vehicle.seats }} places &middot; {{ vehicle.transmission }}</p>

                    <p v-if="vehicle.description" class="mt-4 text-sm leading-relaxed text-forest-500/80">{{ vehicle.description }}</p>

                    <div class="mt-6 flex items-center justify-between border-t border-forest-500/10 pt-6">
                        <span class="font-display text-2xl font-bold text-gold-600">{{ vehicle.daily_price?.toLocaleString('fr-FR') }} FCFA<span class="text-sm font-normal text-forest-500/50">/jour</span></span>
                        <Link :href="`/reservations/nouvelle?vehicle_id=${vehicle.id}`" class="btn-primary">Réserver</Link>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
