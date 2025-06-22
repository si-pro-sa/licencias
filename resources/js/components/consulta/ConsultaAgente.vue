<template>
    <v-container
        id="contenedor"
        class="ml-9"
        fluid
    >
        <v-row>
            <v-col cols="12">
                <tarjeta-agente
                    titulo="titulo"
                    :deshabilitado="false"
                    @hayAgente="habilitarBusqueda"
                ></tarjeta-agente>
            </v-col>
        </v-row>
        <v-row
            class="ml-9"
            v-if="habilitarTablas"
        >
            <v-col>
                <v-card>
                    <v-card-title
                        class="font-weight-regular blue-grey white--text headline"
                    >
                        <span class="white--text">Licencias</span>
                    </v-card-title>
                    <v-spacer></v-spacer>
                    <v-card-subtitle class="pa-5">
                        <v-toolbar
                            color="white"
                            flat
                        >
                            <v-toolbar-title>Acciones</v-toolbar-title>
                            <v-divider
                                class="mx-4"
                                inset
                                vertical
                            ></v-divider>
                            <v-spacer></v-spacer>
                            <v-btn-toggle rounded>
                                <button
                                    v-if="can('exportarExcel-consultaAgente')"
                                    class="m-2 v-btn v-btn--contained theme--light v-size--default yellow darken-5"
                                >
                                    <download-excel
                                        :data="selectedExcel"
                                        :fields="json_fields"
                                        name="filename.xls"
                                    >
                                        <v-icon
                                            color="dark"
                                            left
                                            >mdi-file-excel-outline
                                        </v-icon>
                                        Exportar Excel
                                    </download-excel>
                                </button>
                                <v-btn
                                    v-if="can('exportarPDF-consultaAgente')"
                                    @click="exportPDF"
                                    class="m-2 v-btn v-btn--contained theme--light v-size--default yellow darken-5"
                                >
                                    <v-icon left>mdi-file-pdf-outline</v-icon>
                                    Exportar PDF
                                </v-btn>
                            </v-btn-toggle>
                        </v-toolbar>
                    </v-card-subtitle>
                    <v-spacer></v-spacer>
                    <v-card-text>
                        <v-row>
                            <v-col>
                                <div
                                    v-for="item in datosTablasLicencias"
                                    :key="item.id"
                                >
                                    <v-data-table
                                        :id="item.id"
                                        v-model="selected"
                                        :headers="headersLicencia"
                                        :items="item.items"
                                        show-select
                                        class="elevation-1"
                                        sort-by="idlicencia"
                                        item-key="idlicencia"
                                        :loading="loading"
                                        :item-class="row_classes"
                                        loading-text="Cargando Datos... Por favor tenga paciencia"
                                    >
                                        <template v-slot:top>
                                            <v-toolbar
                                                color="white"
                                                flat
                                            >
                                                <v-toolbar-title>
                                                    {{ item.title }}
                                                </v-toolbar-title>
                                                <v-divider
                                                    class="mx-4"
                                                    inset
                                                    vertical
                                                ></v-divider>
                                            </v-toolbar>
                                        </template>
                                    </v-data-table>
                                </div>
                            </v-col>
                            <v-col>
                                <div
                                    v-for="item in datosTablasSaldos"
                                    :key="item.id"
                                >
                                    <tabla-saldos
                                        :headers="item.headers"
                                        :items="item.items"
                                        :title="item.title"
                                    />
                                </div>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import moment from 'moment';
import _ from 'lodash';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import TarjetaAgente from '../utils/TarjetaAgente.vue';
import TablaSaldos from './TablaSaldos.vue';
import LicenciaMixin from '../../mixins/licenciaMixin.js';

