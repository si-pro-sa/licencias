<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12">
                <v-card class="mb-4">
                    <v-card-title class="headline primary white--text">
                        Sistema de Administración Recursos Humanos de Salud
                    </v-card-title>
                    <v-card-subtitle class="py-2">
                        Panel de Control Principal
                    </v-card-subtitle>
                </v-card>
            </v-col>
        </v-row>

        <!-- Tarjetas de estadísticas principales -->
        <v-row>
            <v-col
                cols="12"
                md="3"
                sm="6"
            >
                <v-card
                    outlined
                    class="text-center"
                    height="150"
                >
                    <v-card-text>
                        <v-avatar
                            size="48"
                            color="primary"
                            class="mb-2"
                        >
                            <v-icon dark>mdi-file-document</v-icon>
                        </v-avatar>
                        <div class="text-h3 primary--text">
                            {{ licenciasTotales }}
                        </div>
                        <div class="text-subtitle-1">Licencias Totales</div>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col
                cols="12"
                md="3"
                sm="6"
            >
                <v-card
                    outlined
                    class="text-center"
                    height="150"
                >
                    <v-card-text>
                        <v-avatar
                            size="48"
                            color="success"
                            class="mb-2"
                        >
                            <v-icon dark>mdi-check-circle</v-icon>
                        </v-avatar>
                        <div class="text-h3 success--text">
                            {{ licenciasVisadas }}
                        </div>
                        <div class="text-subtitle-1">Licencias Visadas</div>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col
                cols="12"
                md="3"
                sm="6"
            >
                <v-card
                    outlined
                    class="text-center"
                    height="150"
                >
                    <v-card-text>
                        <v-avatar
                            size="48"
                            color="warning"
                            class="mb-2"
                        >
                            <v-icon dark>mdi-clock-outline</v-icon>
                        </v-avatar>
                        <div class="text-h3 warning--text">
                            {{ licenciasPendientes }}
                        </div>
                        <div class="text-subtitle-1">Pendientes</div>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col
                cols="12"
                md="3"
                sm="6"
            >
                <v-card
                    outlined
                    class="text-center"
                    height="150"
                >
                    <v-card-text>
                        <v-avatar
                            size="48"
                            color="info"
                            class="mb-2"
                        >
                            <v-icon dark>mdi-account-group</v-icon>
                        </v-avatar>
                        <div class="text-h3 info--text">
                            {{ juntasMedicas }}
                        </div>
                        <div class="text-subtitle-1">Juntas Médicas</div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <!-- Gráficos y estadísticas detalladas -->
        <v-row>
            <v-col
                cols="12"
                md="8"
            >
                <v-card class="mb-4">
                    <v-card-title>
                        <v-icon left>mdi-chart-line</v-icon>
                        Licencias por Tipo
                    </v-card-title>
                    <v-card-text style="height: 380px">
                        <div
                            id="chart-licencias-tipo"
                            class="chart-container"
                            style="height: 100%"
                        >
                            <!-- Aquí se insertaría el gráfico usando una librería como Chart.js -->
                            <!-- Mockup de gráfico -->
                            <v-sheet
                                class="d-flex align-center justify-center flex-column"
                                color="grey lighten-4"
                                height="100%"
                            >
                                <div
                                    class="pa-4 d-flex flex-column align-center"
                                >
                                    <v-row>
                                        <v-col
                                            v-for="(
                                                item, i
                                            ) in tiposLicenciaStats"
                                            :key="i"
                                            cols="6"
                                            class="py-1"
                                        >
                                            <v-sheet
                                                :color="item.color"
                                                width="100%"
                                                height="30"
                                                class="d-flex align-center px-2"
                                            >
                                                <span class="white--text"
                                                    >{{ item.tipo }} -
                                                    {{ item.cantidad }}</span
                                                >
                                            </v-sheet>
                                        </v-col>
                                    </v-row>
                                    <v-img
                                        src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIj48cmVjdCB3aWR0aD0iNTAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2Y1ZjVmNSIvPjxwYXRoIGQ9Ik01MCAxNTBDMTAwIDgwIDE1MCAxMjAgMjAwIDYwIDI1MCAxMDAgMzAwIDQwIDM1MCAxMjAgNDAwIDkwIDQ1MCAxMDAiIHN0cm9rZT0iIzFkOGZlNSIgc3Ryb2tlLXdpZHRoPSIzIi8+PHBhdGggZD0iTTUwIDE3MEMxMDAgMTIwIDE1MCAxMzAgMjAwIDEwMCAyNTAgMTQwIDMwMCA5MCAzNTAgMTMwIDQwMCAxMTAgNDUwIDEzMCIgc3Ryb2tlPSIjNGNhZjUwIiBzdHJva2Utd2lkdGg9IjMiLz48cGF0aCBkPSJNNTAgMTMwQzEwMCAxMTAgMTUwIDE0MCAyMDAgMTIwIDI1MCAxNjAgMzAwIDEwMCAzNTAgMTQwIDQwMCAxMjAgNDUwIDE0MCIgc3Ryb2tlPSIjZmY5ODAwIiBzdHJva2Utd2lkdGg9IjMiLz48L2c+PC9zdmc+"
                                        height="200"
                                        class="my-4"
                                    ></v-img>
                                </div>
                            </v-sheet>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col
                cols="12"
                md="4"
            >
                <v-card
                    class="mb-4"
                    height="465"
                >
                    <v-card-title>
                        <v-icon left>mdi-calendar-check</v-icon>
                        Licencias Recientes
                    </v-card-title>
                    <v-card-text class="px-0">
                        <v-list two-line>
                            <v-list-item
                                v-for="(item, index) in licenciasRecientes"
                                :key="index"
                            >
                                <v-list-item-avatar :color="item.color">
                                    <v-icon dark>{{ item.icon }}</v-icon>
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title>{{
                                        item.tipo
                                    }}</v-list-item-title>
                                    <v-list-item-subtitle>
                                        {{ item.agente }} - {{ item.fecha }}
                                    </v-list-item-subtitle>
                                </v-list-item-content>
                                <v-list-item-action>
                                    <v-chip
                                        :color="item.estadoColor"
                                        small
                                        text-color="white"
                                    >
                                        {{ item.estado }}
                                    </v-chip>
                                </v-list-item-action>
                            </v-list-item>
                        </v-list>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <!-- Tabla de licencias pendientes -->
        <v-row>
            <v-col cols="12">
                <tabla-masiva :habilitar-buscar="true"></tabla-masiva>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import TablaMasiva from '../components/licencia/TablaMasiva';

