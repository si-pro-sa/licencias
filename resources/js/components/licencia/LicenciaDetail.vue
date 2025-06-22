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
            licencia: {},
            loading: false,
        };
    },
    methods: {
        async fetchLicenciaData(licenciaId) {
            this.loading = true;
            try {
                // Obtener licencia desde el store
                await this.$store.dispatch('licencia/getLicencia', licenciaId);
                // Asignar la licencia del store a la data local
                this.licencia = this.$store.getters['licencia/licencia'] || {};
            } catch (error) {
                console.error('Error al cargar la licencia:', error);
                this.$store.commit('app/SET_SNACKBAR', {
                    message: 'Error al cargar la licencia: ' + error.message,
                    color: 'error',
                });
            } finally {
                this.loading = false;
            }
        },
    },
    mounted() {
        // Obtener el ID de la licencia de los parámetros de la ruta
        const licenciaId = this.$route.params.licenciaId;

        if (licenciaId) {
            // Llamar al método del componente, no como función global
            this.fetchLicenciaData(licenciaId);
        } else {
            console.error('No se proporcionó un ID de licencia');
            this.$store.commit('app/SET_SNACKBAR', {
                message: 'No se proporcionó un ID de licencia',
                color: 'error',
            });
        }
    },
};
</script>
