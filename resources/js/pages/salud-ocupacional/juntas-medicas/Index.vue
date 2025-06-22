<template>
    <v-container fluid>
        <v-card>
            <v-card-title class="headline">
                Juntas Médicas
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Buscar"
                    single-line
                    hide-details
                    class="mx-4"
                ></v-text-field>
                <v-btn
                    color="primary"
                    dark
                    @click="openCreateDialog"
                    v-if="$can('juntas-medicas-write')"
                >
                    <v-icon left>mdi-plus</v-icon>
                    Nueva Junta Médica
                </v-btn>
            </v-card-title>

            <v-data-table
                :headers="headers"
                :items="juntas"
                :items-per-page="10"
                :loading="loading"
                :search="search"
                class="elevation-1"
            >
                <template v-slot:item.fecha="{ item }">
                    {{ formatDate(item.fecha) }}
                </template>
                <template v-slot:item.estado="{ item }">
                    <v-chip
                        :color="getStatusColor(item.estado)"
                        dark
                    >
                        {{ item.estado }}
                    </v-chip>
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-icon
                        small
                        class="mr-2"
                        @click="openViewDialog(item)"
                    >
                        mdi-eye
                    </v-icon>
                    <v-icon
                        small
                        class="mr-2"
                        @click="openEditDialog(item)"
                        v-if="$can('juntas-medicas-write')"
                    >
                        mdi-pencil
                    </v-icon>
                    <v-icon
                        small
                        @click="confirmDelete(item)"
                        v-if="$can('juntas-medicas-write')"
                    >
                        mdi-delete
                    </v-icon>
                </template>
            </v-data-table>
        </v-card>

        <!-- Diálogos para crear, editar y ver detalles -->
        <junta-medica-form
            v-if="dialog.form"
            :show="dialog.form"
            :junta-medica="selectedJunta"
            :is-edit="isEdit"
            @close="closeDialog('form')"
            @refresh="loadJuntas"
        ></junta-medica-form>

        <junta-medica-view
            v-if="dialog.view"
            :show="dialog.view"
            :junta-medica="selectedJunta"
            @close="closeDialog('view')"
        ></junta-medica-view>

        <confirm-dialog
            :show="dialog.delete"
            title="Eliminar Junta Médica"
            text="¿Estás seguro que deseas eliminar esta junta médica? Esta acción no se puede deshacer."
            @cancel="closeDialog('delete')"
            @confirm="deleteJuntaMedica"
        ></confirm-dialog>
    </v-container>
</template>

<script>
import JuntaMedicaForm from './components/JuntaMedicaForm.vue';
import JuntaMedicaView from './components/JuntaMedicaView.vue';
import ConfirmDialog from '../../../components/dialogs/ConfirmDialog.vue';
import { formatDate } from '../../../utils/dateFormatter';

export default {
    name: 'JuntasMedicasIndex',

    components: {
        JuntaMedicaForm,
        JuntaMedicaView,
        ConfirmDialog,
    },

    data() {
        return {
            search: '',
            loading: false,
            juntas: [],
            selectedJunta: null,
            isEdit: false,
            dialog: {
                form: false,
                view: false,
                delete: false,
            },
            headers: [
                { text: 'ID', value: 'id', align: 'start' },
                { text: 'Empleado', value: 'empleado' },
                { text: 'Fecha', value: 'fecha' },
                { text: 'Tipo', value: 'tipo' },
                { text: 'Estado', value: 'estado' },
                { text: 'Acciones', value: 'actions', sortable: false },
            ],
        };
    },

    created() {
        this.loadJuntas();
    },

    methods: {
        formatDate,

        async loadJuntas() {
            this.loading = true;
            try {
                const response = await this.$http.get('/api/juntas-medicas');
                this.juntas = response.data.data;
            } catch (error) {
                console.error('Error al cargar juntas médicas:', error);
                this.$toast.error('No se pudieron cargar las juntas médicas');
            } finally {
                this.loading = false;
            }
        },

        getStatusColor(estado) {
            switch (estado.toLowerCase()) {
                case 'pendiente':
                    return 'orange';
                case 'realizada':
                    return 'green';
                case 'cancelada':
                    return 'red';
                default:
                    return 'grey';
            }
        },

        openCreateDialog() {
            this.selectedJunta = null;
            this.isEdit = false;
            this.dialog.form = true;
        },

        openEditDialog(item) {
            this.selectedJunta = { ...item };
            this.isEdit = true;
            this.dialog.form = true;
        },

        openViewDialog(item) {
            this.selectedJunta = { ...item };
            this.dialog.view = true;
        },

        confirmDelete(item) {
            this.selectedJunta = { ...item };
            this.dialog.delete = true;
        },

        async deleteJuntaMedica() {
            try {
                await this.$http.delete(
                    `/api/juntas-medicas/${this.selectedJunta.id}`,
                );
                this.$toast.success('Junta médica eliminada con éxito');
                this.loadJuntas();
            } catch (error) {
                console.error('Error al eliminar junta médica:', error);
                this.$toast.error('No se pudo eliminar la junta médica');
            } finally {
                this.closeDialog('delete');
            }
        },

        closeDialog(dialogType) {
            this.dialog[dialogType] = false;
            if (dialogType === 'form' || dialogType === 'delete') {
                this.selectedJunta = null;
            }
        },
    },
};
</script>
