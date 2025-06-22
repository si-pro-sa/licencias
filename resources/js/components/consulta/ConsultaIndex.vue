<template>
    <v-container
        id="contenedor"
        class="ml-9"
        fluid
    >
        <v-row class="ml-9">
            <v-col>
                <v-card>
                    <v-card-title
                        class="font-weight-regular blue-grey white--text headline"
                    >
                        <span class="white--text">Consulta de Licencias</span>
                    </v-card-title>
                    <v-spacer></v-spacer>
                    <v-card-subtitle class="pa-5">
                        <v-card class="d-flex flex-row mb-6">
                            <v-menu
                                :close-on-content-click="false"
                                full-width
                                max-width="290px"
                                min-width="290px"
                                offset-y
                                ref="menuFechaDesde"
                                transition="scale-transition"
                                v-model="menuFechaDesde"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="dateFormattedDesde"
                                        hint="DD/MM/YYYY"
                                        label="Fecha Desde"
                                        persistent-hint
                                        prepend-icon="mdi-calendar"
                                        @blur="
                                            fecha_desde =
                                                parseDate(dateFormattedDesde)
                                        "
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    @input="menuFechaDesde = false"
                                    locale="es-AR"
                                    no-title
                                    v-model="fecha_desde"
                                ></v-date-picker>
                            </v-menu>
                            <v-menu
                                :close-on-content-click="false"
                                full-width
                                max-width="290px"
                                min-width="290px"
                                offset-y
                                ref="menuFechaHasta"
                                transition="scale-transition"
                                v-model="menuFechaHasta"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="dateFormattedHasta"
                                        hint="DD/MM/YYYY"
                                        label="Fecha Hasta"
                                        persistent-hint
                                        prepend-icon="mdi-calendar"
                                        @blur="
                                            fecha_hasta =
                                                parseDate(dateFormattedHasta)
                                        "
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    @input="menuFechaHasta = false"
                                    locale="es-AR"
                                    no-title
                                    v-model="fecha_hasta"
                                ></v-date-picker>
                            </v-menu>
                        </v-card>
                    </v-card-subtitle>
                    <v-spacer></v-spacer>

                    <v-spacer></v-spacer>
                    <v-card-text>
                        <v-data-table
                            v-model="selected"
                            :headers="headers"
                            :items="licencias"
                            show-select
                            class="elevation-1"
                            item-key="idlicencia"
                            :item-class="row_classes"
                            :loading="loading"
                            loading-text="Cargando Datos... Por favor tenga paciencia"
                        >
                            <template v-slot:item.primer_visado="{ item }">
                                {{
                                    item.primer_visado === true
                                        ? 'Si'
                                        : item.primer_visado === false
                                        ? 'No'
                                        : 'No ha sido visada'
                                }}
                            </template>
                            <template v-slot:item.segundo_visado="{ item }">
                                {{
                                    item.segundo_visado === true
                                        ? 'Si'
                                        : item.segundo_visado === false
                                        ? 'No'
                                        : 'No ha sido visada'
                                }}
                            </template>
                            <template v-slot:item.cuarta_visado="{ item }">
                                {{
                                    item.cuarta_visado === true
                                        ? 'Si'
                                        : item.cuarta_visado === false
                                        ? 'No'
                                        : 'No ha sido visada'
                                }}
                            </template>
                            <template v-slot:item.descripcion="{ item }">
                                <b>
                                    {{ item.descripcion | mayuscula }}
                                </b>
                            </template>
                            <template v-slot:item.apellido_nombre="{ item }"
                                >{{ String(item.apellido + ' ' + item.nombre) }}
                            </template>
                            <template v-slot:item.dias="{ item }"
                                >{{
                                    item.dias === 0 &&
                                    (item.primer_visado === false ||
                                        item.primer_visado === null) &&
                                    (item.segundo_visado === null ||
                                        item.segundo_visado === false)
                                        ? 'No Autorizado'
                                        : item.cuarta_visado === true
                                        ? 'Interrumpida'
                                        : item.dias
                                }}
                            </template>
                            <template v-slot:item.fecha_pedido_inicio="{ item }"
                                >{{ item.fecha_pedido_inicio | fechaAcomodada }}
                            </template>
                            <template v-slot:item.fecha_pedido_final="{ item }"
                                >{{ item.fecha_pedido_final | fechaAcomodada }}
                            </template>
                            <template
                                v-slot:item.fecha_efectiva_inicio="{ item }"
                            >
                                {{
                                    item.fecha_efectiva_inicio | fechaAcomodada
                                }}
                            </template>

                            <template
                                v-slot:item.fecha_efectiva_final="{ item }"
                                >{{
                                    item.fecha_efectiva_final | fechaAcomodada
                                }}
                            </template>
                            <template
                                v-slot:item.fecha_interrupcion_inicio="{ item }"
                            >
                                {{
                                    item.fecha_interrupcion_inicio
                                        | fechaAcomodada
                                }}
                            </template>
                            <template v-slot:no-data
                                >No hay licencias cargadas
                            </template>

                            <template v-slot:body.prepend>
                                <tr>
                                    <td></td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="efector"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="codigoNombre"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="documento"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="apellido_nombre"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="tipoLicencia"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="dias"
                                        ></v-text-field>
                                    </td>

                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="fecha_pedido_inicio"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="fecha_pedido_final"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="primer_visado"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="fecha_efectiva_inicio"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="fecha_efectiva_final"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="segundo_visado"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="cuarta_visado"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="fecha_interrupcion_inicio"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="idlicencia"
                                        ></v-text-field>
                                    </td>
                                </tr>
                            </template>

                            <template v-slot:top>
                                <v-toolbar
                                    color="white"
                                    flat
                                >
                                    <v-toolbar-title>Licencias</v-toolbar-title>
                                    <v-divider
                                        class="mx-4"
                                        inset
                                        vertical
                                    ></v-divider>
                                    <v-spacer></v-spacer>

                                    <v-btn-toggle rounded>
                                        <download-excel
                                            class="m-2 v-btn v-btn--contained theme--light v-size--default yellow darken-5"
                                            :data="selected"
                                            :fields="json_fields"
                                            name="filename.xls"
                                            @change="actualizarTabla"
                                            v-if="
                                                can(
                                                    'exportarExcel-consultaLicencia',
                                                )
                                            "
                                        >
                                            <v-icon
                                                color="dark"
                                                left
                                                >mdi-file-excel-outline
                                            </v-icon>
                                            Exportar Excel
                                        </download-excel>

                                        <v-btn
                                            @click="exportPDF"
                                            class="m-2"
                                            color="warning"
                                            dark
                                            v-if="
                                                can(
                                                    'exportarPDF-consultaLicencia',
                                                )
                                            "
                                        >
                                            <v-icon left
                                                >mdi-file-pdf-outline
                                            </v-icon>
                                            Exportar PDF
                                        </v-btn>

                                        <v-btn
                                            @click="
                                                buscarLicencias(
                                                    fecha_desde,
                                                    fecha_hasta,
                                                )
                                            "
                                            class="m-2"
                                            color="blue darken-5"
                                        >
                                            <v-icon
                                                color="dark"
                                                left
                                                >mdi-file-find-outline
                                            </v-icon>
                                            Buscar
                                        </v-btn>
                                    </v-btn-toggle>
                                </v-toolbar>
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import jsPDF from 'jspdf';
import _ from 'lodash';
import 'jspdf-autotable';
import moment from 'moment';

