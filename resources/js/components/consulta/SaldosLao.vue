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
                        <span class="white--text"
                            >Consulta de Saldos de LAO</span
                        >
                    </v-card-title>
                    <v-spacer></v-spacer>
                    <v-card-subtitle class="pa-5">
                        <v-card class="d-flex flex-row mb-6">
                            <v-col
                                class="d-flex"
                                cols="12"
                                sm="6"
                            >
                                <v-autocomplete
                                    v-model="efectorSelected"
                                    return-object
                                    :items="organismos"
                                    item-text="label"
                                    item-value="value"
                                    label="Efector"
                                    outlined
                                ></v-autocomplete>
                            </v-col>
                        </v-card>
                    </v-card-subtitle>
                    <v-spacer></v-spacer>

                    <v-spacer></v-spacer>
                    <v-card-text>
                        <v-data-table
                            v-model="selected"
                            :headers="headers"
                            :items="antiguedades"
                            show-select
                            class="elevation-1"
                            sort-by="documento"
                            item-key="idantiguedad"
                            :loading="loading"
                            loading-text="Cargando Datos... Por favor tenga paciencia"
                        >
                            <template v-slot:no-data
                                >No hay antiguedades cargadas
                            </template>
                            <template v-slot:item.vigente="{ item }">
                                {{ item.vigente === true ? 'Si' : 'No' }}
                            </template>
                            <template v-slot:body.prepend>
                                <tr>
                                    <td></td>
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
                                            v-model="codigoNombre"
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
                                            v-model="antiguedad"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="año"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="disponible"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="pedido"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="saldo"
                                        ></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field
                                            label="Filtro"
                                            type="text"
                                            v-model="vigente"
                                        ></v-text-field>
                                    </td>
                                </tr>
                            </template>

                            <template v-slot:top>
                                <v-toolbar
                                    color="white"
                                    flat
                                >
                                    <v-toolbar-title>Consulta</v-toolbar-title>
                                    <v-divider
                                        class="mx-4"
                                        inset
                                        vertical
                                    ></v-divider>
                                    <v-spacer></v-spacer>

                                    <v-btn-toggle rounded>
                                        <!-- TODO envolver el boton del excel con otro de PDF y crear los permisos -->
                                        <v-btn
                                            v-if="mostrarDebug"
                                            @click="
                                                vigenteAntiguedades(
                                                    efectorSelected.value,
                                                    true,
                                                )
                                            "
                                            class="m-2"
                                            color="blue darken-5"
                                        >
                                            <v-icon
                                                color="dark"
                                                left
                                                >mdi-file-find-outline</v-icon
                                            >
                                            Vigente
                                        </v-btn>
                                        <v-btn
                                            v-if="mostrarDebug"
                                            @change="actualizarTabla"
                                            @click="
                                                vigenteAntiguedades(
                                                    efectorSelected.value,
                                                    false,
                                                )
                                            "
                                            class="m-2"
                                            color="blue darken-5"
                                        >
                                            <v-icon
                                                color="dark"
                                                left
                                                >mdi-file-find-outline</v-icon
                                            >
                                            No Vigente
                                        </v-btn>
                                        <download-excel
                                            class="m-2 v-btn v-btn--contained theme--light v-size--default yellow darken-5"
                                            :data="selected"
                                            :fields="json_fields"
                                            name="filename.xls"
                                            @change="actualizarTabla"
                                            v-if="
                                                can('exportarExcel-consultaLAO')
                                            "
                                        >
                                            <v-icon
                                                color="dark"
                                                left
                                                >mdi-file-excel-outline
                                            </v-icon>
                                            Exportar
                                        </download-excel>

                                        <v-btn
                                            @click="exportPDF"
                                            class="m-2"
                                            color="warning"
                                            dark
                                            v-if="
                                                can('exportarPDF-consultaLAO')
                                            "
                                        >
                                            <v-icon left
                                                >mdi-file-pdf-outline</v-icon
                                            >
                                            Exportar PDF
                                        </v-btn>

                                        <v-btn
                                            @change="actualizarTabla"
                                            @click="
                                                buscarAntiguedades(
                                                    efectorSelected.value,
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
                                                >mdi-file-find-outline</v-icon
                                            >
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
import _ from 'lodash';
import moment from 'moment';
import jsPDF from 'jspdf';

export default {
    name: 'SaldosLao.vue',
    data: (vm) => ({
        json_fields: {
            Efector: 'efector',
            Servicio: 'codigo_nombre',
            Documento: 'documento',
            'Apellido y Nombre': 'apellido_nombre',
            Antiguedad: 'antiguedad',
            Año: 'año',
            Disponible: 'disponible',
            Pedido: {
                field: 'pedido',
                callback: (value) => {
                    return value === null ? 0 : value;
                },
            },
            Saldo: {
                field: 'saldo',
                callback: (value) => {
                    return value === null ? 0 : value;
                },
            },
            Vigente: {
                field: 'vigente',
                callback: (value) => {
                    return value === false ? 'No' : 'Si';
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
        mostrarDebug: false,
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
        codigoNombre: '',
        iddependencia: 0,
        apellido_nombre: '',
        antiguedad: '',
        año: '',
        disponible: '',
        pedido: '',
        saldo: '',
        vigente: '',
        licTitulo: 'Consulta de Lao',
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
                        doc.internal.pageSize.width * 0.75,
                        20,
                    );
                }
            };

            var vm = this;
            var doc = new jsPDF('l', 'pt', 'a4');
            doc.setFont('times');

            var texto = 'Consulta de Saldo de Anuales Ordinarias';
            var xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
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
            doc.text(texto, xOffset, 120);
            texto = 'Efector Seleccionado';
            xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            doc.text(texto, xOffset, 150);
            texto = this.efectorSelected.label;
            doc.text(texto, 50, 150 + 25);

            let antiguedades = _.cloneDeep(this.selected);

            _.each(antiguedades, (antiguedad) => {
                antiguedad['pedido'] =
                    antiguedad['pedido'] === null ? 0 : antiguedad['pedido'];
                antiguedad['saldo'] =
                    antiguedad['saldo'] === null ? 0 : antiguedad['saldo'];
                antiguedad['vigente'] =
                    antiguedad['vigente'] === true ? 'Si' : 'No';
            });

            texto = 'Listado de Saldos';
            xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            doc.text(texto, xOffset, 150 + 25 + 20);

            let columnas = [
                { title: 'Efector', dataKey: 'efector' },
                { title: 'Servicio', dataKey: 'codigo_nombre' },
                { title: 'Documento', dataKey: 'documento' },
                { title: 'Apellido y Nombre', dataKey: 'apellido_nombre' },
                { title: 'Antiguedad', dataKey: 'antiguedad' },
                { title: 'Año', dataKey: 'año' },
                { title: 'Disponible', dataKey: 'disponible' },
                { title: 'Pedido', dataKey: 'pedido' },
                { title: 'Saldo', dataKey: 'saldo' },
                { title: 'Vigente', dataKey: 'vigente' },
            ];
            antiguedades = antiguedades.sort(function (a, b) {
                return a.documento - b.documento; //sort by date ascending
            });

            doc.autoTable(columnas, antiguedades, {
                styles: { overflow: 'linebreak', fontSize: 7 },
                startY: 150 + 25 + 20 + 40,
            });
            addHeaders(doc);
            addFooters(doc);
            doc.save('Consulta_LAO.pdf');
            this.selected = [];
        },
        buscar() {},
        actualizarTabla() {
            // this.loading = true
            // this.buscarAntiguedades(
            //     this.efectorSelected.value,
            //     this.fecha_desde,
            //     this.fecha_hasta
            // )
        },
        async buscarOrganismos() {
            // Buscar organismos de acuerdo a la dependencia seleccionada
            try {
                let dependencia = this.dependencia;
                if (Object.keys(dependencia).length === 0) {
                    dependencia = this.dependencia_padre;
                }
                await this.$store.dispatch('antiguedad/getOrganismos', {
                    dependencia,
                });
                console.log('Organismos cargados');
                console.dir(this.organismos);
            } catch (error) {
                console.error('Error al cargar organismos');
            }
        },
        async vigenteAntiguedades(iddependencia, vigente) {
            await this.$store.dispatch('antiguedad/vigenteAntiguedades', {
                iddependencia: iddependencia,
                vigente: vigente,
            });
        },
        async buscarAntiguedades(iddependencia, fecha_desde, fecha_hasta) {
            this.loading = true;
            this.selected = [];
            await this.$store
                .dispatch('antiguedad/getAntiguedadConsulta', {
                    iddependencia: iddependencia,
                    fecha_desde: fecha_desde,
                    fecha_hasta: fecha_hasta,
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
        efectorSelected: function (newValue, oldValue) {
            this.selected = [];
        },
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
    created() {
        this.buscarOrganismos();
    },
    computed: {
        dependencia() {
            return this.$store.getters['user/dependencia'];
        },
        dependencia_padre() {
            return this.$store.getters['user/dependencia_padre'];
        },
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
        antiguedades() {
            return this.$store.getters['antiguedad/antiguedades_consulta'];
        },
        organismos() {
            return this.$store.getters['antiguedad/organismos'];
        },
        headers() {
            return [
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
                    value: 'antiguedad',
                    text: 'Antiguedad',
                    sortable: true,
                    filter: (value) => {
                        if (!this.antiguedad) return true;
                        return value.toString().indexOf(this.antiguedad) !== -1;
                    },
                },
                {
                    value: 'año',
                    text: 'Año',
                    sortable: true,
                    filter: (value) => {
                        if (!this.año) return true;
                        return value.toString().indexOf(this.año) !== -1;
                    },
                },
                {
                    value: 'disponible',
                    text: 'Disponible',
                    sortable: true,
                    filter: (value) => {
                        if (!this.disponible) return true;
                        return value.toString().indexOf(this.disponible) !== -1;
                    },
                },
                {
                    value: 'pedido',
                    text: 'Pedidos',
                    sortable: true,
                    filter: (value) => {
                        if (!this.pedido) return true;
                        return value.toString().indexOf(this.pedido) !== -1;
                    },
                },
                {
                    value: 'saldo',
                    text: 'Saldo',
                    sortable: true,
                    filter: (value) => {
                        if (!this.saldo) return true;
                        return value.toString().indexOf(this.saldo) !== -1;
                    },
                },
                {
                    value: 'vigente',
                    text: 'Vigente',
                    sortable: true,
                    filter: (value) => {
                        if (!this.vigente) return true;
                        value = value === true ? 'Si' : 'No';
                        return value.toString().indexOf(this.vigente) !== -1;
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
</style>
