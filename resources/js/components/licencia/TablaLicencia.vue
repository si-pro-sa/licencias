<template>
    <v-row>
        <v-col>
            <v-data-table
                :headers="headers"
                :items="licencias"
                :item-class="row_classes"
                item-key="idlicencia"
                sort-by="idlicencia"
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
                <template v-slot:item.cuarta_visado="{ item }">{{
                    item.cuarta_visado ? 'Si' : 'No'
                }}</template>
                <template v-slot:item.dias="{ item }">{{
                    item.dias === 0 &&
                    (item.primer_visado === false ||
                        item.primer_visado === null) &&
                    (item.segundo_visado === null ||
                        item.segundo_visado === false)
                        ? 'No Autorizado'
                        : item.cuarta_visado === true
                        ? 'Interrumpida'
                        : item.dias
                }}</template>
                <template v-slot:item.fecha_pedido_inicio="{ item }">{{
                    item.fecha_pedido_inicio | fechaAcomodada
                }}</template>
                <template v-slot:item.fecha_pedido_final="{ item }">{{
                    item.fecha_pedido_final | fechaAcomodada
                }}</template>
                <template v-slot:item.fecha_efectiva_inicio="{ item }">
                    {{ item.fecha_efectiva_inicio | fechaAcomodada }}
                </template>
                <template v-slot:item.fecha_efectiva_final="{ item }">{{
                    item.fecha_efectiva_final | fechaAcomodada
                }}</template>
                <template v-slot:item.fecha_interrupcion_inicio="{ item }">
                    {{ item.fecha_interrupcion_inicio | fechaAcomodada }}
                </template>
                <template v-slot:body.prepend>
                    <tr>
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
                                v-model="idlicencia"
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
                    </tr>
                </template>

                <template v-slot:top>
                    <v-alert
                        transition="scale-transition"
                        :value="mensajeSancion"
                        type="error"
                        >Solicitar Autorización y Carga al Dpto Capacitación
                    </v-alert>
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
                        <div v-show="!habilitarBuscar">
                            <download-excel
                                class="m-2 v-btn v-btn--contained theme--light v-size--default yellow darken-5"
                                :data="selected"
                                :fields="json_fields"
                                name="filename.xls"
                                @change="actualizarTabla"
                                v-if="can('exportarExcel-licencia')"
                            >
                                <v-icon
                                    color="dark"
                                    left
                                    >mdi-file-excel-outline
                                </v-icon>
                                Exportar Excel
                            </download-excel>
                        </div>
                        <v-btn
                            @change="actualizarTabla"
                            @click="exportPDF"
                            class="m-2"
                            color="warning"
                            dark
                            :disabled="habilitarBuscar"
                            v-if="can('exportarPDF-licencia')"
                        >
                            <v-icon left>mdi-file-pdf-outline</v-icon>
                            Exportar Licencias
                        </v-btn>
                        <v-btn
                            @change="actualizarTabla"
                            @click="buscarLicencias"
                            class="m-2"
                            color="primary"
                            dark
                            :disabled="habilitarBuscar"
                        >
                            <i class="fas fa-search mr-2"></i>Buscar Licencias
                        </v-btn>
                        <div v-if="can('crear-licencia')">
                            <div v-show="agente.fhasta === null">
                                <div v-show="!crearConSancion">
                                    <v-btn
                                        @change="actualizarTabla"
                                        @click="agregarLicencia"
                                        class="m-2"
                                        color="success"
                                        dark
                                        :disabled="habilitarBuscar"
                                    >
                                        <v-icon>mdi-clipboard-plus</v-icon>
                                        Nueva Licencia
                                    </v-btn>
                                </div>
                            </div>
                        </div>
                    </v-toolbar>
                </template>
                <template v-slot:item.action="{ item }">
                    <!--                    <v-icon @click="editItem(item)" class="mr-2" small>mdi-account-edit</v-icon>-->
                    <div v-if="can('ver-licencia')">
                        <v-hover v-slot:default="{ hover }">
                            <v-badge
                                :value="hover"
                                color="deep-purple accent-4"
                                content="Ver"
                                left
                                transition="slide-x-transition"
                            >
                                <v-icon
                                    @click="verLicencia(item)"
                                    small
                                    >mdi-eye</v-icon
                                >
                            </v-badge>
                        </v-hover>
                    </div>
                    <div v-show="can('visar-licencia')">
                        <div
                            v-show="
                                (item.primer_visado === null &&
                                    item.segundo_visado === null) ||
                                (item.primer_visado === true &&
                                    item.segundo_visado === null)
                            "
                        >
                            <div v-if="agente.fhasta === null">
                                <v-hover v-slot:default="{ hover }">
                                    <v-badge
                                        :value="hover"
                                        color="deep-purple accent-4"
                                        content="Primer Visado"
                                        left
                                        transition="slide-x-transition"
                                    >
                                        <v-icon
                                            @click="primerVisado(item)"
                                            small
                                            >mdi-check
                                        </v-icon>
                                    </v-badge>
                                </v-hover>
                            </div>
                        </div>
                    </div>

                    <div v-if="can('visar2-licencia')">
                        <div
                            v-show="
                                (item.primer_visado !== null &&
                                    item.cuarta_visado === null &&
                                    item.segundo_visado === null) ||
                                (item.primer_visado !== null &&
                                    item.segundo_visado === true &&
                                    (item.cuarta_visado === null ||
                                        item.cuarta_visado === false))
                            "
                        >
                            <div v-if="agente.fhasta === null">
                                <v-hover v-slot:default="{ hover }">
                                    <v-badge
                                        :value="hover"
                                        color="deep-purple accent-4"
                                        content="Segundo Visado"
                                        left
                                        transition="slide-x-transition"
                                    >
                                        <v-icon
                                            @click="segundoVisado(item)"
                                            small
                                            >mdi-check-all
                                        </v-icon>
                                    </v-badge>
                                </v-hover>
                            </div>
                        </div>
                    </div>
                    <div v-if="can('editar-licencia')">
                        <div
                            v-show="
                                item.primer_visado === null &&
                                item.segundo_visado === null
                            "
                        >
                            <div v-if="agente.fhasta === null">
                                <v-hover v-slot:default="{ hover }">
                                    <v-badge
                                        :value="hover"
                                        color="deep-purple accent-4"
                                        content="Modificar Presentacion"
                                        left
                                        transition="slide-x-transition"
                                    >
                                        <v-icon
                                            @click="modificarLicencia(item)"
                                            small
                                            >mdi-fountain-pen
                                        </v-icon>
                                    </v-badge>
                                </v-hover>
                            </div>
                        </div>
                    </div>
                    <div v-if="can('editarGerencia-licencia')">
                        <div v-if="agente.fhasta === null">
                            <v-hover v-slot:default="{ hover }">
                                <v-badge
                                    :value="hover"
                                    color="deep-purple accent-4"
                                    content="Modificar Por Gerencia"
                                    left
                                    transition="slide-x-transition"
                                >
                                    <v-icon
                                        @click="modificarGerenciaLicencia(item)"
                                        small
                                        >mdi-fountain-pen-tip
                                    </v-icon>
                                </v-badge>
                            </v-hover>
                        </div>
                    </div>
                    <div v-if="can('desvisar-licencia')">
                        <div
                            v-show="
                                item.segundo_visado === true ||
                                item.primer_visado === true
                            "
                        >
                            <div v-if="agente.fhasta === null">
                                <v-hover v-slot:default="{ hover }">
                                    <v-badge
                                        :value="hover"
                                        color="deep-purple accent-4"
                                        content="Desvisar"
                                        left
                                        transition="slide-x-transition"
                                    >
                                        <div
                                            v-show="
                                                (role_display ===
                                                    'Consulta para Directores' &&
                                                    item.segundo_visado !==
                                                        true) ||
                                                role_display !==
                                                    'Consulta para Directores'
                                            "
                                        >
                                            <v-icon
                                                @click="desvisarLicencia(item)"
                                                small
                                                >mdi-pen-minus
                                            </v-icon>
                                        </div>
                                    </v-badge>
                                </v-hover>
                            </div>
                        </div>
                    </div>
                    <div v-if="can('interrumpir-licencia')">
                        <div v-show="item.segundo_visado === true">
                            <div v-if="agente.fhasta === null">
                                <v-hover v-slot:default="{ hover }">
                                    <v-badge
                                        :value="hover"
                                        color="deep-purple accent-4"
                                        content="Interrumpir"
                                        left
                                        transition="slide-x-transition"
                                    >
                                        <v-icon
                                            v-show="
                                                item.primer_visado === true &&
                                                item.segundo_visado === true &&
                                                (item.cuarta_visado === null ||
                                                    item.cuarta_visado ===
                                                        false)
                                            "
                                            @click="interrumpirLicencia(item)"
                                            small
                                            >mdi-file-remove
                                        </v-icon>
                                    </v-badge>
                                </v-hover>
                            </div>
                        </div>
                    </div>
                    <div v-if="can('borrar-licencia')">
                        <div v-if="agente.fhasta === null">
                            <v-hover v-slot:default="{ hover }">
                                <v-badge
                                    :value="hover"
                                    color="deep-purple accent-4"
                                    content="Borrar"
                                    left
                                    transition="slide-x-transition"
                                >
                                    <v-icon
                                        v-show="
                                            (item.primer_visado === false ||
                                                item.primer_visado === null) &&
                                            (item.segundo_visado === false ||
                                                item.segundo_visado === null)
                                        "
                                        @click="borrarLicencia(item)"
                                        small
                                        >mdi-delete
                                    </v-icon>
                                </v-badge>
                            </v-hover>
                        </div>
                    </div>
                </template>
                <template v-slot:no-data>No hay licencias cargadas</template>
            </v-data-table>
        </v-col>
    </v-row>
