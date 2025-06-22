<template>
    <v-card class="elevation-1">
        <v-card-title class="headline primary white--text">
            Comparativa Anual de Licencias
            <v-spacer></v-spacer>
            <v-btn
                icon
                color="white"
                @click="reloadData"
            >
                <v-icon>mdi-refresh</v-icon>
            </v-btn>
        </v-card-title>

        <v-card-text>
            <v-row class="mt-2 mb-4">
                <v-col
                    cols="12"
                    sm="4"
                >
                    <v-select
                        v-model="filtroAnno"
                        :items="annosDisponibles"
                        label="Año a comparar"
                        dense
                        outlined
                        hide-details
                        @change="actualizarGrafico"
                    ></v-select>
                </v-col>
                <v-col
                    cols="12"
                    sm="4"
                >
                    <v-select
                        v-model="filtroTipo"
                        :items="tiposLicencia"
                        label="Tipo de licencia"
                        dense
                        outlined
                        hide-details
                        @change="actualizarGrafico"
                    ></v-select>
                </v-col>
                <v-col
                    cols="12"
                    sm="4"
                >
                    <v-select
                        v-model="tipoGrafico"
                        :items="tiposGrafico"
                        label="Tipo de gráfico"
                        dense
                        outlined
                        hide-details
                        @change="cambiarTipoGrafico"
                    ></v-select>
                </v-col>
            </v-row>

            <v-row
                align="center"
                justify="center"
                v-if="loading"
            >
                <v-progress-circular
                    indeterminate
                    color="primary"
                    class="my-8"
                ></v-progress-circular>
            </v-row>

            <template v-else>
                <div class="chart-container">
                    <div v-if="tipoGrafico === 'stacked'">
                        <simple-chart-component
                            chart-id="licencias-comparativa-stacked-chart"
                            :chart-data="chartDataStacked"
                            :options="stackedChartOptions"
                            chart-type="bar"
                            :height="350"
                        />
                    </div>
                    <div v-else>
                        <simple-chart-component
                            chart-id="licencias-comparativa-chart"
                            :chart-data="chartData"
                            :options="
                                tipoGrafico === 'bar'
                                    ? barChartOptions
                                    : lineChartOptions
                            "
                            :chart-type="tipoGrafico"
                            :height="350"
                        />
                    </div>
                </div>

                <v-card-subtitle class="pt-4 pb-0">
                    Resumen Comparativo
                </v-card-subtitle>

                <v-simple-table dense>
                    <template v-slot:default>
                        <thead>
                            <tr>
                                <th class="text-left">Categoría</th>
                                <th class="text-right">{{ annoActual }}</th>
                                <th class="text-right">{{ filtroAnno }}</th>
                                <th class="text-right">Diferencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item, index) in resumenComparativo"
                                :key="index"
                            >
                                <td>{{ item.categoria }}</td>
                                <td class="text-right font-weight-medium">
                                    {{ item.annoActual }}
                                </td>
                                <td class="text-right font-weight-medium">
                                    {{ item.annoComparado }}
                                </td>
                                <td class="text-right">
                                    <v-chip
                                        x-small
                                        :color="
                                            item.diferencia > 0
                                                ? 'red lighten-4'
                                                : item.diferencia < 0
                                                ? 'green lighten-4'
                                                : 'grey lighten-3'
                                        "
                                        :text-color="
                                            item.diferencia > 0
                                                ? 'red darken-4'
                                                : item.diferencia < 0
                                                ? 'green darken-4'
                                                : 'grey darken-3'
                                        "
                                    >
                                        {{
                                            item.diferencia > 0
                                                ? '+' + item.diferencia
                                                : item.diferencia
                                        }}
                                    </v-chip>
                                </td>
                            </tr>
                            <tr class="grey lighten-4">
                                <td class="font-weight-bold">Total</td>
                                <td class="text-right font-weight-bold">
                                    {{ totalAnnoActual }}
                                </td>
                                <td class="text-right font-weight-bold">
                                    {{ totalAnnoComparado }}
                                </td>
                                <td class="text-right">
                                    <v-chip
                                        small
                                        :color="
                                            diferenciaTotal > 0
                                                ? 'red'
                                                : diferenciaTotal < 0
                                                ? 'green'
                                                : 'grey'
                                        "
                                        text-color="white"
                                    >
                                        {{
                                            diferenciaTotal > 0
                                                ? '+' + diferenciaTotal
                                                : diferenciaTotal
                                        }}
                                    </v-chip>
                                </td>
                            </tr>
                        </tbody>
                    </template>
                </v-simple-table>
            </template>
        </v-card-text>
    </v-card>
