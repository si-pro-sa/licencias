<template>
    <v-container>
        <v-card>
            <v-row>
                <v-col>
                    <v-form v-if="show">
                        <v-container>
                            <v-row>
                                <v-col>
                                    <v-text-field
                                        v-model="texto_tipo_licencia"
                                        label="Tipo de Licencia"
                                        disabled
                                    ></v-text-field>
                                    <transition name="fade">
                                        <v-alert
                                            border="top"
                                            color="red lighten-2"
                                            dark
                                            v-if="get_mensaje"
                                            >{{ mensaje }}
                                        </v-alert>
                                    </transition>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col class="d-flex flex-row-reverse p-5">
                                    <v-btn
                                        v-show="
                                            [
                                                1, 2, 3, 4, 37, 38, 39, 40, 21,
                                                22, 11, 8, 7,
                                            ].includes(tipoLicencia)
                                        "
                                        color="primary"
                                        :class="{
                                            'filled-diagnostico':
                                                diagnostico !== null &&
                                                diagnostico.codigo_icd10,
                                        }"
                                        block
                                        @click="openDiagnosticoModal"
                                    >
                                        <v-icon left>mdi-plus</v-icon>
                                        Agregar Diagnóstico y Observaciones
                                        <v-icon
                                            v-if="
                                                diagnostico !== null &&
                                                diagnostico.codigo_icd10
                                            "
                                            color="white"
                                        >
                                            mdi-check-circle
                                        </v-icon>
                                    </v-btn>
                                </v-col>
                            </v-row>
                            <v-row
                                ><v-col>
                                    <diagnostico-dialog
                                        v-model="isDiagnosticoModalOpen"
                                        @save-diagnostico="handleDiagnostico"
                                        :visar="visar"
                                        :idlicencia="idlicencia"
                                    ></diagnostico-dialog></v-col
                            ></v-row>
                        </v-container>

                        <v-container
                            v-show="visar === 0 || visar === 3 || visar === 7"
                        >
                            <v-row>
                                <v-col>
                                    <v-row>
                                        <vc-date-picker
                                            id="pedido_inicio"
                                            v-model="
                                                licencia.fecha_pedido_inicio
                                            "
                                            color="teal"
                                            locale="es-AR"
                                            :attributes="attributes"
                                            :popover="{
                                                placement: 'bottom',
                                                visibility: 'click',
                                            }"
                                        >
                                            <v-icon
                                                color="blue"
                                                x-large
                                                >mdi-calendar
                                            </v-icon>
                                        </vc-date-picker>
                                        <v-text-field
                                            v-model="getFechaPedidoInicio"
                                            outlined
                                            clearable
                                            label="Fecha Inicial del Pedido"
                                            disabled
                                        ></v-text-field>
                                    </v-row>
                                    <v-row>
                                        <vc-date-picker
                                            v-model="
                                                licencia.fecha_pedido_final
                                            "
                                            color="teal"
                                            locale="es-AR"
                                            :attributes="attributes"
                                            :min-date="
                                                get_fecha_pedido_inicio
                                                    ? get_fecha_pedido_inicio
                                                    : new Date()
                                            "
                                            :popover="{
                                                placement: 'bottom',
                                                visibility: 'click',
                                            }"
                                        >
                                            <v-icon
                                                color="blue"
                                                x-large
                                                >mdi-calendar
                                            </v-icon>
                                        </vc-date-picker>
                                        <v-text-field
                                            outlined
                                            clearable
                                            v-model="getFechaPedidoFinal"
                                            label="Fecha Final del Pedido"
                                            disabled
                                        ></v-text-field>
                                    </v-row>
                                    <v-select
                                        v-show="
                                            tipoLicencia === 11 ||
                                            tipoLicencia === 21 ||
                                            tipoLicencia === 22 ||
                                            tipoLicencia === 23 ||
                                            tipoLicencia === 24 ||
                                            tipoLicencia === 35 ||
                                            tipoLicencia === 36
                                        "
                                        label="Persona a Cargo"
                                        v-model="licencia.idpersona"
                                        :items="personas"
                                        required
                                    ></v-select>
                                    <v-select
                                        v-show="tipoLicencia === 14"
                                        label="Parentesco"
                                        v-model="licencia.parentescoFallecido"
                                        :items="fallecimiento"
                                        required
                                    ></v-select>

                                    <div
                                        v-show="
                                            tipoLicencia === 18 ||
                                            tipoLicencia === 19
                                        "
                                    >
                                        <v-select
                                            v-model="licencia.caracter"
                                            :items="itemsCaracter"
                                            label="Carácter"
                                        ></v-select>
                                        <v-btn
                                            color="primary"
                                            @click="resetForm()"
                                            ><i class="fas fa-eraser mx-2"></i>
                                            Limpiar</v-btn
                                        >
                                        <v-dialog
                                            transition="dialog-top-transition"
                                            max-width="950"
                                            v-model="dialogCapacitacion"
                                        >
                                            <template
                                                v-slot:activator="{ on, attrs }"
                                            >
                                                <v-btn
                                                    color="primary"
                                                    v-bind="attrs"
                                                    v-on="on"
                                                    ><i
                                                        class="fas fa-search mx-2"
                                                    ></i>
                                                    Capacitacion</v-btn
                                                >
                                            </template>

                                            <v-card>
                                                <v-toolbar
                                                    color="primary"
                                                    dark
                                                    >Buscar
                                                    Capacitacion</v-toolbar
                                                >

                                                <CapacitacionTab
                                                    @seleccionada="
                                                        setCapacitacion
                                                    "
                                                />

                                                <v-card-actions
                                                    class="justify-end"
                                                >
                                                    <v-btn
                                                        text
                                                        @click="
                                                            dialogCapacitacion = false
                                                        "
                                                        >Cerrar</v-btn
                                                    >
                                                </v-card-actions>
                                            </v-card>
                                        </v-dialog>
                                        <v-text-field
                                            label="Nombre del Evento"
                                            v-model="licencia.evento_nombre"
                                            disabled
                                        ></v-text-field>
                                        <v-text-field
                                            label="Lugar del Evento"
                                            v-model="licencia.evento_lugar"
                                            disabled
                                        ></v-text-field>
                                        <v-text-field
                                            label="Alcance"
                                            v-model="licencia.alcance"
                                            disabled
                                        ></v-text-field>

                                        <v-text-field
                                            label="Fecha de Inicio del Evento"
                                            disabled
                                            :value="
                                                licencia.fecha_evento_inicio
                                            "
                                        ></v-text-field>
                                        <v-text-field
                                            label="Fecha de Fin del Evento"
                                            disabled
                                            :value="licencia.fecha_evento_final"
                                        ></v-text-field>
                                        <v-text-field
                                            label="Información Adicional de la Capacitación"
                                            disabled
                                            v-model="licencia.razon"
                                        ></v-text-field>
                                    </div>
                                </v-col>
                            </v-row>
                        </v-container>

                        <v-container
                            v-show="visar !== 0 && visar !== 3 && visar !== 7"
                        >
                            <v-row>
                                <v-col>
                                    <v-text-field
                                        label="Fecha de Inicio de Pedido Licencia"
                                        v-model="getFechaPedidoInicio"
                                        disabled
                                    ></v-text-field>
                                    <v-text-field
                                        label="Fecha de Fin de Pedido Licencia"
                                        v-model="getFechaPedidoFinal"
                                        disabled
                                    ></v-text-field>
                                    <div v-show="visar === 5">
                                        <v-text-field
                                            label="Fecha Inicial de la Licencia Efectiva"
                                            v-model="getFechaEfectivoInicio"
                                            disabled
                                        ></v-text-field>
                                        <v-text-field
                                            label="Fecha Final de la Licencia Efectiva"
                                            v-model="getFechaEfectivoFinal"
                                            disabled
                                        ></v-text-field>
                                    </div>

                                    <v-select
                                        v-show="
                                            tipoLicencia === 11 ||
                                            tipoLicencia === 20
                                        "
                                        label="Persona a Cargo"
                                        v-model="licencia.idpersona"
                                        :items="personas"
                                        disabled
                                    ></v-select>

                                    <div
                                        v-show="
                                            tipoLicencia === 18 ||
                                            tipoLicencia === 19
                                        "
                                    >
                                        <v-text-field
                                            label="Caracter"
                                            v-model="licencia.caracter"
                                            disabled
                                        ></v-text-field>
                                        <v-text-field
                                            label="Nombre del Evento"
                                            v-model="licencia.evento_nombre"
                                            disabled
                                        ></v-text-field>
                                        <v-text-field
                                            label="Lugar del Evento"
                                            v-model="licencia.evento_lugar"
                                            disabled
                                        ></v-text-field>
                                        <v-text-field
                                            label="Alcance"
                                            v-model="licencia.alcance"
                                            disabled
                                        ></v-text-field>

                                        <v-text-field
                                            label="Fecha de Inicio del Evento"
                                            disabled
                                            :value="
                                                licencia.fecha_evento_inicio
                                            "
                                        ></v-text-field>
                                        <v-text-field
                                            label="Fecha de Fin del Evento"
                                            disabled
                                            :value="licencia.fecha_evento_final"
                                        ></v-text-field>
                                        <v-text-field
                                            label="Información Adicional de la Capacitación"
                                            v-model="licencia.razon"
                                            disabled
                                        ></v-text-field>
                                    </div>
                                </v-col>
                            </v-row>
                        </v-container>
                        <!-- Visible para visar 1 -->
                        <v-container v-show="visar === 1 || visar === 7">
                            <v-row>
                                <v-col>
                                    <v-radio-group
                                        name="licencia.primer_visado"
                                        v-model="licencia.primer_visado"
                                        label="Primer Visado"
                                        row
                                    >
                                        <v-radio
                                            label="Visado por SI"
                                            color="success"
                                            :value="true"
                                        ></v-radio>
                                        <v-radio
                                            label="Visado por NO"
                                            color="red"
                                            :value="false"
                                        ></v-radio>
                                    </v-radio-group>

                                    <v-text-field
                                        label="Observacion"
                                        v-model="licencia.observacion_primera"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-container>
                        <!-- Fin para visar  -->

                        <!-- Visible para visar 2 -->
                        <v-container v-show="visar === 2">
                            <v-row>
                                <v-col>
                                    <v-text-field
                                        label="Visado"
                                        v-model="primer_visado"
                                        disabled
                                    ></v-text-field>

                                    <v-text-field
                                        label="Observacion"
                                        v-model="licencia.observacion_primera"
                                        disabled
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-container>

                        <v-container v-show="visar === 2 || visar === 7">
                            <v-row>
                                <v-col>
                                    <v-radio-group
                                        name="licencia.segundo_visado"
                                        v-model="licencia.segundo_visado"
                                        label="Segundo Visado"
                                        row
                                    >
                                        <v-radio
                                            label="Visar por SI"
                                            color="success"
                                            :value="true"
                                        ></v-radio>
                                        <v-radio
                                            label="Visar por NO"
                                            color="red"
                                            :value="false"
                                        ></v-radio>
                                    </v-radio-group>

                                    <div
                                        v-show="
                                            licencia.segundo_visado === true
                                        "
                                    >
                                        <v-row>
                                            <v-col
                                                cols="12"
                                                sm="12"
                                                xl="12"
                                            >
                                                <v-text-field
                                                    label="Resolucion"
                                                    v-model="
                                                        licencia.resolucion
                                                    "
                                                ></v-text-field>
                                            </v-col>
                                            <v-col
                                                cols="12"
                                                sm="12"
                                                xl="12"
                                            >
                                                <v-row>
                                                    <vc-date-picker
                                                        v-model="
                                                            licencia.fecha_efectiva_inicio
                                                        "
                                                        color="blue"
                                                        locale="es-AR"
                                                        :min-date="
                                                            get_fecha_pedido_inicio
                                                        "
                                                        :max-date="
                                                            get_fecha_pedido_final
                                                        "
                                                        :popover="{
                                                            placement: 'bottom',
                                                            visibility: 'click',
                                                        }"
                                                    >
                                                        <v-icon
                                                            color="blue"
                                                            x-large
                                                            >mdi-calendar
                                                        </v-icon>
                                                    </vc-date-picker>
                                                    <v-text-field
                                                        v-model="
                                                            getFechaEfectivoInicio
                                                        "
                                                        label="Fecha de Inicio Efectivo"
                                                        disabled
                                                    ></v-text-field>
                                                </v-row>
                                            </v-col>
                                            <v-col
                                                cols="12"
                                                sm="12"
                                                xl="12"
                                            >
                                                <v-row>
                                                    <vc-date-picker
                                                        v-model="
                                                            licencia.fecha_efectiva_final
                                                        "
                                                        color="blue"
                                                        locale="es-AR"
                                                        :min-date="
                                                            get_fecha_pedido_inicio
                                                        "
                                                        :max-date="
                                                            get_fecha_pedido_final
                                                        "
                                                        :popover="{
                                                            placement: 'bottom',
                                                            visibility: 'click',
                                                        }"
                                                    >
                                                        <v-icon
                                                            color="blue"
                                                            x-large
                                                            >mdi-calendar
                                                        </v-icon>
                                                    </vc-date-picker>
                                                    <v-text-field
                                                        v-model="
                                                            getFechaEfectivoFinal
                                                        "
                                                        label="Fecha de Final Efectivo"
                                                        disabled
                                                    ></v-text-field>
                                                </v-row>
                                            </v-col>
                                        </v-row>
                                    </div>

                                    <v-textarea
                                        v-model="licencia.observacion_segunda"
                                        auto-grow
                                        color="deep-purple"
                                        label="Observacion"
                                        rows="4"
                                        row-height="30"
                                    ></v-textarea>
                                </v-col>
                            </v-row>
                        </v-container>
                        <!-- Fin para visar 2 -->

                        <!-- Interrupcion -->
                        <v-container v-show="visar === 5">
                            <v-row>
                                <v-col>
                                    <v-radio-group
                                        name="licencia.cuarta_visado"
                                        v-model="licencia.cuarta_visado"
                                        label="Interrumpir Licencia"
                                        row
                                    >
                                        <v-radio
                                            label="Interrupción por SI"
                                            color="success"
                                            :value="true"
                                        ></v-radio>
                                        <v-radio
                                            label="Interrupción por NO"
                                            color="red"
                                            :value="false"
                                        ></v-radio>
                                    </v-radio-group>

                                    <v-row
                                        v-show="licencia.cuarta_visado === true"
                                    >
                                        <v-col>
                                            <vc-date-picker
                                                v-model="
                                                    licencia.fecha_interrupcion_inicio
                                                "
                                                :min-date="
                                                    get_fecha_efectiva_inicio
                                                "
                                                :max-date="
                                                    get_fecha_efectiva_final
                                                "
                                                color="red"
                                                locale="es-AR"
                                                :popover="{
                                                    placement: 'bottom',
                                                    visibility: 'click',
                                                }"
                                            >
                                                <v-icon
                                                    color="blue"
                                                    x-large
                                                    >mdi-calendar
                                                </v-icon>
                                            </vc-date-picker>
                                            <v-text-field
                                                v-model="getFechaInterrupcion"
                                                label="Fecha de Interrupcion de Licencia"
                                                disabled
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-text-field
                                        label="Observacion de la Interrupcion"
                                        v-model="licencia.observacion_cuarta"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-container>
                        <!-- Fin Interrupcion -->
                        <!-- Botonera principal -->
                        <v-container v-show="visar === 0">
                            <v-row>
                                <v-col class="d-flex flex-row-reverse p-5">
                                    <v-btn
                                        class="mr-2"
                                        outlined
                                        color="success"
                                        @click="onSubmit"
                                        variant="outline-success"
                                        :disabled="deshabilitarBoton"
                                    >
                                        <v-icon>mdi-content-save</v-icon>
                                        Enviar
                                    </v-btn>
                                    <v-btn
                                        class="mr-2"
                                        outlined
                                        color="error"
                                        @click="volverLicencia"
                                    >
                                        <v-icon>mdi-reply</v-icon>
                                        Volver
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-container>
                        <!-- Botonera Secundario de Modificacion -->

                        <v-container v-show="visar !== 0">
                            <v-row>
                                <v-col class="d-flex flex-row-reverse p-5">
                                    <v-btn
                                        class="mr-2"
                                        outlined
                                        color="success"
                                        @click="onUpdate"
                                    >
                                        <v-icon>mdi-briefcase-edit</v-icon>
                                        Modificar
                                    </v-btn>
                                    <v-btn
                                        class="mr-2"
                                        outlined
                                        color="error"
                                        @click="volverLicencia"
                                    >
                                        <v-icon>mdi-reply</v-icon>
                                        Volver
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-form>
                </v-col>
            </v-row>
        </v-card>
    </v-container>
</template>

