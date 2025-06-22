/**
 * @module HistoriaClinica
 * @description Módulo Vuex para gestionar la historia clínica de agentes
 */
import historiaClinicaApi from '../../api/historiaClinicaApi';

// Estado inicial
const state = {
    examenes: [],
    consultas: [],
    aptitud: [],
    licencias: [],
    detalleExamen: null,
    detalleConsulta: null,
    loading: false,
    error: null,
};

// Mutaciones
const mutations = {
    SET_EXAMENES(state, examenes) {
        state.examenes = examenes;
    },
    SET_CONSULTAS(state, consultas) {
        state.consultas = consultas;
    },
    SET_APTITUD(state, aptitud) {
        state.aptitud = aptitud;
    },
    SET_LICENCIAS(state, licencias) {
        state.licencias = licencias;
    },
    SET_DETALLE_EXAMEN(state, examen) {
        state.detalleExamen = examen;
    },
    SET_DETALLE_CONSULTA(state, consulta) {
        state.detalleConsulta = consulta;
    },
    SET_LOADING(state, status) {
        state.loading = status;
    },
    SET_ERROR(state, error) {
        state.error = error;
    },
    CLEAR_HISTORIA_CLINICA(state) {
        state.examenes = [];
        state.consultas = [];
        state.aptitud = [];
        state.licencias = [];
        state.detalleExamen = null;
        state.detalleConsulta = null;
        state.error = null;
    },
};

