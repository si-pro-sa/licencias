<template>
    <v-container
        id="contenedor"
        class="ml-9"
        fluid
    >
        <v-row class="ml-9">
            <v-col>
                <tarjeta-agente
                    :titulo="titulo"
                    :deshabilitado="false"
                    @hayAgente="habilitarBusqueda"
                ></tarjeta-agente>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-data-table
                    v-model="selected"
                    :headers="headers"
                    :items="sanciones"
                    show-select
                    item-key="idsancion"
                    sort-by="idsancion"
                >
                    <template v-slot:item.created_at="{ item }"
                        >{{ item.created_at | fechaAcomodada }}
                    </template>
                    <template v-slot:item.fecha_inicio="{ item }"
                        >{{ item.fecha_inicio | fechaAcomodada }}
                    </template>
                    <template v-slot:item.fecha_final="{ item }"
                        >{{ item.fecha_final | fechaAcomodada }}
                    </template>
                    <template v-slot:item.documento="{ item }"></template>
                    <template v-slot:item.codigo_nombre="{ item }"></template>

                    <template v-slot:header.documento="{ header }"></template>
                    <template
                        v-slot:header.codigo_nombre="{ header }"
                    ></template>
                    <template v-slot:top>
                        <v-toolbar
                            color="white"
                            flat
                        >
                            <v-toolbar-title>Sanciones</v-toolbar-title>
                            <v-divider
                                class="mx-4"
                                inset
                                vertical
                            ></v-divider>
                            <v-spacer></v-spacer>

                            <v-btn
                                v-if="can('exportarPDF-consultaSancion')"
                                @change="actualizarTabla"
                                @click="exportPDF"
                                class="m-2"
                                color="warning"
                                dark
                            >
                                <i class="far fa-file-pdf mr-2"></i>Exportar PDF
                            </v-btn>

                            <download-excel
                                v-if="can('exportarExcel-consultaSancion')"
                                class="m-2 v-btn v-btn--contained theme--light v-size--default yellow darken-5"
                                :data="selected_sanciones"
                                :fields="json_fields"
                                name="filename.xls"
                                @change="actualizarTabla"
                            >
                                <v-icon
                                    color="dark"
                                    left
                                    >mdi-file-excel-outline
                                </v-icon>
                                Exportar Excel
                            </download-excel>
                        </v-toolbar>
                    </template>
                    <template v-slot:no-data>No hay datos cargados</template>
                </v-data-table>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import TarjetaAgente from '../utils/TarjetaAgente';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import exportMixin from '../../mixins/exportMixin.js';
import _ from 'lodash';