export default {
    created() {
        this.$store.dispatch('licencia/getDefaultState');
    },
    name: 'ConsultaIndex.vue',
    data: (vm) => ({
        json_fields: {
            'Numero de Licencia': 'idlicencia',
            Efector: 'efector',
            Servicio: 'codigo_nombre',
            Documento: 'documento',
            'Apellido y Nombre': 'apellido_nombre',
            'Tipo de Licencia': 'descripcion',
            'Dias de Licencia': 'dias',
            'Fecha del Inicio del Pedido': {
                field: 'fecha_pedido_inicio',
                callback: (value) => {
                    return value === null
                        ? 'No tiene fecha'
                        : moment(value).utcOffset('+0300').format('DD/MM/YYYY');
                },
            },
            'Fecha Final del Pedido': {
                field: 'fecha_pedido_final',
                callback: (value) => {
                    return value === null
                        ? 'No tiene fecha'
                        : moment(value).utcOffset('+0300').format('DD/MM/YYYY');
                },
            },
            'Fecha Efectiva de Inicio': {
                field: 'fecha_efectiva_inicio',
                callback: (value) => {
                    return value === ''
                        ? 'No tiene fecha'
                        : moment(value).utcOffset('+0300').format('DD/MM/YYYY');
                },
            },
            'Fecha Efectiva Final': {
                field: 'fecha_efectiva_final',
                callback: (value) => {
                    return value === ''
                        ? 'No tiene fecha'
                        : moment(value).utcOffset('+0300').format('DD/MM/YYYY');
                },
            },
            'Primer Visado': {
                field: 'primer_visado',
                callback: (value) => {
                    return value === true
                        ? 'Si'
                        : value === false
                        ? 'No'
                        : 'No ha sido visado';
                },
            },
            'Segundo Visado': {
                field: 'segundo_visado',
                callback: (value) => {
                    return value === true
                        ? 'Si'
                        : value === false
                        ? 'No'
                        : 'No ha sido visado';
                },
            },
            Interrupcion: {
                field: 'cuarta_visado',
                callback: (value) => {
                    return value === true
                        ? 'Si'
                        : value === false
                        ? 'No'
                        : 'Sin Interrupción';
                },
            },
            'Fecha Interrupcion': {
                field: 'fecha_interrupcion_inicio',
                callback: (value) => {
                    return value === ''
                        ? 'No tiene fecha'
                        : moment(value).utcOffset('+0300').format('DD/MM/YYYY');
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
        menuFechaDesde: false,
        menuFechaHasta: false,
        dateFormattedDesde: vm.formatDate(
            new Date().toISOString().substr(0, 10),
        ),
        dateFormattedHasta: vm.formatDate(
            new Date().toISOString().substr(0, 10),
        ),
        fecha_desde: new Date().toISOString().substr(0, 10),
        fecha_hasta: new Date().toISOString().substr(0, 10),
        loading: false,
        selected: [],
        error: false,
        efector: '',
        codigoNombre: '',
        documento: '',
        apellido_nombre: '',
        tipoLicencia: '',
        dias: '',
        idlicencia: '',
        fecha_pedido_inicio: '',
        fecha_pedido_final: '',
        primer_visado: '',
        segundo_visado: '',
        fecha_efectiva_inicio: '',
        fecha_efectiva_final: '',
        cuarta_visado: '',
        fecha_interrupcion_inicio: '',
        licTitulo: 'Consulta de Licencias',
        editedItem: {
            idlicencia: '',
            documento: '',
            fecha_desde: '',
            fecha_hasta: '',
        },
        defaultItem: {
            idlicencia: '',
            documento: '',
            fecha_desde: '',
            fecha_hasta: '',
        },
    }),
    methods: {
        can(cadena) {
            return _.findIndex(this.permisos, ['name', cadena]) >= 0;
        },
        exportPDF() {
            const addFooters = (doc) => {
                const pageCount = doc.internal.getNumberOfPages();

                doc.setFont('helvetica', 'italic');
                doc.setFontSize(8);
                for (var i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.text('SIARHU', doc.internal.pageSize.width * 0.1, 572);
                    doc.text(
                        this.user,
                        doc.internal.pageSize.width * 0.85,
                        572,
                    );
                    doc.text(
                        'Página ' + String(i) + ' de ' + String(pageCount),
                        doc.internal.pageSize.width / 2,
                        572,
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
                        doc.internal.pageSize.width * 0.85,
                        20,
                    );
                }
            };

            var vm = this;
            var doc = new jsPDF('l', 'pt', 'a4');
            doc.setFont('times');

            var texto = 'Consulta de Licencias';
            var xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            // Informacion del Agente
            var img = new Image();
            img.src = 'images/rrhh_siprosa.png';
            doc.addImage(img, 'png', 50, 60, 130, 44);
            img.src = 'images/logo_ministerio.jpg';
            doc.addImage(
                img,
                'JPEG',
                doc.internal.pageSize.width * 0.7,
                60,
                130,
                24,
            );
            doc.text(texto, xOffset, 120);
            texto = 'Periodo Entre';
            xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            doc.text(texto, xOffset, 150);
            let columnas = [
                { title: 'Desde', dataKey: 'fecha_desde' },
                { title: 'Hasta', dataKey: 'fecha_hasta' },
            ];
            let fechas = [
                {
                    fecha_desde: this.fecha_desde,
                    fecha_hasta: this.fecha_hasta,
                },
            ];
            doc.autoTable(columnas, fechas, {
                styles: { overflow: 'linebreak', fontSize: 7 },
                startY: 150 + 25,
            });

            let licencias = _.cloneDeep(this.selected);

            if (this.licencias.length === 0) {
                texto =
                    'No hay ninguna licencia visible para el periodo seleccionado \n \t y el usuario logueado';
                xOffset =
                    doc.internal.pageSize.width / 2 -
                    (doc.getStringUnitWidth(texto) *
                        doc.internal.getFontSize()) /
                        2;
                var finalY = doc.previousAutoTable.finalY;
                doc.text(texto, xOffset, finalY + 20);
            } else if (this.selected.length === 0) {
                texto = 'No ha seleccionado ninguna licencia para exportar';
                xOffset =
                    doc.internal.pageSize.width / 2 -
                    (doc.getStringUnitWidth(texto) *
                        doc.internal.getFontSize()) /
                        2;
                var finalY = doc.previousAutoTable.finalY;
                doc.text(texto, xOffset, finalY + 20);
            } else {
                _.each(licencias, function (licencia, idx) {
                    licencias[idx].primer_visado =
                        licencia.primer_visado == true
                            ? 'Si'
                            : licencia.primer_visado == false
                            ? 'No'
                            : 'No tiene visado';
                    licencias[idx].segundo_visado =
                        licencia.segundo_visado == true
                            ? 'Si'
                            : licencia.segundo_visado == false
                            ? 'No'
                            : 'No tiene visado';
                    licencias[idx].cuarta_visado =
                        licencia.cuarta_visado == true
                            ? 'Si'
                            : licencia.cuarta_visado == false
                            ? 'No'
                            : 'No tiene visado';
                    licencia.fecha_pedido_inicio = moment(
                        licencia.fecha_pedido_inicio,
                    )
                        .utcOffset('+0300')
                        .format('DD/MM/YYYY');
                    licencia.fecha_pedido_final = moment(
                        licencia.fecha_pedido_final,
                    )
                        .utcOffset('+0300')
                        .format('DD/MM/YYYY');
                    licencia.fecha_efectiva_inicio =
                        licencia.fecha_efectiva_inicio !== null
                            ? moment(licencia.fecha_efectiva_inicio)
                                  .utcOffset('+0300')
                                  .format('DD/MM/YYYY')
                            : 'No hay fecha';
                    licencia.fecha_efectiva_final =
                        licencia.fecha_efectiva_final !== null
                            ? moment(licencia.fecha_efectiva_final)
                                  .utcOffset('+0300')
                                  .format('DD/MM/YYYY')
                            : 'No hay fecha';
                    licencia.fecha_interrupcion_inicio =
                        licencia.fecha_interrupcion_inicio !== null
                            ? moment(licencia.fecha_interrupcion_inicio)
                                  .utcOffset('+0300')
                                  .format('DD/MM/YYYY')
                            : 'No hay fecha';
                });
                texto = 'Listado de Licencias';
                xOffset =
                    doc.internal.pageSize.width / 2 -
                    (doc.getStringUnitWidth(texto) *
                        doc.internal.getFontSize()) /
                        2;
                var finalY = doc.previousAutoTable.finalY;
                doc.text(texto, xOffset, finalY + 20);
                columnas = [
                    { title: 'Efector', dataKey: 'efector' },
                    { title: 'Servicio', dataKey: 'codigo_nombre' },
                    { title: 'Documento', dataKey: 'documento' },
                    { title: 'Apellido y Nombre', dataKey: 'apellido_nombre' },
                    { title: 'Dias', dataKey: 'dias' },
                    { title: 'Nº de Lic.', dataKey: 'idlicencia' },
                    { title: 'Tipo de Licencia', dataKey: 'descripcion' },
                    {
                        title: 'Fecha Inicial del Pedido',
                        dataKey: 'fecha_pedido_inicio',
                    },
                    {
                        title: 'Fecha Final del Pedido',
                        dataKey: 'fecha_pedido_final',
                    },
                    {
                        title: 'Fecha Inical Efectiva',
                        dataKey: 'fecha_efectiva_inicio',
                    },
                    {
                        title: 'Fecha Final Efectiva',
                        dataKey: 'fecha_efectiva_final',
                    },
                    { title: 'Primer Visado', dataKey: 'primer_visado' },
                    { title: 'Segundo Visado', dataKey: 'segundo_visado' },
                    { title: 'Interrupcion', dataKey: 'cuarta_visado' },
                    {
                        title: 'Fecha de la Interrupción',
                        dataKey: 'fecha_interrupcion_inicio',
                    },
                ];
                licencias = licencias.sort(function (a, b) {
                    var dateA = new moment(a.fecha_pedido_inicio, 'DD/MM/YYYY'),
                        dateB = new moment(b.fecha_pedido_inicio, 'DD/MM/YYYY');
                    return dateA - dateB; //sort by date ascending
                });
                doc.autoTable(columnas, licencias, {
                    styles: { overflow: 'linebreak', fontSize: 7 },
                    startY: finalY + 40,
                });
            }

            addHeaders(doc);
            addFooters(doc);
            doc.save('consulta_licencias.pdf');
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
        async exportarXLS() {
            var res = window.confirm(
                '¿Esta seguro que desea generar una planilla de calculo para las licencias seleccionadas?',
            );
            if (res) {
                await this.$store
                    .dispatch('licencia/exportXLS', {
                        licencias: this.selected,
                    })
                    .then((res) => {
                        this.buscarLicencias(
                            this.fecha_desde,
                            this.fecha_hasta,
                        );
                    })
                    .catch((err) => {
                        console.log('Error en la exportacion ', err);
                    });
            }
        },
        actualizarTabla() {
            /*
            this.loading = true
            this.buscarLicencias(this.fecha_desde, this.fecha_hasta)*/
        },
        async buscarLicencias(fecha_desde, fecha_hasta) {
            this.loading = true;
            this.selected = [];
            let dependencia = this.dependencia?.iddependencia
                ? this.dependencia.iddependencia
                : this.dependencia_padre?.iddependencia
                ? this.dependencia_padre.iddependencia
                : null;
            console.log(`Dependencia: ${dependencia}`);
            await this.$store
                .dispatch('licencia/getLicenciasConsulta', {
                    fecha_desde: fecha_desde,
                    fecha_hasta: fecha_hasta,
                    dependencia: dependencia,
                })
                .then((res) => {
                    this.loading = false;
                });
        },
        formatDate(date) {
            if (!date) return null;

            const [year, month, day] = date.split('-');
            console.log(`${day}/${month}/${year}`);
            return `${day}/${month}/${year}`;
        },
        parseDate(date) {
            if (!date) return null;

            const [day, month, year] = date.split('/');
            return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
        },
    },
    watch: {
        documento: function (newValue, oldValue) {
            this.debouncedSearchPersona();
        },
        dialog(val) {
            val || this.close();
        },
        fecha_desde(val) {
            this.dateFormattedDesde = this.formatDate(this.fecha_desde);
        },
        fecha_hasta(val) {
            this.dateFormattedHasta = this.formatDate(this.fecha_hasta);
        },
    },
    computed: {
        permisos() {
            return this.$store.getters['user/permisos'];
        },
        user() {
            return this.$store.getters['user/user'].nombreusuario;
        },
        hijos() {
            return this.role_display === 'Dpto./Oficina Personal Hospitales' ||
                this.role_display ===
                    'Dpto./Oficina Personal Dir. Gral. Red De Servicios' ||
                this.role_display ===
                    'Jefe Personal de Areas Operativas Con Carga De RI'
                ? 1
                : 0;
        },
        role_display() {
            return this.$store.getters['user/role_display'];
        },
        agente() {
            return this.$store.getters['user/agente'];
        },
        licencias() {
            return this.$store.getters['licencia/licencias_dependientes'];
        },
        dependencia() {
            return this.$store.getters['user/dependencia'];
        },
        dependencia_padre() {
            return this.$store.getters['user/dependencia_padre'];
        },
        headers() {
            return [
                {
                    value: 'efector',
                    text: 'Efector',
                    sortable: true,
                    filter: (value) => {
                        if (!this.efector) return true;
                        return (
                            value
                                .toString()
                                .indexOf(this.efector.toUpperCase()) !== -1
                        );
                    },
                },
                {
                    value: 'codigo_nombre',
                    text: 'Servicio',
                    sortable: true,
                    filter: (value) => {
                        if (!this.codigoNombre) return true;
                        return (
                            value
                                .toString()
                                .indexOf(this.codigoNombre.toUpperCase()) !== -1
                        );
                    },
                },
                {
                    value: 'documento',
                    text: 'Documento',
                    sortable: true,
                    filter: (value) => {
                        if (!this.documento) return true;
                        return value.toString().indexOf(this.documento) !== -1;
                    },
                },
                {
                    value: 'apellido_nombre',
                    text: 'Apellido y Nombre',
                    sortable: true,
                    filter: (value) => {
                        if (!this.apellido_nombre) return true;
                        return (
                            value
                                .toString()
                                .indexOf(this.apellido_nombre.toUpperCase()) !==
                            -1
                        );
                    },
                },
                {
                    value: 'descripcion',
                    text: 'Tipo de Licencias',
                    sortable: true,
                    filter: (value) => {
                        if (!this.tipoLicencia) return true;
                        return (
                            value
                                .toString()
                                .toUpperCase()
                                .indexOf(this.tipoLicencia.toUpperCase()) !== -1
                        );
                    },
                },
                {
                    value: 'dias',
                    text: 'Dias de Licencia',
                    sortable: true,
                    filter: (value) => {
                        if (!this.dias) return true;
                        return value <= parseInt(this.dias);
                    },
                },
                {
                    value: 'fecha_pedido_inicio',
                    text: 'Fecha del Inicio del Pedido',
                    sortable: true,
                    filter: (value) => {
                        if (!this.fecha_pedido_inicio) return true;
                        value = moment(value, 'YYYY-MM-DD').format(
                            'DD/MM/YYYY',
                        );
                        return (
                            value
                                .toString()
                                .indexOf(this.fecha_pedido_inicio) !== -1
                        );
                    },
                },
                {
                    value: 'fecha_pedido_final',
                    text: 'Fecha Pedido Final',
                    sortable: true,
                    filter: (value) => {
                        if (!this.fecha_pedido_final) return true;
                        value = moment(value, 'YYYY-MM-DD').format(
                            'DD/MM/YYYY',
                        );
                        return (
                            value
                                .toString()
                                .indexOf(this.fecha_pedido_final) !== -1
                        );
                    },
                },
                {
                    value: 'primer_visado',
                    text: 'Primer Visado',
                    filter: (value) => {
                        if (!this.primer_visado) return true;
                        if (this.primer_visado.length > 3) {
                            return value === null;
                        }
                        if (this.primer_visado.length > 0) {
                            let aux =
                                this.primer_visado[0] === 'S' ||
                                this.primer_visado[0] === 's';
                            return value === aux;
                        }
                    },
                    sortable: true,
                },
                {
                    value: 'fecha_efectiva_inicio',
                    text: 'Fecha Efectiva Inicio',
                    sortable: true,
                    filter: (value) => {
                        if (!this.fecha_efectiva_inicio) return true;
                        value = moment(value, 'YYYY-MM-DD').format(
                            'DD/MM/YYYY',
                        );
                        return (
                            value
                                .toString()
                                .indexOf(this.fecha_efectiva_inicio) !== -1
                        );
                    },
                },
                {
                    value: 'fecha_efectiva_final',
                    text: 'Fecha Efectiva Final',
                    sortable: true,
                    filter: (value) => {
                        if (!this.fecha_efectiva_final) return true;
                        value = moment(value, 'YYYY-MM-DD').format(
                            'DD/MM/YYYY',
                        );
                        return (
                            value
                                .toString()
                                .indexOf(this.fecha_efectiva_final) !== -1
                        );
                    },
                },
                {
                    value: 'segundo_visado',
                    text: 'Segundo Visado',
                    filter: (value) => {
                        if (!this.segundo_visado) return true;
                        if (this.segundo_visado.length > 3) {
                            return value === null;
                        }
                        if (this.segundo_visado.length > 0) {
                            let aux =
                                this.segundo_visado[0] === 'S' ||
                                this.segundo_visado[0] === 's';
                            return value === aux;
                        }
                    },
                    sortable: true,
                },
                {
                    value: 'cuarta_visado',
                    text: 'Interrupcion',
                    filter: (value) => {
                        if (!this.cuarta_visado) return true;
                        if (this.cuarta_visado.length > 3) {
                            return value === null;
                        }
                        if (this.cuarta_visado.length > 0) {
                            let aux =
                                this.cuarta_visado[0] === 'S' ||
                                this.cuarta_visado[0] === 's';
                            return value === aux;
                        }
                    },
                    sortable: true,
                },
                {
                    value: 'fecha_interrupcion_inicio',
                    text: 'Fecha de Interrupcion',
                    filter: (value) => {
                        if (!this.fecha_interrupcion_inicio) return true;
                        value = moment(value, 'YYYY-MM-DD').format(
                            'DD/MM/YYYY',
                        );
                        return (
                            value
                                .toString()
                                .indexOf(this.fecha_interrupcion_inicio) !== -1
                        );
                    },
                    sortable: true,
                },
                {
                    value: 'idlicencia',
                    text: 'Numero de Licencia',
                    sortable: true,
                    filter: (value) => {
                        if (!this.idlicencia) return true;
                        return value === parseInt(this.idlicencia);
                    },
                },
            ];
        },
        name() {
            return this.data;
        },
        fechaActual() {
            let hoy = new Date();
            return (
                hoy.getDate() +
                '-' +
                (hoy.getMonth() + 1) +
                '-' +
                hoy.getFullYear()
            );
        },
        formatearFecha(fecha) {
            return fecha ? fecha.format('DD/MM/YYYY') : '';
        },
    },
};
</script>

<style lang="scss" scoped>
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
