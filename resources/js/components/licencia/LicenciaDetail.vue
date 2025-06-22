<template>
    <v-card>
        <v-card-title>{{ licencia.nombre }}</v-card-title>
        <v-card-text>
            <p><strong>Fecha de inicio:</strong> {{ licencia.fechaInicio }}</p>
            <p><strong>Fecha de fin:</strong> {{ licencia.fechaFin }}</p>
            <p><strong>Estado:</strong> {{ licencia.estado }}</p>
            <p><strong>Descripción:</strong> {{ licencia.descripcion }}</p>
        </v-card-text>
    </v-card>
</template>

<script>
export default {
    data() {
        return {
            licencia: {}
        };
    },
    methods: {
        fetchLicenciaData(licenciaId) {
            // Fetch the data for the specified `licenciaId`
            // and return it
        }
    },
    mounted() {
        // Assuming you're using Vue Router to pass the `licenciaId` parameter
        const licenciaId = this.$route.params.licenciaId;

        // Fetch the data for the specified `licenciaId`
        // Replace `fetchLicenciaData` with your own function to fetch the data
        this.licencia = fetchLicenciaData(licenciaId);
    }
};
</script>
