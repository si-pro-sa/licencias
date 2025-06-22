<template>
    <div>
        <v-container>
            <v-select
                label="Seleccione una categoría"
                :items="['Capacitacion', 'Agente', 'Licencia']"
                v-model="seleccion"
            >
            </v-select>
            <div v-if="seleccion === 'Capacitacion'">
                <v-container>
                    <v-row>
                        <v-select
                            v-model="capacitacionSeleccionada"
                            :items="capacitaciones"
                            item-text="evento_nombre"
                            item-value="idCapacitacion"
                            label="Capacitaciones"
                            return-object
                        ></v-select>
                    </v-row>
                </v-container>
                <v-container>
                    <v-row v-show="capacitacionSeleccionada !== ''">
                        <v-col
                            cols="12"
                            sm="12"
                            md="12"
                            lg="6"
                            xl="6"
                        >
                            <v-text-field
                                label="Nombre del Evento"
                                v-model="capacitacionSeleccionada.evento_nombre"
                                disabled
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            sm="12"
                            md="12"
                            lg="6"
                            xl="6"
                        >
                            <v-text-field
                                label="Lugar del Evento"
                                v-model="capacitacionSeleccionada.evento_lugar"
                                disabled
                            ></v-text-field
                        ></v-col>
                        <v-col
                            cols="12"
                            sm="12"
                            md="12"
                            lg="6"
                            xl="6"
                        >
                            <v-text-field
                                label="Alcance"
                                v-model="capacitacionSeleccionada.alcance"
                                disabled
                            ></v-text-field
                        ></v-col>
                        <v-col
                            cols="12"
                            sm="12"
                            md="12"
                            lg="6"
                            xl="6"
                        >
                            <v-text-field
                                label="Información Adicional de la Capacitación"
                                v-model="capacitacionSeleccionada.razon"
                                disabled
                            ></v-text-field
                        ></v-col>
                    </v-row>
                </v-container>
                <v-container>
                    <!-- Agregar loader si la variable de loading esta en true -->
                    <div v-if="loading">
                        <v-row>
                            <v-col cols="12">
                                <v-progress-linear
                                    indeterminate
                                    color="primary"
                                ></v-progress-linear>
                            </v-col>
                        </v-row>
                    </div>
                    <div v-else>
                        <v-data-table
                            :headers="headersAgente"
                            :items="capacitacionAgentes"
                            sort-by="documento"
                            class="elevation-1"
                            show-select
                            v-model="selectedAgente"
                        >
                            <template
                                v-slot:item.fecha_evento_inicio="{ item }"
                            >
                                {{ item.fecha_evento_inicio | filterDate }}
                            </template>
                            <template v-slot:item.fecha_evento_final="{ item }">
                                {{ item.fecha_evento_final | filterDate }}
                            </template>
                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-toolbar-title>Agentes</v-toolbar-title>
                                    <v-divider
                                        class="mx-4"
                                        inset
                                        vertical
                                    ></v-divider>
                                    <v-spacer></v-spacer>
                                    <download-excel
                                        class="m-2 v-btn v-btn--contained theme--light v-size--default blue darken-2"
                                        :style="{ cursor: 'pointer' }"
                                        :data="exportExcelAgentes"
                                        :fields="json_fields_agentes"
                                        name="reporte.xls"
                                    >
                                        <v-icon
                                            dark
                                            left
                                            >mdi-file-excel-outline
                                        </v-icon>
                                        <span class="white--text"
                                            >Exportar Excel</span
                                        >
                                    </download-excel>

                                    <v-btn
                                        @click="exportPDFAgente"
                                        class="m-2"
                                        color="blue darken-2"
                                        dark
                                    >
                                        <v-icon
                                            dark
                                            left
                                            >mdi-file-pdf-outline</v-icon
                                        >
                                        Exportar Agentes
                                    </v-btn>
                                </v-toolbar>
                            </template>
                            <template v-slot:no-data>
                                <v-btn
                                    color="primary"
                                    @click="initialize"
                                >
                                    Actualizar
                                </v-btn>
                            </template>
                        </v-data-table>
                    </div>
                </v-container>
            </div>
            <div v-if="seleccion === 'Agente'">
                <tarjeta-agente
                    titulo="Capacitacion"
                    :deshabilitado="false"
                    @hayAgente="habilitarBusqueda"
                />
                <v-data-table
                    :headers="headersCapacitacion"
                    :items="agenteCapacitaciones"
                    sort-by="resolucion"
                    class="elevation-1"
                    show-select
                    v-model="selectedCapacitaciones"
                >
                    <template v-slot:item.fecha_evento_inicio="{ item }">
                        {{ item.fecha_evento_inicio | filterDate }}
                    </template>
                    <template v-slot:item.fecha_evento_final="{ item }">
                        {{ item.fecha_evento_final | filterDate }}
                    </template>
                    <template v-slot:top>
                        <v-toolbar flat>
                            <v-toolbar-title>Capacitaciones</v-toolbar-title>
                            <v-divider
                                class="mx-4"
                                inset
                                vertical
                            ></v-divider>
                            <v-spacer></v-spacer>
                            <download-excel
                                class="m-2 v-btn v-btn--contained theme--light v-size--default blue darken-2"
                                :style="{ cursor: 'pointer' }"
                                :data="exportExcelCapacitaciones"
                                :fields="json_fields_capacitaciones"
                                v-if="can('exportar-capacitacion-excel')"
                                name="reporte.xls"
                            >
                                <v-icon
                                    dark
                                    left
                                    >mdi-file-excel-outline
                                </v-icon>
                                <span class="white--text">Exportar Excel</span>
                            </download-excel>

                            <v-btn
                                @click="exportPDFCapacitacion"
                                class="m-2"
                                color="blue darken-2"
                                dark
                                v-if="can('exportar-capacitacion-pdf')"
                            >
                                <v-icon
                                    dark
                                    left
                                    >mdi-file-pdf-outline</v-icon
                                >
                                Exportar Capacitaciones
                            </v-btn>
                        </v-toolbar>
                    </template>
                    <template v-slot:no-data>
                        <v-btn
                            color="primary"
                            @click="initialize"
                        >
                            Actualizar
                        </v-btn>
                    </template>
                </v-data-table>
            </div>
            <div v-if="seleccion === 'Licencia'">
                <v-row>
                    <v-col cols="6">
                        <v-date-picker
                            v-model="dates"
                            :first-day-of-week="0"
                            range
                            locale="es-ar"
                            selected-items-text="{0} seleccionados"
                            label="Seleccione rango de fechas (Segundo Visado)"
                        ></v-date-picker>
                    </v-col>
                    <v-col cols="6">
                        <v-text-field
                            v-model="dateRangeText"
                            label="Rango de Fechas"
                            prepend-icon="mdi-calendar"
                            readonly
                        ></v-text-field> </v-col
                ></v-row>

                <v-row>
                    <v-btn
                        color="primary"
                        block
                        @click="buscarLicencias"
                        >Buscar</v-btn
                    >
                </v-row>
                <v-row>
                    <div v-if="loading">
                        <v-row>
                            <v-col cols="12">
                                <v-progress-linear
                                    indeterminate
                                    color="primary"
                                ></v-progress-linear>
                            </v-col>
                        </v-row>
                    </div>
                    <div v-else>
                        <v-container
                            ><v-row
                                ><v-col>
                                    <v-data-table
                                        :headers="headersLicencia"
                                        :items="licenciaCapacitaciones"
                                        sort-by="idlicencia"
                                        class="elevation-1"
                                        item-key="idlicencia"
                                        show-select
                                        v-model="selectedLicencias"
                                        dense
                                        height="800px"
                                        :item-class="row_classes"
                                    >
                                        <template v-slot:top>
                                            <v-toolbar flat>
                                                <v-toolbar-title
                                                    >Licencias Por
                                                    Capacitaciones</v-toolbar-title
                                                >

                                                <download-excel
                                                    class="m-2 v-btn v-btn--contained theme--light v-size--default blue darken-2"
                                                    :style="{
                                                        cursor: 'pointer',
                                                    }"
                                                    :data="selectedLicencias"
                                                    :fields="
                                                        json_fields_licencias
                                                    "
                                                    name="Licencias_Capacitacion.xls"
                                                >
                                                    <v-icon
                                                        dark
                                                        left
                                                        >mdi-file-excel-outline
                                                    </v-icon>
                                                    <span class="white--text"
                                                        >Exportar Excel</span
                                                    >
                                                </download-excel>

                                                <v-btn
                                                    @click="exportPDFLicencias"
                                                    class="m-2"
                                                    color="blue darken-2"
                                                    dark
                                                >
                                                    <v-icon
                                                        dark
                                                        left
                                                        >mdi-file-pdf-outline</v-icon
                                                    >
                                                    Exportar Licencias
                                                </v-btn>
                                                <download-excel
                                                    :data="
                                                        filteredLicenciasTrue
                                                    "
                                                    :fields="
                                                        json_fields_licencias
                                                    "
                                                    :style="{
                                                        cursor: 'pointer',
                                                    }"
                                                    name="Licencias_Visado_True.xls"
                                                    class="m-2 v-btn v-btn--contained theme--light v-size--default green darken-2"
                                                >
                                                    <v-icon
                                                        dark
                                                        left
                                                        >mdi-file-excel</v-icon
                                                    >
                                                    <span class="white--text">
                                                        Exportar (Visado 2:
                                                        Sí)</span
                                                    >
                                                </download-excel>

                                                <!-- Botón para exportar con segundo_visado: false -->
                                                <download-excel
                                                    :data="
                                                        filteredLicenciasFalse
                                                    "
                                                    :fields="
                                                        json_fields_licencias
                                                    "
                                                    :style="{
                                                        cursor: 'pointer',
                                                    }"
                                                    name="Licencias_Visado_False.xls"
                                                    class="m-2 v-btn v-btn--contained theme--light v-size--default red darken-2"
                                                >
                                                    <v-icon
                                                        dark
                                                        left
                                                        >mdi-file-excel</v-icon
                                                    >
                                                    <span class="white--text">
                                                        Exportar (Visado 2:
                                                        No)</span
                                                    >
                                                </download-excel>
                                            </v-toolbar>
                                        </template>
                                        <template
                                            v-slot:item.fecha_pedido_inicio="{
                                                item,
                                            }"
                                        >
                                            {{
                                                item.fecha_pedido_inicio
                                                    | filterDate
                                            }}
                                        </template>
                                        <template
                                            v-slot:item.fecha_pedido_final="{
                                                item,
                                            }"
                                        >
                                            {{
                                                item.fecha_pedido_final
                                                    | filterDate
                                            }}
                                        </template>
                                        <template
                                            v-slot:item.fecha_evento_inicio="{
                                                item,
                                            }"
                                        >
                                            {{
                                                item.fecha_pedido_inicio
                                                    | filterDate
                                            }}
                                        </template>
                                        <template
                                            v-slot:item.fecha_evento_final="{
                                                item,
                                            }"
                                        >
                                            {{
                                                item.fecha_pedido_final
                                                    | filterDate
                                            }}
                                        </template>
                                        <template
                                            v-slot:item.fecha_efectiva_inicio="{
                                                item,
                                            }"
                                        >
                                            {{
                                                item.fecha_efectiva_inicio
                                                    | filterDate
                                            }}
                                        </template>
                                        <template
                                            v-slot:item.fecha_efectiva_final="{
                                                item,
                                            }"
                                        >
                                            {{
                                                item.fecha_efectiva_final
                                                    | filterDate
                                            }}
                                        </template>
                                        <template
                                            v-slot:item.fecha_visado_primero="{
                                                item,
                                            }"
                                        >
                                            {{
                                                item.fecha_visado_primero
                                                    | filterDate
                                            }}
                                        </template>
                                        <template
                                            v-slot:item.fecha_visado_segundo="{
                                                item,
                                            }"
                                        >
                                            {{
                                                item.fecha_visado_segundo
                                                    | filterDate
                                            }}
                                        </template>

                                        <template
                                            v-slot:item.fecha_interrupcion_inicio="{
                                                item,
                                            }"
                                        >
                                            {{
                                                item.fecha_interrupcion_inicio
                                                    | filterDate
                                            }}
                                        </template>
                                        <template
                                            v-slot:item.primer_visado="{ item }"
                                        >
                                            {{
                                                item.primer_visado === true
                                                    ? 'Si'
                                                    : item.primer_visado ===
                                                      false
                                                    ? 'No'
                                                    : 'No ha sido visada'
                                            }}
                                        </template>
                                        <template
                                            v-slot:item.segundo_visado="{
                                                item,
                                            }"
                                        >
                                            {{
                                                item.segundo_visado === true
                                                    ? 'Si'
                                                    : item.segundo_visado ===
                                                      false
                                                    ? 'No'
                                                    : 'No ha sido visada'
                                            }}
                                        </template>
                                        <template
                                            v-slot:item.cuarta_visado="{ item }"
                                            >{{
                                                item.cuarta_visado ? 'Si' : 'No'
                                            }}</template
                                        >
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
                                        <template v-slot:no-data>
                                            <v-btn
                                                color="primary"
                                                @click="initialize"
                                            >
                                                Actualizar
                                            </v-btn>
                                        </template>
                                    </v-data-table>
                                </v-col></v-row
                            ></v-container
                        >
                    </div>
                </v-row>
            </div>
        </v-container>
    </div>
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
import TarjetaAgente from '../utils/TarjetaAgente.vue';
export default {
    name: 'InformesTab',
    components: {
        TarjetaAgente,
    },
    filters: {
        filterDate: function (date) {
            if (!date) return null;

            const [year, month, day] = date.substr(0, 10).split('-');
            return `${day}/${month}/${year}`;
        },
    },
    data: () => ({
        loading: false,
        selectedAgente: [],
        json_fields_agentes: {
            'Evento Nombre': 'evento_nombre',
            'Evento Lugar': 'evento_lugar',
            'Tipo Evento': 'tipo_evento',
            Alcance: 'alcance',
            'Informacion Adic.': 'razon',
            Documento: 'documento',
            Nombre: 'nombre',
            Apellido: 'apellido',
        },
        selectedCapacitaciones: [],
        json_fields_capacitaciones: {
            'Evento Nombre': 'evento_nombre',
            'Evento Lugar': 'evento_lugar',
            'Tipo Evento': 'tipo_evento',
            Alcance: 'alcance',
            'Informacion Adic.': 'razon',
            Documento: 'documento',
            'Apellido y Nombre': 'apellido_nombre',
        },
        selectedLicencias: [],
        json_fields_licencias: {
            'N de Licencia': 'idlicencia',
            'Fecha Primer Visado': {
                field: 'fecha_visado_primero',
                callback: (value) => {
                    return value !== '' && value !== null
                        ? moment(value).utcOffset('+0300').format('DD/MM/YYYY')
                        : 'No hay fecha establecida';
                },
            },
            'Fecha Segundo Visado': {
                field: 'fecha_visado_segundo',
                callback: (value) => {
                    return value !== '' && value !== null
                        ? moment(value).utcOffset('+0300').format('DD/MM/YYYY')
                        : 'No hay fecha establecida';
                },
            },
            // 'Tiempo de RPTA (visado 2 - visado 1, dias habiles)': {
            //     field: 'tiempo_rpta',
            //     callback: (value) => {
            //         return value === 0
            //             ? 'No Autorizado'
            //             : value === null
            //             ? 'No hay fecha establecida'
            //             : value;
            //     },
            // },
            'Tipo de Licencia': 'descripcion',
            Documento: 'documento',
            'Apellido y Nombre': 'apellido_nombre',
            Puesto: 'funcion',
            Servicio: 'codigo_nombre',
            //'Profesion/Formancion': '',
            Efector: 'efector',
            'Evento Nombre': 'evento_nombre',
            'Fecha Evento Inicio': {
                field: 'fecha_evento_inicio',
                callback: (value) => {
                    return value !== null
                        ? moment(value).utcOffset('+0300').format('DD/MM/YYYY')
                        : 'No hay fecha establecida';
                },
            },
            'Fecha Evento Final': {
                field: 'fecha_evento_final',
                callback: (value) => {
                    return value !== null
                        ? moment(value).utcOffset('+0300').format('DD/MM/YYYY')
                        : 'No hay fecha establecida';
                },
            },
            Dias: 'dias',
            'Evento Lugar': 'evento_lugar',
            'Tipo de Evento': 'tipo_evento',
            Alcance: 'alcance',
            Caracter: 'caracter',
            'Fecha Efectiva Inicial': {
                field: 'fecha_efectiva_inicio',
                callback: (value) => {
                    return value !== '' && value !== null
                        ? moment(value).utcOffset('+0300').format('DD/MM/YYYY')
                        : 'No hay fecha establecida';
                },
            },
            'Fecha Final Efectiva': {
                field: 'fecha_efectiva_final',
                callback: (value) => {
                    return value !== '' && value !== null
                        ? moment(value).utcOffset('+0300').format('DD/MM/YYYY')
                        : 'No hay fecha establecida';
                },
            },
            'Fecha Carga': {
                field: 'created_at',
                callback: (value) => {
                    return value !== null
                        ? moment(value).utcOffset('+0300').format('DD/MM/YYYY')
                        : 'No hay fecha establecida';
                },
            },
            'Fecha Final del Pedido': {
                field: 'fecha_pedido_final',
                callback: (value) => {
                    return value !== null
                        ? moment(value).utcOffset('+0300').format('DD/MM/YYYY')
                        : 'No hay fecha establecida';
                },
            },
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
            'Primera Observacion': 'primera_observacion',
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
            'Segunda Observacion': 'segunda_observacion',
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
            'Fecha de la Interrupción': {
                field: 'fecha_interrupcion_inicio',
                callback: (value) => {
                    return value !== '' && value !== null
                        ? moment(value).utcOffset('+0300').format('DD/MM/YYYY')
                        : 'No hay fecha establecida';
                },
            },
        },
        seleccion: '',
        capacitacionSeleccionada: '',
        dates: [],
        headersAgente: [
            {
                text: 'Documento',
                align: 'start',
                sortable: false,
                value: 'documento',
            },
            { text: 'Apellido', value: 'apellido' },
            { text: 'Nombre', value: 'nombre' },
            { text: 'Fecha de Nacimiento', value: 'fnacimiento' },
        ],
        headersLicencia: [
            {
                value: 'dias',
                text: 'Dias de Licencia',
                sortable: true,
            },
            {
                value: 'idlicencia',
                text: 'Numero de Licencia',
                sortable: true,
            },
            {
                value: 'apellido_nombre',
                text: 'Apellido y Nombre',
                sortable: true,
            },
            {
                value: 'documento',
                text: 'Documento',
                sortable: true,
            },
            {
                value: 'efector',
                text: 'Efector',
                sortable: true,
            },
            {
                value: 'codigo_nombre',
                text: 'Codigo',
                sortable: true,
            },
            {
                value: 'funcion',
                text: 'Funcion',
                sortable: true,
            },
            {
                value: 'evento_nombre',
                text: 'Evento Nombre',
                sortable: true,
            },
            {
                value: 'evento_lugar',
                text: 'Evento Lugar',
                sortable: true,
            },
            {
                value: 'alcance',
                text: 'Alcance',
                sortable: true,
            },
            {
                value: 'caracter',
                text: 'Caracter',
                sortable: true,
            },
            {
                value: 'tipo_evento',
                text: 'Tipo de Evento',
                sortable: true,
            },
            {
                value: 'fecha_pedido_inicio',
                text: 'Fecha del Inicio del Pedido',
                sortable: true,
            },
            {
                value: 'fecha_pedido_final',
                text: 'Fecha Pedido Final',
                sortable: true,
            },
            {
                value: 'fecha_evento_inicio',
                text: 'Fecha de Comienzo del Evento',
                sortable: true,
            },
            {
                value: 'fecha_evento_final',
                text: 'Fecha de Fin del Evento',
                sortable: true,
            },
            {
                value: 'primer_visado',
                text: 'Primer Visado',
                sortable: true,
            },
            {
                value: 'fecha_visado_primero',
                text: 'Fecha Primer Visado',
                sortable: true,
            },
            {
                value: 'fecha_visado_segundo',
                text: 'Fecha Segundo Visado',
                sortable: true,
            },
            {
                value: 'fecha_efectiva_inicio',
                text: 'Fecha Efectiva Inicio',
                sortable: true,
            },
            {
                value: 'fecha_efectiva_final',
                text: 'Fecha Efectiva Final',
                sortable: true,
            },
            {
                value: 'segundo_visado',
                text: 'Segundo Visado',
                sortable: true,
            },
            {
                value: 'segunda_observacion',
                text: 'Observacion',
                sortable: true,
            },
            {
                value: 'cuarta_visado',
                text: 'Interrumpido',
                sortable: true,
            },
            {
                value: 'fecha_interrupcion_inicio',
                text: 'Fecha Interrupcion',
                sortable: true,
            },
        ],
        headersCapacitacion: [
            { text: 'Nombre', value: 'evento_nombre' },
            { text: 'Lugar', value: 'evento_lugar' },
            { text: 'Informacion Adic.', value: 'razon' },
            { text: 'Fecha de Inicio', value: 'fecha_evento_inicio' },
            { text: 'Fecha de Finalizacion', value: 'fecha_evento_final' },
            { text: 'Tipo de Evento', value: 'tipoevento' },
            { text: 'Alcance', value: 'alcance' },
        ],
    }),

    computed: {
        /**
         * Filtrar datos con segundo_visado: true
         */
        filteredLicenciasTrue() {
            return this.licenciaCapacitaciones.filter(
                (item) => item.segundo_visado === true,
            );
        },

        /**
         * Filtrar datos con segundo_visado: false
         */
        filteredLicenciasFalse() {
            return this.licenciaCapacitaciones.filter(
                (item) => item.segundo_visado === false,
            );
        },
        dateRangeText() {
            return this.dates.join(' ~ ');
        },
        capacitaciones() {
            return this.$store.getters['capacitacion/capacitaciones'];
        },
        agenteCapacitaciones() {
            return this.$store.getters['agente/capacitaciones'];
        },
        capacitacionAgentes() {
            return this.$store.getters['capacitacion/agentes'];
        },
        licenciaCapacitaciones() {
            return this.$store.getters['licencia/capacitaciones'];
        },
        agente() {
            return this.$store.getters['agente/agente'];
        },
        user() {
            return this.$store.getters['user/user'].nombreusuario;
        },
        exportExcelAgentes() {
            return this.capacitacionAgentes.filter((el) => {
                return this.selectedAgente
                    .map((el) => el.documento)
                    .includes(el.documento);
            });
        },
        exportExcelCapacitaciones() {
            return this.agenteCapacitaciones.filter((el) => {
                return this.selectedCapacitaciones
                    .map((el) => el.resolucion)
                    .includes(el.resolucion);
            });
        },
        dependencia() {
            return this.$store.getters['user/dependencia'];
        },
        dependencia_padre() {
            return this.$store.getters['user/dependencia_padre'];
        },
    },

    watch: {
        async seleccion(newValue) {
            this.loading = true;
            try {
                if (newValue === 'Capacitacion') {
                    await this.$store.dispatch('capacitacion/all');
                }
            } catch (ex) {
                console.error(ex);
            } finally {
                this.loading = false;
            }
        },
        async capacitacionSeleccionada(newValue) {
            this.loading = true;
            try {
                await this.$store.dispatch(
                    `capacitacion/agentes`,
                    newValue.idCapacitacion,
                );
            } catch (error) {
                console.error(error);
            } finally {
                this.loading = false;
            }
        },
    },

    async created() {
        await this.initialize();
    },

    methods: {
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
        exportPDFAgente() {
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

            var texto = 'Datos de la Capacitación';
            var xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            doc.text(texto, xOffset, 150);

            const cap = this.capacitacionSeleccionada;
            const capacitacion = [];
            capacitacion.push(cap);
            let columnas = [
                { title: 'Evento Nombre', dataKey: 'evento_nombre' },
                { title: 'Evento Lugar', dataKey: 'evento_lugar' },
                { title: 'Tipo Evento', dataKey: 'tipo_evento' },
                { title: 'Alcance', dataKey: 'alcance' },
                { title: 'Informacion Adic.', dataKey: 'razon' },
            ];
            doc.autoTable(columnas, capacitacion, {
                startY: 150 + 25,
            });
            const agentes = _.cloneDeep(
                this.$store.getters['capacitacion/agentes'],
            );

            texto = 'Listado de Agentes';
            xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            var finalY = doc.previousAutoTable.finalY;
            doc.text(texto, xOffset, finalY + 20);
            columnas = [
                { title: 'Documento', dataKey: 'documento' },
                { title: 'Nombre', dataKey: 'nombre' },
                { title: 'Apellido', dataKey: 'apellido' },
            ];

            doc.autoTable(columnas, agentes, {
                styles: { overflow: 'linebreak', fontSize: 7 },
                startY: finalY + 40,
            });
            addHeaders(doc);
            addFooters(doc);
            doc.save('agentes.pdf');
        },
        exportPDFCapacitacion() {
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

            var texto = 'Datos del Agente';
            var xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            doc.text(texto, xOffset, 150);

            const ag = this.$store.getters['agente/agente'];
            const agente = [];
            agente.push(ag);
            let columnas = [
                { title: 'Documento', dataKey: 'documento' },
                { title: 'Nombre', dataKey: 'nombre' },
                { title: 'Apellido', dataKey: 'apellido' },
            ];
            doc.autoTable(columnas, agente, {
                startY: 150 + 25,
            });
            const capacitaciones = _.cloneDeep(
                this.$store.getters['agente/capacitaciones'],
            );

            texto = 'Listado de Capacitaciones';
            xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            var finalY = doc.previousAutoTable.finalY;
            doc.text(texto, xOffset, finalY + 20);
            columnas = [
                { title: 'Evento Nombre', dataKey: 'evento_nombre' },
                { title: 'Evento Lugar', dataKey: 'evento_lugar' },
                { title: 'Tipo Evento', dataKey: 'tipo_evento' },
                { title: 'Alcance', dataKey: 'alcance' },
                { title: 'Informacion Adic.', dataKey: 'razon' },
            ];

            doc.autoTable(columnas, capacitaciones, {
                styles: { overflow: 'linebreak', fontSize: 7 },
                startY: finalY + 40,
            });
            addHeaders(doc);
            addFooters(doc);
            doc.save('capacitaciones.pdf');
        },
        exportPDFLicencias() {
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
            var texto = '';
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
                doc.internal.pageSize.width * 0.8,
                60,
                130,
                24,
            );
            doc.text(texto, xOffset, 150);

            let licencias = _.cloneDeep(
                this.$store.getters['licencia/capacitaciones'],
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
                let fvp =
                    licencia.fecha_visado_primero != null
                        ? licencia.fecha_visado_primero.substr(0, 10).split('-')
                        : null;
                let fvs =
                    licencia.fecha_visado_segundo != null
                        ? licencia.fecha_visado_segundo.substr(0, 10).split('-')
                        : null;
                licencias[idx].fecha_visado_primero =
                    fvp != null
                        ? new Date(
                              fvp[0],
                              parseInt(fvp[1]) - 1,
                              fvp[2],
                          ).toLocaleDateString()
                        : 'No hay fecha';
                licencias[idx].fecha_visado_segundo =
                    fvs != null
                        ? new Date(
                              fvs[0],
                              parseInt(fvs[1]) - 1,
                              fvs[2],
                          ).toLocaleDateString()
                        : 'No hay fecha';
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

            doc.text(texto, xOffset, 150 + 25);
            const columnas = [
                { title: 'Nº de Lic.', dataKey: 'idlicencia' },
                {
                    title: 'Fecha Primer Visado',
                    dataKey: 'fecha_visado_primero',
                },
                {
                    title: 'Fecha Segundo Visado',
                    dataKey: 'fecha_visado_segundo',
                },
                { title: 'Tipo de Licencia', dataKey: 'descripcion' },
                { title: 'Dias', dataKey: 'dias' },
                { title: 'Documento', dataKey: 'documento' },
                { title: 'Apellido y Nombre', dataKey: 'apellido_nombre' },
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
                startY: 220 + 40,
            });
            addHeaders(doc);
            addFooters(doc);
            doc.save('licencias.pdf');
        },
        async buscarLicencias() {
            this.loading = true;
            try {
                await this.$store.dispatch('licencia/getCapacitacion', [
                    ...this.dates,
                    this.dependencia?.iddependencia
                        ? this.dependencia.iddependencia
                        : this.dependencia_padre.iddependencia,
                ]);
            } catch (error) {
                console.error(error);
            } finally {
                this.loading = false;
            }
        },
        async habilitarBusqueda(evt) {
            this.loading = true;
            try {
                this.$store.dispatch('agente/resetCapacitaciones');
                await this.$store.dispatch(
                    'agente/getCapacitacion',
                    this.agente.idagente,
                );
            } catch (error) {
                console.error(error);
            } finally {
                this.loading = false;
            }
        },
        async initialize() {},
    },
};
</script>

<style scoped>
.v-data-table {
    overflow-x: auto;
}
/* Estilos personalizados para las filas */
.row-green {
    background-color: #d4edda !important; /* Verde claro */
    border: 2px solid green;
}
.row-yellow {
    background-color: #fff3cd !important; /* Amarillo claro */
    border: 2px solid yellow;
}
.row-red {
    background-color: #f8d7da !important; /* Rojo claro */
    border: 2px solid red;
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