</template>

<script>
import SimpleChartComponent from './SimpleChartComponent.vue';

export default {
    name: 'LicenciasComparativaChart',
    components: {
        SimpleChartComponent,
    },
    data() {
        return {
            loading: true,
            annoActual: new Date().getFullYear(),
            filtroAnno: new Date().getFullYear() - 1,
            filtroTipo: 'Todos',
            tipoGrafico: 'bar',
            annosDisponibles: [
                new Date().getFullYear() - 3,
                new Date().getFullYear() - 2,
                new Date().getFullYear() - 1,
            ],
            tiposLicencia: [
                'Todos',
                'Salud',
                'Anual',
                'Corto Tratamiento',
                'Sin Goce',
                'Otros',
            ],
            tiposGrafico: [
                { text: 'Barras', value: 'bar' },
                { text: 'Líneas', value: 'line' },
                { text: 'Barras Apiladas', value: 'stacked' },
            ],
            datosAnnoActual: [12, 15, 8, 10, 14, 12, 9, 6, 18, 22, 15, 10],
            datosAnnoComparado: [10, 12, 6, 8, 15, 10, 12, 5, 15, 18, 12, 8],
            // Datos para gráfico apilado
            datosPorTipoActual: {
                Salud: [5, 7, 3, 4, 6, 5, 4, 2, 8, 10, 7, 4],
                Anual: [3, 4, 2, 3, 5, 4, 3, 2, 6, 7, 5, 3],
                'Corto Tratamiento': [2, 2, 1, 2, 2, 2, 1, 1, 2, 3, 2, 2],
                'Sin Goce': [1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1],
                Otros: [1, 1, 1, 0, 0, 0, 0, 1, 1, 1, 0, 0],
            },
            datosPorTipoComparado: {
                Salud: [4, 5, 2, 3, 7, 4, 5, 2, 7, 8, 5, 3],
                Anual: [2, 3, 2, 2, 4, 3, 4, 1, 5, 6, 4, 2],
                'Corto Tratamiento': [2, 2, 1, 2, 2, 2, 2, 1, 1, 2, 2, 2],
                'Sin Goce': [1, 1, 0, 1, 1, 1, 1, 0, 1, 1, 1, 1],
                Otros: [1, 1, 1, 0, 1, 0, 0, 1, 1, 1, 0, 0],
            },
        };
    },
    computed: {
        barChartOptions() {
            return {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    yAxes: [
                        {
                            ticks: {
                                beginAtZero: true,
                                precision: 0,
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Cantidad de Licencias',
                            },
                        },
                    ],
                },
            };
        },
        lineChartOptions() {
            return {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    yAxes: [
                        {
                            ticks: {
                                beginAtZero: true,
                                precision: 0,
                            },
                        },
                    ],
                },
                elements: {
                    line: {
                        tension: 0.2,
                    },
                    point: {
                        radius: 4,
                        hoverRadius: 6,
                    },
                },
            };
        },
        stackedChartOptions() {
            return {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'top',
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    xAxes: [
                        {
                            stacked: true,
                        },
                    ],
                    yAxes: [
                        {
                            stacked: true,
                            ticks: {
                                beginAtZero: true,
                                precision: 0,
                            },
                        },
                    ],
                },
            };
        },
        chartData() {
            const meses = [
                'Ene',
                'Feb',
                'Mar',
                'Abr',
                'May',
                'Jun',
                'Jul',
                'Ago',
                'Sep',
                'Oct',
                'Nov',
                'Dic',
            ];

            return {
                labels: meses,
                datasets: [
                    {
                        label: `${this.annoActual}`,
                        data: this.datosAnnoActual,
                        backgroundColor: 'rgba(25, 118, 210, 0.6)',
                        borderColor: '#1976D2',
                        borderWidth: 1,
                    },
                    {
                        label: `${this.filtroAnno}`,
                        data: this.datosAnnoComparado,
                        backgroundColor: 'rgba(76, 175, 80, 0.6)',
                        borderColor: '#4CAF50',
                        borderWidth: 1,
                    },
                ],
            };
        },
        chartDataStacked() {
            const meses = [
                'Ene',
                'Feb',
                'Mar',
                'Abr',
                'May',
                'Jun',
                'Jul',
                'Ago',
                'Sep',
                'Oct',
                'Nov',
                'Dic',
            ];
            const colores = {
                Salud: {
                    actual: 'rgba(25, 118, 210, 0.7)',
                    comparado: 'rgba(25, 118, 210, 0.4)',
                },
                Anual: {
                    actual: 'rgba(76, 175, 80, 0.7)',
                    comparado: 'rgba(76, 175, 80, 0.4)',
                },
                'Corto Tratamiento': {
                    actual: 'rgba(255, 152, 0, 0.7)',
                    comparado: 'rgba(255, 152, 0, 0.4)',
                },
                'Sin Goce': {
                    actual: 'rgba(244, 67, 54, 0.7)',
                    comparado: 'rgba(244, 67, 54, 0.4)',
                },
                Otros: {
                    actual: 'rgba(156, 39, 176, 0.7)',
                    comparado: 'rgba(156, 39, 176, 0.4)',
                },
            };

            const datasets = [];

            // Generar datasets para el año actual y el comparado
            Object.keys(this.datosPorTipoActual).forEach((tipo) => {
                datasets.push({
                    label: `${tipo} ${this.annoActual}`,
                    data: this.datosPorTipoActual[tipo],
                    backgroundColor: colores[tipo].actual,
                    stack: 'Stack 0',
                });

                datasets.push({
                    label: `${tipo} ${this.filtroAnno}`,
                    data: this.datosPorTipoComparado[tipo],
                    backgroundColor: colores[tipo].comparado,
                    stack: 'Stack 1',
                });
            });

            return {
                labels: meses,
                datasets: datasets,
            };
        },
        resumenComparativo() {
            // Datos para la tabla de resumen
            return [
                {
                    categoria: 'Licencias médicas',
                    annoActual: 65,
                    annoComparado: 55,
                    diferencia: 10,
                },
                {
                    categoria: 'Vacaciones anuales',
                    annoActual: 45,
                    annoComparado: 38,
                    diferencia: 7,
                },
                {
                    categoria: 'Licencias corto tratamiento',
                    annoActual: 22,
                    annoComparado: 21,
                    diferencia: 1,
                },
                {
                    categoria: 'Licencias sin goce',
                    annoActual: 10,
                    annoComparado: 10,
                    diferencia: 0,
                },
                {
                    categoria: 'Otras licencias',
                    annoActual: 9,
                    annoComparado: 12,
                    diferencia: -3,
                },
            ];
        },
        totalAnnoActual() {
            return this.resumenComparativo.reduce(
                (total, item) => total + item.annoActual,
                0,
            );
        },
        totalAnnoComparado() {
            return this.resumenComparativo.reduce(
                (total, item) => total + item.annoComparado,
                0,
            );
        },
        diferenciaTotal() {
            return this.totalAnnoActual - this.totalAnnoComparado;
        },
    },
    mounted() {
        this.cargarDatos();
    },
    methods: {
        cargarDatos() {
            this.loading = true;

            // Simulamos carga de datos (reemplazar con llamada a API real)
            setTimeout(() => {
                // Aquí deberíamos hacer la llamada a la API para obtener los datos
                // const params = { anno_actual: this.annoActual, anno_comparado: this.filtroAnno, tipo: this.filtroTipo };
                // fetchDatosComparativos(params).then(response => { ... })

                this.loading = false;
            }, 1000);
        },
        actualizarGrafico() {
            this.cargarDatos();
        },
        cambiarTipoGrafico() {
            // El tipo de gráfico cambió, actualizamos la vista
            this.loading = true;
            setTimeout(() => {
                this.loading = false;
            }, 400);
        },
        reloadData() {
            this.cargarDatos();
        },
    },
};
</script>

<style scoped>
.chart-container {
    position: relative;
    margin: auto;
    height: 350px;
}

.v-card__title.primary {
    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
}
</style>