export default {
    name: 'ConsultaSanciones.vue',
    components: {
        TarjetaAgente,
    },
    mixins: [exportMixin],
    data: (vm) => ({
        habilitarBuscar: false,
        titulo: 'Consulta de Sancion',
        json_fields: {
            Efector: 'efector',
            Servicio: 'codigo_nombre',
            Documento: 'documento',
            'Apellido y Nombre': 'apellido_nombre',
            Expediente: 'expediente',
            Resolucion: 'resolucion',
            Causal: 'reseña',
            Dictamen: {
                field: 'conclusion',
                callback: (value) => {
                    return value;
                },
            },
            'Resolucion de conclusion': {
                field: 'acuerdo',
                callback: (value) => {
                    return value;
                },
            },
            'Usuario responsable': {
                field: 'nombreusuario',
                callback: (value) => {
                    return value;
                },
            },
            'Fecha Inicio Sancion': {
                field: 'fecha_inicio',
                callback: (value) => {
                    return new moment(value.slice(0, 10)).format('DD/MM/YYYY');
                },
            },
            'Fecha Fin Sancion': {
                field: 'fecha_final',
                callback: (value) => {
                    return new moment(value.slice(0, 10)).format('DD/MM/YYYY');
                },
            },
            'Fecha de creacion': {
                field: 'created_at',
                callback: (value) => {
                    return new moment(value.slice(0, 10)).format('DD/MM/YYYY');
                },
            },
        },
        json_meta: [
            [
                {
                    key: 'charset',
                    value: 'utf-8',
                },
            ],
        ],
        documento: 0,
        loading: false,
        selected: [],
        error: false,
        efector: '',
        efectorSelected: {
            label: 'DIRECCION GENERAL DE PROGRAMAS INTEGRADOS DE SALUD',
            value: '204',
        },
        efectores: [
            {
                label: 'DIRECCION GENERAL DE PROGRAMAS INTEGRADOS DE SALUD',
                value: '204',
            },
        ],
        iddependencia: 0,
    }),
    methods: {
        exportPDF() {
            let data = this.preprocessDataset('ConsultaSancion', this.selected);
            this.exportPDFMixin(
                ['Consulta Sanciones'],
                [this.headings.Sanciones],
                [data],
                true,
                'ConsultaSancion',
                this.agenteBuscado,
            );
            this.selected = [];
        },
        habilitarBusqueda(evt) {
            this.selected = [];
            this.habilitarBuscar = !evt;
            if (!evt === true) {
                let obj = { idagente: this.agenteBuscado.idagente };
                this.buscarSanciones(obj);
            }
        },
        can(cadena) {
            return _.findIndex(this.permisos, ['name', cadena]) >= 0;
        },
        actualizarTabla() {
            this.buscarSanciones(this.efectorSelected.value);
        },
        async buscarOrganismos() {
            await this.$store.dispatch('antiguedad/getOrganismos');
        },
        async buscarSanciones(obj) {
            this.selected = [];
            this.loading = true;
            await this.$store
                .dispatch('sancion/fetch', {
                    idagente: obj.idagente,
                })
                .then((res) => {
                    this.loading = false;
                });
        },
    },
    watch: {
        efectorSelected: function (newValue, oldValue) {
            this.selected = [];
        },
        dialog(val) {
            val || this.close();
        },
    },
    created() {
        this.$store.dispatch('sancion/getDefaultState');
    },
    computed: {
        permisos() {
            return this.$store.getters['user/permisos'];
        },
        selected_sanciones() {
            let aux = _.each(this.selected, (row) => {
                row['efector'] = this.agenteBuscado.efector;
            });
            return aux;
        },
        user() {
            return this.$store.getters['user/user'].nombreusuario;
        },
        role_display() {
            return this.$store.getters['user/role_display'];
        },
        agente() {
            return this.$store.getters['user/agente'];
        },
        agenteBuscado() {
            return this.$store.getters['agente/agente'];
        },
        sanciones() {
            return this.$store.getters['sancion/sanciones'];
        },
        organismos() {
            return this.$store.getters['antiguedad/organismos'];
        },
        headers() {
            return [
                {
                    text: 'Documento',
                    value: 'documento',
                    sortable: false,
                    class: 'display:none',
                },
                {
                    text: '',
                    value: 'codigo_nombre',
                    sortable: false,
                    class: 'display:none',
                },
                {
                    text: 'EXPEDIENTE',
                    value: 'expediente',
                    filter: (value) => {
                        if (!this.expediente) return true;
                        return value.toString().indexOf(this.expediente) !== -1;
                    },
                },
                {
                    text: 'RESOLUCION DE INST. SUMARIO',
                    value: 'resolucion',
                    filter: (value) => {
                        if (!this.resolucion) return true;
                        return (
                            value
                                .toString()
                                .indexOf(this.resolucion.toString()) !== -1
                        );
                    },
                },
                {
                    text: 'CAUSAL',
                    value: 'reseña',
                    filter: (value) => {
                        if (!this.reseña) return true;
                        return (
                            value.toString().indexOf(this.reseña.toString()) !==
                            -1
                        );
                    },
                },
                {
                    text: 'DICTAMEN DE CONCLUSION',
                    value: 'conclusion',
                    filter: (value) => {
                        if (!this.conclusion) return true;
                        return (
                            value
                                .toString()
                                .indexOf(this.conclusion.toString()) !== -1
                        );
                    },
                },
                {
                    text: 'RESOLUCION DE CONCLUSION',
                    value: 'acuerdo',
                    filter: (value) => {
                        if (!this.acuerdo) return true;
                        return value.toString().indexOf(this.acuerdo) !== -1;
                    },
                },
                {
                    text: 'Usuario Responsable',
                    value: 'nombreusuario',
                    filter: (value) => {
                        if (!this.nombreusuario) return true;
                        return (
                            value.toString().indexOf(this.nombreusuario) !== -1
                        );
                    },
                },
                {
                    text: 'FECHA INICIO SANCION',
                    value: 'fecha_inicio',
                    filter: (value) => {
                        if (!this.fecha_inicio) return true;
                        value = moment(value, 'YYYY-MM-DD').format(
                            'DD/MM/YYYY',
                        );
                        return (
                            value.toString().indexOf(this.fecha_inicio) !== -1
                        );
                    },
                },
                {
                    text: 'FECHA FIN SANCION',
                    value: 'fecha_final',
                    filter: (value) => {
                        if (!this.fecha_final) return true;
                        value = moment(value, 'YYYY-MM-DD').format(
                            'DD/MM/YYYY',
                        );
                        return (
                            value.toString().indexOf(this.fecha_final) !== -1
                        );
                    },
                },
                {
                    text: 'Fecha de Creacion de la Sancion',
                    value: 'created_at',
                    filter: (value) => {
                        if (!this.created_at) return true;
                        value = moment(value, 'YYYY-MM-DD').format(
                            'DD/MM/YYYY',
                        );
                        return value.toString().indexOf(this.created_at) !== -1;
                    },
                },
            ];
        },
        name() {
            return this.data;
        },
    },
};
</script>

<style scoped>
#contenedor {
    max-width: 1600px;
}

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
