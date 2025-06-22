<template>
    <v-container fluid>
        <!-- Si el usuario es de Gerencia, mostrar el dashboard completo -->
        <template v-if="esGerencia">
            <v-row>
                <v-col cols="12">
                    <v-card class="mb-4">
                        <v-card-title class="headline primary white--text">
                            Sistema de Administración Recursos Humanos de Salud
                        </v-card-title>
                        <v-card-subtitle class="py-2">
                            Panel de Control Principal - Bienvenid@
                            {{ role_display }}
                        </v-card-subtitle>
                    </v-card>
                </v-col>
            </v-row>

            <!-- Métricas personales del usuario -->
            <v-row>
                <v-col cols="12">
                    <v-card
                        class="mb-4 pa-3"
                        outlined
                    >
                        <v-card-title class="subtitle-1 font-weight-bold">
                            Mis Indicadores Personales
                        </v-card-title>
                        <v-row>
                            <v-col
                                cols="12"
                                md="4"
                                sm="6"
                                v-for="(item, index) in misIndicadores"
                                :key="index"
                            >
                                <v-card
                                    flat
                                    class="pa-2 rounded-lg"
                                    :color="item.color + '15'"
                                >
                                    <v-row no-gutters>
                                        <v-col
                                            cols="auto"
                                            class="pr-3"
                                        >
                                            <v-avatar
                                                :color="item.color"
                                                size="48"
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
                                            <div class="title font-weight-bold">
                                                {{ item.valor }}
                                            </div>
                                        </v-col>
                                    </v-row>
                                </v-card>
                            </v-col>
                        </v-row>
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
                        <v-card-text style="height: 400px">
                            <licencias-chart
                                title="Licencias por Tipo"
                                :licenciasData="licenciasPorTipo"
                            />
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

            <!-- Comparativa anual de licencias -->
            <v-row>
                <v-col cols="12">
                    <licencias-comparativa-chart />
                </v-col>
            </v-row>

            <!-- Métricas personales del usuario -->
            <v-row>
                <v-col
                    cols="12"
                    md="6"
                >
                    <usuario-metricas-chart :usuarioId="usuarioId" />
                </v-col>
                <v-col
                    cols="12"
                    md="6"
                >
                    <v-card class="elevation-1">
                        <v-card-title class="headline">
                            Indicadores de Plantilla
                            <v-spacer></v-spacer>
                            <v-btn
                                icon
                                @click="reloadData"
                            >
                                <v-icon>mdi-refresh</v-icon>
                            </v-btn>
                        </v-card-title>
                        <v-card-text>
                            <v-row>
                                <v-col
                                    cols="12"
                                    sm="6"
                                    v-for="(
                                        item, index
                                    ) in indicadoresPlantilla"
                                    :key="index"
                                >
                                    <v-card
                                        flat
                                        class="pa-3 rounded-lg"
                                        :color="item.color + '15'"
                                    >
                                        <div class="d-flex align-center">
                                            <v-avatar
                                                :color="item.color"
                                                size="42"
                                                class="mr-3 elevation-1"
                                            >
                                                <v-icon dark>{{
                                                    item.icon
                                                }}</v-icon>
                                            </v-avatar>
                                            <div>
                                                <div
                                                    class="subtitle-1 font-weight-medium"
                                                >
                                                    {{ item.valor }}
                                                </div>
                                                <div
                                                    class="caption text--secondary"
                                                >
                                                    {{ item.titulo }}
                                                </div>
                                            </div>
                                        </div>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </template>

        <!-- Si el usuario NO es de Gerencia, mostrar solo la bienvenida y sus métricas personales -->
        <template v-else>
            <v-row>
                <v-col cols="12">
                    <v-card class="mb-4">
                        <v-card-title class="headline primary white--text">
                            Sistema de Administración Recursos Humanos de Salud
                        </v-card-title>
                        <v-card-subtitle class="m-4 py-2">
                            Bienvenid@ {{ role_display }}
                        </v-card-subtitle>
                    </v-card>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12">
                    <usuario-metricas-chart :usuarioId="usuarioId" />
                </v-col>
            </v-row>
        </template>
    </v-container>
</template>

<script>
import LicenciasChart from '../components/charts/LicenciasChart';
import LicenciasComparativaChart from '../components/charts/LicenciasComparativaChart';
import UsuarioMetricasChart from '../components/charts/UsuarioMetricasChart';

export default {
    name: 'Welcome',
    components: {
        LicenciasChart,
        LicenciasComparativaChart,
        UsuarioMetricasChart,
    },
    data() {
        return {
            // Datos para métricas
            licenciasTotales: 122,
            licenciasVisadas: 89,
            licenciasPendientes: 33,
            juntasMedicas: 17,
            usuarioId: 1,

            // Datos para gráficos
            licenciasPorTipo: [
                { tipo: 'Salud Ocupacional', cantidad: 45, color: '#1976D2' },
                { tipo: 'Anual Reglamentaria', cantidad: 32, color: '#4CAF50' },
                { tipo: 'Sin Goce', cantidad: 12, color: '#FF9800' },
                { tipo: 'Con Goce', cantidad: 28, color: '#F44336' },
                { tipo: 'Largo Tratamiento', cantidad: 15, color: '#00BCD4' },
                { tipo: 'Corto Tratamiento', cantidad: 37, color: '#673AB7' },
            ],

            // Datos métricas personales
            misIndicadores: [
                {
                    titulo: 'Mis licencias activas',
                    valor: '1',
                    color: 'blue',
                    icon: 'mdi-calendar-check',
                },
                {
                    titulo: 'Días disponibles vacaciones',
                    valor: '15',
                    color: 'green',
                    icon: 'mdi-beach',
                },
                {
                    titulo: 'Próxima licencia programada',
                    valor: '10/06/2023',
                    color: 'purple',
                    icon: 'mdi-calendar-plus',
                },
            ],

            // Datos indicadores plantilla
            indicadoresPlantilla: [
                {
                    titulo: 'Agentes activos',
                    valor: '342',
                    color: 'primary',
                    icon: 'mdi-account-multiple',
                },
                {
                    titulo: 'Licencias activas',
                    valor: '28',
                    color: 'amber',
                    icon: 'mdi-file-document-outline',
                },
                {
                    titulo: 'Promedio licencias/agente',
                    valor: '7.2 días',
                    color: 'teal',
                    icon: 'mdi-calculator',
                },
                {
                    titulo: 'Ausentismo mensual',
                    valor: '5.3%',
                    color: 'red',
                    icon: 'mdi-chart-timeline-variant',
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
    computed: {
        role_display() {
            return this.$store.getters['user/role_display'];
        },
        esGerencia() {
            // Verificar si el rol del usuario es de Gerencia
            const userRole = this.$store.getters['user/role'];
            return userRole === 'gerencia' || userRole === 'admin';
        },
    },
    created() {
        this.cargarDatosIniciales();
    },
    methods: {
        async cargarDatosIniciales() {
            try {
                // En una implementación real, aquí cargaríamos datos desde API
                // Por ahora, usamos datos de muestra
                console.log(
                    'Dashboard cargado para el usuario con rol:',
                    this.role_display,
                );

                // También podríamos cargar los datos del usuario actual
                // this.usuarioId = this.$store.getters['user/id'];
            } catch (error) {
                console.error('Error al cargar datos iniciales:', error);
            }
        },
        reloadData() {
            this.cargarDatosIniciales();
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
