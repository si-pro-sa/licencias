<template>
    <v-dialog
        v-model="isOpen"
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition"
    >
        <v-card class="diagnostico-modal">
            <!-- Toolbar -->
            <v-toolbar
                flat
                color="indigo darken-1"
                dark
            >
                <v-toolbar-title>{{ titleByVisar }}</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn
                    icon
                    @click="close"
                >
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar>

            <!-- Content -->
            <v-card-text>
                <v-container>
                    <v-row>
                        <!-- Diagnostic Details -->
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-card
                                outlined
                                class="pa-3"
                            >
                                <v-card-title>Diagnóstico</v-card-title>
                                <v-divider></v-divider>

                                <!-- Code -->
                                <v-text-field
                                    v-if="visar !== 0 && visar !== 1"
                                    v-model="diagnostico.codigo_icd10"
                                    label="Código del Diagnóstico (ICD-10)"
                                    outlined
                                    clearable
                                    dense
                                    :disabled="visar !== 2"
                                    class="mt-3"
                                ></v-text-field>

                                <!-- Description -->
                                <v-textarea
                                    v-if="visar !== 0 && visar !== 1"
                                    v-model="diagnostico.descripcion"
                                    label="Descripción"
                                    outlined
                                    auto-grow
                                    dense
                                    :disabled="visar !== 2"
                                ></v-textarea>

                                <!-- File Upload -->
                                <v-file-input
                                    v-if="visar === 0"
                                    v-model="diagnostico.archivo"
                                    label="Subir Archivo (imagen o foto)"
                                    outlined
                                    dense
                                    prepend-icon="mdi-file-upload"
                                    show-size
                                ></v-file-input>

                                <!-- Uploaded File Display -->
                                <v-btn
                                    v-if="diagnosticFileUrl"
                                    :href="diagnosticFileUrl"
                                    target="_blank"
                                    color="indigo"
                                    dark
                                    class="mt-2"
                                >
                                    Ver Archivo de Diagnóstico
                                </v-btn>
                            </v-card>
                        </v-col>

                        <!-- Studies -->
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-card
                                outlined
                                class="pa-3"
                            >
                                <v-card-title
                                    >Estudios Complementarios</v-card-title
                                >
                                <v-divider></v-divider>
                                <v-data-table
                                    :headers="observacionesHeaders"
                                    :items="observaciones"
                                    item-value="id"
                                    dense
                                    class="elevation-1 mt-3"
                                    hide-default-footer
                                >
                                    <template v-slot:top>
                                        <v-toolbar flat>
                                            <v-toolbar-title
                                                >Listado de
                                                Estudios</v-toolbar-title
                                            >
                                            <v-spacer></v-spacer>
                                            <v-btn
                                                color="indigo"
                                                dark
                                                rounded
                                                @click="addObservacion"
                                                class="elevation-2"
                                                :disabled="
                                                    visar !== 0 && visar !== 2
                                                "
                                            >
                                                <v-icon left>mdi-plus</v-icon>
                                                Nuevo Estudio
                                            </v-btn>
                                        </v-toolbar>
                                    </template>
                                    <template v-slot:item.actions="{ item }">
                                        <v-btn
                                            v-if="
                                                observationsFileUrls[
                                                    item.idObservacion
                                                ]
                                            "
                                            :href="
                                                observationsFileUrls[
                                                    item.idObservacion
                                                ]
                                            "
                                            target="_blank"
                                            small
                                            color="indigo"
                                            icon
                                        >
                                            <v-icon>mdi-file-document</v-icon>
                                        </v-btn>
                                        <v-icon
                                            small
                                            class="mr-2"
                                            color="blue"
                                            @click="editObservacion(item)"
                                        >
                                            mdi-pencil
                                        </v-icon>
                                        <v-icon
                                            small
                                            color="red"
                                            @click="deleteObservacion(item)"
                                        >
                                            mdi-delete
                                        </v-icon>
                                    </template>
                                </v-data-table>
                            </v-card>
                        </v-col>
                    </v-row>

                    <!-- Provider and Facility -->
                    <!-- <v-row>
                        <v-col cols="6">
                            <v-text-field
                                label="Proveedor"
                                v-model="diagnostico.proveedor"
                                outlined
                                dense
                                :disabled="visar !== 0"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field
                                label="Establecimiento"
                                v-model="diagnostico.establecimiento"
                                outlined
                                dense
                                :disabled="visar !== 0"
                            ></v-text-field>
                        </v-col>
                    </v-row> -->
                </v-container>
            </v-card-text>

            <!-- Actions -->
            <v-card-actions>
                <v-container>
                    <v-row justify="center">
                        <v-btn
                            v-if="visar === 0 || visar === 2"
                            color="indigo darken-1"
                            dark
                            large
                            @click="saveDiagnostico"
                            class="elevation-2"
                        >
                            <v-icon left>mdi-content-save</v-icon>
                            Guardar
                        </v-btn>
                        <v-btn
                            color="red darken-1"
                            text
                            large
                            @click="close"
                            class="ml-3"
                        >
                            Cancelar
                        </v-btn>
                    </v-row>
                </v-container>
            </v-card-actions>
        </v-card>
        <observacion-dialog
            v-model="isObservacionDialogOpen"
            :mode="observacionDialogMode"
            :initial-observacion="currentObservacion"
            @save="handleSaveObservacion"
        ></observacion-dialog>
    </v-dialog>