// Acciones
const actions = {
    /**
     * Cargar toda la historia clínica de un agente
     * @param {Object} context - Contexto de acción Vuex
     * @param {number} idAgente - ID del agente
     * @returns {Promise} Promesa que se resuelve cuando se carga la historia clínica
     */
    async loadHistoriaClinica({ commit, dispatch }, idAgente) {
        commit('SET_LOADING', true);
        commit('SET_ERROR', null);
        commit('CLEAR_HISTORIA_CLINICA');

        try {
            // Cargar todos los componentes de la historia clínica
            await Promise.all([
                dispatch('loadExamenesMedicos', idAgente),
                dispatch('loadConsultasMedicas', idAgente),
                dispatch('loadAptitudLaboral', idAgente),
                dispatch('loadLicenciasMedicas', idAgente),
            ]);

            return true;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.message || 'Error al cargar la historia clínica',
            );
            console.error('Error cargando historia clínica:', error);
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    /**
     * Cargar exámenes médicos de un agente
     * @param {Object} context - Contexto de acción Vuex
     * @param {number} idAgente - ID del agente
     * @param {Object} params - Parámetros de filtrado opcionales
     * @returns {Promise} Promesa que se resuelve cuando se cargan los exámenes
     */
    async loadExamenesMedicos({ commit }, idAgente, params = {}) {
        try {
            const response = await historiaClinicaApi.getExamenesMedicos(
                idAgente,
                params,
            );
            commit('SET_EXAMENES', response.data || []);
            return response.data;
        } catch (error) {
            console.error('Error cargando exámenes médicos:', error);
            throw error;
        }
    },

    /**
     * Cargar detalle de un examen médico
     * @param {Object} context - Contexto de acción Vuex
     * @param {number} idExamen - ID del examen médico
     * @returns {Promise} Promesa que se resuelve cuando se carga el detalle
     */
    async loadDetalleExamen({ commit }, idExamen) {
        commit('SET_LOADING', true);

        try {
            const response = await historiaClinicaApi.getExamenMedico(idExamen);
            commit('SET_DETALLE_EXAMEN', response.data || null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.message ||
                    `Error al cargar detalle del examen ${idExamen}`,
            );
            console.error('Error cargando detalle del examen:', error);
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    /**
     * Cargar consultas médicas de un agente
     * @param {Object} context - Contexto de acción Vuex
     * @param {number} idAgente - ID del agente
     * @param {Object} params - Parámetros de filtrado opcionales
     * @returns {Promise} Promesa que se resuelve cuando se cargan las consultas
     */
    async loadConsultasMedicas({ commit }, idAgente, params = {}) {
        try {
            const response = await historiaClinicaApi.getConsultasMedicas(
                idAgente,
                params,
            );
            commit('SET_CONSULTAS', response.data || []);
            return response.data;
        } catch (error) {
            console.error('Error cargando consultas médicas:', error);
            throw error;
        }
    },

    /**
     * Cargar detalle de una consulta médica
     * @param {Object} context - Contexto de acción Vuex
     * @param {number} idConsulta - ID de la consulta médica
     * @returns {Promise} Promesa que se resuelve cuando se carga el detalle
     */
    async loadDetalleConsulta({ commit }, idConsulta) {
        commit('SET_LOADING', true);

        try {
            const response = await historiaClinicaApi.getConsultaMedica(
                idConsulta,
            );
            commit('SET_DETALLE_CONSULTA', response.data || null);
            return response.data;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.message ||
                    `Error al cargar detalle de la consulta ${idConsulta}`,
            );
            console.error('Error cargando detalle de la consulta:', error);
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    /**
     * Cargar evaluaciones de aptitud laboral de un agente
     * @param {Object} context - Contexto de acción Vuex
     * @param {number} idAgente - ID del agente
     * @param {Object} params - Parámetros de filtrado opcionales
     * @returns {Promise} Promesa que se resuelve cuando se cargan las evaluaciones
     */
    async loadAptitudLaboral({ commit }, idAgente, params = {}) {
        try {
            const response = await historiaClinicaApi.getAptitudLaboral(
                idAgente,
                params,
            );
            commit('SET_APTITUD', response.data || []);
            return response.data;
        } catch (error) {
            console.error('Error cargando aptitud laboral:', error);
            throw error;
        }
    },

    /**
     * Cargar licencias médicas de un agente
     * @param {Object} context - Contexto de acción Vuex
     * @param {number} idAgente - ID del agente
     * @param {Object} params - Parámetros de filtrado opcionales
     * @returns {Promise} Promesa que se resuelve cuando se cargan las licencias
     */
    async loadLicenciasMedicas({ commit }, idAgente, params = {}) {
        try {
            const response = await historiaClinicaApi.getLicenciasMedicas(
                idAgente,
                params,
            );
            commit('SET_LICENCIAS', response.data || []);
            return response.data;
        } catch (error) {
            console.error('Error cargando licencias médicas:', error);
            throw error;
        }
    },

    /**
     * Crear un nuevo examen médico
     * @param {Object} context - Contexto de acción Vuex
     * @param {Object} examenData - Datos del examen médico
     * @returns {Promise} Promesa que se resuelve cuando se crea el examen
     */
    async createExamenMedico({ commit, dispatch }, examenData) {
        commit('SET_LOADING', true);
        commit('SET_ERROR', null);

        try {
            const response = await historiaClinicaApi.crearExamenMedico(
                examenData,
            );
            // Actualizar la lista de exámenes después de crear uno nuevo
            if (examenData.id_agente) {
                await dispatch('loadExamenesMedicos', examenData.id_agente);
            }
            return response;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.message || 'Error al crear el examen médico',
            );
            console.error('Error creando examen médico:', error);
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    /**
     * Crear una nueva consulta médica
     * @param {Object} context - Contexto de acción Vuex
     * @param {Object} consultaData - Datos de la consulta médica
     * @returns {Promise} Promesa que se resuelve cuando se crea la consulta
     */
    async createConsultaMedica({ commit, dispatch }, consultaData) {
        commit('SET_LOADING', true);
        commit('SET_ERROR', null);

        try {
            const response = await historiaClinicaApi.crearConsultaMedica(
                consultaData,
            );
            // Actualizar la lista de consultas después de crear una nueva
            if (consultaData.id_agente) {
                await dispatch('loadConsultasMedicas', consultaData.id_agente);
            }
            return response;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.message || 'Error al crear la consulta médica',
            );
            console.error('Error creando consulta médica:', error);
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    /**
     * Crear una nueva evaluación de aptitud laboral
     * @param {Object} context - Contexto de acción Vuex
     * @param {Object} aptitudData - Datos de la aptitud laboral
     * @returns {Promise} Promesa que se resuelve cuando se crea la evaluación
     */
    async createAptitudLaboral({ commit, dispatch }, aptitudData) {
        commit('SET_LOADING', true);
        commit('SET_ERROR', null);

        try {
            const response = await historiaClinicaApi.crearAptitudLaboral(
                aptitudData,
            );
            // Actualizar la lista de evaluaciones después de crear una nueva
            if (aptitudData.id_agente) {
                await dispatch('loadAptitudLaboral', aptitudData.id_agente);
            }
            return response;
        } catch (error) {
            commit(
                'SET_ERROR',
                error.message || 'Error al crear la evaluación de aptitud',
            );
            console.error('Error creando evaluación de aptitud:', error);
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },
};

// Getters
const getters = {
    getExamenes: (state) => state.examenes,
    getConsultas: (state) => state.consultas,
    getAptitud: (state) => state.aptitud,
    getLicencias: (state) => state.licencias,
    getDetalleExamen: (state) => state.detalleExamen,
    getDetalleConsulta: (state) => state.detalleConsulta,
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