<script>
Date.prototype.addDays = function (days) {
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() + days);
    return date;
};
import moment from 'moment';
import CapacitacionTab from '../capacitacion/CapacitacionTab.vue';
import DiagnosticoDialog from './DiagnosticoDialog.vue';
export default {
    name: 'LicenciaCreate',
    components: {
        CapacitacionTab,
        DiagnosticoDialog,
    },
    created() {
        //TODO: asegurar los catch para que no haya perdida de conexion
        if (this.tipoLicencia === 11 && this.idlicencia === 0) {
            this.buscarPersonasActivas();
        }
        if (
            (this.tipoLicencia === 21 ||
                this.tipoLicencia === 22 ||
                this.tipoLicencia === 35 ||
                this.tipoLicencia === 36) &&
            this.idlicencia === 0
        ) {
            this.buscarPersonasActivas(true);
        }
        this.getDiasPosibles();
        this.getFeriados();
        console.log(`get feriados: ${this.getFeriados()}`);
        if (this.tipoLicencia === 18 || this.tipoLicencia === 19) {
            this.fetchCaracter();
        }
        this.buscarLicencias();
        if (this.idlicencia > 0) {
            this.buscarLicencia(this.idlicencia);
        }

        console.log('Como comienza el saldo ', this.diasAnualesMaximos);
    },
    props: {
        idlicencia: {
            type: Number,
        },
        visar: {
            type: Number,
        },
        tipoLicencia: {
            type: Number,
            default: 1,
        },
    },
    data: function () {
        return {
            dialogCapacitacion: false,
            dialogDiagnostico: false,
            isDiagnosticoModalOpen: false,
            deshabilitarBoton: false,
            ruleGeneric: [(v) => !!v || 'Campo es requerido'],
            attributes: [
                {
                    bar: {
                        color: 'red',
                        class: 'my-dot-class',
                    },
                    dates: [],
                },
            ],
            max_date: 0,
            min_date: 0,
            titulo: 'Nueva Licencia',
            working: 0,
            antiguedades: [],
            itemsCaracter: [],
            diagnostico: null, // Aquí almacenaremos el diagnóstico completo
            licencia: {
                alcance: '',
                fecha_pedido_inicio: new Date(),
                fecha_pedido_final: new Date(),
                fecha_efectiva_inicio: new Date(),
                fecha_efectiva_final: new Date(),
                fecha_evento_inicio: new Date().toLocaleDateString('es-AR'),
                fecha_evento_final: new Date().toLocaleDateString('es-AR'),
                fecha_interrupcion_inicio: new Date(),
                fecha_interrupcion_final: null,
                primer_visado: false,
                segundo_visado: false,
                tercera_visado: false,
                cuarta_visado: false,
                observacion_primera: '',
                observacion_segunda: '',
                observacion_tercera: '',
                observacion_cuarta: '',
                idtipoLicencia: '',
                idpersona: null,
                evento_nombre: '',
                evento_lugar: '',
                razon: '',
                caracter: '',
                resolucion: '',
                tipo_evento: '',
                parentescoFallecido: '',
                dias: 0,
                idCapacitacion: 0,
            },
            licenciaReset: {
                alcance: '',
                fecha_pedido_inicio: new Date(),
                fecha_pedido_final: new Date(),
                fecha_efectiva_inicio: new Date(),
                fecha_efectiva_final: new Date(),
                fecha_evento_inicio: new Date().toLocaleDateString('es-AR'),
                fecha_evento_final: new Date().toLocaleDateString('es-AR'),
                fecha_interrupcion_inicio: new Date(),
                fecha_interrupcion_final: null,
                primer_visado: false,
                segundo_visado: false,
                tercera_visado: false,
                cuarta_visado: false,
                observacion_primera: '',
                observacion_segunda: '',
                observacion_tercera: '',
                observacion_cuarta: '',
                idtipoLicencia: '',
                idpersona: null,
                evento_nombre: '',
                evento_lugar: '',
                razon: '',
                caracter: '',
                resolucion: '',
                tipo_evento: '',
                parentescoFallecido: '',
                dias: 0,
                idCapacitacion: 0,
            },
            licenciasPrimerVisado: [9, 10, 12, 13, 14, 16, 17, 25, 26, 27, 28],
            fecha_pedido_inicio: new Date().toISOString().substr(0, 10),
            fecha_pedido_final: new Date().toISOString().substr(0, 10),
            fecha_efectiva_inicio: new Date().toISOString().substr(0, 10),
            fecha_efectiva_final: new Date().toISOString().substr(0, 10),
            fecha_evento_inicio: new Date().toLocaleDateString('es-AR'),
            fecha_evento_final: new Date().toLocaleDateString('es-AR'),
            fecha_interrupcion_inicio: new Date().toISOString().substr(0, 10),
            dia: 0,
            feriados: [],
            personas: [],
            mensaje: '',
            fallecimiento: [
                {
                    text: 'Seleccione un Parentesco',
                    value: 0,
                },
                { text: 'Padre', value: 1 },
                { text: 'Madre', value: 2 },
                { text: 'Hijo', value: 3 },
                { text: 'Hija', value: 4 },
                { text: 'Nieto', value: 5 },
                { text: 'Nieta', value: 6 },
                { text: 'Hermano', value: 7 },
                { text: 'Hermana', value: 8 },
                { text: 'Abuelo', value: 9 },
                { text: 'Abuela', value: 10 },
                { text: 'Suegro', value: 11 },
                { text: 'Suegra', value: 12 },
                { text: 'Yerno', value: 13 },
                { text: 'Nuera', value: 14 },
                { text: 'Conyuge', value: 15 },
            ],
            show: true,
            existeSanciones: false,
            totalRowsSaldos: 0,
            totalRowsLicencias: 0,
            totalRowsSaldosTotales: 0,
            totalRowsLicenciasTotales: 0,
            error: '',
            resetAntiguedades: [],
            licencias: [],
            licencias_totales: [],
            saldos: [],
            saldos_totales: [],
            saldosInsertar: [],
            licenciasRequierenUnaPersonaACargo: [11, 21, 22, 35, 36],
            licenciasConMinimoPorVez: [19],
            licenciasHabiles: [11, 14, 16, 17, 25, 27, 28],
            licenciasLAO: [16, 17, 25, 27],
            licenciasConGoce: [9, 12, 10, 13, 14, 28],
            licenciasConMaximoPorVez: [
                1, 2, 7, 8, 9, 10, 12, 13, 14, 15, 17, 18, 19, 25, 28, 38, 39,
                40,
            ],
            licenciasConMaximoAnual: [
                1, 10, 11, 15, 21, 22, 25, 28, 35, 36, 40,
            ],
            licenciasConMaximoMensual: [17, 38],
            licenciasConMaximoAntiguedad: [16, 17, 25, 27],
            licenciasSinControl: [35, 36],
            licenciasMujeres: [37, 38, 39, 40],
            diasAnualesMaximos: {
                2010: {
                    10: 150,
                    11: 10,
                    1: 30,
                    15: 93,
                    21: 93,
                    22: 180,
                    25: 6,
                    28: 20,
                },
                2011: {
                    10: 150,
                    11: 10,
                    1: 30,
                    15: 93,
                    21: 93,
                    22: 180,
                    25: 6,
                    28: 20,
                },
                2012: {
                    10: 150,
                    11: 10,
                    1: 30,
                    15: 93,
                    21: 93,
                    22: 180,
                    25: 6,
                    28: 20,
                },
                2013: {
                    10: 150,
                    11: 10,
                    1: 30,
                    15: 93,
                    21: 93,
                    22: 180,
                    25: 6,
                    28: 20,
                },
                2014: {
                    10: 150,
                    11: 10,
                    1: 30,
                    15: 93,
                    21: 93,
                    22: 180,
                    25: 6,
                    28: 20,
                },
                2015: {
                    10: 150,
                    11: 10,
                    1: 30,
                    15: 93,
                    21: 93,
                    22: 180,
                    25: 6,
                    28: 20,
                },
                2016: {
                    10: 150,
                    11: 10,
                    1: 30,
                    15: 93,
                    21: 93,
                    22: 180,
                    25: 6,
                    28: 20,
                },
                2017: {
                    10: 150,
                    11: 10,
                    1: 30,
                    15: 93,
                    21: 93,
                    22: 180,
                    25: 6,
                    28: 20,
                },
                2018: {
                    10: 150,
                    11: 10,
                    1: 30,
                    15: 93,
                    21: 93,
                    22: 180,
                    25: 6,
                    28: 20,
                },
                2019: {
                    10: 150,
                    11: 10,
                    1: 45,
                    15: 93,
                    21: 93,
                    22: 180,
                    25: 6,
                    28: 20,
                },
                2020: {
                    10: 150,
                    11: 10,
                    1: 300,
                    15: 93,
                    21: 150,
                    22: 300,
                    25: 6,
                    28: 20,
                    32: 30,
                },
                2021: {
                    10: 150,
                    11: 10,
                    1: 300,
                    15: 93,
                    21: 150,
                    22: 300,
                    25: 6,
                    28: 20,
                    32: 30,
                },
                2022: {
                    10: 150,
                    11: 10,
                    1: 45,
                    15: 93,
                    21: 150,
                    22: 300,
                    25: 6,
                    28: 20,
                    32: 30,
                },
                2023: {
                    10: 150,
                    11: 10,
                    1: 45,
                    15: 93,
                    21: 150,
                    22: 300,
                    25: 6,
                    28: 20,
                    32: 30,
                    35: 90,
                    36: 180,
                    40: 2,
                },
                2024: {
                    10: 150,
                    11: 10,
                    1: 45,
                    15: 93,
                    21: 150,
                    22: 300,
                    25: 6,
                    28: 20,
                    32: 30,
                    35: 90,
                    36: 180,
                    40: 2,
                },
                2025: {
                    10: 150,
                    11: 10,
                    1: 45,
                    15: 93,
                    21: 150,
                    22: 300,
                    25: 6,
                    28: 20,
                    32: 30,
                    35: 90,
                    36: 180,
                    40: 2,
                },
                2026: {
                    10: 150,
                    11: 10,
                    1: 45,
                    15: 93,
                    21: 150,
                    22: 300,
                    25: 6,
                    28: 20,
                    32: 30,
                    35: 90,
                    36: 180,
                    40: 2,
                },
            },
            diasInsertarSaldo: {
                2010: {},
                2011: {},
                2012: {},
                2013: {},
                2014: {},
                2015: {},
                2016: {},
                2017: {},
                2018: {},
                2019: {},
                2020: {},
                2021: {},
                2022: {},
                2023: {},
                2024: {},
                2025: {},
                2026: {},
            },
            diasMensualesMaximos: {
                2010: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2011: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2012: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2013: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2014: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2015: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2016: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2017: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2018: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2019: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2020: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2021: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2022: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2023: {
                    1: { 17: 1, 38: 1 },
                    2: { 17: 1, 38: 1 },
                    3: { 17: 1, 38: 1 },
                    4: { 17: 1, 38: 1 },
                    5: { 17: 1, 38: 1 },
                    6: { 17: 1, 38: 1 },
                    7: { 17: 1, 38: 1 },
                    8: { 17: 1, 38: 1 },
                    9: { 17: 1, 38: 1 },
                    10: { 17: 1, 38: 1 },
                    11: { 17: 1, 38: 1 },
                    12: { 17: 1, 38: 1 },
                },
                2024: {
                    1: { 17: 1, 38: 1 },
                    2: { 17: 1, 38: 1 },
                    3: { 17: 1, 38: 1 },
                    4: { 17: 1, 38: 1 },
                    5: { 17: 1, 38: 1 },
                    6: { 17: 1, 38: 1 },
                    7: { 17: 1, 38: 1 },
                    8: { 17: 1, 38: 1 },
                    9: { 17: 1, 38: 1 },
                    10: { 17: 1, 38: 1 },
                    11: { 17: 1, 38: 1 },
                    12: { 17: 1, 38: 1 },
                },
                2025: {
                    1: { 17: 1, 38: 1 },
                    2: { 17: 1, 38: 1 },
                    3: { 17: 1, 38: 1 },
                    4: { 17: 1, 38: 1 },
                    5: { 17: 1, 38: 1 },
                    6: { 17: 1, 38: 1 },
                    7: { 17: 1, 38: 1 },
                    8: { 17: 1, 38: 1 },
                    9: { 17: 1, 38: 1 },
                    10: { 17: 1, 38: 1 },
                    11: { 17: 1, 38: 1 },
                    12: { 17: 1, 38: 1 },
                },
                2026: {
                    1: { 17: 1, 38: 1 },
                    2: { 17: 1, 38: 1 },
                    3: { 17: 1, 38: 1 },
                    4: { 17: 1, 38: 1 },
                    5: { 17: 1, 38: 1 },
                    6: { 17: 1, 38: 1 },
                    7: { 17: 1, 38: 1 },
                    8: { 17: 1, 38: 1 },
                    9: { 17: 1, 38: 1 },
                    10: { 17: 1, 38: 1 },
                    11: { 17: 1, 38: 1 },
                    12: { 17: 1, 38: 1 },
                },
            },
            diasMaximosPorVez: {
                1: 150,
                2: 30,
                7: 93,
                8: 180,
                9: 12,
                10: 180,
                12: 15,
                13: 60,
                14: 2,
                15: 93,
                17: 1,
                18: 10,
                19: 93,
                25: 6,
                28: 5,
                38: 1,
                39: 2,
                40: 2,
            },
            resetdiasAnualesMaximos: {
                2010: {
                    10: 150,
                    11: 10,
                    1: 30,
                    15: 93,
                    21: 93,
                    22: 180,
                    28: 20,
                },
                2011: {
                    10: 150,
                    11: 10,
                    1: 30,
                    15: 93,
                    21: 93,
                    22: 180,
                    28: 20,
                },
                2012: {
                    10: 150,
                    11: 10,
                    1: 30,
                    15: 93,
                    21: 93,
                    22: 180,
                    28: 20,
                },
                2013: {
                    10: 150,
                    11: 10,
                    1: 30,
                    15: 93,
                    21: 93,
                    22: 180,
                    28: 20,
                },
                2014: {
                    10: 150,
                    11: 10,
                    1: 30,
                    15: 93,
                    21: 93,
                    22: 180,
                    28: 20,
                },
                2015: {
                    10: 150,
                    11: 10,
                    1: 30,
                    15: 93,
                    21: 93,
                    22: 180,
                    28: 20,
                },
                2016: {
                    10: 150,
                    11: 10,
                    1: 30,
                    15: 93,
                    21: 93,
                    22: 180,
                    28: 20,
                },
                2017: {
                    10: 150,
                    11: 10,
                    1: 30,
                    15: 93,
                    21: 93,
                    22: 180,
                    28: 20,
                },
                2018: {
                    10: 150,
                    11: 10,
                    1: 30,
                    15: 93,
                    21: 93,
                    22: 180,
                    28: 20,
                },
                2019: {
                    10: 150,
                    11: 10,
                    1: 45,
                    15: 93,
                    21: 150,
                    22: 300,
                    28: 20,
                },
                2020: {
                    10: 150,
                    11: 10,
                    1: 300,
                    15: 93,
                    21: 150,
                    22: 300,
                    28: 20,
                    32: 30,
                },
                2021: {
                    10: 150,
                    11: 10,
                    1: 300,
                    15: 93,
                    21: 150,
                    22: 300,
                    28: 20,
                    32: 30,
                },
                2022: {
                    10: 150,
                    11: 10,
                    1: 45,
                    15: 93,
                    21: 150,
                    22: 300,
                    28: 20,
                    32: 30,
                },
                2023: {
                    10: 150,
                    11: 10,
                    1: 45,
                    15: 93,
                    21: 150,
                    22: 300,
                    28: 20,
                    32: 30,
                    35: 90,
                    36: 180,
                    40: 2,
                },
                2024: {
                    10: 150,
                    11: 10,
                    1: 45,
                    15: 93,
                    21: 150,
                    22: 300,
                    28: 20,
                    32: 30,
                    35: 90,
                    36: 180,
                    40: 2,
                },
                2025: {
                    10: 150,
                    11: 10,
                    1: 45,
                    15: 93,
                    21: 150,
                    22: 300,
                    28: 20,
                    32: 30,
                    35: 90,
                    36: 180,
                    40: 2,
                },
                2026: {
                    10: 150,
                    11: 10,
                    1: 45,
                    15: 93,
                    21: 150,
                    22: 300,
                    28: 20,
                    32: 30,
                    35: 90,
                    36: 180,
                    40: 2,
                },
            },
            resetdiasInsertarSaldo: {
                2010: {},
                2011: {},
                2012: {},
                2013: {},
                2014: {},
                2015: {},
                2016: {},
                2017: {},
                2018: {},
                2019: {},
                2020: {},
                2021: {},
                2022: {},
                2023: {},
                2024: {},
                2025: {},
                2026: {},
            },
            resetdiasMensualesMaximos: {
                2010: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2011: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2012: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2013: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2014: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2015: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2016: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2017: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2018: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2019: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2020: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2021: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2022: {
                    1: { 17: 1 },
                    2: { 17: 1 },
                    3: { 17: 1 },
                    4: { 17: 1 },
                    5: { 17: 1 },
                    6: { 17: 1 },
                    7: { 17: 1 },
                    8: { 17: 1 },
                    9: { 17: 1 },
                    10: { 17: 1 },
                    11: { 17: 1 },
                    12: { 17: 1 },
                },
                2023: {
                    1: { 17: 1, 38: 1 },
                    2: { 17: 1, 38: 1 },
                    3: { 17: 1, 38: 1 },
                    4: { 17: 1, 38: 1 },
                    5: { 17: 1, 38: 1 },
                    6: { 17: 1, 38: 1 },
                    7: { 17: 1, 38: 1 },
                    8: { 17: 1, 38: 1 },
                    9: { 17: 1, 38: 1 },
                    10: { 17: 1, 38: 1 },
                    11: { 17: 1, 38: 1 },
                    12: { 17: 1, 38: 1 },
                },
                2024: {
                    1: { 17: 1, 38: 1 },
                    2: { 17: 1, 38: 1 },
                    3: { 17: 1, 38: 1 },
                    4: { 17: 1, 38: 1 },
                    5: { 17: 1, 38: 1 },
                    6: { 17: 1, 38: 1 },
                    7: { 17: 1, 38: 1 },
                    8: { 17: 1, 38: 1 },
                    9: { 17: 1, 38: 1 },
                    10: { 17: 1, 38: 1 },
                    11: { 17: 1, 38: 1 },
                    12: { 17: 1, 38: 1 },
                },
                2025: {
                    1: { 17: 1, 38: 1 },
                    2: { 17: 1, 38: 1 },
                    3: { 17: 1, 38: 1 },
                    4: { 17: 1, 38: 1 },
                    5: { 17: 1, 38: 1 },
                    6: { 17: 1, 38: 1 },
                    7: { 17: 1, 38: 1 },
                    8: { 17: 1, 38: 1 },
                    9: { 17: 1, 38: 1 },
                    10: { 17: 1, 38: 1 },
                    11: { 17: 1, 38: 1 },
                    12: { 17: 1, 38: 1 },
                },
                2026: {
                    1: { 17: 1, 38: 1 },
                    2: { 17: 1, 38: 1 },
                    3: { 17: 1, 38: 1 },
                    4: { 17: 1, 38: 1 },
                    5: { 17: 1, 38: 1 },
                    6: { 17: 1, 38: 1 },
                    7: { 17: 1, 38: 1 },
                    8: { 17: 1, 38: 1 },
                    9: { 17: 1, 38: 1 },
                    10: { 17: 1, 38: 1 },
                    11: { 17: 1, 38: 1 },
                    12: { 17: 1, 38: 1 },
                },
            },
            resetdiasMaximosPorVez: {
                1: 150,
                2: 30,
                7: 93,
                8: 180,
                10: 180,
                12: 15,
                13: 60,
                14: 2,
                15: 93,
                17: 1,
                18: 10,
                19: 93,
                25: 6,
                28: 5,
                38: 1,
                39: 2,
                40: 2,
            },
        };
    },
    methods: {
        openDiagnosticoModal() {
            this.isDiagnosticoModalOpen = true;
        },
        handleDiagnostico(diagnostico) {
            this.diagnostico = diagnostico;
        },
        handleModalClose() {
            console.log('Modal cerrado');
        },
        fetchCaracter() {
            window.axios
                .get('api/caracter')
                .then((res) => {
                    console.log(res.data);
                    this.itemsCaracter = res.data.data.map(
                        (el) => el.descripcion,
                    );
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        fetchCapacitacion() {
            window.axios
                .get(`api/getCapacitacion/${this.licencia.idlicencia}`)
                .then((res) => {
                    const response = res.data.data[0];
                    this.licencia.resolucion = response.resolucion;
                    this.licencia.alcance = response.alcance;
                    this.licencia.caracter = response.caracter;
                    this.licencia.fecha_evento_inicio = new Date(
                        response.fecha_evento_inicio
                            .replace(/T.+/, '')
                            .split('-'),
                    ).toLocaleDateString('es-AR');
                    this.licencia.fecha_evento_final = new Date(
                        response.fecha_evento_final
                            .replace(/T.+/, '')
                            .split('-'),
                    ).toLocaleDateString('es-AR');
                    this.licencia.tipo_evento = response.tipo_evento;
                    this.licencia.evento_lugar = response.evento_lugar;
                    this.licencia.evento_nombre = response.evento_nombre;
                    this.licencia.razon = response.razon;
                })
                .catch((err) => {
                    console.error(err);
                });
        },
        setCapacitacion(evt) {
            this.licencia.tipo_evento = evt.tipo_evento;
            this.licencia.fecha_evento_inicio = new Date(
                evt.fecha_evento_inicio.replace(/T.+/, '').split('-'),
            ).toLocaleDateString('es-AR');
            this.licencia.fecha_evento_final = new Date(
                evt.fecha_evento_final.replace(/T.+/, '').split('-'),
            ).toLocaleDateString('es-AR');
            this.licencia.evento_lugar = evt.evento_lugar;
            this.licencia.evento_nombre = evt.evento_nombre;
            this.licencia.alcance = evt.alcance;
            this.licencia.razon = evt.razon;
            this.licencia.idCapacitacion = evt.idCapacitacion;
            this.dialogCapacitacion = false;
        },
        async getDiasRestanteDiscapacitado(idpersona, idlicencia = 0) {
            await this.$store
                .dispatch('grupo/getDiasRestanteDiscapacitado', {
                    idpersona: idpersona,
                    idtipoLicencia: this.tipoLicencia,
                    idlicencia: idlicencia,
                })
                .then((res) => {
                    for (let row of this.dias) {
                        this.diasAnualesMaximos[row.año][this.tipoLicencia] -=
                            row.dias;
                        console.info(
                            'La cantidad de dias que quedan para esta licencia son: ',
                            this.diasAnualesMaximos[row.año][this.tipoLicencia],
                        );
                    }

                    console.log(
                        `Todo ok en el get dias consumidos de discapacidad`,
                    );
                })
                .catch((err) => {
                    console.error(
                        `Error en el get dias de discapacidad ${err}`,
                    );
                });
        },
        // El dispatch para guardar las modificaciones
        actualizarLicencia(obj) {
            if (obj.dias === -1) {
                this.mensaje = 'No esta respetando los dias maximos permitidos';
                return -1;
            }
            var error = [];
            if (obj.primer_visado === false && obj.segundo_visado === null) {
                obj.dias = 0;
            }
            if (obj.primer_visado === false && obj.segundo_visado === false) {
                obj.dias = 0;
            }
            if (obj.segundo_visado === false) {
                obj.dias = 0;
            }
            this.formatearDiasInsertados();
            if (obj.fecha_efectiva_final) {
                var fecha = obj.fecha_efectiva_final;
            } else if (obj.fecha_interrupcion_inicio) {
                var fecha = obj.fecha_interrupcion_inicio;
            } else {
                var fecha = obj.fecha_pedido_inicio;
            }

            console.log(this.saldosInsertar);
            if (this.licenciasLAO.includes(this.tipoLicencia)) {
                /**
                 * Tratamiento de DLA, proporcional y anticipo
                 */
                var res_trat = this.tratamientoHabilesSinAntiguedad(
                    obj.dias,
                    fecha,
                    [obj.fecha_pedido_inicio, obj.fecha_pedido_final],
                    true,
                );
                if (res_trat < 0) {
                    error.push(11);
                    this.error_flag(error);
                    return -1;
                }
                /**
                 * Tratamiento a LAO
                 *
                 */
                if (this.tipoLicencia === 16) {
                    let res = this.tratamientoLAO(
                        this.antiguedades,
                        fecha,
                        obj.dias,
                    );
                    if (res < 0) {
                        error.push(9);
                        this.error_flag(error);
                        return -1;
                    }
                }
                /**
                 * Si es alguna de las licencias de anual reglamentaria y tiene el 1º visado
                 * se le pone automaticamente el segundo visado
                 */
                if (obj.primer_visado === true && obj.segundo_visado === null) {
                    obj.segundo_visado = true;
                    obj.fecha_efectiva_inicio = obj.fecha_pedido_inicio;
                    obj.fecha_efectiva_final = obj.fecha_pedido_final;
                }
            }
            if (this.licenciasConGoce.includes(this.tipoLicencia)) {
                if (obj.primer_visado === true && obj.segundo_visado === null) {
                    obj.segundo_visado = true;
                    obj.fecha_efectiva_inicio = obj.fecha_pedido_inicio;
                    obj.fecha_efectiva_final = obj.fecha_pedido_final;
                }
            }
            let diagnosticosForm = this.guardarDiagnosticos();
            this.$store
                .dispatch('licencia/updateLicencia', [
                    obj,
                    this.agente,
                    this.saldosInsertar,
                    this.antiguedades,
                    diagnosticosForm,
                ])
                .then(() => {
                    this.volverLicencia();
                });
        },
        //Busca todas las licencias que se necesitan para calcular la cantidad de dias que le quedan
        async buscarLicencias() {
            await this.$store
                .dispatch('licencia/getLicencias', {
                    idagente: this.agente.idagente,
                    tipoLicencia: this.tipoLicencia,
                })
                .then(() => {
                    this.licencias = Array.from(
                        this.$store.getters['licencia/obtenerLicencias'],
                    );
                    this.licencias = _.filter(this.licencias, (lic) => {
                        return (
                            (lic.primer_visado === null ||
                                lic.primer_visado === true) &&
                            (lic.segundo_visado === null ||
                                lic.segundo_visado === true) &&
                            (lic.cuarta_visado === null ||
                                lic.cuarta_visado === true)
                        );
                    });
                    this.totalRowsLicencias =
                        this.$store.getters['licencia/obtenerLicencias'].length;
                    this.buscarLicenciasTotales();
                });
        },
        //Busca todas las licencias que se necesitan para calcular la cantidad de dias que le quedan
        async buscarLicenciasTotales() {
            await this.$store
                .dispatch('licencia/getLicenciasTotales', {
                    idagente: this.agente.idagente,
                })
                .then(() => {
                    this.licencias_totales = Array.from(
                        this.$store.getters['licencia/obtenerLicenciasTotales'],
                    );
                    this.licencias_totales = _.filter(
                        this.licencias_totales,
                        (lic) => {
                            return (
                                (lic.primer_visado === null ||
                                    lic.primer_visado === true) &&
                                (lic.segundo_visado === null ||
                                    lic.segundo_visado === true) &&
                                (lic.cuarta_visado === null ||
                                    lic.cuarta_visado === true)
                            );
                        },
                    );
                    this.totalRowsLicenciasTotales =
                        this.$store.getters[
                            'licencia/obtenerLicenciasTotales'
                        ].length;
                    this.buscarSaldos();
                });
        },
        /**
         * Funcion de busqueda de licencia que se llama
         * solo por el props de idlicencia en la creacion del componente
         *
         * @param idlicencia id de la licencia pasada por props al componente
         */
        async buscarLicencia(idlicencia) {
            await this.$store
                .dispatch('licencia/getLicencia', idlicencia)
                .then(() => {
                    console.log(
                        'listo para pasar la licencia traida, ',
                        Object.entries(this.$store.state.licencia.licencia),
                    );

                    this.licencia = this.$store.state.licencia.licencia;
                    if (this.tipoLicencia === 18 || this.tipoLicencia === 19) {
                        this.fetchCapacitacion();
                    }
                    if (this.licencia.fecha_pedido_inicio) {
                        let aux_fecha_pedido_inicio = moment(
                            this.licencia.fecha_pedido_inicio.slice(0, 10),
                        );
                        aux_fecha_pedido_inicio = new Date(
                            aux_fecha_pedido_inicio.get('year'),
                            aux_fecha_pedido_inicio.get('month'),
                            aux_fecha_pedido_inicio.get('date'),
                        );
                        this.licencia.fecha_pedido_inicio =
                            aux_fecha_pedido_inicio;

                        let aux_fecha_pedido_final = moment(
                            this.licencia.fecha_pedido_final.slice(0, 10),
                        );
                        aux_fecha_pedido_final = new Date(
                            aux_fecha_pedido_final.get('year'),
                            aux_fecha_pedido_final.get('month'),
                            aux_fecha_pedido_final.get('date'),
                        );
                        this.licencia.fecha_pedido_final =
                            aux_fecha_pedido_final;
                    }
                    if (this.licencia.fecha_efectiva_inicio) {
                        let aux_fecha_efectiva_inicio = moment(
                            this.licencia.fecha_efectiva_inicio.slice(0, 10),
                        );
                        aux_fecha_efectiva_inicio = new Date(
                            aux_fecha_efectiva_inicio.get('year'),
                            aux_fecha_efectiva_inicio.get('month'),
                            aux_fecha_efectiva_inicio.get('date'),
                        );
                        this.licencia.fecha_efectiva_inicio =
                            aux_fecha_efectiva_inicio;

                        let aux_fecha_efectiva_final = moment(
                            this.licencia.fecha_efectiva_final.slice(0, 10),
                        );
                        aux_fecha_efectiva_final = new Date(
                            aux_fecha_efectiva_final.get('year'),
                            aux_fecha_efectiva_final.get('month'),
                            aux_fecha_efectiva_final.get('date'),
                        );
                        this.licencia.fecha_efectiva_final =
                            aux_fecha_efectiva_final;
                    }
                    if (this.licencia.fecha_evento_inicio) {
                        let aux_fecha_evento_inicio = moment(
                            this.licencia.fecha_evento_inicio.slice(0, 10),
                        );
                        aux_fecha_evento_inicio = new Date(
                            aux_fecha_evento_inicio.get('year'),
                            aux_fecha_evento_inicio.get('month'),
                            aux_fecha_evento_inicio.get('date'),
                        );
                        this.licencia.fecha_evento_inicio =
                            aux_fecha_evento_inicio;
                        let aux_fecha_evento_final = moment(
                            this.licencia.fecha_evento_final.slice(0, 10),
                        );
                        aux_fecha_evento_final = new Date(
                            aux_fecha_evento_final.get('year'),
                            aux_fecha_evento_final.get('month'),
                            aux_fecha_evento_final.get('date'),
                        );
                        this.licencia.fecha_evento_final =
                            aux_fecha_evento_final;
                    }
                    if (this.licencia.fecha_interrupcion_inicio) {
                        let aux_fecha_interrupcion_inicio = moment(
                            this.licencia.fecha_interrupcion_inicio.slice(
                                0,
                                10,
                            ),
                        );
                        aux_fecha_interrupcion_inicio = new Date(
                            aux_fecha_interrupcion_inicio.get('year'),
                            aux_fecha_interrupcion_inicio.get('month'),
                            aux_fecha_interrupcion_inicio.get('date'),
                        );
                        this.licencia.fecha_interrupcion_inicio =
                            aux_fecha_interrupcion_inicio;
                    }
                });
        },
        // buscar saldos de la licencia
        async buscarSaldos() {
            await this.$store
                .dispatch('licencia/getLicenciasSaldos', {
                    idagente: this.agente.idagente,
                    tipoLicencia: this.tipoLicencia,
                })
                .then(() => {
                    this.saldos = Array.from(
                        this.$store.getters['licencia/obtenerSaldos'],
                    );
                    this.totalRowsSaldos =
                        this.$store.getters['licencia/obtenerSaldos'].length;
                    this.buscarSaldosTotales();
                });
        },
        // buscar todos los saldos
        async buscarSaldosTotales() {
            await this.$store
                .dispatch('licencia/getLicenciasSaldosTotalesHabiles', {
                    idagente: this.agente.idagente,
                })
                .then(() => {
                    this.saldos_totales = Array.from(
                        this.$store.getters['licencia/obtenerSaldosTotales'],
                    );
                    this.totalRowsSaldosTotales =
                        this.$store.getters[
                            'licencia/obtenerSaldosTotales'
                        ].length;
                    this.buscarAntiguedades();
                });
        },
        /**
         * Busca Antiguedades api/antiguedad/getAntiguedades/&idagente
         * idagente: this.$store.state.agente.agente.idagente
         * then => this.antiguedades y resetAntiguedades
         * actualizarMaximos
         */
        async buscarAntiguedades() {
            await this.$store
                .dispatch('antiguedad/getAntiguedadesMenosLicencia', {
                    idagente: this.agente.idagente,
                    idlicencia: this.idlicencia,
                })
                .then(() => {
                    this.antiguedades = Array.from(this.obtenerAntiguedades);
                    console.log(
                        'esta son las antiguedades traidas ',
                        this.antiguedades,
                    );
                    this.resetAntiguedades = JSON.parse(
                        JSON.stringify(this.antiguedades),
                    );
                    this.actualizarMaximos();
                });
        },
        setearDiasMaximosFamiliares() {
            let personas = this.$store.state.licencia.personas;
            if (this.tipoLicencia == 11) {
                this.diasAnualesMaximos[moment().year()][11] =
                    this.cantidadDiasPorFamiliares();
                this.diasAnualesMaximos[moment().year() + 1][11] =
                    this.cantidadDiasPorFamiliares();
                this.diasAnualesMaximos[moment().year() - 1][11] =
                    this.cantidadDiasPorFamiliares();
            }
            // TODO mover al back
            if (
                this.tipoLicencia == 21 ||
                this.tipoLicencia == 22 ||
                this.tipoLicencia == 35 ||
                this.tipoLicencia == 36
            ) {
                personas = personas.filter((element) => {
                    return element.discapacidad;
                });
                if (this.licencia.idpersona > 0) {
                    let personaSeleccionada = personas.filter((element) => {
                        return element.idpersona === this.licencia.idpersona;
                    });
                    const reducer = (accumulator, currentValue) =>
                        accumulator + currentValue.dias;
                    if (this.tipoLicencia === 22) {
                        this.diasAnualesMaximos[moment().year()][22] =
                            180 -
                            personaSeleccionada
                                .map((element) => {
                                    if (
                                        moment().year() == element.año &&
                                        element.idtipolicencia == 22
                                    ) {
                                        return {
                                            dias: element.dias,
                                        };
                                    } else {
                                        return {
                                            dias: 0,
                                        };
                                    }
                                })
                                .reduce(reducer, 0);

                        this.diasAnualesMaximos[moment().year() + 1][22] =
                            180 -
                            personaSeleccionada
                                .map((element) => {
                                    if (
                                        moment().year() + 1 === element.año &&
                                        element.idtipolicencia === 22
                                    ) {
                                        return {
                                            dias: element.dias,
                                        };
                                    } else {
                                        return {
                                            dias: 0,
                                        };
                                    }
                                })
                                .reduce(reducer, 0);
                        this.diasAnualesMaximos[moment().year() - 1][22] =
                            180 -
                            personaSeleccionada
                                .map((element) => {
                                    if (
                                        moment().year() - 1 === element.año &&
                                        element.idtipolicencia === 22
                                    ) {
                                        return {
                                            dias: element.dias,
                                        };
                                    } else {
                                        return {
                                            dias: 0,
                                        };
                                    }
                                })
                                .reduce(reducer, 0);
                    }
                    if (this.tipoLicencia === 36) {
                        this.diasAnualesMaximos[moment().year()][36] =
                            180 -
                            personaSeleccionada
                                .map((element) => {
                                    if (
                                        moment().year() == element.año &&
                                        element.idtipolicencia == 36
                                    ) {
                                        return {
                                            dias: element.dias,
                                        };
                                    } else {
                                        return {
                                            dias: 0,
                                        };
                                    }
                                })
                                .reduce(reducer, 0);

                        this.diasAnualesMaximos[moment().year() + 1][36] =
                            180 -
                            personaSeleccionada
                                .map((element) => {
                                    if (
                                        moment().year() + 1 === element.año &&
                                        element.idtipolicencia === 36
                                    ) {
                                        return {
                                            dias: element.dias,
                                        };
                                    } else {
                                        return {
                                            dias: 0,
                                        };
                                    }
                                })
                                .reduce(reducer, 0);
                        this.diasAnualesMaximos[moment().year() - 1][36] =
                            180 -
                            personaSeleccionada
                                .map((element) => {
                                    if (
                                        moment().year() - 1 === element.año &&
                                        element.idtipolicencia === 36
                                    ) {
                                        return {
                                            dias: element.dias,
                                        };
                                    } else {
                                        return {
                                            dias: 0,
                                        };
                                    }
                                })
                                .reduce(reducer, 0);
                    }
                    if (this.tipoLicencia === 21) {
                        this.diasAnualesMaximos[moment().year()][21] =
                            90 -
                            personaSeleccionada
                                .map((element) => {
                                    if (
                                        moment().year() === element.año &&
                                        element.idtipolicencia === 21
                                    ) {
                                        return {
                                            dias: element.dias,
                                        };
                                    } else {
                                        return {
                                            dias: 0,
                                        };
                                    }
                                })
                                .reduce(reducer, 0);
                        this.diasAnualesMaximos[moment().year() + 1][21] =
                            90 -
                            personaSeleccionada
                                .map((element) => {
                                    if (
                                        moment().year() + 1 === element.año &&
                                        element.idtipolicencia === 21
                                    ) {
                                        return {
                                            dias: element.dias,
                                        };
                                    } else {
                                        return {
                                            dias: 0,
                                        };
                                    }
                                })
                                .reduce(reducer, 0);
                        this.diasAnualesMaximos[moment().year() - 1][21] =
                            90 -
                            personaSeleccionada
                                .map((element) => {
                                    if (
                                        moment().year() - 1 === element.año &&
                                        element.idtipolicencia === 21
                                    ) {
                                        return {
                                            dias: element.dias,
                                        };
                                    } else {
                                        return {
                                            dias: 0,
                                        };
                                    }
                                })
                                .reduce(reducer, 0);
                    }
                    if (this.tipoLicencia === 35) {
                        this.diasAnualesMaximos[moment().year()][35] =
                            90 -
                            personaSeleccionada
                                .map((element) => {
                                    if (
                                        moment().year() === element.año &&
                                        element.idtipolicencia === 35
                                    ) {
                                        return {
                                            dias: element.dias,
                                        };
                                    } else {
                                        return {
                                            dias: 0,
                                        };
                                    }
                                })
                                .reduce(reducer, 0);
                        this.diasAnualesMaximos[moment().year() + 1][35] =
                            90 -
                            personaSeleccionada
                                .map((element) => {
                                    if (
                                        moment().year() + 1 === element.año &&
                                        element.idtipolicencia === 35
                                    ) {
                                        return {
                                            dias: element.dias,
                                        };
                                    } else {
                                        return {
                                            dias: 0,
                                        };
                                    }
                                })
                                .reduce(reducer, 0);
                        this.diasAnualesMaximos[moment().year() - 1][35] =
                            90 -
                            personaSeleccionada
                                .map((element) => {
                                    if (
                                        moment().year() - 1 === element.año &&
                                        element.idtipolicencia === 35
                                    ) {
                                        return {
                                            dias: element.dias,
                                        };
                                    } else {
                                        return {
                                            dias: 0,
                                        };
                                    }
                                })
                                .reduce(reducer, 0);
                    }
                }
            }
        },

        // Busca las personas declaradas como grupo familiar excepto para la licencia de familiar discapacitado que tiene en cuenta si alguien ya saco licencia por este ultimo
        async buscarPersonasActivas(discapacitado = false) {
            console.log('buscarPersonasActivas');
            await this.$store
                .dispatch('licencia/getPersonasActivas', [
                    this.tipoLicencia,
                    this.agente.idagente,
                ])
                .then(() => {
                    this.setearDiasMaximosFamiliares();
                    this.personas = this.$store.getters['licencia/personas'];
                    if (
                        this.tipoLicencia === 21 ||
                        this.tipoLicencia === 22 ||
                        this.tipoLicencia === 35 ||
                        this.tipoLicencia === 36
                    ) {
                        this.personas = this.personas.filter((element) => {
                            return element.discapacidad === true;
                        });
                    }
                    console.log('Las personas traidas son:');
                    console.log(this.personas);
                    this.personas = this.personas.map((element) => {
                        return {
                            text: element.nombre + ' ' + element.apellido,
                            value: element.idpersona,
                        };
                    });
                    console.log('saliendo');
                    console.info(this.personas);
                })
                .catch((err) => {
                    console.error(err);
                    console.error('Error en buscar personas activas');
                });
        },
        /**
         * Cantidad Dias por Familiares devuelve la cantidad de dias que le corresponden por la cantidad
         * de familaires declarados a traves de this.personas.length
         * cantidad = ((cantidad - 4) * 2) + 10 if cantidad > 4
         * cantidad = 10 else
         */
        cantidadDiasPorFamiliares() {
            var cantidad = this.$store.state.licencia.personas.length;
            if (cantidad > 4) {
                cantidad = (cantidad - 4) * 2;
            } else {
                return 10;
            }
            return cantidad + 10;
        },
        // Saca el dia de guardia del agente
        async getDiasPosibles() {
            await this.$store
                .dispatch('licencia/getDiasPosibles', this.agente.idagente)
                .then(() => {
                    this.dia = this.$store.state.licencia.dia;
                })
                .catch();
        },
        // Llena el vector de los feriados
        async getFeriados() {
            await this.$store
                .dispatch('licencia/getFeriados')
                .then(() => {
                    this.feriados = this.$store.state.licencia.feriados;
                    this.attributes[0].dates = this.feriados;
                })
                .catch();
        },

        async guardarDiagnosticos() {
            const formData = new FormData();

            // Agregar diagnóstico al payload si existe
            if (this.diagnostico) {
                formData.append(
                    'diagnostico[codigo]',
                    this.diagnostico.codigo_icd10,
                );
                formData.append(
                    'diagnostico[descripcion]',
                    this.diagnostico.descripcion,
                );
                if (this.diagnostico.archivo) {
                    formData.append(
                        'diagnostico[archivo]',
                        this.diagnostico.archivo,
                    );
                }
                // formData.append(
                //     'diagnostico[plataforma][url]',
                //     this.diagnostico.plataforma.url || '',
                // );
                // formData.append(
                //     'diagnostico[plataforma][codigo]',
                //     this.diagnostico.plataforma.codigo || '',
                // );
                // formData.append(
                //     'diagnostico[plataforma][usuario]',
                //     this.diagnostico.plataforma.usuario || '',
                // );
                // formData.append(
                //     'diagnostico[plataforma][contrasena]',
                //     this.diagnostico.plataforma.contrasena || '',
                // );

                // Agregar observaciones
                this.diagnostico.observaciones.forEach((observacion, index) => {
                    formData.append(
                        `diagnostico[observaciones][${index}][descripcion]`,
                        observacion.descripcion,
                    );
                    formData.append(
                        `diagnostico[observaciones][${index}][fecha]`,
                        observacion.fecha,
                    );
                    formData.append(
                        `diagnostico[observaciones][${index}][tipo]`,
                        observacion.tipo,
                    );
                    formData.append(
                        `diagnostico[observaciones][${index}][valor]`,
                        observacion.valor,
                    );
                    formData.append(
                        `diagnostico[observaciones][${index}][unidad]`,
                        observacion.unidad,
                    );
                    if (observacion.archivo) {
                        formData.append(
                            `diagnostico[observaciones][${index}][archivo]`,
                            observacion.archivo,
                        );
                    }
                    formData.append(
                        `diagnostico[observaciones][${index}][sitio_web]`,
                        observacion.sitio_web || '',
                    );
                    formData.append(
                        `diagnostico[observaciones][${index}][codigo]`,
                        observacion.codigo || '',
                    );
                    formData.append(
                        `diagnostico[observaciones][${index}][usuario]`,
                        observacion.usuario || '',
                    );
                    formData.append(
                        `diagnostico[observaciones][${index}][contrasena]`,
                        observacion.contrasena || '',
                    );
                });
            }

            return formData;
        },
        // El dispatch para crear la licencia
        async guardarLicencia(obj) {
            this.formatearDiasInsertados();
            let diagnosticosForm = this.guardarDiagnosticos();
            await this.$store
                .dispatch('licencia/postLicencia', [
                    obj,
                    this.agente,
                    this.saldosInsertar,
                    this.antiguedades,
                    diagnosticosForm,
                ])
                .then(() => {
                    this.volverLicencia();
                })
                .catch((err) => {
                    console.log(err);
                });
            this.deshabilitarBoton = false;
        },
        // Busca si es que hay sanciones de dos años atras
        async existSancion() {
            await this.$store
                .dispatch('sancion/existSancion', this.agente.idagente)
                .then(() => {
                    this.existeSanciones =
                        this.$store.getters['sancion/existe'];
                });
        },
        /**
         * Contiene un switch para this.error
         */
        error_flag(arr) {
            for (var value of arr) {
                switch (value) {
                    case 1:
                        this.mensaje +=
                            '\n Se solapan las Fechas con una licencia prexistente';
                        break;
                    case 2:
                        this.mensaje +=
                            '\n No es mayor que 7 los dias pedidos para la licencia';
                        break;
                    case 3:
                        this.mensaje += '\n No selecciono a una persona';
                        break;
                    case 4:
                        this.mensaje +=
                            '\n La fecha de pedido final parece ser anterior a la fecha de inicio';
                        break;
                    case 5:
                        this.mensaje += '\n ERROR! ';
                        break;
                    case 6:
                        this.mensaje +=
                            '\n Ha ocurrido un error con las fechas seleccionada';
                        break;
                    case 7:
                        this.mensaje += '\n No tiene el primer visado';
                        break;
                    case 8:
                        this.mensaje +=
                            ' \n La Fecha no es anterior al 1º de Julio';
                        break;
                    case 9:
                        this.mensaje +=
                            ' \n La Fecha es anterior al 31 de Diciembre y no tiene saldo';
                        break;
                    case 10:
                        this.mensaje +=
                            ' \n La Fecha del proporcional es anterior al 1º de Julio';
                        break;
                    case 11:
                        this.mensaje +=
                            ' \n No ha pasado  la validacion de restriccion del tipo de licencias';
                        break;
                    case 12:
                        this.mensaje +=
                            ' \n Licencia solo para personal con identidad de genero femenina';
                        break;
                    default:
                        this.mensaje += '\n Multiples errores';
                        break;
                }
            }
            if (arr.length > 0) {
                this.resetearValores();
                this.deshabilitarBoton = false;
                return -1;
            }
            return 0;
        },
        /**
         * Esta funcion es la ejecutada por el boton de modificar
         * setea el todos los parametros de licencia a un formulario
         * luego revisa que fechas estan llenas y que visados para comprobar el error
         * tambien tiene los tratamientos de LAO y otros habiles
         * como asi tambien la simulacion, el formateo de saldos se ejecuta en actualizarLicencia
         */
        onUpdate(evt) {
            evt.preventDefault();
            var error = [];
            let form = {
                idlicencia: this.idlicencia,
                fecha_pedido_inicio: this.licencia.fecha_pedido_inicio,
                fecha_pedido_final: this.licencia.fecha_pedido_final,
                fecha_efectiva_inicio: this.licencia.fecha_efectiva_inicio,
                fecha_efectiva_final: this.licencia.fecha_efectiva_final,
                fecha_evento_inicio: this.licencia.fecha_evento_inicio,
                fecha_evento_final: this.licencia.fecha_evento_final,
                fecha_interrupcion_inicio:
                    this.licencia.fecha_interrupcion_inicio,
                observacion_primera: this.licencia.observacion_primera,
                observacion_segunda: this.licencia.observacion_segunda,
                observacion_tercera: this.licencia.observacion_tercera,
                observacion_cuarta: this.licencia.observacion_cuarta,
                primer_visado: this.licencia.primer_visado,
                segundo_visado: this.licencia.segundo_visado,
                tercera_visado: this.licencia.tercera_visado,
                cuarta_visado: this.licencia.cuarta_visado,
                idtipoLicencia: this.tipoLicencia,
                idpersona: this.licencia.idpersona,
                evento_nombre: this.licencia.evento_nombre,
                evento_lugar: this.licencia.evento_lugar,
                razon: this.licencia.razon,
                alcance: this.licencia.alcance,
                caracter: this.licencia.caracter,
                resolucion: this.licencia.resolucion,
                tipo_evento: this.licencia.tipo_evento,
                parentescoFallecido: this.licencia.parentescoFallecido,
                idCapacitacion: this.licencia.idCapacitacion,
                dias: 0,
            };
            console.log('form');
            console.log(form);
            if (form.primer_visado === 'true') {
                form.primer_visado = true;
            } else if (form.primer_visado === 'false') {
                form.primer_visado = false;
            }

            if (form.segundo_visado === 'true') {
                form.segundo_visado = true;
            } else if (form.segundo_visado === 'false') {
                form.segundo_visado = false;
            }

            if (form.cuarta_visado === 'true') {
                form.cuarta_visado = true;
            } else if (form.cuarta_visado === 'false') {
                form.cuarta_visado = false;
            }
            console.log('fijate la fecha siguiente');
            console.log(this.licencia.fecha_interrupcion_inicio);
            if (
                this.licencia.fecha_interrupcion_inicio === null &&
                form.cuarta_visado === true
            ) {
                error.push(6);
            }
            if (
                this.licencia.fecha_efectiva_final === null &&
                form.segundo_visado === true
            ) {
                error.push(6);
            }
            if (form.primer_visado === null) {
                form.primer_visado = false;
            }
            if (form.segundo_visado === null) {
                form.segundo_visado = null;
            }

            console.log('El formulario que se desea actualizar ', form);

            this.mensaje = '';

            if (form.dias < 0) error.push(6);

            if (
                (form.primer_visado === false || form.primer_visado === null) &&
                form.segundo_visado === true
            )
                error.push(7);
            if (
                (form.primer_visado === false || form.primer_visado === null) &&
                form.cuarta_visado === true
            )
                error.push(7);
            if (error.length > 0) {
                this.error_flag(error);
                return -1;
            }
            // Calculo de dias para 4.1 y 4.2
            if (
                this.licenciasRequierenUnaPersonaACargo.includes(
                    this.tipoLicencia,
                )
            ) {
                if (this.licencia.idpersona) {
                    form.idpersona = this.licencia.idpersona;
                    this.getDiasRestanteDiscapacitado(
                        form.idpersona,
                        form.idlicencia,
                    );
                } else {
                    error.push(3);
                }
            }
            // Fin del calculo
            if (form.fecha_interrupcion_inicio && form.cuarta_visado === true) {
                form.fecha_efectiva_inicio = new Date(
                    this.licencia.fecha_efectiva_inicio.getFullYear(),
                    this.licencia.fecha_efectiva_inicio.getMonth(),
                    this.licencia.fecha_efectiva_inicio.getDate(),
                );
                form.fecha_interrupcion_inicio = new Date(
                    this.licencia.fecha_interrupcion_inicio.getFullYear(),
                    this.licencia.fecha_interrupcion_inicio.getMonth(),
                    this.licencia.fecha_interrupcion_inicio.getDate(),
                );
                form.dias = this.obtenerSimulacionSaldosDias(
                    this.licencia.fecha_efectiva_inicio,
                    this.licencia.fecha_interrupcion_inicio,
                    this.tipoLicencia,
                );
            } else if (
                this.licencia.fecha_efectiva_final &&
                ((form.segundo_visado == true && form.primer_visado == true) ||
                    (form.primer_visado == true && form.segundo_visado == null))
            ) {
                form.fecha_efectiva_inicio = new Date(
                    this.licencia.fecha_efectiva_inicio.getFullYear(),
                    this.licencia.fecha_efectiva_inicio.getMonth(),
                    this.licencia.fecha_efectiva_inicio.getDate(),
                );
                form.fecha_efectiva_final = new Date(
                    this.licencia.fecha_efectiva_final.getFullYear(),
                    this.licencia.fecha_efectiva_final.getMonth(),
                    this.licencia.fecha_efectiva_final.getDate(),
                );

                console.log('ambos visados en true');
                console.log('ambos visados en true ', this.saldosInsertar);
                console.log('ambos visados en true ', this.diasAnualesMaximos);
                form.dias = this.obtenerSimulacionSaldosDias(
                    this.licencia.fecha_efectiva_inicio,
                    this.licencia.fecha_efectiva_final,
                    this.tipoLicencia,
                );
                console.log('dias del obtener Simulacion ', form.dias);
                console.log('ambos visados en true');
                console.log('ambos visados en true ', this.saldosInsertar);
                console.log('ambos visados en true ', this.diasAnualesMaximos);
            } else {
                form.fecha_pedido_inicio = new Date(
                    this.licencia.fecha_pedido_inicio.getFullYear(),
                    this.licencia.fecha_pedido_inicio.getMonth(),
                    this.licencia.fecha_pedido_inicio.getDate(),
                );
                form.fecha_pedido_final = new Date(
                    this.licencia.fecha_pedido_final.getFullYear(),
                    this.licencia.fecha_pedido_final.getMonth(),
                    this.licencia.fecha_pedido_final.getDate(),
                );
                console.log('no se modifico nada');
                form.dias = this.obtenerSimulacionSaldosDias(
                    this.licencia.fecha_pedido_inicio,
                    this.licencia.fecha_pedido_final,
                    this.tipoLicencia,
                );

                console.log(
                    'no se modifico nada con resultado de simulaicon ',
                    form.dias,
                );
                if (form.dias == -1) {
                    form.dias = moment(form.fecha_pedido_final).diff(
                        moment(form.fecha_pedido_inicio),
                        'days',
                    );
                }
            }

            if (form.primer_visado === false || form.segundo_visado === false) {
                this.actualizarLicencia(form);
                return 0;
            } else if (form.primer_visado == true) {
                this.actualizarLicencia(form);
                return 0;
            }
            if (form.primer_visado === true && form.cuarta_visado === true) {
                if (
                    form.fecha_interrupcion_inicio &&
                    moment(form.fecha_efectiva_inicio).isSameOrBefore(
                        moment(form.fecha_interrupcion_inicio),
                    )
                ) {
                    this.actualizarLicencia(form);
                    return 0;
                }
            } else {
                this.mensaje +=
                    '\n No selecciono una de las fechas o la fecha de inicio es mayor a la del final';
                this.resetearValores();
                return -1;
            }

            return 0;
        },
        /**
         * Devuelve el total de la diferencia entre Disponible y Pedido de cada año
         * pero si la pide antes del 1º de Diciembre se tiene que excluir de la suma
         * al año corriente porque todavia no le ha sido acreditado ese disponible
         * y si pidio con anterioridad un anticipo la antiguedad ya ha sido insertada
         *
         */
        tratamientoLAO(antiguedades, fecha_pedido_inicio, dias) {
            const res = this.tratamientoOrdinario(
                dias,
                fecha_pedido_inicio,
                16,
                antiguedades,
            );
            return res;
        },
        tratamientoOrdinario(
            dias,
            fecha,
            tipoLicencia,
            antiguedades,
            visado = false,
        ) {
            console.log('tratamiento ordinario entrando');
            const anioActual =
                new Date(fecha).getFullYear() === new Date().getFullYear();
            if (
                new Date(fecha) < new Date(new Date().getFullYear(), 0, 1) && // Compara si la fecha es anterior al inicio del año actual
                this.role_name !== 'gerencia'
            ) {
                //proceso de gerencia
                this.mensaje = 'habilidad de gerencia no permitida';
                console.error('habilidad de gerencia no permitida');
                return -1;
            }
            let auxiliar = _.cloneDeep(antiguedades);
            auxiliar = _.orderBy(auxiliar, 'año', 'asc');
            if (tipoLicencia === 16) {
                const añoActual = new Date().getFullYear();
                const disponiblesAñoActual = auxiliar.reduce((prev, cur) => {
                    if (cur.año === añoActual && cur.vigente === true) {
                        prev += parseInt(cur.disponible) - parseInt(cur.pedido);
                    }
                    return prev;
                }, 0);
                const sobrantes = auxiliar.reduce((prev, cur) => {
                    if (cur.año !== añoActual && cur.vigente === true) {
                        prev += parseInt(cur.disponible) - parseInt(cur.pedido);
                    }
                    return prev;
                }, 0);
                const añoReferencia = new Date(fecha).getFullYear() - 2;
                const sobrantesPreviosDosAños = auxiliar.reduce((prev, cur) => {
                    if (cur.año > añoReferencia && cur.vigente === true) {
                        prev += parseInt(cur.disponible) - parseInt(cur.pedido);
                    }
                    return prev;
                }, 0);
                console.log('sobrantes: ', sobrantes);
                console.log('disponible actual: ', disponiblesAñoActual);
                const fechaInicioAño = new Date(new Date().getFullYear(), 0, 1); // 1 de enero del año actual
                const fechaComparar = new Date(fecha);
                if (
                    fechaComparar >= fechaInicioAño || // Comparar si la fecha es igual o posterior al inicio del año actual
                    this.role_name === 'gerencia'
                ) {
                    if (anioActual) {
                        console.log('anio actual');
                        if (dias <= sobrantes) {
                            //registra licencia
                            let aux = dias;
                            for (var index in auxiliar) {
                                let saldo =
                                    auxiliar[index].disponible -
                                    auxiliar[index].pedido;
                                if (saldo - aux >= 0) {
                                    var fila = {
                                        idlicencia: null,
                                        idagente: this.agente.idagente,
                                        idtipoLicencia: this.tipoLicencia,
                                        año: auxiliar[index].año,
                                        mes: null,
                                        dias: aux,
                                        saldoMensual: null,
                                        saldoAnual: null,
                                    };
                                    aux = 0;
                                } else {
                                    var fila = {
                                        idlicencia: null,
                                        idagente: this.agente.idagente,
                                        idtipoLicencia: this.tipoLicencia,
                                        año: auxiliar[index].año,
                                        mes: null,
                                        dias: saldo,
                                        saldoMensual: null,
                                        saldoAnual: null,
                                    };
                                    aux -= saldo;
                                }
                                this.saldosInsertar.push(fila);
                            }
                            return 0;
                        }
                        console.log(
                            'disponibles año acutal:',
                            disponiblesAñoActual,
                        );
                        if (dias <= sobrantes + disponiblesAñoActual) {
                            if (
                                moment(fecha).isSameOrAfter({
                                    year: moment().year(),
                                    month: 11,
                                }) ||
                                this.role_name === 'gerencia'
                            ) {
                                //registra la licencia
                                let aux = dias;
                                for (var index in auxiliar) {
                                    let saldo =
                                        auxiliar[index].disponible -
                                        auxiliar[index].pedido;
                                    if (saldo - aux >= 0) {
                                        var fila = {
                                            idlicencia: null,
                                            idagente: this.agente.idagente,
                                            idtipoLicencia: this.tipoLicencia,
                                            año: auxiliar[index].año,
                                            mes: null,
                                            dias: aux,
                                            saldoMensual: null,
                                            saldoAnual: null,
                                        };
                                        aux = 0;
                                    } else {
                                        var fila = {
                                            idlicencia: null,
                                            idagente: this.agente.idagente,
                                            idtipoLicencia: this.tipoLicencia,
                                            año: auxiliar[index].año,
                                            mes: null,
                                            dias: saldo,
                                            saldoMensual: null,
                                            saldoAnual: null,
                                        };
                                        aux -= saldo;
                                    }
                                    this.saldosInsertar.push(fila);
                                }
                                return 0;
                            }
                            if (usuario === gerencia) {
                                // proceso de gerencia
                                this.mensaje =
                                    'habilidad de gerencia no permitida';
                                console.error(
                                    'habilidad de gerencia no permitida',
                                );
                                return -1;
                            } else {
                                this.mensaje =
                                    'No puede tomar licencia de este tipo, probable anticipo';
                                console.error(
                                    'No puede tomar licencia de este tipo, probable anticipo',
                                );
                                return -1;
                            }
                        } else {
                            this.mensaje =
                                'No puede tomar licencia de este tipo, probable anticipo';
                            console.error(
                                'No puede tomar licencia de este tipo, probable anticipo',
                            );
                            return -1;
                        }
                    } else {
                        console.log('no es anio actual');
                        const hoy = new Date();
                        const fechaComparar = new Date(fecha);
                        if (
                            dias <= sobrantesPreviosDosAños &&
                            fechaComparar > hoy
                        ) {
                            //registra licencia
                            let aux = dias;
                            for (let element of auxiliar.filter((el) => {
                                return el.año >= fecha.getUTCFullYear() - 2;
                            })) {
                                let saldo = element.disponible - element.pedido;
                                if (saldo - aux >= 0) {
                                    var fila = {
                                        idlicencia: null,
                                        idagente: this.agente.idagente,
                                        idtipoLicencia: this.tipoLicencia,
                                        año: element.año,
                                        mes: null,
                                        dias: aux,
                                        saldoMensual: null,
                                        saldoAnual: null,
                                    };
                                    aux = 0;
                                } else {
                                    var fila = {
                                        idlicencia: null,
                                        idagente: this.agente.idagente,
                                        idtipoLicencia: this.tipoLicencia,
                                        año: element.año,
                                        mes: null,
                                        dias: saldo,
                                        saldoMensual: null,
                                        saldoAnual: null,
                                    };
                                    aux -= saldo;
                                }
                                this.saldosInsertar.push(fila);
                            }
                            if (aux === 0) {
                                return 0;
                            } else {
                                this.mensaje =
                                    'No puede tomar licencia de este tipo, probable anticipo';
                                console.error(
                                    'No puede tomar licencia de este tipo, probable anticipo',
                                );
                                return -1;
                            }
                        }
                        console.log('gerencia mode');
                        console.log(
                            'dias para restar: ',
                            sobrantes + disponiblesAñoActual,
                        );
                        console.log('auxiliar para gastar: ', auxiliar);
                        if (dias <= sobrantes + disponiblesAñoActual) {
                            const fechaComparar = new Date(fecha);
                            const inicioDiciembre = new Date(
                                new Date().getFullYear(),
                                11,
                                1,
                            );
                            if (
                                fechaComparar >= inicioDiciembre || // Verifica si la fecha es igual o posterior al 1 de diciembre
                                this.role_name === 'gerencia'
                            ) {
                                //registra la licencia
                                console.log('gerencia mode');
                                console.log(
                                    'dias para restar: ',
                                    sobrantes + disponiblesAñoActual,
                                );
                                console.log('auxiliar para gastar: ', auxiliar);
                                let aux = dias;
                                for (var index in auxiliar) {
                                    console.log('auxiliar');
                                    console.log(auxiliar);
                                    console.log('indice: ', index);
                                    let saldo =
                                        auxiliar[index].disponible -
                                        auxiliar[index].pedido;
                                    console.log('saldo del mes: ', saldo);
                                    if (saldo - aux >= 0) {
                                        console.log(
                                            'calculo si el saldo es menos los dias son mayor a 0',
                                        );
                                        console.log(saldo - aux);
                                        var fila = {
                                            idlicencia: null,
                                            idagente: this.agente.idagente,
                                            idtipoLicencia: this.tipoLicencia,
                                            año: auxiliar[index].año,
                                            mes: null,
                                            dias: aux,
                                            saldoMensual: null,
                                            saldoAnual: null,
                                        };
                                        aux = 0;
                                    } else {
                                        var fila = {
                                            idlicencia: null,
                                            idagente: this.agente.idagente,
                                            idtipoLicencia: this.tipoLicencia,
                                            año: auxiliar[index].año,
                                            mes: null,
                                            dias: saldo,
                                            saldoMensual: null,
                                            saldoAnual: null,
                                        };
                                        aux -= saldo;
                                    }
                                    this.saldosInsertar.push(fila);
                                }
                                console.log(
                                    'saldo completo:',
                                    this.saldosInsertar,
                                );
                                return 0;
                            }
                            if (usuario === gerencia) {
                                // proceso de gerencia
                                this.mensaje =
                                    'habilidad de gerencia no permitida';
                                console.error(
                                    'habilidad de gerencia no permitida',
                                );
                                return -1;
                            } else {
                                this.mensaje =
                                    'No puede tomar licencia de este tipo, probable anticipo';
                                console.error(
                                    'No puede tomar licencia de este tipo, probable anticipo',
                                );
                                return -1;
                            }
                        } else {
                            this.mensaje =
                                'No puede tomar licencia de este tipo, probable anticipo';
                            console.error(
                                'No puede tomar licencia de este tipo, probable anticipo',
                            );
                            return -1;
                        }
                    }
                }
            }
        },
        tratamientoAnticipo(
            dias,
            fechas,
            tpoLicencia,
            antiguedades,
            visado = false,
        ) {
            console.log('tratamientoAnticipo');

            // Variables comunes para evitar cálculos repetidos
            const currentYear = moment().year();

            // Validar parámetros iniciales
            if (!fechas || !fechas[0]) {
                this.mensaje = 'Fechas inválidas.';
                console.error(this.mensaje);
                return -1;
            }
            if (!antiguedades || antiguedades.length === 0) {
                this.mensaje = 'Antigüedades inválidas.';
                console.error(this.mensaje);
                return -1;
            }

            // Calcular sobrantes de años anteriores
            const sobrantes = antiguedades.reduce((acc, antiguedad) => {
                if (antiguedad.año !== currentYear && antiguedad.vigente) {
                    return (
                        acc +
                        (parseInt(antiguedad.disponible) -
                            parseInt(antiguedad.pedido))
                    );
                }
                return acc;
            }, 0);

            if (sobrantes > 0) {
                this.mensaje = `No puede tomar este tipo de Licencia, debe solicitar ordinaria. Tiene un saldo sobrante correspondiente a años anteriores vigentes de ${sobrantes} días.`;
                console.error(this.mensaje);
                return -1;
            }

            // Validar fechas dentro del rango permitido
            const fechaInicio = moment({ year: currentYear, month: 6 }); // 1 de Julio
            const fechaFin = moment({ year: currentYear, month: 10, date: 30 }); // 30 de Noviembre
            if (
                moment(fechas[0]).isBefore(fechaInicio) ||
                moment(fechas[0]).isAfter(fechaFin)
            ) {
                this.mensaje =
                    'Debe ser sacada entre el primer día de Julio y el último día de Noviembre del corriente año.';
                console.error(this.mensaje);
                return -1;
            }

            // Obtener datos del año actual
            const antiguedadActual = antiguedades.find(
                (a) => a.año === currentYear && a.vigente,
            );
            if (!antiguedadActual) {
                this.mensaje =
                    'No hay antigüedad disponible para el año actual.';
                console.error(this.mensaje);
                return -1;
            }

            const disponiblesAñoActual = {
                disponible: parseInt(antiguedadActual.disponible),
                pedido: parseInt(antiguedadActual.pedido),
            };

            // Calcular días de licencias sumadas
            const licenciasSumadas = this.licencias_totales.reduce(
                (acc, licencia) => {
                    if (
                        (licencia.tipoLicencia === 17 ||
                            licencia.tipoLicencia === 27) &&
                        licencia.primer_visado !== false &&
                        licencia.segundo_visado !== false &&
                        licencia.cuarta_visado !== true
                    ) {
                        return acc + licencia.dias;
                    }
                    return acc;
                },
                0,
            );

            // Validar si el anticipo es posible
            if (
                dias + licenciasSumadas <=
                disponiblesAñoActual.disponible / 2
            ) {
                const fila = {
                    idlicencia: null,
                    idagente: this.agente.idagente,
                    idtipoLicencia: this.tipoLicencia,
                    año: antiguedadActual.año,
                    mes: null,
                    dias: dias,
                    saldoMensual: null,
                    saldoAnual: null,
                };
                this.saldosInsertar.push(fila);
                return 0;
            }

            // Caso de saldo insuficiente
            this.mensaje =
                'No puede tomar este tipo de Licencia, sin saldo para anticipo.';
            console.error(this.mensaje);
            return -1;
        },
        tratamientoDLA(
            dias,
            fechas,
            tipoLicencia,
            antiguedades,
            visado = false,
        ) {
            console.log('tratamiento dla');
            let valido = false;
            if (visado) {
                const otraLicenciaEnCurso = this.licencias.filter((el) => {
                    return (
                        el.fecha_pedido_inicio &&
                        el.primer_visado !== false &&
                        el.segundo_visado !== false &&
                        el.cuarta_visado === undefined
                    );
                });
                console.log(otraLicenciaEnCurso);
                console.log('visado de DLA');
                console.log(this.licencias);
                return 0;
            }
            if (dias > 1) {
                new Error('No puede tomar este tipo de licencias');
                console.error('No puede tomar este tipo de licencias');
                this.mensaje = 'Solo valido para un dia';
                return -1;
            }
            if (
                !moment(fechas[0]).isBetween(
                    moment().startOf('year'),
                    moment().endOf('year'),
                )
            ) {
                new Error('No puede tomar este tipo de licencias');
                console.error(
                    'No puede tomar este tipo de licencias, solo valido para año en curso',
                );
                this.mensaje = 'Solo valido para año en curso';
                return -1;
            }

            if (tipoLicencia === 17) {
                const auxiliar = _.cloneDeep(antiguedades);
                const sobrantes = auxiliar.reduce((prev, cur) => {
                    if (cur.año === moment().year() && cur.vigente === true) {
                        return (prev =
                            prev +
                            (parseInt(cur.disponible) - parseInt(cur.pedido)));
                    }
                    return prev;
                }, 0);
                // hardcodeado el tipolicencia
                const diasSacadosEnElMes = this.licencias_totales.reduce(
                    (prev, cur) => {
                        if (
                            cur.idtipoLicencia === 17 &&
                            moment(cur.fecha_pedido_inicio).year() ===
                                moment(fechas[0]).year() &&
                            cur.cuarta_visado === null &&
                            cur.segundo_visado !== false &&
                            cur.primer_visado !== false &&
                            moment(cur.fecha_pedido_inicio).month() ===
                                moment(fechas[0]).month()
                        ) {
                            return (prev = prev + cur.dias);
                        }
                        return prev;
                    },
                    0,
                );
                if (
                    dias === 1 &&
                    dias <= sobrantes &&
                    diasSacadosEnElMes === 0
                ) {
                    valido = true;
                }
                if (valido) {
                    var fila = {
                        idlicencia: null,
                        idagente: this.agente.idagente,
                        idtipoLicencia: tipoLicencia,
                        año: moment().year(),
                        mes: fechas[0].getMonth() + 1,
                        dias: dias,
                        saldoMensual: 0,
                        saldoAnual: null,
                    };
                    this.saldosInsertar.push(fila);
                    return 0;
                }
            } else {
                console.error('no hay posibles dias sobrantes');
                return -1;
            }
            return -1;
        },
        /**
         * Esta funcion toma los dias para la DLA anticipo de LAO y proporcional y como es seguro que no tenga
         * insertado la antiguedad crea una nueva final con la antiguedad y el pedido de los dias
         * si en caso de que ya tenga la antiguedad porque pidio una DLA por ejemplo
         * solo el findIndex la encontrara y podra seleccionarse desde this.antiguedades
         * se verifica la resta y se agrega al pedidos de dias del corriente año
         * devuelvo -1 si hay error cuando los dias pedidos exceden lo permitido
         */
        tratamientoHabilesSinAntiguedad(dias, fecha, fechas, visado = false) {
            if (this.tipoLicencia === 17) {
                const res = this.tratamientoDLA(
                    dias,
                    fechas,
                    this.tipoLicencia,
                    this.antiguedades,
                    visado,
                );
                return res;
            }
            if (this.tipoLicencia === 27) {
                const res = this.tratamientoAnticipo(
                    dias,
                    fechas,
                    this.tipoLicencia,
                    this.antiguedades,
                    visado,
                );
                return res;
            }
            if (
                this.tipoLicencia != 16 &&
                (this.tipoLicencia === 17 ||
                    this.tipoLicencia === 25 ||
                    this.tipoLicencia === 27)
            ) {
                var diasPosibles = 0;
                var diasPedidos = 0;
                console.log(
                    'tratamiento Habiles sin antiguedades comienzo adentro',
                );
                let indiceAntiguedadActual = _.findIndex(
                    this.antiguedades,
                    (a) => {
                        return a.año === moment().year() && a.vigente === true;
                    },
                );

                if (indiceAntiguedadActual >= 0) {
                    diasPosibles = parseInt(
                        this.antiguedades[indiceAntiguedadActual].disponible,
                    );
                    diasPedidos = parseInt(
                        this.antiguedades[indiceAntiguedadActual].pedido,
                    );
                } else {
                    if (this.tipoLicencia === 25) {
                        diasPosibles = 6;
                        diasPedidos = 0;
                    }
                }

                var auxiliar = _.cloneDeep(this.antiguedades);
                auxiliar = _.orderBy(auxiliar, ['año'], ['asc']);
                auxiliar = _.filter(auxiliar, (a) => {
                    return a.año !== moment().year() && a.vigente === true;
                });
                var sobrantes = _.sumBy(auxiliar, (a) => {
                    return parseInt(a.disponible) - parseInt(a.pedido);
                });

                let Validado = 0;
                switch (this.tipoLicencia) {
                    case 17: // DLA
                        var auxiliar = _.cloneDeep(this.antiguedades);
                        auxiliar = _.orderBy(auxiliar, ['año'], ['asc']);
                        auxiliar = _.filter(auxiliar, (a) => {
                            return (
                                a.año === moment().year() && a.vigente === true
                            );
                        });
                        let sobrantes = _.sumBy(auxiliar, (a) => {
                            return parseInt(a.disponible) - parseInt(a.pedido);
                        });

                        if (dias === 1 && dias <= sobrantes) {
                            Validado = 1;
                        }
                        break;
                    case 27: // Anticipo
                        let sobrantesAnticipo = _.sumBy(auxiliar, (a) => {
                            if (a.año != moment().year()) {
                                return (
                                    parseInt(a.disponible) - parseInt(a.pedido)
                                );
                            }
                        });
                        console.log(
                            'Fehca de licencia.fecha pedido inicial en el moment del case de anticipo ',
                            moment(this.licencia.fecha_pedido_inicio),
                        );
                        if (
                            dias + diasPedidos <= diasPosibles / 2 &&
                            sobrantesAnticipo === 0 &&
                            moment(
                                this.licencia.fecha_pedido_inicio,
                            ).isSameOrAfter(
                                moment({
                                    year: this.licencia.fecha_pedido_inicio.getFullYear(),
                                    month: 6,
                                    date: 1,
                                }),
                            ) &&
                            moment(
                                this.licencia.fecha_pedido_inicio,
                            ).isSameOrBefore(
                                moment({
                                    year: this.licencia.fecha_pedido_inicio.getFullYear(),
                                    month: 10,
                                    date: 30,
                                }),
                            )
                        ) {
                            console.log('valido anticipo');
                            Validado = 1;
                        }
                        break;
                    case 25: //Proporcional
                        if (
                            dias <= 6 &&
                            dias <= diasPosibles - diasPedidos &&
                            this.agente.antiguedad === 0
                        ) {
                            console.log('validado proporcional');
                            Validado = 1;
                        }
                        break;
                }
                if (Validado === 1) {
                    if (
                        indiceAntiguedadActual < 0 &&
                        this.tipoLicencia === 25
                    ) {
                        let antiguedad = {
                            año: new Date().getFullYear(),
                            disponible: diasPosibles,
                            pedido: diasPedidos,
                            vigente: true,
                        };
                        var fila = {
                            idlicencia: null,
                            idagente: this.agente.idagente,
                            idtipoLicencia: this.tipoLicencia,
                            año: new Date().getFullYear(),
                            mes: new Date().getMonth() + 1,
                            dias: dias,
                            saldoMensual: null,
                            saldoAnual: null,
                        };
                        this.saldosInsertar.push(fila);
                        this.antiguedades.push(antiguedad);
                    } else if (this.tipoLicencia === 27) {
                        var fila = {
                            idlicencia: null,
                            idagente: this.agente.idagente,
                            idtipoLicencia: this.tipoLicencia,
                            año: this.antiguedades[indiceAntiguedadActual].año,
                            mes: null,
                            dias: dias,
                            saldoMensual: null,
                            saldoAnual: null,
                        };
                        this.saldosInsertar.push(fila);
                    } else if (
                        this.tipoLicencia === 17 &&
                        dias === 1 &&
                        parseInt(
                            this.antiguedades[indiceAntiguedadActual]
                                .disponible,
                        ) -
                            (parseInt(
                                this.antiguedades[indiceAntiguedadActual]
                                    .pedido,
                            ) +
                                parseInt(dias)) >=
                            0
                    ) {
                        var fila = {
                            idlicencia: null,
                            idagente: this.agente.idagente,
                            idtipoLicencia: this.tipoLicencia,
                            año: this.antiguedades[indiceAntiguedadActual].año,
                            mes: fecha.getMonth() + 1,
                            dias: dias,
                            saldoMensual: null,
                            saldoAnual: null,
                        };
                        this.saldosInsertar.push(fila);
                    } else if (
                        this.tipoLicencia === 25 &&
                        parseInt(
                            this.antiguedades[indiceAntiguedadActual]
                                .disponible,
                        ) -
                            (parseInt(
                                this.antiguedades[indiceAntiguedadActual]
                                    .pedido,
                            ) +
                                parseInt(dias)) >=
                            0
                    ) {
                        console.log('entro al saldo');
                        var fila = {
                            idlicencia: null,
                            idagente: this.agente.idagente,
                            idtipoLicencia: this.tipoLicencia,
                            año: this.antiguedades[indiceAntiguedadActual].año,
                            mes: null,
                            dias: dias,
                            saldoMensual: null,
                            saldoAnual: null,
                        };
                        this.saldosInsertar.push(fila);
                    } else {
                        console.info(
                            this.antiguedades[indiceAntiguedadActual]
                                .disponible -
                                (this.antiguedades[indiceAntiguedadActual]
                                    .pedido +
                                    dias) >=
                                0,
                        );
                        console.info(
                            this.antiguedades[indiceAntiguedadActual]
                                .disponible,
                        );
                        console.info(
                            this.antiguedades[indiceAntiguedadActual].pedido,
                        );
                        console.info(dias);
                        console.info(
                            this.antiguedades[indiceAntiguedadActual].pedido +
                                dias,
                        );
                        console.info(
                            this.antiguedades[indiceAntiguedadActual].pedido +
                                dias >=
                                0,
                        );
                        this.mensaje +=
                            ' \n Se descuentan mas dias de los disponibles';
                        return -1;
                    }
                } else {
                    console.log('no ha pasado la validacion');
                    this.mensaje += ' \n No ha pasado la validacion el pedido';
                    return -1;
                }
                return 0;
            }
            return 0;
        },
        /** Busca si la fechas seleccionadas ya se encuentra en el rango de las ya declaradas*/
        fechasYaUtilizadas(form) {
            let result;

            if (this.tipoLicencia !== 35 && this.tipoLicencia !== 36) {
                this.licencias_totales = this.licencias_totales.filter((el) => {
                    if (el.idtipoLicencia !== 35 && el.idtipoLicencia !== 36) {
                        return el;
                    }
                });
            }

            result = _.find(this.licencias_totales, (l) => {
                let bandera = false;
                if (l.cuarta_visado === true) {
                    bandera =
                        bandera +
                        moment(form.fecha_pedido_inicio).isBetween(
                            moment(l.fecha_efectiva_inicio),
                            moment(l.fecha_interrupcion_inicio),
                            null,
                            '[]',
                        );
                    bandera =
                        bandera +
                        moment(form.fecha_pedido_final).isBetween(
                            moment(l.fecha_efectiva_inicio),
                            moment(l.fecha_interrupcion_inicio),
                            null,
                            '[]',
                        );
                    bandera =
                        bandera +
                        moment(l.fecha_interrupcion_inicio).isBetween(
                            moment(form.fecha_pedido_inicio),
                            moment(form.fecha_pedido_final),
                            null,
                            '[]',
                        );
                } else if (l.segundo_visado === true) {
                    bandera =
                        bandera +
                        moment(form.fecha_pedido_inicio).isBetween(
                            moment(l.fecha_efectiva_inicio),
                            moment(l.fecha_efectiva_final),
                            null,
                            '[]',
                        );
                    bandera =
                        bandera +
                        moment(form.fecha_pedido_final).isBetween(
                            moment(l.fecha_efectiva_inicio),
                            moment(l.fecha_efectiva_final),
                            null,
                            '[]',
                        );
                    bandera =
                        bandera +
                        moment(l.fecha_efectiva_inicio).isBetween(
                            moment(form.fecha_pedido_inicio),
                            moment(form.fecha_pedido_final),
                            null,
                            '[]',
                        );
                    bandera =
                        bandera +
                        moment(l.fecha_efectiva_final).isBetween(
                            moment(form.fecha_pedido_inicio),
                            moment(form.fecha_pedido_final),
                            null,
                            '[]',
                        );
                } else {
                    bandera =
                        bandera +
                        moment(form.fecha_pedido_inicio).isBetween(
                            moment(l.fecha_pedido_inicio),
                            moment(l.fecha_pedido_final),
                            null,
                            '[]',
                        );
                    bandera =
                        bandera +
                        moment(form.fecha_pedido_final).isBetween(
                            moment(l.fecha_pedido_inicio),
                            moment(l.fecha_pedido_final),
                            null,
                            '[]',
                        );
                    bandera =
                        bandera +
                        moment(l.fecha_pedido_inicio).isBetween(
                            moment(form.fecha_pedido_inicio),
                            moment(form.fecha_pedido_final),
                            null,
                            '[]',
                        );
                    bandera =
                        bandera +
                        moment(l.fecha_pedido_final).isBetween(
                            moment(form.fecha_pedido_inicio),
                            moment(form.fecha_pedido_final),
                            null,
                            '[]',
                        );
                }
                return bandera;
            });
            return result;
        },
        // Boton de guardar la licencia tiene en cuenta absolutamente todo
        // Verificar la fecha que se permita anteriores para cualquier otra que no sea reglamentaria
        // Ampliar el campo observaciones
        // Revisar la fecha del evento que no puede parsear a string
        onSubmit(evt) {
            evt.preventDefault();
            var error = [];
            this.deshabilitarBoton = true;
            if (
                this.agente.idtipo_sexo === 1 &&
                this.licenciasMujeres.includes(this.tipoLicencia)
            ) {
                // es hombre
                error.push(12);
                this.error_flag(error);
                return -1;
            }
            let form = {
                fecha_pedido_inicio: new Date(
                    this.licencia.fecha_pedido_inicio.getFullYear(),
                    this.licencia.fecha_pedido_inicio.getMonth(),
                    this.licencia.fecha_pedido_inicio.getDate(),
                ),
                fecha_pedido_final: new Date(
                    this.licencia.fecha_pedido_final.getFullYear(),
                    this.licencia.fecha_pedido_final.getMonth(),
                    this.licencia.fecha_pedido_final.getDate(),
                ),
                idtipoLicencia: this.tipoLicencia,
                evento_nombre: this.licencia.evento_nombre,
                evento_lugar: this.licencia.evento_lugar,
                fecha_evento_inicio: this.licencia.fecha_evento_inicio,
                fecha_evento_final: this.licencia.fecha_evento_final,
                razon: this.licencia.razon,
                caracter: this.licencia.caracter,
                resolucion: this.licencia.resolucion,
                idpersona: this.licencia.idpersona,
                alcance: this.licencia.alcance,
                tipo_evento: this.licencia.tipo_evento,
                parentescoFallecido: this.licencia.parentescoFallecido,
                idCapacitacion: this.licencia.idCapacitacion,
                dias: 0,
            };
            this.mensaje = '';
            console.info('entra en submit');
            console.info(this.tipoLicencia);
            console.info(this.licencia.idpersona);
            console.log(form);
            if (this.licenciasPrimerVisado.includes(this.tipoLicencia)) {
                form.primer_visado = true;
            }
            if (
                this.licenciasRequierenUnaPersonaACargo.includes(
                    this.tipoLicencia,
                )
            ) {
                if (this.licencia.idpersona) {
                    form.idpersona = this.licencia.idpersona;
                    this.getDiasRestanteDiscapacitado(form.idpersona);
                } else {
                    error.push(3);
                }
            }
            if (
                form.fecha_pedido_inicio &&
                form.fecha_pedido_final &&
                moment(form.fecha_pedido_inicio).isSameOrBefore(
                    moment(form.fecha_pedido_final),
                )
            ) {
                if (
                    this.fechasYaUtilizadas(form) &&
                    !this.licenciasSinControl.includes(this.tipoLicencia)
                ) {
                    error.push(1);
                    this.error_flag(error);
                    return -1;
                }
                console.log('El formulario que se desea insertar ', form);
                let dias_obtenidos = this.obtenerSimulacionSaldosDias(
                    this.licencia.fecha_pedido_inicio,
                    this.licencia.fecha_pedido_final,
                    this.tipoLicencia,
                );
                form.dias = dias_obtenidos;
                console.log('dias obtenidos', dias_obtenidos);
                console.log('form.dias', form.dias);
                console.log(
                    'fecha pedido inicio',
                    this.licencia.fecha_pedido_inicio,
                );
                console.log(
                    'fecha pedido final',
                    this.licencia.fecha_pedido_final,
                );

                //control si las fechas de inicio y la de final estan presentes y la de inicio es anterior a la de final
                if (
                    form.dias < 7 &&
                    this.licenciasConMinimoPorVez.includes(this.tipoLicencia)
                ) {
                    console.log('entra en el minimo por vez');
                    error.push(2);
                }
                if (dias_obtenidos >= 0) {
                    console.log('dias obtenidos', dias_obtenidos);
                    if (this.licenciasLAO.includes(this.tipoLicencia)) {
                        /**
                         * Tratamiento de DLA, proporcional y anticipo
                         */
                        var res_trat = this.tratamientoHabilesSinAntiguedad(
                            dias_obtenidos,
                            form.fecha_pedido_inicio,
                            [form.fecha_pedido_inicio, form.fecha_pedido_final],
                        );
                        console.log('resultado del tratamiento ', res_trat);
                        if (res_trat === -1) {
                            error.push(11);
                            this.error_flag(error);
                            return -1;
                        }

                        /*****
                         * Tratamiento a LAO
                         *
                         */
                        if (this.tipoLicencia === 16) {
                            let res = this.tratamientoLAO(
                                this.antiguedades,
                                form.fecha_pedido_inicio,
                                dias_obtenidos,
                            );
                            if (res < 0) {
                                error.push(9);
                                this.error_flag(error);
                                return -1;
                            }
                        }
                    }

                    console.log('OnSubmit form.dias', form.dias);
                    if (
                        this.licenciasRequierenUnaPersonaACargo.includes(
                            this.tipoLicencia,
                        )
                    ) {
                        if (this.licencia.idpersona) {
                            form.idpersona = this.licencia.idpersona;
                        } else {
                            error.push(3);
                        }
                    }
                } else {
                    console.log('error en los dias obtenidos');
                    error.push(5);
                }

                if (error.length === 0) {
                    console.log('form antes de guardar');
                    console.log(form);
                    this.guardarLicencia(form);
                    return 0;
                }
            } else {
                error.push(4);
            }

            return error.length > 0 ? this.error_flag(error) : 0;
        },
        onReset(evt) {
            evt.preventDefault();
            // Reset our form values
            this.licencia.fecha_pedido = '';
            this.licencia.fecha_efectiva = '';
            this.licencia.idtipoLicencias = null;
            this.licencia.persona = null;
            // Trick to reset/clear native browser form validation state
            this.show = false;
            this.$nextTick(() => {
                this.show = true;
            });
        },
        /**
         * Volver a la pantalla principal
         */
        volverLicencia() {
            this.deshabilitarBoton = false;
            this.$emit('addLicencia', { licencia: 0, visar: 0 });
        },
        /**
         * Actualiza todos los saldos exceptuando los dependientes de la antiguedad pero la antiguedad es
         * actualizada en el getAntiguedad del controlador
         * Actualiza maximos mensuales y anuales y setea el reset de antiguedades
         */
        actualizarMaximos() {
            console.log('actualizarMaximos');
            let idTipoLicencia = this.tipoLicencia;
            if (this.idlicencia) {
                console.log(
                    'Encontro que tiene que sacar saldos demas para la modificacion',
                );
                console.log('Estos son los saldos traidos ', this.saldos);
                this.saldos = _.filter(this.saldos, (saldo) => {
                    return saldo.idlicencia != this.idlicencia;
                });
                this.saldos_totales = _.filter(this.saldos_totales, (saldo) => {
                    return saldo.idlicencia != this.idlicencia;
                });
                console.log('Estos son los saldos limpiados ', this.saldos);
            }
            if (this.licenciasConMaximoMensual.includes(this.tipoLicencia)) {
                for (var licencia of this.licencias_totales.filter((el) => {
                    return el.idtipoLicencia === this.tipoLicencia;
                })) {
                    var anio = licencia.fecha_pedido_final.split('-');
                    anio = anio[0];
                    const mes = licencia.fecha_pedido_final.split('-')[1];
                    const dias = licencia.fecha_pedido_final.split('-')[2];
                    this.diasMensualesMaximos[parseInt(anio)][parseInt(mes)][
                        this.tipoLicencia
                    ] -= dias;
                }
                this.resetdiasMensualesMaximos = JSON.parse(
                    JSON.stringify(this.diasMensualesMaximos),
                );
            } else if (
                this.licenciasConMaximoAnual.includes(this.tipoLicencia)
            ) {
                console.log('3');

                for (var asiento in this.saldos) {
                    console.log('asiento');
                    console.log(asiento);
                    console.log(this.saldos);
                    console.log(this.diasAnualesMaximos);
                    var anio = this.saldos[asiento]['año'];
                    var dias = this.saldos[asiento]['dias'];
                    this.diasAnualesMaximos[anio][this.tipoLicencia] -= dias;
                    // TODO: Sacar si es que el 254 no va, el de postparto y preparto para tener 180 dias en cconjunto
                    if (this.tipoLicencia === 8 || this.tipoLicencia === 10) {
                        if (this.tipoLicencia === 8) {
                            this.diasAnualesMaximos[anio][10] -= dias;
                        } else {
                            this.diasAnualesMaximos[anio][8] -= dias;
                        }
                    }
                }
                this.resetdiasAnualesMaximos = JSON.parse(
                    JSON.stringify(this.diasAnualesMaximos),
                );
            } else if (
                this.licenciasConMaximoAntiguedad.includes(this.tipoLicencia)
            ) {
                this.resetAntiguedades = JSON.parse(
                    JSON.stringify(this.antiguedades),
                );
            } else {
                return -1;
            }
            return 0;
        },
        decontarFeriados(startDate, endDate, holydays) {
            var counter = 0;
            for (var holyday in holydays) {
                let amountHolydays = moment(holydays[holyday]).isBetween(
                    moment([
                        startDate.getFullYear(),
                        startDate.getMonth(),
                        startDate.getDate(),
                    ]),
                    moment([
                        endDate.getFullYear(),
                        endDate.getMonth(),
                        endDate.getDate(),
                    ]),
                    null,
                    '[]',
                ); //true
                // Ver si ese feriado es sabado o domingo
                if (amountHolydays) {
                    if (
                        moment(holydays[holyday]).day() !== 6 &&
                        moment(holydays[holyday]).day() !== 0
                    ) {
                        counter += amountHolydays ? 1 : 0;
                    }
                }
            }
            console.log(`descuento feriado ${counter}`);
            return counter;
        },
        getDiasContinuos(startDate, endDate) {
            var elapsed, daysBeforeFirstSunday, daysAfterLastSunday;
            elapsed = endDate.getTime() - startDate.getTime();
            elapsed /= 86400000;
            return Math.ceil(elapsed + 1);
        },
        getDiasHabiles(startDate, endDate) {
            var elapsed, daysBeforeFirstSunday, daysAfterLastSunday;
            var ifThen = function (a, b, c) {
                return a === b ? c : a;
            };
            elapsed = endDate - startDate;
            elapsed /= 86400000;
            daysBeforeFirstSunday = (7 - startDate.getDay()) % 7;
            daysAfterLastSunday = endDate.getDay();
            elapsed -= daysBeforeFirstSunday + daysAfterLastSunday;
            elapsed = (elapsed / 7) * 5;
            //console.log("enlapse", elapsed);
            var sabado = 0;
            var domingo = 0;
            if (startDate.getDay() === 6 || startDate.getDay() === 0) {
                sabado = 0;
            } else {
                sabado = 6 - startDate.getDay();
            }

            if (endDate.getDay() === 5 || endDate.getDay() === 6) {
                domingo = 5;
            } else {
                domingo = endDate.getDay();
            }

            elapsed += sabado + domingo;

            if (this.feriados.length > 0) {
                let descuentoFeriados = this.decontarFeriados(
                    startDate,
                    endDate,
                    this.feriados,
                );
                elapsed -= descuentoFeriados;
            }

            const sumarSabado = this.dia.findIndex(
                (element) => element === 8 || element === 6 || element === 7,
            );
            // Calculo sumar la guardia Sabado y Domingo
            if (startDate.getDay() === 0 || startDate.getDay() === 6) {
                console.log('sumar Sabado');
                if (sumarSabado >= 0) {
                    elapsed += 1;
                }
            }

            var banderaFeriadoAlComienzo = this.feriados.includes(
                moment([
                    startDate.getFullYear(),
                    startDate.getMonth(),
                    startDate.getDate(),
                ]).format('yyyy-MM-DD'),
            );
            if (
                banderaFeriadoAlComienzo === true &&
                (startDate.getDay() === 0 || startDate.getDay() === 6)
            ) {
                banderaFeriadoAlComienzo = false;
            }
            if (
                banderaFeriadoAlComienzo === true &&
                (this.dia === startDate.getDay() || this.dia.includes(8))
            ) {
                elapsed += 1;
            }

            // Calculo de sumar el feriado

            return Math.ceil(elapsed);
        },
        obtenerSimulacionSaldosDias(date1, date2, idTipoLicencia) {
            var fecha_inicio = [];
            var fecha_final = [];
            fecha_inicio[0] = date1.getFullYear();
            fecha_inicio[1] = date1.getMonth();
            fecha_inicio[2] = date1.getDate();

            fecha_final[0] = date2.getFullYear();
            fecha_final[1] = date2.getMonth();
            fecha_final[2] = date2.getDate();

            var dias = this.getDiasPorAñoMes(
                new Date(fecha_inicio[0], fecha_inicio[1], fecha_inicio[2]),
                new Date(fecha_final[0], fecha_final[1], fecha_final[2]),
                idTipoLicencia,
            );
            console.log('dias obtenidos en obtenerSimulacionSaldosDias', dias);
            console.log(
                'dias obtenidos en inicio',
                new Date(fecha_inicio[0], fecha_inicio[1], fecha_inicio[2]),
            );
            console.log(
                'dias obtenidos en final',
                new Date(fecha_final[0], fecha_final[1], fecha_final[2]),
            );
            if (dias[1] === -1) {
                this.mensaje +=
                    ' \n No se guardo, porque supera los maximos permitidos';
                return -1;
            }

            if (this.licenciasHabiles.includes(idTipoLicencia)) {
                dias = this.getDiasHabiles(
                    new Date(fecha_inicio[0], fecha_inicio[1], fecha_inicio[2]),
                    new Date(fecha_final[0], fecha_final[1], fecha_final[2]),
                );
            } else {
                dias = this.getDiasContinuos(
                    new Date(fecha_inicio[0], fecha_inicio[1], fecha_inicio[2]),
                    new Date(fecha_final[0], fecha_final[1], fecha_final[2]),
                );
            }
            console.log(
                'dias obtenidos en obtenerSimulacionSaldosDias despues de habil y continuo',
                dias,
            );
            var feriados = this.decontarFeriados(
                new Date(fecha_inicio[0], fecha_inicio[1], fecha_inicio[2]),
                new Date(fecha_final[0], fecha_final[1], fecha_final[2]),
                this.feriados,
            );

            if (this.licenciasConMaximoPorVez.includes(idTipoLicencia)) {
                dias = this.restarMaximoPorVez(
                    dias,
                    this.diasMaximosPorVez,
                    idTipoLicencia,
                );
            }
            console.log('estoy al final de obtenerSimulacionSaldosDias');
            console.log('dias obtenidos en obtenerSimulacionSaldosDias', dias);
            return dias;
        },
        formatearDiasInsertados() {
            console.log(
                'formateando el saldo a insertar ',
                this.diasInsertarSaldo,
            );
            console.log(
                'Insertando fila en conleccion de saldos ',
                _.cloneDeep(this.saldosInsertar),
            );
            console.log('Saldo completo insertada ', this.saldosInsertar);

            if (
                this.licenciasConMaximoMensual.includes(this.tipoLicencia) &&
                !this.licenciasConMaximoAnual.includes(this.tipoLicencia) &&
                this.tipoLicencia != 17 &&
                this.tipoLicencia != 27 &&
                this.tipoLicencia != 25
            ) {
                console.log('Insertando fila en saldo con maximo mensual ');
                for (var idxAnual in this.diasInsertarSaldo) {
                    for (var idxMensual in this.diasInsertarSaldo[idxAnual]) {
                        let fila = {
                            idlicencia: null,
                            idagente: this.agente.idagente,
                            idtipoLicencia: this.tipoLicencia,
                            año: idxAnual,
                            mes: idxMensual,
                            dias: this.diasInsertarSaldo[idxAnual][idxMensual][
                                this.tipoLicencia
                            ],
                            saldoMensual: null,
                            saldoAnual: null,
                        };
                        fila['saldoMensual'] =
                            this.diasMensualesMaximos[idxAnual][idxMensual][
                                this.tipoLicencia
                            ];
                        this.saldosInsertar.push(fila);
                    }
                }
            } //endIF
            var diasAnuales = 0;
            if (
                this.licenciasConMaximoAnual.includes(this.tipoLicencia) &&
                !this.licenciasConMaximoMensual.includes(this.tipoLicencia) &&
                this.tipoLicencia != 17 &&
                this.tipoLicencia != 27 &&
                this.tipoLicencia != 25
            ) {
                console.log('Insertando fila en saldo con maximo anual ');
                for (var idxAnual in this.diasInsertarSaldo) {
                    for (var idxMensual in this.diasInsertarSaldo[idxAnual]) {
                        diasAnuales +=
                            this.diasInsertarSaldo[idxAnual][idxMensual][
                                this.tipoLicencia
                            ];
                    } //endfor mes
                    if (diasAnuales > 0) {
                        let fila = {
                            idlicencia: null,
                            idagente: this.agente.idagente,
                            idtipoLicencia: this.tipoLicencia,
                            año: idxAnual,
                            mes: null,
                            dias: diasAnuales,
                            saldoMensual: null,
                            saldoAnual: null,
                        };
                        fila['saldoAnual'] =
                            this.diasAnualesMaximos[idxAnual][
                                this.tipoLicencia
                            ];
                        console.log('Insertando fila en saldo ', fila);
                        this.saldosInsertar.push(fila);
                        console.log(
                            'Insertando fila en conleccion de saldos ',
                            this.saldosInsertar,
                        );
                    }
                    diasAnuales = 0;
                } //endfor anio
            } //endIf
            diasAnuales = 0;

            console.log('Saldo completo insertada ', this.saldosInsertar);
        },
        restarAntiguedad(descuentos, maximos, idTipoLicencia) {
            var diasAnuales = 0;
            console.log('descuentos en restarAntiguedad ', descuentos);
            console.log(
                'antiguedades originales en restarAntiguedad ',
                this.antiguedades,
            );
            console.log('maximos en restarAntiguedad ', maximos);
            for (var idxAnual in descuentos) {
                for (var idxMensual in descuentos[idxAnual]) {
                    diasAnuales +=
                        descuentos[idxAnual][idxMensual][this.tipoLicencia];
                }
            }
            var variosAnios = false;
            // Si son mas de 3 dias descuenta de todos los años vigentes sino solo del actual
            if (diasAnuales >= 3) {
                maximos = _.orderBy(maximos, ['año'], ['asc']);
                variosAnios = true;
            } else {
                maximos = _.find(maximos, (m) => {
                    return m.año === new Date().getFullYear();
                });
            }
            if (maximos) {
                if (variosAnios) {
                    for (var antiguedad in maximos) {
                        if (
                            maximos[antiguedad]['disponible'] -
                                (maximos[antiguedad]['pedido'] + diasAnuales) >=
                            0
                        ) {
                            maximos[antiguedad]['pedido'] += diasAnuales;
                            diasAnuales = 0;
                        } else if (
                            maximos[antiguedad]['disponible'] -
                                maximos[antiguedad]['pedido'] >
                            0
                        ) {
                            let saldo =
                                maximos[antiguedad]['disponible'] -
                                maximos[antiguedad]['pedido'];
                            maximos[antiguedad]['pedido'] += saldo;
                            diasAnuales -= saldo;
                        } else {
                        }
                    }
                    if (diasAnuales > 0) {
                        console.log(
                            'los dias a descontar son mas de los disponibles ',
                            diasAnuales,
                        );
                        return -1;
                    }
                } else {
                    if (
                        maximos['disponible'] -
                            (maximos['pedido'] + diasAnuales) >=
                        0
                    ) {
                        console.log('maximos ', maximos);
                        console.log('maximos.pedido ', maximos.pedido);
                        console.log('maximos.disponible ', maximos.disponible);
                        console.log('maximos.pedido ', maximos['disponible']);
                        console.log('maximos [pedido] ', maximos['pedido']);
                        maximos['pedido'] += diasAnuales;
                        console.log('Se resto en teoria ', maximos['pedido']);
                    } else {
                        console.log('restarAntiguedadesMaximos reseteo');
                        this.resetearValores();
                        return -1;
                    }
                    console.log('Se resto en teoria ', diasAnuales);
                    console.log('Antiguedades originales ', this.antiguedades);
                    console.log('El resultado del descuento es ', maximos);
                    console.log('Los restos que llegaron son ', descuentos);
                    return -1;
                }
            }
        },
        resetearValores() {
            console.log('Reseteo de valores');
            this.mensaje += ' \n El descuento de la fecha pedida no es posible';
            this.diasAnualesMaximos = JSON.parse(
                JSON.stringify(this.resetdiasAnualesMaximos),
            );
            this.diasMensualesMaximos = JSON.parse(
                JSON.stringify(this.resetdiasMensualesMaximos),
            );
            this.antiguedades = JSON.parse(
                JSON.stringify(this.resetAntiguedades),
            );
            this.saldosInsertar = [];
            this.diasInsertarSaldo = JSON.parse(
                JSON.stringify(this.resetdiasInsertarSaldo),
            );
            this.setearDiasMaximosFamiliares();
        },
        restarMaximoPorVez(descuentos, maximos, idTipoLicencia) {
            console.log('comienza la resta por vez');
            console.log(maximos[idTipoLicencia]);
            console.log(descuentos);
            if (maximos[idTipoLicencia] - descuentos >= 0) {
                maximos[idTipoLicencia] -= descuentos;
            } else {
                this.mensaje +=
                    '\n Se esta excediendo en el numero maximo de dias por licencia';
                this.resetearValores();
                return -1;
            }
            let resultado = descuentos;
            return resultado;
        },
        restarMensualesMaximos(descuentos, maximos, idTipoLicencia) {
            console.log('comienza la resta de mensual');
            console.log(descuentos);
            console.log(maximos);
            console.log(idTipoLicencia);
            if (this.tipoLicencia === 17) {
                return 0;
            }
            var anios = Object.keys(descuentos);
            var anio = 0;
            for (anio in anios) {
                var meses = Object.keys(descuentos[anios[anio]]);
                for (var mes in meses) {
                    if (
                        maximos[anios[anio]][meses[mes]][idTipoLicencia] -
                            descuentos[anios[anio]][meses[mes]][
                                idTipoLicencia
                            ] >=
                        0
                    ) {
                        maximos[anios[anio]][meses[mes]][idTipoLicencia] -=
                            descuentos[anios[anio]][meses[mes]][idTipoLicencia];
                    } else {
                        this.mensaje += ' \n Se ha superado el maximo mensual';
                        this.resetearValores();
                        return -1;
                    }
                }
            }
            return 0;
        },
        restarAnualesMaximos(descuentos, maximos, idTipoLicencia) {
            const anios = [];
            Object.entries(descuentos).forEach(([key, value]) => {
                if (
                    typeof value === 'object' &&
                    value !== null &&
                    !Array.isArray(value)
                ) {
                    anios.push(parseInt(key));
                }
            });
            let anio = 0;
            for (anio of anios) {
                let meses = Object.keys(descuentos[anio]);
                if (meses.length === 0) {
                    continue;
                }
                let contadorMeses = 0;
                for (let mes of meses) {
                    contadorMeses += descuentos[anio][mes][idTipoLicencia];
                }
                if (maximos[anio][idTipoLicencia] - contadorMeses >= 0) {
                    maximos[anio][idTipoLicencia] -= contadorMeses;
                } else {
                    this.mensaje += ' \n Se ha superado el maximo anual';
                    this.resetearValores();
                    return -1;
                }
            }
            return 0;
        },
        getDiasPorAñoMes(startDate, endDate, idTipoLicencia) {
            let mesInicio = startDate.getMonth();
            let mesFin = endDate.getMonth();
            let anioInicio = startDate.getFullYear();
            let anioFin = endDate.getFullYear();
            var cantidadAnios = 0;
            var cantidadMeses = 0;
            if (anioFin !== anioInicio) {
                cantidadAnios = anioFin - anioInicio;
                cantidadMeses += 12 * cantidadAnios;
            }
            if (mesFin !== mesInicio) {
                cantidadMeses += mesFin - mesInicio;
            }

            var a1 = startDate.getFullYear();
            var m1 = startDate.getMonth();
            var d1 = startDate.getDate();

            var a2 = endDate.getFullYear();
            var m2 = endDate.getMonth();
            var d2 = endDate.getDate();

            var auxInicio = new Date(a1, m1, d1);
            var auxFin = new Date(a2, m2, d2);
            var dias = 0;
            for (var i = 0; i <= cantidadMeses && cantidadMeses !== 0; i++) {
                if (i > 0 && m1 === 11) {
                    //a1 += 1;
                    console.log('Se actualizo el año');
                }
                // Cuando ya no es el primer mes debe aumentar el mes
                // Se aumentan 2 meses porque porque el 0 del dia me lo manda al
                // correspondiente, por el problema de las fechas 31
                if (i >= 1) {
                    auxInicio = new Date(a1, auxInicio.getMonth() + 2, 0);
                    console.log('El avance de la fecha ', auxInicio.toString());
                } else {
                }
                m1 = auxInicio.getMonth();
                a1 = auxInicio.getFullYear();
                if (i === 0) {
                    let ultimoDiaMes = new Date(
                        m1 + 1 === 12 ? a1 + 1 : a1,
                        m1 + 1 === 12 ? 0 : m1 + 1,
                        0,
                    );
                    console.log('dia del Primer mes: ', auxInicio.toString());
                    console.log(
                        'Ultimo dia del Primer mes: ',
                        ultimoDiaMes.toString(),
                    );
                    if (this.licenciasHabiles.includes(idTipoLicencia)) {
                        dias = this.getDiasHabiles(auxInicio, ultimoDiaMes);
                    } else {
                        dias = this.getDiasContinuos(auxInicio, ultimoDiaMes);
                    }
                } else if (i === cantidadMeses) {
                    let primerDiaMes = new Date(a1, m1 === 12 ? 0 : m1);
                    if (this.licenciasHabiles.includes(idTipoLicencia)) {
                        dias = this.getDiasHabiles(primerDiaMes, endDate);
                    } else {
                        dias = this.getDiasContinuos(primerDiaMes, endDate);
                    }
                    console.log(
                        'Primer dia del Ultimo mes: ',
                        primerDiaMes.toString(),
                    );
                    console.log(
                        'Ultimo dia del Primer mes: ',
                        endDate.toString(),
                    );
                } else {
                    let primerDiaMes = new Date(a1, m1 === 12 ? 0 : m1);
                    let ultimoDiaMes = new Date(
                        m1 + 1 === 12 ? a1 + 1 : a1,
                        m1 + 1 === 12 ? 0 : m1 + 1,
                        0,
                    );
                    console.log(
                        'Ultimo dia del mes sandwich: ',
                        ultimoDiaMes.toString(),
                    );
                    console.log(
                        'Primer dia del mes sandwich: ',
                        primerDiaMes.toString(),
                    );
                    if (this.licenciasHabiles.includes(idTipoLicencia)) {
                        dias = this.getDiasHabiles(primerDiaMes, ultimoDiaMes);
                    } else {
                        dias = this.getDiasContinuos(
                            primerDiaMes,
                            ultimoDiaMes,
                        );
                    }
                }
                let nuevoItem = {};
                this.diasInsertarSaldo[a1][m1 + 1] = {
                    [idTipoLicencia]: dias,
                };
                nuevoItem = {
                    [a1]: {
                        [m1 + 1]: {
                            [idTipoLicencia]: dias,
                        },
                    },
                };
                console.log(this.diasInsertarSaldo[a1]);
            }

            if (cantidadMeses === 0) {
                console.log('cero meses de diferencia');
                if (this.licenciasHabiles.includes(idTipoLicencia)) {
                    dias = this.getDiasHabiles(startDate, endDate);
                } else {
                    dias = this.getDiasContinuos(startDate, endDate);
                }
                let nuevoItem = {};
                this.diasInsertarSaldo[a1][m1 + 1] = {
                    [idTipoLicencia]: dias,
                };
                nuevoItem = {
                    [a1]: {
                        [m1 + 1]: {
                            [idTipoLicencia]: dias,
                        },
                    },
                };
                console.log(
                    'mostrando lineas de saldo ',
                    this.diasInsertarSaldo,
                );
            }
            console.log('se hara la resta');
            // Aquellas licencias que tienen limite mensual
            if (this.licenciasConMaximoMensual.includes(idTipoLicencia)) {
                var resultado = this.restarMensualesMaximos(
                    this.diasInsertarSaldo,
                    this.diasMensualesMaximos,
                    idTipoLicencia,
                );
            }
            // Aquellas licencias que tienen limite anual
            if (this.licenciasConMaximoAnual.includes(idTipoLicencia)) {
                var resultado = this.restarAnualesMaximos(
                    this.diasInsertarSaldo,
                    this.diasAnualesMaximos,
                    idTipoLicencia,
                );
            }
            return [dias, resultado];
        },
        resetForm() {
            this.licencia = Object.assign({}, this.licenciaReset);
        },
    },
    watch: {
        fecha_pedido_final: function (newV, oldV) {
            //this.licencia.dias = diasTrabajados(this.form.fecha_pedido_inicio, newV);
        },
        fecha_pedido_inicio: function (newV, oldV) {
            //this.licencia.dias = diasTrabajados(this.form.fecha_pedido_inicio, newV);
        },
        [`licencia.idpersona`](newValue, oldValue) {
            if (newValue !== oldValue) this.setearDiasMaximosFamiliares();
        },
    },
    computed: {
        // dias sobrantes del familiar buscado
        dias() {
            return this.$store.getters['grupo/dias'];
        },
        existeSancion() {
            return false;
        },
        obtenerAntiguedades() {
            return this.$store.getters['antiguedad/antiguedades'];
        },
        role_name() {
            return this.$store.getters['user/role_name'];
        },
        primer_visado() {
            return this.licencia.primer_visado ? 'SI' : 'NO';
        },
        segundo_visado() {
            return this.licencia.segundo_visado ? 'SI' : 'NO';
        },
        tercera_visado() {
            return this.licencia.tercera_visado ? 'SI' : 'NO';
        },
        cuarta_visado() {
            return this.licencia.cuarta_visado ? 'SI' : 'NO';
        },
        persona() {
            return this.licencia.nombre + ' ' + this.licencia.apellido;
        },
        texto_tipo_licencia() {
            let texto = 0;
            switch (this.tipoLicencia) {
                case 1:
                    texto = 'Corto Tratamiento';
                    break;
                case 2:
                    texto = 'Largo Tratamiento';
                    break;
                case 3:
                    texto = 'Enfermedad Critica';
                    break;
                case 4:
                    texto = 'ART';
                    break;
                case 5:
                    texto = 'Gremial';
                    break;
                case 6:
                    texto = 'Deportiva';
                    break;
                case 7:
                    texto = 'Jubilacion Invalidez';
                    break;
                case 8:
                    texto = 'Preparto';
                    break;
                case 9:
                    texto = 'Matrimonio';
                    break;
                case 10:
                    texto = 'Postparto';
                    break;
                case 11:
                    texto = 'Familiar Enfermo';
                    break;
                case 12:
                    texto = 'Nacimiento';
                    break;
                case 13:
                    texto = 'Adopcion';
                    break;
                case 14:
                    texto = 'Fallecimiento';
                    break;
                case 15:
                    texto = 'Sin Sueldo';
                    break;
                case 16:
                    texto = 'Ordinaria';
                    break;
                case 17:
                    texto = 'DLA';
                    break;
                case 18:
                    texto = 'Capacitacion';
                    break;
                case 19:
                    texto = 'Capacitacion Prolongada';
                    break;
                case 20:
                    texto = 'Familiar Discapacitado';
                    break;
                case 21:
                    texto = '4.1';
                    break;
                case 22:
                    texto = '4.2';
                    break;
                case 23:
                    texto = '2A';
                    break;
                case 24:
                    texto = '2B';
                    break;
                case 25:
                    texto = 'Proporcional';
                    break;
                case 26:
                    texto = 'Postergacion';
                    break;
                case 27:
                    texto = 'Anticipo';
                    break;
                case 28:
                    texto = 'Examen';
                    break;
                case 29:
                    texto = 'Obligacion Militar';
                    break;
                case 30:
                    texto = 'Cargo Publico';
                    break;
                case 31:
                    texto = 'Otra Causal PE';
                    break;
                case 32:
                    texto = 'Deportiva No Rentada';
                    break;
                case 33:
                    texto = 'Inasistencia con Aviso';
                    break;
                case 34:
                    texto = 'Inasistencia sin Aviso';
                    break;
                case 35:
                    texto = '5.1';
                    break;
                case 36:
                    texto = '5.2';
                    break;
                default:
                    texto = null;
                    break;
            }
            return texto;
        },
        state() {
            return this.licencia.primer_visado === true;
        },
        get_fecha_pedido_inicio_evento() {
            let aux = moment(this.licencia.fecha_pedido_inicio).subtract(
                -7,
                'days',
            );
            return new Date(aux.get('year'), aux.get('month'), aux.get('date'));
        },
        get_fecha_pedido_final_evento() {
            let aux = moment(this.licencia.fecha_pedido_final).add(7, 'days');
            return new Date(aux.get('year'), aux.get('month'), aux.get('date'));
        },
        get_fecha_pedido_inicio() {
            let aux = moment(this.licencia.fecha_pedido_inicio);
            return new Date(aux.get('year'), aux.get('month'), aux.get('date'));
        },
        get_fecha_pedido_final() {
            let aux = moment(this.licencia.fecha_pedido_final);
            return new Date(aux.get('year'), aux.get('month'), aux.get('date'));
        },
        get_fecha_efectiva_inicio() {
            let aux = moment(this.licencia.fecha_efectiva_inicio);
            return new Date(aux.get('year'), aux.get('month'), aux.get('date'));
        },
        get_fecha_efectiva_final() {
            let aux = moment(this.licencia.fecha_efectiva_final);
            return new Date(aux.get('year'), aux.get('month'), aux.get('date'));
        },
        getFechaPedidoInicio() {
            let aux = moment(this.licencia.fecha_pedido_inicio).format(
                'DD/MM/YYYY',
            );
            return aux === 'Invalid date' ? 'Sin Fecha Seleccionada' : aux;
        },
        getFechaPedidoFinal() {
            let aux = moment(this.licencia.fecha_pedido_final).format(
                'DD/MM/YYYY',
            );
            return aux === 'Invalid date' ? 'Sin Fecha Seleccionada' : aux;
        },
        getFechaEventoInicio() {
            if (this.licencia.fecha_evento_inicio) {
                const [anio, mes, dia] = this.licencia.fecha_evento_inicio
                    .substr(0, 10)
                    .split('-');
                const aux = new Date(anio, mes, dia);
                return aux === 'Invalid date'
                    ? 'Sin Fecha Seleccionada'
                    : aux.toLocaleDateString();
            }
            return 'Sin Fecha Seleccionada';
        },
        getFechaEventoFinal() {
            if (this.licencia.fecha_evento_inicio) {
                const [anio, mes, dia] = this.licencia.fecha_evento_final
                    .substr(0, 10)
                    .split('-');
                const aux = new Date(anio, mes, dia);
                return aux === 'Invalid date'
                    ? 'Sin Fecha Seleccionada'
                    : aux.toLocaleDateString();
            }
            return 'Sin Fecha Seleccionada';
        },
        getFechaEfectivoInicio() {
            let aux = moment(this.licencia.fecha_efectiva_inicio).format(
                'DD/MM/YYYY',
            );
            return aux === 'Invalid date' ? 'Sin Fecha Seleccionada' : aux;
        },
        getFechaEfectivoFinal() {
            let aux = moment(this.licencia.fecha_efectiva_final).format(
                'DD/MM/YYYY',
            );
            return aux === 'Invalid date' ? 'Sin Fecha Seleccionada' : aux;
        },
        getFechaInterrupcion() {
            let aux = moment(this.licencia.fecha_interrupcion_inicio).format(
                'DD/MM/YYYY',
            );
            return aux === 'Invalid date' ? 'Sin Fecha Seleccionada' : aux;
        },
        agente() {
            return this.$store.getters['agente/agente'];
        },
        get_antiguedades() {
            return this.$store.getters['antiguedad/antiguedades'];
        },
        puesto() {
            return this.$store.getters['agente/puesto'];
        },
        get_mensaje() {
            return this.mensaje ? true : false;
        },
    },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to
/* .fade-leave-active below version 2.1.8 */ {
    opacity: 0;
}

.filled-diagnostico {
    background-color: #4caf50 !important; /* Verde */
    color: white !important;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}
</style>
