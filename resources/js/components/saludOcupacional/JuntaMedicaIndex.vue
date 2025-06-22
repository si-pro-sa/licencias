<template>
    <div>
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title
                            class="headline primary white--text d-flex align-center"
                        >
                            <v-icon
                                large
                                dark
                                left
                                >mdi-account-group</v-icon
                            >
                            Juntas Médicas
                            <v-spacer></v-spacer>
                            <v-btn
                                color="white"
                                outlined
                                @click="mostrarFormulario()"
                            >
                                <v-icon left>mdi-plus</v-icon>
                                Nueva Junta Médica
                            </v-btn>
                        </v-card-title>

                        <v-card-text>
                            <!-- Panel de estadísticas -->
                            <v-row class="mb-4">
                                <v-col cols="12">
                                    <h2 class="text-h5 mb-2">
                                        Resumen de Juntas Médicas
                                    </h2>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="4"
                                    sm="6"
                                >
                                    <v-card
                                        outlined
                                        class="text-center"
                                    >
                                        <v-card-text>
                                            <v-avatar
                                                size="48"
                                                color="primary"
                                                class="mb-2"
                                            >
                                                <v-icon dark
                                                    >mdi-calendar-clock</v-icon
                                                >
                                            </v-avatar>
                                            <div class="text-h3 primary--text">
                                                {{ juntasPendientes.length }}
                                            </div>
                                            <div class="text-subtitle-1">
                                                Pendientes
                                            </div>
                                            <div class="text-caption">
                                                {{ getPendientesPercent }}% del
                                                total
                                            </div>
                                        </v-card-text>
                                        <v-card-actions class="justify-center">
                                            <v-btn
                                                text
                                                color="primary"
                                                @click="activeTab = 0"
                                            >
                                                <v-icon left
                                                    >mdi-calendar-clock</v-icon
                                                >
                                                Ver juntas pendientes
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="4"
                                    sm="6"
                                >
                                    <v-card
                                        outlined
                                        class="text-center"
                                    >
                                        <v-card-text>
                                            <v-avatar
                                                size="48"
                                                color="blue"
                                                class="mb-2"
                                            >
                                                <v-icon dark
                                                    >mdi-calendar-check</v-icon
                                                >
                                            </v-avatar>
                                            <div class="text-h3 blue--text">
                                                {{ juntasEnProgreso.length }}
                                            </div>
                                            <div class="text-subtitle-1">
                                                En Progreso
                                            </div>
                                            <div class="text-caption">
                                                {{ getEnProgresoPercent }}% del
                                                total
                                            </div>
                                        </v-card-text>
                                        <v-card-actions class="justify-center">
                                            <v-btn
                                                text
                                                color="blue"
                                                @click="activeTab = 1"
                                            >
                                                <v-icon left
                                                    >mdi-calendar-check</v-icon
                                                >
                                                Ver juntas en progreso
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="4"
                                    sm="6"
                                >
                                    <v-card
                                        outlined
                                        class="text-center"
                                    >
                                        <v-card-text>
                                            <v-avatar
                                                size="48"
                                                color="success"
                                                class="mb-2"
                                            >
                                                <v-icon dark
                                                    >mdi-check-circle</v-icon
                                                >
                                            </v-avatar>
                                            <div class="text-h3 success--text">
                                                {{ juntasCompletadas.length }}
                                            </div>
                                            <div class="text-subtitle-1">
                                                Completadas
                                            </div>
                                            <div class="text-caption">
                                                {{ getCompletadasPercent }}% del
                                                total
                                            </div>
                                        </v-card-text>
                                        <v-card-actions class="justify-center">
                                            <v-btn
                                                text
                                                color="success"
                                                @click="activeTab = 2"
                                            >
                                                <v-icon left
                                                    >mdi-calendar-check</v-icon
                                                >
                                                Ver juntas completadas
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="4"
                                    sm="6"
                                >
                                    <v-card
                                        outlined
                                        class="text-center"
                                    >
                                        <v-card-text>
                                            <v-avatar
                                                size="48"
                                                color="error"
                                                class="mb-2"
                                            >
                                                <v-icon dark
                                                    >mdi-calendar-remove</v-icon
                                                >
                                            </v-avatar>
                                            <div class="text-h3 error--text">
                                                {{ juntasCanceladas.length }}
                                            </div>
                                            <div class="text-subtitle-1">
                                                Canceladas
                                            </div>
                                            <div class="text-caption">
                                                {{ getCanceladasPercent }}% del
                                                total
                                            </div>
                                        </v-card-text>
                                        <v-card-actions class="justify-center">
                                            <v-btn
                                                text
                                                color="error"
                                                @click="activeTab = 3"
                                            >
                                                <v-icon left
                                                    >mdi-calendar-remove</v-icon
                                                >
                                                Ver juntas canceladas
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="4"
                                    sm="6"
                                >
                                    <v-card
                                        outlined
                                        class="text-center"
                                    >
                                        <v-card-text>
                                            <v-avatar
                                                size="48"
                                                color="grey darken-1"
                                                class="mb-2"
                                            >
                                                <v-icon dark
                                                    >mdi-calendar-multiple</v-icon
                                                >
                                            </v-avatar>
                                            <div class="text-h3 grey--text">
                                                {{ juntas.length }}
                                            </div>
                                            <div class="text-subtitle-1">
                                                Total de Juntas
                                            </div>
                                            <div class="text-caption">
                                                Última actualización:
                                                {{ fechaActualizacion }}
                                            </div>
                                        </v-card-text>
                                        <v-card-actions class="justify-center">
                                            <v-btn
                                                text
                                                color="grey darken-1"
                                                @click="activeTab = 4"
                                            >
                                                <v-icon left
                                                    >mdi-calendar-multiple</v-icon
                                                >
                                                Ver todas las juntas
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-col>
                            </v-row>

                            <v-tabs
                                v-model="activeTab"
                                background-color="primary"
                                dark
                            >
                                <v-tab>
                                    <v-badge
                                        :content="getPendientesCount"
                                        :value="getPendientesCount > 0"
                                        color="error"
                                        offset-x="10"
                                        offset-y="-10"
                                    >
                                        Pendientes
                                    </v-badge>
                                </v-tab>
                                <v-tab>En Progreso</v-tab>
                                <v-tab>Completadas</v-tab>
                                <v-tab>Canceladas</v-tab>
                                <v-tab>Todas</v-tab>

                                <v-tab-item>
                                    <v-card flat>
                                        <v-card-text>
                                            <tablaJuntas
                                                :juntas="juntasPendientes"
                                                @editar="editarJunta"
                                                @ver="verJunta"
                                                @cancelar="cancelarJunta"
                                            />
                                        </v-card-text>
                                    </v-card>
                                </v-tab-item>

                                <v-tab-item>
                                    <v-card flat>
                                        <v-card-text>
                                            <tablaJuntas
                                                :juntas="juntasEnProgreso"
                                                @editar="editarJunta"
                                                @ver="verJunta"
                                                @cancelar="cancelarJunta"
                                            />
                                        </v-card-text>
                                    </v-card>
                                </v-tab-item>

                                <v-tab-item>
                                    <v-card flat>
                                        <v-card-text>
                                            <tablaJuntas
                                                :juntas="juntasCompletadas"
                                                @editar="editarJunta"
                                                @ver="verJunta"
                                                @cancelar="cancelarJunta"
                                            />
                                        </v-card-text>
                                    </v-card>
                                </v-tab-item>

                                <v-tab-item>
                                    <v-card flat>
                                        <v-card-text>
                                            <tablaJuntas
                                                :juntas="juntasCanceladas"
                                                @editar="editarJunta"
                                                @ver="verJunta"
                                                @cancelar="cancelarJunta"
                                            />
                                        </v-card-text>
                                    </v-card>
                                </v-tab-item>

                                <v-tab-item>
                                    <v-card flat>
                                        <v-card-text>
                                            <tablaJuntas
                                                :juntas="juntas"
                                                @editar="editarJunta"
                                                @ver="verJunta"
                                                @cancelar="cancelarJunta"
                                            />
                                        </v-card-text>
                                    </v-card>
                                </v-tab-item>
                            </v-tabs>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>

        <!-- Diálogo para formulario de Junta Médica -->
        <v-dialog
            v-model="formularioDialog"
            persistent
            max-width="900px"
        >
            <juntaMedicaForm
                :idlicencia="idLicenciaSeleccionada"
                :juntaMedicaData="juntaMedicaSeleccionada"
                @cancelar="cerrarFormulario"
                @guardado="juntaGuardada"
            />
        </v-dialog>

        <!-- Diálogo para ver detalles de Junta Médica -->
        <v-dialog
            v-model="detalleDialog"
            max-width="900px"
        >
            <v-card>
                <v-card-title class="headline primary white--text">
                    Detalle de Junta Médica
                    <v-spacer></v-spacer>
                    <v-btn
                        icon
                        dark
                        @click="detalleDialog = false"
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>

                <v-card-text
                    class="pa-4"
                    v-if="juntaMedicaSeleccionada"
                >
                    <v-row>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-list-item>
                                <v-list-item-content>
                                    <v-list-item-subtitle class="text-caption"
                                        >Nombre</v-list-item-subtitle
                                    >
                                    <v-list-item-title>{{
                                        juntaMedicaSeleccionada.nombre
                                    }}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-list-item>
                                <v-list-item-content>
                                    <v-list-item-subtitle class="text-caption"
                                        >Tipo</v-list-item-subtitle
                                    >
                                    <v-list-item-title>{{
                                        juntaMedicaSeleccionada.tipo
                                    }}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-list-item>
                                <v-list-item-content>
                                    <v-list-item-subtitle class="text-caption"
                                        >Fecha y Hora</v-list-item-subtitle
                                    >
                                    <v-list-item-title
                                        >{{ juntaMedicaSeleccionada.fecha }} -
                                        {{
                                            juntaMedicaSeleccionada.hora
                                        }}</v-list-item-title
                                    >
                                </v-list-item-content>
                            </v-list-item>
                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-list-item>
                                <v-list-item-content>
                                    <v-list-item-subtitle class="text-caption"
                                        >Estado</v-list-item-subtitle
                                    >
                                    <v-list-item-title>
                                        <v-chip
                                            :color="
                                                getEstadoColor(
                                                    juntaMedicaSeleccionada.estado,
                                                )
                                            "
                                            text-color="white"
                                            small
                                        >
                                            {{ juntaMedicaSeleccionada.estado }}
                                        </v-chip>
                                    </v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-col>
                    </v-row>

                    <v-divider class="my-3"></v-divider>

                    <h3 class="subtitle-1 mb-2">Miembros de la Junta</h3>
                    <v-simple-table>
                        <template v-slot:default>
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Rol</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(
                                        miembro, index
                                    ) in juntaMedicaSeleccionada.miembros"
                                    :key="index"
                                >
                                    <td>
                                        {{ getNombreAgente(miembro.idagente) }}
                                    </td>
                                    <td>{{ miembro.rol }}</td>
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table>

                    <v-divider class="my-3"></v-divider>

                    <h3 class="subtitle-1 mb-2">Observaciones</h3>
                    <v-card
                        outlined
                        class="pa-3"
                    >
                        <p>
                            {{
                                juntaMedicaSeleccionada.observaciones ||
                                'No hay observaciones registradas.'
                            }}
                        </p>
                    </v-card>

                    <v-divider class="my-3"></v-divider>

                    <h3 class="subtitle-1 mb-2">Información de Licencia</h3>
                    <v-card
                        outlined
                        class="pa-3"
                    >
                        <v-row>
                            <v-col
                                cols="12"
                                md="6"
                            >
                                <p><strong>Agente:</strong> Pérez, Juan</p>
                                <p>
                                    <strong>Tipo de Licencia:</strong>
                                    Enfermedad Prolongada
                                </p>
                            </v-col>
                            <v-col
                                cols="12"
                                md="6"
                            >
                                <p><strong>Fecha Inicio:</strong> 01/02/2024</p>
                                <p><strong>Fecha Fin:</strong> 15/03/2024</p>
                            </v-col>
                        </v-row>
                    </v-card>

                    <v-divider
                        class="my-3"
                        v-if="juntaMedicaSeleccionada.estado === 'Pendiente'"
                    ></v-divider>

                    <div
                        v-if="juntaMedicaSeleccionada.estado === 'Pendiente'"
                        class="mt-3"
                    >
                        <v-btn
                            color="success"
                            class="mr-2"
                            @click="iniciarJunta(juntaMedicaSeleccionada.id)"
                        >
                            <v-icon left>mdi-play</v-icon>
                            Iniciar Junta
                        </v-btn>
                        <v-btn
                            color="error"
                            text
                            @click="cancelarJunta(juntaMedicaSeleccionada.id)"
                        >
                            <v-icon left>mdi-cancel</v-icon>
                            Cancelar Junta
                        </v-btn>
                    </div>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="primary"
                        text
                        @click="detalleDialog = false"
                    >
                        Cerrar
                    </v-btn>
                    <v-btn
                        color="primary"
                        @click="evaluarLicencia(juntaMedicaSeleccionada.id)"
                        v-if="
                            juntaMedicaSeleccionada &&
                            juntaMedicaSeleccionada.estado === 'En Progreso'
                        "
                    >
                        <v-icon left>mdi-clipboard-check</v-icon>
                        Completar Evaluación
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import JuntaMedicaForm from './JuntaMedicaForm.vue';