export default {
    name: 'Dashboard',
    components: {
        TablaMasiva,
    },
    data: function () {
        return {
            habilitarBuscar: true,
            deshabilitado: false,
            role: 'No seteado',
            licTitulo: 'Resumen de Licencias',
            titulo: 'Dashboard',
            // Datos para métricas
            licenciasTotales: 0,
            licenciasVisadas: 0,
            licenciasPendientes: 0,
            juntasMedicas: 0,
            // Datos para gráficos
            tiposLicenciaStats: [
                { tipo: 'Salud Ocupacional', cantidad: 45, color: 'primary' },
                { tipo: 'Anual Reglamentaria', cantidad: 32, color: 'success' },
                { tipo: 'Sin Goce', cantidad: 12, color: 'warning' },
                { tipo: 'Con Goce', cantidad: 28, color: 'error' },
                { tipo: 'Largo Tratamiento', cantidad: 15, color: 'info' },
                {
                    tipo: 'Corto Tratamiento',
                    cantidad: 37,
                    color: 'deep-purple',
                },
            ],
            licenciasRecientes: [
                {
                    tipo: 'Largo Tratamiento',
                    agente: 'Martínez, Juan',
                    fecha: '12/04/2023',
                    estado: 'Aprobada',
                    estadoColor: 'success',
                    color: 'primary',
                    icon: 'mdi-hospital',
                },
                {
                    tipo: 'Corto Tratamiento',
                    agente: 'García, Ana',
                    fecha: '10/04/2023',
                    estado: 'Pendiente',
                    estadoColor: 'warning',
                    color: 'info',
                    icon: 'mdi-medical-bag',
                },
                {
                    tipo: 'Anual Reglamentaria',
                    agente: 'López, Carlos',
                    fecha: '08/04/2023',
                    estado: 'Aprobada',
                    estadoColor: 'success',
                    color: 'green',
                    icon: 'mdi-calendar',
                },
                {
                    tipo: 'Familiar Enfermo',
                    agente: 'Fernández, María',
                    fecha: '05/04/2023',
                    estado: 'Rechazada',
                    estadoColor: 'error',
                    color: 'deep-orange',
                    icon: 'mdi-account-group',
                },
                {
                    tipo: 'ART',
                    agente: 'Rodríguez, Pedro',
                    fecha: '01/04/2023',
                    estado: 'Pendiente',
                    estadoColor: 'warning',
                    color: 'red',
                    icon: 'mdi-ambulance',
                },
            ],
        };
    },
    created() {
        console.log('agente usurio en el dashboar ', this.agenteUsuario);
        this.cargarDatosIniciales();
    },
    computed: {
        get_licencias() {
            return this.$store.getters['licencia/obtenerLicencias'];
        },
        agente() {
            return this.$store.getters['agente/agente'];
        },
        agenteUsuario() {
            return this.$store.getters['user/agente'];
        },
        agentes() {
            return this.$store.getters['agente/agentes'];
        },
    },
    methods: {
        habilitarBusqueda(evt) {
            this.habilitarBuscar = evt;
        },
        async cargarDatosIniciales() {
            try {
                // Inicializar carga de datos para métricas
                await this.$store.dispatch('licencia/fetchTipoLicencias');
                await this.cargarEstadisticas();
            } catch (error) {
                console.error('Error al cargar datos iniciales:', error);
            }
        },
        async cargarEstadisticas() {
            try {
                // Obtener datos para mostrar en métricas
                // En un caso real, estos datos vendrían de las APIs
                // Esta es una simulación con datos de prueba
                this.licenciasTotales = 122;
                this.licenciasVisadas = 89;
                this.licenciasPendientes = 33;
                this.juntasMedicas = 17;
            } catch (error) {
                console.error('Error al cargar estadísticas:', error);
            }
        },
    },
};
</script>

<style scoped>
.chart-container {
    position: relative;
    margin: auto;
}
</style>
