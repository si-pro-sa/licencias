import axios from 'axios';

const state = {
    formData: {
        entidadExaminadora: '',
        fechaExamen: '',
        apellidoNombre: '',
        dni: '',
        reparticionOrigen: '',
        modalidadAtencion: 'CONSULTORIO',
        diasJustificar: null,
        articulo: '',
        fechaDesde: '',
        fechaHasta: '',
        fechaAlta: '',
        fechaControl: '',
        observaciones: '',
        domicilio: '',
    },
    loading: false,
    error: null,
    success: false,
};

const mutations = {
    SET_FORM_DATA(state, data) {
        state.formData = { ...state.formData, ...data };
    },
    RESET_FORM(state) {
        state.formData = {
            entidadExaminadora: '',
            fechaExamen: '',
            apellidoNombre: '',
            dni: '',
            reparticionOrigen: '',
            modalidadAtencion: 'CONSULTORIO',
            diasJustificar: null,
            articulo: '',
            fechaDesde: '',
            fechaHasta: '',
            fechaAlta: '',
            fechaControl: '',
            observaciones: '',
            domicilio: '',
        };
    },
    SET_LOADING(state, loading) {
        state.loading = loading;
    },
    SET_ERROR(state, error) {
        state.error = error;
    },
    SET_SUCCESS(state, success) {
        state.success = success;
    },
};

const actions = {
    async submitForm({ commit, state }) {
        commit('SET_LOADING', true);
        commit('SET_ERROR', null);
        commit('SET_SUCCESS', false);

        try {
            const response = await axios.post('/api/informes', state.formData);
            commit('SET_SUCCESS', true);
            commit('RESET_FORM');
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al enviar el formulario',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },
    updateFormData({ commit }, data) {
        commit('SET_FORM_DATA', data);
    },
    resetForm({ commit }) {
        commit('RESET_FORM');
    },
};

const getters = {
    formData: (state) => state.formData,
    loading: (state) => state.loading,
    error: (state) => state.error,
    success: (state) => state.success,
    isValid: (state) => {
        return !!(
            state.formData.apellidoNombre &&
            state.formData.dni &&
            state.formData.diasJustificar &&
            state.formData.fechaDesde &&
            state.formData.fechaHasta
        );
    },
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
