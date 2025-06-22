<template>
    <v-container fluid>
        <v-card>
            <v-card-title>
                <span class="headline">Gestión de Establecimientos</span>
                <v-spacer></v-spacer>
                <v-btn
                    color="primary"
                    @click="openDialog()"
                >
                    <v-icon left>mdi-plus</v-icon>
                    Nuevo Establecimiento
                </v-btn>
            </v-card-title>

            <v-card-text>
                <!-- Search and filters -->
                <v-row>
                    <v-col
                        cols="12"
                        md="6"
                    >
                        <v-text-field
                            v-model="search"
                            append-icon="mdi-magnify"
                            label="Buscar establecimiento"
                            single-line
                            hide-details
                            clearable
                        ></v-text-field>
                    </v-col>
                    <v-col
                        cols="12"
                        md="3"
                    >
                        <v-select
                            v-model="filterType"
                            :items="facilityTypes"
                            label="Tipo de establecimiento"
                            clearable
                        ></v-select>
                    </v-col>
                    <v-col
                        cols="12"
                        md="3"
                    >
                        <v-select
                            v-model="filterStatus"
                            :items="statusOptions"
                            label="Estado"
                            clearable
                        ></v-select>
                    </v-col>
                </v-row>

                <!-- Data table -->
                <v-data-table
                    :headers="headers"
                    :items="filteredFacilities"
                    :search="search"
                    :loading="loading"
                    class="elevation-1 mt-4"
                    :items-per-page="10"
                >
                    <template v-slot:item.tipo_establecimiento="{ item }">
                        <v-chip
                            :color="getTypeColor(item.tipo_establecimiento)"
                            dark
                            small
                        >
                            {{ item.tipo_establecimiento }}
                        </v-chip>
                    </template>

                    <template v-slot:item.nivel_atencion="{ item }">
                        <v-chip
                            color="blue-grey"
                            dark
                            small
                        >
                            Nivel {{ item.nivel_atencion }}
                        </v-chip>
                    </template>

                    <template v-slot:item.activo="{ item }">
                        <v-chip
                            :color="item.activo ? 'success' : 'error'"
                            dark
                            small
                        >
                            {{ item.activo ? 'Activo' : 'Inactivo' }}
                        </v-chip>
                    </template>

                    <template v-slot:item.actions="{ item }">
                        <v-icon
                            small
                            class="mr-2"
                            @click="openDialog(item)"
                        >
                            mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click="deleteFacility(item)"
                        >
                            mdi-delete
                        </v-icon>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>

        <!-- Dialog for create/edit -->
        <v-dialog
            v-model="dialog"
            max-width="900px"
            persistent
        >
            <v-card>
                <v-card-title>
                    <span class="headline"
                        >{{
                            editMode ? 'Editar' : 'Nuevo'
                        }}
                        Establecimiento</span
                    >
                </v-card-title>

                <v-card-text>
                    <v-container>
                        <v-form
                            ref="form"
                            v-model="valid"
                        >
                            <v-row>
                                <!-- Basic Information -->
                                <v-col cols="12">
                                    <div class="subtitle-1 font-weight-bold">
                                        Información Básica
                                    </div>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="6"
                                >
                                    <v-text-field
                                        v-model="facilityForm.nombre"
                                        label="Nombre del establecimiento"
                                        :rules="nameRules"
                                        required
                                    ></v-text-field>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="6"
                                >
                                    <v-text-field
                                        v-model="facilityForm.codigo_sisa"
                                        label="Código SISA"
                                        hint="Código del Sistema Integrado de Información Sanitaria Argentina"
                                    ></v-text-field>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="6"
                                >
                                    <v-select
                                        v-model="
                                            facilityForm.tipo_establecimiento
                                        "
                                        :items="facilityTypes"
                                        label="Tipo de establecimiento"
                                        :rules="[
                                            (v) => !!v || 'Seleccione un tipo',
                                        ]"
                                        required
                                    ></v-select>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="6"
                                >
                                    <v-select
                                        v-model="facilityForm.nivel_atencion"
                                        :items="attentionLevels"
                                        label="Nivel de atención"
                                        :rules="[
                                            (v) => !!v || 'Seleccione un nivel',
                                        ]"
                                        required
                                    ></v-select>
                                </v-col>

                                <!-- Location Information -->
                                <v-col
                                    cols="12"
                                    class="mt-4"
                                >
                                    <div class="subtitle-1 font-weight-bold">
                                        Ubicación
                                    </div>
                                </v-col>

                                <v-col cols="12">
                                    <v-text-field
                                        v-model="facilityForm.direccion"
                                        label="Dirección"
                                        :rules="[
                                            (v) =>
                                                !!v ||
                                                'La dirección es requerida',
                                        ]"
                                        required
                                    ></v-text-field>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="4"
                                >
                                    <v-select
                                        v-model="facilityForm.provincia"
                                        :items="provincias"
                                        label="Provincia"
                                        :rules="[
                                            (v) =>
                                                !!v ||
                                                'Seleccione una provincia',
                                        ]"
                                        required
                                    ></v-select>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="4"
                                >
                                    <v-text-field
                                        v-model="facilityForm.localidad"
                                        label="Localidad"
                                        :rules="[
                                            (v) =>
                                                !!v ||
                                                'La localidad es requerida',
                                        ]"
                                        required
                                    ></v-text-field>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="4"
                                >
                                    <v-text-field
                                        v-model="facilityForm.codigo_postal"
                                        label="Código Postal"
                                    ></v-text-field>
                                </v-col>

                                <!-- Contact Information -->
                                <v-col
                                    cols="12"
                                    class="mt-4"
                                >
                                    <div class="subtitle-1 font-weight-bold">
                                        Información de Contacto
                                    </div>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="4"
                                >
                                    <v-text-field
                                        v-model="facilityForm.telefono"
                                        label="Teléfono"
                                        prepend-icon="mdi-phone"
                                    ></v-text-field>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="4"
                                >
                                    <v-text-field
                                        v-model="facilityForm.email"
                                        label="Email"
                                        :rules="emailRules"
                                        prepend-icon="mdi-email"
                                    ></v-text-field>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="4"
                                >
                                    <v-text-field
                                        v-model="facilityForm.responsable"
                                        label="Responsable/Director"
                                        prepend-icon="mdi-account-tie"
                                    ></v-text-field>
                                </v-col>

                                <!-- Services -->
                                <v-col
                                    cols="12"
                                    class="mt-4"
                                >
                                    <div class="subtitle-1 font-weight-bold">
                                        Servicios Disponibles
                                    </div>
                                </v-col>

                                <v-col cols="12">
                                    <v-select
                                        v-model="facilityForm.servicios"
                                        :items="availableServices"
                                        label="Servicios"
                                        multiple
                                        chips
                                        small-chips
                                        deletable-chips
                                    ></v-select>
                                </v-col>

                                <!-- Additional Information -->
                                <v-col cols="12">
                                    <v-textarea
                                        v-model="facilityForm.observaciones"
                                        label="Observaciones"
                                        rows="3"
                                    ></v-textarea>
                                </v-col>

                                <v-col cols="12">
                                    <v-switch
                                        v-model="facilityForm.activo"
                                        label="Activo"
                                    ></v-switch>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        text
                        @click="closeDialog"
                    >
                        Cancelar
                    </v-btn>
                    <v-btn
                        color="primary"
                        :disabled="!valid"
                        :loading="saving"
                        @click="saveFacility"
                    >
                        Guardar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
