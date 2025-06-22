<template>
    <div>
        <v-data-table
            :search="search"
            :headers="headers"
            :items="rowData"
            sort-by="fecha_evento_inicio"
            class="elevation-1"
        >
            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>Capacitacion</v-toolbar-title>
                    <v-divider
                        class="mx-4"
                        inset
                        vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <v-text-field
                        v-model="search"
                        label="Busqueda"
                        append-icon="mdi-magnify"
                        single-line
                        hide-details
                        class="mx-4"
                    ></v-text-field>
                    <v-dialog
                        v-model="dialog"
                        v-if="can('crear-capacitacion')"
                        max-width="500px"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                color="primary"
                                dark
                                class="mb-2"
                                v-bind="attrs"
                                v-on="on"
                            >
                                <v-icon left>mdi-plus</v-icon>Nuevo
                            </v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="text-h5">{{ formTitle }}</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col
                                            cols="12"
                                            sm="12"
                                            md="12"
                                        >
                                            <v-text-field
                                                v-model="
                                                    editedItem.evento_nombre
                                                "
                                                label="Nombre del Evento"
                                                @input="
                                                    toUpperCase('evento_nombre')
                                                "
                                            ></v-text-field>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="12"
                                            md="12"
                                        >
                                            <v-text-field
                                                v-model="
                                                    editedItem.evento_lugar
                                                "
                                                label="Lugar del Evento"
                                                @input="
                                                    toUpperCase('evento_lugar')
                                                "
                                            ></v-text-field>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="12"
                                            md="12"
                                        >
                                            <v-text-field
                                                v-model="editedItem.razon"
                                                label="Informacion Adic."
                                            ></v-text-field>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="12"
                                            md="12"
                                        >
                                            <v-menu
                                                v-model="menu1"
                                                :close-on-content-click="false"
                                                transition="scale-transition"
                                                offset-y
                                                max-width="290px"
                                                min-width="auto"
                                            >
                                                <template
                                                    v-slot:activator="{
                                                        on,
                                                        attrs,
                                                    }"
                                                >
                                                    <v-text-field
                                                        v-model="
                                                            computedDateFormattedStart
                                                        "
                                                        label="Fecha de Inicio"
                                                        prepend-icon="mdi-calendar"
                                                        readonly
                                                        v-bind="attrs"
                                                        v-on="on"
                                                    ></v-text-field>
                                                </template>
                                                <v-date-picker
                                                    v-model="
                                                        editedItem.fecha_evento_inicio
                                                    "
                                                    no-title
                                                    @input="menu1 = false"
                                                ></v-date-picker>
                                            </v-menu>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="12"
                                            md="12"
                                        >
                                            <v-menu
                                                v-model="menu2"
                                                :close-on-content-click="false"
                                                transition="scale-transition"
                                                offset-y
                                                max-width="290px"
                                                min-width="auto"
                                            >
                                                <template
                                                    v-slot:activator="{
                                                        on,
                                                        attrs,
                                                    }"
                                                >
                                                    <v-text-field
                                                        v-model="
                                                            computedDateFormattedEnd
                                                        "
                                                        label="Fecha de Finalización"
                                                        prepend-icon="mdi-calendar"
                                                        readonly
                                                        v-bind="attrs"
                                                        v-on="on"
                                                    ></v-text-field>
                                                </template>
                                                <v-date-picker
                                                    v-model="
                                                        editedItem.fecha_evento_final
                                                    "
                                                    no-title
                                                    @input="menu2 = false"
                                                ></v-date-picker>
                                            </v-menu>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="12"
                                            md="12"
                                        >
                                            <v-select
                                                v-model="
                                                    editedItem.idTipoEvento
                                                "
                                                :items="tipo_eventos"
                                                label="Tipo de Evento"
                                                item-text="descripcion"
                                                item-value="idTipoEvento"
                                            ></v-select>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="12"
                                            md="12"
                                        >
                                            <v-select
                                                v-model="
                                                    editedItem.idAlcanceCapacitacion
                                                "
                                                :items="alcances"
                                                item-text="descripcion"
                                                item-value="idAlcanceCapacitacion"
                                                label="Alcance"
                                            ></v-select>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="12"
                                            md="12"
                                        >
                                            <v-file-input
                                                v-model="editedItem.file"
                                                label="Subir Programa"
                                                prepend-icon="mdi-paperclip"
                                                accept=".pdf,.doc,.docx,.txt,.png,.jpg,.jpeg"
                                            ></v-file-input>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="close"
                                >
                                    Cancelar
                                </v-btn>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="save"
                                >
                                    Guardar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <v-dialog
                        v-model="dialogDelete"
                        max-width="500px"
                    >
                        <v-card>
                            <v-card-title class="text-h5"
                                >Esta seguro de borrar este
                                Alcance?</v-card-title
                            >
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="closeDelete"
                                    >Cancelar</v-btn
                                >
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="deleteItemConfirm"
                                    >Aceptar</v-btn
                                >
                                <v-spacer></v-spacer>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
            </template>
            <template v-slot:item.fecha_evento_inicio="{ item }">
                {{ item.fecha_evento_inicio | filterDate }}
            </template>
            <template v-slot:item.fecha_evento_final="{ item }">
                {{ item.fecha_evento_final | filterDate }}
            </template>

            <template v-slot:item.actions="{ item }">
                <div>
                    <v-hover v-slot:default="{ hover }">
                        <v-badge
                            :value="hover"
                            color="deep-purple accent-4"
                            content="Copiar/Seleccionar Capacitacion"
                            left
                            transition="slide-x-transition"
                        >
                            <v-icon
                                small
                                class="mr-2"
                                @click="seleccionar(item)"
                            >
                                mdi-cursor-default-click
                            </v-icon>
                        </v-badge></v-hover
                    >
                </div>
                <div v-if="can('editar-capacitacion')">
                    <v-hover v-slot:default="{ hover }">
                        <v-badge
                            :value="hover"
                            color="deep-purple accent-4"
                            content="Editar Capacitacion"
                            left
                            transition="slide-x-transition"
                        >
                            <v-icon
                                small
                                class="mr-2"
                                @click="editItem(item)"
                            >
                                mdi-pencil
                            </v-icon>
                        </v-badge>
                    </v-hover>
                </div>
                <div v-if="can('borrar-capacitacion')">
                    <v-hover v-slot:default="{ hover }">
                        <v-badge
                            :value="hover"
                            color="deep-purple accent-4"
                            content="Borrar Capacitacion"
                            left
                            transition="slide-x-transition"
                        >
                            <v-icon
                                small
                                @click="deleteItem(item)"
                            >
                                mdi-delete
                            </v-icon>
                        </v-badge>
                    </v-hover>
                </div>
                <div>
                    <v-hover v-slot:default="{ hover }">
                        <v-badge
                            :value="hover"
                            color="deep-purple accent-4"
                            content="Ver Programa"
                            left
                            transition="slide-x-transition"
                        >
                            <v-icon
                                v-if="item.programa"
                                small
                                color="blue"
                                @click="getProgramaUrl(item.idCapacitacion)"
                            >
                                mdi-notebook
                            </v-icon>
                        </v-badge>
                    </v-hover>
                </div>
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
        <v-dialog
            v-model="dialogPrograma"
            max-width="500px"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h5">Subir Programa</span>
                </v-card-title>

                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col
                                cols="12"
                                sm="12"
                                md="12"
                            >
                                <v-file-input
                                    v-model="editedItem.file"
                                    label="Subir Programa"
                                    prepend-icon="mdi-paperclip"
                                    accept=".pdf,.doc,.docx,.txt,.png,.jpg,.jpeg"
                                ></v-file-input>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="cerrarDialogPrograma"
                    >
                        Cancelar
                    </v-btn>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="guardarPrograma"
                    >
                        Guardar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    name: 'CapacitacionTab',
    data: (vm) => ({
        search: '',
        dateFormattedStart: vm.formatDate(
            new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
                .toISOString()
                .substr(0, 10),
        ),
        dateFormattedEnd: vm.formatDate(
            new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
                .toISOString()
                .substr(0, 10),
        ),
        menu1: false,
        menu2: false,

        dialog: false,
        dialogPrograma: false,
        dialogDelete: false,
        headers: [
            {
                text: 'Nombre',
                value: 'evento_nombre',
                sortable: false,
                align: 'start',
            },
            { text: 'Lugar', value: 'evento_lugar' },
            { text: 'Información Adic.', value: 'razon' },
            { text: 'Fecha de Inicio', value: 'fecha_evento_inicio' },
            { text: 'Fecha de Finalizacion', value: 'fecha_evento_final' },
            { text: 'Evento', value: 'tipo_evento' },
            { text: 'Alcance', value: 'alcance' },
            { text: 'Acciones', value: 'actions', sortable: false },
        ],
        editedIndex: -1,
        editedItem: {
            idCapacitacion: 0,
            resolucion: '',
            evento_nombre: '',
            evento_lugar: '',
            razon: '',
            fecha_evento_inicio: new Date(
                Date.now() - new Date().getTimezoneOffset() * 60000,
            )
                .toISOString()
                .substr(0, 10),
            fecha_evento_final: new Date(
                Date.now() - new Date().getTimezoneOffset() * 60000,
            )
                .toISOString()
                .substr(0, 10),
            idCaracter: 0,
            idTipoEvento: 0,
            idAlcanceCapacitacion: 0,
            file: null,
            filename: null,
        },
        defaultItem: {
            idCapacitacion: 0,
            resolucion: '',
            evento_nombre: '',
            evento_lugar: '',
            razon: '',
            fecha_evento_inicio: new Date(
                Date.now() - new Date().getTimezoneOffset() * 60000,
            )
                .toISOString()
                .substr(0, 10),
            fecha_evento_final: new Date(
                Date.now() - new Date().getTimezoneOffset() * 60000,
            )
                .toISOString()
                .substr(0, 10),
            idCaracter: 0,
            idTipoEvento: 0,
            idAlcanceCapacitacion: 0,
            file: null,
            filename: null,
        },
    }),
    filters: {
        filterDate: function (date) {
            if (!date) return null;

            const [year, month, day] = date.substr(0, 10).split('-');
            return `${day}/${month}/${year}`;
        },
    },

    computed: {
        rowData() {
            return this.$store.getters['capacitacion/capacitaciones'];
        },
        alcances() {
            return this.$store.getters['alcance/alcances'];
        },
        tipo_eventos() {
            return this.$store.getters['tipoEvento/tipoEventos'];
        },
        agente() {
            return this.$store.getters['user/agente'];
        },
        formTitle() {
            return this.editedIndex === -1
                ? 'Nuevo Capacitación'
                : 'Editar Capacitación';
        },
        computedDateFormattedStart() {
            return this.formatDate(this.editedItem.fecha_evento_inicio);
        },
        computedDateFormattedEnd() {
            return this.formatDate(this.editedItem.fecha_evento_final);
        },
    },

    watch: {
        async dialog(val) {
            await this.initialize();
            val || this.close();
        },
        dialogDelete(val) {
            val || this.closeDelete();
        },
        ['editedItem.fecha_evento_inicio'](val) {
            this.dateFormattedStart = this.formatDate(val);
        },
        ['editedItem.fecha_evento_final'](val) {
            this.dateFormattedEnd = this.formatDate(val);
        },
    },

    async created() {
        await this.initialize();
    },

    methods: {
        can(cadena) {
            var permisos = this.$store.getters['user/permisos'];
            return _.findIndex(permisos, ['name', cadena]) >= 0 ? true : false;
        },
        seleccionar(evt) {
            console.log(evt);
            this.$emit('seleccionada', evt);
        },
        formatDate(date) {
            if (!date) return null;

            const [year, month, day] = date.split('-');
            return `${day}/${month}/${year}`;
        },
        async initialize() {
            try {
                await this.$store.dispatch('alcance/all');
                await this.$store.dispatch('caracter/all');
                await this.$store.dispatch('tipoEvento/all');
                await this.$store.dispatch('capacitacion/all');
            } catch (ex) {
                console.error(ex);
            }
        },

        uploadPrograma(item) {
            this.editedIndex = this.rowData.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialogPrograma = true;
        },

        editItem(item) {
            item.fecha_evento_inicio = new Date(item.fecha_evento_inicio)
                .toISOString()
                .substr(0, 10);
            item.fecha_evento_final = new Date(item.fecha_evento_final)
                .toISOString()
                .substr(0, 10);
            this.editedIndex = this.rowData.indexOf(item);
            this.editedItem = Object.assign({}, item);
            console.log(item);
            this.dialog = true;
        },

        deleteItem(item) {
            this.editedIndex = this.rowData.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialogDelete = true;
        },
        cerrarDialogPrograma() {
            this.dialogPrograma = false;
        },

        async deleteItemConfirm() {
            try {
                await this.$store.dispatch(
                    'capacitacion/delete',
                    this.editedItem.idCapacitacion,
                );
            } catch (error) {
                console.error(error);
            } finally {
                this.initialize();
                this.closeDelete();
            }
        },

        close() {
            this.dialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },

        closeDelete() {
            this.dialogDelete = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },

        async save() {
            this.loading = true;
            const formData = new FormData();
            Object.keys(this.editedItem).forEach((key) => {
                if (this.editedItem[key] !== null) {
                    formData.append(key, this.editedItem[key]);
                }
            });
            formData.append('idagente', this.agente.idagente);
            try {
                if (this.editedIndex > -1) {
                    await this.$store.dispatch('capacitacion/update', formData);
                    this.successMessage = 'Capacitación actualizada';
                } else {
                    await this.$store.dispatch('capacitacion/post', formData);
                    this.successMessage = 'Capacitación creada';
                }
                this.close();
            } catch (error) {
                console.error('Error al guardar capacitación: ', error);
                this.errorMessage =
                    'Fallo el guardado de capacitación. Por favor trata de nuevo.';
            } finally {
                this.loading = false;
            }
        },
        async guardarPrograma() {
            try {
                const formData = new FormData();
                formData.append('file', this.editedItem.file);
                formData.append('filename', this.editedItem.filename);
                formData.append(
                    'idCapacitacion',
                    this.editedItem.idCapacitacion,
                );
                const result = await this.$store.dispatch(
                    'capacitacion/uploadPrograma',
                    formData,
                );
            } catch (error) {
                console.error(error);
            }
            this.dialogPrograma = false;
            await this.$store.dispatch('capacitacion/all');
            this.$nextTick(async () => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },
        onFileChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length) return;
            this.editedItem.file = files[0];
            this.editedItem.filename = files[0].name;
        },
        async getProgramaUrl(item) {
            try {
                const url = await this.$store.dispatch(
                    'capacitacion/getProgramaURL',
                    item,
                );

                console.log(url);
                if (url) {
                    window.open(url, '_blank');
                } else {
                    console.error('No se recibió una URL válida');
                }
            } catch (error) {
                console.error(error);
            }
        },
        toUpperCase(field) {
            this.editedItem[field] = this.editedItem[field].toUpperCase();
        },
    },
};
</script>

<style lang="scss" scoped></style>
