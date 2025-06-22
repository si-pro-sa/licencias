<template>
    <v-container class="ml-9" fluid fill-height>
        <v-row class="justify-center align-center">
            <v-col cols="12">
                <v-card class="my-11 mx-auto" width="700">
                    <v-card-title dark class="headline">
                        Importar datos de capacitaciones registradas
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col>
                                <v-file-input
                                    ref="fileUpload"
                                    show-size
                                    counter
                                    accept=".xls, .xlsx"
                                    v-model="file"
                                    v-on:change="handleFileUpload()"
                                    prepend-icon="mdi-file-excel"
                                    label="Buscar archivo"
                                ></v-file-input>
                            </v-col>
                        </v-row>

                        <v-row
                            class="align-center"
                            v-show="can('importar-capacitaciones')"
                        >
                            <v-col cols="12">
                                <v-btn
                                    :loading="loading"
                                    :disabled="loading"
                                    color="blue-grey"
                                    class="ma-2 white--text"
                                    @click="EventSubir"
                                >
                                    Subir Archivo
                                    <v-icon right dark>mdi-cloud-upload</v-icon>
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions>
                        <v-alert dense border="left" type="warning">
                            Recuerde que el archivo no debe tener cabecera en
                            las columnas y su orden debe ser
                            <strong
                                >Documento - Años Trabajados - Año a acreditar
                                la antiguedad</strong
                            >
                        </v-alert>
                    </v-card-actions>
                    <v-alert
                        :value="fail"
                        transition="scale-transition"
                        type="error"
                    >
                        Archivo no ha sido ingresado o con formato incorrecto
                    </v-alert>
                    <v-alert
                        :value="success"
                        transition="scale-transition"
                        type="success"
                    >
                        Archivo importado
                    </v-alert>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import _ from 'lodash'

export default {
    name: 'ImportarapacitacionIndex',
    data() {
        return {
            loader: null,
            loading: false,
            file: null,
            loading3: false,
            fail: false,
            success: false,
            vigente: false
        }
    },

    methods: {
        can(cadena) {
            var permisos = this.$store.getters['user/permisos']
            return _.findIndex(permisos, ['name', cadena]) >= 0 ? true : false
        },
        fallo() {
            this.loading = false
            this.fail = true
        },
        apagarLoading() {
            console.log('apagar')
            this.loading = false
            this.success = true
        },
        EventSubir() {
            this.loading = true
            this.fail = false
            this.success = false
            let formData = new FormData()
            formData.append('file', this.file)
            formData.append('vigente', this.vigente)
            window.axios
                .post('api/import-excel-capacitaciones', formData)
                .then(res => {
                    this.apagarLoading()

                    console.log('SUCCESS!!')
                })
                .catch(err => {
                    console.log('FAILURE!! ', err)
                    this.fallo()
                })
        },

        handleFileUpload() {
            console.log(this.$refs.fileUpload)
        }
    }
}
</script>

<style scoped lang="scss">
.custom-loader {
    animation: loader 1s infinite;
    display: flex;
}

@-moz-keyframes loader {
    from {
        transform: rotate(0);
    }
    to {
        transform: rotate(360deg);
    }
}

@-webkit-keyframes loader {
    from {
        transform: rotate(0);
    }
    to {
        transform: rotate(360deg);
    }
}

@-o-keyframes loader {
    from {
        transform: rotate(0);
    }
    to {
        transform: rotate(360deg);
    }
}

@keyframes loader {
    from {
        transform: rotate(0);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
