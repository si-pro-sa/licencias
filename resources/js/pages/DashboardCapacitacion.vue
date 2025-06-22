<template>
<div>
    <v-row>
        <v-col>
            <tabla-masiva-capacitacion key="tablaMasivaCapacitacion" id="tablaMasivaCapacitacion" ref="tablaMasivaCapacitacion" :habilitar-buscar="true"></tabla-masiva-capacitacion>
        </v-col>
    </v-row>
</div>
</template>

<script>
    import TablaMasivaCapacitacion from "../components/licencia/TablaMasivaCapacitacion.vue";

    export default {
        name: "Dashboard",
        components: {
            TablaMasivaCapacitacion
        },
        data: function () {
            return {
                habilitarBuscar: true,
                deshabilitado: false,
                role: "No seteado",
                licTitulo: "Resumen de Licencias",
                titulo: "Dashboard",
            };
        },
        created() {
            console.log('agente usurio en el dashboard ', this.agenteUsuario);
        },
        computed: {
            get_licencias() {
                return this.$store.getters["licencia/obtenerLicencias"];
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
        }
    }
</script>

<style scoped>

</style>
