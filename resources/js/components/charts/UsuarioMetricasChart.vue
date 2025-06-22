<template>
    <v-card
        class="mx-auto my-3"
        elevation="2"
    >
        <v-card-title
            class="primary white--text headline d-flex justify-space-between"
        >
            <div>
                <v-icon
                    left
                    color="white"
                    >mdi-chart-box</v-icon
                >
                Métricas Personales
            </div>
            <div>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            icon
                            @click="reloadData"
                            :loading="loading"
                            v-bind="attrs"
                            v-on="on"
                            color="white"
                        >
                            <v-icon>mdi-refresh</v-icon>
                        </v-btn>
                    </template>
                    <span>Actualizar datos</span>
                </v-tooltip>
            </div>
        </v-card-title>

        <v-alert
            v-if="error"
            type="error"
            dismissible
            class="ma-3"
            dense
        >
            {{ error }}
        </v-alert>

        <v-card-text>
            <v-row class="text-center mb-3">
                <v-col cols="4">
                    <v-card
                        flat
                        class="pa-2 rounded-lg"
                        color="primary lighten-5"
                    >
                        <div class="subtitle-2 text--secondary">
                            Total de licencias
                        </div>
                        <div class="title font-weight-bold primary--text">
                            {{ getTotalLicencias }}
                        </div>
                    </v-card>
                </v-col>
                <v-col cols="4">
                    <v-card
                        flat
                        class="pa-2 rounded-lg"
                        color="success lighten-5"
                    >
                        <div class="subtitle-2 text--secondary">Aprobadas</div>
                        <div class="title font-weight-bold success--text">
                            {{ getLicenciasAprobadas }}
                        </div>
                    </v-card>
                </v-col>
                <v-col cols="4">
                    <v-card
                        flat
                        class="pa-2 rounded-lg"
                        color="warning lighten-5"
                    >
                        <div class="subtitle-2 text--secondary">Pendientes</div>
                        <div class="title font-weight-bold warning--text">
                            {{ getLicenciasPendientes }}
                        </div>
                    </v-card>
                </v-col>
            </v-row>

            <v-tabs
                v-model="activeTab"
                background-color="transparent"
                grow
                slider-color="primary"
            >
                <v-tab>
                    <v-icon left>mdi-file-document-outline</v-icon>
                    Licencias
                </v-tab>
                <v-tab>
                    <v-icon left>mdi-calendar-clock</v-icon>
                    Tiempo
                </v-tab>
            </v-tabs>

            <v-divider></v-divider>

            <v-tabs-items v-model="activeTab">
                <v-tab-item>
                    <div
                        v-if="loading"
                        class="d-flex justify-center align-center"
                        style="height: 350px"
                    >
                        <v-progress-circular
                            indeterminate
                            color="primary"
                            size="50"
                        ></v-progress-circular>
                    </div>
                    <div v-else>
                        <v-row>
                            <v-col
                                cols="12"
                                sm="6"
                            >
                                <div
                                    class="chart-container"
                                    style="height: 300px"
                                >
                                    <simple-chart-component
                                        chart-id="licencias-chart"
                                        chart-type="pie"
                                        :chart-data="licenciasChartData"
                                        :options="licenciasChartOptions"
                                        :height="280"
                                    ></simple-chart-component>
                                </div>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                            >
                                <v-list dense>
                                    <v-subheader
                                        >Resumen de Licencias</v-subheader
                                    >
                                    <v-list-item
                                        v-for="(item, i) in licenciasData"
                                        :key="i"
                                        class="list-item"
                                    >
                                        <v-list-item-icon>
                                            <v-icon :color="chartColors[i]"
                                                >mdi-circle</v-icon
                                            >
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title>{{
                                                item.label
                                            }}</v-list-item-title>
                                        </v-list-item-content>
                                        <v-list-item-action>
                                            <v-chip
                                                small
                                                :color="item.color"
                                                text-color="white"
                                            >
                                                {{ item.value }}
                                            </v-chip>
                                        </v-list-item-action>
                                    </v-list-item>

                                    <v-divider class="my-2"></v-divider>

                                    <v-list-item>
                                        <v-list-item-icon>
                                            <v-icon color="primary"
                                                >mdi-check-circle</v-icon
                                            >
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                >Días
                                                disponibles</v-list-item-title
                                            >
                                        </v-list-item-content>
                                        <v-list-item-action>
                                            <strong class="primary--text"
                                                >20 días</strong
                                            >
                                        </v-list-item-action>
                                    </v-list-item>
                                </v-list>
                            </v-col>
                        </v-row>
                    </div>
                </v-tab-item>

                <v-tab-item>
                    <div
                        v-if="loading"
                        class="d-flex justify-center align-center"
                        style="height: 350px"
                    >
                        <v-progress-circular
                            indeterminate
                            color="primary"
                            size="50"
                        ></v-progress-circular>
                    </div>
                    <div v-else>
                        <v-row>
                            <v-col cols="12">
                                <div
                                    class="chart-container px-2 pt-4 pb-0"
                                    style="height: 300px"
                                >
                                    <simple-chart-component
                                        chart-id="tiempo-chart"
                                        chart-type="line"
                                        :chart-data="tiempoChartData"
                                        :options="tiempoChartOptions"
                                        :height="280"
                                    ></simple-chart-component>
                                </div>
                            </v-col>
                        </v-row>

                        <v-row class="mt-2">
                            <v-col
                                cols="12"
                                md="6"
                                v-for="(item, index) in estadisticasTiempo"
                                :key="index"
                            >
                                <v-card
                                    flat
                                    class="pa-2 rounded-lg"
                                    :color="`${item.color} lighten-5`"
                                >
                                    <v-row no-gutters>
                                        <v-col
                                            cols="auto"
                                            class="pr-3"
                                        >
                                            <v-avatar
                                                :color="item.color"
                                                size="38"
                                                class="elevation-1"
                                            >
                                                <v-icon dark>{{
                                                    item.icon
                                                }}</v-icon>
                                            </v-avatar>
                                        </v-col>
                                        <v-col>
                                            <div
                                                class="subtitle-2 text--secondary"
                                            >
                                                {{ item.titulo }}
                                            </div>
                                            <div
                                                class="subtitle-1 font-weight-bold"
                                            >
                                                {{ item.valor }}
                                            </div>
                                        </v-col>
                                    </v-row>
                                </v-card>
                            </v-col>
                        </v-row>
                    </div>
                </v-tab-item>
            </v-tabs-items>
        </v-card-text>

        <v-card-actions class="pb-4 px-4">
            <v-spacer></v-spacer>
            <v-btn
                color="primary"
                text
                @click="mostrarDetalles"
            >
                Ver más detalles
                <v-icon right>mdi-chevron-right</v-icon>
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import SimpleChartComponent from './SimpleChartComponent.vue';
import userApi from '../../api/userApi';

