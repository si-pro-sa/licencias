<template>
<div>
    <v-data-table
        :headers="headers"
        :items="inasistencia ? inasistencias : licencias"
        :items-per-page="5"
        class="elevation-1"
        item-key="idlicencia"
        sort-by="idlicencia"
        :item-class="row_classes"
    >
        <template v-slot:item.primer_visado="{ item }">{{
            item.primer_visado === true ? "Si" : (item.primer_visado === false ? "No" : 'No ha sido visada')
            }}
        </template>
        <template v-slot:item.segundo_visado="{ item }">{{
            item.segundo_visado === true ? "Si" : (item.segundo_visado === false ? "No" : 'No ha sido visada')
            }}
        </template>
        <template v-slot:item.cuarta_visado="{ item }">{{ item.cuarta_visado ? "Si" : "No" }}</template>
        <template v-slot:item.dias="{ item }">{{
                item.dias === 0 && ((item.primer_visado === false && item.segundo_visado === null) || (item.segundo_visado === false)) ? 'No Autorizado' : item.dias
            }}
        </template>
        <template v-slot:item.fecha_pedido_inicio="{ item }">{{ item.fecha_pedido_inicio | fechaAcomodada }}
        </template>
        <template v-slot:item.fecha_pedido_final="{ item }">{{ item.fecha_pedido_final | fechaAcomodada }}
        </template>
        <template v-slot:item.fecha_efectiva_inicio="{ item }">{{ item.fecha_efectiva_inicio | fechaAcomodada}}
        </template>
        <template v-slot:item.fecha_efectiva_final="{ item }">{{ item.fecha_efectiva_final | fechaAcomodada }}
        </template>
        <template v-slot:item.fecha_interrupcion_inicio="{ item }">{{ item.fecha_interrupcion_inicio |
            fechaAcomodada }}
        </template>
        <template v-slot:top>
            <v-toolbar flat>
                <v-toolbar-title>Ultimas Licencias Pedidas</v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
                <v-spacer></v-spacer>
            </v-toolbar>
        </template>

    </v-data-table>
</div>
</template>

<script>

    export default {
        name: "TablaUltimaLicencias",
        props: {
            idagente: Number,
            inasistencia: Boolean,
            pagos: {
                type: Boolean,
                default: false,
                required: false
            }
        },
        data: () => ({interval: 0}),
        watch: {
            idagente: function (val, oldVal) {
                console.log('cambia idagente ', val);
                console.log('cambia idagente ', oldVal);

                this.getLicencias();
            }
        },
        mounted() {
            this.getLicencias();
            this.interval = setInterval(() => this.getLicencias(), 50000);
        },
        methods: {
            row_classes(item) {
                if (item.primer_visado === true && item.segundo_visado === null) {
                    return "advertencia";
                } else if (item.segundo_visado === true) {
                    return "visado";
                } else if (item.primer_visado === false || item.segundo_visado === false) {
                    return "desvisado";
                }
            },
            async getLicencias() {
                this.$store.dispatch('licencia/getLicenciasTotales', {idagente: this.idagente});
            },

        },
        computed: {
            licencias() {
                if(this.pagos){
                    return this.$store.getters['licencia/licencias'].filter(el => {
                        if(el.codigo === 35 || el.codigo === 36){
                            return el
                        }
                    });
                }
                return this.$store.getters['licencia/licencias'];
            },
            inasistencias() {
                return this.$store.getters['licencia/inasistencias'];
            },
            headers() {
                return [
                    {
                        value: "dias",
                        text: "Dias de Licencia",
                        sortable: true,
                    },
                    {
                        value: "idlicencia",
                        text: "Numero de Licencia",
                        sortable: true,
                    },
                    {
                        value: "descripcion",
                        text: "Tipo de Licencia",
                        sortable: true,
                    },
                    {
                        value: "fecha_pedido_inicio",
                        text: "Fecha del Inicio del Pedido",
                        sortable: true,
                    },
                    {
                        value: "fecha_pedido_final",
                        text: "Fecha Pedido Final",
                        sortable: true,
                    },
                    {
                        value: "primer_visado",
                        text: "Primer Visado",
                        sortable: true
                    },
                    {
                        value: "fecha_efectiva_inicio",
                        text: "Fecha Efectiva Inicio",
                        sortable: true,
                    },
                    {
                        value: "fecha_efectiva_final",
                        text: "Fecha Efectiva Final",
                        sortable: true,
                    },
                    {
                        value: "segundo_visado",
                        text: "Segundo Visado",
                        sortable: true
                    },
                    {
                        value: "cuarta_visado",
                        text: "Interrumpido",
                        sortable: true,

                    },
                    {
                        value: "fecha_interrupcion_inicio",
                        text: "Fecha Interrupcion",
                        sortable: true,
                    },
                ];
            }
        },
        beforeDestroy() {
            clearInterval(this.interval);
        }
    }
</script>

<style scoped>
    .advertencia {
        background-color: #f7f5dd;
    }

    .desvisado {
        background-color: #e2979c;
    }

    .visado {
        background-color: #9bdeac;
    }
</style>
