<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';

const props = defineProps({ vehicles: Object });
const page = usePage();
const adminBase = () => `/${page.props.adminPath ?? ''}`;
const confirmDialog = ref(null);
const removingId = ref(null);

const statusStyle = {
    disponible: 'bg-emerald-500/15 text-emerald-300',
    en_location: 'bg-gold-500/15 text-gold-300',
    maintenance: 'bg-amber-500/15 text-amber-300',
    hors_service: 'bg-white/10 text-paper-100/50',
};

function getPhotoUrl(photoPath) {
    if (!photoPath) return null;
    return photoPath.startsWith('http') ? photoPath : `/vehicule-photo/${photoPath}`;
}

async function remove(vehicle) {
    const hasReservations = vehicle.reservations_count > 0;

    const ok = await confirmDialog.value.open({
        title: hasReservations ? 'Retirer ce véhicule de la flotte active ?' : 'Supprimer définitivement ce véhicule ?',
        message: hasReservations
            ? `${vehicle.brand} ${vehicle.model} a déjà des réservations : il sera marqué "hors service" et n'apparaîtra plus dans le catalogue client, mais son historique est conservé.`
            : `${vehicle.brand} ${vehicle.model} (${vehicle.plate_number}) sera définitivement supprimé, aucune réservation n'y est liée.`,
        confirmLabel: hasReservations ? 'Retirer' : 'Supprimer',
        danger: true,
    });
    if (!ok) return;

    removingId.value = vehicle.id;
    router.delete(`${adminBase()}/vehicules/${vehicle.id}`, {
        preserveScroll: true,
        onFinish: () => (removingId.value = null),
    });
}
</script>

<template>
    <AdminLayout>
        <ConfirmDialog ref="confirmDialog" />

        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-2xl font-bold text-white">Flotte Andadoo</h1>
                <p class="mt-1 text-sm text-paper-100/60">
                    C'est le statut ici qui décide si un client peut réserver ce véhicule.
                </p>
            </div>
            <Link :href="`${adminBase()}/vehicules/nouveau`" class="btn-primary">+ Ajouter un véhicule</Link>
        </div>

        <div v-if="vehicles.data.length" class="mt-8 overflow-x-auto rounded-xl2 border border-white/10">
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead class="bg-forest-700 text-paper-100/60">
                    <tr>
                        <th class="px-4 py-3"></th>
                        <th class="px-4 py-3">Véhicule</th>
                        <th class="px-4 py-3">Catégorie</th>
                        <th class="px-4 py-3">Prix/jour</th>
                        <th class="px-4 py-3">Statut</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 bg-forest-700/40">
                    <tr
                        v-for="v in vehicles.data"
                        :key="v.id"
                        class="transition-all duration-200 hover:bg-white/[0.03]"
                        :class="{ 'opacity-40': v.status === 'hors_service' || removingId === v.id }"
                    >
                        <td class="px-4 py-3">
                            <div class="h-12 w-16 overflow-hidden rounded bg-forest-900 flex items-center justify-center">
                                <img v-if="v.photo_path" :src="getPhotoUrl(v.photo_path)" :alt="`${v.brand} ${v.model}`" class="h-full w-full object-cover" />
                                <span v-else class="text-[10px] font-display text-paper-100/40 uppercase">{{ v.brand?.slice(0, 3) }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-white">{{ v.brand }} {{ v.model }} <span class="text-paper-100/40">({{ v.year }})</span></td>
                        <td class="px-4 py-3 capitalize text-paper-100/80">{{ v.category }}</td>
                        <td class="px-4 py-3 text-paper-100/80">{{ v.daily_price?.toLocaleString('fr-FR') }} FCFA</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-1 text-xs capitalize" :class="statusStyle[v.status]">{{ v.status.replace('_', ' ') }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span v-if="removingId === v.id" class="text-xs text-paper-100/40">Traitement...</span>
                            <template v-else>
                                <Link :href="`${adminBase()}/vehicules/${v.id}/modifier`" class="mr-3 text-paper-100/70 transition-colors hover:text-white">Modifier</Link>
                                <button v-if="v.status !== 'hors_service'" @click="remove(v)" class="text-gold-400 transition-colors hover:text-gold-300">Retirer</button>
                            </template>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-else class="mt-16 flex flex-col items-center rounded-xl2 border border-dashed border-white/10 py-16 text-center">
            <p class="font-display font-semibold text-white">Aucun véhicule dans la flotte</p>
            <p class="mt-1 max-w-sm text-sm text-paper-100/50">Ajoutez votre premier véhicule pour qu'il apparaisse dans le catalogue client.</p>
            <Link :href="`${adminBase()}/vehicules/nouveau`" class="btn-primary mt-5">+ Ajouter un véhicule</Link>
        </div>
    </AdminLayout>
</template>
