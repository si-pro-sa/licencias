<template>
    <div class="chart-container">
        <canvas
            :id="chartId"
            :width="width"
            :height="height"
        ></canvas>
    </div>
</template>

<script>
// Importar Chart.js directamente
import Chart from 'chart.js';

export default {
    name: 'SimpleChartComponent',
    props: {
        chartId: {
            type: String,
            required: true,
        },
        width: {
            type: Number,
            default: 400,
        },
        height: {
            type: Number,
            default: 200,
        },
        chartData: {
            type: Object,
            required: true,
        },
        options: {
            type: Object,
            default: () => ({}),
        },
        chartType: {
            type: String,
            default: 'bar',
            validator: function (value) {
                return ['bar', 'line', 'pie', 'doughnut'].indexOf(value) !== -1;
            },
        },
    },
    data() {
        return {
            chart: null,
        };
    },
    mounted() {
        this.renderChart();
    },
    beforeDestroy() {
        if (this.chart) {
            this.chart.destroy();
        }
    },
    watch: {
        chartData: {
            handler() {
                this.renderChart();
            },
            deep: true,
        },
        options: {
            handler() {
                this.renderChart();
            },
            deep: true,
        },
        chartType() {
            this.renderChart();
        },
    },
    methods: {
        renderChart() {
            // Si ya existe un gráfico, lo destruimos
            if (this.chart) {
                this.chart.destroy();
            }

            // Establecemos la altura del canvas
            document.getElementById(this.chartId).height = this.height;

            // Obtenemos el contexto del canvas
            const ctx = document.getElementById(this.chartId).getContext('2d');

            // Creamos el gráfico
            this.chart = new Chart(ctx, {
                type: this.chartType,
                data: this.chartData,
                options: this.options,
            });
        },
    },
};
</script>

<style scoped>
.chart-container {
    position: relative;
    width: 100%;
    height: 100%;
}
</style>
