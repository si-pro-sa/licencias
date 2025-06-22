import axios from 'axios';

const state = {
    locations: [],
    currentLocation: null,
    loading: false,
    error: null,
};

const mutations = {
    SET_LOCATIONS(state, locations) {
        state.locations = locations;
    },
    SET_CURRENT_LOCATION(state, location) {
        state.currentLocation = location;
    },
    SET_LOADING(state, loading) {
        state.loading = loading;
    },
    SET_ERROR(state, error) {
        state.error = error;
    },
    ADD_LOCATION(state, location) {
        state.locations.push(location);
    },
    UPDATE_LOCATION(state, updatedLocation) {
        const index = state.locations.findIndex(
            (l) => l.fhir_location_id === updatedLocation.fhir_location_id,
        );
        if (index !== -1) {
            state.locations.splice(index, 1, updatedLocation);
        }
    },
    DELETE_LOCATION(state, locationId) {
        state.locations = state.locations.filter(
            (l) => l.fhir_location_id !== locationId,
        );
    },
};

const actions = {
    async fetchLocations({ commit }) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get('/api/fhir/locations');
            commit('SET_LOCATIONS', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al obtener las ubicaciones',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async fetchLocation({ commit }, locationId) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get(
                `/api/fhir/locations/${locationId}`,
            );
            commit('SET_CURRENT_LOCATION', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al obtener la ubicación',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async createLocation({ commit }, locationData) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.post(
                '/api/fhir/locations',
                locationData,
            );
            commit('ADD_LOCATION', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message || 'Error al crear la ubicación',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async updateLocation({ commit }, { locationId, locationData }) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.put(
                `/api/fhir/locations/${locationId}`,
                locationData,
            );
            commit('UPDATE_LOCATION', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al actualizar la ubicación',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async deleteLocation({ commit }, locationId) {
        try {
            commit('SET_LOADING', true);
            await axios.delete(`/api/fhir/locations/${locationId}`);
            commit('DELETE_LOCATION', locationId);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al eliminar la ubicación',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },
};

const getters = {
    getAllLocations: (state) => state.locations,
    getCurrentLocation: (state) => state.currentLocation,
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
