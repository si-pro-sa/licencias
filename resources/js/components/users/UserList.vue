<template>
    <v-container>
        <v-card>
            <v-card-title>
                Gestión de Usuarios
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Buscar"
                    single-line
                    hide-details
                    @input="debounceSearch"
                ></v-text-field>
            </v-card-title>

            <v-data-table
                :headers="headers"
                :items="users"
                :loading="loading"
                :server-items-length="pagination.total"
                :options.sync="options"
                class="elevation-1"
                @update:options="fetchData"
            >
                <template v-slot:top>
                    <v-toolbar flat>
                        <v-btn
                            color="primary"
                            dark
                            class="mb-2"
                            @click="openDialog()"
                        >
                            <v-icon left>mdi-plus</v-icon>
                            Nuevo Usuario
                        </v-btn>
                    </v-toolbar>
                </template>

                <!-- Columna de acciones -->
                <template v-slot:item.actions="{ item }">
                    <v-icon
                        small
                        class="mr-2"
                        @click="openDialog(item)"
                    >
                        mdi-pencil
                    </v-icon>
                    <v-icon
                        small
                        @click="deleteItem(item)"
                    >
                        mdi-delete
                    </v-icon>
                </template>

                <!-- Estado de carga -->
                <template v-slot:loading>
                    <v-skeleton-loader
                        v-for="n in 10"
                        :key="n"
                        type="list-item-avatar-two-line"
                    ></v-skeleton-loader>
                </template>

                <!-- Sin datos -->
                <template v-slot:no-data>
                    <v-alert
                        type="info"
                        outlined
                    >
                        No hay datos disponibles
                    </v-alert>
                </template>
            </v-data-table>
        </v-card>

        <!-- Diálogo para crear/editar usuario -->
        <v-dialog
            v-model="dialog"
            max-width="500px"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h5">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col
                                cols="12"
                                sm="6"
                            >
                                <v-text-field
                                    v-model="editedItem.name"
                                    label="Nombre"
                                    :error-messages="errors.name"
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                            >
                                <v-text-field
                                    v-model="editedItem.email"
                                    label="Email"
                                    :error-messages="errors.email"
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
                        @click="closeDialog"
                    >
                        Cancelar
                    </v-btn>
                    <v-btn
                        color="blue darken-1"
                        text
                        :loading="saving"
                        @click="saveItem"
                    >
                        Guardar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Diálogo de confirmación para eliminar -->
        <v-dialog
            v-model="deleteDialog"
            max-width="500px"
        >
            <v-card>
                <v-card-title class="text-h5"
                    >¿Está seguro que desea eliminar este usuario?</v-card-title
                >
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="deleteDialog = false"
                    >
                        Cancelar
                    </v-btn>
                    <v-btn
                        color="red darken-1"
                        text
                        :loading="deleting"
                        @click="confirmDelete"
                    >
                        Eliminar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import { debounce } from 'lodash';

export default {
    name: 'UserList',

    data() {
        return {
            // Configuración de tabla
            headers: [
                { text: 'ID', value: 'id' },
                { text: 'Nombre', value: 'name' },
                { text: 'Email', value: 'email' },
                { text: 'Acciones', value: 'actions', sortable: false },
            ],
            options: {
                page: 1,
                itemsPerPage: 15,
                sortBy: ['id'],
                sortDesc: [false],
            },
            search: '',

            // Diálogos
            dialog: false,
            deleteDialog: false,

            // Estado
            saving: false,
            deleting: false,
            errors: {},

            // Items para editar/eliminar
            editedIndex: -1,
            editedItem: {
                id: null,
                name: '',
                email: '',
            },
            defaultItem: {
                id: null,
                name: '',
                email: '',
            },
            itemToDelete: null,
        };
    },

    computed: {
        ...mapState('users', ['loading', 'error']),
        ...mapGetters('users', ['getAllUsers', 'getPagination']),

        formTitle() {
            return this.editedIndex === -1 ? 'Nuevo Usuario' : 'Editar Usuario';
        },

        users() {
            return this.getAllUsers;
        },

        pagination() {
            return this.getPagination;
        },
    },

    created() {
        this.fetchData();
    },

    methods: {
        ...mapActions('users', [
            'fetchUsers',
            'createUser',
            'updateUser',
            'deleteUser',
        ]),

        // Método para obtener datos con paginación y ordenamiento
        fetchData() {
            const { page, itemsPerPage, sortBy, sortDesc } = this.options;

            // Construir parámetros para la solicitud
            const params = {
                page,
                perPage: itemsPerPage,
                search: this.search,
            };

            // Añadir ordenamiento si existe
            if (sortBy.length > 0 && sortDesc.length > 0) {
                params.sortBy = sortBy[0];
                params.sortDir = sortDesc[0] ? 'desc' : 'asc';
            }

            this.fetchUsers(params).catch((error) => {
                // Mostrar error si no se maneja en otro lugar
                console.error('Error al cargar usuarios:', error);
            });
        },

        // Buscar con debounce para evitar muchas solicitudes
        debounceSearch: debounce(function () {
            this.options.page = 1; // Resetear a la primera página
            this.fetchData();
        }, 500),

        // Abrir diálogo para crear/editar
        openDialog(item) {
            this.errors = {};

            if (item) {
                this.editedIndex = this.users.indexOf(item);
                this.editedItem = Object.assign({}, item);
            } else {
                this.editedIndex = -1;
                this.editedItem = Object.assign({}, this.defaultItem);
            }

            this.dialog = true;
        },

        // Cerrar diálogo
        closeDialog() {
            this.dialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
                this.errors = {};
            });
        },

        // Guardar item (crear o actualizar)
        async saveItem() {
            this.saving = true;
            this.errors = {};

            try {
                if (this.editedIndex > -1) {
                    // Actualizar usuario existente
                    await this.updateUser({
                        id: this.editedItem.id,
                        data: this.editedItem,
                    });
                    this.$toast.success('Usuario actualizado correctamente');
                } else {
                    // Crear nuevo usuario
                    await this.createUser(this.editedItem);
                    this.$toast.success('Usuario creado correctamente');
                }

                this.closeDialog();
                this.fetchData(); // Refrescar datos
            } catch (error) {
                if (
                    error.name === 'ValidationError' &&
                    typeof error.errors === 'object'
                ) {
                    this.errors = error.errors;
                } else {
                    this.$toast.error(
                        error.message || 'Error al guardar usuario',
                    );
                }
            } finally {
                this.saving = false;
            }
        },

        // Abrir diálogo de confirmación para eliminar
        deleteItem(item) {
            this.itemToDelete = item;
            this.deleteDialog = true;
        },

        // Confirmar y ejecutar eliminación
        async confirmDelete() {
            this.deleting = true;

            try {
                await this.deleteUser(this.itemToDelete.id);
                this.$toast.success('Usuario eliminado correctamente');
                this.deleteDialog = false;
            } catch (error) {
                this.$toast.error(error.message || 'Error al eliminar usuario');
            } finally {
                this.deleting = false;
                this.itemToDelete = null;
            }
        },
    },
};
</script>
