<template>
    <div fluid>
        <v-row
            class="ml-9"
            id="contenedor"
        >
            <v-col>
                <v-data-table
                    ref="dataTableMasiva"
                    id="dataTableMasiva"
                    key="dataTableMasiva"
                    v-model="selected"
                    :headers="headers"
                    :items="licencias"
                    :item-class="row_classes"
                    :options.sync="options"
                    :server-items-length="licencias_masivo_total"
                    show-select
                    class="elevation-1"
                    sort-by="idlicencia"
                    item-key="idlicencia"
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
                    <template v-slot:item.descripcion="{ item }">
                        <b>
                            {{ item.descripcion | mayuscula }}
                        </b>
                    </template>
                    <template v-slot:item.apellido_nombre="{ item }"
                        >{{ String(item.apellido + ' ' + item.nombre) }}
                    </template>
                    <template v-slot:item.dias="{ item }"
                        >{{ item.dias === 0 ? 'Desvisado' : item.dias }}
                    </template>
                    <template v-slot:item.fecha_pedido_inicio="{ item }"
                        >{{ item.fecha_pedido_inicio | fechaAcomodada }}
                    </template>
                    <template v-slot:item.fecha_pedido_final="{ item }"
                        >{{ item.fecha_pedido_final | fechaAcomodada }}
                    </template>
                    <template v-slot:item.fecha_efectiva_inicio="{ item }">
                        {{ item.fecha_efectiva_inicio | fechaAcomodada }}
                    </template>
                    <template v-slot:item.fecha_efectiva_final="{ item }"
                        >{{ item.fecha_efectiva_final | fechaAcomodada }}
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
                                    v-model="filter.efector"
                                ></v-text-field>
                            </td>
                            <td>
                                <v-text-field
                                    label="Filtro"
                                    type="text"
                                    v-model="filter.codigo_nombre"
                                ></v-text-field>
                            </td>
                            <td>
                                <v-text-field
                                    label="Filtro"
                                    type="text"
                                    v-model="filter.documento"
                                ></v-text-field>
                            </td>
                            <td>
                                <v-text-field
                                    label="Filtro"
                                    type="text"
                                    v-model="filter.apellido_nombre"
                                ></v-text-field>
                            </td>
                            <td>
                                <v-text-field
                                    label="Filtro"
                                    type="text"
                                    v-model="filter.tipoLicencia"
                                ></v-text-field>
                            </td>
                            <td>
                                <v-text-field
                                    label="Filtro"
                                    type="text"
                                    v-model="filter.dias"
                                ></v-text-field>
                            </td>

                            <td>
                                <v-menu
                                    v-model="menu"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="auto"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="
                                                computedFechaPedidoInicioFormatted
                                            "
                                            label="Filtro"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        locale="es-AR"
                                        v-model="filter.fecha_pedido_inicio"
                                        @input="menu = false"
                                    ></v-date-picker>
                                </v-menu>
                            </td>
                            <td>
                                <v-menu
                                    v-model="menu2"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="auto"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="
                                                computedFechaPedidoFinalFormatted
                                            "
                                            label="Filtro"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        locale="es-AR"
                                        v-model="filter.fecha_pedido_final"
                                        @input="menu2 = false"
                                    ></v-date-picker>
                                </v-menu>
                            </td>
                            <td>
                                <v-select
                                    label="Filtro"
                                    :items="[
                                        { text: 'Si', value: 0 },
                                        { text: 'No', value: 1 },
                                        { text: 'No ha sido visada', value: 2 },
                                        { text: 'Todos', value: 3 },
                                    ]"
                                    :item-value="'value'"
                                    :item-text="'text'"
                                    v-model="filter.primer_visado"
                                ></v-select>
                            </td>
                            <td>
                                <v-menu
                                    v-model="menu3"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="auto"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="
                                                computedFechaEfectivaInicioFormatted
                                            "
                                            label="Filtro"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        locale="es-AR"
                                        v-model="filter.fecha_efectiva_inicio"
                                        @input="menu3 = false"
                                    ></v-date-picker>
                                </v-menu>
                            </td>
                            <td>
                                <v-menu
                                    v-model="menu4"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="auto"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="
                                                computedFechaEfectivaFinalFormatted
                                            "
                                            label="Filtro"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        locale="es-AR"
                                        v-model="filter.fecha_efectiva_final"
                                        @input="menu4 = false"
                                    ></v-date-picker>
                                </v-menu>
                            </td>
                            <td>
                                <v-select
                                    label="Filtro"
                                    :items="[
                                        { text: 'Si', value: 0 },
                                        { text: 'No', value: 1 },
                                        { text: 'No ha sido visada', value: 2 },
                                        { text: 'Todos', value: 3 },
                                    ]"
                                    :item-value="'value'"
                                    :item-text="'text'"
                                    v-model="filter.segundo_visado"
                                ></v-select>
                            </td>
                            <td>
                                <v-text-field
                                    label="Filtro"
                                    type="text"
                                    v-model="filter.idlicencia"
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
                            <v-btn
                                @click="buscarLicencias"
                                class="m-2"
                                color="primary"
                                dark
                            >
                                <i class="fas fa-search mr-2"></i>Buscar
                                Licencias
                            </v-btn>
                            <div v-if="can('visar-licencia')">
                                <v-btn-toggle rounded>
                                    <v-btn
                                        @change="actualizarTabla"
                                        @click="primerVisadoTodo(true)"
                                        class="m-2"
                                        color="success darken-2"
                                        dark
                                    >
                                        <v-icon
                                            color="white"
                                            left
                                            >mdi-check-box-multiple-outline
                                        </v-icon>
                                        Primer Visado SI
                                    </v-btn>
                                    <v-btn
                                        @change="actualizarTabla"
                                        @click="primerVisadoTodo(false)"
                                        class="m-2"
                                        color="red darken-2"
                                        dark
                                    >
                                        <v-icon
                                            color="white"
                                            left
                                            >mdi-file-cancel-outline
                                        </v-icon>
                                        Primer Visado NO
                                    </v-btn>
                                </v-btn-toggle>
                            </div>
                            <div v-if="can('visar2-licencia')">
                                <v-btn-toggle rounded>
                                    <v-btn
                                        @change="actualizarTabla"
                                        @click="segundoVisadoTodo(true)"
                                        class="m-2"
                                        color="success darken-2"
                                        dark
                                    >
                                        <v-icon
                                            color="white"
                                            left
                                            >mdi-check-box-multiple-outline
                                        </v-icon>
                                        Segundo Visado SI
                                    </v-btn>
                                    <v-btn
                                        @change="actualizarTabla"
                                        @click="segundoVisadoTodo(false)"
                                        class="m-2"
                                        color="red darken-2"
                                        dark
                                    >
                                        <v-icon
                                            color="white"
                                            left
                                            >mdi-file-cancel-outline
                                        </v-icon>
                                        Segundo Visado NO
                                    </v-btn>
                                </v-btn-toggle>
                            </div>
                        </v-toolbar>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import moment from 'moment';
