<template>
    <v-row>
        <v-col>
            <v-data-table
                :headers="headers"
                :items="expedientes"
                item-key="idgrupoFamiliar"
                sort-by="idgrupoFamiliar"
            >
                <template v-slot:item.created_at="{ item }"
                    >{{ item.created_at | fechaAcomodada }}
                </template>
                <template v-slot:item.updated_at="{ item }"
                    >{{ item.updated_at | fechaAcomodada }}
                </template>
                <template v-slot:item.vencimiento="{ item }"
                    >{{ item.vencimiento | fechaAcomodada }}
                </template>
                <template v-slot:item.aprobado="{ item }"
                    >{{ item.aprobado ? 'Si' : 'No' }}
                </template>
                <template v-slot:item.activo="{ item }">
                    <v-chip
                        :color="getColor(item.activo)"
                        dark
                        >{{ item.activo ? 'Si' : 'No' }}
                    </v-chip>
                </template>
                <template v-slot:body.prepend>
                    <tr>
                        <td>
                            <v-text-field
                                label="Filtro"
                                type="text"
                                v-model="nExpediente"
                            ></v-text-field>
                        </td>
                        <td>
                            <v-text-field
                                label="Filtro"
                                type="text"
                                v-model="created_at"
                            ></v-text-field>
                        </td>
                        <td>
                            <v-text-field
                                label="Filtro"
                                type="text"
                                v-model="updated_at"
                            ></v-text-field>
                        </td>
                        <td>
                            <v-text-field
                                label="Filtro"
                                type="text"
                                v-model="vencimiento"
                            ></v-text-field>
                        </td>
                        <td>
                            <v-text-field
                                label="Filtro"
                                type="text"
                                v-model="activo"
                            ></v-text-field>
                        </td>
                    </tr>
                </template>

                <template v-slot:top>
                    <v-toolbar
                        color="white"
                        flat
                    >
                        <v-toolbar-title>DDJJ Grupo Familiar</v-toolbar-title>
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
                                v-if="can('exportarExcel-grupoFamiliar')"
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
                            v-if="can('exportarPDF-grupoFamiliar')"
                        >
                            <i class="far fa-file-pdf mr-2"></i>Exportar DDJJ
                        </v-btn>
                        <v-btn
                            @change="actualizarTabla"
                            @click="buscarExpedientes"
                            class="m-2"
                            color="primary"
                            dark
                            :disabled="habilitarBuscar"
                        >
                            <i class="fas fa-search mr-2"></i>Buscar DDJJ
                        </v-btn>
                        <div v-if="can('crear-grupoFamiliar')">
                            <div v-show="agente.fhasta === null">
                                <v-btn
                                    @change="actualizarTabla"
                                    @click="agregarExpediente"
                                    class="m-2"
                                    color="success"
                                    dark
                                    :disabled="habilitarBuscar"
                                >
                                    <v-icon>mdi-clipboard-plus</v-icon>
                                    Nueva DDJJ
                                </v-btn>
                            </div>
                        </div>
                    </v-toolbar>
                </template>
                <template v-slot:item.action="{ item }">
                    <v-icon
                        @click="viewItem(item)"
                        large
                        >mdi-eye</v-icon
                    >

                    <div v-show="can('borrar-grupoFamiliar')">
                        <div v-if="agente.fhasta === null">
                            <div v-show="role_display === 'Gerencia'">
                                <v-icon
                                    @click="deleteItem(item)"
                                    large
                                    >mdi-delete
                                </v-icon>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-slot:no-data>No hay ddjj cargadas</template>
            </v-data-table>
        </v-col>
    </v-row>
</template>

<script>
import _ from 'lodash';
import moment from 'moment';
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

function ponerVencimientoDate(date) {
    var d = new Date(date),
        month = '' + (2 + 1),
        day = '' + 31,
        year = d.getFullYear() + 1;

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}

