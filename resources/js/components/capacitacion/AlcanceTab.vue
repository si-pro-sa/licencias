<template>
    <div>
        <v-data-table
            :headers="headers"
            :items="rowData"
            sort-by="codigo"
            class="elevation-1"
        >
            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>Alcance</v-toolbar-title>
                    <v-divider class="mx-4" inset vertical></v-divider>
                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" max-width="500px">
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
                                        <v-col cols="12" sm="6" md="4">
                                            <v-text-field
                                                v-model="editedItem.codigo"
                                                label="Código"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="6" md="4">
                                            <v-text-field
                                                v-model="editedItem.descripcion"
                                                label="Descripción"
                                            ></v-text-field>
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
                                <v-btn color="blue darken-1" text @click="save">
                                    Guardar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <v-dialog v-model="dialogDelete" max-width="500px">
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
            <template v-slot:item.actions="{ item }">
                <v-icon small class="mr-2" @click="editItem(item)">
                    mdi-pencil
                </v-icon>
                <v-icon small @click="deleteItem(item)">
                    mdi-delete
                </v-icon>
            </template>
            <template v-slot:no-data>
                <v-btn color="primary" @click="initialize">
                    Actualizar
                </v-btn>
            </template>
        </v-data-table>
    </div>
</template>

<script>
export default {
    name: 'AlcanceTab',
    data: () => ({
        dialog: false,
        dialogDelete: false,
        headers: [
            {
                text: 'Código',
                align: 'start',
                sortable: false,
                value: 'codigo'
            },
            { text: 'Descripción', value: 'descripcion' },
            { text: 'Acciones', value: 'actions', sortable: false }
        ],
        rowData: [],
        editedIndex: -1,
        editedItem: {
            idAlcanceCapacitacion: '',
            codigo: '',
            descripcion: ''
        },
        defaultItem: {
            idAlcanceCapacitacion: '',
            codigo: '',
            descripcion: ''
        }
    }),

    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'Nuevo Alcance' : 'Editar Alcance'
        }
    },

    watch: {
        dialog(val) {
            val || this.close()
        },
        dialogDelete(val) {
            val || this.closeDelete()
        }
    },

    async created() {
        await this.initialize()
    },

    methods: {
        can(cadena) {
            var permisos = this.$store.getters['user/permisos']
            return _.findIndex(permisos, ['name', cadena]) >= 0 ? true : false
        },
        async initialize() {
            try {
                const res = await window.axios.get('api/alcance')
                if (res) {
                    this.rowData = res.data.data
                }
            } catch (ex) {
                console.error(ex)
            }
        },

        editItem(item) {
            this.editedIndex = this.rowData.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },

        deleteItem(item) {
            this.editedIndex = this.rowData.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },

        async deleteItemConfirm() {
            try {
                await window.axios.delete(
                    `api/alcance/${this.editedItem.idAlcanceCapacitacion}`
                )
            } catch (error) {
                console.error(error)
            } finally {
                await this.initialize()
                this.closeDelete()
            }
        },

        close() {
            this.dialog = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        closeDelete() {
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        async save() {
            try {
                if (this.editedIndex > -1) {
                    await window.axios.put(
                        `api/alcance/${this.editedItem.idAlcanceCapacitacion}`,
                        this.editedItem
                    )
                } else {
                    await window.axios.post(`/api/alcance`, this.editedItem)
                }
            } catch (error) {
                console.error(error)
            } finally {
                await this.initialize()
                this.close()
            }
        }
    }
}
</script>

<style lang="scss" scoped></style>
