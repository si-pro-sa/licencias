<template>
    <div fluid>
        <v-row
            class="ml-9"
            id="contenedor"
        >
            <v-col>
                <v-data-table
                    ref="dataTableCapacitacionMasiva"
                    id="dataTableCapacitacionMasiva"
                    key="dataTableCapacitacionMasiva"
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
                    <template v-slot:item.fecha_evento_inicio="{ item }">
                        {{ item.fecha_evento_inicio | fechaAcomodada }}
                    </template>
                    <template v-slot:item.fecha_evento_final="{ item }"
                        >{{ item.fecha_evento_final | fechaAcomodada }}
                    </template>
                    <template v-slot:item.fecha_visado_primero="{ item }"
                        >{{ item.fecha_visado_primero | fechaAcomodada }}
                    </template>
                    <template v-slot:item.fecha_visado_segundo="{ item }"
                        >{{ item.fecha_visado_segundo | fechaAcomodada }}
                    </template>
                    <template v-slot:item.action="{ item }">
                        <v-hover v-slot:default="{ hover }">
                            <v-badge
                                :value="hover"
                                color="deep-purple accent-4"
                                content="Ver Datos de Licencia"
                                left
                                transition="slide-x-transition"
                            >
                                <v-icon
                                    @click="verLicencia(item)"
                                    small
                                    >mdi-file-document</v-icon
                                >
                            </v-badge>
                        </v-hover>
                        <v-hover v-slot:default="{ hover }">
                            <v-badge
                                :value="hover"
                                color="deep-purple accent-4"
                                content="Ver Datos de Capacitacion"
                                left
                                transition="slide-x-transition"
                            >
                                <v-icon
                                    @click="verCapacitacion(item)"
                                    small
                                    >mdi-certificate</v-icon
                                >
                            </v-badge>
                        </v-hover>
                        <v-hover v-slot:default="{ hover }">
                            <v-badge
                                :value="hover"
                                color="deep-purple accent-4"
                                content="Ver Curricula de Capacitacion"
                                left
                                transition="slide-x-transition"
                            >
                                <v-icon
                                    v-if="item.programa"
                                    small
                                    color="blue"
                                    @click="verPrograma(item.idCapacitacion)"
                                >
                                    mdi-notebook
                                </v-icon>
                            </v-badge>
                        </v-hover>
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
                                    v-model="filter.dias"
                                ></v-text-field>
                            </td>
                            <td></td>
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
                                    v-model="menu10"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="auto"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="
                                                computedFechaVisadoPrimeroFormatted
                                            "
                                            label="Filtro"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        locale="es-AR"
                                        v-model="filter.fecha_visado_primero"
                                        @input="menu10 = false"
                                    ></v-date-picker>
                                </v-menu>
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
                                <v-menu
                                    v-model="menu11"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="auto"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="
                                                computedFechaVisadoSegundoFormatted
                                            "
                                            label="Filtro"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        locale="es-AR"
                                        v-model="filter.fecha_visado_segundo"
                                        @input="menu11 = false"
                                    ></v-date-picker>
                                </v-menu>
                            </td>
                            <td>
                                <v-text-field
                                    label="Filtro"
                                    type="text"
                                    v-model="filter.idlicencia"
                                ></v-text-field>
                            </td>
                            <td>
                                <v-select
                                    label="Filtro"
                                    :items="caracterDropdown"
                                    :item-value="'descripcion'"
                                    :item-text="'descripcion'"
                                    v-model="filter.caracter"
                                ></v-select>
                            </td>
                            <td>
                                <v-select
                                    label="Filtro"
                                    :items="alcanceDropdown"
                                    :item-value="'descripcion'"
                                    :item-text="'descripcion'"
                                    v-model="filter.alcance"
                                ></v-select>
                            </td>
                            <td>
                                <v-select
                                    label="Filtro"
                                    :items="tipoEventoDropdown"
                                    :item-value="'descripcion'"
                                    :item-text="'descripcion'"
                                    v-model="filter.tipo_evento"
                                ></v-select>
                            </td>
                            <td>
                                <v-text-field
                                    label="Filtro"
                                    type="text"
                                    v-model="filter.evento_nombre"
                                ></v-text-field>
                            </td>
                            <td>
                                <v-text-field
                                    label="Filtro"
                                    type="text"
                                    v-model="filter.evento_lugar"
                                ></v-text-field>
                            </td>
                            <td>
                                <v-menu
                                    v-model="menu5"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="auto"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="
                                                computedFechaEventoInicioFormatted
                                            "
                                            label="Filtro"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        locale="es-AR"
                                        v-model="filter.fecha_evento_inicio"
                                        @input="menu5 = false"
                                    ></v-date-picker>
                                </v-menu>
                            </td>
                            <td>
                                <v-menu
                                    v-model="menu6"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="auto"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="
                                                computedFechaEventoFinalFormatted
                                            "
                                            label="Filtro"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        locale="es-AR"
                                        v-model="filter.fecha_evento_final"
                                        @input="menu6 = false"
                                    ></v-date-picker>
                                </v-menu>
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
                <v-dialog
                    v-model="dialogLicencia"
                    fullscreen
                    hide-overlay
                    transition="dialog-bottom-transition"
                >
                    <v-card>
                        <v-toolbar
                            dark
                            color="primary"
                        >
                            <v-btn
                                icon
                                dark
                                @click="dialogLicencia = false"
                            >
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                            <v-toolbar-title>Licencia</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-toolbar-items>
                                <v-btn
                                    dark
                                    text
                                    @click="dialogLicencia = false"
                                ></v-btn>
                            </v-toolbar-items>
                        </v-toolbar>

                        <v-card-text>
                            <v-list>
                                <v-list-item-group>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                >Fecha de inicio del
                                                pedido:</v-list-item-title
                                            >
                                            {{
                                                new Date(
                                                    licencia.fecha_pedido_inicio,
                                                ).toLocaleDateString()
                                            }}
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                >Fecha de fin del
                                                pedido:</v-list-item-title
                                            >
                                            {{
                                                new Date(
                                                    licencia.fecha_pedido_final,
                                                ).toLocaleDateString()
                                            }}
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-divider></v-divider>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                >Primer
                                                Visado:</v-list-item-title
                                            >
                                            {{
                                                licencia.primer_visado
                                                    ? 'Si'
                                                    : 'No'
                                            }}
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                >Fecha efectiva del
                                                inicio:</v-list-item-title
                                            >
                                            {{
                                                new Date(
                                                    licencia.fecha_efectiva_inicio,
                                                ).toLocaleDateString()
                                            }}
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                >Fecha efectiva del
                                                fin:</v-list-item-title
                                            >
                                            {{
                                                new Date(
                                                    licencia.fecha_efectiva_final,
                                                ).toLocaleDateString()
                                            }}
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-divider></v-divider>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                >Segundo
                                                Visado:</v-list-item-title
                                            >
                                            {{
                                                licencia.segundo_visado
                                                    ? 'Si'
                                                    : 'No'
                                            }}
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                >Fecha de
                                                Interrupcion:</v-list-item-title
                                            >
                                            {{
                                                licencia.fecha_interrupcion_inicio
                                                    ? new Date(
                                                          licencia.fecha_interrupcion_inicio,
                                                      ).toLocaleDateString()
                                                    : 'No Interrumpida'
                                            }}
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list-item-group>
                            </v-list>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="green darken-1"
                                block
                                text
                                @click="dialogLicencia = false"
                            >
                                Cerrar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <v-dialog
                    v-model="dialogCapacitacion"
                    fullscreen
                    hide-overlay
                    transition="dialog-bottom-transition"
                >
                    <v-card>
                        <v-toolbar
                            dark
                            color="primary"
                        >
                            <v-btn
                                icon
                                dark
                                @click="dialogCapacitacion = false"
                            >
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                            <v-toolbar-title>Capacitacion</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-toolbar-items>
                                <v-btn
                                    dark
                                    text
                                    @click="dialogCapacitacion = false"
                                ></v-btn>
                            </v-toolbar-items>
                        </v-toolbar>

                        <v-card-text>
                            <v-list>
                                <v-list-item-group>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                >Fecha de inicio del
                                                evento:</v-list-item-title
                                            >
                                            {{
                                                new Date(
                                                    capacitacion.fecha_evento_inicio,
                                                ).toLocaleDateString()
                                            }}
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                >Fecha de fin del
                                                evento:</v-list-item-title
                                            >
                                            {{
                                                new Date(
                                                    capacitacion.fecha_evento_final,
                                                ).toLocaleDateString()
                                            }}
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-divider></v-divider>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                >Caracter:</v-list-item-title
                                            >
                                            {{ capacitacion.caracter }}
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                >Alcance:</v-list-item-title
                                            >
                                            {{ capacitacion.alcance }}
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-divider></v-divider>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                >Tipo de
                                                Evento:</v-list-item-title
                                            >
                                            {{ capacitacion.tipo_evento }}
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                >Nombre del
                                                Evento:</v-list-item-title
                                            >
                                            {{ capacitacion.evento_nombre }}
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                >Lugar del
                                                Evento:</v-list-item-title
                                            >
                                            {{ capacitacion.evento_lugar }}
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list-item-group>
                            </v-list>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="green darken-1"
                                block
                                text
                                @click="dialogCapacitacion = false"
                            >
                                Cerrar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import moment from 'moment';