export default {
    props: {
        habilitarBuscar: Boolean,
    },
    watch: {
        habilitarBuscar: function (oldValue, newValue) {
            this.buscarExpedientes();
        },
    },
    name: 'TablaExpediente',
    data: function () {
        return {
            json_fields: {
                Efector: 'efector',
                Servicio: 'codigo_nombre',
                Documento: 'documento',
                'Apellido y Nombre': 'apellido_nombre',
                'Nº de DDJJ': 'nExpediente',
                Activo: {
                    field: 'activo',
                    callback: (value) => {
                        return value === true ? 'Si' : 'No';
                    },
                },
                'Fecha de Alta': {
                    field: 'created_at',
                    callback: (value) => {
                        return moment(value).format('DD/MM/YYYY');
                    },
                },
            },
            idgrupoFamiliar: '',
            nExpediente: '',
            idagente: '',
            activo: '',
            aprobado: '',
            updated_at: '',
            created_at: '',
            vencimiento: '',
            expediente: {},
            expedientes: [],
            editedIndex: -1,
            editedItem: {
                idagente: 0,
                nExpediente: 0,
                activo: '',
                aprobado: '',
                updated_at: new Date().toISOString().substr(0, 10),
                vencimiento: new Date().toISOString().substr(0, 10),
                created_at: new Date().toISOString().substr(0, 10),
            },
            defaultItem: {
                idagente: 0,
                nExpediente: 0,
                activo: '',
                aprobado: '',
                updated_at: new Date().toISOString().substr(0, 10),
                vencimiento: new Date().toISOString().substr(0, 10),
                created_at: new Date().toISOString().substr(0, 10),
            },
        };
    },
    computed: {
        permisos() {
            return this.$store.getters['user/permisos'];
        },
        selected() {
            return _.each(this.expedientes, (element) => {
                element['efector'] = this.agente.efector;
                element['codigo_nombre'] = this.agente.codigo_nombre;
                element['documento'] = this.agente.documento;
                element['apellido_nombre'] = this.agente.apellido_nombre;
            });
        },
        headers() {
            return [
                {
                    text: 'Nº de DDJJ',
                    value: 'nExpediente',
                    filter: (value) => {
                        if (!this.nExpediente) return true;
                        return (
                            value.toString().indexOf(this.nExpediente) !== -1
                        );
                    },
                },
                {
                    text: 'Fecha de Creacion',
                    value: 'created_at',
                    filter: (value) => {
                        if (!this.created_at) return true;
                        value = moment(value, 'YYYY-MM-DD').format(
                            'DD/MM/YYYY',
                        );
                        return value.toString().indexOf(this.created_at) !== -1;
                    },
                },
                {
                    text: 'Fecha de Modificacion',
                    value: 'updated_at',
                    filter: (value) => {
                        if (!this.updated_at) return true;
                        value = moment(value, 'YYYY-MM-DD').format(
                            'DD/MM/YYYY',
                        );
                        return value.toString().indexOf(this.updated_at) !== -1;
                    },
                },
                {
                    text: 'Fecha de Vencimiento',
                    value: 'vencimiento',
                    filter: (value) => {
                        if (!this.vencimiento) return true;
                        value = moment(value, 'YYYY-MM-DD').format(
                            'DD/MM/YYYY',
                        );
                        return (
                            value.toString().indexOf(this.vencimiento) !== -1
                        );
                    },
                },
                {
                    text: 'Activo',
                    value: 'activo',
                    filter: (value) => {
                        if (!this.activo) return true;
                        let aux =
                            this.activo[0] === 'S' || this.activo[0] === 's'
                                ? true
                                : false;
                        return value === aux ? true : false;
                    },
                },
                { text: 'Comandos', value: 'action', sortable: false },
            ];
        },
        get_expedientes() {
            return this.$store.getters['adminGrupo/obtenerExpedientes'];
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
        agente() {
            return this.$store.getters['agente/agente'];
        },
        hayAgente() {
            return this.$store.getters['agente/foundAgente'];
        },
        role_display() {
            return this.$store.getters['user/role_display'];
        },
    },
    methods: {
        getColor(value) {
            if (value === true) return 'green';
            else return 'red';
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
                    doc.text('SIARHU', 50, 811);
                    doc.text('Siprosa', 520, 811);
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
                        40,
                    );
                }
            };

            var vm = this;
            var doc = new jsPDF('portrait', 'pt', 'a4');
            doc.setFont('times');

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
            var texto = 'Declaraciones Juradas del Agente';
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
            doc.text(texto, xOffset, 140);

            let agentes = [];
            let agente = this.agente;

            agentes.push(agente);
            let columnas = [
                { title: 'Documento', dataKey: 'documento' },
                { title: 'Nombre Completo', dataKey: 'apellido_nombre' },
                { title: 'Efector', dataKey: 'efector' },
                { title: 'Servicio', dataKey: 'codigo_nombre' },
            ];
            doc.autoTable(columnas, agentes, {
                startY: 140 + 30,
            });
            let expedientes = _.cloneDeep(this.expedientes);
            _.each(expedientes, function (expediente, idx) {
                expedientes[idx].aprobado =
                    expediente.aprobado === true ? 'Si' : 'No';
                expedientes[idx].activo =
                    expediente.activo === true ? 'Si' : 'No';
                let venc = expediente.vencimiento.substr(0, 10).split('-');
                expedientes[idx].vencimiento = new Date(
                    venc[0],
                    venc[1] - 1,
                    venc[2],
                ).toLocaleDateString();
                let creac = expediente.created_at.substr(0, 10).split('-');
                expedientes[idx].created_at = new Date(
                    creac[0],
                    creac[1] - 1,
                    creac[2],
                ).toLocaleDateString();
            });

            var finalY = doc.previousAutoTable.finalY;
            texto = 'Listado de DDJJ';
            xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            doc.text(texto, xOffset, finalY + 20);
            columnas = [
                { title: 'Nº de DDJJ', dataKey: 'nExpediente' },
                { title: 'Fecha de Alta', dataKey: 'created_at' },
                { title: 'Activo', dataKey: 'activo' },
                { title: 'Fecha de Vencimiento', dataKey: 'vencimiento' },
            ];

            doc.autoTable(columnas, expedientes, {
                startY: finalY + 40,
            });

            var finalY = doc.previousAutoTable.finalY;

            columnas = [
                { title: 'Documento', dataKey: 'documento' },
                { title: 'Nombre', dataKey: 'nombre' },
                { title: 'Apellido', dataKey: 'apellido' },
                { title: 'Fecha de Nacimiento', dataKey: 'fecha_nacimiento' },
                { title: 'Parentesco', dataKey: 'parentesco' },
                { title: 'Discapacidad', dataKey: 'discapacidad' },
            ];

            let familiares = _.cloneDeep(
                this.$store.getters['grupo/obtenerPersonasActivas'],
            );

            _.each(familiares, function (familiar, idx) {
                familiares[idx].discapacidad =
                    familiar.discapacidad === true ? 'Si' : 'No';

                let nac = familiar.fecha_nacimiento.substr(0, 10).split('-');
                familiares[idx].fecha_nacimiento = new Date(
                    nac[0],
                    nac[1] - 1,
                    nac[2],
                ).toLocaleDateString();
                let par = 0;
                switch (familiar.idtipoParentesco) {
                    case 0:
                        par = 'Ninguno';
                        break;
                    case 1:
                        par = 'Padre';
                        break;
                    case 2:
                        par = 'Madre';
                        break;
                    case 3:
                        par = 'Suegro';
                        break;
                    case 4:
                        par = 'Suegra';
                        break;
                    case 5:
                        par = 'Hijo';
                        break;
                    case 6:
                        par = 'Hija';
                        break;
                    case 7:
                        par = 'Yerno';
                        break;
                    case 8:
                        par = 'Nuera';
                        break;
                    case 9:
                        par = 'Abuelo';
                        break;
                    case 10:
                        par = 'Abuela';
                        break;
                    case 11:
                        par = 'Nieto';
                        break;
                    case 12:
                        par = 'Nieta';
                        break;
                    case 13:
                        par = 'Hermano';
                        break;
                    case 14:
                        par = 'Hermana';
                        break;
                    case 15:
                        par = 'Cuñado';
                        break;
                    case 16:
                        par = 'Cuñada';
                        break;
                    case 17:
                        par = 'Primo';
                        break;
                    case 18:
                        par = 'Prima';
                        break;
                    case 19:
                        par = 'Tio';
                        break;
                    case 20:
                        par = 'Tia';
                        break;
                    case 21:
                        par = 'Sobrino';
                        break;
                    case 22:
                        par = 'Sobrina';
                        break;
                    case 23:
                        par = 'Biznieto';
                        break;
                    case 24:
                        par = 'Biznieta';
                        break;
                    case 25:
                        par = 'Bisabuelo';
                        break;
                    case 26:
                        par = 'Bisabuela';
                        break;
                    case 27:
                        par = 'Conyuge';
                        break;
                }
                familiares[idx].parentesco = par;
            });

            doc.autoTable(columnas, familiares, {
                startY: finalY + 40,
            });
            addHeaders(doc);
            addFooters(doc);

            doc.save('ddjj_GrupoFamiliar.pdf');
        },
        editItem(item) {
            console.log('Item a editar ', item);

            //this.$emit("addExpediente", 0);
            //this.editedIndex = this.expedientes.indexOf(item);
            //this.editedItem = Object.assign({}, item);
        },
        viewItem(item) {
            this.$emit('addExpediente', item.idgrupoFamiliar);
        },
        deleteItem(item) {
            console.log('Item a borrar ', item);
            this.borrarExpediente(item);
        },
        agregarExpediente() {
            this.$emit('addExpediente', 0);
        },
        async buscarExpedientes() {
            await this.$store.dispatch(
                'adminGrupo/getExpedientes',
                this.agente.idagente,
            );
            this.expedientes = Array.from(
                this.$store.getters['adminGrupo/obtenerExpedientes'],
            );
        },
        async borrarExpediente(item, index, button) {
            if (
                window.confirm(
                    '¿Desea borrar la declaracion jurada de este grupo familiar?',
                )
            ) {
                console.log('apunto de ejecutar');
                await this.$store.dispatch('adminGrupo/deleteExpediente', item);

                _.remove(
                    this.$store.state.adminGrupo.expedientes,
                    (f) => f.nExpediente == item.nExpediente,
                );

                this.expedientes = Array.from(
                    this.$store.getters['adminGrupo/obtenerExpedientes'],
                );
                this.$emit('actualizar-tabla', true);
            }
        },
        actualizarTabla() {
            this.buscarExpedientes();
            this.expedientes = Array.from(this.get_expedientes);
        },
    },
    mounted() {
        this.$store.state.adminGrupo.expedientes = [];
        if (!this.habilitarBuscar) {
            this.buscarExpedientes();
        }
    },
};
</script>

<style scoped></style>
