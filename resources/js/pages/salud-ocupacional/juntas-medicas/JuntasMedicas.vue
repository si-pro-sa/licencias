<template>
    <v-container fluid>
        <v-card>
            <v-card-title>
                Juntas Médicas
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Buscar"
                    single-line
                    hide-details
                    class="mx-4"
                    outlined
                    dense
                ></v-text-field>
                <v-btn
                    color="primary"
                    @click="openFormDialog"
                    :disabled="loading"
                >
                    <v-icon left>mdi-plus</v-icon>
                    Nueva Junta Médica
                </v-btn>
            </v-card-title>

            <v-card-text>
                <v-data-table
                    :headers="headers"
                    :items="juntasMedicas"
                    :search="search"
                    :loading="loading"
                    sort-by="fecha"
                    sort-desc
                    :items-per-page="10"
                    :footer-props="{
                        'items-per-page-options': [5, 10, 15, 20],
                        'items-per-page-text': 'Registros por página',
                    }"
                    class="elevation-1"
                >
                    <!-- Empleado -->
                    <template v-slot:item.empleado="{ item }">
                        {{
                            item.empleado
                                ? item.empleado.nombre_completo
                                : 'No asignado'
                        }}
                    </template>

                    <!-- Fecha -->
                    <template v-slot:item.fecha="{ item }">
                        {{ formatDate(item.fecha) }}
                    </template>

                    <!-- Estado -->
                    <template v-slot:item.estado="{ item }">
                        <v-chip
                            :color="getStatusColor(item.estado)"
                            dark
                            small
                        >
                            {{ item.estado }}
                        </v-chip>
                    </template>

                    <!-- Acciones -->
                    <template v-slot:item.actions="{ item }">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    icon
                                    small
                                    color="info"
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="viewJuntaMedica(item)"
                                >
                                    <v-icon small>mdi-eye</v-icon>
                                </v-btn>
                            </template>
                            <span>Ver detalles</span>
                        </v-tooltip>

                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    icon
                                    small
                                    color="success"
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="ejecutarJuntaMedica(item)"
                                    :disabled="item.estado === 'Completada'"
                                >
                                    <v-icon small>mdi-clipboard-check</v-icon>
                                </v-btn>
                            </template>
                            <span>Ejecutar Junta Médica</span>
                        </v-tooltip>

                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    icon
                                    small
                                    color="warning"
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="editJuntaMedica(item)"
                                >
                                    <v-icon small>mdi-pencil</v-icon>
                                </v-btn>
                            </template>
                            <span>Editar</span>
                        </v-tooltip>

                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    icon
                                    small
                                    color="error"
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="confirmDelete(item)"
                                >
                                    <v-icon small>mdi-delete</v-icon>
                                </v-btn>
                            </template>
                            <span>Eliminar</span>
                        </v-tooltip>
                    </template>

                    <!-- Sin datos -->
                    <template v-slot:no-data>
                        <v-alert
                            type="info"
                            outlined
                            class="ma-2"
                        >
                            No se encontraron juntas médicas.
                        </v-alert>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>

        <!-- Formulario de creación/edición -->
        <junta-medica-form
            v-model="formDialog"
            :junta-medica-data="selectedJuntaMedica"
            :edit-mode="formEditMode"
            @saved="loadJuntasMedicas"
        />

        <!-- Diálogo de visualización de detalles -->
        <junta-medica-view
            v-model="viewDialog"
            :junta-medica="selectedJuntaMedica"
        />

        <!-- Diálogo de confirmación de eliminación -->
        <v-dialog
            v-model="deleteDialog"
            max-width="400"
        >
            <v-card>
                <v-card-title class="headline error--text">
                    Eliminar Junta Médica
                </v-card-title>
                <v-card-text>
                    ¿Está seguro de que desea eliminar esta junta médica? Esta
                    acción no se puede deshacer.
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="grey darken-1"
                        text
                        @click="deleteDialog = false"
                        :disabled="deleteLoading"
                    >
                        Cancelar
                    </v-btn>
                    <v-btn
                        color="error"
                        @click="deleteJuntaMedica"
                        :loading="deleteLoading"
                        :disabled="deleteLoading"
                    >
                        Eliminar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
// Importamos los componentes y servicios necesarios
import JuntaMedicaForm from './components/JuntaMedicaForm.vue';
import JuntaMedicaView from './components/JuntaMedicaView.vue';
import juntaMedicaApi from '../../../api/juntaMedicaApi';