import _ from 'lodash';
export default {
    name: 'TablaMasivaCapacitacion',
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
            capacitacion: {},
            licencia: {},
            dialogLicencia: false,
            dialogCapacitacion: false,
            loading: false,
            menu: false,
            menu2: false,
            menu3: false,
            menu4: false,
            menu5: false,
            menu6: false,
            menu10: false,
            manu11: false,
            selected: [],
            error: false,
            filter: {
                efector: '',
                codigo_nombre: '',
                documento: '',
                apellido_nombre: '',
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
                caracter: '',
                alcance: '',
                tipo_evento: '',
                evento_nombre: '',
                evento_lugar: '',
                fecha_evento_inicio: '',
                fecha_evento_final: '',
                fecha_visado_primero: '',
                fecha_visado_segundo: '',
            },
            licTitulo: 'Resumen de Licencias',
            options: {},
            totalLicencias: 0,
            alcanceDropdown: [],
            caracterDropdown: [],
            tipoEventoDropdown: [],
        };
    },
    async created() {
        this.debouncedSearch = _.debounce(this.buscarLicencias, 400);
        try {
            const resAlcance = await axios.get('api/alcance');
            const resCaracter = await axios.get('api/caracter');
            const resTipoEvento = await axios.get('api/tipo-evento');
            if (resAlcance && resCaracter && resTipoEvento) {
                this.alcanceDropdown = resAlcance.data.data;
                this.caracterDropdown = resCaracter.data.data;
                this.tipoEventoDropdown = resTipoEvento.data.data;
            }
        } catch (ex) {
            console.error(ex);
        }
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
        computedFechaEventoInicioFormatted() {
            return this.formatDate(this.filter.fecha_evento_inicio);
        },
        computedFechaEventoFinalFormatted() {
            return this.formatDate(this.filter.fecha_evento_final);
        },
        computedFechaVisadoPrimeroFormatted() {
            return this.formatDate(this.filter.fecha_visado_primero);
        },
        computedFechaVisadoSegundoFormatted() {
            return this.formatDate(this.filter.fecha_visado_segundo);
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
        permisosArray() {
            return this.$store.getters['user/permisos'].map((el) => el.name);
        },
        licencias() {
            return this.$store.getters[
                'licencia/licencias_dependientes_capacitacion'
            ];
        },
        licencias_masivo_total() {
            return this.$store.getters[
                'licencia/licencias_masivo_total_capacitacion'
            ];
        },
        headers() {
            return [
                {
                    value: 'efector',
                    text: 'Efector',
                    sortable: true,
                    width: '300',
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
                    value: 'action',
                    text: 'Comandos',
                    sortable: false,
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
                    value: 'fecha_visado_primero',
                    text: 'Fecha Primer Visado',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.fecha_visado_primero) return true;
                        value = moment(value, 'YYYY-MM-DD').format(
                            'DD/MM/YYYY',
                        );
                        return (
                            value
                                .toString()
                                .indexOf(this.filter.fecha_visado_primero) !==
                            -1
                        );
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
                    value: 'fecha_visado_segundo',
                    text: 'Fecha Segundo Visado',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.fecha_visado_segundo) return true;
                        value = moment(value, 'YYYY-MM-DD').format(
                            'DD/MM/YYYY',
                        );
                        return (
                            value
                                .toString()
                                .indexOf(this.filter.fecha_visado_segundo) !==
                            -1
                        );
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
                {
                    value: 'caracter',
                    text: 'Caracter',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.caracter) return true;
                        return (
                            value.toString().indexOf(this.filter.caracter) !==
                            -1
                        );
                    },
                },
                {
                    value: 'alcance',
                    text: 'Alcance',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.alcance) return true;
                        return (
                            value.toString().indexOf(this.filter.alcance) !== -1
                        );
                    },
                },
                {
                    value: 'tipo_evento',
                    text: 'Tipo de Evento',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.tipo_evento) return true;
                        return (
                            value
                                .toString()
                                .indexOf(this.filter.tipo_evento) !== -1
                        );
                    },
                },
                {
                    value: 'evento_nombre',
                    text: 'Nombre de evento',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.evento_nombre) return true;
                        return (
                            value
                                .toString()
                                .indexOf(this.filter.evento_nombre) !== -1
                        );
                    },
                },
                {
                    value: 'evento_lugar',
                    text: 'Lugar del Evento',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.evento_lugar) return true;
                        return (
                            value
                                .toString()
                                .indexOf(this.filter.evento_lugar) !== -1
                        );
                    },
                },
                {
                    value: 'fecha_evento_inicio',
                    text: 'Inicio de Evento',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.fecha_evento_inicio) return true;
                        value = moment(value, 'YYYY-MM-DD').format(
                            'DD/MM/YYYY',
                        );
                        return (
                            value
                                .toString()
                                .indexOf(this.filter.fecha_evento_inicio) !== -1
                        );
                    },
                },
                {
                    value: 'fecha_evento_final',
                    text: 'Finalizacion del Evento',
                    sortable: true,
                    filter: (value) => {
                        if (!this.filter.fecha_evento_final) return true;
                        value = moment(value, 'YYYY-MM-DD').format(
                            'DD/MM/YYYY',
                        );
                        return (
                            value
                                .toString()
                                .indexOf(this.filter.fecha_evento_final) !== -1
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
            console.log('buscando licencias');
            console.log(this.filter);
            await this.$store
                .dispatch('licencia/getLicenciasDependientesCapacitacion', {
                    idagente: this.agente.idagente,
                    options: this.options,
                    filters: this.filter,
                    hijos: this.hijos,
                    dependencia: this.dependencia?.iddependencia
                        ? this.dependencia.iddependencia
                        : this.dependencia_padre?.iddependencia,
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
        verCapacitacion(item) {
            this.dialogCapacitacion = true;
            this.capacitacion = item;
        },
        verLicencia(item) {
            this.dialogLicencia = true;
            this.licencia = item;
        },
        async verPrograma(item) {
            try {
                const url = await this.$store.dispatch(
                    'capacitacion/getProgramaURL',
                    item,
                );

                console.log(url);
                if (url) {
                    window.open(url, '_blank');
                } else {
                    // Manejar el caso en que no se recibe una URL
                    console.error('No se recibió una URL válida');
                }
            } catch (error) {
                console.error(error);
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
.v-data-table {
    overflow-x: auto !important;
}
</style>
