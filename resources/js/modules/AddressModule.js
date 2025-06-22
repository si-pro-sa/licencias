import axios from 'axios';

const state = {
    addresses: [],
    currentAddress: null,
    loading: false,
    error: null,
};

const mutations = {
    SET_ADDRESSES(state, addresses) {
        state.addresses = addresses;
    },
    SET_CURRENT_ADDRESS(state, address) {
        state.currentAddress = address;
    },
    SET_LOADING(state, loading) {
        state.loading = loading;
    },
    SET_ERROR(state, error) {
        state.error = error;
    },
    ADD_ADDRESS(state, address) {
        state.addresses.push(address);
    },
    UPDATE_ADDRESS(state, updatedAddress) {
        const index = state.addresses.findIndex(
            (a) => a.fhir_address_id === updatedAddress.fhir_address_id,
        );
        if (index !== -1) {
            state.addresses.splice(index, 1, updatedAddress);
        }
    },
    DELETE_ADDRESS(state, addressId) {
        state.addresses = state.addresses.filter(
            (a) => a.fhir_address_id !== addressId,
        );
    },
};

const actions = {
    async fetchAddresses({ commit }) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get('/api/fhir/addresses');
            commit('SET_ADDRESSES', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al obtener las direcciones',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async fetchAddress({ commit }, addressId) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get(
                `/api/fhir/addresses/${addressId}`,
            );
            commit('SET_CURRENT_ADDRESS', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al obtener la dirección',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async createAddress({ commit }, addressData) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.post(
                '/api/fhir/addresses',
                addressData,
            );
            commit('ADD_ADDRESS', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message || 'Error al crear la dirección',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async updateAddress({ commit }, { addressId, addressData }) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.put(
                `/api/fhir/addresses/${addressId}`,
                addressData,
            );
            commit('UPDATE_ADDRESS', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al actualizar la dirección',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async deleteAddress({ commit }, addressId) {
        try {
            commit('SET_LOADING', true);
            await axios.delete(`/api/fhir/addresses/${addressId}`);
            commit('DELETE_ADDRESS', addressId);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al eliminar la dirección',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },
};

const getters = {
    getAllAddresses: (state) => state.addresses,
    getCurrentAddress: (state) => state.currentAddress,
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