// Componente interno para tabla de juntas médicas
const TablaJuntas = {
    props: {
        juntas: {
            type: Array,
            default: () => [],
        },
    },
    template: `
        <v-data-table
            :headers="headers"
            :items="juntas"
            :items-per-page="10"
            class="elevation-1"
        >
            <template v-slot:item.fecha="{ item }">
                {{ item.fecha }} {{ item.hora }}
            </template>
            <template v-slot:item.estado="{ item }">
                <v-chip
                    :color="getChipColor(item.estado)"
                    text-color="white"
                    small
                >
                    {{ item.estado }}
                </v-chip>
            </template>
            <template v-slot:item.miembros="{ item }">
                {{ item.miembros.length }} miembros
            </template>
            <template v-slot:item.acciones="{ item }">
                <v-btn small icon color="primary" @click="$emit('ver', item.id)">
                    <v-icon small>mdi-eye</v-icon>
                </v-btn>
                <v-btn small icon color="success" @click="$emit('editar', item.id)" :disabled="item.estado !== 'Pendiente'">
                    <v-icon small>mdi-pencil</v-icon>
                </v-btn>
                <v-btn small icon color="error" @click="$emit('cancelar', item.id)" :disabled="item.estado !== 'Pendiente'">
                    <v-icon small>mdi-cancel</v-icon>
                </v-btn>
            </template>
        </v-data-table>
    `,
    data() {
        return {
            headers: [
                { text: 'Nombre', value: 'nombre' },
                { text: 'Tipo', value: 'tipo' },
                { text: 'Fecha y Hora', value: 'fecha' },
                { text: 'Miembros', value: 'miembros' },
                { text: 'Estado', value: 'estado' },
                { text: 'Acciones', value: 'acciones', sortable: false },
            ],
        };
    },
    methods: {
        getChipColor(estado) {
            switch (estado) {
                case 'Pendiente':
                    return 'warning';
                case 'En Progreso':
                    return 'primary';
                case 'Completada':
                    return 'success';
                case 'Cancelada':
                    return 'error';
                default:
                    return 'grey';
            }
        },
    },
};