</template>

<script>
import ObservacionDialog from './ObservacionDialog.vue';

export default {
    components: { ObservacionDialog },
    props: {
        value: { type: Boolean, required: true },
        visar: { type: Number, required: true },
        idlicencia: { type: Number, required: true }, // ID de la licencia
    },
    data() {
        return {
            isOpen: this.value,
            diagnostico: {
                codigo_icd10: '',
                descripcion: '',
                archivo: null,
                proveedor: '',
                establecimiento: '',
                // plataforma: {
                //     url: '',
                //     codigo: '',
                //     usuario: '',
                //     contrasena: '',
                // },
            },
            observacionesHeaders: [
                { text: 'Descripción', value: 'descripcion' },
                { text: 'Fecha', value: 'fecha' },
                { text: 'Acciones', value: 'actions', sortable: false },
            ],
            observaciones: [],
            diagnosticFileUrl: '', // URL for the diagnostic file
            observationsFileUrls: {}, // URLs for observation files
            isObservacionDialogOpen: false,
            observacionDialogMode: 'add',
            currentObservacion: { id: null, descripcion: '', fecha: '' },
        };
    },
    computed: {
        titleByVisar() {
            return this.visar === 0
                ? 'Agregar Diagnóstico y Estudios Complementarios'
                : this.visar === 2
                ? 'Revisar y Editar Diagnóstico'
                : 'Visualizar Diagnóstico';
        },
    },
    watch: {
        value(val) {
            this.isOpen = val;
        },
        isOpen(val) {
            this.$emit('input', val);
        },
    },
    methods: {
        async fetchDiagnosticByLicencia() {
            try {
                const response = await axios.get(
                    `/api/diagnosticos/licencia/${this.idlicencia}`,
                );
                if (response.data.length > 0) {
                    const diagnostic = response.data[0]; // Assuming one diagnostic per licencia
                    this.diagnostico = diagnostic;
                    this.observaciones = diagnostic.observaciones || [];
                    this.fetchDiagnosticFile(diagnostic.idDiagnostico);
                    await this.fetchAllObservationFiles();
                }
            } catch (error) {
                console.error('Error fetching diagnostic:', error);
            }
        },
        async fetchDiagnosticFile(idDiagnostico) {
            try {
                const response = await axios.get(
                    `/api/diagnosticos/${idDiagnostico}/archivo`,
                );
                if (response.data.success) {
                    this.diagnosticFileUrl = response.data.url;
                }
            } catch (error) {
                console.error('Error fetching diagnostic file:', error);
            }
        },
        async fetchObservationFile(idObservacion) {
            try {
                const response = await axios.get(
                    `/api/observaciones/${idObservacion}/archivo`,
                );
                if (response.data.success) {
                    this.$set(
                        this.observationsFileUrls,
                        idObservacion,
                        response.data.url,
                    );
                }
            } catch (error) {
                console.error('Error fetching observation file:', error);
            }
        },
        async fetchAllObservationFiles() {
            for (const observation of this.observaciones) {
                if (observation.idObservacion && observation.archivo_url) {
                    try {
                        const response = await axios.get(
                            `/api/observaciones/${observation.idObservacion}/archivo`,
                        );

                        if (response.data.success) {
                            this.$set(
                                this.observationsFileUrls,
                                observation.idObservacion,
                                response.data.url,
                            );
                        }
                    } catch (error) {
                        console.error(
                            `Error fetching file for observation ID ${observation.idObservacion}:`,
                            error,
                        );
                    }
                }
            }
        },
        saveDiagnostico() {
            const diagnosticoPayload = {
                codigo_icd10: this.diagnostico.codigo_icd10,
                descripcion: this.diagnostico.descripcion,
                archivo: this.diagnostico.archivo,
                //proveedor: this.diagnostico.proveedor,
                //establecimiento: this.diagnostico.establecimiento,
                // plataforma: {
                //     url: this.diagnostico.plataforma.url,
                //     codigo: this.diagnostico.plataforma.codigo,
                //     usuario: this.diagnostico.plataforma.usuario,
                //     contrasena: this.diagnostico.plataforma.contrasena,
                // },
                observaciones: this.observaciones.map((obs) => ({
                    descripcion: obs.descripcion,
                    fecha: obs.fecha,
                    tipo: obs.tipo,
                    valor: obs.valor,
                    unidad: obs.unidad,
                    archivo: obs.archivo,
                    sitio_web: obs.sitio_web,
                    usuario: obs.usuario,
                    contrasena: obs.contrasena,
                    codigo: obs.codigo,
                })),
            };

            // Emit the payload to the parent component
            this.$emit('save-diagnostico', diagnosticoPayload);

            // Close the modal
            this.resetForm();
        },
        resetForm() {
            this.diagnostico = {
                codigo_icd10: '',
                descripcion: '',
                archivo: null,
                proveedor: '',
                establecimiento: '',
                // plataforma: {
                //     url: '',
                //     codigo: '',
                //     usuario: '',
                //     contrasena: '',
                // },
            };
            this.observaciones = [];
            this.diagnosticFileUrl = '';
            this.observationsFileUrls = {};
            this.isOpen = false;
        },
        close() {
            this.isOpen = false;
        },
        addObservacion() {
            this.openObservacionDialog('add');
        },
        editObservacion(observacion) {
            this.openObservacionDialog('edit', observacion);
        },
        openObservacionDialog(mode, observacion = null) {
            this.observacionDialogMode = mode;
            if (mode === 'edit' && observacion) {
                this.currentObservacion = { ...observacion };
            } else {
                this.currentObservacion = {
                    id: null,
                    descripcion: '',
                    fecha: '',
                    tipo: '',
                    valor: '',
                    unidad: '',
                    archivo: null,
                    sitio_web: '',
                    usuario: '',
                    contrasena: '',
                    codigo: '',
                };
            }
            this.isObservacionDialogOpen = true;
        },
        handleSaveObservacion(observacion) {
            if (this.observacionDialogMode === 'add') {
                observacion.id = this.observaciones.length + 1;
                this.observaciones.push(observacion);
            } else if (this.observacionDialogMode === 'edit') {
                const index = this.observaciones.findIndex(
                    (o) => o.id === observacion.id,
                );
                if (index !== -1) {
                    this.observaciones[index] = observacion;
                }
            }
            this.isObservacionDialogOpen = false;
        },
    },
    mounted() {
        this.fetchDiagnosticByLicencia();
    },
};
</script>

<style scoped>
.diagnostico-modal {
    background-color: #f5f5f5;
    border-radius: 10px;
    overflow: hidden;
}
.v-toolbar {
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}
.v-card {
    border-radius: 8px;
    box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.16);
}
</style>
