import axios from 'axios';

const state = {
    observations: [],
    currentObservation: null,
    loading: false,
    error: null,
};

const mutations = {
    SET_OBSERVATIONS(state, observations) {
        state.observations = observations;
    },
    SET_CURRENT_OBSERVATION(state, observation) {
        state.currentObservation = observation;
    },
    SET_LOADING(state, loading) {
        state.loading = loading;
    },
    SET_ERROR(state, error) {
        state.error = error;
    },
    ADD_OBSERVATION(state, observation) {
        state.observations.push(observation);
    },
    UPDATE_OBSERVATION(state, updatedObservation) {
        const index = state.observations.findIndex(
            (o) =>
                o.fhir_observation_id ===
                updatedObservation.fhir_observation_id,
        );
        if (index !== -1) {
            state.observations.splice(index, 1, updatedObservation);
        }
    },
    DELETE_OBSERVATION(state, observationId) {
        state.observations = state.observations.filter(
            (o) => o.fhir_observation_id !== observationId,
        );
    },
};

const actions = {
    async fetchObservations({ commit }) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get('/api/fhir/observations');
            commit('SET_OBSERVATIONS', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al obtener las observaciones',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async fetchObservation({ commit }, observationId) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get(
                `/api/fhir/observations/${observationId}`,
            );
            commit('SET_CURRENT_OBSERVATION', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al obtener la observación',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async createObservation({ commit }, observationData) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.post(
                '/api/fhir/observations',
                observationData,
            );
            commit('ADD_OBSERVATION', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al crear la observación',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async updateObservation({ commit }, { observationId, observationData }) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.put(
                `/api/fhir/observations/${observationId}`,
                observationData,
            );
            commit('UPDATE_OBSERVATION', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al actualizar la observación',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async deleteObservation({ commit }, observationId) {
        try {
            commit('SET_LOADING', true);
            await axios.delete(`/api/fhir/observations/${observationId}`);
            commit('DELETE_OBSERVATION', observationId);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al eliminar la observación',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },
};

const getters = {
    getAllObservations: (state) => state.observations,
    getCurrentObservation: (state) => state.currentObservation,
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
