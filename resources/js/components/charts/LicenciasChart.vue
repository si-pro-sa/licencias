<template>
    <div class="chart-container">
        <div class="chart-filters mb-4">
            <v-row align="center">
                <v-col
                    cols="12"
                    sm="5"
                >
                    <v-menu
                        v-model="startDateMenu"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                                v-model="formattedStartDate"
                                label="Desde"
                                prepend-icon="mdi-calendar"
                                readonly
                                v-bind="attrs"
                                v-on="on"
                                dense
                                outlined
                            ></v-text-field>
                        </template>
                        <v-date-picker
                            v-model="startDate"
                            @input="startDateMenu = false"
                        ></v-date-picker>
                    </v-menu>
                </v-col>

                <v-col
                    cols="12"
                    sm="5"
                >
                    <v-menu
                        v-model="endDateMenu"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                                v-model="formattedEndDate"
                                label="Hasta"
                                prepend-icon="mdi-calendar"
                                readonly
                                v-bind="attrs"
                                v-on="on"
                                dense
                                outlined
                            ></v-text-field>
                        </template>
                        <v-date-picker
                            v-model="endDate"
                            @input="endDateMenu = false"
                        ></v-date-picker>
                    </v-menu>
                </v-col>

                <v-col
                    cols="12"
                    sm="2"
                >
                    <v-btn
                        color="primary"
                        @click="updateChart"
                        :disabled="!startDate || !endDate"
                        small
                    >
                        <v-icon left>mdi-filter</v-icon>
                        Filtrar
                    </v-btn>
                </v-col>
            </v-row>
        </div>

        <div class="chart-wrapper">
            <simple-chart-component
                :chart-id="'licencias-chart'"
                :chart-data="chartData"
                :options="chartOptions"
                :chart-type="chartType"
                :height="350"
            />
        </div>

        <div class="chart-controls text-center mt-2">
            <v-btn-toggle
                v-model="chartType"
                mandatory
                dense
            >
                <v-btn
                    value="bar"
                    small
                >
                    <v-icon small>mdi-chart-bar</v-icon>
                </v-btn>
                <v-btn
                    value="pie"
                    small
                >
                    <v-icon small>mdi-chart-pie</v-icon>
                </v-btn>
                <v-btn
                    value="line"
                    small
                >
                    <v-icon small>mdi-chart-line</v-icon>
                </v-btn>
            </v-btn-toggle>
        </div>
    </div>
</template>

<script>
import { format, subMonths, parse } from 'date-fns';
import SimpleChartComponent from './SimpleChartComponent.vue';

export default {
    name: 'LicenciasChart',
    components: {
        SimpleChartComponent,
    },
    props: {
        licenciasData: {
            type: Array,
            default: () => [],
        },
        title: {
            type: String,
            default: 'Licencias por Tipo',
        },
    },
    data() {
        // Configurar las fechas por defecto (último mes)
        const endDate = new Date();
        const startDate = subMonths(endDate, 1);

        return {
            chartType: 'bar',
            startDate: format(startDate, 'yyyy-MM-dd'),
            endDate: format(endDate, 'yyyy-MM-dd'),
            startDateMenu: false,
            endDateMenu: false,
            chartData: null,
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                title: {
                    display: true,
                    text: this.title,
                    fontSize: 16,
                },
                legend: {
                    position: 'bottom',
                },
                tooltips: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleFontSize: 14,
                    bodyFontSize: 13,
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
            },
        };
    },
    computed: {
        formattedStartDate() {
            if (!this.startDate) return '';
            const date = parse(this.startDate, 'yyyy-MM-dd', new Date());
            return format(date, 'dd/MM/yyyy');
        },
        formattedEndDate() {
            if (!this.endDate) return '';
            const date = parse(this.endDate, 'yyyy-MM-dd', new Date());
            return format(date, 'dd/MM/yyyy');
        },
    },
    mounted() {
        this.updateChart();
    },
    watch: {
        licenciasData: {
            handler() {
                this.updateChart();
            },
            deep: true,
        },
    },
    methods: {
        updateChart() {
            // Procesar datos para el gráfico basado en las fechas seleccionadas
            const data = this.processData();

            // Configurar el chartData según el tipo de gráfico
            if (this.chartType === 'pie') {
                this.chartData = {
                    labels: data.labels,
                    datasets: [
                        {
                            data: data.values,
                            backgroundColor: data.colors,
                            borderWidth: 1,
                        },
                    ],
                };
                // Eliminar escalas para el gráfico circular
                this.chartOptions.scales = undefined;
            } else {
                this.chartData = {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Cantidad de Licencias',
                            data: data.values,
                            backgroundColor: data.colors,
                            borderColor: data.colors,
                            borderWidth: 1,
                        },
                    ],
                };
                // Restaurar escalas para gráficos de barras y líneas
                this.chartOptions.scales = {
                    yAxes: [
                        {
                            ticks: {
                                beginAtZero: true,
                                precision: 0,
                            },
                        },
                    ],
                };
            }

            this.chartOptions.title.text = this.title;
        },
        processData() {
            // En un caso real, aquí filtrarías los datos por fecha
            // Por ahora, simulamos datos de licencias
            const mockData = [
                { tipo: 'Salud Ocupacional', cantidad: 45, color: '#1976D2' },
                { tipo: 'Anual Reglamentaria', cantidad: 32, color: '#4CAF50' },
                { tipo: 'Sin Goce', cantidad: 12, color: '#FF9800' },
                { tipo: 'Con Goce', cantidad: 28, color: '#F44336' },
                { tipo: 'Largo Tratamiento', cantidad: 15, color: '#00BCD4' },
                { tipo: 'Corto Tratamiento', cantidad: 37, color: '#673AB7' },
            ];

            return {
                labels: mockData.map((item) => item.tipo),
                values: mockData.map((item) => item.cantidad),
                colors: mockData.map((item) => item.color),
            };
        },
    },
};
</script>

<style scoped>
.chart-container {
    width: 100%;
    height: 100%;
    position: relative;
}
.chart-wrapper {
    position: relative;
    width: 100%;
}
</style>