export default {
    name: 'UsuarioMetricasChart',
    components: {
        SimpleChartComponent,
    },
    props: {
        usuarioId: {
            type: [Number, String],
            default: null,
        },
    },
    data() {
        return {
            activeTab: 0,
            loading: true,
            chartColors: [
                '#4CAF50', // Verde
                '#FFC107', // Amarillo
                '#F44336', // Rojo
                '#2196F3', // Azul
                '#9C27B0', // Morado
            ],
            licenciasData: [
                { label: 'Aprobadas', value: 0, color: 'success' },
                { label: 'Pendientes', value: 0, color: 'warning' },
                { label: 'Rechazadas', value: 0, color: 'error' },
            ],
            tiempoData: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                datasets: [
                    {
                        label: 'Días solicitados',
                        data: [0, 0, 0, 0, 0, 0],
                        borderColor: '#4CAF50',
                        backgroundColor: 'rgba(76, 175, 80, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                    },
                    {
                        label: 'Promedio departamento',
                        data: [0, 0, 0, 0, 0, 0],
                        borderColor: '#2196F3',
                        backgroundColor: 'rgba(33, 150, 243, 0.1)',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        fill: false,
                    },
                ],
            },
            estadisticasTiempo: [
                {
                    titulo: 'Días disponibles vacaciones',
                    valor: '15 días',
                    color: 'green',
                    icon: 'mdi-beach',
                },
                {
                    titulo: 'Horas extras acumuladas',
                    valor: '22 horas',
                    color: 'blue',
                    icon: 'mdi-clock-outline',
                },
                {
                    titulo: 'Licencias ocupacionales',
                    valor: '2 pendientes',
                    color: 'amber',
                    icon: 'mdi-medical-bag',
                },
                {
                    titulo: 'Asistencia mensual',
                    valor: '97%',
                    color: 'deep-purple',
                    icon: 'mdi-check-circle-outline',
                },
            ],
            licenciasChartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        fontColor: '#555',
                    },
                },
                tooltips: {
                    backgroundColor: 'rgba(0,0,0,0.7)',
                    titleFontSize: 14,
                    bodyFontSize: 13,
                    callbacks: {
                        label: function (tooltipItem, data) {
                            const value =
                                data.datasets[0].data[tooltipItem.index];
                            const label = data.labels[tooltipItem.index];
                            return `${label}: ${value}`;
                        },
                    },
                },
                animation: {
                    animateScale: true,
                    animateRotate: true,
                },
            },
            tiempoChartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                    },
                },
                tooltips: {
                    backgroundColor: 'rgba(0,0,0,0.7)',
                    titleFontSize: 14,
                    bodyFontSize: 13,
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    yAxes: [
                        {
                            ticks: {
                                beginAtZero: true,
                                callback: function (value) {
                                    return value + ' días';
                                },
                            },
                            gridLines: {
                                display: true,
                                drawBorder: false,
                                color: 'rgba(200, 200, 200, 0.2)',
                            },
                        },
                    ],
                    xAxes: [
                        {
                            gridLines: {
                                display: false,
                            },
                        },
                    ],
                },
                elements: {
                    point: {
                        radius: 3,
                        hoverRadius: 5,
                    },
                },
            },
            error: null,
        };
    },
    computed: {
        licenciasChartData() {
            return {
                labels: this.licenciasData.map((item) => item.label),
                datasets: [
                    {
                        data: this.licenciasData.map((item) => item.value),
                        backgroundColor: this.chartColors.slice(
                            0,
                            this.licenciasData.length,
                        ),
                    },
                ],
            };
        },
        tiempoChartData() {
            return this.tiempoData;
        },
        getTotalLicencias() {
            return this.licenciasData.reduce(
                (total, item) => total + item.value,
                0,
            );
        },
        getLicenciasAprobadas() {
            const aprobadas = this.licenciasData.find(
                (item) => item.label === 'Aprobadas',
            );
            return aprobadas ? aprobadas.value : 0;
        },
        getLicenciasPendientes() {
            const pendientes = this.licenciasData.find(
                (item) => item.label === 'Pendientes',
            );
            return pendientes ? pendientes.value : 0;
        },
    },
    mounted() {
        this.loadData();
    },
    methods: {
        async loadData() {
            this.loading = true;
            this.error = null;

            try {
                // Obtener datos de la API
                const metricas = this.usuarioId
                    ? await userApi.getUserMetricsById(this.usuarioId)
                    : await userApi.getUserMetrics();

                // Procesar datos para licencias
                if (metricas && metricas.licencias) {
                    this.licenciasData = [
                        {
                            label: 'Aprobadas',
                            value: metricas.licencias.aprobadas || 0,
                            color: 'success',
                        },
                        {
                            label: 'Pendientes',
                            value: metricas.licencias.pendientes || 0,
                            color: 'warning',
                        },
                        {
                            label: 'Rechazadas',
                            value: metricas.licencias.rechazadas || 0,
                            color: 'error',
                        },
                    ];
                }

                // Procesar datos para tiempo
                if (
                    metricas &&
                    metricas.tiempo &&
                    metricas.tiempo.labels &&
                    metricas.tiempo.datos
                ) {
                    this.tiempoData.labels = metricas.tiempo.labels;
                    this.tiempoData.datasets[0].data = metricas.tiempo.datos;

                    // Si hay datos de promedio del departamento, los incluimos
                    if (metricas.tiempo.promedio_departamento) {
                        this.tiempoData.datasets[1].data =
                            metricas.tiempo.promedio_departamento;
                    } else {
                        // Datos de ejemplo para el promedio si no hay datos reales
                        this.tiempoData.datasets[1].data = [2, 3, 2, 5, 3, 4];
                    }
                }

                // Actualizar estadísticas de tiempo si están disponibles
                if (metricas && metricas.estadisticas) {
                    this.actualizarEstadisticas(metricas.estadisticas);
                }
            } catch (error) {
                console.error('Error al cargar métricas de usuario:', error);
                this.error =
                    'No se pudieron cargar las métricas. Intente nuevamente.';

                // Si hay un error, cargar datos de ejemplo como fallback
                this.cargarDatosEjemplo();
            } finally {
                this.loading = false;
            }
        },
        cargarDatosEjemplo() {
            // Datos de ejemplo para licencias como fallback
            this.licenciasData = [
                { label: 'Aprobadas', value: 12, color: 'success' },
                { label: 'Pendientes', value: 5, color: 'warning' },
                { label: 'Rechazadas', value: 2, color: 'error' },
            ];

            // Datos de ejemplo para tiempo como fallback
            this.tiempoData = {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                datasets: [
                    {
                        label: 'Días solicitados',
                        data: [3, 5, 2, 7, 4, 6],
                        borderColor: '#4CAF50',
                        backgroundColor: 'rgba(76, 175, 80, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                    },
                    {
                        label: 'Promedio departamento',
                        data: [2, 3, 2, 5, 3, 4],
                        borderColor: '#2196F3',
                        backgroundColor: 'rgba(33, 150, 243, 0.1)',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        fill: false,
                    },
                ],
            };
        },
        actualizarEstadisticas(estadisticas) {
            if (estadisticas.dias_disponibles) {
                this.estadisticasTiempo[0].valor = `${estadisticas.dias_disponibles} días`;
            }

            if (estadisticas.horas_extras) {
                this.estadisticasTiempo[1].valor = `${estadisticas.horas_extras} horas`;
            }

            if (estadisticas.licencias_pendientes) {
                this.estadisticasTiempo[2].valor = `${estadisticas.licencias_pendientes} pendientes`;
            }

            if (estadisticas.asistencia) {
                this.estadisticasTiempo[3].valor = `${estadisticas.asistencia}%`;
            }
        },
        reloadData() {
            this.loadData();
        },
        mostrarDetalles() {
            // En un caso real, aquí navegaríamos a una página de detalles
            // o abriríamos un modal con más información
            this.$emit('ver-detalles');
        },
    },
};
</script>

<style scoped>
.list-item {
    min-height: 40px;
}
.chart-container {
    position: relative;
    width: 100%;
}
</style>