export default {
    name: 'JuntasMedicas',

    components: {
        JuntaMedicaForm,
        JuntaMedicaView,
    },

    data() {
        return {
            loading: false,
            deleteLoading: false,
            search: '',
            juntasMedicas: [],
            selectedJuntaMedica: null,
            formDialog: false,
            viewDialog: false,
            deleteDialog: false,
            formEditMode: false,

            headers: [
                {
                    text: 'Empleado',
                    align: 'start',
                    value: 'empleado',
                    sortable: true,
                },
                {
                    text: 'Fecha',
                    value: 'fecha',
                    sortable: true,
                },
                {
                    text: 'Tipo',
                    value: 'tipo',
                    sortable: true,
                },
                {
                    text: 'Estado',
                    value: 'estado',
                    sortable: true,
                },
                {
                    text: 'Diagnóstico',
                    value: 'diagnostico',
                    sortable: true,
                },
                {
                    text: 'Acciones',
                    value: 'actions',
                    sortable: false,
                    align: 'center',
                },
            ],
        };
    },

    created() {
        this.loadJuntasMedicas();
    },

    methods: {
        /**
         * Carga la lista de juntas médicas desde la API
         */
        async loadJuntasMedicas() {
            this.loading = true;
            try {
                const response = await juntaMedicaApi.getJuntasMedicas();
                this.juntasMedicas = response.data || [];
            } catch (error) {
                console.error('Error al cargar juntas médicas:', error);
                this.$toast.error('No se pudieron cargar las juntas médicas');
            } finally {
                this.loading = false;
            }
        },

        /**
         * Abre el diálogo para crear una nueva junta médica
         */
        openFormDialog() {
            this.selectedJuntaMedica = {};
            this.formEditMode = false;
            this.formDialog = true;
        },

        /**
         * Abre el diálogo para editar una junta médica existente
         * @param {Object} juntaMedica - La junta médica a editar
         */
        editJuntaMedica(juntaMedica) {
            this.selectedJuntaMedica = { ...juntaMedica };
            this.formEditMode = true;
            this.formDialog = true;
        },

        /**
         * Abre el diálogo para ver los detalles de una junta médica
         * @param {Object} juntaMedica - La junta médica a visualizar
         */
        viewJuntaMedica(juntaMedica) {
            this.selectedJuntaMedica = { ...juntaMedica };
            this.viewDialog = true;
        },

        /**
         * Muestra el diálogo de confirmación para eliminar una junta médica
         * @param {Object} juntaMedica - La junta médica a eliminar
         */
        confirmDelete(juntaMedica) {
            this.selectedJuntaMedica = { ...juntaMedica };
            this.deleteDialog = true;
        },

        /**
         * Elimina una junta médica
         */
        async deleteJuntaMedica() {
            if (!this.selectedJuntaMedica || !this.selectedJuntaMedica.id)
                return;

            this.deleteLoading = true;
            try {
                await juntaMedicaApi.deleteJuntaMedica(
                    this.selectedJuntaMedica.id,
                );
                this.$toast.success('Junta médica eliminada correctamente');
                this.deleteDialog = false;
                this.loadJuntasMedicas();
            } catch (error) {
                console.error('Error al eliminar junta médica:', error);
                this.$toast.error('No se pudo eliminar la junta médica');
            } finally {
                this.deleteLoading = false;
            }
        },

        /**
         * Formatea una fecha para mostrarla en formato dd/mm/yyyy
         * @param {String} dateString - La fecha en formato ISO
         * @returns {String} Fecha formateada
         */
        formatDate(dateString) {
            if (!dateString) return 'Sin fecha';

            const date = new Date(dateString);
            if (isNaN(date.getTime())) return dateString;

            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();

            return `${day}/${month}/${year}`;
        },

        /**
         * Devuelve el color correspondiente al estado de la junta médica
         * @param {String} status - El estado de la junta médica
         * @returns {String} Color para el chip
         */
        getStatusColor(status) {
            const colors = {
                Programada: 'blue',
                Completada: 'success',
                Cancelada: 'error',
                Pendiente: 'orange',
            };

            return colors[status] || 'grey';
        },

        /**
         * Redirige a la pantalla de ejecución de la junta médica
         * @param {Object} juntaMedica - La junta médica a ejecutar
         */
        ejecutarJuntaMedica(juntaMedica) {
            // Redirigir a la vista de evaluación de junta médica
            this.$router.push({
                name: 'junta-medica-evaluacion',
                params: { id: juntaMedica.id },
            });
        },
    },
};
</script>
