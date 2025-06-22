<template>
<div>
    <v-row>
        <v-col>
            <tabla-masiva :habilitar-buscar="true"></tabla-masiva>
        </v-col>
    </v-row>
</div>
</template>

<script>
    import TablaMasiva from "../components/licencia/TablaMasiva";

    export default {
        name: "Dashboard",
        components: {
            TablaMasiva
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
            console.log('agente usurio en el dashboar ', this.agenteUsuario);
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
