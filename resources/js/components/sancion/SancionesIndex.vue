<template>
    <div>
        <v-app>
            <v-container fluid>
                <v-row>
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
                            :headers="headers"
                            :items="sanciones"
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
                            <template v-slot:body.prepend>
                                <tr>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="expediente"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="resolucion"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="reseña"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="conclusion"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="acuerdo"
                                        ></v-text-field>
                                    </td>

                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="nombreusuario"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="fecha_inicio"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="fecha_final"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="created_at"
                                        ></v-text-field>
                                    </td>
                                </tr>
                            </template>

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
                                    <div v-show="!habilitarBuscar">
                                        <download-excel
                                            class="m-2 v-btn v-btn--contained theme--light v-size--default yellow darken-5"
                                            :data="selected"
                                            :fields="json_fields"
                                            name="filename.xls"
                                            @change="actualizarTabla"
                                            v-if="can('exportarExcel-sancion')"
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
                                        v-if="can('exportarPDF-sancion')"
                                    >
                                        <v-icon left
                                            >mdi-file-pdf-outline
                                        </v-icon>
                                        Exportar PDF
                                    </v-btn>

                                    <v-btn
                                        @change="actualizarTabla"
                                        @click="buscarSanciones"
                                        class="m-2"
                                        color="primary"
                                        dark
                                        :disabled="habilitarBuscar"
                                    >
                                        <i class="fas fa-search mr-2"></i>Buscar
                                        Sanciones
                                    </v-btn>

                                    <v-dialog
                                        max-width="500px"
                                        v-model="dialog"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <div v-show="can('crear-sancion')">
                                                <div
                                                    v-show="
                                                        agente.fhasta === null
                                                    "
                                                >
                                                    <v-btn
                                                        class="m-2"
                                                        color="success"
                                                        dark
                                                        v-on="on"
                                                        :disabled="
                                                            habilitarBuscar
                                                        "
                                                    >
                                                        <i
                                                            class="fas fa-plus-circle mr-2"
                                                        ></i
                                                        >Nueva Sancion
                                                    </v-btn>
                                                </div>
                                            </div>
                                        </template>
                                        <v-card>
                                            <v-form
                                                ref="form"
                                                v-model="valid"
                                            >
                                                <v-card-title
                                                    class="font-weight-regular blue-grey white--text headline"
                                                >
                                                    <span class="white--text">{{
                                                        formTitle
                                                    }}</span>
                                                </v-card-title>

                                                <v-card-text>
                                                    <v-container>
                                                        <div
                                                            class="d-flex flex-column"
                                                        >
                                                            <!--resolucion, "reseña", conclusion, acuerdo, expediente, sale, created_at-->
                                                            <v-card>
                                                                <v-text-field
                                                                    label="EXPEDIENTE"
                                                                    :rules="
                                                                        genericRules
                                                                    "
                                                                    v-model="
                                                                        editedItem.expediente
                                                                    "
                                                                ></v-text-field>
                                                            </v-card>
                                                            <v-card>
                                                                <v-text-field
                                                                    label="RESOLUCION DE INST. SUMARIO"
                                                                    v-model="
                                                                        editedItem.resolucion
                                                                    "
                                                                    counter
                                                                ></v-text-field>
                                                            </v-card>
                                                            <v-card>
                                                                <v-text-field
                                                                    label="CAUSAL"
                                                                    v-model="
                                                                        editedItem.reseña
                                                                    "
                                                                ></v-text-field>
                                                            </v-card>
                                                            <v-card>
                                                                <v-text-field
                                                                    label="DICTAMEN DE CONCLUSION"
                                                                    v-model="
                                                                        editedItem.conclusion
                                                                    "
                                                                ></v-text-field>
                                                            </v-card>
                                                            <v-card>
                                                                <v-text-field
                                                                    label="RESOLUCION DE CONCLUSION"
                                                                    v-model="
                                                                        editedItem.acuerdo
                                                                    "
                                                                ></v-text-field>
                                                            </v-card>
                                                            <v-card>
                                                                <v-menu
                                                                    :close-on-content-click="
                                                                        false
                                                                    "
                                                                    full-width
                                                                    max-width="290px"
                                                                    min-width="290px"
                                                                    offset-y
                                                                    ref="menuFechaInicio"
                                                                    transition="scale-transition"
                                                                    v-model="
                                                                        menuFechaInicio
                                                                    "
                                                                >
                                                                    <template
                                                                        v-slot:activator="{
                                                                            on,
                                                                            attrs,
                                                                        }"
                                                                    >
                                                                        <v-text-field
                                                                            :value="
                                                                                formatearFechaInicio
                                                                            "
                                                                            hint="DD/MM/YYYY"
                                                                            label="Fecha de Inicio de la Sancion"
                                                                            persistent-hint
                                                                            readonly
                                                                            v-bind="
                                                                                attrs
                                                                            "
                                                                            prepend-icon="mdi-calendar"
                                                                            v-on="
                                                                                on
                                                                            "
                                                                        ></v-text-field>
                                                                    </template>
                                                                    <v-date-picker
                                                                        ref="pickerFechaInicio"
                                                                        @input="
                                                                            menuFechaInicio = false
                                                                        "
                                                                        locale="es-AR"
                                                                        v-model="
                                                                            editedItem.fecha_inicio
                                                                        "
                                                                        no-title
                                                                        @change="
                                                                            saveInicio
                                                                        "
                                                                        :max="
                                                                            new Date(
                                                                                '2030',
                                                                            )
                                                                                .toISOString()
                                                                                .substr(
                                                                                    0,
                                                                                    10,
                                                                                )
                                                                        "
                                                                        min="1950-01-01"
                                                                    ></v-date-picker>
                                                                </v-menu>
                                                            </v-card>
                                                            <v-card>
                                                                <v-menu
                                                                    :close-on-content-click="
                                                                        false
                                                                    "
                                                                    full-width
                                                                    max-width="290px"
                                                                    min-width="290px"
                                                                    offset-y
                                                                    ref="menuFechaFinal"
                                                                    transition="scale-transition"
                                                                    v-model="
                                                                        menuFechaFinal
                                                                    "
                                                                >
                                                                    <template
                                                                        v-slot:activator="{
                                                                            on,
                                                                            attrs,
                                                                        }"
                                                                    >
                                                                        <v-text-field
                                                                            :value="
                                                                                formatearFechaFinal
                                                                            "
                                                                            v-bind="
                                                                                attrs
                                                                            "
                                                                            hint="DD/MM/YYYY"
                                                                            label="Fecha de Finalizacion de la Sancion"
                                                                            persistent-hint
                                                                            readonly
                                                                            prepend-icon="mdi-calendar"
                                                                            v-on="
                                                                                on
                                                                            "
                                                                        ></v-text-field>
                                                                    </template>
                                                                    <v-date-picker
                                                                        ref="pickerFechaFinalizacion"
                                                                        @input="
                                                                            menuFechaFinal = false
                                                                        "
                                                                        locale="es-AR"
                                                                        no-title
                                                                        v-model="
                                                                            editedItem.fecha_final
                                                                        "
                                                                        @change="
                                                                            saveFinalizacion
                                                                        "
                                                                        :max="
                                                                            new Date(
                                                                                '2030',
                                                                            )
                                                                                .toISOString()
                                                                                .substr(
                                                                                    0,
                                                                                    10,
                                                                                )
                                                                        "
                                                                        min="1950-01-01"
                                                                    ></v-date-picker>
                                                                </v-menu>
                                                            </v-card>
                                                        </div>
                                                    </v-container>
                                                </v-card-text>

                                                <v-card-actions
                                                    class="blue-grey white--text"
                                                >
                                                    <v-spacer></v-spacer>
                                                    <v-btn
                                                        @click="close"
                                                        color="red lighten-5"
                                                    >
                                                        <span>Cancelar</span>
                                                    </v-btn>
                                                    <v-btn
                                                        @click="save"
                                                        color="green lighten-4"
                                                    >
                                                        <span>Guardar</span>
                                                    </v-btn>
                                                </v-card-actions>
                                            </v-form>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                            </template>
                            <template v-slot:item.action="{ item }">
                                <div v-if="can('editar-sancion')">
                                    <div v-if="agente.fhasta === null">
                                        <v-icon
                                            @click="editItem(item)"
                                            class="mr-2"
                                            small
                                            >mdi-account-edit
                                        </v-icon>
                                    </div>
                                </div>
                                <div v-if="can('borrar-sancion')">
                                    <div v-if="agente.fhasta === null">
                                        <v-icon
                                            @click="deleteItem(item)"
                                            small
                                            >mdi-delete
                                        </v-icon>
                                    </div>
                                </div>
                            </template>
                            <template v-slot:no-data
                                >No hay datos cargados
                            </template>
                        </v-data-table>
                    </v-col>
                </v-row>
            </v-container>
        </v-app>
    </div>
