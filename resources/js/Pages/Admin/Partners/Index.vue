<script setup>
import { router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';

const props = defineProps({ partners: Array });
const page = usePage();
const adminBase = () => `/${page.props.adminPath ?? ''}`;
const confirmDialog = ref(null);
const showForm = ref(false);
const editing = ref(null);

const typeLabels = {
    agence_voyage: 'Agence de voyage',
    hotel: 'Hôtel',
    compagnie_aerienne: 'Compagnie aérienne',
    autre: 'Autre',
};

const form = useForm({ name: '', type: 'agence_voyage', contact_email: '', contact_phone: '' });

function openCreate() {
    editing.value = null;
    form.reset();
    showForm.value = true;
}

function openEdit(partner) {
    editing.value = partner;
    form.name = partner.name;
    form.type = partner.type;
    form.contact_email = partner.contact_email ?? '';
    form.contact_phone = partner.contact_phone ?? '';
    showForm.value = true;
}

function submit() {
    const opts = { preserveScroll: true, onSuccess: () => (showForm.value = false) };
    if (editing.value) {
        form.put(`${adminBase()}/partenaires/${editing.value.id}`, opts);
    } else {
        form.post(`${adminBase()}/partenaires`, opts);
    }
}

async function remove(partner) {
    const ok = await confirmDialog.value.open({
        title: 'Supprimer ce partenaire ?',
        message: `"${partner.name}" sera retiré de la liste des partenaires commerciaux.`,
        confirmLabel: 'Supprimer',
        danger: true,
    });
    if (ok) router.delete(`${adminBase()}/partenaires/${partner.id}`, { preserveScroll: true });
}
</script>

<template>
    <AdminLayout>
        <ConfirmDialog ref="confirmDialog" />

        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-2xl font-bold text-white">Partenaires commerciaux</h1>
                <p class="mt-1 text-sm text-paper-100/60">
                    Agences de voyage, hôtels, compagnies aériennes — pour suivre l'origine des réservations. Jamais des apporteurs de véhicules.
                </p>
            </div>
            <button @click="openCreate" class="btn-primary">+ Ajouter un partenaire</button>
        </div>

        <!-- Formulaire (creation ou edition) -->
        <form v-if="showForm" @submit.prevent="submit" class="mt-6 grid gap-4 rounded-xl2 border border-white/10 bg-forest-700 p-5 sm:grid-cols-2">
            <div>
                <label class="text-xs uppercase tracking-wide text-paper-100/60">Nom</label>
                <input v-model="form.name" type="text" required class="mt-1 w-full rounded-lg border-white/10 bg-forest-900 text-white focus:border-gold-400 focus:ring-gold-400" />
                <p v-if="form.errors.name" class="mt-1 text-xs text-gold-400">{{ form.errors.name }}</p>
            </div>
            <div>
                <label class="text-xs uppercase tracking-wide text-paper-100/60">Type</label>
                <select v-model="form.type" class="mt-1 w-full rounded-lg border-white/10 bg-forest-900 text-white focus:border-gold-400 focus:ring-gold-400">
                    <option value="agence_voyage">Agence de voyage</option>
                    <option value="hotel">Hôtel</option>
                    <option value="compagnie_aerienne">Compagnie aérienne</option>
                    <option value="autre">Autre</option>
                </select>
            </div>
            <div>
                <label class="text-xs uppercase tracking-wide text-paper-100/60">E-mail de contact</label>
                <input v-model="form.contact_email" type="email" class="mt-1 w-full rounded-lg border-white/10 bg-forest-900 text-white focus:border-gold-400 focus:ring-gold-400" />
            </div>
            <div>
                <label class="text-xs uppercase tracking-wide text-paper-100/60">Téléphone de contact</label>
                <input v-model="form.contact_phone" type="tel" class="mt-1 w-full rounded-lg border-white/10 bg-forest-900 text-white focus:border-gold-400 focus:ring-gold-400" />
            </div>
            <div class="flex gap-3 sm:col-span-2">
                <button type="submit" :disabled="form.processing" class="btn-primary">{{ editing ? 'Enregistrer' : 'Ajouter' }}</button>
                <button type="button" @click="showForm = false" class="rounded-full px-6 py-3 font-display text-sm font-semibold text-paper-100/60 hover:text-white">Annuler</button>
            </div>
        </form>

        <div v-if="partners.length" class="mt-6 overflow-x-auto rounded-xl2 border border-white/10">
            <table class="w-full min-w-[560px] text-left text-sm">
                <thead class="bg-forest-700 text-paper-100/60">
                    <tr>
                        <th class="px-4 py-3">Nom</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Contact</th>
                        <th class="px-4 py-3">Réservations apportées</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 bg-forest-700/40">
                    <tr v-for="p in partners" :key="p.id">
                        <td class="px-4 py-3 text-white">{{ p.name }}</td>
                        <td class="px-4 py-3 text-paper-100/80">{{ typeLabels[p.type] }}</td>
                        <td class="px-4 py-3 text-paper-100/60">{{ p.contact_email || p.contact_phone || '—' }}</td>
                        <td class="px-4 py-3 text-paper-100/80">{{ p.reservations_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <button @click="openEdit(p)" class="mr-3 font-display text-paper-100/70 hover:text-white">Modifier</button>
                            <button @click="remove(p)" class="font-display text-gold-400 hover:text-gold-300">Supprimer</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p v-else class="mt-10 text-center text-sm text-paper-100/50">Aucun partenaire pour le moment.</p>
    </AdminLayout>
</template>