/**
 * @component Establecimientos
 * @description Component for managing healthcare facilities
 * @example
 * <Establecimientos />
 */
export default {
    name: 'Establecimientos',
    data() {
        return {
            facilities: [],
            loading: false,
            dialog: false,
            editMode: false,
            valid: false,
            saving: false,
            search: '',
            filterType: null,
            filterStatus: null,

            // Form data
            facilityForm: {
                id: null,
                nombre: '',
                codigo_sisa: '',
                tipo_establecimiento: null,
                nivel_atencion: null,
                direccion: '',
                provincia: null,
                localidad: '',
                codigo_postal: '',
                telefono: '',
                email: '',
                responsable: '',
                servicios: [],
                observaciones: '',
                activo: true,
            },

            // Options
            facilityTypes: [
                'Hospital',
                'Centro de Salud',
                'Clínica',
                'Sanatorio',
                'Centro de Diagnóstico',
                'Laboratorio',
                'Centro de Rehabilitación',
                'Consultorio Externo',
                'Otro',
            ],

            attentionLevels: [
                { text: 'Nivel I - Atención Primaria', value: 1 },
                { text: 'Nivel II - Atención Especializada', value: 2 },
                { text: 'Nivel III - Alta Complejidad', value: 3 },
            ],

            provincias: [
                'Buenos Aires',
                'CABA',
                'Catamarca',
                'Chaco',
                'Chubut',
                'Córdoba',
                'Corrientes',
                'Entre Ríos',
                'Formosa',
                'Jujuy',
                'La Pampa',
                'La Rioja',
                'Mendoza',
                'Misiones',
                'Neuquén',
                'Río Negro',
                'Salta',
                'San Juan',
                'San Luis',
                'Santa Cruz',
                'Santa Fe',
                'Santiago del Estero',
                'Tierra del Fuego',
                'Tucumán',
            ],

            availableServices: [
                'Emergencias',
                'Consultorios Externos',
                'Internación',
                'Cirugía',
                'Laboratorio',
                'Radiología',
                'Ecografía',
                'Tomografía',
                'Resonancia Magnética',
                'Pediatría',
                'Ginecología',
                'Obstetricia',
                'Traumatología',
                'Cardiología',
                'Neurología',
                'Psicología',
                'Psiquiatría',
                'Kinesiología',
                'Fonoaudiología',
                'Nutrición',
                'Odontología',
                'Oftalmología',
                'Otorrinolaringología',
                'Urología',
                'Nefrología',
                'Oncología',
                'Hematología',
                'Infectología',
                'Medicina General',
                'Medicina Familiar',
            ],

            statusOptions: [
                { text: 'Activo', value: true },
                { text: 'Inactivo', value: false },
            ],

            // Validation rules
            nameRules: [
                (v) => !!v || 'El nombre es requerido',
                (v) => (v && v.length >= 3) || 'Mínimo 3 caracteres',
            ],
            emailRules: [(v) => !v || /.+@.+\..+/.test(v) || 'Email inválido'],
        };
    },
    computed: {
        headers() {
            return [
                { text: 'ID', value: 'id', width: '80px' },
                { text: 'Nombre', value: 'nombre' },
                { text: 'Tipo', value: 'tipo_establecimiento' },
                { text: 'Nivel', value: 'nivel_atencion', align: 'center' },
                { text: 'Dirección', value: 'direccion' },
                { text: 'Localidad', value: 'localidad' },
                { text: 'Teléfono', value: 'telefono' },
                { text: 'Estado', value: 'activo', align: 'center' },
                {
                    text: 'Acciones',
                    value: 'actions',
                    sortable: false,
                    align: 'center',
                    width: '120px',
                },
            ];
        },
        filteredFacilities() {
            let filtered = this.facilities;

            if (this.filterType) {
                filtered = filtered.filter(
                    (f) => f.tipo_establecimiento === this.filterType,
                );
            }

            if (this.filterStatus !== null) {
                filtered = filtered.filter(
                    (f) => f.activo === this.filterStatus,
                );
            }

            return filtered;
        },
    },
    created() {
        this.fetchFacilities();
    },
    methods: {
        /**
         * Fetch all facilities from API
         */
        async fetchFacilities() {
            this.loading = true;
            try {
                const response = await axios.get('/api/facilities');
                this.facilities = response.data.data;
            } catch (error) {
                console.error('Error fetching facilities:', error);
                this.$toast.error('Error al cargar establecimientos');
            } finally {
                this.loading = false;
            }
        },

        /**
         * Open dialog for create/edit
         * @param {Object} facility - Facility to edit (optional)
         */
        openDialog(facility = null) {
            this.editMode = !!facility;
            if (facility) {
                this.facilityForm = { ...facility };
            } else {
                this.resetForm();
            }
            this.dialog = true;
        },

        /**
         * Close dialog and reset form
         */
        closeDialog() {
            this.dialog = false;
            this.resetForm();
        },

        /**
         * Reset form to initial state
         */
        resetForm() {
            this.facilityForm = {
                id: null,
                nombre: '',
                codigo_sisa: '',
                tipo_establecimiento: null,
                nivel_atencion: null,
                direccion: '',
                provincia: null,
                localidad: '',
                codigo_postal: '',
                telefono: '',
                email: '',
                responsable: '',
                servicios: [],
                observaciones: '',
                activo: true,
            };
            this.$refs.form?.reset();
        },

        /**
         * Save facility (create or update)
         */
        async saveFacility() {
            if (!this.$refs.form.validate()) return;

            this.saving = true;
            try {
                const data = { ...this.facilityForm };

                if (this.editMode) {
                    await axios.put(`/api/facilities/${data.id}`, data);
                    this.$toast.success(
                        'Establecimiento actualizado correctamente',
                    );
                } else {
                    await axios.post('/api/facilities', data);
                    this.$toast.success('Establecimiento creado correctamente');
                }

                this.fetchFacilities();
                this.closeDialog();
            } catch (error) {
                console.error('Error saving facility:', error);
                this.$toast.error('Error al guardar establecimiento');
            } finally {
                this.saving = false;
            }
        },

        /**
         * Delete facility
         * @param {Object} facility - Facility to delete
         */
        async deleteFacility(facility) {
            const confirm = await this.$confirm(
                `¿Está seguro de eliminar el establecimiento ${facility.nombre}?`,
                'Confirmar eliminación',
            );

            if (confirm) {
                try {
                    await axios.delete(`/api/facilities/${facility.id}`);
                    this.$toast.success(
                        'Establecimiento eliminado correctamente',
                    );
                    this.fetchFacilities();
                } catch (error) {
                    console.error('Error deleting facility:', error);
                    this.$toast.error('Error al eliminar establecimiento');
                }
            }
        },

        /**
         * Get chip color based on facility type
         * @param {string} type - Facility type
         * @returns {string} Color name
         */
        getTypeColor(type) {
            const colors = {
                Hospital: 'primary',
                'Centro de Salud': 'secondary',
                Clínica: 'info',
                Sanatorio: 'purple',
                'Centro de Diagnóstico': 'orange',
                Laboratorio: 'teal',
                'Centro de Rehabilitación': 'green',
                'Consultorio Externo': 'indigo',
                Otro: 'grey',
            };
            return colors[type] || 'grey';
        },
    },
};
</script>

<style scoped>
.subtitle-1 {
    color: rgba(0, 0, 0, 0.6);
}
</style>
