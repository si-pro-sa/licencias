<template>
    <div>
        <v-card outlined>
            <v-card-title class="headline primary white--text">
                {{ editando ? 'Editar Junta Médica' : 'Nueva Junta Médica' }}
            </v-card-title>
            <v-card-text>
                <v-container>
                    <v-form
                        ref="form"
                        v-model="valid"
                        lazy-validation
                    >
                        <v-row>
                            <v-col
                                cols="12"
                                md="6"
                            >
                                <v-text-field
                                    v-model="juntaMedica.nombre"
                                    label="Nombre de la Junta Médica"
                                    :rules="[
                                        (v) => !!v || 'El nombre es requerido',
                                    ]"
                                    required
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                md="6"
                            >
                                <v-select
                                    v-model="juntaMedica.tipo"
                                    :items="tiposJunta"
                                    label="Tipo de Junta"
                                    :rules="[
                                        (v) => !!v || 'El tipo es requerido',
                                    ]"
                                    required
                                ></v-select>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col
                                cols="12"
                                md="6"
                            >
                                <v-menu
                                    v-model="fechaMenu"
                                    :close-on-content-click="false"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="juntaMedica.fecha"
                                            label="Fecha de Reunión"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                            :rules="[
                                                (v) =>
                                                    !!v ||
                                                    'La fecha es requerida',
                                            ]"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="juntaMedica.fecha"
                                        @input="fechaMenu = false"
                                        locale="es"
                                    ></v-date-picker>
                                </v-menu>
                            </v-col>
                            <v-col
                                cols="12"
                                md="6"
                            >
                                <v-text-field
                                    v-model="juntaMedica.hora"
                                    label="Hora"
                                    type="time"
                                    :rules="[
                                        (v) => !!v || 'La hora es requerida',
                                    ]"
                                    required
                                ></v-text-field>
                            </v-col>
                        </v-row>

                        <!-- Selector de Licencia -->
                        <v-row>
                            <v-col cols="12">
                                <v-autocomplete
                                    v-model="juntaMedica.idlicencia"
                                    :items="licenciasDisponibles"
                                    item-text="descripcion"
                                    item-value="id"
                                    label="Seleccionar Licencia"
                                    prepend-icon="mdi-file-document-outline"
                                    :rules="[
                                        (v) =>
                                            !!v || 'La licencia es requerida',
                                    ]"
                                    required
                                    @change="onLicenciaSelected"
                                >
                                    <template v-slot:selection="{ item }">
                                        <span>{{ item.descripcion }}</span>
                                    </template>
                                    <template v-slot:item="{ item }">
                                        <v-list-item-content>
                                            <v-list-item-title>{{
                                                item.descripcion
                                            }}</v-list-item-title>
                                            <v-list-item-subtitle>
                                                Agente:
                                                {{ item.nombreAgente }} |
                                                Fechas: {{ item.fechaInicio }} -
                                                {{ item.fechaFin }}
                                            </v-list-item-subtitle>
                                        </v-list-item-content>
                                    </template>
                                </v-autocomplete>
                            </v-col>
                        </v-row>

                        <v-subheader class="text-h6 mt-4"
                            >Miembros de la Junta Médica</v-subheader
                        >
                        <v-divider></v-divider>

                        <div
                            v-for="(miembro, index) in juntaMedica.miembros"
                            :key="index"
                            class="mt-3"
                        >
                            <v-row>
                                <v-col
                                    cols="12"
                                    md="5"
                                >
                                    <v-autocomplete
                                        v-model="miembro.idagente"
                                        :items="agentesDisponibles"
                                        item-text="nombre"
                                        item-value="idagente"
                                        label="Seleccionar Agente"
                                        :rules="[
                                            (v) =>
                                                !!v || 'El agente es requerido',
                                        ]"
                                        required
                                    ></v-autocomplete>
                                </v-col>
                                <v-col
                                    cols="12"
                                    md="5"
                                >
                                    <v-select
                                        v-model="miembro.rol"
                                        :items="rolesJunta"
                                        label="Rol en la Junta"
                                        :rules="[
                                            (v) => !!v || 'El rol es requerido',
                                        ]"
                                        required
                                    ></v-select>
                                </v-col>
                                <v-col
                                    cols="12"
                                    md="2"
                                >
                                    <v-btn
                                        color="error"
                                        icon
                                        @click="eliminarMiembro(index)"
                                        :disabled="index < 3"
                                    >
                                        <v-icon>mdi-delete</v-icon>
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </div>

                        <v-row
                            class="mt-3"
                            v-if="juntaMedica.miembros.length < 5"
                        >
                            <v-col cols="12">
                                <v-btn
                                    color="success"
                                    @click="agregarMiembro"
                                    block
                                >
                                    <v-icon left>mdi-account-plus</v-icon>
                                    Agregar Miembro
                                </v-btn>
                            </v-col>
                        </v-row>

                        <v-row v-if="juntaMedica.idlicencia">
                            <v-col cols="12">
                                <v-card
                                    outlined
                                    class="mt-4"
                                >
                                    <v-card-title class="subtitle-1">
                                        Información de la Licencia
                                    </v-card-title>
                                    <v-card-text>
                                        <v-row>
                                            <v-col
                                                cols="12"
                                                md="6"
                                            >
                                                <p>
                                                    <strong>Agente:</strong>
                                                    {{
                                                        licenciaInfo.nombreAgente
                                                    }}
                                                </p>
                                                <p>
                                                    <strong
                                                        >Tipo de
                                                        Licencia:</strong
                                                    >
                                                    {{
                                                        licenciaInfo.tipoLicencia
                                                    }}
                                                </p>
                                            </v-col>
                                            <v-col
                                                cols="12"
                                                md="6"
                                            >
                                                <p>
                                                    <strong
                                                        >Fecha Inicio:</strong
                                                    >
                                                    {{
                                                        licenciaInfo.fechaInicio
                                                    }}
                                                </p>
                                                <p>
                                                    <strong>Fecha Fin:</strong>
                                                    {{ licenciaInfo.fechaFin }}
                                                </p>
                                            </v-col>
                                        </v-row>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>

                        <v-subheader class="text-h6 mt-4"
                            >Observaciones</v-subheader
                        >
                        <v-divider></v-divider>

                        <v-row>
                            <v-col cols="12">
                                <v-textarea
                                    v-model="juntaMedica.observaciones"
                                    label="Observaciones"
                                    auto-grow
                                    rows="3"
                                    counter
                                ></v-textarea>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="error"
                    text
                    @click="cancelar"
                >
                    Cancelar
                </v-btn>
                <v-btn
                    color="primary"
                    :disabled="!valid || loading"
                    :loading="loading"
                    @click="guardar"
                >
                    {{ editando ? 'Actualizar' : 'Guardar' }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
export default {
    name: 'JuntaMedicaForm',
    props: {
        idlicencia: {
            type: [Number, String],
            default: null,
        },
        juntaMedicaData: {
            type: Object,
            default: null,
        },
    },
    data() {
        return {
            valid: false,
            loading: false,
            editando: false,
            fechaMenu: false,

            // Datos de la junta médica
            juntaMedica: {
                id: null,
                nombre: '',
                tipo: '',
                fecha: new Date().toISOString().substr(0, 10),
                hora: '09:00',
                idlicencia: null,
                miembros: [
                    { idagente: null, rol: 'Presidente' },
                    { idagente: null, rol: 'Secretario' },
                    { idagente: null, rol: 'Vocal' },
                ],
                observaciones: '',
                estado: 'Pendiente',
            },

            // Opciones para los selects
            tiposJunta: ['Evaluación Inicial', 'Revisión', 'Prórroga', 'Alta'],
            rolesJunta: [
                'Presidente',
                'Secretario',
                'Vocal',
                'Especialista',
                'Asesor',
            ],

            // Datos mockeados para desarrollo
            agentesDisponibles: [
                { idagente: 1, nombre: 'Dr. Martínez, Carlos' },
                { idagente: 2, nombre: 'Dra. González, María' },
                { idagente: 3, nombre: 'Dr. Sánchez, Javier' },
                { idagente: 4, nombre: 'Dra. Fernández, Laura' },
                { idagente: 5, nombre: 'Dr. Rodríguez, Eduardo' },
                { idagente: 6, nombre: 'Dra. López, Ana' },
                { idagente: 7, nombre: 'Dr. García, Pablo' },
            ],
            licenciasDisponibles: [
                {
                    id: 123,
                    descripcion:
                        'Licencia por Enfermedad Prolongada - Pérez, Juan',
                    nombreAgente: 'Pérez, Juan',
                    tipoLicencia: 'Enfermedad Prolongada',
                    fechaInicio: '01/02/2024',
                    fechaFin: '15/03/2024',
                },
                {
                    id: 124,
                    descripcion:
                        'Licencia por Accidente Laboral - Gómez, María',
                    nombreAgente: 'Gómez, María',
                    tipoLicencia: 'Accidente Laboral',
                    fechaInicio: '15/02/2024',
                    fechaFin: '30/04/2024',
                },
                {
                    id: 125,
                    descripcion: 'Licencia Médica - Rodríguez, Carlos',
                    nombreAgente: 'Rodríguez, Carlos',
                    tipoLicencia: 'Enfermedad Común',
                    fechaInicio: '10/03/2024',
                    fechaFin: '20/03/2024',
                },
                {
                    id: 126,
                    descripcion: 'Licencia por Maternidad - López, Ana',
                    nombreAgente: 'López, Ana',
                    tipoLicencia: 'Maternidad',
                    fechaInicio: '01/03/2024',
                    fechaFin: '01/06/2024',
                },
                {
                    id: 127,
                    descripcion:
                        'Licencia por Enfermedad Crónica - Fernández, Roberto',
                    nombreAgente: 'Fernández, Roberto',
                    tipoLicencia: 'Enfermedad Crónica',
                    fechaInicio: '05/01/2024',
                    fechaFin: '05/04/2024',
                },
            ],
            licenciaInfo: {
                nombreAgente: 'Pérez, Juan',
                tipoLicencia: 'Enfermedad Prolongada',
                fechaInicio: '01/02/2024',
                fechaFin: '15/03/2024',
            },
        };
    },
    watch: {
        juntaMedicaData: {
            handler(value) {
                if (value) {
                    this.cargarDatos(value);
                }
            },
            immediate: true,
        },
        idlicencia: {
            handler(value) {
                if (value) {
                    this.juntaMedica.idlicencia = value;
                    // En un entorno real, aquí cargaríamos la información de la licencia
                    // this.cargarInfoLicencia(value);
                }
            },
            immediate: true,
        },
    },
    methods: {
        cargarDatos(data) {
            if (!data) return;

            this.editando = true;
            this.juntaMedica = { ...data };

            // Asegurar que siempre haya al menos 3 miembros
            if (
                !this.juntaMedica.miembros ||
                this.juntaMedica.miembros.length < 3
            ) {
                this.juntaMedica.miembros = [
                    { idagente: null, rol: 'Presidente' },
                    { idagente: null, rol: 'Secretario' },
                    { idagente: null, rol: 'Vocal' },
                ];
            }

            // Cargar información de la licencia si existe
            if (this.juntaMedica.idlicencia) {
                this.cargarInfoLicencia(this.juntaMedica.idlicencia);
            }
        },
        agregarMiembro() {
            if (this.juntaMedica.miembros.length < 5) {
                this.juntaMedica.miembros.push({
                    idagente: null,
                    rol: 'Vocal',
                });
            }
        },
        eliminarMiembro(index) {
            // No permitir eliminar los primeros 3 miembros (obligatorios)
            if (index >= 3) {
                this.juntaMedica.miembros.splice(index, 1);
            }
        },
        cargarInfoLicencia(idLicencia) {
            this.loading = true;

            // Comentado: Implementación con datos mock
            /*
            setTimeout(() => {
                // Buscar la licencia en nuestro array mock por ID
                const licenciaEncontrada = this.licenciasDisponibles.find(lic => lic.id === idLicencia);
                
                if (licenciaEncontrada) {
                    this.licencia = licenciaEncontrada;
                } else {
                    // Manejo de error
                    this.$store.commit('app/SET_SNACKBAR', {
                        color: 'error',
                        message: 'No se encontró la licencia seleccionada'
                    });
                }
                
                this.loading = false;
            }, 500);
            */

            // Implementación usando servicio API
            import('../../api/licenciaApi')
                .then((module) => {
                    const licenciaApi = module.default;

                    licenciaApi
                        .getLicencia(idLicencia)
                        .then((response) => {
                            // Guardamos la licencia completa
                            this.licencia = response;

                            // Actualizamos la información resumida
                            this.licenciaInfo = {
                                nombreAgente:
                                    response.nombreAgente ||
                                    response.agente?.nombre ||
                                    'Sin nombre',
                                tipoLicencia:
                                    response.tipoLicencia ||
                                    response.tipo_licencia?.nombre ||
                                    'Sin tipo',
                                fechaInicio:
                                    response.fechaInicio ||
                                    response.fecha_inicio ||
                                    'Sin fecha',
                                fechaFin:
                                    response.fechaFin ||
                                    response.fecha_fin ||
                                    'Sin fecha',
                            };

                            // Generamos nombre automático si es necesario
                            this.generarNombreAutomatico();
                        })
                        .catch((error) => {
                            // Si ocurre un error, mostramos mensaje y usamos datos mock como fallback
                            console.error('Error al cargar licencia:', error);

                            this.$store.commit('app/SET_SNACKBAR', {
                                color: 'error',
                                message:
                                    'Error al cargar la licencia: ' +
                                    error.message,
                            });

                            // Fallback a datos mock
                            const licenciaEncontrada =
                                this.licenciasDisponibles.find(
                                    (lic) => lic.id === idLicencia,
                                );
                            if (licenciaEncontrada) {
                                this.licenciaInfo = {
                                    nombreAgente:
                                        licenciaEncontrada.nombreAgente,
                                    tipoLicencia:
                                        licenciaEncontrada.tipoLicencia,
                                    fechaInicio: licenciaEncontrada.fechaInicio,
                                    fechaFin: licenciaEncontrada.fechaFin,
                                };
                            }
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                })
                .catch((error) => {
                    console.error('Error al importar el servicio API:', error);
                    this.loading = false;
                });
        },
        async guardar() {
            if (!this.$refs.form.validate()) return;

            this.loading = true;

            try {
                // En un entorno real, aquí enviaríamos los datos al backend
                if (this.editando) {
                    // await this.$store.dispatch('juntaMedica/actualizarJuntaMedica', this.juntaMedica);
                    this.$emit('actualizado', this.juntaMedica);
                } else {
                    // await this.$store.dispatch('juntaMedica/crearJuntaMedica', this.juntaMedica);
                    this.$emit('creado', this.juntaMedica);
                }

                // Simulamos una demora para mostrar el loading
                await new Promise((resolve) => setTimeout(resolve, 1000));

                // Mostrar mensaje de éxito
                this.$emit('guardado', this.juntaMedica);
            } catch (error) {
                console.error('Error al guardar junta médica:', error);
                // Mostrar mensaje de error
            } finally {
                this.loading = false;
            }
        },
        cancelar() {
            this.$emit('cancelar');
        },
        onLicenciaSelected() {
            if (this.juntaMedica.idlicencia) {
                this.cargarInfoLicencia(this.juntaMedica.idlicencia);
                this.generarNombreAutomatico();
            }
        },
        generarNombreAutomatico() {
            if (
                !this.juntaMedica.nombre ||
                this.juntaMedica.nombre.trim() === '' ||
                this.juntaMedica.nombre.includes('Junta Médica -')
            ) {
                const fechaActual = new Date().toLocaleDateString('es-AR');

                if (this.licenciaInfo.nombreAgente) {
                    this.juntaMedica.nombre = `Junta Médica - ${this.licenciaInfo.nombreAgente} - ${fechaActual}`;
                } else {
                    this.juntaMedica.nombre = `Junta Médica - Nueva - ${fechaActual}`;
                }
            }
        },
    },
};
</script>

<style scoped>
.v-subheader {
    padding-left: 0;
}
</style>
