<template>
    <v-container fluid>
        <v-card>
            <v-card-title>
                <span class="headline">Gestión de Prestadores</span>
                <v-spacer></v-spacer>
                <v-btn
                    color="primary"
                    @click="openDialog()"
                >
                    <v-icon left>mdi-plus</v-icon>
                    Nuevo Prestador
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
                            label="Buscar prestador"
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
                            :items="providerTypes"
                            label="Tipo de prestador"
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
                    :items="filteredProviders"
                    :search="search"
                    :loading="loading"
                    class="elevation-1 mt-4"
                    :items-per-page="10"
                >
                    <template v-slot:item.tipo_prestador="{ item }">
                        <v-chip
                            :color="getTypeColor(item.tipo_prestador)"
                            dark
                            small
                        >
                            {{ item.tipo_prestador }}
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
                            @click="deleteProvider(item)"
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
            max-width="800px"
            persistent
        >
            <v-card>
                <v-card-title>
                    <span class="headline"
                        >{{ editMode ? 'Editar' : 'Nuevo' }} Prestador</span
                    >
                </v-card-title>

                <v-card-text>
                    <v-container>
                        <v-form
                            ref="form"
                            v-model="valid"
                        >
                            <v-row>
                                <v-col cols="12">
                                    <v-radio-group
                                        v-model="providerForm.es_agente"
                                        row
                                        label="Tipo de prestador"
                                        :rules="[
                                            (v) =>
                                                v !== null ||
                                                'Seleccione un tipo',
                                        ]"
                                    >
                                        <v-radio
                                            label="Agente interno"
                                            :value="true"
                                        ></v-radio>
                                        <v-radio
                                            label="Prestador externo"
                                            :value="false"
                                        ></v-radio>
                                    </v-radio-group>
                                </v-col>

                                <!-- Agent search if internal -->
                                <v-col
                                    cols="12"
                                    v-if="providerForm.es_agente"
                                >
                                    <v-autocomplete
                                        v-model="providerForm.agente_id"
                                        :items="agentes"
                                        :loading="loadingAgentes"
                                        :search-input.sync="searchAgente"
                                        item-text="nombre_completo"
                                        item-value="id"
                                        label="Buscar agente"
                                        placeholder="Ingrese nombre o DNI"
                                        prepend-icon="mdi-account-search"
                                        :rules="[
                                            (v) =>
                                                !!v || 'Seleccione un agente',
                                        ]"
                                        @change="selectAgente"
                                    >
                                        <template v-slot:item="{ item }">
                                            <v-list-item-content>
                                                <v-list-item-title>{{
                                                    item.nombre_completo
                                                }}</v-list-item-title>
                                                <v-list-item-subtitle>
                                                    DNI: {{ item.dni }} -
                                                    {{ item.cargo }}
                                                </v-list-item-subtitle>
                                            </v-list-item-content>
                                        </template>
                                    </v-autocomplete>
                                </v-col>

                                <!-- External provider fields -->
                                <template v-if="!providerForm.es_agente">
                                    <v-col
                                        cols="12"
                                        md="6"
                                    >
                                        <v-text-field
                                            v-model="providerForm.nombre"
                                            label="Nombre"
                                            :rules="nameRules"
                                            required
                                        ></v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="6"
                                    >
                                        <v-text-field
                                            v-model="providerForm.apellido"
                                            label="Apellido"
                                            :rules="nameRules"
                                            required
                                        ></v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="6"
                                    >
                                        <v-text-field
                                            v-model="providerForm.dni"
                                            label="DNI"
                                            :rules="dniRules"
                                            required
                                        ></v-text-field>
                                    </v-col>
                                </template>

                                <!-- Common fields -->
                                <v-col
                                    cols="12"
                                    md="6"
                                >
                                    <v-select
                                        v-model="providerForm.tipo_prestador"
                                        :items="providerTypes"
                                        label="Especialidad/Tipo"
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
                                    <v-text-field
                                        v-model="providerForm.matricula"
                                        label="Matrícula profesional"
                                        :rules="matriculaRules"
                                    ></v-text-field>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="4"
                                >
                                    <v-select
                                        v-model="providerForm.genero"
                                        :items="genderOptions"
                                        label="Género"
                                    ></v-select>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="4"
                                >
                                    <v-menu
                                        v-model="dateMenu"
                                        :close-on-content-click="false"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="290px"
                                    >
                                        <template
                                            v-slot:activator="{ on, attrs }"
                                        >
                                            <v-text-field
                                                v-model="
                                                    providerForm.fecha_nacimiento
                                                "
                                                label="Fecha de nacimiento"
                                                prepend-icon="mdi-calendar"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker
                                            v-model="
                                                providerForm.fecha_nacimiento
                                            "
                                            @input="dateMenu = false"
                                            locale="es"
                                        ></v-date-picker>
                                    </v-menu>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="4"
                                >
                                    <v-autocomplete
                                        v-model="
                                            providerForm.establecimiento_id
                                        "
                                        :items="establecimientos"
                                        item-text="nombre"
                                        item-value="id"
                                        label="Establecimiento"
                                        clearable
                                    ></v-autocomplete>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="6"
                                >
                                    <v-text-field
                                        v-model="providerForm.telefono"
                                        label="Teléfono"
                                    ></v-text-field>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="6"
                                >
                                    <v-text-field
                                        v-model="providerForm.email"
                                        label="Email"
                                        :rules="emailRules"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12">
                                    <v-textarea
                                        v-model="providerForm.observaciones"
                                        label="Observaciones"
                                        rows="3"
                                    ></v-textarea>
                                </v-col>

                                <v-col cols="12">
                                    <v-switch
                                        v-model="providerForm.activo"
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
                        @click="saveProvider"
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
 * @component Prestadores
 * @description Component for managing healthcare providers (internal agents and external providers)
 * @example
 * <Prestadores />
 */
