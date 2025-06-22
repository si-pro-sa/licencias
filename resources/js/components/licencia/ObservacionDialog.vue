<template>
    <v-dialog
        v-model="isOpen"
        max-width="800px"
        persistent
    >
        <v-card>
            <!-- Barra superior -->
            <v-toolbar
                flat
                color="indigo darken-1"
                dark
            >
                <v-toolbar-title>
                    {{ mode === 'add' ? 'Agregar Estudio' : 'Editar Estudio' }}
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn
                    icon
                    @click="close"
                >
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar>

            <!-- Contenido -->
            <v-card-text>
                <v-container>
                    <!-- Primera fila -->
                    <v-row>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-textarea
                                v-model="observacion.descripcion"
                                label="Descripción"
                                outlined
                                auto-grow
                                rows="4"
                            ></v-textarea>
                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-text-field
                                v-model="observacion.fecha"
                                label="Fecha"
                                outlined
                                type="date"
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <!-- Segunda fila -->
                    <v-row>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-select
                                v-model="observacion.tipo"
                                :items="tiposObservacion"
                                label="Tipo"
                                outlined
                            ></v-select>
                        </v-col>
                        <v-col
                            cols="6"
                            md="3"
                        >
                            <v-text-field
                                v-model="observacion.valor"
                                label="Valor"
                                outlined
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="6"
                            md="3"
                        >
                            <v-text-field
                                v-model="observacion.unidad"
                                label="Unidad"
                                outlined
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <!-- Subida de archivos -->
                    <v-row>
                        <v-col cols="12">
                            <v-file-input
                                v-model="observacion.archivo"
                                label="Cargar Archivo (PNG, JPG, PDF)"
                                outlined
                                dense
                                accept=".png, .jpg, .jpeg, .pdf"
                                prepend-icon="mdi-file-upload"
                                show-size
                            ></v-file-input>
                        </v-col>
                    </v-row>

                    <!-- Datos del sitio web -->
                    <v-row>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-text-field
                                v-model="observacion.sitio_web"
                                label="URL del Sitio Web"
                                outlined
                                prepend-icon="mdi-link"
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-text-field
                                v-model="observacion.codigo"
                                label="Código para el Estudio"
                                outlined
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-text-field
                                v-model="observacion.usuario"
                                label="Usuario"
                                outlined
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-text-field
                                v-model="observacion.contrasena"
                                label="Contraseña"
                                outlined
                                type="password"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>

            <!-- Acciones -->
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="indigo darken-1"
                    dark
                    @click="save"
                >
                    Guardar
                </v-btn>
                <v-btn
                    color="red darken-1"
                    text
                    @click="close"
                >
                    Cancelar
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props: {
        value: {
            type: Boolean,
            required: true,
        },
        mode: {
            type: String,
            default: 'add', // Puede ser 'add' o 'edit'
        },
        initialObservacion: {
            type: Object,
            default: () => ({
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
            }),
        },
    },
    data() {
        return {
            isOpen: this.value,
            observacion: { ...this.initialObservacion },
            tiposObservacion: [
                'Examen de Sangre',
                'Imagenología',
                'Laboratorio',
                'Otros',
            ],
        };
    },
    watch: {
        value(val) {
            this.isOpen = val;
        },
        isOpen(val) {
            this.$emit('input', val);
        },
        initialObservacion: {
            immediate: true,
            handler(newVal) {
                this.observacion = { ...newVal };
            },
        },
    },
    methods: {
        close() {
            this.isOpen = false;
        },
        save() {
            // Emitir evento con la observación editada o nueva
            this.$emit('save', this.observacion);
            this.close();
        },
    },
};
</script>

<style scoped>
.v-card {
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}
.v-toolbar-title {
    font-weight: bold;
}
.v-btn {
    border-radius: 20px;
}
</style>
