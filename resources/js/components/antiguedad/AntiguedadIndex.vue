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
                            :items="antiguedades"
                            item-key="idantiguedad"
                            sort-by="año"
                        >
                            <template v-slot:item.created_at="{ item }"
                                >{{ item.created_at | fechaAcomodada }}
                            </template>
                            <template v-slot:item.updated_at="{ item }"
                                >{{ item.updated_at | fechaAcomodada }}
                            </template>
                            <template v-slot:item.vigente="{ item }">
                                <v-chip
                                    :color="getColor(item.vigente)"
                                    dark
                                    >{{ item.vigente === true ? 'Si' : 'No' }}
                                </v-chip>
                            </template>
                            <template v-slot:body.prepend>
                                <tr>
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
                                            v-model="pedido"
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
                                            v-model="vigente"
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
                                </tr>
                            </template>

                            <template v-slot:top>
                                <v-toolbar
                                    color="white"
                                    flat
                                >
                                    <v-toolbar-title
                                        >Antigüedades
                                    </v-toolbar-title>
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
                                            v-if="
                                                can('exportarExcel-antiguedad')
                                            "
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
                                        v-if="can('exportarPDF-antiguedad')"
                                    >
                                        <i class="far fa-file-pdf mr-2"></i
                                        >Exportar PDF
                                    </v-btn>
                                    <v-btn
                                        @change="actualizarTabla"
                                        @click="buscarAntiguedades"
                                        class="m-2"
                                        color="primary"
                                        :disabled="habilitarBuscar"
                                        dark
                                    >
                                        <i class="fas fa-search mr-2"></i>Buscar
                                        Antigüedades
                                    </v-btn>
                                    <v-dialog
                                        max-width="500px"
                                        v-model="dialog"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <div v-if="can('crear-antiguedad')">
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
                                                        >Nueva Antigüedad
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
                                                            <v-card
                                                                v-if="
                                                                    editedIndex ===
                                                                    -1
                                                                "
                                                            >
                                                                <v-text-field
                                                                    label="Año"
                                                                    v-model="
                                                                        editedItem.año
                                                                    "
                                                                    :rules="[
                                                                        rules.required,
                                                                        anioRule,
                                                                    ]"
                                                                ></v-text-field>
                                                            </v-card>
                                                            <v-card
                                                                v-if="
                                                                    editedIndex !==
                                                                    -1
                                                                "
                                                            >
                                                                <v-text-field
                                                                    label="Año"
                                                                    v-model="
                                                                        editedItem.año
                                                                    "
                                                                    :rules="[
                                                                        rules.required,
                                                                    ]"
                                                                    v-restrict.number
                                                                ></v-text-field>
                                                            </v-card>
                                                            <v-card>
                                                                <v-text-field
                                                                    label="Disponible"
                                                                    v-model="
                                                                        editedItem.disponible
                                                                    "
                                                                    :rules="
                                                                        genericRules
                                                                    "
                                                                    v-restrict.number.decimal
                                                                ></v-text-field>
                                                            </v-card>
                                                            <v-card>
                                                                <div
                                                                    v-if="
                                                                        can(
                                                                            'habilitar-vigencias',
                                                                        )
                                                                    "
                                                                >
                                                                    <v-checkbox
                                                                        v-model="
                                                                            editedItem.vigente
                                                                        "
                                                                        label="¿Esta Vigente?"
                                                                    ></v-checkbox>
                                                                </div>
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
                                <div v-if="can('editar-antiguedad')">
                                    <div v-if="agente.fhasta === null">
                                        <v-icon
                                            @click="editItem(item)"
                                            class="mr-2"
                                            small
                                            >mdi-account-edit
                                        </v-icon>
                                    </div>
                                </div>
                                <div v-if="can('borrar-antiguedad')">
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
                                >No hay antiguedades cargadas
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
import moment from 'moment';
import TarjetaAgente from '../utils/TarjetaAgente.vue';

import jsPDF from 'jspdf';
import 'jspdf-autotable';

