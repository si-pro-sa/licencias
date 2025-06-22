import axios from 'axios';

const state = {
    facilities: [],
    currentFacility: null,
    loading: false,
    error: null,
};

const mutations = {
    SET_FACILITIES(state, facilities) {
        state.facilities = facilities;
    },
    SET_CURRENT_FACILITY(state, facility) {
        state.currentFacility = facility;
    },
    SET_LOADING(state, loading) {
        state.loading = loading;
    },
    SET_ERROR(state, error) {
        state.error = error;
    },
    ADD_FACILITY(state, facility) {
        state.facilities.push(facility);
    },
    UPDATE_FACILITY(state, updatedFacility) {
        const index = state.facilities.findIndex(
            (f) => f.fhir_facility_id === updatedFacility.fhir_facility_id,
        );
        if (index !== -1) {
            state.facilities.splice(index, 1, updatedFacility);
        }
    },
    DELETE_FACILITY(state, facilityId) {
        state.facilities = state.facilities.filter(
            (f) => f.fhir_facility_id !== facilityId,
        );
    },
};

const actions = {
    async fetchFacilities({ commit }) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get('/api/fhir/facilities');
            commit('SET_FACILITIES', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al obtener las instalaciones',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async fetchFacility({ commit }, facilityId) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get(
                `/api/fhir/facilities/${facilityId}`,
            );
            commit('SET_CURRENT_FACILITY', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al obtener la instalación',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async createFacility({ commit }, facilityData) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.post(
                '/api/fhir/facilities',
                facilityData,
            );
            commit('ADD_FACILITY', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al crear la instalación',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async updateFacility({ commit }, { facilityId, facilityData }) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.put(
                `/api/fhir/facilities/${facilityId}`,
                facilityData,
            );
            commit('UPDATE_FACILITY', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al actualizar la instalación',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async deleteFacility({ commit }, facilityId) {
        try {
            commit('SET_LOADING', true);
            await axios.delete(`/api/fhir/facilities/${facilityId}`);
            commit('DELETE_FACILITY', facilityId);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al eliminar la instalación',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },
};

const getters = {
    getAllFacilities: (state) => state.facilities,
    getCurrentFacility: (state) => state.currentFacility,
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