export default {
    name: 'Prestadores',
    data() {
        return {
            providers: [],
            loading: false,
            dialog: false,
            editMode: false,
            valid: false,
            saving: false,
            search: '',
            filterType: null,
            filterStatus: null,

            // Agent search
            agentes: [],
            loadingAgentes: false,
            searchAgente: null,

            // Establecimientos
            establecimientos: [],

            // Date picker
            dateMenu: false,

            // Form data
            providerForm: {
                id: null,
                es_agente: true,
                agente_id: null,
                nombre: '',
                apellido: '',
                dni: '',
                tipo_prestador: null,
                matricula: '',
                telefono: '',
                email: '',
                genero: null,
                fecha_nacimiento: null,
                establecimiento_id: null,
                observaciones: '',
                activo: true,
            },

            // Options
            providerTypes: [
                'Médico/a General',
                'Médico/a Especialista',
                'Enfermero/a',
                'Psicólogo/a',
                'Kinesiólogo/a',
                'Odontólogo/a',
                'Nutricionista',
                'Trabajador/a Social',
                'Otro',
            ],

            statusOptions: [
                { text: 'Activo', value: true },
                { text: 'Inactivo', value: false },
            ],

            genderOptions: [
                { text: 'Masculino', value: 'male' },
                { text: 'Femenino', value: 'female' },
                { text: 'Otro', value: 'other' },
                { text: 'No especificado', value: 'unknown' },
            ],

            // Validation rules
            nameRules: [
                (v) => !!v || 'Este campo es requerido',
                (v) => (v && v.length >= 2) || 'Mínimo 2 caracteres',
            ],
            dniRules: [
                (v) => !!v || 'El DNI es requerido',
                (v) => /^\d{7,8}$/.test(v) || 'DNI inválido',
            ],
            matriculaRules: [
                (v) =>
                    !v ||
                    /^[A-Z0-9-]+$/i.test(v) ||
                    'Formato de matrícula inválido',
            ],
            emailRules: [(v) => !v || /.+@.+\..+/.test(v) || 'Email inválido'],
        };
    },
    computed: {
        headers() {
            return [
                { text: 'ID', value: 'id', width: '80px' },
                { text: 'Nombre', value: 'nombre_completo' },
                { text: 'DNI', value: 'dni' },
                { text: 'Tipo', value: 'tipo_prestador' },
                { text: 'Matrícula', value: 'matricula' },
                { text: 'Teléfono', value: 'telefono' },
                { text: 'Establecimiento', value: 'establecimiento' },
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
        filteredProviders() {
            let filtered = this.providers;

            if (this.filterType) {
                filtered = filtered.filter(
                    (p) => p.tipo_prestador === this.filterType,
                );
            }

            if (this.filterStatus !== null) {
                filtered = filtered.filter(
                    (p) => p.activo === this.filterStatus,
                );
            }

            return filtered;
        },
    },
    watch: {
        searchAgente(val) {
            if (val && val.length > 2) {
                this.searchAgentes(val);
            }
        },
    },
    created() {
        this.fetchProviders();
        this.fetchEstablecimientos();
    },
    methods: {
        /**
         * Fetch all providers from API
         */
        async fetchProviders() {
            this.loading = true;
            try {
                const response = await axios.get('/api/providers');
                this.providers = response.data.data;
            } catch (error) {
                console.error('Error fetching providers:', error);
                this.$toast.error('Error al cargar prestadores');
            } finally {
                this.loading = false;
            }
        },

        /**
         * Fetch all establishments from API
         */
        async fetchEstablecimientos() {
            try {
                const response = await axios.get('/api/facilities');
                this.establecimientos = response.data.data;
            } catch (error) {
                console.error('Error fetching establishments:', error);
            }
        },

        /**
         * Search internal agents
         * @param {string} search - Search term
         */
        async searchAgentes(search) {
            this.loadingAgentes = true;
            try {
                const response = await axios.get(
                    '/api/providers/search/agentes',
                    {
                        params: { q: search },
                    },
                );
                this.agentes = response.data.data;
            } catch (error) {
                console.error('Error searching agents:', error);
            } finally {
                this.loadingAgentes = false;
            }
        },

        /**
         * Handle agent selection
         * @param {number} agenteId - Selected agent ID
         */
        selectAgente(agenteId) {
            const agente = this.agentes.find((a) => a.id === agenteId);
            if (agente) {
                this.providerForm.nombre = agente.nombre;
                this.providerForm.apellido = agente.apellido;
                this.providerForm.dni = agente.dni;
            }
        },

        /**
         * Open dialog for create/edit
         * @param {Object} provider - Provider to edit (optional)
         */
        openDialog(provider = null) {
            this.editMode = !!provider;
            if (provider) {
                this.providerForm = { ...provider };
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
            this.providerForm = {
                id: null,
                es_agente: true,
                agente_id: null,
                nombre: '',
                apellido: '',
                dni: '',
                tipo_prestador: null,
                matricula: '',
                telefono: '',
                email: '',
                genero: null,
                fecha_nacimiento: null,
                establecimiento_id: null,
                observaciones: '',
                activo: true,
            };
            this.$refs.form?.reset();
        },

        /**
         * Save provider (create or update)
         */
        async saveProvider() {
            if (!this.$refs.form.validate()) return;

            this.saving = true;
            try {
                const data = { ...this.providerForm };

                if (this.editMode) {
                    await axios.put(`/api/providers/${data.id}`, data);
                    this.$toast.success('Prestador actualizado correctamente');
                } else {
                    await axios.post('/api/providers', data);
                    this.$toast.success('Prestador creado correctamente');
                }

                this.fetchProviders();
                this.closeDialog();
            } catch (error) {
                console.error('Error saving provider:', error);
                this.$toast.error('Error al guardar prestador');
            } finally {
                this.saving = false;
            }
        },

        /**
         * Delete provider
         * @param {Object} provider - Provider to delete
         */
        async deleteProvider(provider) {
            const confirm = await this.$confirm(
                `¿Está seguro de eliminar al prestador ${provider.nombre_completo}?`,
                'Confirmar eliminación',
            );

            if (confirm) {
                try {
                    await axios.delete(`/api/providers/${provider.id}`);
                    this.$toast.success('Prestador eliminado correctamente');
                    this.fetchProviders();
                } catch (error) {
                    console.error('Error deleting provider:', error);
                    this.$toast.error('Error al eliminar prestador');
                }
            }
        },

        /**
         * Get chip color based on provider type
         * @param {string} type - Provider type
         * @returns {string} Color name
         */
        getTypeColor(type) {
            const colors = {
                'Médico/a General': 'primary',
                'Médico/a Especialista': 'secondary',
                'Enfermero/a': 'info',
                'Psicólogo/a': 'purple',
                'Kinesiólogo/a': 'orange',
                'Odontólogo/a': 'teal',
                Nutricionista: 'green',
                'Trabajador/a Social': 'indigo',
                Otro: 'grey',
            };
            return colors[type] || 'grey';
        },
    },
};
</script>

<style scoped></style>
