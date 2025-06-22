<template>
    <v-dialog
        v-model="dialog"
        max-width="800"
        scrollable
    >
        <v-card v-if="juntaMedica">
            <v-card-title class="primary white--text headline">
                Detalles de Junta Médica
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
                <v-row>
                    <v-col
                        cols="12"
                        md="6"
                    >
                        <v-list-item>
                            <v-list-item-avatar
                                color="primary"
                                class="white--text"
                            >
                                <v-icon>mdi-account</v-icon>
                            </v-list-item-avatar>
                            <v-list-item-content>
                                <v-list-item-subtitle class="text-caption"
                                    >Empleado</v-list-item-subtitle
                                >
                                <v-list-item-title class="font-weight-medium">
                                    {{ empleado }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-col>

                    <v-col
                        cols="12"
                        md="6"
                    >
                        <v-list-item>
                            <v-list-item-avatar
                                color="primary"
                                class="white--text"
                            >
                                <v-icon>mdi-calendar</v-icon>
                            </v-list-item-avatar>
                            <v-list-item-content>
                                <v-list-item-subtitle class="text-caption"
                                    >Fecha</v-list-item-subtitle
                                >
                                <v-list-item-title class="font-weight-medium">
                                    {{ formatDate(juntaMedica.fecha) }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-col>

                    <v-col
                        cols="12"
                        md="6"
                    >
                        <v-list-item>
                            <v-list-item-avatar
                                color="primary"
                                class="white--text"
                            >
                                <v-icon>mdi-format-list-bulleted-type</v-icon>
                            </v-list-item-avatar>
                            <v-list-item-content>
                                <v-list-item-subtitle class="text-caption"
                                    >Tipo</v-list-item-subtitle
                                >
                                <v-list-item-title class="font-weight-medium">
                                    {{ juntaMedica.tipo }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-col>

                    <v-col
                        cols="12"
                        md="6"
                    >
                        <v-list-item>
                            <v-list-item-avatar
                                :color="getStatusColor(juntaMedica.estado)"
                                class="white--text"
                            >
                                <v-icon>mdi-checkbox-marked-circle</v-icon>
                            </v-list-item-avatar>
                            <v-list-item-content>
                                <v-list-item-subtitle class="text-caption"
                                    >Estado</v-list-item-subtitle
                                >
                                <v-list-item-title class="font-weight-medium">
                                    {{ juntaMedica.estado }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-col>

                    <!-- Diagnóstico -->
                    <v-col cols="12">
                        <v-card
                            outlined
                            class="mb-3"
                        >
                            <v-card-title class="subtitle-1">
                                <v-icon
                                    left
                                    color="primary"
                                    >mdi-stethoscope</v-icon
                                >
                                Diagnóstico
                            </v-card-title>
                            <v-card-text>
                                <div class="text-body-1">
                                    {{
                                        juntaMedica.diagnostico ||
                                        'No se ha registrado diagnóstico'
                                    }}
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-col>

                    <!-- Recomendaciones -->
                    <v-col cols="12">
                        <v-card
                            outlined
                            class="mb-3"
                        >
                            <v-card-title class="subtitle-1">
                                <v-icon
                                    left
                                    color="primary"
                                    >mdi-playlist-check</v-icon
                                >
                                Recomendaciones
                            </v-card-title>
                            <v-card-text>
                                <div class="text-body-1">
                                    {{
                                        juntaMedica.recomendaciones ||
                                        'No se han registrado recomendaciones'
                                    }}
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-col>

                    <!-- Observaciones -->
                    <v-col cols="12">
                        <v-card
                            outlined
                            class="mb-3"
                        >
                            <v-card-title class="subtitle-1">
                                <v-icon
                                    left
                                    color="primary"
                                    >mdi-note-text</v-icon
                                >
                                Observaciones
                            </v-card-title>
                            <v-card-text>
                                <div class="text-body-1">
                                    {{
                                        juntaMedica.observaciones ||
                                        'No se han registrado observaciones'
                                    }}
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-col>

                    <!-- Documentos adjuntos -->
                    <v-col
                        cols="12"
                        v-if="
                            juntaMedica.documentos &&
                            juntaMedica.documentos.length > 0
                        "
                    >
                        <v-card outlined>
                            <v-card-title class="subtitle-1">
                                <v-icon
                                    left
                                    color="primary"
                                    >mdi-file-document-multiple</v-icon
                                >
                                Documentos adjuntos
                            </v-card-title>
                            <v-card-text>
                                <v-list dense>
                                    <v-list-item
                                        v-for="(
                                            documento, index
                                        ) in juntaMedica.documentos"
                                        :key="index"
                                        @click="downloadFile(documento)"
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
                                            <v-icon v-else>mdi-file</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title>{{
                                                documento.nombre
                                            }}</v-list-item-title>
                                            <v-list-item-subtitle>{{
                                                formatFileSize(documento.tamano)
                                            }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                        <v-list-item-action>
                                            <v-btn
                                                icon
                                                small
                                                color="primary"
                                            >
                                                <v-icon>mdi-download</v-icon>
                                            </v-btn>
                                        </v-list-item-action>
                                    </v-list-item>
                                </v-list>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-card-text>

            <v-card-actions class="pa-4">
                <v-spacer></v-spacer>
                <v-btn
                    text
                    color="primary"
                    @click="closeDialog"
                >
                    Cerrar
                </v-btn>
                <v-btn
                    color="primary"
                    @click="editJuntaMedica"
                >
                    <v-icon left>mdi-pencil</v-icon>
                    Editar
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: 'JuntaMedicaView',

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

    computed: {
        dialog: {
            get() {
                return this.value;
            },
            set(value) {
                this.$emit('input', value);
            },
        },

        empleado() {
            if (!this.juntaMedica || !this.juntaMedica.empleado) {
                return 'No asignado';
            }
            return (
                this.juntaMedica.empleado.nombre_completo ||
                `${this.juntaMedica.empleado.nombre} ${
                    this.juntaMedica.empleado.apellido || ''
                }`
            );
        },
    },

    methods: {
        /**
         * Cierra el diálogo
         */
        closeDialog() {
            this.dialog = false;
        },

        /**
         * Emite evento para editar la junta médica
         */
        editJuntaMedica() {
            this.$emit('edit', this.juntaMedica);
            this.closeDialog();
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
         * @returns {String} Color para el avatar
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
         * Devuelve el icono correspondiente al tipo de archivo
         * @param {String} fileName - Nombre del archivo
         * @returns {String} Icono de Material Design
         */
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

        /**
         * Formatea el tamaño del archivo en bytes a una unidad legible
         * @param {Number} bytes - Tamaño en bytes
         * @returns {String} Tamaño formateado
         */
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

        /**
         * Maneja la descarga de un archivo
         * @param {Object} documento - Objeto con la información del documento
         */
        downloadFile(documento) {
            if (!documento || !documento.url) {
                this.$toast.error('No se puede descargar el archivo');
                return;
            }

            // Abrir el enlace en una nueva pestaña o descargar directamente
            window.open(documento.url, '_blank');
        },
    },
};
</script>

<style scoped>
.v-card__title.headline {
    word-break: break-word;
}
</style>