export default {
    name: 'JuntaMedicaIndex',
    components: {
        JuntaMedicaForm,
        tablaJuntas: TablaJuntas,
    },
    data() {
        return {
            activeTab: 0,
            formularioDialog: false,
            detalleDialog: false,
            idLicenciaSeleccionada: null,
            juntaMedicaSeleccionada: null,

            // Datos mockeados para desarrollo
            juntas: [
                {
                    id: 1,
                    nombre: 'Junta de Evaluación Inicial - Juan Pérez',
                    tipo: 'Evaluación Inicial',
                    fecha: '10/03/2024',
                    hora: '09:30',
                    idlicencia: 123,
                    miembros: [
                        { idagente: 1, rol: 'Presidente' },
                        { idagente: 2, rol: 'Secretario' },
                        { idagente: 3, rol: 'Vocal' },
                    ],
                    observaciones:
                        'Evaluación inicial para determinar la aptitud del agente para retornar a sus funciones.',
                    estado: 'Pendiente',
                },
                {
                    id: 2,
                    nombre: 'Junta de Revisión - María López',
                    tipo: 'Revisión',
                    fecha: '05/03/2024',
                    hora: '11:00',
                    idlicencia: 124,
                    miembros: [
                        { idagente: 2, rol: 'Presidente' },
                        { idagente: 3, rol: 'Secretario' },
                        { idagente: 4, rol: 'Vocal' },
                        { idagente: 5, rol: 'Especialista' },
                    ],
                    observaciones:
                        'Revisión del estado de la licencia médica por enfermedad prolongada.',
                    estado: 'En Progreso',
                },
                {
                    id: 3,
                    nombre: 'Junta de Alta - Carlos Rodríguez',
                    tipo: 'Alta',
                    fecha: '01/03/2024',
                    hora: '10:15',
                    idlicencia: 125,
                    miembros: [
                        { idagente: 1, rol: 'Presidente' },
                        { idagente: 4, rol: 'Secretario' },
                        { idagente: 5, rol: 'Vocal' },
                    ],
                    observaciones:
                        'Evaluación final para determinar el alta médica del agente.',
                    estado: 'Completada',
                },
                {
                    id: 4,
                    nombre: 'Junta de Prórroga - Ana Martínez',
                    tipo: 'Prórroga',
                    fecha: '15/03/2024',
                    hora: '14:00',
                    idlicencia: 126,
                    miembros: [
                        { idagente: 3, rol: 'Presidente' },
                        { idagente: 5, rol: 'Secretario' },
                        { idagente: 6, rol: 'Vocal' },
                        { idagente: 7, rol: 'Especialista' },
                        { idagente: 1, rol: 'Asesor' },
                    ],
                    observaciones:
                        'Evaluación para determinar la extensión de la licencia médica actual.',
                    estado: 'Pendiente',
                },
                {
                    id: 5,
                    nombre: 'Junta de Evaluación - Roberto Gómez',
                    tipo: 'Evaluación Inicial',
                    fecha: '28/02/2024',
                    hora: '09:00',
                    idlicencia: 127,
                    miembros: [
                        { idagente: 2, rol: 'Presidente' },
                        { idagente: 4, rol: 'Secretario' },
                        { idagente: 6, rol: 'Vocal' },
                    ],
                    observaciones: 'Evaluación cancelada a pedido del agente.',
                    estado: 'Cancelada',
                },
            ],

            agentesDisponibles: [
                { idagente: 1, nombre: 'Dr. Martínez, Carlos' },
                { idagente: 2, nombre: 'Dra. González, María' },
                { idagente: 3, nombre: 'Dr. Sánchez, Javier' },
                { idagente: 4, nombre: 'Dra. Fernández, Laura' },
                { idagente: 5, nombre: 'Dr. Rodríguez, Eduardo' },
                { idagente: 6, nombre: 'Dra. López, Ana' },
                { idagente: 7, nombre: 'Dr. García, Pablo' },
            ],
        };
    },
    computed: {
        // Filtrar juntas por estado
        juntasPendientes() {
            return this.juntas.filter((junta) => junta.estado === 'Pendiente');
        },
        juntasEnProgreso() {
            return this.juntas.filter(
                (junta) => junta.estado === 'En Progreso',
            );
        },
        juntasCompletadas() {
            return this.juntas.filter((junta) => junta.estado === 'Completada');
        },
        juntasCanceladas() {
            return this.juntas.filter((junta) => junta.estado === 'Cancelada');
        },
        getPendientesCount() {
            return this.juntasPendientes.length;
        },
        getPendientesPercent() {
            return this.juntas.length
                ? (
                      (this.juntasPendientes.length / this.juntas.length) *
                      100
                  ).toFixed(1)
                : 0;
        },
        getEnProgresoPercent() {
            return this.juntas.length
                ? (
                      (this.juntasEnProgreso.length / this.juntas.length) *
                      100
                  ).toFixed(1)
                : 0;
        },
        getCompletadasPercent() {
            return this.juntas.length
                ? (
                      (this.juntasCompletadas.length / this.juntas.length) *
                      100
                  ).toFixed(1)
                : 0;
        },
        getCanceladasPercent() {
            return this.juntas.length
                ? (
                      (this.juntasCanceladas.length / this.juntas.length) *
                      100
                  ).toFixed(1)
                : 0;
        },
        fechaActualizacion() {
            return new Date().toLocaleDateString();
        },
    },
    methods: {
        // Mostrar formulario de creación/edición
        mostrarFormulario(idLicencia = null) {
            this.idLicenciaSeleccionada = idLicencia;
            this.juntaMedicaSeleccionada = null;
            this.formularioDialog = true;
        },

        // Cerrar formulario
        cerrarFormulario() {
            this.formularioDialog = false;
            this.idLicenciaSeleccionada = null;
            this.juntaMedicaSeleccionada = null;
        },

        // Editar junta médica
        editarJunta(id) {
            const junta = this.juntas.find((j) => j.id === id);
            if (junta) {
                this.juntaMedicaSeleccionada = { ...junta };
                this.formularioDialog = true;
            }
        },

        // Ver detalles de junta médica
        verJunta(id) {
            const junta = this.juntas.find((j) => j.id === id);
            if (junta) {
                this.juntaMedicaSeleccionada = { ...junta };
                this.detalleDialog = true;
            }
        },

        // Cancelar junta médica
        cancelarJunta(id) {
            // En un entorno real, se enviaría la solicitud al backend
            const index = this.juntas.findIndex((j) => j.id === id);
            if (index !== -1) {
                // Actualizar estado localmente para demo
                this.juntas[index].estado = 'Cancelada';

                // Mostrar mensaje de éxito
                this.$emit('message', {
                    text: 'Junta médica cancelada correctamente',
                    color: 'success',
                });

                // Cerrar diálogo de detalles si está abierto
                if (this.detalleDialog) {
                    this.detalleDialog = false;
                }
            }
        },

        // Iniciar junta médica
        iniciarJunta(id) {
            // En un entorno real, se enviaría la solicitud al backend
            const index = this.juntas.findIndex((j) => j.id === id);
            if (index !== -1) {
                // Actualizar estado localmente para demo
                this.juntas[index].estado = 'En Progreso';
                this.juntaMedicaSeleccionada = { ...this.juntas[index] };

                // Mostrar mensaje de éxito
                this.$emit('message', {
                    text: 'Junta médica iniciada correctamente',
                    color: 'success',
                });
            }
        },

        // Completar evaluación de junta médica
        evaluarLicencia(id) {
            // Redireccionar a la página de evaluación
            this.$router.push(
                `/salud-ocupacional/junta-medica/${id}/evaluacion`,
            );
        },

        // Guardar junta médica (nueva o editada)
        juntaGuardada(juntaMedica) {
            // En un entorno real, la respuesta vendría del backend
            if (juntaMedica.id) {
                // Actualizar junta existente
                const index = this.juntas.findIndex(
                    (j) => j.id === juntaMedica.id,
                );
                if (index !== -1) {
                    this.juntas.splice(index, 1, juntaMedica);
                }
            } else {
                // Crear nueva junta
                const newId = Math.max(...this.juntas.map((j) => j.id), 0) + 1;
                this.juntas.push({
                    ...juntaMedica,
                    id: newId,
                });
            }

            // Cerrar formulario
            this.cerrarFormulario();

            // Mostrar mensaje de éxito
            this.$emit('message', {
                text: `Junta médica ${
                    juntaMedica.id ? 'actualizada' : 'creada'
                } correctamente`,
                color: 'success',
            });
        },

        // Obtener color de estado
        getEstadoColor(estado) {
            switch (estado) {
                case 'Pendiente':
                    return 'warning';
                case 'En Progreso':
                    return 'primary';
                case 'Completada':
                    return 'success';
                case 'Cancelada':
                    return 'error';
                default:
                    return 'grey';
            }
        },

        // Obtener nombre de agente por ID
        getNombreAgente(idagente) {
            const agente = this.agentesDisponibles.find(
                (a) => a.idagente === idagente,
            );
            return agente ? agente.nombre : 'Desconocido';
        },
    },
};
</script>

<style scoped>
.v-data-table >>> th {
    font-weight: bold !important;
}
</style>