</template>

<script>
import _ from 'lodash';
import jsPDF from 'jspdf';
import 'jspdf-autotable';

import TarjetaAgente from '../utils/TarjetaAgente.vue';
import moment from 'moment';

export default {
    name: 'Sancion',
    components: {
        TarjetaAgente,
    },
    data: () => ({
        json_fields: {
            Efector: 'efector',
            Servicio: 'codigo_nombre',
            Documento: 'documento',
            'Apellido y Nombre': 'apellido_nombre',
            EXPEDIENTE: 'expediente',
            'RESOL. DE INST. SUMARIO': 'resolucion',
            CAUSAL: 'reseña',
            'DICT. DE CONCL.': {
                field: 'conclusion',
                callback: (value) => {
                    return value === null ? 0 : value;
                },
            },
            'RESOL. DE CONCL.': {
                field: 'acuerdo',
                callback: (value) => {
                    return value === null ? 0 : value;
                },
            },
            'Usuario Responsable': 'nombreusuario',
            'Fecha Inicio Sanción': {
                field: 'fecha_inicio',
                callback: (value) => {
                    return moment(value).format('DD/MM/YYYY');
                },
            },
            'Fecha Fin Sanción': {
                field: 'fecha_final',
                callback: (value) => {
                    return moment(value).format('DD/MM/YYYY');
                },
            },
            'Fecha Creación Sanción': {
                field: 'created_at',
                callback: (value) => {
                    return moment(value).format('DD/MM/YYYY');
                },
            },
        },
        genericRules: [(v) => !!v || 'El campo es requerido'],
        habilitarBuscar: true,
        titulo: 'Gestion de Sanciones',
        valid: false,
        idagente: 0,
        dialog: false,
        search: '',
        menuFechaInicio: false,
        menuFechaFinal: false,
        fecha_inicio: null,
        fecha_final: null,
        resolucion: '',
        idsancion: '',
        reseña: '',
        acuerdo: '',
        expediente: '',
        conclusion: '',
        created_at: '',
        nombreusuario: '',
        sanciones: [],
        editedIndex: -1,
        editedItem: {
            idsancion: 0,
            idagente: 0,
            resolucion: '',
            reseña: '',
            conclusion: '',
            acuerdo: '',
            expediente: '',
            fecha_inicio: null,
            fecha_final: null,
            created_at: new Date().toISOString().substr(0, 10),
        },
        defaultItem: {
            idsancion: 0,
            idagente: 0,
            resolucion: '',
            reseña: '',
            conclusion: '',
            acuerdo: '',
            expediente: '',
            fecha_inicio: null,
            fecha_final: null,
            created_at: new Date().toISOString().substr(0, 10),
        },
    }),

    computed: {
        permisos() {
            return this.$store.getters['user/permisos'];
        },
        selected() {
            return _.each(this.sanciones, (element) => {
                element['efector'] = this.agente.efector;
                element['codigo_nombre'] = this.agente.codigo_nombre;
                element['documento'] = this.agente.documento;
                element['apellido_nombre'] = this.agente.apellido_nombre;
            });
        },
        user() {
            return this.$store.getters['user/user'].nombreusuario;
        },
        idusuario() {
            return this.$store.getters['user/user'].idusuario;
        },
        obtenerSanciones() {
            return this.$store.getters['sancion/sanciones'];
        },
        agente() {
            return this.$store.getters['agente/agente'];
        },
        formatearFechaFinal() {
            return this.editedItem.fecha_final
                ? moment(this.editedItem.fecha_final).format('DD/MM/YYYY')
                : '';
        },
        formatearFechaInicio() {
            return this.editedItem.fecha_inicio
                ? moment(this.editedItem.fecha_inicio).format('DD/MM/YYYY')
                : '';
        },
        formatearFecha() {
            return this.editedItem.created_at
                ? moment(this.editedItem.created_at).format('DD/MM/YYYY')
                : '';
        },
        headers() {
            return [
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
                { text: 'Comandos', value: 'action', sortable: false },
            ];
        },
        formTitle() {
            return this.editedIndex === -1
                ? 'Nueva Sancion'
                : 'Modificar Sancion';
        },
    },

    watch: {
        dialog(val) {
            val || this.close();
        },
        menuFechaInicio(val) {
            val &&
                setTimeout(
                    () => (this.$refs.pickerFechaInicio.activePicker = 'YEAR'),
                );
        },

        menuFechaFinal(val) {
            val &&
                setTimeout(
                    () =>
                        (this.$refs.pickerFechaFinalizacion.activePicker =
                            'YEAR'),
                );
        },
    },

    created() {
        this.$store.state.sancion.sanciones = [];
    },

    methods: {
        saveInicio(date) {
            this.$refs.menuFechaInicio.save(date);
        },
        saveFinalizacion(date) {
            this.$refs.menuFechaFinal.save(date);
        },
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
                        doc.internal.pageSize.width * 0.75,
                        30,
                    );
                }
            };

            var vm = this;
            var doc = new jsPDF('l', 'pt');
            // Informacion del Agente
            var img = new Image();
            img.src = 'images/rrhh_siprosa.png';
            doc.addImage(img, 'JPEG', 50, 60, 130, 44);
            img.src = 'images/logo_ministerio.jpg';
            doc.addImage(
                img,
                'JPEG',
                doc.internal.pageSize.width * 0.8,
                60,
                130,
                24,
            );
            doc.setFont('times');
            var texto = 'Sanciones del Agente';
            var xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            doc.text(texto, xOffset, 90);
            texto = 'Datos del Agente';
            xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            doc.text(texto, xOffset, 120);

            let agentes = [];
            let agente = _.cloneDeep(this.$store.getters['agente/agente']);

            agentes.push(agente);
            let columnas = [
                { title: 'Documento', dataKey: 'documento' },
                { title: 'Nombre Completo', dataKey: 'apellido_nombre' },
                { title: 'Efector', dataKey: 'efector' },
                { title: 'Servicio', dataKey: 'codigo_nombre' },
            ];
            doc.autoTable(columnas, agentes, {
                startY: 120 + 25,
            });

            if (this.sanciones.length < 1) {
                var texto = 'No tiene sanciones';
                var xOffset =
                    doc.internal.pageSize.width / 2 -
                    (doc.getStringUnitWidth(texto) *
                        doc.internal.getFontSize()) /
                        2;
                doc.text(texto, xOffset, 150 + 25 + 40 + 60);
            } else {
                var finalY = doc.previousAutoTable.finalY;
                texto = 'Listado de Sanciones';
                xOffset =
                    doc.internal.pageSize.width / 2 -
                    (doc.getStringUnitWidth(texto) *
                        doc.internal.getFontSize()) /
                        2;
                doc.text(texto, xOffset, finalY + 20);
                columnas = [
                    { title: 'EXPEDIENTE', dataKey: 'expediente' },
                    { title: 'RESOL. DE INST. SUMARIO', dataKey: 'resolucion' },
                    { title: 'CAUSAL', dataKey: 'reseña' },
                    { title: 'DICT. DE CONCL.', dataKey: 'conclusion' },
                    { title: 'RESOL. DE CONCL.', dataKey: 'acuerdo' },
                    { title: 'Usuario Responsable', dataKey: 'nombreusuario' },
                    { title: 'Fecha Inicio Sanción', dataKey: 'fecha_inicio' },
                    { title: 'Fecha Fin Sanción', dataKey: 'fecha_final' },
                    { title: 'Fecha Creación Sanción', dataKey: 'created_at' },
                ];
                var valores = _.cloneDeep(this.sanciones);
                valores = _.each(valores, (v) => {
                    let fecha_inicio =
                        v.fecha_inicio != null
                            ? v.fecha_inicio.substr(0, 10).split('-')
                            : null;
                    v.fecha_inicio =
                        fecha_inicio != null
                            ? new Date(
                                  fecha_inicio[0],
                                  parseInt(fecha_inicio[1]) - 1,
                                  fecha_inicio[2],
                              ).toLocaleDateString()
                            : 'No hay fecha';
                    let fecha_final =
                        v.fecha_final != null
                            ? v.fecha_final.substr(0, 10).split('-')
                            : null;
                    v.fecha_final =
                        fecha_final != null
                            ? new Date(
                                  fecha_final[0],
                                  parseInt(fecha_final[1]) - 1,
                                  fecha_final[2],
                              ).toLocaleDateString()
                            : 'No hay fecha';
                    let created_at =
                        v.created_at != null
                            ? v.created_at.substr(0, 10).split('-')
                            : null;
                    v.created_at =
                        created_at != null
                            ? new Date(
                                  created_at[0],
                                  parseInt(created_at[1]) - 1,
                                  created_at[2],
                              ).toLocaleDateString()
                            : 'No hay fecha';
                });
                doc.autoTable(columnas, valores, {
                    startY: finalY + 40,
                });
            }
            addHeaders(doc);
            addFooters(doc);
            doc.save('sanciones.pdf');
        },
        validate() {
            this.$refs.form.validate();
        },
        reset() {
            this.$refs.form.reset();
        },
        resetValidation() {
            this.$refs.form.resetValidation();
        },
        habilitarBusqueda(evt) {
            this.habilitarBuscar = evt;
            this.buscarSanciones();
        },
        volverHome() {
            //location.replace("http://siarhu.testing/rrhh");
        },
        async actualizarSancion(obj) {
            await this.$store
                .dispatch('sancion/updateSancion', obj)
                .then(() => {
                    console.log('Exito al modificar Sancion');
                    this.buscarSanciones();
                })
                .catch((err) => {
                    console.log('Error al modificar Sancion' + err);
                });
        },
        async guardarSancion(obj) {
            obj.idusuario = this.idusuario;
            await this.$store
                .dispatch('sancion/postSancion', obj)
                .then(() => {
                    console.log('Exito al crear Sancion');
                    this.buscarSanciones();
                })
                .catch((err) => {});
        },
        async borrarSancion(item) {
            if (window.confirm('¿Desea borrar la sancion seleccionada?')) {
                await this.$store.dispatch('sancion/deleteSancion', item);
                _.remove(
                    this.obtenerSanciones,
                    (f) => f.idsancion == item.idsancion,
                );
                this.sanciones = Array.from(this.obtenerSanciones);
                this.buscarSanciones();
            }
        },
        async buscarSanciones() {
            await this.$store.dispatch(
                'sancion/getSanciones',
                this.agente.idagente,
            );
            this.sanciones = Array.from(this.obtenerSanciones);
            this.idagente = this.agente.idagente;
        },
        actualizarTabla() {
            this.sanciones = Array.from(this.obtenerSanciones);
        },
        initialize() {},
        editItem(item) {
            this.editedIndex = this.sanciones.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
        },
        deleteItem(item) {
            this.borrarSancion(item.idsancion);
        },
        close() {
            this.dialog = false;
            setTimeout(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            }, 300);
        },
        save() {
            if (this.valid) {
                this.editedItem.idagente = this.agente.idagente;
                if (this.editedIndex > -1) {
                    this.actualizarSancion(this.editedItem);
                    Object.assign(
                        this.sanciones[this.editedIndex],
                        this.editedItem,
                    );
                } else {
                    this.guardarSancion(this.editedItem);
                    this.sanciones.push(this.editedItem);
                }
                this.close();
            }
        },
    },
};
</script>
