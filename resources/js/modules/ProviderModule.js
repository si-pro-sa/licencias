import axios from 'axios';

const state = {
    providers: [],
    currentProvider: null,
    loading: false,
    error: null,
};

const mutations = {
    SET_PROVIDERS(state, providers) {
        state.providers = providers;
    },
    SET_CURRENT_PROVIDER(state, provider) {
        state.currentProvider = provider;
    },
    SET_LOADING(state, loading) {
        state.loading = loading;
    },
    SET_ERROR(state, error) {
        state.error = error;
    },
    ADD_PROVIDER(state, provider) {
        state.providers.push(provider);
    },
    UPDATE_PROVIDER(state, updatedProvider) {
        const index = state.providers.findIndex(
            (p) => p.fhir_provider_id === updatedProvider.fhir_provider_id,
        );
        if (index !== -1) {
            state.providers.splice(index, 1, updatedProvider);
        }
    },
    DELETE_PROVIDER(state, providerId) {
        state.providers = state.providers.filter(
            (p) => p.fhir_provider_id !== providerId,
        );
    },
};

const actions = {
    async fetchProviders({ commit }) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get('/api/fhir/providers');
            commit('SET_PROVIDERS', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al obtener los proveedores',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async fetchProvider({ commit }, providerId) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get(
                `/api/fhir/providers/${providerId}`,
            );
            commit('SET_CURRENT_PROVIDER', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al obtener el proveedor',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async createProvider({ commit }, providerData) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.post(
                '/api/fhir/providers',
                providerData,
            );
            commit('ADD_PROVIDER', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message || 'Error al crear el proveedor',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async updateProvider({ commit }, { providerId, providerData }) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.put(
                `/api/fhir/providers/${providerId}`,
                providerData,
            );
            commit('UPDATE_PROVIDER', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al actualizar el proveedor',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async deleteProvider({ commit }, providerId) {
        try {
            commit('SET_LOADING', true);
            await axios.delete(`/api/fhir/providers/${providerId}`);
            commit('DELETE_PROVIDER', providerId);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al eliminar el proveedor',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },
};

const getters = {
    getAllProviders: (state) => state.providers,
    getCurrentProvider: (state) => state.currentProvider,
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
