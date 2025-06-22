<template>
    <v-container
        fluid
        class="fill-height"
    >
        <v-row>
            <v-col>
                <h3 class="display-1">
                    LICENCIA: {{ getTipoLicencia.descripcion.toUpperCase() }}
                </h3>
                <tarjeta-agente
                    :titulo="titulo"
                    :deshabilitado="deshabilitado"
                    @hayAgente="habilitarBusqueda"
                ></tarjeta-agente>
            </v-col>
        </v-row>

        <v-row
            v-if="!habilitarBuscar"
            class="py-3"
        >
            <v-col>
                <tabla-ultima-licencias
                    v-if="!habilitarBuscar"
                    :idagente="hayAgente"
                    :inasistencia="tipoLicencia >= 33 && tipoLicencia < 35"
                    :pagos="tipoLicencia === 35 || tipoLicencia === 36"
                ></tabla-ultima-licencias>
            </v-col>
        </v-row>

        <v-row>
            <transition
                name="component-fade"
                mode="out-in"
            >
                <component
                    v-bind:is="view"
                    @addLicencia="cambiarComponente"
                    :idlicencia="idlicencia"
                    :habilitarBuscar="habilitarBuscar"
                    :visar="visar"
                    :role="role"
                    :ver="ver"
                    :tipoLicencia="tipoLicencia"
                ></component>
            </transition>
        </v-row>
    </v-container>
</template>

<script>
import TarjetaAgente from '../utils/TarjetaAgente.vue';
import LicenciaCreate from './LicenciaCreate.vue';
import LicenciaView from './LicenciaView.vue';
import TablaLicencia from './TablaLicencia.vue';
import TablaUltimaLicencias from '../utils/TablaUltimaLicencias';
//Todo crear una mejor propagacion para la tabla ultimas licencias
export default {
    name: 'LicenciaIndex',
    components: {
        TablaUltimaLicencias,
        TarjetaAgente,
        LicenciaCreate,
        TablaLicencia,
        LicenciaView,
    },
    data: function () {
        return {
            habilitarBuscar: true,
            deshabilitado: false,
            ver: false,
            role: 'No seteado',
            licTitulo: 'Resumen de Licencias',
            titulo: 'Listado de Licencias',
            view: 'TablaLicencia',
            visar: 0,
            idlicencia: 0,
            tipoLicencia: 0,
        };
    },
    created() {
        this.tipoLicencia = parseInt(this.$route.params['tipoLicencia']);
        if (this.tipoLicencia >= 1) {
            this.$store.state.user.tipoLicencia =
                this.tipoLicencia !== 0 ? this.tipoLicencia : 0;
        } else {
            this.$router.push({ path: 'home' });
        }
    },
    computed: {
        get_licencias() {
            return this.$store.getters['licencia/obtenerLicencias'];
        },
        get_agente() {
            return this.$store.state.agente.agente.documento;
        },
        hayAgente() {
            return this.$store.getters['agente/agente'].idagente;
        },
        getTipoLicencia() {
            return this.$store.getters['licencia/tipoLicencia'](
                this.tipoLicencia,
            );
        },
    },
    mounted() {
        // Set the initial number of items
    },
    methods: {
        habilitarBusqueda(evt) {
            this.habilitarBuscar = evt;
        },
        cambiarComponente(value) {
            if (this.view === 'TablaLicencia') {
                this.deshabilitado = true;
                switch (value.visar) {
                    case 6:
                        console.log('licencia view');
                        this.view = 'LicenciaView';
                        this.titulo = 'Revisar Licencia';
                        this.esCreacion = false;
                        this.idlicencia = value.licencia;
                        this.visar = value.visar;
                        break;
                    case 1:
                        console.log('licencia create');
                        this.view = 'LicenciaCreate';
                        this.titulo = 'Presentacion de Licencia';
                        this.esCreacion = false;
                        this.idlicencia = value.licencia;
                        this.visar = value.visar;
                        break;
                    default:
                        console.log('licencia create');
                        this.view = 'LicenciaCreate';
                        this.titulo = 'Presentacion de Licencia';
                        this.esCreacion = false;
                        this.idlicencia = value.licencia;
                        this.visar = value.visar;
                        break;
                }
            } else {
                this.view = 'TablaLicencia';
                this.titulo = 'Listado de Licencias';
                this.deshabilitado = false;
            }
        },
    },
};
</script>

<style scoped>
.component-fade-enter-active,
.component-fade-leave-active {
    transition: opacity 0.3s ease;
}

.component-fade-enter, .component-fade-leave-to
    /* .component-fade-leave-active below version 2.1.8 */ {
    opacity: 0;
}
</style>
