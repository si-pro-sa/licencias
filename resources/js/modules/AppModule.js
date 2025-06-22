/**
 * @file modules/AppModule.js
 * @description Módulo Vuex para manejar el estado global de la aplicación
 */

// Estado inicial
const state = {
    apiError: null,
    snackbar: {
        show: false,
        message: '',
        color: 'error', // 'success', 'info', 'warning', 'error'
        timeout: 5000,
    },
    loading: false,
};

// Mutaciones
const mutations = {
    /**
     * Establece un error de API
     * @param {Object} state - Estado del módulo
     * @param {Object} error - Información del error de API
     */
    SET_API_ERROR(state, error) {
        state.apiError = error;
    },

    /**
     * Limpia el error de API
     * @param {Object} state - Estado del módulo
     */
    CLEAR_API_ERROR(state) {
        state.apiError = null;
    },

    /**
     * Muestra un mensaje en el snackbar
     * @param {Object} state - Estado del módulo
     * @param {Object} payload - Configuración del snackbar
     */
    SET_SNACKBAR(state, payload) {
        state.snackbar = {
            show: true,
            message: payload.message || 'Ocurrió un error',
            color: payload.color || 'error',
            timeout: payload.timeout || 5000,
        };
    },

    /**
     * Oculta el snackbar
     * @param {Object} state - Estado del módulo
     */
    HIDE_SNACKBAR(state) {
        state.snackbar.show = false;
    },

    /**
     * Establece el estado de carga global
     * @param {Object} state - Estado del módulo
     * @param {boolean} isLoading - Estado de carga
     */
    SET_LOADING(state, isLoading) {
        state.loading = isLoading;
    },
};

// Acciones
const actions = {
    /**
     * Maneja un error de API
     * @param {Object} context - Contexto de la acción
     * @param {Object} error - Error de API
     */
    handleApiError({ commit }, error) {
        // Guardar el error en el estado
        commit('SET_API_ERROR', error);

        // Mostrar mensaje de error
        commit('SET_SNACKBAR', {
            message: error.mensaje || 'Ha ocurrido un error',
            color: 'error',
        });

        // Opcional: Registrar el error en un servicio externo
        console.error('Error de API:', error);
    },

    /**
     * Muestra un mensaje en el snackbar
     * @param {Object} context - Contexto de la acción
     * @param {Object} payload - Configuración del snackbar
     */
    showMessage({ commit }, payload) {
        commit('SET_SNACKBAR', payload);
    },
};

// Getters
const getters = {
    /**
     * Obtiene el error de API actual
     * @param {Object} state - Estado del módulo
     * @returns {Object|null} Error de API o null
     */
    getApiError: (state) => state.apiError,

    /**
     * Obtiene la configuración del snackbar
     * @param {Object} state - Estado del módulo
     * @returns {Object} Configuración del snackbar
     */
    getSnackbar: (state) => state.snackbar,

    /**
     * Verifica si la aplicación está cargando
     * @param {Object} state - Estado del módulo
     * @returns {boolean} Estado de carga
     */
    isLoading: (state) => state.loading,
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
