<template>
    <div>
        <v-container>
            <v-row>
                <v-col cols="12">
                    <h1 class="text-center mb-4">
                        Historia Clínica - Salud Ocupacional
                    </h1>
                </v-col>
            </v-row>

            <!-- Componente TarjetaAgente para búsqueda -->
            <TarjetaAgente
                titulo="Búsqueda de Agente"
                :deshabilitado="false"
                @hayAgente="agenteEncontrado"
            />

            <!-- Historia Clínica -->
            <div v-if="mostrarHistoria && !hayError">
                <v-row>
                    <v-col cols="12">
                        <v-card>
                            <v-card-title
                                class="font-weight-regular teal darken-1 white--text headline"
                            >
                                <v-icon
                                    large
                                    color="white"
                                    >mdi-medical-bag</v-icon
                                >
                                <span class="white--text ml-4"
                                    >Historia Clínica</span
                                >
                            </v-card-title>

                            <v-card-text>
                                <v-tabs
                                    v-model="tabHistoria"
                                    background-color="primary"
                                    dark
                                    grow
                                >
                                    <v-tab>Exámenes Médicos</v-tab>
                                    <v-tab>Consultas</v-tab>
                                    <v-tab>Aptitud Laboral</v-tab>
                                    <v-tab>Licencias Médicas</v-tab>

                                    <v-tab-item>
                                        <v-card flat>
                                            <v-card-text>
                                                <v-data-table
                                                    :headers="headersExamenes"
                                                    :items="examenesMostrar"
                                                    :items-per-page="5"
                                                    class="elevation-1"
                                                >
                                                    <template
                                                        v-slot:item.acciones="{
                                                            item,
                                                        }"
                                                    >
                                                        <v-btn
                                                            small
                                                            color="primary"
                                                            text
                                                            @click="
                                                                verDetalle(item)
                                                            "
                                                        >
                                                            <v-icon small
                                                                >mdi-eye</v-icon
                                                            >
                                                            Ver
                                                        </v-btn>
                                                    </template>

                                                    <template
                                                        v-slot:item.estado="{
                                                            item,
                                                        }"
                                                    >
                                                        <v-chip
                                                            :color="
                                                                getChipColor(
                                                                    item.estado,
                                                                )
                                                            "
                                                            text-color="white"
                                                            small
                                                        >
                                                            {{ item.estado }}
                                                        </v-chip>
                                                    </template>
                                                </v-data-table>
                                            </v-card-text>
                                        </v-card>
                                    </v-tab-item>

                                    <v-tab-item>
                                        <v-card flat>
                                            <v-card-text>
                                                <v-data-table
                                                    :headers="headersConsultas"
                                                    :items="consultasMostrar"
                                                    :items-per-page="5"
                                                    class="elevation-1"
                                                >
                                                    <template
                                                        v-slot:item.acciones="{
                                                            item,
                                                        }"
                                                    >
                                                        <v-btn
                                                            small
                                                            color="primary"
                                                            text
                                                            @click="
                                                                verDetalleConsulta(
                                                                    item,
                                                                )
                                                            "
                                                        >
                                                            <v-icon small
                                                                >mdi-eye</v-icon
                                                            >
                                                            Ver
                                                        </v-btn>
                                                    </template>
                                                </v-data-table>
                                            </v-card-text>
                                        </v-card>
                                    </v-tab-item>

                                    <v-tab-item>
                                        <v-card flat>
                                            <v-card-text>
                                                <v-timeline dense>
                                                    <v-timeline-item
                                                        v-for="aptitud in aptitudMostrar"
                                                        :key="aptitud.id"
                                                        :color="
                                                            getAptitudColor(
                                                                aptitud.resultado,
                                                            )
                                                        "
                                                        small
                                                    >
                                                        <template
                                                            v-slot:opposite
                                                        >
                                                            <span
                                                                :class="`font-weight-bold ${getAptitudTextColor(
                                                                    aptitud.resultado,
                                                                )}`"
                                                                v-text="
                                                                    aptitud.fecha
                                                                "
                                                            ></span>
                                                        </template>
                                                        <v-card
                                                            class="elevation-2"
                                                        >
                                                            <v-card-title
                                                                class="text-h6"
                                                            >
                                                                {{
                                                                    aptitud.tipo
                                                                }}
                                                            </v-card-title>
                                                            <v-card-text>
                                                                <p>
                                                                    <strong
                                                                        >Resultado:</strong
                                                                    >
                                                                    {{
                                                                        aptitud.resultado
                                                                    }}
                                                                </p>
                                                                <p>
                                                                    <strong
                                                                        >Profesional:</strong
                                                                    >
                                                                    {{
                                                                        aptitud.profesional
                                                                    }}
                                                                </p>
                                                                <p>
                                                                    <strong
                                                                        >Observaciones:</strong
                                                                    >
                                                                    {{
                                                                        aptitud.observaciones
                                                                    }}
                                                                </p>
                                                            </v-card-text>
                                                        </v-card>
                                                    </v-timeline-item>
                                                </v-timeline>
                                            </v-card-text>
                                        </v-card>
                                    </v-tab-item>

                                    <v-tab-item>
                                        <v-card flat>
                                            <v-card-text>
                                                <v-data-table
                                                    :headers="headersLicencias"
                                                    :items="licenciasMostrar"
                                                    :items-per-page="5"
                                                    class="elevation-1"
                                                >
                                                    <template
                                                        v-slot:item.estado="{
                                                            item,
                                                        }"
                                                    >
                                                        <v-chip
                                                            :color="
                                                                getLicenciaColor(
                                                                    item.estado,
                                                                )
                                                            "
                                                            text-color="white"
                                                            small
                                                        >
                                                            {{ item.estado }}
                                                        </v-chip>
                                                    </template>
                                                    <template
                                                        v-slot:item.dias="{
                                                            item,
                                                        }"
                                                    >
                                                        <span
                                                            class="font-weight-bold"
                                                            >{{
                                                                item.dias
                                                            }}</span
                                                        >
                                                    </template>
                                                </v-data-table>
                                            </v-card-text>
                                        </v-card>
                                    </v-tab-item>
                                </v-tabs>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>

                <!-- Diálogo para mostrar detalles de exámenes -->
                <v-dialog
                    v-model="dialogDetalle"
                    max-width="700"
                >
                    <v-card>
                        <v-card-title
                            class="headline teal darken-1 white--text"
                        >
                            Detalle del Examen Médico
                            <v-spacer></v-spacer>
                            <v-btn
                                icon
                                dark
                                @click="dialogDetalle = false"
                            >
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                        </v-card-title>
                        <v-card-text class="mt-4">
                            <v-row v-if="examenSeleccionado">
                                <v-col cols="6">
                                    <p>
                                        <strong>Fecha:</strong>
                                        {{ examenSeleccionado.fecha }}
                                    </p>
                                    <p>
                                        <strong>Tipo:</strong>
                                        {{ examenSeleccionado.tipo }}
                                    </p>
                                    <p>
                                        <strong>Profesional:</strong>
                                        {{ examenSeleccionado.profesional }}
                                    </p>
                                    <p>
                                        <strong>Estado:</strong>
                                        {{ examenSeleccionado.estado }}
                                    </p>
                                </v-col>
                                <v-col cols="6">
                                    <p>
                                        <strong>Presión Arterial:</strong>
                                        {{ examenSeleccionado.presion }}
                                    </p>
                                    <p>
                                        <strong>Frecuencia Cardíaca:</strong>
                                        {{ examenSeleccionado.frecuencia }}
                                    </p>
                                    <p>
                                        <strong>Peso:</strong>
                                        {{ examenSeleccionado.peso }} kg
                                    </p>
                                    <p>
                                        <strong>Talla:</strong>
                                        {{ examenSeleccionado.talla }} cm
                                    </p>
                                </v-col>
                                <v-col cols="12">
                                    <v-divider class="my-3"></v-divider>
                                    <p><strong>Diagnóstico:</strong></p>
                                    <p>{{ examenSeleccionado.diagnostico }}</p>
                                    <v-divider class="my-3"></v-divider>
                                    <p><strong>Recomendaciones:</strong></p>
                                    <p>
                                        {{ examenSeleccionado.recomendaciones }}
                                    </p>
                                </v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="primary"
                                text
                                @click="dialogDetalle = false"
                            >
                                Cerrar
                            </v-btn>
                            <v-btn
                                color="teal darken-1"
                                dark
                                @click="imprimirExamen(examenSeleccionado)"
                            >
                                Imprimir
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <!-- Diálogo para mostrar detalles de consultas -->
                <v-dialog
                    v-model="dialogConsulta"
                    max-width="700"
                >
                    <v-card>
                        <v-card-title
                            class="headline teal darken-1 white--text"
                        >
                            Detalle de la Consulta Médica
                            <v-spacer></v-spacer>
                            <v-btn
                                icon
                                dark
                                @click="dialogConsulta = false"
                            >
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                        </v-card-title>
                        <v-card-text class="mt-4">
                            <v-row v-if="consultaSeleccionada">
                                <v-col cols="6">
                                    <p>
                                        <strong>Fecha:</strong>
                                        {{ consultaSeleccionada.fecha }}
                                    </p>
                                    <p>
                                        <strong>Profesional:</strong>
                                        {{ consultaSeleccionada.profesional }}
                                    </p>
                                    <p>
                                        <strong>Especialidad:</strong>
                                        {{ consultaSeleccionada.especialidad }}
                                    </p>
                                </v-col>
                                <v-col cols="6">
                                    <p>
                                        <strong>Motivo:</strong>
                                        {{ consultaSeleccionada.motivo }}
                                    </p>
                                    <p>
                                        <strong>Síntomas:</strong>
                                        {{ consultaSeleccionada.sintomas }}
                                    </p>
                                </v-col>
                                <v-col cols="12">
                                    <v-divider class="my-3"></v-divider>
                                    <p><strong>Diagnóstico:</strong></p>
                                    <p>
                                        {{ consultaSeleccionada.diagnostico }}
                                    </p>
                                    <v-divider class="my-3"></v-divider>
                                    <p><strong>Tratamiento:</strong></p>
                                    <p>
                                        {{ consultaSeleccionada.tratamiento }}
                                    </p>
                                </v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="primary"
                                text
                                @click="dialogConsulta = false"
                            >
                                Cerrar
                            </v-btn>
                            <v-btn
                                color="teal darken-1"
                                dark
                                @click="imprimirConsulta(consultaSeleccionada)"
                            >
                                Imprimir
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </div>
        </v-container>
    </div>
</template>

<script>
import TarjetaAgente from '../utils/TarjetaAgente.vue';
import { mapState, mapGetters, mapActions } from 'vuex';

export default {
    name: 'HistoriaClinica',
    components: {
        TarjetaAgente,
    },
    data: () => ({
        mostrarHistoria: false,
        hayError: false,
        tabHistoria: null,
        dialogDetalle: false,
        dialogConsulta: false,
        examenSeleccionado: null,
        consultaSeleccionada: null,

        // Headers para las tablas
        headersExamenes: [
            { text: 'Fecha', value: 'fecha', sortable: true },
            { text: 'Tipo de Examen', value: 'tipo', sortable: true },
            { text: 'Profesional', value: 'profesional', sortable: true },
            { text: 'Estado', value: 'estado', sortable: true },
            { text: 'Acciones', value: 'acciones', sortable: false },
        ],

        headersConsultas: [
            { text: 'Fecha', value: 'fecha', sortable: true },
            { text: 'Motivo', value: 'motivo', sortable: true },
            { text: 'Profesional', value: 'profesional', sortable: true },
            { text: 'Especialidad', value: 'especialidad', sortable: true },
            { text: 'Acciones', value: 'acciones', sortable: false },
        ],

        headersLicencias: [
            { text: 'Fecha Inicio', value: 'fechaInicio', sortable: true },
            { text: 'Fecha Fin', value: 'fechaFin', sortable: true },
            { text: 'Días', value: 'dias', sortable: true },
            { text: 'Tipo', value: 'tipo', sortable: true },
            { text: 'Diagnóstico', value: 'diagnostico', sortable: true },
            { text: 'Estado', value: 'estado', sortable: true },
        ],

        // Datos mockeados
        examenesData: [
            {
                id: 1,
                fecha: '15/03/2023',
                tipo: 'Examen Preocupacional',
                profesional: 'Dr. Martínez, Carlos',
                estado: 'Completado',
                presion: '120/80 mmHg',
                frecuencia: '75 lpm',
                peso: '78',
                talla: '175',
                diagnostico:
                    'Paciente en buen estado de salud. Apto para el puesto solicitado sin restricciones. Laboratorio dentro de parámetros normales.',
                recomendaciones:
                    'Se recomienda control anual de rutina. Mantener hábitos saludables y actividad física regular.',
            },
            {
                id: 2,
                fecha: '10/04/2023',
                tipo: 'Control Periódico',
                profesional: 'Dra. González, María',
                estado: 'Completado',
                presion: '130/85 mmHg',
                frecuencia: '80 lpm',
                peso: '80',
                talla: '175',
                diagnostico:
                    'Leve aumento de presión arterial. Resto de parámetros normales. Requiere seguimiento.',
                recomendaciones:
                    'Control de presión arterial semanal. Reducir consumo de sodio. Aumentar actividad física aeróbica.',
            },
            {
                id: 3,
                fecha: '22/06/2023',
                tipo: 'Examen por Cambio de Puesto',
                profesional: 'Dr. Sánchez, Javier',
                estado: 'Pendiente',
                presion: '125/80 mmHg',
                frecuencia: '72 lpm',
                peso: '79',
                talla: '175',
                diagnostico:
                    'Pendiente de resultados de laboratorio y audiometría.',
                recomendaciones:
                    'Completar estudios faltantes en un plazo de 15 días.',
            },
            {
                id: 4,
                fecha: '05/09/2023',
                tipo: 'Evaluación de Aptitud',
                profesional: 'Dra. Fernández, Laura',
                estado: 'Completado',
                presion: '120/75 mmHg',
                frecuencia: '68 lpm',
                peso: '77',
                talla: '175',
                diagnostico:
                    'Agente en buen estado de salud. Apto para las tareas asignadas.',
                recomendaciones:
                    'No requiere intervención adicional. Próximo control en 12 meses.',
            },
            {
                id: 5,
                fecha: '18/12/2023',
                tipo: 'Control Post-licencia',
                profesional: 'Dr. Rodríguez, Eduardo',
                estado: 'Completado',
                presion: '115/75 mmHg',
                frecuencia: '70 lpm',
                peso: '76',
                talla: '175',
                diagnostico:
                    'Recuperación completa tras licencia médica. Sin secuelas evidentes.',
                recomendaciones:
                    'Reincorporación gradual a tareas habituales. Control en 3 meses.',
            },
        ],

        consultasData: [
            {
                id: 1,
                fecha: '10/02/2023',
                motivo: 'Dolor lumbar',
                profesional: 'Dr. López, Miguel',
                especialidad: 'Traumatología',
                sintomas:
                    'Dolor lumbar irradiado a miembro inferior derecho de 3 días de evolución.',
                diagnostico:
                    'Lumbalgia aguda con componente ciático. Posible hernia discal a confirmar.',
                tratamiento:
                    'Reposo relativo 72hs. Diclofenac 75mg/12hs. Miorrelajantes. Solicitud de RMN lumbar.',
            },
            {
                id: 2,
                fecha: '15/05/2023',
                motivo: 'Control de hipertensión',
                profesional: 'Dra. Ramírez, Ana',
                especialidad: 'Cardiología',
                sintomas: 'Asintomático. Consulta de control programada.',
                diagnostico:
                    'Hipertensión arterial controlada. Valores actuales 130/85 mmHg.',
                tratamiento:
                    'Continuar con Enalapril 10mg/día. Control en 3 meses con laboratorio previo.',
            },
            {
                id: 3,
                fecha: '27/07/2023',
                motivo: 'Cefalea intensa',
                profesional: 'Dr. Gutiérrez, Pablo',
                especialidad: 'Neurología',
                sintomas:
                    'Cefalea pulsátil hemicraneal izquierda con fotofobia de 48hs de evolución.',
                diagnostico:
                    'Migraña sin aura. Posible componente tensional asociado a estrés laboral.',
                tratamiento:
                    'Sumatriptán 50mg al inicio de la crisis. Técnicas de relajación. Reducir factores desencadenantes.',
            },
            {
                id: 4,
                fecha: '14/10/2023',
                motivo: 'Fatiga y malestar general',
                profesional: 'Dra. Álvarez, Sofía',
                especialidad: 'Clínica Médica',
                sintomas:
                    'Astenia, adinamia, somnolencia diurna y dificultad para concentrarse de 3 semanas de evolución.',
                diagnostico:
                    'Síndrome de fatiga crónica. Descartar hipotiroidismo y anemia ferropénica.',
                tratamiento:
                    'Solicitud de perfil tiroideo, hemograma completo y ferritina. Control con resultados en 2 semanas.',
            },
        ],

        aptitudData: [
            {
                id: 1,
                fecha: '15/03/2023',
                tipo: 'Evaluación inicial',
                resultado: 'Apto sin restricciones',
                profesional: 'Dr. Martínez, Carlos',
                observaciones:
                    'El agente cumple con todos los requisitos físicos y psicológicos para el puesto solicitado.',
            },
            {
                id: 2,
                fecha: '10/04/2023',
                tipo: 'Reevaluación',
                resultado: 'Apto con restricciones',
                profesional: 'Dra. González, María',
                observaciones:
                    'Se recomienda evitar turnos nocturnos por hipertensión arterial en estudio.',
            },
            {
                id: 3,
                fecha: '22/06/2023',
                tipo: 'Cambio de funciones',
                resultado: 'Apto con restricciones',
                profesional: 'Dr. Sánchez, Javier',
                observaciones:
                    'Apto para funciones administrativas. Restricción para tareas con esfuerzo físico intenso o levantamiento de cargas mayores a 15kg.',
            },
            {
                id: 4,
                fecha: '05/09/2023',
                tipo: 'Control periódico',
                resultado: 'Apto sin restricciones',
                profesional: 'Dra. Fernández, Laura',
                observaciones:
                    'Mejoría significativa. Se levantan restricciones previas.',
            },
        ],

        licenciasData: [
            {
                id: 1,
                fechaInicio: '12/02/2023',
                fechaFin: '19/02/2023',
                dias: 8,
                tipo: 'Enfermedad Corta',
                diagnostico: 'Lumbalgia aguda',
                estado: 'Completada',
            },
            {
                id: 2,
                fechaInicio: '05/06/2023',
                fechaFin: '09/06/2023',
                dias: 5,
                tipo: 'Enfermedad Corta',
                diagnostico: 'Síndrome gripal',
                estado: 'Completada',
            },
            {
                id: 3,
                fechaInicio: '28/07/2023',
                fechaFin: '28/08/2023',
                dias: 30,
                tipo: 'Enfermedad Prolongada',
                diagnostico: 'Intervención quirúrgica - Colecistectomía',
                estado: 'Completada',
            },
            {
                id: 4,
                fechaInicio: '15/11/2023',
                fechaFin: '16/11/2023',
                dias: 2,
                tipo: 'Estudios Médicos',
                diagnostico: 'Estudios diagnósticos cardiológicos',
                estado: 'Completada',
            },
            {
                id: 5,
                fechaInicio: '01/02/2024',
                fechaFin: '15/02/2024',
                dias: 15,
                tipo: 'Enfermedad Media',
                diagnostico: 'Esguince de tobillo',
                estado: 'En curso',
            },
        ],
    }),
    computed: {
        ...mapState('historiaClinica', ['loading', 'error']),
        ...mapGetters('historiaClinica', [
            'getExamenes',
            'getConsultas',
            'getAptitud',
            'getLicencias',
            'getDetalleExamen',
            'getDetalleConsulta',
        ]),
        ...mapGetters('agente', ['agente']),

        // Getter para ver si debemos usar datos de la API o datos mockeados
        examenesMostrar() {
            // Si hay datos en la store, usarlos. De lo contrario, usar datos mockeados
            return this.getExamenes.length > 0
                ? this.getExamenes
                : this.examenesData;
        },
        consultasMostrar() {
            return this.getConsultas.length > 0
                ? this.getConsultas
                : this.consultasData;
        },
        aptitudMostrar() {
            return this.getAptitud.length > 0
                ? this.getAptitud
                : this.aptitudData;
        },
        licenciasMostrar() {
            return this.getLicencias.length > 0
                ? this.getLicencias
                : this.licenciasData;
        },
    },
    methods: {
        ...mapActions('historiaClinica', [
            'loadHistoriaClinica',
            'loadDetalleExamen',
            'loadDetalleConsulta',
        ]),
        async agenteEncontrado(error) {
            this.hayError = error;
            this.mostrarHistoria = !error;

            if (!error) {
                console.log('Agente encontrado, cargando historia clínica...');
                // En un entorno real, cargaríamos la historia clínica desde la API
                // Por ahora, comentamos esta línea para seguir usando datos mockeados
                // await this.cargarHistoriaClinica();
            }
        },
        // Método para cargar datos desde la API
        async cargarHistoriaClinica() {
            try {
                if (this.agente && this.agente.idagente) {
                    await this.loadHistoriaClinica(this.agente.idagente);
                }
            } catch (error) {
                console.error('Error al cargar historia clínica:', error);
                // Mostrar algún mensaje de error al usuario
            }
        },
        async verDetalle(item) {
            this.examenSeleccionado = item;

            // En una implementación real, cargaríamos el detalle desde la API
            // try {
            //   await this.loadDetalleExamen(item.id);
            //   this.examenSeleccionado = this.getDetalleExamen;
            // } catch (error) {
            //   console.error('Error al cargar detalle del examen:', error);
            // }

            this.dialogDetalle = true;
        },
        async verDetalleConsulta(item) {
            this.consultaSeleccionada = item;

            // En una implementación real, cargaríamos el detalle desde la API
            // try {
            //   await this.loadDetalleConsulta(item.id);
            //   this.consultaSeleccionada = this.getDetalleConsulta;
            // } catch (error) {
            //   console.error('Error al cargar detalle de la consulta:', error);
            // }

            this.dialogConsulta = true;
        },
        getChipColor(estado) {
            switch (estado) {
                case 'Completado':
                    return 'success';
                case 'Pendiente':
                    return 'warning';
                case 'Cancelado':
                    return 'error';
                default:
                    return 'primary';
            }
        },
        getLicenciaColor(estado) {
            switch (estado) {
                case 'Completada':
                    return 'success';
                case 'En curso':
                    return 'info';
                case 'Rechazada':
                    return 'error';
                default:
                    return 'primary';
            }
        },
        getAptitudColor(resultado) {
            switch (resultado) {
                case 'Apto sin restricciones':
                    return 'success';
                case 'Apto con restricciones':
                    return 'amber darken-2';
                case 'No apto':
                    return 'error';
                default:
                    return 'primary';
            }
        },
        getAptitudTextColor(resultado) {
            switch (resultado) {
                case 'Apto sin restricciones':
                    return 'success--text';
                case 'Apto con restricciones':
                    return 'amber--text text--darken-2';
                case 'No apto':
                    return 'error--text';
                default:
                    return 'primary--text';
            }
        },
        // Métodos para ejecutar cuando el componente esté conectado a una API real
        exportarHistoriaClinica() {
            console.log('Exportando historia clínica a PDF...');
            // Implementar lógica para exportar a PDF
        },
        imprimirExamen(examen) {
            if (!examen) return;

            // Crear una ventana de impresión
            const printWindow = window.open('', '_blank');

            // Definir el contenido HTML para imprimir
            const contenido = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Examen Médico - ${examen.tipo}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 20px; }
                        .header h1 { margin-bottom: 5px; }
                        .section { margin-bottom: 20px; }
                        .section h2 { color: #2196F3; }
                        .divider { border-top: 1px solid #ccc; margin: 15px 0; }
                        .row { display: flex; flex-wrap: wrap; }
                        .col { flex: 1; padding: 0 10px; }
                        .field { margin-bottom: 10px; }
                        .field strong { font-weight: bold; }
                        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #666; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>EXAMEN MÉDICO</h1>
                        <p>Fecha: ${examen.fecha}</p>
                    </div>
                    
                    <div class="section">
                        <h2>Información General</h2>
                        <div class="row">
                            <div class="col">
                                <div class="field"><strong>Tipo de Examen:</strong> ${
                                    examen.tipo
                                }</div>
                                <div class="field"><strong>Profesional:</strong> ${
                                    examen.profesional
                                }</div>
                                <div class="field"><strong>Estado:</strong> ${
                                    examen.estado
                                }</div>
                            </div>
                            <div class="col">
                                <div class="field"><strong>Presión Arterial:</strong> ${
                                    examen.presion
                                }</div>
                                <div class="field"><strong>Frecuencia Cardíaca:</strong> ${
                                    examen.frecuencia
                                }</div>
                                <div class="field"><strong>Peso:</strong> ${
                                    examen.peso
                                } kg</div>
                                <div class="field"><strong>Talla:</strong> ${
                                    examen.talla
                                } cm</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="divider"></div>
                    
                    <div class="section">
                        <h2>Diagnóstico</h2>
                        <p>${examen.diagnostico}</p>
                    </div>
                    
                    <div class="divider"></div>
                    
                    <div class="section">
                        <h2>Recomendaciones</h2>
                        <p>${examen.recomendaciones}</p>
                    </div>
                    
                    <div class="footer">
                        <p>Este documento es confidencial y solo para uso médico-administrativo</p>
                        <p>Impreso el ${new Date().toLocaleDateString()} a las ${new Date().toLocaleTimeString()}</p>
                    </div>
                </body>
                </html>
            `;

            // Escribir en la ventana y ejecutar la impresión
            printWindow.document.write(contenido);
            printWindow.document.close();

            // Esperar a que los estilos se carguen
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        },
        imprimirConsulta(consulta) {
            if (!consulta) return;

            // Crear una ventana de impresión
            const printWindow = window.open('', '_blank');

            // Definir el contenido HTML para imprimir
            const contenido = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Consulta Médica - ${consulta.motivo}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 20px; }
                        .header h1 { margin-bottom: 5px; }
                        .section { margin-bottom: 20px; }
                        .section h2 { color: #2196F3; }
                        .divider { border-top: 1px solid #ccc; margin: 15px 0; }
                        .row { display: flex; flex-wrap: wrap; }
                        .col { flex: 1; padding: 0 10px; }
                        .field { margin-bottom: 10px; }
                        .field strong { font-weight: bold; }
                        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #666; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>CONSULTA MÉDICA</h1>
                        <p>Fecha: ${consulta.fecha}</p>
                    </div>
                    
                    <div class="section">
                        <h2>Información de la Consulta</h2>
                        <div class="row">
                            <div class="col">
                                <div class="field"><strong>Profesional:</strong> ${
                                    consulta.profesional
                                }</div>
                                <div class="field"><strong>Especialidad:</strong> ${
                                    consulta.especialidad
                                }</div>
                            </div>
                            <div class="col">
                                <div class="field"><strong>Motivo:</strong> ${
                                    consulta.motivo
                                }</div>
                                <div class="field"><strong>Síntomas:</strong> ${
                                    consulta.sintomas
                                }</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="divider"></div>
                    
                    <div class="section">
                        <h2>Diagnóstico</h2>
                        <p>${consulta.diagnostico}</p>
                    </div>
                    
                    <div class="divider"></div>
                    
                    <div class="section">
                        <h2>Tratamiento</h2>
                        <p>${consulta.tratamiento}</p>
                    </div>
                    
                    <div class="footer">
                        <p>Este documento es confidencial y solo para uso médico-administrativo</p>
                        <p>Impreso el ${new Date().toLocaleDateString()} a las ${new Date().toLocaleTimeString()}</p>
                    </div>
                </body>
                </html>
            `;

            // Escribir en la ventana y ejecutar la impresión
            printWindow.document.write(contenido);
            printWindow.document.close();

            // Esperar a que los estilos se carguen
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        },
    },
};
</script>

<style scoped>
.v-timeline-item__body {
    max-width: 100%;
}
</style>
