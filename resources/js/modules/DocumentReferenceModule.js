import axios from 'axios';

const state = {
    documentReferences: [],
    currentDocumentReference: null,
    loading: false,
    error: null,
};

const mutations = {
    SET_DOCUMENT_REFERENCES(state, documentReferences) {
        state.documentReferences = documentReferences;
    },
    SET_CURRENT_DOCUMENT_REFERENCE(state, documentReference) {
        state.currentDocumentReference = documentReference;
    },
    SET_LOADING(state, loading) {
        state.loading = loading;
    },
    SET_ERROR(state, error) {
        state.error = error;
    },
    ADD_DOCUMENT_REFERENCE(state, documentReference) {
        state.documentReferences.push(documentReference);
    },
    UPDATE_DOCUMENT_REFERENCE(state, updatedDocumentReference) {
        const index = state.documentReferences.findIndex(
            (d) =>
                d.fhir_document_reference_id ===
                updatedDocumentReference.fhir_document_reference_id,
        );
        if (index !== -1) {
            state.documentReferences.splice(index, 1, updatedDocumentReference);
        }
    },
    DELETE_DOCUMENT_REFERENCE(state, documentReferenceId) {
        state.documentReferences = state.documentReferences.filter(
            (d) => d.fhir_document_reference_id !== documentReferenceId,
        );
    },
};

const actions = {
    async fetchDocumentReferences({ commit }) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get('/api/fhir/document-references');
            commit('SET_DOCUMENT_REFERENCES', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al obtener las referencias a documentos',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async fetchDocumentReference({ commit }, documentReferenceId) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.get(
                `/api/fhir/document-references/${documentReferenceId}`,
            );
            commit('SET_CURRENT_DOCUMENT_REFERENCE', response.data);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al obtener la referencia al documento',
            );
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async createDocumentReference({ commit }, documentReferenceData) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.post(
                '/api/fhir/document-references',
                documentReferenceData,
            );
            commit('ADD_DOCUMENT_REFERENCE', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al crear la referencia al documento',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async updateDocumentReference(
        { commit },
        { documentReferenceId, documentReferenceData },
    ) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.put(
                `/api/fhir/document-references/${documentReferenceId}`,
                documentReferenceData,
            );
            commit('UPDATE_DOCUMENT_REFERENCE', response.data);
            commit('SET_ERROR', null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al actualizar la referencia al documento',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async deleteDocumentReference({ commit }, documentReferenceId) {
        try {
            commit('SET_LOADING', true);
            await axios.delete(
                `/api/fhir/document-references/${documentReferenceId}`,
            );
            commit('DELETE_DOCUMENT_REFERENCE', documentReferenceId);
            commit('SET_ERROR', null);
        } catch (error) {
            commit(
                'SET_ERROR',
                error.response?.data?.message ||
                    'Error al eliminar la referencia al documento',
            );
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },
};

const getters = {
    getAllDocumentReferences: (state) => state.documentReferences,
    getCurrentDocumentReference: (state) => state.currentDocumentReference,
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
