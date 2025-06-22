import axios from 'axios';

const state = {
    patients: [],
    currentPatient: null,
    loading: false,
    error: null,
};

const mutations = {
    SET_PATIENTS(state, patients) {
        state.patients = patients;
    },
    SET_CURRENT_PATIENT(state, patient) {
        state.currentPatient = patient;
    },
    SET_LOADING(state, loading) {
        state.loading = loading;
    },
    SET_ERROR(state, error) {
        state.error = error;
    },
    ADD_PATIENT(state, patient) {
        state.patients.push(patient);
    },
    UPDATE_PATIENT(state, updatedPatient) {
        const index = state.patients.findIndex(
            (p) => p.fhir_patient_id === updatedPatient.fhir_patient_id,
        );
        if (index !== -1) {
            state.patients.splice(index, 1, updatedPatient);
        }
    },
    DELETE_PATIENT(state, patientId) {
        state.patients = state.patients.filter(
            (p) => p.fhir_patient_id !== patientId,
        );
    },
};

const actions = {
    async fetchPatients({ commit }) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get('/api/fhir/patients');
            commit('SET_PATIENTS', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al obtener los pacientes',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async fetchPatient({ commit }, patientId) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get(`/api/fhir/patients/${patientId}`);
            commit('SET_CURRENT_PATIENT', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message || 'Error al obtener el paciente',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async createPatient({ commit }, patientData) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.post(
                '/api/fhir/patients',
                patientData,
            );
            commit('ADD_PATIENT', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message || 'Error al crear el paciente',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async updatePatient({ commit }, { patientId, patientData }) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.put(
                `/api/fhir/patients/${patientId}`,
                patientData,
            );
            commit('UPDATE_PATIENT', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al actualizar el paciente',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async deletePatient({ commit }, patientId) {
        try {
            commit('SET_LOADING', true);
            await axios.delete(`/api/fhir/patients/${patientId}`);
            commit('DELETE_PATIENT', patientId);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al eliminar el paciente',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },
};

const getters = {
    getAllPatients: (state) => state.patients,
    getCurrentPatient: (state) => state.currentPatient,
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