export default {
    name: 'ConsultaAgente.vue',
    mixins: [LicenciaMixin],
    components: {
        TarjetaAgente,
        TablaSaldos,
    },
    mounted() {},
    data: (vm) => ({
        dialog: false,
        selected: [],
        maximosAnuales: {
            10: 150,
            11: 10,
            1: 300,
            15: 93,
            25: 6,
            28: 20,
            32: 30,
        },
        gruposTipoLicencias: [
            {
                title: 'Salud Ocupacional',
                codes: [1, 2, 3, 4, 7, 8, 11, 21, 22],
            },
            { title: 'Anual Reglamentaria', codes: [16, 17, 25, 27] },
            { title: 'Aut. Nivel Central', codes: [5, 29, 6, 15, 30, 32] },
            { title: 'Aut. Efector', codes: [9, 10, 12, 13, 14, 28] },
            { title: 'Capacitacion', codes: [18, 19] },
            { title: 'Inasistencias', codes: [33, 34] },
        ],
        habilitarTablas: false,
        json_fields: {
            documento: 'documento',
            apellido_nombre: 'apellido_nombre',
            servicio: 'servicio',
            efector: 'efector',
            'Numero de Licencia': 'idlicencia',
            'Tipo de Licencia': 'descripcion',
            'Fecha Desde': 'fecha_efectiva_inicio',
            'Fecha Hasta': 'fecha_efectiva_final',
            'Dias Pedidos': 'dias',
            'Primer Visado': {
                field: 'primer_visado',
                callback: (value) => {
                    return value === true
                        ? 'Si'
                        : value === false
                        ? 'No'
                        : 'No esta visado';
                },
            },
            'Segundo Visado': {
                field: 'segundo_visado',
                callback: (value) => {
                    return value === true
                        ? 'Si'
                        : value === false
                        ? 'No'
                        : 'No esta visado';
                },
            },
            Interrupcion: {
                field: 'cuarta_visado',
                callback: (value) => {
                    return value === true
                        ? 'Si'
                        : value === false
                        ? 'No'
                        : 'Sin Interrupcion';
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
        error: false,
    }),
    methods: {
        exportPDF() {
            const addFooters = (doc) => {
                const pageCount = doc.internal.getNumberOfPages();

                doc.setFont('helvetica', 'italic');
                doc.setFontSize(8);
                for (var i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.text('SIARHU', 50, 811);
                    doc.text(this.user, 520, 811);
                    doc.text(
                        'Página ' + String(i) + ' de ' + String(pageCount),
                        doc.internal.pageSize.width / 2,
                        811,
                        {
                            align: 'center',
                        },
                    );
                }
            };
            const addHeaders = (doc) => {
                const pageCount = doc.internal.getNumberOfPages();
                doc.setFont('helvetica', 'italic');
                doc.setFontSize(8);
                for (var i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.text(
                        moment().locale('es').format('DD/MM/YYYY, h:mm:ss a'),
                        doc.internal.pageSize.width * 0.75,
                        20,
                    );
                }
            };
            var vm = this;
            var doc = new jsPDF('p', 'pt');
            // Informacion del Agente
            var img = new Image();
            img.src = 'images/rrhh_siprosa.png';
            doc.addImage(img, 'JPEG', 50, 60, 130, 44);
            img.src = 'images/logo_ministerio.jpg';
            doc.addImage(
                img,
                'JPEG',
                doc.internal.pageSize.width * 0.7,
                60,
                130,
                24,
            );
            doc.setFont('times');
            var texto = 'Licencias del Agente';
            var xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            doc.text(texto, xOffset, 120);
            texto = 'Datos del Agente';
            xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            doc.text(texto, xOffset, 150);

            let agentes = [];
            let agente = _.cloneDeep(this.agente);

            agentes.push(agente);

            let columnas = [
                { title: 'Documento', dataKey: 'documento' },
                { title: 'Nombre Completo', dataKey: 'apellido_nombre' },
                { title: 'Efector', dataKey: 'efector' },
                { title: 'Servicio', dataKey: 'codigo_nombre' },
            ];
            doc.autoTable(columnas, agentes, {
                startY: 150 + 25,
            });

            var finalY = doc.previousAutoTable.finalY;
            texto = 'Listado de Saldos Anuales';
            xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            doc.text(texto, xOffset, finalY + 20);

            columnas = [
                { title: 'Tipo de Licencia', dataKey: 'descripcion' },
                { title: 'Año', dataKey: 'año' },
                { title: 'Disponible', dataKey: 'disponible' },
                { title: 'Pedido', dataKey: 'dias' },
                { title: 'Saldo', dataKey: 'saldo' },
            ];

            doc.autoTable(
                columnas,
                this.saldoAnuales.reduce((acc, el) => {
                    if (el.descripcion !== 'ORDINARIA') {
                        acc.push(el);
                    }
                    return acc;
                }, []),
                {
                    styles: { overflow: 'linebreak', fontSize: 7 },
                    startY: finalY + 40,
                },
            );

            var finalY = doc.previousAutoTable.finalY;
            texto = 'Listado de Saldos Anuales Ordinarias';
            xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            doc.text(texto, xOffset, finalY + 20);
            columnas = [
                { title: 'Año', dataKey: 'año' },
                { title: 'Disponible', dataKey: 'disponible' },
                { title: 'Pedido', dataKey: 'pedido' },
                { title: 'Saldo', dataKey: 'saldo' },
            ];

            doc.autoTable(columnas, _.sortBy(this.antiguedades, 'año'), {
                styles: { overflow: 'linebreak', fontSize: 7 },
                startY: finalY + 40,
            });

            let licencias = _.cloneDeep(this.selected);

            if (this.getLicencias.length === 0) {
                texto = 'El agente no ha pedido ninguna licencia';
            } else if (licencias.length === 0) {
                texto = 'No ha seleccionado ninguna licencia para exportar';
            } else {
                texto = 'Listado de Licencias';
            }

            xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            var finalY = doc.previousAutoTable.finalY;
            doc.text(texto, xOffset, finalY + 20);

            if (this.selected.length > 0) {
                _.each(licencias, (licencia, idx) => {
                    licencias[idx].primer_visado =
                        licencia.primer_visado === true
                            ? 'Si'
                            : licencia.primer_visado === false
                            ? 'No'
                            : 'No esta visado';
                    licencias[idx].segundo_visado =
                        licencia.segundo_visado === true
                            ? 'Si'
                            : licencia.segundo_visado === false
                            ? 'No'
                            : 'No esta visado';
                    licencias[idx].cuarta_visado =
                        licencia.cuarta_visado === true
                            ? 'Si'
                            : licencia.cuarta_visado === false
                            ? 'No'
                            : 'Sin Interrupcion';
                });

                columnas = [
                    { title: 'Dias', dataKey: 'dias' },
                    { title: 'Nº de Lic.', dataKey: 'idlicencia' },
                    { title: 'Tipo de Licencia', dataKey: 'descripcion' },
                    {
                        title: 'Fecha Desde',
                        dataKey: 'fecha_efectiva_inicio',
                    },
                    {
                        title: 'Fecha Hasta',
                        dataKey: 'fecha_efectiva_final',
                    },
                    { title: 'Primer Visado', dataKey: 'primer_visado' },
                    { title: 'Segundo Visado', dataKey: 'segundo_visado' },
                    { title: 'Interrupcion', dataKey: 'cuarta_visado' },
                ];
                licencias = licencias.sort(function (a, b) {
                    var dateA = new moment(
                            a.fecha_efectiva_inicio,
                            'DD/MM/YYYY',
                        ),
                        dateB = new moment(
                            b.fecha_efectiva_inicio,
                            'DD/MM/YYYY',
                        );
                    return dateA - dateB; //sort by date ascending
                });
                doc.autoTable(columnas, licencias, {
                    styles: { overflow: 'linebreak', fontSize: 7 },
                    startY: finalY + 40,
                });
            }

            addHeaders(doc);
            addFooters(doc);
            doc.save('Consulta_Agente.pdf');
            this.selected = [];
        },
        row_classes(item) {
            if (item.primer_visado === true && item.segundo_visado === null) {
                return 'advertencia';
            } else if (item.segundo_visado === true) {
                return 'visado';
            } else if (
                item.primer_visado === false ||
                item.segundo_visado === false
            ) {
                return 'desvisado';
            }
        },
        can(cadena) {
            return _.findIndex(this.permisos, ['name', cadena]) >= 0;
        },
        habilitarBusqueda(evt) {
            this.habilitarTablas = !evt;
            this.buscarPersonasActivas(true);
            this.fetchLicenciasPorTipoAgente();
        },
    },
    watch: {},

    computed: {
        selectedExcel() {
            let aux = _.each(this.selected, (fila) => {
                fila['documento'] = this.agente.documento;
                fila['apellido_nombre'] = this.agente.apellido_nombre;
                fila['servicio'] = this.agente.servicio;
                fila['efector'] = this.agente.efector;
            });

            return aux;
        },
        saldos_personas() {
            return this.$store.getters['licencia/saldos_personas'];
        },
        user() {
            return this.$store.getters['user/user'].nombreusuario;
        },
        permisos() {
            return this.$store.getters['user/permisos'];
        },
        role_display() {
            return this.$store.getters['user/role_display'];
        },
        agente() {
            return this.$store.getters['agente/agente'];
        },
        headersLicencia() {
            return [
                {
                    value: 'idlicencia',
                    text: 'Nº de Licencia',
                    sortable: true,
                },
                {
                    value: 'descripcion',
                    text: 'Tipo de Licencia',
                    sortable: true,
                },
                {
                    value: 'fecha_efectiva_inicio',
                    text: 'Fecha de Inicio Efectivo',
                    sortable: true,
                },
                {
                    value: 'fecha_efectiva_final',
                    text: 'Fecha de Finalizacion Efectiva',
                    sortable: true,
                },
                {
                    value: 'dias',
                    text: 'Cantidad de Días',
                    sortable: true,
                },
            ];
        },
        licenciasSaludOcupacional() {
            const lic = this.viewLicenciasTables(
                this.getLicencias,
                this.gruposTipoLicencias[0].codes,
            );
            return lic;
        },
        licenciasAnualReglamentaria() {
            const lic = this.viewLicenciasTables(
                this.getLicencias,
                this.gruposTipoLicencias[1].codes,
            );
            return lic;
        },
        licenciasConGoce() {
            const lic = this.viewLicenciasTables(
                this.getLicencias,
                this.gruposTipoLicencias[3].codes,
            );
            return lic;
        },
        licenciasSinGoce() {
            const lic = this.viewLicenciasTables(
                this.getLicencias,
                this.gruposTipoLicencias[2].codes,
            );
            return lic;
        },
        licenciasCapacitacion() {
            const lic = this.viewLicenciasTables(
                this.getLicencias,
                this.gruposTipoLicencias[4].codes,
            );
            return lic;
        },
        inasistencias() {
            const lic = this.viewLicenciasTables(
                this.getLicencias,
                this.gruposTipoLicencias[5].codes,
            );
            return lic;
        },
        datosTablasLicencias() {
            return [
                {
                    id: 0,
                    items: this.licenciasSaludOcupacional,
                    collection: this.updatedSelectionLicSO,
                    title: 'Salud Ocupacional',
                },
                {
                    id: 1,
                    items: this.licenciasAnualReglamentaria,
                    collection: this.updatedSelectionLicAR,
                    title: 'Anual Reglamentaria',
                },
                {
                    id: 2,
                    items: this.licenciasConGoce,
                    collection: this.updatedSelectionLicCG,
                    title: 'Con Goce de Sueldo',
                },
                {
                    id: 3,
                    items: this.licenciasSinGoce,
                    collection: this.updatedSelectionLicSG,
                    title: 'Sin Goce de Sueldo',
                },
                {
                    id: 4,
                    items: this.licenciasCapacitacion,
                    collection: this.updatedSelectionLicC,
                    title: 'Capacitacion',
                },
                {
                    id: 5,
                    items: this.inasistencias,
                    collection: this.updatedSelectionInasistencias,
                    title: 'Inasistencias',
                },
            ];
        },
        datosTablasSaldos() {
            return [
                {
                    id: 0,
                    headers: this.headersSaldoAnual,
                    items: this.saldoAnuales,
                    title: 'Saldos con Restricciones Anuales',
                },
                {
                    id: 1,
                    headers: this.headersSaldoLAO,
                    items: this.antiguedades,
                    title: 'Saldos de Licencias Anuales Ordinarias',
                },
            ];
        },
        saldoAnuales() {
            var resulta = _.chain(this.saldos)
                .groupBy('año')
                .map(function (value, ind1) {
                    return [
                        _.chain(value)
                            .groupBy('descripcion')
                            .map((fila, ind2) => {
                                return {
                                    año: _.get(_.find(fila, 'año'), 'año'),
                                    descripcion: ind2.toUpperCase(),
                                    codigo: _.get(
                                        _.find(fila, 'codigo'),
                                        'codigo',
                                    ),
                                    dias: _.sumBy(fila, 'dias'),
                                };
                            })
                            .value(),
                    ];
                })
                .value();
            var aplanado = resulta.flat(Infinity);
            console.log('consulta de agente');
            console.log(this.maximosAnuales);
            console.log(this.diasAnualesMaximos);
            this.maximosAnuales[11] =
                this.diasAnualesMaximos[moment().year()][11];
            aplanado.forEach((item, index, arr) => {
                arr[index]['disponible'] =
                    this.maximosAnuales[arr[index]['codigo']];
                arr[index]['saldo'] =
                    arr[index]['disponible'] - arr[index]['dias'];
                arr[index]['disponible'] =
                    typeof arr[index]['disponible'] !== 'number'
                        ? 'No Aplicable'
                        : arr[index]['disponible'];

                arr[index]['saldo'] = Number.isNaN(arr[index]['saldo'])
                    ? 'No Aplicable'
                    : arr[index]['saldo'];
            });
            aplanado = _.orderBy(
                aplanado,
                ['descripcion', 'año'],
                ['asc', 'desc'],
            );

            aplanado = aplanado.filter((el) => {
                return el.descripcion !== '1A' && el.descripcion !== '1B';
            });
            aplanado = [
                ...aplanado,
                ...this.saldos_personas.map((el) => {
                    el.descripcion =
                        el.tipo_licencia +
                        ' - ' +
                        el.nombre +
                        ' ' +
                        el.apellido;
                    return el;
                }),
            ];
            return aplanado;
        },
        headersSaldoAnual() {
            return [
                {
                    value: 'descripcion',
                    text: 'Tipo de Licencia',
                    sortable: true,
                },
                {
                    value: 'año',
                    text: 'Año',
                    sortable: true,
                },
                {
                    value: 'disponible',
                    text: 'Disponible',
                    sortable: true,
                },
                {
                    value: 'dias',
                    text: 'Pedido',
                    sortable: true,
                },
                {
                    text: 'Saldo',
                    value: 'saldo',
                    sortable: true,
                },
            ];
        },
        headersSaldoLAO() {
            return [
                {
                    value: 'año',
                    text: 'Año',
                    sortable: true,
                },
                {
                    value: 'disponible',
                    text: 'Disponible',
                    sortable: true,
                },
                {
                    value: 'pedido',
                    text: 'Pedido',
                    sortable: true,
                },
                {
                    value: 'saldo',
                    text: 'Saldo',
                    sortable: true,
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
