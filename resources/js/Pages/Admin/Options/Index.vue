<script setup>
import { router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';

const props = defineProps({ options: Array });
const page = usePage();
const adminBase = () => `/${page.props.adminPath ?? ''}`;
const confirmDialog = ref(null);

const createForm = useForm({ name: '', extra_price: '' });
const editingId = ref(null);
const editForm = useForm({ name: '', extra_price: '' });

function submitCreate() {
    createForm.post(`${adminBase()}/options`, {
        preserveScroll: true,
        onSuccess: () => createForm.reset(),
    });
}

function startEdit(option) {
    editingId.value = option.id;
    editForm.name = option.name;
    editForm.extra_price = option.extra_price;
}

function submitEdit(option) {
    editForm.put(`${adminBase()}/options/${option.id}`, {
        preserveScroll: true,
        onSuccess: () => (editingId.value = null),
    });
}

async function remove(option) {
    const ok = await confirmDialog.value.open({
        title: 'Supprimer cette option ?',
        message: `"${option.name}" ne sera plus proposée aux clients lors d'une réservation.`,
        confirmLabel: 'Supprimer',
        danger: true,
    });
    if (ok) router.delete(`${adminBase()}/options/${option.id}`, { preserveScroll: true });
}
</script>

<template>
    <AdminLayout>
        <ConfirmDialog ref="confirmDialog" />

        <h1 class="font-display text-2xl font-bold text-white">Options de réservation</h1>
        <p class="mt-1 text-sm text-paper-100/60">
            Ces options sont proposées au client au moment de réserver (siège enfant, GPS, assurance renforcée...).
        </p>

        <form @submit.prevent="submitCreate" class="mt-6 flex flex-wrap items-end gap-3 rounded-xl2 border border-white/10 bg-forest-700 p-5">
            <div class="flex-1">
                <label class="text-xs uppercase tracking-wide text-paper-100/60">Nom de l'option</label>
                <input v-model="createForm.name" type="text" placeholder="Siège enfant" required class="mt-1 w-full rounded-lg border-white/10 bg-forest-900 text-white focus:border-gold-400 focus:ring-gold-400" />
            </div>
            <div>
                <label class="text-xs uppercase tracking-wide text-paper-100/60">Prix (FCFA)</label>
                <input v-model.number="createForm.extra_price" type="number" min="0" required class="mt-1 w-40 rounded-lg border-white/10 bg-forest-900 text-white focus:border-gold-400 focus:ring-gold-400" />
            </div>
            <button type="submit" :disabled="createForm.processing" class="btn-primary">+ Ajouter</button>
        </form>

        <div v-if="options.length" class="mt-6 overflow-x-auto rounded-xl2 border border-white/10">
            <table class="w-full min-w-[480px] text-left text-sm">
                <thead class="bg-forest-700 text-paper-100/60">
                    <tr>
                        <th class="px-4 py-3">Nom</th>
                        <th class="px-4 py-3">Prix</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 bg-forest-700/40">
                    <tr v-for="o in options" :key="o.id">
                        <td class="px-4 py-3">
                            <input v-if="editingId === o.id" v-model="editForm.name" type="text" class="w-full rounded-lg border-white/10 bg-forest-900 text-white" />
                            <span v-else class="text-white">{{ o.name }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <input v-if="editingId === o.id" v-model.number="editForm.extra_price" type="number" class="w-28 rounded-lg border-white/10 bg-forest-900 text-white" />
                            <span v-else class="text-paper-100/80">{{ o.extra_price }} FCFA</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <template v-if="editingId === o.id">
                                <button @click="submitEdit(o)" class="mr-3 font-display text-emerald-400 hover:text-emerald-300">Enregistrer</button>
                                <button @click="editingId = null" class="font-display text-paper-100/50">Annuler</button>
                            </template>
                            <template v-else>
                                <button @click="startEdit(o)" class="mr-3 font-display text-paper-100/70 hover:text-white">Modifier</button>
                                <button @click="remove(o)" class="font-display text-gold-400 hover:text-gold-300">Supprimer</button>
                            </template>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p v-else class="mt-10 text-center text-sm text-paper-100/50">Aucune option pour le moment — ajoutez-en une ci-dessus.</p>
    </AdminLayout>
</template>
