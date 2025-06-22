<template>
    <div>
        <div class="col-md-12 shadow-lg p-4 mb-4 bg-white">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white font-weight-bold">
                    <h4>
                        <i class="fas fa-address-card"></i>
                        {{titulo}}
                    </h4>
                </div>
                <div class="card-body">
                    <v-row>
                        <v-col>
                            <v-text-field
                                v-model="agente.documento"
                                :counter="10"
                                label="Documento"
                                :disabled="true"
                                required
                            ></v-text-field>
                            <v-text-field
                                v-model="agente.nombre"
                                label="Nombre"
                                disabled
                            ></v-text-field>
                            <v-text-field
                                v-model="agente.apellido"
                                label="Apellido"
                                disabled
                            ></v-text-field>
                            <v-text-field
                                :value="fechaNacimiento"
                                label="Fecha de Nacimiento"
                                disabled
                            ></v-text-field>

                        </v-col>
                    </v-row>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TarjetaUser",
        created() {
            console.log('agente usuario', this.agente)
        },
        props: {titulo: String, deshabilitado: Boolean, agente: Object},
        computed: {
            fechaNacimiento() {
                var cadena = String(this.agente.fnacimiento);
                return new Date(cadena.slice(0, 4), cadena.slice(5, 7) - 1, cadena.slice(8, 10)).toLocaleDateString();
            },
        }
    }
</script>

<style scoped>

</style>
