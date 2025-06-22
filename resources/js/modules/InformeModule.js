import axios from 'axios';

const state = {
    informes: [],
    currentInforme: null,
    loading: false,
    error: null,
};

const mutations = {
    SET_INFORMES(state, informes) {
        state.informes = informes;
    },
    SET_CURRENT_INFORME(state, informe) {
        state.currentInforme = informe;
    },
    SET_LOADING(state, loading) {
        state.loading = loading;
    },
    SET_ERROR(state, error) {
        state.error = error;
    },
    ADD_INFORME(state, informe) {
        state.informes.push(informe);
    },
    UPDATE_INFORME(state, updatedInforme) {
        const index = state.informes.findIndex(
            (i) => i.informe_id === updatedInforme.informe_id,
        );
        if (index !== -1) {
            state.informes.splice(index, 1, updatedInforme);
        }
    },
    DELETE_INFORME(state, informeId) {
        state.informes = state.informes.filter(
            (i) => i.informe_id !== informeId,
        );
    },
};

const actions = {
    async fetchInformes({ commit }) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get('/api/informes');
            commit('SET_INFORMES', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al obtener los informes',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async fetchInforme({ commit }, informeId) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get(`/api/informes/${informeId}`);
            commit('SET_CURRENT_INFORME', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message || 'Error al obtener el informe',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async createInforme({ commit }, informeData) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.post('/api/informes', informeData);
            commit('ADD_INFORME', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message || 'Error al crear el informe',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async updateInforme({ commit }, { informeId, informeData }) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.put(
                `/api/informes/${informeId}`,
                informeData,
            );
            commit('UPDATE_INFORME', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al actualizar el informe',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async deleteInforme({ commit }, informeId) {
        try {
            commit('SET_LOADING', true);
            await axios.delete(`/api/informes/${informeId}`);
            commit('DELETE_INFORME', informeId);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message || 'Error al eliminar el informe',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },
};

const getters = {
    getAllInformes: (state) => state.informes,
    getCurrentInforme: (state) => state.currentInforme,
    isLoading: (state) => state.loading,
    getError: (state) => state.error,
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
