<template>
    <v-data-table
        :headers="headers"
        :items="personas"
        :items-per-page="5"
        class="elevation-1"
    >
        <template v-slot:item.idtipoParentesco="{ item }">{{ item.idtipoParentesco | parentescoConv }}</template>
        <template v-slot:item.fecha_nacimiento="{ item }">{{ item.fecha_nacimiento | fechaAcomodada }}</template>
        <template v-slot:item.discapacidad="{ item }">
            <v-chip :color="getColor(item.discapacidad)" dark>{{ item.discapacidad ? "Si" : "No" }}</v-chip>
        </template>
    </v-data-table>
</template>

<script>
    import _ from "lodash";

    export default {
        filters: {
            parentescoConv: function (value) {
                switch (value) {
                    case 0:
                        return "Ninguno";
                        break;
                    case 1:
                        return "Padre";
                        break;
                    case 2:
                        return "Madre";
                        break;
                    case 3:
                        return "Suegro";
                        break;
                    case 4:
                        return "Suegra";
                        break;
                    case 5:
                        return "Hijo";
                        break;
                    case 6:
                        return "Hija";
                        break;
                    case 7:
                        return "Yerno";
                        break;
                    case 8:
                        return "Nuera";
                        break;
                    case 9:
                        return "Abuelo";
                        break;
                    case 10:
                        return "Abuela";
                        break;
                    case 11:
                        return "Nieto";
                        break;
                    case 12:
                        return "Nieta";
                        break;
                    case 13:
                        return "Hermano";
                        break;
                    case 14:
                        return "Hermana";
                        break;
                    case 15:
                        return "Cuñado";
                        break;
                    case 16:
                        return "Cuñada";
                        break;
                    case 17:
                        return "Primo";
                        break;
                    case 18:
                        return "Prima";
                        break;
                    case 19:
                        return "Tio";
                        break;
                    case 20:
                        return "Tia";
                        break;
                    case 21:
                        return "Sobrino";
                        break;
                    case 22:
                        return "Sobrina";
                        break;
                    case 23:
                        return "Biznieto";
                        break;
                    case 24:
                        return "Biznieta";
                        break;
                    case 25:
                        return "Bisabuelo";
                        break;
                    case 26:
                        return "Bisabuela";
                        break;
                    case 27:
                        return "Conyuge";
                        break;
                }
            }
        },
        name: "TablaFamiliarActivo",
        created() {
            this.getPersonasActivas();
            this.interval = setInterval(() => this.getPersonasActivas(), 50000);
        },
        data () {
            return {
               totalRows:0,
            }
        },
        methods: {
            getColor(value) {
                if (value === true) return 'green'
                else return 'red'
            },
            async getPersonasActivas() {
                await this.$store.dispatch(
                    "grupo/getPersonasActivas",
                    this.$store.state.agente.agente.idagente
                );
                this.totalRows = this.$store.getters["grupo/obtenerPersonasActivas"].length;

            },

        },
        computed:{
            personas(){
              return this.$store.getters["grupo/obtenerPersonasActivas"];
            },
            headers() {
                return [
                    {text: 'Documento', value: 'documento'},
                    {text: 'Nombre', value: 'nombre'},
                    {text: 'Apellido', value: 'apellido'},
                    {text: 'Fecha de Nacimiento', value: 'fecha_nacimiento'},
                    {
                        text: 'Discapacidad Presente', value: 'discapacidad',
                        sortable: true,
                        filter: value => {
                            if (!this.discapacidad) return true;
                            return value ? "Si" : "No";
                        }
                    },
                    {
                        text: 'Parentesco',
                        value: 'idtipoParentesco',
                        sortable: true,
                        filter: value => {
                            if (!this.idtipoParentesco) return true;
                            switch (value) {
                                case 0:
                                    return "Ninguno";
                                    break;
                                case 1:
                                    return "Padre";
                                    break;
                                case 2:
                                    return "Madre";
                                    break;
                                case 3:
                                    return "Suegro";
                                    break;
                                case 4:
                                    return "Suegra";
                                    break;
                                case 5:
                                    return "Hijo";
                                    break;
                                case 6:
                                    return "Hija";
                                    break;
                                case 7:
                                    return "Yerno";
                                    break;
                                case 8:
                                    return "Nuera";
                                    break;
                                case 9:
                                    return "Abuelo";
                                    break;
                                case 10:
                                    return "Abuela";
                                    break;
                                case 11:
                                    return "Nieto";
                                    break;
                                case 12:
                                    return "Nieta";
                                    break;
                                case 13:
                                    return "Hermano";
                                    break;
                                case 14:
                                    return "Hermana";
                                    break;
                                case 15:
                                    return "Cuñado";
                                    break;
                                case 16:
                                    return "Cuñada";
                                    break;
                                case 17:
                                    return "Primo";
                                    break;
                                case 18:
                                    return "Prima";
                                    break;
                                case 19:
                                    return "Tio";
                                    break;
                                case 20:
                                    return "Tia";
                                    break;
                                case 21:
                                    return "Sobrino";
                                    break;
                                case 22:
                                    return "Sobrina";
                                    break;
                                case 23:
                                    return "Biznieto";
                                    break;
                                case 24:
                                    return "Biznieta";
                                    break;
                                case 25:
                                    return "Bisabuelo";
                                    break;
                                case 26:
                                    return "Bisabuela";
                                    break;
                                case 27:
                                    return "Conyuge";
                                    break;
                            }
                        }
                    },
                ];
            },
        }
    }
</script>

<style scoped>

</style>
