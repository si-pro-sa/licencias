import axios from 'axios';

const state = {
    encounters: [],
    currentEncounter: null,
    loading: false,
    error: null,
};

const mutations = {
    SET_ENCOUNTERS(state, encounters) {
        state.encounters = encounters;
    },
    SET_CURRENT_ENCOUNTER(state, encounter) {
        state.currentEncounter = encounter;
    },
    SET_LOADING(state, loading) {
        state.loading = loading;
    },
    SET_ERROR(state, error) {
        state.error = error;
    },
    ADD_ENCOUNTER(state, encounter) {
        state.encounters.push(encounter);
    },
    UPDATE_ENCOUNTER(state, updatedEncounter) {
        const index = state.encounters.findIndex(
            (e) => e.fhir_encounter_id === updatedEncounter.fhir_encounter_id,
        );
        if (index !== -1) {
            state.encounters.splice(index, 1, updatedEncounter);
        }
    },
    DELETE_ENCOUNTER(state, encounterId) {
        state.encounters = state.encounters.filter(
            (e) => e.fhir_encounter_id !== encounterId,
        );
    },
};

const actions = {
    async fetchEncounters({ commit }) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get('/api/fhir/encounters');
            commit('SET_ENCOUNTERS', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al obtener los encuentros',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async fetchEncounter({ commit }, encounterId) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get(
                `/api/fhir/encounters/${encounterId}`,
            );
            commit('SET_CURRENT_ENCOUNTER', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al obtener el encuentro',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async createEncounter({ commit }, encounterData) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.post(
                '/api/fhir/encounters',
                encounterData,
            );
            commit('ADD_ENCOUNTER', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message || 'Error al crear el encuentro',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async updateEncounter({ commit }, { encounterId, encounterData }) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.put(
                `/api/fhir/encounters/${encounterId}`,
                encounterData,
            );
            commit('UPDATE_ENCOUNTER', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al actualizar el encuentro',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async deleteEncounter({ commit }, encounterId) {
        try {
            commit('SET_LOADING', true);
            await axios.delete(`/api/fhir/encounters/${encounterId}`);
            commit('DELETE_ENCOUNTER', encounterId);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al eliminar el encuentro',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },
};

const getters = {
    getAllEncounters: (state) => state.encounters,
    getCurrentEncounter: (state) => state.currentEncounter,
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
