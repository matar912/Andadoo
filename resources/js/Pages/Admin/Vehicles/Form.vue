<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ vehicle: Object });
const page = usePage();
const adminBase = () => `/${page.props.adminPath ?? ''}`;
const isEdit = !!props.vehicle;

const form = useForm({
    brand: props.vehicle?.brand ?? '',
    model: props.vehicle?.model ?? '',
    year: props.vehicle?.year ?? new Date().getFullYear(),
    plate_number: props.vehicle?.plate_number ?? '',
    category: props.vehicle?.category ?? 'berline',
    seats: props.vehicle?.seats ?? 4,
    transmission: props.vehicle?.transmission ?? 'manuelle',
    daily_price: props.vehicle?.daily_price ?? '',
    status: props.vehicle?.status ?? 'disponible',
    description: props.vehicle?.description ?? '',
    photo: null,
});

// Apercu local du fichier choisi ; retombe sur la photo deja enregistree en edition.
const fileInput = ref(null);
const localPreview = ref(null);
const currentPhotoUrl = props.vehicle?.photo_path ? `/vehicule-photo/${props.vehicle.photo_path}` : null;
const previewUrl = computed(() => localPreview.value ?? currentPhotoUrl);

function onPhotoChange(e) {
    const file = e.target.files[0];
    form.photo = file ?? null;
    localPreview.value = file ? URL.createObjectURL(file) : null;
}

function submit() {
    // forceFormData : necessaire des qu'un fichier est present dans le payload.
    form.post(isEdit ? `${adminBase()}/vehicules/${props.vehicle.id}` : `${adminBase()}/vehicules`, {
        forceFormData: true,
    });
}
</script>

<template>
    <AdminLayout>
        <h1 class="font-display text-2xl font-bold text-white">
            {{ isEdit ? 'Modifier le véhicule' : 'Ajouter un véhicule' }}
        </h1>

        <form @submit.prevent="submit" class="mt-8 max-w-2xl space-y-6 rounded-xl2 border border-white/10 bg-night-700 p-6">
            <!-- Photo -->
            <div>
                <label class="text-xs uppercase tracking-wide text-sand-100/60">Photo du véhicule</label>
                <div class="mt-2 flex items-center gap-4">
                    <div class="flex h-24 w-32 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-night-900 text-sand-100/30">
                        <img v-if="previewUrl" :src="previewUrl" alt="Aperçu" class="h-full w-full object-cover" />
                        <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" class="h-8 w-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3 3h18v18H3V3Z" />
                        </svg>
                    </div>
                    <div>
                        <button type="button" @click="fileInput.click()" class="rounded-full border border-white/15 px-4 py-2 text-sm font-display font-medium text-white hover:border-runway-400">
                            {{ previewUrl ? 'Changer la photo' : 'Choisir une photo' }}
                        </button>
                        <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="onPhotoChange" />
                        <p class="mt-2 text-xs text-sand-100/40">JPG ou PNG, 4 Mo maximum.</p>
                        <p v-if="form.errors.photo" class="mt-1 text-xs text-runway-400">{{ form.errors.photo }}</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="text-xs uppercase tracking-wide text-sand-100/60">Marque</label>
                    <input v-model="form.brand" type="text" required class="mt-1 w-full rounded-lg border-white/10 bg-night-900 text-white focus:border-runway-400 focus:ring-runway-400" />
                    <p v-if="form.errors.brand" class="mt-1 text-xs text-runway-400">{{ form.errors.brand }}</p>
                </div>
                <div>
                    <label class="text-xs uppercase tracking-wide text-sand-100/60">Modèle</label>
                    <input v-model="form.model" type="text" required class="mt-1 w-full rounded-lg border-white/10 bg-night-900 text-white focus:border-runway-400 focus:ring-runway-400" />
                </div>
                <div>
                    <label class="text-xs uppercase tracking-wide text-sand-100/60">Année</label>
                    <input v-model.number="form.year" type="number" class="mt-1 w-full rounded-lg border-white/10 bg-night-900 text-white focus:border-runway-400 focus:ring-runway-400" />
                </div>
                <div>
                    <label class="text-xs uppercase tracking-wide text-sand-100/60">Immatriculation</label>
                    <input v-model="form.plate_number" type="text" required class="mt-1 w-full rounded-lg border-white/10 bg-night-900 text-white focus:border-runway-400 focus:ring-runway-400" />
                    <p v-if="form.errors.plate_number" class="mt-1 text-xs text-runway-400">{{ form.errors.plate_number }}</p>
                </div>
                <div>
                    <label class="text-xs uppercase tracking-wide text-sand-100/60">Catégorie</label>
                    <select v-model="form.category" class="mt-1 w-full rounded-lg border-white/10 bg-night-900 text-white focus:border-runway-400 focus:ring-runway-400">
                        <option value="berline">Berline</option>
                        <option value="suv">SUV</option>
                        <option value="4x4">4x4</option>
                        <option value="minibus">Minibus</option>
                        <option value="citadine">Citadine</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs uppercase tracking-wide text-sand-100/60">Places</label>
                    <input v-model.number="form.seats" type="number" min="1" class="mt-1 w-full rounded-lg border-white/10 bg-night-900 text-white focus:border-runway-400 focus:ring-runway-400" />
                </div>
                <div>
                    <label class="text-xs uppercase tracking-wide text-sand-100/60">Transmission</label>
                    <select v-model="form.transmission" class="mt-1 w-full rounded-lg border-white/10 bg-night-900 text-white focus:border-runway-400 focus:ring-runway-400">
                        <option value="manuelle">Manuelle</option>
                        <option value="automatique">Automatique</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs uppercase tracking-wide text-sand-100/60">Prix / jour (FCFA)</label>
                    <input v-model.number="form.daily_price" type="number" min="0" required class="mt-1 w-full rounded-lg border-white/10 bg-night-900 text-white focus:border-runway-400 focus:ring-runway-400" />
                </div>

                <div class="sm:col-span-2">
                    <label class="text-xs uppercase tracking-wide text-sand-100/60">Disponibilité</label>
                    <select v-model="form.status" class="mt-1 w-full rounded-lg border-white/10 bg-night-900 text-white focus:border-runway-400 focus:ring-runway-400">
                        <option value="disponible">Disponible à la réservation</option>
                        <option value="en_location">En location</option>
                        <option value="maintenance">En maintenance</option>
                        <option value="hors_service">Hors service</option>
                    </select>
                    <p class="mt-1 text-xs text-sand-100/50">
                        Seuls les véhicules "disponible" apparaissent dans le catalogue client.
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <label class="text-xs uppercase tracking-wide text-sand-100/60">Description (optionnel)</label>
                    <textarea v-model="form.description" rows="3" class="mt-1 w-full rounded-lg border-white/10 bg-night-900 text-white focus:border-runway-400 focus:ring-runway-400"></textarea>
                </div>
            </div>

            <button type="submit" :disabled="form.processing" class="btn-primary">
                <span v-if="!form.processing">{{ isEdit ? 'Enregistrer' : 'Ajouter à la flotte' }}</span>
                <span v-else>Envoi en cours...</span>
            </button>
        </form>
    </AdminLayout>
</template>