import _ from 'lodash';

export default {
    name: 'TablaMasiva',
    props: {},
    watch: {
        agente: function (val, oldVal) {
            this.buscarLicencias();
        },
        filter: {
            handler() {
                this.debouncedSearch();
            },
            deep: true,
        },
        options: {
            handler() {
                this.debouncedSearch();
            },
            deep: true,
        },
    },
    mounted() {
        if (this.agente.idagente != 0) {
            this.buscarLicencias();
        }
    },
    data() {
        return {
            loading: false,
            menu: false,
            menu2: false,
            menu3: false,
            menu4: false,
            menu5: false,
            selected: [],
            error: false,
            filter: {
                efector: '',
                codigo_nombre: '',
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
                fecha_interrupcion_inicio: new Date()
                    .toISOString()
                    .substr(0, 10),
            },
            licTitulo: 'Resumen de Licencias',
            options: {},
            totalLicencias: 0,
        };
    },
    created() {
        this.debouncedSearch = _.debounce(this.buscarLicencias, 400);
    },
    computed: {
        dependencia() {
            return this.$store.getters['user/dependencia'];
        },
        dependencia_padre() {
            return this.$store.getters['user/dependencia_padre'];
        },
        computedFechaPedidoInicioFormatted() {
            return this.formatDate(this.filter.fecha_pedido_inicio);
        },
        computedFechaPedidoFinalFormatted() {
            return this.formatDate(this.filter.fecha_pedido_final);
        },
        computedFechaEfectivaInicioFormatted() {
            return this.formatDate(this.filter.fecha_efectiva_inicio);
        },
        computedFechaEfectivaFinalFormatted() {
            return this.formatDate(this.filter.fecha_efectiva_final);
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
        licencias_masivo_total() {
            return this.$store.getters['licencia/licencias_masivo_total'];
        },
        headers() {
            return [
                {
                    value: 'efector',
                    text: 'Efector',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.efector) return true;
                        return (
                            value
                                .toString()
                                .indexOf(this.filter.efector.toUpperCase()) !==
                            -1
                        );
                    },
                },
                {
                    value: 'codigo_nombre',
                    text: 'Servicio',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.codigo_nombre) return true;
                        return (
                            value
                                .toString()
                                .indexOf(
                                    this.filter.codigo_nombre.toUpperCase(),
                                ) !== -1
                        );
                    },
                },
                {
                    value: 'documento',
                    text: 'Documento',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.documento) return true;
                        return (
                            value.toString().indexOf(this.filter.documento) !==
                            -1
                        );
                    },
                },
                {
                    value: 'apellido_nombre',
                    text: 'Apellido y Nombre',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.apellido_nombre) return true;
                        return (
                            value
                                .toString()
                                .indexOf(
                                    this.filter.apellido_nombre.toUpperCase(),
                                ) !== -1
                        );
                    },
                },
                {
                    value: 'descripcion',
                    text: 'Tipo de Licencias',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.tipoLicencia) return true;
                        return (
                            value
                                .toString()
                                .toUpperCase()
                                .indexOf(
                                    this.filter.tipoLicencia.toUpperCase(),
                                ) !== -1
                        );
                    },
                },
                {
                    value: 'dias',
                    text: 'Dias de Licencia',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.dias) return true;
                        return (
                            value.toString().indexOf(this.filter.dias) !== -1
                        );
                    },
                },
                {
                    value: 'fecha_pedido_inicio',
                    text: 'Fecha del Inicio del Pedido',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.fecha_pedido_inicio) return true;
                        value = moment(value, 'YYYY-MM-DD').format(
                            'DD/MM/YYYY',
                        );
                        return (
                            value
                                .toString()
                                .indexOf(this.filter.fecha_pedido_inicio) !== -1
                        );
                    },
                },
                {
                    value: 'fecha_pedido_final',
                    text: 'Fecha Pedido Final',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.fecha_pedido_final) return true;
                        value = moment(value, 'YYYY-MM-DD').format(
                            'DD/MM/YYYY',
                        );
                        return (
                            value
                                .toString()
                                .indexOf(this.filter.fecha_pedido_final) !== -1
                        );
                    },
                },
                {
                    value: 'primer_visado',
                    text: 'Primer Visado',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.primer_visado) return true;
                        if (this.filter.primer_visado === 2) {
                            return value === null;
                        }
                        if (this.filter.primer_visado === 1) {
                            return value === false;
                        }
                        if (this.filter.segundo_visado === 3) {
                            return value;
                        }
                        return value === true;
                    },
                },
                {
                    value: 'fecha_efectiva_inicio',
                    text: 'Fecha Efectiva Inicio',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.fecha_efectiva_inicio) return true;
                        value = moment(value, 'YYYY-MM-DD').format(
                            'DD/MM/YYYY',
                        );
                        return (
                            value
                                .toString()
                                .indexOf(this.filter.fecha_efectiva_inicio) !==
                            -1
                        );
                    },
                },
                {
                    value: 'fecha_efectiva_final',
                    text: 'Fecha Efectiva Final',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.fecha_efectiva_final) return true;
                        value = moment(value, 'YYYY-MM-DD').format(
                            'DD/MM/YYYY',
                        );
                        return (
                            value
                                .toString()
                                .indexOf(this.filter.fecha_efectiva_final) !==
                            -1
                        );
                    },
                },
                {
                    value: 'segundo_visado',
                    text: 'Segundo Visado',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.segundo_visado) return true;
                        if (this.filter.segundo_visado === 2) {
                            return value === null;
                        }
                        if (this.filter.segundo_visado === 1) {
                            return value === false;
                        }
                        if (this.filter.segundo_visado === 3) {
                            return value;
                        }
                        return value === true;
                    },
                },
                {
                    value: 'idlicencia',
                    text: 'Numero de Licencia',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.idlicencia) return true;
                        return (
                            value.toString().indexOf(this.filter.idlicencia) !==
                            -1
                        );
                    },
                },
            ];
        },
    },

    methods: {
        formatDate(date) {
            if (!date) return null;

            const [year, month, day] = date.split('-');
            return `${day}/${month}/${year}`;
        },
        parseDate(date) {
            if (!date) return null;

            const [month, day, year] = date.split('/');
            return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
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
            var permisos = this.$store.getters['user/permisos'];
            return _.findIndex(permisos, ['name', cadena]) >= 0 ? true : false;
        },
        actualizarTabla() {
            //this.buscarLicencias()
        },
        async buscarLicencias() {
            this.loading = true;
            this.selected = [];
            await this.$store
                .dispatch('licencia/getLicenciasDependientes', {
                    idagente: this.agente.idagente,
                    options: this.options,
                    filters: this.filter,
                    hijos: this.hijos,
                    dependencia: this.dependencia?.iddependencia
                        ? this.dependencia.iddependencia
                        : this.dependencia_padre?.iddependencia
                        ? this.dependencia_padre.iddependencia
                        : null,
                })
                .then((res) => {
                    this.loading = false;
                });
        },
        async primerVisadoTodo(value) {
            var res = window.confirm(
                '¿Esta seguro de otorgarle el visado a las licencias seleccionadas?',
            );
            if (res) {
                await this.$store
                    .dispatch('licencia/primerVisadoTodo', {
                        licencias: this.selected,
                        value: value,
                    })
                    .then((res) => {
                        this.buscarLicencias();
                    })
                    .catch((err) => {
                        console.log('Error en la masiva primera ', err);
                    });
            }
        },
        async segundoVisadoTodo(value) {
            var res = window.confirm(
                '¿Esta seguro de otorgarle el visado a las licencias seleccionadas?',
            );
            if (res) {
                await this.$store
                    .dispatch('licencia/segundoVisadoTodo', {
                        licencias: this.selected,
                        value: value,
                    })
                    .then((res) => {
                        this.buscarLicencias();
                    })
                    .catch((err) => {
                        console.log('Error en la masiva segunda ', err);
                    });
            }
        },
    },
};
</script>

<style scoped>
#contenedor {
    max-width: 95%;
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
