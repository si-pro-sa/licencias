<template>
    <div>
        <v-container>
            <v-row>
                <v-col>
                    <v-card>
                        <v-card-title
                            class="font-weight-regular siprosa white--text headline"
                        >
                            <v-icon
                                large
                                color="white"
                                >mdi-account-circle-outline
                            </v-icon>
                            <span class="white--text ml-4"
                                >Informacion del Agente</span
                            >
                        </v-card-title>

                        <v-card-text>
                            <v-container>
                                <div class="d-flex flex-column">
                                    <v-row>
                                        <v-col>
                                            <v-dialog
                                                v-model="dialog"
                                                fullscreen
                                                hide-overlay
                                                transition="dialog-bottom-transition"
                                            >
                                                <template
                                                    v-slot:activator="{
                                                        on,
                                                        attrs,
                                                    }"
                                                >
                                                    <v-btn
                                                        color="primary"
                                                        dark
                                                        tile
                                                        v-bind="attrs"
                                                        v-on="on"
                                                        :disabled="
                                                            deshabilitado
                                                        "
                                                    >
                                                        <v-icon left
                                                            >mdi-account-search-outline</v-icon
                                                        >
                                                        Busqueda Avanzada
                                                    </v-btn>
                                                </template>
                                                <v-card>
                                                    <v-toolbar
                                                        dark
                                                        color="primary"
                                                    >
                                                        <v-btn
                                                            icon
                                                            dark
                                                            @click="
                                                                dialog = false
                                                            "
                                                        >
                                                            <v-icon
                                                                >mdi-close</v-icon
                                                            >
                                                        </v-btn>
                                                        <v-toolbar-title
                                                            >Buscar
                                                            Agente</v-toolbar-title
                                                        >
                                                    </v-toolbar>
                                                    <v-card-text>
                                                        <v-row>
                                                            <v-col>
                                                                <v-text-field
                                                                    v-model="
                                                                        documento
                                                                    "
                                                                    label="Documento"
                                                                ></v-text-field>
                                                                <v-text-field
                                                                    v-model="
                                                                        nombre
                                                                    "
                                                                    label="Nombre"
                                                                ></v-text-field>
                                                            </v-col>
                                                            <v-col>
                                                                <v-text-field
                                                                    v-model="
                                                                        apellido
                                                                    "
                                                                    label="Apellido"
                                                                ></v-text-field>
                                                                <v-select
                                                                    v-model="
                                                                        select
                                                                    "
                                                                    :items="
                                                                        tipoFunciones
                                                                    "
                                                                    item-text="label"
                                                                    item-value="value"
                                                                    label="Función"
                                                                    return-object
                                                                ></v-select>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row
                                                            align="center"
                                                            justify="center"
                                                        >
                                                            <v-col cols="12">
                                                                <p
                                                                    class="text-center"
                                                                ></p>
                                                            </v-col>
                                                            <v-btn-toggle>
                                                                <v-btn
                                                                    @click="
                                                                        clean
                                                                    "
                                                                >
                                                                    <v-icon
                                                                        >mdi-backspace-reverse-outline</v-icon
                                                                    >
                                                                    Limpiar
                                                                </v-btn>
                                                                <v-btn
                                                                    @click="
                                                                        fetchAgenteAdvance
                                                                    "
                                                                >
                                                                    <v-icon
                                                                        >mdi-account-search-outline</v-icon
                                                                    >
                                                                    Buscar
                                                                </v-btn>
                                                            </v-btn-toggle>
                                                        </v-row>
                                                    </v-card-text>
                                                    <v-divider></v-divider>
                                                    <v-data-table
                                                        :headers="headers"
                                                        :items="agentes"
                                                        :items-per-page="5"
                                                        class="elevation-1"
                                                        item-key="documento"
                                                        sort-by="documento"
                                                    >
                                                        <template
                                                            v-slot:body.prepend
                                                        >
                                                            <tr>
                                                                <td></td>
                                                                <td>
                                                                    <v-text-field
                                                                        label="Filtro"
                                                                        type="text"
                                                                        v-model="
                                                                            filter.documento
                                                                        "
                                                                    ></v-text-field>
                                                                </td>
                                                                <td>
                                                                    <v-text-field
                                                                        label="Filtro"
                                                                        type="text"
                                                                        v-model="
                                                                            filter.nombre
                                                                        "
                                                                    ></v-text-field>
                                                                </td>
                                                                <td>
                                                                    <v-text-field
                                                                        label="Filtro"
                                                                        type="text"
                                                                        v-model="
                                                                            filter.apellido
                                                                        "
                                                                    ></v-text-field>
                                                                </td>
                                                                <td>
                                                                    <v-text-field
                                                                        label="Filtro"
                                                                        type="text"
                                                                        v-model="
                                                                            filter.funcion
                                                                        "
                                                                    ></v-text-field>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                        <template
                                                            v-slot:item.action="{
                                                                item,
                                                            }"
                                                        >
                                                            <div>
                                                                <v-hover
                                                                    v-slot:default="{
                                                                        hover,
                                                                    }"
                                                                >
                                                                    <v-badge
                                                                        :value="
                                                                            hover
                                                                        "
                                                                        color="deep-purple accent-4"
                                                                        content="Seleccionar"
                                                                        right
                                                                        transition="slide-x-transition"
                                                                    >
                                                                        <v-icon
                                                                            @click="
                                                                                seleccionarAgente(
                                                                                    item,
                                                                                )
                                                                            "
                                                                        >
                                                                            mdi-chevron-right-circle-outline
                                                                        </v-icon>
                                                                    </v-badge>
                                                                </v-hover>
                                                            </div>
                                                        </template>
                                                        <template v-slot:no-data
                                                            >No hay
                                                            agentes</template
                                                        >
                                                    </v-data-table>
                                                </v-card>
                                            </v-dialog>
                                            <v-form
                                                v-on:submit.prevent=""
                                                ref="form"
                                                v-model="valid"
                                                lazy-validation
                                            >
                                                <v-text-field
                                                    v-model="documento_rapido"
                                                    :counter="10"
                                                    :rules="documentoRules"
                                                    label="Documento"
                                                    :disabled="deshabilitado"
                                                    required
                                                ></v-text-field>
                                                <transition name="fade">
                                                    <v-alert
                                                        border="top"
                                                        color="red lighten-2"
                                                        dark
                                                        v-if="alert"
                                                        >{{ alertText }}
                                                    </v-alert>
                                                </transition>
                                                <transition name="fade">
                                                    <v-alert
                                                        border="top"
                                                        color="red lighten-2"
                                                        dark
                                                        v-if="alertHorario"
                                                        >Horario no encontrado
                                                    </v-alert>
                                                </transition>
                                                <transition name="fade">
                                                    <v-alert
                                                        border="top"
                                                        color="blue lighten-2"
                                                        dark
                                                        v-if="alertBaja"
                                                        >Agente dado de baja
                                                    </v-alert>
                                                </transition>
                                                <transition name="fade">
                                                    <v-alert
                                                        border="top"
                                                        color="red lighten-2"
                                                        dark
                                                        v-if="alertAlta"
                                                        >Fecha de Alta del
                                                        puesto no encontrado
                                                    </v-alert>
                                                </transition>
                                            </v-form>
                                            <div v-if="mostrarTarjetas">
                                                <v-simple-table dense>
                                                    <template v-slot:default>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <b
                                                                        >Documento</b
                                                                    >
                                                                </td>
                                                                <td>
                                                                    {{
                                                                        agente.documento
                                                                    }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <b
                                                                        >Nombre</b
                                                                    >
                                                                </td>
                                                                <td>
                                                                    {{
                                                                        agente.nombre
                                                                    }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <b
                                                                        >Apellido</b
                                                                    >
                                                                </td>
                                                                <td>
                                                                    {{
                                                                        agente.apellido
                                                                    }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <b
                                                                        >Fecha
                                                                        de
                                                                        Nacimiento</b
                                                                    >
                                                                </td>
                                                                <td>
                                                                    {{
                                                                        fechaNacimiento
                                                                    }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <b
                                                                        >Antiguedad</b
                                                                    >
                                                                </td>
                                                                <td>
                                                                    {{
                                                                        agente.antiguedad
                                                                            ? agente.antiguedad
                                                                            : 'No fue ingresada la antiguedad'
                                                                    }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </template>
                                                </v-simple-table>
                                            </div>
                                        </v-col>
                                    </v-row>
                                </div>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-col>

                <v-col v-if="mostrarTarjetas">
                    <v-card>
                        <v-card-title
                            class="font-weight-regular blue-grey white--text headline"
                            color="rgb(1,153,200)"
                        >
                            <v-icon
                                large
                                color="white"
                                >mdi-card-account-details
                            </v-icon>
                            <span class="white--text ml-4"
                                >Informacion del Puesto</span
                            >
                        </v-card-title>
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col>
                                        <v-simple-table dense>
                                            <template v-slot:default>
                                                <tbody>
                                                    <tr v-show="sancionURL">
                                                        <td>
                                                            <b>Fecha de Alta</b>
                                                        </td>
                                                        <td>
                                                            {{ fechaDesde }}
                                                        </td>
                                                    </tr>
                                                    <tr v-show="sancionURL">
                                                        <td>
                                                            <b>Fecha de Baja</b>
                                                        </td>
                                                        <td>
                                                            {{ fechaHasta }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Función</b></td>
                                                        <td>
                                                            {{ agente.funcion }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Planta</b></td>
                                                        <td>
                                                            {{ agente.planta }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Nivel</b></td>
                                                        <td>
                                                            {{ agente.nivel }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Efector</b></td>
                                                        <td>
                                                            {{ agente.efector }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Servicio</b></td>
                                                        <td>
                                                            {{
                                                                agente.codigo_nombre
                                                            }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </template>
                                        </v-simple-table>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-col>

                <v-col
                    v-if="mostrarTarjetas"
                    cols="3"
                >
                    <v-card>
                        <v-card-title
                            class="font-weight-regular blue-grey white--text headline"
                            color="rgb(1,153,200)"
                        >
                            <v-icon
                                large
                                color="white"
                                >mdi-account-clock
                            </v-icon>
                            <span class="white--text ml-4">
                                Inf. del Horario</span
                            >
                        </v-card-title>
                        <v-tabs
                            v-model="tab"
                            background-color="deep-blur accent-4"
                            centered
                            dark
                            icons-and-text
                        >
                            <v-tabs-slider></v-tabs-slider>

                            <v-tab
                                v-for="i in horarios.length"
                                :key="i"
                                :href="'#tab-' + i"
                                >Horario {{ i }}</v-tab
                            >
                        </v-tabs>

                        <v-tabs-items v-model="tab">
                            <v-tab-item
                                v-for="i in horarios.length"
                                :key="i"
                                :value="'tab-' + i"
                            >
                                <v-card flat>
                                    <v-card-text>
                                        <v-container>
                                            <v-simple-table dense>
                                                <template v-slot:default>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <b
                                                                    >Tipo
                                                                    Horario</b
                                                                >
                                                            </td>
                                                            <td>
                                                                {{
                                                                    'tipohorario' in
                                                                    horarios[
                                                                        i - 1
                                                                    ]
                                                                        ? horarios[
                                                                              i -
                                                                                  1
                                                                          ]
                                                                              .tipohorario
                                                                        : horarios[
                                                                              i -
                                                                                  1
                                                                          ]
                                                                              .tipodia_corto
                                                                }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <b
                                                                    >Horario
                                                                    Desde</b
                                                                >
                                                            </td>
                                                            <td>
                                                                {{
                                                                    horarios[
                                                                        i - 1
                                                                    ].hora_desde
                                                                }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <b
                                                                    >Horario
                                                                    Hasta</b
                                                                >
                                                            </td>
                                                            <td>
                                                                {{
                                                                    horarios[
                                                                        i - 1
                                                                    ].hora_hasta
                                                                }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <b
                                                                    >Dias de
                                                                    Guardia</b
                                                                >
                                                            </td>
                                                            <td>
                                                                {{
                                                                    horarios[
                                                                        i - 1
                                                                    ]
                                                                        .dias_guardia ===
                                                                    ''
                                                                        ? 'No tiene'
                                                                        : horarios[
                                                                              i -
                                                                                  1
                                                                          ]
                                                                              .dias_guardia
                                                                }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <b
                                                                    >Sistema
                                                                    Viejo de
                                                                    Horarios</b
                                                                >
                                                            </td>
                                                            <td>
                                                                {{
                                                                    'tipohorario' in
                                                                    horarios[
                                                                        i - 1
                                                                    ]
                                                                        ? 'Si'
                                                                        : 'No'
                                                                }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </template>
                                            </v-simple-table>
                                        </v-container>
                                    </v-card-text>
                                </v-card>
                            </v-tab-item>
                        </v-tabs-items>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
import _ from 'lodash';
import moment from 'moment';

export default {
    name: 'TarjetaAgente',
    created() {
        this.fetchFunciones();
        this.sancionURL = window.location.pathname.includes('sancion');
        this.$store.state.agente.agente = {};
        this.debouncedSearchAgente = _.debounce(this.fetchAgente, 400);
        this.debouncedValidate = _.debounce(this.validate, 400);
    },
    mounted() {
        this.validate();
    },
    props: {
        titulo: String,
        deshabilitado: Boolean,
    },
    data: () => ({
        tab: null,
        select: { value: 0, label: '' },
        dialog: false,
        filter: { documento: '', nombre: '', apellido: '', funcion: '' },
        documento: '',
        documento_rapido: '',
        nombre: '',
        apellido: '',
        funcion: '',
        mostrarTarjetas: false,
        alert: false,
        alertHorario: false,
        alertBaja: false,
        alertAlta: false,
        valid: true,
        sancionURL: false,
        documentoRules: [(v) => !!v || 'El Documento es requerido'],
        alertText: '',
    }),
    watch: {
        documento_rapido: function (newValue, oldValue) {
            this.documento = '';
            this.debouncedSearchAgente();
            this.debouncedValidate();
        },
    },
    computed: {
        getBlockSearch() {
            return this.$store.state.licencia.bloquearTarjetaAgente;
        },
        headers() {
            return [
                {
                    value: 'action',
                    text: 'Comandos',
                    sortable: false,
                },
                {
                    text: 'Documento',
                    align: 'start',
                    sortable: true,
                    value: 'documento',
                    filter: (value) => {
                        if (!this.filter.documento) return true;
                        return (
                            value.toString().indexOf(this.filter.documento) !==
                            -1
                        );
                    },
                },
                {
                    text: 'Nombre',
                    value: 'nombre',
                    filter: (value) => {
                        if (!this.filter.nombre) return true;
                        return (
                            value
                                .toString()
                                .indexOf(this.filter.nombre.toUpperCase()) !==
                            -1
                        );
                    },
                },
                {
                    text: 'Apellido',
                    value: 'apellido',
                    filter: (value) => {
                        if (!this.filter.apellido) return true;
                        return (
                            value
                                .toString()
                                .indexOf(this.filter.apellido.toUpperCase()) !==
                            -1
                        );
                    },
                },
                {
                    text: 'Funcion',
                    value: 'funcion',
                    filter: (value) => {
                        if (!this.filter.funcion) return true;
                        return (
                            value
                                .toString()
                                .indexOf(this.filter.funcion.toUpperCase()) !==
                            -1
                        );
                    },
                },
            ];
        },
        fechaNacimiento() {
            if (this.agente.idagente > 0) {
                var cadena = String(this.agente.fnacimiento);
                return new Date(
                    cadena.slice(0, 4),
                    cadena.slice(5, 7) - 1,
                    cadena.slice(8, 10),
                ).toLocaleDateString();
            }
            return '';
        },
        fechaDesde() {
            if (this.agente.idagente > 0) {
                var cadena =
                    this.agente.fdesde !== null
                        ? String(this.agente.fdesde)
                        : '';
                if (cadena !== '') {
                    return new Date(
                        cadena.slice(0, 4),
                        cadena.slice(5, 7) - 1,
                        cadena.slice(8, 10),
                    ).toLocaleDateString();
                } else {
                    return 'No tiene fecha de alta';
                }
            }
            return '';
        },
        fechaHasta() {
            if (this.agente.idagente > 0) {
                var cadena =
                    this.agente.fhasta !== null
                        ? String(this.agente.fhasta)
                        : '';
                console.log('fechas hasta: ', cadena);
                if (cadena !== '') {
                    return new Date(
                        cadena.slice(0, 4),
                        cadena.slice(5, 7) - 1,
                        cadena.slice(8, 10),
                    ).toLocaleDateString();
                } else {
                    return 'No tiene fecha de baja';
                }
            }
            return '';
        },
        agente() {
            return this.$store.getters['agente/agente'];
        },
        horarios() {
            return this.$store.getters['agente/horarios'];
        },
        agentes() {
            return this.$store.getters['agente/agentes'];
        },
        tipoFunciones() {
            return this.$store.getters['tipoFuncion/tipoFunciones'];
        },
        isAgente: function () {
            return this.$store.getters['agente/foundAgente'];
        },
        existeSancion() {
            return this.$store.getters['sancion/existe'];
        },
    },
    methods: {
        clean() {
            this.documento = '';
            this.nombre = '';
            this.apellido = '';
            this.select = {};
        },
        seleccionarAgente(item) {
            this.dialog = false;
            this.documento_rapido = item.documento;
        },
        validate() {
            console.log();
            this.$refs.form.validate();
        },
        reset() {
            this.$refs.form.reset();
        },
        resetValidation() {
            this.$refs.form.resetValidation();
        },
        async fetchFunciones() {
            await this.$store
                .dispatch('tipoFuncion/all')
                .then(() => {
                    console.info('Funciones retornadas con exito');
                })
                .catch((err) => {
                    console.error(`${err}`);
                });
        },
        // Busca al agente a traves del documento y si tiene sancion preexistente
        async fetchAgenteAdvance() {
            console.log(this.select.value);
            try {
                let dependencia = this.$store.getters['user/dependencia'];
                if (Object.keys(dependencia).length === 0) {
                    dependencia = this.$store.getters['user/dependencia_padre'];
                }
                await this.$store.dispatch('agente/getAgentesHijos', {
                    documento: this.documento,
                    nombre: this.nombre,
                    apellido: this.apellido,
                    funcion: this.select.value,
                    dependencia: dependencia.iddependencia,
                });
            } catch (e) {
                console.error(e);
            }
        },
        async fetchAgente() {
            this.debouncedValidate();
            try {
                let dependencia = this.$store.getters['user/dependencia'];
                if (Object.keys(dependencia).length === 0) {
                    dependencia = this.$store.getters['user/dependencia_padre'];
                }
                await this.$store.dispatch('agente/getAgenteRapido', {
                    documento: this.documento_rapido,
                    dependencia: dependencia.iddependencia,
                });
                this.tiene_sancion();
                if (this.isAgente) {
                    this.$emit('hayAgente', false);
                    if (
                        this.agente.tipohorario === '' ||
                        this.agente.tipohorario === null
                    ) {
                        this.showAlertHorario();
                        this.$emit('hayAgente', true);
                    }
                    if (
                        this.agente.fdesde === '' ||
                        this.agente.fdesde === null
                    ) {
                        this.showAlertAlta();
                    }
                    if (
                        this.agente.fhasta !== '' &&
                        this.agente.fhasta !== null
                    ) {
                        this.showAlertBaja();
                    }
                    this.mostrarTarjetas = true;
                } else {
                    console.error(
                        'Error al tratar de encontrar agente con dni: ' +
                            this.documento_rapido,
                    );
                    this.$emit('hayAgente', true);
                    this.mostrarTarjetas = false;
                    this.showAlert(
                        this.$store.getters['agente/errores_agente'],
                    );
                }
            } catch (e) {
                console.error(e);
            }
        },
        // Busca si es que hay sanciones de dos años atras
        async tiene_sancion() {
            await this.$store.dispatch(
                'sancion/existSancion',
                this.agente.idagente,
            );
        },
        showAlert(msg) {
            this.alert = !this.alert;
            this.alertText = msg;
            setTimeout(() => {
                this.alert = false;
            }, 2000);
        },
        showAlertHorario() {
            this.alertHorario = !this.alertHorario;
            //this.$store.state.agente.agente = {}
            setTimeout(() => {
                this.alertHorario = false;
            }, 2000);
        },
        showAlertAlta() {
            if (this.sancionURL) {
                this.alertAlta = !this.alertAlta;
                setTimeout(() => {
                    this.alertAlta = false;
                }, 2000);
            }
        },
        showAlertBaja() {
            if (this.sancionURL) {
                this.alertBaja = !this.alertBaja;
                setTimeout(() => {
                    this.alertBaja = false;
                }, 2000);
            }
        },
    },
};
</script>

<style scoped>
tr:nth-child(even) {
    background: #ccc;
}

tr:nth-child(odd) {
    background: #fff;
}

.siprosa {
    background-color: #0199c8;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
    opacity: 0;
}
</style>