export default {
    name: 'Antiguedad',
    components: {
        TarjetaAgente,
    },
    data: () => ({
        json_fields: {
            Efector: 'efector',
            Servicio: 'codigo_nombre',
            Documento: 'documento',
            'Apellido y Nombre': 'apellido_nombre',
            Año: 'año',
            Pedido: 'pedido',
            Disponible: 'disponible',
            Vigente: {
                field: 'vigente',
                callback: (value) => {
                    return value === true ? 'Si' : 'No';
                },
            },
            'Usuario Responsable': 'nombreusuario',
            'Fecha de Alta': {
                field: 'created_at',
                callback: (value) => {
                    return moment(value).format('DD/MM/YYYY');
                },
            },
        },
        valid: false,
        rules: {
            required: (value) => !!value || 'El campo es requerido.',
        },
        genericRules: [(v) => v !== null || 'El campo es requerido'],
        habilitarBuscar: true,
        titulo: 'Gestion de Antigüedades',
        idagente: 0,
        dialog: false,
        search: '',
        año: '',
        idantiguedad: '',
        pedido: '',
        disponible: '',
        vigente: '',
        created_at: '',
        nombreusuario: '',
        antiguedades: [],
        aniosAntiguedades: [],
        editedIndex: -1,
        updated_at: '',
        editedItem: {
            idagente: 0,
            año: '',
            pedido: '',
            disponible: '',
            vigente: false,
            created_at: new Date().toISOString().substr(0, 10),
            updated_at: new Date().toISOString().substr(0, 10),
        },
        defaultItem: {
            idagente: 0,
            año: '',
            pedido: '',
            disponible: '',
            vigente: false,
            created_at: new Date().toISOString().substr(0, 10),
            updated_at: new Date().toISOString().substr(0, 10),
        },
    }),
    computed: {
        permisos() {
            return this.$store.getters['user/permisos'];
        },
        selected() {
            return _.each(this.antiguedades, (element) => {
                element['efector'] = this.agente.efector;
                element['codigo_nombre'] = this.agente.codigo_nombre;
                element['documento'] = this.agente.documento;
                element['apellido_nombre'] = this.agente.apellido_nombre;
            });
        },
        user() {
            return this.$store.getters['user/user'].nombreusuario;
        },
        role_display() {
            return this.$store.getters['user/role_display'];
        },
        obtenerAntiguedades() {
            return this.$store.getters['antiguedad/antiguedades'];
        },
        agente() {
            return this.$store.getters['agente/agente'];
        },
        formatearFecha() {
            return this.editedItem.created_at
                ? moment(this.editedItem.created_at).format('DD/MM/YYYY')
                : '';
        },
        headers() {
            return [
                {
                    text: 'Año',
                    value: 'año',
                    filter: (value) => {
                        if (!this.año) return true;
                        return value.toString().indexOf(this.año) !== -1;
                    },
                },
                {
                    text: 'Pedido',
                    value: 'pedido',
                    filter: (value) => {
                        if (!this.pedido) return true;
                        return value.toString().indexOf(this.pedido) !== -1;
                    },
                },
                {
                    text: 'Disponible',
                    value: 'disponible',
                    filter: (value) => {
                        if (!this.disponible) return true;
                        return value.toString().indexOf(this.disponible) !== -1;
                    },
                },

                {
                    text: 'Vigente',
                    value: 'vigente',
                    filter: (value) => {
                        if (!this.vigente) return true;
                        return value.toString().indexOf(this.vigente) !== -1;
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
                    text: 'Fecha de Creacion de la Antiguedad',
                    value: 'created_at',
                    filter: (value) => {
                        if (!this.created_at) return true;
                        if (this.created_at.toString().length === 10) {
                            return moment(value).isSame(
                                moment(this.created_at, 'DD/MM/YYYY'),
                            );
                        } else {
                            return (
                                value.toString().indexOf(this.created_at) !== -1
                            );
                        }
                    },
                },
                {
                    text: 'Fecha de Modificación de la Antiguedad',
                    value: 'updated_at',
                    filter: (value) => {
                        if (!this.updated_at) return true;
                        if (this.updated_at.toString().length === 10) {
                            return moment(value).isSame(
                                moment(this.updated_at, 'DD/MM/YYYY'),
                            );
                        } else {
                            return (
                                value.toString().indexOf(this.updated_at) !== -1
                            );
                        }
                    },
                },
                { text: 'Comandos', value: 'action', sortable: false },
            ];
        },
        formTitle() {
            return this.editedIndex === -1
                ? 'Nueva Antigüedad'
                : 'Modificar Antigüedad';
        },
        idusuario() {
            return this.$store.getters['user/user'].idusuario;
        },
    },

    watch: {
        dialog(val) {
            val || this.close();
        },
    },

    created() {
        this.$store.state.antiguedad.antiguedades = [];
    },

    methods: {
        getColor(value) {
            if (value === true) return 'green';
            else return 'red';
        },
        can(cadena) {
            return _.findIndex(this.permisos, ['name', cadena]) >= 0;
        },
        anioRule(value) {
            var encontrado = this.aniosAntiguedades.includes(parseInt(value));
            return !encontrado || 'Ya se encuentra ese año agregado.';
        },
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
                        40,
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
            var texto = 'Saldos por Antiguedad del Agente';
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
            let agente = _.cloneDeep(this.agente);

            agentes.push(agente);
            let columnas = [
                { title: 'Documento', dataKey: 'documento' },
                { title: 'Nombre Completo', dataKey: 'apellido_nombre' },
                { title: 'Efector', dataKey: 'efector' },
                { title: 'Servicio', dataKey: 'codigo_nombre' },
            ];
            doc.autoTable(columnas, agentes, {
                startY: 140 + 25,
            });
            let antiguedades = _.cloneDeep(this.antiguedades);
            _.each(antiguedades, function (antiguedad, idx) {
                if (antiguedad.vigente === true) {
                    antiguedades[idx].vigente = 'Si';
                } else {
                    antiguedades[idx].vigente = 'No';
                }
                var created_at = antiguedad.created_at.substr(0, 10).split('-');
                antiguedad.created_at = new Date(
                    created_at[0],
                    parseInt(created_at[1]) - 1,
                    created_at[2],
                ).toLocaleDateString();
            });

            var finalY = doc.previousAutoTable.finalY;
            texto = 'Listado de Antiguedades por Año';
            xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;

            doc.text(texto, xOffset, finalY + 20);
            columnas = [
                { title: 'Año', dataKey: 'año' },
                { title: 'Pedido', dataKey: 'pedido' },
                { title: 'Disponible', dataKey: 'disponible' },
                { title: 'Vigente', dataKey: 'vigente' },
                { title: 'Fecha de Alta', dataKey: 'created_at' },
                { title: 'Usuario Responsable', dataKey: 'nombreusuario' },
            ];

            doc.autoTable(columnas, antiguedades, {
                startY: finalY + 40,
            });
            addHeaders(doc);
            addFooters(doc);
            doc.save('antiguedades.pdf');
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
            this.buscarAntiguedades();
        },
        volverHome() {
            //location.replace("http://siarhu.testing/rrhh");
        },
        async actualizarAntiguedad(obj) {
            console.log('El agente tiene: ', obj);
            obj.pedido = 0;
            this.crear = true;
            this.editar = false;
            obj.idusuario = this.idusuario;
            await this.$store
                .dispatch('antiguedad/updateAntiguedad', obj)
                .then(() => {
                    console.log('Exito al modificar Antiguedad');
                })
                .catch((err) => {
                    console.log('Error al modificar Antiguedad' + err);
                });
        },
        async guardarAntiguedad(obj) {
            obj.pedido = 0;
            obj.vigente = false;
            obj.idusuario = this.idusuario;
            await this.$store
                .dispatch('antiguedad/postAntiguedad', obj)
                .then(() => {
                    console.log('Exito al crear Antiguedad');
                })
                .catch((err) => {
                    console.log('error ', err);
                });
        },
        async borrarAntiguedad(item) {
            if (window.confirm('¿Desea borrar la antiguedad seleccionada?')) {
                await this.$store.dispatch('antiguedad/deleteAntiguedad', item);
                _.remove(
                    this.obtenerAntiguedades,
                    (f) => f.idantiguedad == item.idantiguedad,
                );
                this.antiguedades = Array.from(this.obtenerAntiguedades);
                this.buscarAntiguedades();
            }
        },
        async buscarAntiguedades() {
            await this.$store.dispatch(
                'antiguedad/getAntiguedades',
                this.agente.idagente,
            );
            this.antiguedades = Array.from(this.obtenerAntiguedades);
            this.idagente = this.agente.idagente;
            console.log(
                'objeto con las antiguedades traidas: ',
                this.antiguedades,
            );
            console.log(
                'numero total de sanciones traidas: ',
                this.obtenerAntiguedades.length,
            );
            this.aniosAntiguedades = [];
            var idx;
            for (idx in this.antiguedades) {
                this.aniosAntiguedades.push(this.antiguedades[idx].año);
            }
        },
        actualizarTabla() {
            console.log('actualiza la tabla de antiguedades');
            this.antiguedades = Array.from(this.obtenerAntiguedades);
        },
        initialize() {},
        editItem(item) {
            this.editedIndex = this.antiguedades.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.editedItem.vigente = false;
            this.dialog = true;
        },
        deleteItem(item) {
            this.borrarAntiguedad(item.idantiguedad);
        },
        close() {
            console.log('ejecucion de close');
            this.dialog = false;
            setTimeout(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            }, 300);
            this.buscarAntiguedades();
        },
        save() {
            this.validate();
            console.log(this.valid);
            if (this.valid) {
                this.editedItem.idagente = this.agente.idagente;
                if (this.editedIndex > -1) {
                    this.actualizarAntiguedad(this.editedItem);
                    Object.assign(
                        this.antiguedades[this.editedIndex],
                        this.editedItem,
                    );
                } else {
                    this.guardarAntiguedad(this.editedItem);
                    this.antiguedades.push(this.editedItem);
                }
                this.close();
            }
        },
        beforeAppear: function () {
            console.log('beforeAppear');
        },
        appear: function () {
            console.log('appear!');
        },
        afterAppear: function () {
            console.log('afterAppear!');
        },
    },
};
</script>

<style>
.fade-enter-active {
    animation: cubic-bezier(0.075, 0.82, 0.165, 1) 2s;
}
</style>
