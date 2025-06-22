<template>
    <v-dialog
        v-model="dialog"
        max-width="800px"
        persistent
    >
        <v-card>
            <v-card-title class="headline primary white--text">
                {{ isEdit ? 'Editar' : 'Nueva' }} Junta Médica
                <v-spacer></v-spacer>
                <v-btn
                    icon
                    dark
                    @click="closeDialog"
                >
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>

            <v-card-text class="pt-4">
                <v-form
                    ref="form"
                    v-model="valid"
                    lazy-validation
                >
                    <v-container>
                        <v-row>
                            <v-col
                                cols="12"
                                md="6"
                            >
                                <v-autocomplete
                                    v-model="formData.empleado_id"
                                    :items="empleados"
                                    :rules="rules.empleado"
                                    item-text="nombre_completo"
                                    item-value="id"
                                    label="Empleado"
                                    required
                                    :loading="loadingEmpleados"
                                    :disabled="loading"
                                    clearable
                                    return-object
                                >
                                    <template v-slot:selection="{ item }">
                                        {{ item.nombre_completo }}
                                    </template>
                                    <template v-slot:item="{ item }">
                                        <v-list-item-avatar>
                                            <v-icon>mdi-account</v-icon>
                                        </v-list-item-avatar>
                                        <v-list-item-content>
                                            <v-list-item-title>{{
                                                item.nombre_completo
                                            }}</v-list-item-title>
                                            <v-list-item-subtitle>{{
                                                item.documento
                                            }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </template>
                                </v-autocomplete>
                            </v-col>
                            <v-col
                                cols="12"
                                md="6"
                            >
                                <v-menu
                                    v-model="menuFecha"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="auto"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="formattedDate"
                                            label="Fecha"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                            :rules="rules.fecha"
                                            :disabled="loading"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="formData.fecha"
                                        @input="menuFecha = false"
                                        locale="es-ES"
                                        :disabled="loading"
                                    ></v-date-picker>
                                </v-menu>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col
                                cols="12"
                                md="6"
                            >
                                <v-select
                                    v-model="formData.tipo"
                                    :items="tiposJuntaMedica"
                                    label="Tipo"
                                    outlined
                                    dense
                                    :rules="rules.tipo"
                                    :disabled="loading"
                                ></v-select>
                            </v-col>
                            <v-col
                                cols="12"
                                md="6"
                            >
                                <v-select
                                    v-model="formData.estado"
                                    :items="estadosJuntaMedica"
                                    label="Estado"
                                    outlined
                                    dense
                                    :rules="rules.estado"
                                    :disabled="loading"
                                    :item-color="
                                        getStatusColor(formData.estado)
                                    "
                                >
                                    <template v-slot:selection="{ item }">
                                        <v-chip
                                            small
                                            :color="getStatusColor(item)"
                                            text-color="white"
                                        >
                                            {{ item }}
                                        </v-chip>
                                    </template>
                                    <template v-slot:item="{ item }">
                                        <v-chip
                                            small
                                            :color="getStatusColor(item)"
                                            text-color="white"
                                            class="mr-2"
                                        >
                                            {{ item }}
                                        </v-chip>
                                    </template>
                                </v-select>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12">
                                <v-textarea
                                    v-model="formData.diagnostico"
                                    label="Diagnóstico"
                                    outlined
                                    dense
                                    :disabled="loading"
                                    :rules="rules.diagnostico"
                                    counter="500"
                                    maxlength="500"
                                    rows="3"
                                ></v-textarea>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12">
                                <v-textarea
                                    v-model="formData.recomendaciones"
                                    label="Recomendaciones"
                                    outlined
                                    dense
                                    :disabled="loading"
                                    counter="500"
                                    maxlength="500"
                                    rows="3"
                                ></v-textarea>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12">
                                <v-textarea
                                    v-model="formData.observaciones"
                                    label="Observaciones"
                                    outlined
                                    dense
                                    :disabled="loading"
                                    counter="500"
                                    maxlength="500"
                                    rows="3"
                                ></v-textarea>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12">
                                <v-file-input
                                    v-model="archivos"
                                    label="Documentos adjuntos"
                                    outlined
                                    dense
                                    multiple
                                    counter
                                    show-size
                                    prepend-icon="mdi-paperclip"
                                    :disabled="loading"
                                >
                                    <template
                                        v-slot:selection="{ index, text }"
                                    >
                                        <v-chip
                                            v-if="index < 2"
                                            color="primary"
                                            dark
                                            label
                                            small
                                        >
                                            {{ text }}
                                        </v-chip>

                                        <span
                                            v-else-if="index === 2"
                                            class="text-overline grey--text text--darken-3 mx-2"
                                        >
                                            +{{ archivos.length - 2 }} archivos
                                        </span>
                                    </template>
                                </v-file-input>
                            </v-col>
                        </v-row>

                        <v-row
                            v-if="
                                isEdit &&
                                formData.documentos &&
                                formData.documentos.length > 0
                            "
                        >
                            <v-col cols="12">
                                <v-card outlined>
                                    <v-card-title class="subtitle-1">
                                        <v-icon
                                            left
                                            color="primary"
                                            >mdi-file-document-multiple</v-icon
                                        >
                                        Documentos existentes
                                    </v-card-title>
                                    <v-card-text>
                                        <v-list dense>
                                            <v-list-item
                                                v-for="(
                                                    documento, index
                                                ) in formData.documentos"
                                                :key="index"
                                            >
                                                <v-list-item-icon>
                                                    <v-icon
                                                        v-if="
                                                            getFileIcon(
                                                                documento.nombre,
                                                            )
                                                        "
                                                    >
                                                        {{
                                                            getFileIcon(
                                                                documento.nombre,
                                                            )
                                                        }}
                                                    </v-icon>
                                                    <v-icon v-else
                                                        >mdi-file</v-icon
                                                    >
                                                </v-list-item-icon>
                                                <v-list-item-content>
                                                    <v-list-item-title>{{
                                                        documento.nombre
                                                    }}</v-list-item-title>
                                                    <v-list-item-subtitle>{{
                                                        formatFileSize(
                                                            documento.tamano,
                                                        )
                                                    }}</v-list-item-subtitle>
                                                </v-list-item-content>
                                                <v-list-item-action>
                                                    <v-btn
                                                        icon
                                                        small
                                                        color="error"
                                                        @click="
                                                            eliminarDocumento(
                                                                index,
                                                            )
                                                        "
                                                        :disabled="loading"
                                                    >
                                                        <v-icon
                                                            >mdi-delete</v-icon
                                                        >
                                                    </v-btn>
                                                </v-list-item-action>
                                            </v-list-item>
                                        </v-list>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-form>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="grey darken-1"
                    text
                    @click="closeDialog"
                    :disabled="loading"
                >
                    Cancelar
                </v-btn>
                <v-btn
                    color="primary"
                    @click="submit"
                    :loading="loading"
                    :disabled="!valid"
                >
                    <v-icon left>mdi-content-save</v-icon>
                    {{ isEdit ? 'Actualizar' : 'Guardar' }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
// Importamos el servicio de API para empleados y juntas médicas
import empleadoApi from '@/api/empleadoApi';
import juntaMedicaApi from '@/api/juntaMedicaApi';

export default {
    name: 'JuntaMedicaForm',

    props: {
        value: {
            type: Boolean,
            default: false,
        },
        juntaMedica: {
            type: Object,
            default: null,
        },
    },

    data() {
        return {
            valid: true,
            loading: false,
            loadingEmpleados: false,
            menuFecha: false,
            empleados: [],
            archivos: [],
            documentosEliminados: [],
            formData: {
                empleado_id: null,
                fecha: new Date().toISOString().substring(0, 10),
                tipo: '',
                estado: 'Programada',
                diagnostico: '',
                recomendaciones: '',
                observaciones: '',
                documentos: [],
            },
            tiposJuntaMedica: [
                'Calificación inicial',
                'Revisión de invalidez',
                'Revisión de PCL',
                'Revisión de estado',
                'Apelación',
                'Concepto rehabilitación',
                'Otro',
            ],
            estadosJuntaMedica: [
                'Programada',
                'Pendiente',
                'Completada',
                'Cancelada',
            ],
            rules: {
                empleado: [(v) => !!v || 'El empleado es requerido'],
                fecha: [(v) => !!v || 'La fecha es requerida'],
                tipo: [(v) => !!v || 'El tipo es requerido'],
                estado: [(v) => !!v || 'El estado es requerido'],
                diagnostico: [
                    (v) => !!v || 'El diagnóstico es requerido',
                    (v) =>
                        (v && v.length <= 500) ||
                        'El diagnóstico debe tener máximo 500 caracteres',
                ],
            },
        };
    },

    computed: {
        dialog: {
            get() {
                return this.value;
            },
            set(value) {
                this.$emit('input', value);
            },
        },

        isEdit() {
            return !!this.juntaMedica;
        },

        formattedDate() {
            if (!this.formData.fecha) return '';

            const [year, month, day] = this.formData.fecha.split('-');
            return `${day}/${month}/${year}`;
        },
    },

    watch: {
        value(newVal) {
            if (newVal) {
                this.initialize();
            }
        },

        juntaMedica: {
            handler(newVal) {
                this.resetForm();

                if (newVal) {
                    const juntaMedicaData = JSON.parse(JSON.stringify(newVal));

                    this.formData = {
                        id: juntaMedicaData.id,
                        empleado_id: juntaMedicaData.empleado || null,
                        fecha:
                            juntaMedicaData.fecha ||
                            new Date().toISOString().substring(0, 10),
                        tipo: juntaMedicaData.tipo || '',
                        estado: juntaMedicaData.estado || 'Programada',
                        diagnostico: juntaMedicaData.diagnostico || '',
                        recomendaciones: juntaMedicaData.recomendaciones || '',
                        observaciones: juntaMedicaData.observaciones || '',
                        documentos: juntaMedicaData.documentos || [],
                    };
                }
            },
            immediate: true,
        },

        dialog(val) {
            if (val) {
                this.cargarEmpleados();
            }
        },
    },

    methods: {
        /**
         * Inicializa el formulario
         */
        initialize() {
            this.loadEmpleados();

            if (this.isEdit && this.juntaMedica) {
                this.formData = {
                    ...this.formData,
                    ...this.juntaMedica,
                };
            } else {
                this.formData = {
                    empleado_id: null,
                    fecha: new Date().toISOString().substring(0, 10),
                    tipo: '',
                    estado: 'Programada',
                    diagnostico: '',
                    recomendaciones: '',
                    observaciones: '',
                    documentos: [],
                };
                this.archivos = [];
            }

            this.$nextTick(() => {
                this.$refs.form.resetValidation();
            });
        },

        /**
         * Carga la lista de empleados para el selector
         */
        async loadEmpleados() {
            this.loadingEmpleados = true;
            try {
                const response = await empleadoApi.getEmpleados();
                this.empleados = response.data || [];
            } catch (error) {
                console.error('Error al cargar empleados:', error);
                this.$toast.error('No se pudieron cargar los empleados');
            } finally {
                this.loadingEmpleados = false;
            }
        },

        /**
         * Resetea el formulario a sus valores iniciales
         */
        resetForm() {
            this.formData = {
                empleado_id: null,
                fecha: new Date().toISOString().substring(0, 10),
                tipo: '',
                estado: 'Programada',
                diagnostico: '',
                recomendaciones: '',
                observaciones: '',
                documentos: [],
            };

            this.archivos = [];
            this.documentosEliminados = [];

            if (this.$refs.form) {
                this.$refs.form.resetValidation();
            }
        },

        /**
         * Elimina un documento de la lista de documentos existentes
         */
        eliminarDocumento(index) {
            if (this.formData.documentos[index].id) {
                this.documentosEliminados.push(
                    this.formData.documentos[index].id,
                );
            }

            this.formData.documentos.splice(index, 1);
        },

        /**
         * Envía el formulario para crear/actualizar la junta médica
         */
        async submit() {
            if (!this.$refs.form.validate()) {
                return;
            }

            this.loading = true;

            try {
                const formData = new FormData();

                for (const key in this.formData) {
                    if (key === 'empleado_id' && this.formData[key]) {
                        formData.append(
                            key,
                            this.formData[key].id || this.formData[key],
                        );
                    } else if (key !== 'documentos') {
                        formData.append(key, this.formData[key]);
                    }
                }

                if (this.archivos.length > 0) {
                    this.archivos.forEach((archivo, index) => {
                        formData.append(`archivos[${index}]`, archivo);
                    });
                }

                if (this.documentosEliminados.length > 0) {
                    this.documentosEliminados.forEach((id, index) => {
                        formData.append(`documentos_eliminados[${index}]`, id);
                    });
                }

                let response;

                if (this.isEdit) {
                    response = await juntaMedicaApi.updateJuntaMedica(
                        this.formData.id,
                        formData,
                    );
                    this.$toast.success(
                        'Junta médica actualizada correctamente',
                    );
                } else {
                    response = await juntaMedicaApi.createJuntaMedica(formData);
                    this.$toast.success('Junta médica creada correctamente');
                }

                this.$emit('saved', response.data);
                this.closeDialog();
            } catch (error) {
                console.error('Error al guardar la junta médica:', error);

                const errorMessage =
                    error.response?.data?.message ||
                    'Ha ocurrido un error al guardar la junta médica';
                this.$toast.error(errorMessage);
            } finally {
                this.loading = false;
            }
        },

        /**
         * Cierra el diálogo de formulario
         */
        closeDialog() {
            this.$refs.form.resetValidation();
            this.archivos = [];
            this.documentosEliminados = [];
            this.$emit('input', false);
        },

        getStatusColor(status) {
            const colors = {
                Programada: 'blue',
                Completada: 'success',
                Cancelada: 'error',
                Pendiente: 'orange',
            };

            return colors[status] || 'grey';
        },

        getFileIcon(fileName) {
            if (!fileName) return 'mdi-file';

            const extension = fileName.split('.').pop().toLowerCase();

            const iconMap = {
                pdf: 'mdi-file-pdf',
                doc: 'mdi-file-word',
                docx: 'mdi-file-word',
                xls: 'mdi-file-excel',
                xlsx: 'mdi-file-excel',
                ppt: 'mdi-file-powerpoint',
                pptx: 'mdi-file-powerpoint',
                jpg: 'mdi-file-image',
                jpeg: 'mdi-file-image',
                png: 'mdi-file-image',
                gif: 'mdi-file-image',
                txt: 'mdi-file-document',
                zip: 'mdi-folder-zip',
                rar: 'mdi-folder-zip',
            };

            return iconMap[extension] || 'mdi-file';
        },

        formatFileSize(bytes) {
            if (!bytes || isNaN(bytes)) return 'Desconocido';

            const units = ['B', 'KB', 'MB', 'GB', 'TB'];
            let size = parseInt(bytes, 10);
            let unitIndex = 0;

            while (size >= 1024 && unitIndex < units.length - 1) {
                size /= 1024;
                unitIndex++;
            }

            return `${size.toFixed(2)} ${units[unitIndex]}`;
        },
    },
};
</script>

<style scoped>
.v-chip--small {
    height: 20px;
}
</style>
