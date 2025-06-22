/**
 * @file store/modules/users.js
 * @description Módulo Vuex para gestión de usuarios
 */
import userApi from '../../api/userApi';

// Estado
const state = {
    users: [],
    currentUser: null,
    loading: false,
    error: null,
    pagination: {
        total: 0,
        perPage: 15,
        currentPage: 1,
        lastPage: 1,
    },
};

// Mutaciones
const mutations = {
    SET_USERS(state, users) {
        state.users = users;
    },
    SET_CURRENT_USER(state, user) {
        state.currentUser = user;
    },
    SET_LOADING(state, status) {
        state.loading = status;
    },
    SET_ERROR(state, error) {
        state.error = error;
    },
    SET_PAGINATION(state, pagination) {
        state.pagination = pagination;
    },
};

// Acciones
const actions = {
    /**
     * Obtener todos los usuarios
     * @param {Object} context - Contexto de acción Vuex
     * @param {Object} params - Parámetros de consulta
     * @returns {Promise} Promesa que se resuelve cuando se obtienen los usuarios
     */
    fetchUsers({ commit }, params = {}) {
        commit('SET_LOADING', true);
        commit('SET_ERROR', null);

        return userApi
            .getUsers(params)
            .then((data) => {
                // Si los datos vienen en formato paginado
                if (data.data) {
                    commit('SET_USERS', data.data);

                    // Actualizar información de paginación
                    commit('SET_PAGINATION', {
                        total: data.total,
                        perPage: data.per_page,
                        currentPage: data.current_page,
                        lastPage: data.last_page,
                    });
                } else {
                    commit('SET_USERS', data);
                }

                return data;
            })
            .catch((error) => {
                commit('SET_ERROR', error.message);
                throw error;
            })
            .finally(() => {
                commit('SET_LOADING', false);
            });
    },

    /**
     * Obtener un usuario por ID
     * @param {Object} context - Contexto de acción Vuex
     * @param {number|string} id - ID del usuario
     * @returns {Promise} Promesa que se resuelve cuando se obtiene el usuario
     */
    fetchUser({ commit }, id) {
        commit('SET_LOADING', true);
        commit('SET_ERROR', null);

        return userApi
            .getUser(id)
            .then((data) => {
                commit('SET_CURRENT_USER', data);
                return data;
            })
            .catch((error) => {
                commit('SET_ERROR', error.message);
                throw error;
            })
            .finally(() => {
                commit('SET_LOADING', false);
            });
    },

    /**
     * Crear un nuevo usuario
     * @param {Object} context - Contexto de acción Vuex
     * @param {Object} userData - Datos del usuario
     * @returns {Promise} Promesa que se resuelve cuando se crea el usuario
     */
    createUser({ commit }, userData) {
        commit('SET_LOADING', true);
        commit('SET_ERROR', null);

        return userApi
            .createUser(userData)
            .then((data) => {
                return data;
            })
            .catch((error) => {
                commit('SET_ERROR', error.message);
                throw error;
            })
            .finally(() => {
                commit('SET_LOADING', false);
            });
    },

    /**
     * Actualizar un usuario
     * @param {Object} context - Contexto de acción Vuex
     * @param {Object} payload - Datos para actualizar
     * @param {number|string} payload.id - ID del usuario
     * @param {Object} payload.data - Datos actualizados
     * @returns {Promise} Promesa que se resuelve cuando se actualiza el usuario
     */
    updateUser({ commit }, { id, data }) {
        commit('SET_LOADING', true);
        commit('SET_ERROR', null);

        return userApi
            .updateUser(id, data)
            .then((responseData) => {
                // Si estamos viendo este usuario actualmente, actualizar el state
                if (state.currentUser && state.currentUser.id === id) {
                    commit('SET_CURRENT_USER', responseData);
                }
                return responseData;
            })
            .catch((error) => {
                commit('SET_ERROR', error.message);
                throw error;
            })
            .finally(() => {
                commit('SET_LOADING', false);
            });
    },

    /**
     * Eliminar un usuario
     * @param {Object} context - Contexto de acción Vuex
     * @param {number|string} id - ID del usuario
     * @returns {Promise} Promesa que se resuelve cuando se elimina el usuario
     */
    deleteUser({ commit, dispatch }, id) {
        commit('SET_LOADING', true);
        commit('SET_ERROR', null);

        return userApi
            .deleteUser(id)
            .then((data) => {
                // Refrescar la lista después de eliminar
                dispatch('fetchUsers');
                return data;
            })
            .catch((error) => {
                commit('SET_ERROR', error.message);
                throw error;
            })
            .finally(() => {
                commit('SET_LOADING', false);
            });
    },
};

// Getters
const getters = {
    getAllUsers: (state) => state.users,
    getCurrentUser: (state) => state.currentUser,
    isLoading: (state) => state.loading,
    getError: (state) => state.error,
    getPagination: (state) => state.pagination,
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