</template>

<script>
import _ from 'lodash';
import jsPDF from 'jspdf';
import 'jspdf-autotable';

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}

export default {
    components: {},
    name: 'TablaLicencia',
    props: {
        habilitarBuscar: Boolean,
        tipoLicencia: {
            type: Number,
            default: 0,
        },
    },
    watch: {
        agente: function (oldValue, newValue) {
            this.buscarLicencias();
        },
        existeSancion: function () {
            this.crearConSancion = this.permisoCrearConSancion();
        },
        crearConSancion: function (oldValue, newValue) {
            console.info(`nuevo valor ${newValue}`);
        },
    },
    created() {},
    mounted() {
        if (!this.habilitarBuscar) {
            this.buscarLicencias();
        }
    },
    data() {
        return {
            json_fields: {
                Efector: 'efector',
                Servicio: 'codigo_nombre',
                Documento: 'documento',
                'Apellido y Nombre': 'apellido_nombre',
                Dias: 'dias',
                'Tipo de Licencia': 'descripcion',
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
                'Fecha Inicial del Pedido': {
                    field: 'fecha_pedido_inicio',
                    callback: (value) => {
                        return value !== null
                            ? moment(value)
                                  .utcOffset('+0300')
                                  .format('DD/MM/YYYY')
                            : 'No hay fecha establecida';
                    },
                },
                'Fecha Final del Pedido': {
                    field: 'fecha_pedido_final',
                    callback: (value) => {
                        return value !== null
                            ? moment(value)
                                  .utcOffset('+0300')
                                  .format('DD/MM/YYYY')
                            : 'No hay fecha establecida';
                    },
                },
                'Fecha Inical Efectiva': {
                    field: 'fecha_efectiva_inicio',
                    callback: (value) => {
                        return value !== '' && value !== null
                            ? moment(value)
                                  .utcOffset('+0300')
                                  .format('DD/MM/YYYY')
                            : 'No hay fecha establecida';
                    },
                },
                'Fecha Final Efectiva': {
                    field: 'fecha_efectiva_final',
                    callback: (value) => {
                        return value !== '' && value !== null
                            ? moment(value)
                                  .utcOffset('+0300')
                                  .format('DD/MM/YYYY')
                            : 'No hay fecha establecida';
                    },
                },
                'Fecha de la Interrupción': {
                    field: 'fecha_interrupcion_inicio',
                    callback: (value) => {
                        return value !== '' && value !== null
                            ? moment(value)
                                  .utcOffset('+0300')
                                  .format('DD/MM/YYYY')
                            : 'No hay fecha establecida';
                    },
                },
                Observacion: {
                    field: 'observacion',
                    callback: (value) => {
                        return value;
                    },
                },
            },
            mensajeSancion: false,
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
            licTitulo: 'Resumen de Licencias',
            isBusy: false,
            licencias: [],
            licencia: {},
            hoverVer: false,
            hoverSV: false,
            hoverPV: false,
            hoverMod: false,
            hoverModG: false,
            hoverDesvisar: false,
            hoverInterrumpir: false,
            hoverBorrar: false,
            crearConSancion: false,
        };
    },
    methods: {
        permisoCrearConSancion() {
            var resultado = false;
            console.info(`El agente tiene sancion? ${this.existeSancion}`);
            if (this.tipoLicencia == 18 || this.tipoLicencia == 19) {
                resultado = this.existeSancion == true ? true : false;
                this.mensajeSancion = resultado;
                if (this.role_display === 'Formacion y Capacitacion') {
                    resultado = false;
                }
            }
            return resultado;
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
            return _.findIndex(this.permisos, ['name', cadena]) >= 0
                ? true
                : false;
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
                        doc.internal.pageSize.width * 0.7,
                        30,
                    );
                }
            };

            var vm = this;
            var doc = new jsPDF('l', 'pt', 'a4');
            doc.setFont('times');

            var texto = 'Licencias del Agente';
            var xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            // Informacion del Agente

            var img = new Image();
            img.src = '../images/rrhh_siprosa.png';
            doc.addImage(img, 'JPEG', 50, 60, 130, 44);
            img.src = '../images/logo_ministerio.jpg';
            doc.addImage(
                img,
                'JPEG',
                doc.internal.pageSize.width * 0.8,
                60,
                130,
                24,
            );
            doc.text(texto, xOffset, 120);
            texto = 'Datos del Agente';
            xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            doc.text(texto, xOffset, 150);

            let agentes = [];
            let agente = this.$store.getters['agente/agente'];

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
            let licencias = _.cloneDeep(
                this.$store.getters['licencia/licencias'],
            );
            _.each(licencias, function (licencia, idx) {
                licencias[idx].primer_visado =
                    licencia.primer_visado == true
                        ? 'Si'
                        : licencia.primer_visado === false
                        ? 'No'
                        : 'No esta visado';
                licencias[idx].segundo_visado =
                    licencia.segundo_visado == true
                        ? 'Si'
                        : licencia.segundo_visado === false
                        ? 'No'
                        : 'No esta visado';
                licencias[idx].cuarta_visado =
                    licencia.cuarta_visado == true
                        ? 'Si'
                        : licencia.cuarta_visado === false
                        ? 'No'
                        : 'Sin Interrupcion';
                let fpi =
                    licencia.fecha_pedido_inicio != null
                        ? licencia.fecha_pedido_inicio.substr(0, 10).split('-')
                        : null;
                let fpf =
                    licencia.fecha_pedido_final != null
                        ? licencia.fecha_pedido_final.substr(0, 10).split('-')
                        : null;
                let fei =
                    licencia.fecha_efectiva_inicio != null
                        ? licencia.fecha_efectiva_inicio
                              .substr(0, 10)
                              .split('-')
                        : null;
                let fef =
                    licencia.fecha_efectiva_final != null
                        ? licencia.fecha_efectiva_final.substr(0, 10).split('-')
                        : null;
                let fii =
                    licencia.fecha_interrupcion_inicio != null
                        ? licencia.fecha_interrupcion_inicio
                              .substr(0, 10)
                              .split('-')
                        : null;
                licencias[idx].fecha_pedido_inicio =
                    fpi != null
                        ? new Date(
                              fpi[0],
                              parseInt(fpi[1]) - 1,
                              fpi[2],
                          ).toLocaleDateString()
                        : 'No hay fecha';
                licencias[idx].fecha_pedido_final =
                    fpf != null
                        ? new Date(
                              fpf[0],
                              parseInt(fpf[1]) - 1,
                              fpf[2],
                          ).toLocaleDateString()
                        : 'No hay fecha';
                licencias[idx].fecha_efectiva_inicio =
                    fei != null
                        ? new Date(
                              fei[0],
                              parseInt(fei[1]) - 1,
                              fei[2],
                          ).toLocaleDateString()
                        : 'No hay fecha';
                licencias[idx].fecha_efectiva_final =
                    fef != null
                        ? new Date(
                              fef[0],
                              parseInt(fef[1]) - 1,
                              fef[2],
                          ).toLocaleDateString()
                        : 'No hay fecha';
                licencias[idx].fecha_interrupcion_inicio =
                    fii != null
                        ? new Date(
                              fii[0],
                              parseInt(fii[1]) - 1,
                              fii[2],
                          ).toLocaleDateString()
                        : 'No hay fecha';
            });
            texto = 'Listado de Licencias';
            xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            var finalY = doc.previousAutoTable.finalY;
            doc.text(texto, xOffset, finalY + 20);
            columnas = [
                { title: 'Dias', dataKey: 'dias' },
                { title: 'Nº de Lic.', dataKey: 'idlicencia' },
                { title: 'Tipo de Licencia', dataKey: 'descripcion' },
                { title: 'Primer Visado', dataKey: 'primer_visado' },
                { title: 'Segundo Visado', dataKey: 'segundo_visado' },
                { title: 'Interrupcion', dataKey: 'cuarta_visado' },
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
                {
                    title: 'Fecha de la Interrupción',
                    dataKey: 'fecha_interrupcion_inicio',
                },
            ];

            doc.autoTable(columnas, licencias, {
                styles: { overflow: 'linebreak', fontSize: 7 },
                startY: finalY + 40,
            });
            addHeaders(doc);
            addFooters(doc);
            doc.save('licencias.pdf');
        },
        async borrarLicencia(item) {
            if (window.confirm('¿Desea borrar la licencia seleccionada?')) {
                await this.$store.dispatch('licencia/deleteLicencia', item);
                _.remove(
                    this.$store.state.licencia.licencias,
                    (f) => f.idlicencia === item.idlicencia,
                );
                this.licencias = Array.from(
                    this.$store.getters['licencia/obtenerLicencias'],
                );
                this.actualizarTabla();
                this.$emit('actualizar-tabla', true);
            }
        },
        agregarLicencia() {
            this.$emit('addLicencia', { licencia: 0, visar: 0 });
        },
        modificarLicencia(item) {
            this.$emit('addLicencia', { licencia: item.idlicencia, visar: 3 });
        },
        primerVisado(item) {
            this.$emit('addLicencia', { licencia: item.idlicencia, visar: 1 });
        },
        segundoVisado(item) {
            this.$emit('addLicencia', { licencia: item.idlicencia, visar: 2 });
        },

        async desvisarLicencia(item) {
            if (window.confirm('¿Desea desvisar la licencia seleccionada?')) {
                await this.$store.dispatch('licencia/desvisarLicencia', item);
                this.buscarLicencias();
                this.actualizarTabla();
                this.$emit('actualizar-tabla', true);
            }
        },
        interrumpirLicencia(item) {
            this.$emit('addLicencia', { licencia: item.idlicencia, visar: 5 });
        },
        verLicencia(item) {
            this.$emit('addLicencia', { licencia: item.idlicencia, visar: 6 });
        },
        modificarGerenciaLicencia(item) {
            this.$emit('addLicencia', { licencia: item.idlicencia, visar: 7 });
        },
        actualizarTabla() {
            this.licencias = Array.from(this.get_licencias);
        },
        async buscarLicencias() {
            await this.$store.dispatch('licencia/getLicencias', {
                idagente: this.$store.state.agente.agente.idagente,
                tipoLicencia: this.tipoLicencia,
            });
            this.licencias = Array.from(
                this.$store.getters['licencia/obtenerLicencias'],
            );
        },
    },
    computed: {
        selected() {
            return _.each(this.licencias, (element) => {
                element['efector'] = this.agente.efector;
                element['codigo_nombre'] = this.agente.codigo_nombre;
                element['documento'] = this.agente.documento;
                element['apellido_nombre'] = this.agente.apellido_nombre;
            });
        },
        existeSancion() {
            return this.$store.getters['sancion/existe'];
        },
        user() {
            return this.$store.getters['user/user'].nombreusuario;
        },
        sancion() {
            return this.$store.getters['sancion/existe'];
        },
        headers() {
            return [
                {
                    value: 'dias',
                    text: 'Dias de Licencia',
                    sortable: true,
                    filter: (value) => {
                        if (!this.dias) return true;
                        return value.toString().indexOf(this.dias) !== -1;
                    },
                },
                {
                    value: 'idlicencia',
                    text: 'Numero de Licencia',
                    sortable: true,
                    filter: (value) => {
                        if (!this.idlicencia) return true;
                        return value.toString().indexOf(this.idlicencia) !== -1;
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
                    text: 'Interrumpido',
                    sortable: true,
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
                },
                {
                    value: 'fecha_interrupcion_inicio',
                    text: 'Fecha Interrupcion',
                    sortable: true,
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
                },
                {
                    value: 'action',
                    text: 'Comandos',
                    sortable: false,
                },
            ];
        },
        get_licencias() {
            return this.$store.getters['licencia/obtenerLicencias'];
        },
        hayAgente() {
            return this.$store.getters['agente/foundAgente'];
        },
        role() {
            return this.$store.getters['user/role'];
        },
        role_display() {
            return this.$store.getters['user/role_display'];
        },
        permisos() {
            return this.$store.getters['user/permisos'];
        },
        agente() {
            return this.$store.getters['agente/agente'];
        },
    },
};
</script>

<style>
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
