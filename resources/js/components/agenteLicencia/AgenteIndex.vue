<template>
    <v-container fluid>
        <v-row>
            <v-col>
                <tarjeta-agente :titulo="titulo" :deshabilitado="false" @hayAgente="habilitarBusqueda"></tarjeta-agente>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                
            </v-col>
        </v-row>
        <v-row>
            <v-col><tarjeta-cantidad-licencias></tarjeta-cantidad-licencias></v-col>
            <v-col><tarjeta-dias-licencias></tarjeta-dias-licencias></v-col>
            <v-col></v-col>
        </v-row>
        <v-row>

        </v-row>
        <v-row>

        </v-row>
        <v-row>

        </v-row>
    </v-container>
</template>

<script>
    import TarjetaAgente from "./TarjetaAgente";
    import TarjetaFiltroFechaEntre from "./TarjetaFiltroFechaEntre";
    import TarjetaCalendarioAgente from "./TarjetaCalendarioAgente";
    import TarjetaCantidadLicencias from "./TarjetaCantidadLicencias";
    import TarjetaDiasLicencias from "./TarjetaDiasLicencias";
    export default {
        name: "AgenteIndex",
        components: {TarjetaAgente,TarjetaCantidadLicencias,TarjetaDiasLicencias,TarjetaFiltroFechaEntre,TarjetaCalendarioAgente},
        data: function(){
            return {
                titulo: "Resumen Agente",
                habilitarBuscar: true
            };
        },
        methods:{
            habilitarBusqueda(evt){
                this.habilitarBuscar = evt;
                //this.buscarSanciones();
            },
        }
    }
</script>

<style scoped>

</style>
