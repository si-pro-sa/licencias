import axios from 'axios';

const state = {
    conditions: [],
    currentCondition: null,
    loading: false,
    error: null,
};

const mutations = {
    SET_CONDITIONS(state, conditions) {
        state.conditions = conditions;
    },
    SET_CURRENT_CONDITION(state, condition) {
        state.currentCondition = condition;
    },
    SET_LOADING(state, loading) {
        state.loading = loading;
    },
    SET_ERROR(state, error) {
        state.error = error;
    },
    ADD_CONDITION(state, condition) {
        state.conditions.push(condition);
    },
    UPDATE_CONDITION(state, updatedCondition) {
        const index = state.conditions.findIndex(
            (c) => c.fhir_condition_id === updatedCondition.fhir_condition_id,
        );
        if (index !== -1) {
            state.conditions.splice(index, 1, updatedCondition);
        }
    },
    DELETE_CONDITION(state, conditionId) {
        state.conditions = state.conditions.filter(
            (c) => c.fhir_condition_id !== conditionId,
        );
    },
};

const actions = {
    async fetchConditions({ commit }) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get('/api/fhir/conditions');
            commit('SET_CONDITIONS', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al obtener las condiciones',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async fetchCondition({ commit }, conditionId) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get(
                `/api/fhir/conditions/${conditionId}`,
            );
            commit('SET_CURRENT_CONDITION', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al obtener la condición',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async createCondition({ commit }, conditionData) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.post(
                '/api/fhir/conditions',
                conditionData,
            );
            commit('ADD_CONDITION', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message || 'Error al crear la condición',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async updateCondition({ commit }, { conditionId, conditionData }) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.put(
                `/api/fhir/conditions/${conditionId}`,
                conditionData,
            );
            commit('UPDATE_CONDITION', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al actualizar la condición',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async deleteCondition({ commit }, conditionId) {
        try {
            commit('SET_LOADING', true);
            await axios.delete(`/api/fhir/conditions/${conditionId}`);
            commit('DELETE_CONDITION', conditionId);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al eliminar la condición',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },
};

const getters = {
    getAllConditions: (state) => state.conditions,
    getCurrentCondition: (state) => state.currentCondition,
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
