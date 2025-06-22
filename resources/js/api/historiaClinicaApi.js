/**
 * @file api/historiaClinicaApi.js
 * @description Servicio API para la gestión de historias clínicas
 */
import axios from 'axios';
import { handleError } from './errorHandler';

// Crear instancia de axios con configuración por defecto
const apiClient = axios.create({
    baseURL: process.env.VUE_APP_API_URL || '/api',
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
    },
});

/**
 * Servicio de API para Historia Clínica
 * @namespace HistoriaClinicaApi
 */
export default {
    /**
     * Obtener la historia clínica completa de un agente
     * @memberof HistoriaClinicaApi
     * @param {number} idAgente - ID del agente
     * @returns {Promise<Object>} Datos de la historia clínica
     */
    getHistoriaClinica(idAgente) {
        if (!idAgente) throw new Error('ID de agente es requerido');

        return apiClient
            .get(`/salud-ocupacional/historia-clinica/${idAgente}`)
            .then((response) => response.data)
            .catch((error) =>
                handleError(
                    error,
                    `Error al obtener la historia clínica del agente ${idAgente}`,
                ),
            );
    },

    /**
     * Obtener los exámenes médicos de un agente
     * @memberof HistoriaClinicaApi
     * @param {number} idAgente - ID del agente
     * @param {Object} params - Parámetros de filtrado
     * @returns {Promise<Object>} Datos de exámenes médicos
     */
    getExamenesMedicos(idAgente, params = {}) {
        if (!idAgente) throw new Error('ID de agente es requerido');

        return apiClient
            .get(`/salud-ocupacional/examenes/${idAgente}`, { params })
            .then((response) => response.data)
            .catch((error) =>
                handleError(
                    error,
                    `Error al obtener exámenes médicos del agente ${idAgente}`,
                ),
            );
    },

    /**
     * Obtener detalle de un examen médico específico
     * @memberof HistoriaClinicaApi
     * @param {number} idExamen - ID del examen médico
     * @returns {Promise<Object>} Datos del examen médico
     */
    getExamenMedico(idExamen) {
        if (!idExamen) throw new Error('ID de examen es requerido');

        return apiClient
            .get(`/salud-ocupacional/examenes/detalle/${idExamen}`)
            .then((response) => response.data)
            .catch((error) =>
                handleError(
                    error,
                    `Error al obtener el examen médico ${idExamen}`,
                ),
            );
    },

    /**
     * Obtener consultas médicas de un agente
     * @memberof HistoriaClinicaApi
     * @param {number} idAgente - ID del agente
     * @param {Object} params - Parámetros de filtrado
     * @returns {Promise<Object>} Datos de consultas médicas
     */
    getConsultasMedicas(idAgente, params = {}) {
        if (!idAgente) throw new Error('ID de agente es requerido');

        return apiClient
            .get(`/salud-ocupacional/consultas/${idAgente}`, { params })
            .then((response) => response.data)
            .catch((error) =>
                handleError(
                    error,
                    `Error al obtener consultas médicas del agente ${idAgente}`,
                ),
            );
    },

    /**
     * Obtener detalle de una consulta médica específica
     * @memberof HistoriaClinicaApi
     * @param {number} idConsulta - ID de la consulta médica
     * @returns {Promise<Object>} Datos de la consulta médica
     */
    getConsultaMedica(idConsulta) {
        if (!idConsulta) throw new Error('ID de consulta es requerido');

        return apiClient
            .get(`/salud-ocupacional/consultas/detalle/${idConsulta}`)
            .then((response) => response.data)
            .catch((error) =>
                handleError(
                    error,
                    `Error al obtener la consulta médica ${idConsulta}`,
                ),
            );
    },

    /**
     * Obtener evaluaciones de aptitud laboral de un agente
     * @memberof HistoriaClinicaApi
     * @param {number} idAgente - ID del agente
     * @param {Object} params - Parámetros de filtrado
     * @returns {Promise<Object>} Datos de aptitud laboral
     */
    getAptitudLaboral(idAgente, params = {}) {
        if (!idAgente) throw new Error('ID de agente es requerido');

        return apiClient
            .get(`/salud-ocupacional/aptitud/${idAgente}`, { params })
            .then((response) => response.data)
            .catch((error) =>
                handleError(
                    error,
                    `Error al obtener aptitud laboral del agente ${idAgente}`,
                ),
            );
    },

    /**
     * Obtener licencias médicas de un agente
     * @memberof HistoriaClinicaApi
     * @param {number} idAgente - ID del agente
     * @param {Object} params - Parámetros de filtrado
     * @returns {Promise<Object>} Datos de licencias médicas
     */
    getLicenciasMedicas(idAgente, params = {}) {
        if (!idAgente) throw new Error('ID de agente es requerido');

        return apiClient
            .get(`/salud-ocupacional/licencias/${idAgente}`, { params })
            .then((response) => response.data)
            .catch((error) =>
                handleError(
                    error,
                    `Error al obtener licencias médicas del agente ${idAgente}`,
                ),
            );
    },

    /**
     * Registrar un nuevo examen médico
     * @memberof HistoriaClinicaApi
     * @param {Object} examenData - Datos del examen médico
     * @returns {Promise<Object>} Datos del examen médico creado
     */
    crearExamenMedico(examenData) {
        if (!examenData.id_agente) throw new Error('ID de agente es requerido');

        return apiClient
            .post('/salud-ocupacional/examenes', examenData)
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, 'Error al crear el examen médico'),
            );
    },

    /**
     * Registrar una nueva consulta médica
     * @memberof HistoriaClinicaApi
     * @param {Object} consultaData - Datos de la consulta médica
     * @returns {Promise<Object>} Datos de la consulta médica creada
     */
    crearConsultaMedica(consultaData) {
        if (!consultaData.id_agente)
            throw new Error('ID de agente es requerido');

        return apiClient
            .post('/salud-ocupacional/consultas', consultaData)
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, 'Error al crear la consulta médica'),
            );
    },

    /**
     * Registrar una nueva evaluación de aptitud laboral
     * @memberof HistoriaClinicaApi
     * @param {Object} aptitudData - Datos de la aptitud laboral
     * @returns {Promise<Object>} Datos de la aptitud laboral creada
     */
    crearAptitudLaboral(aptitudData) {
        if (!aptitudData.id_agente)
            throw new Error('ID de agente es requerido');

        return apiClient
            .post('/salud-ocupacional/aptitud', aptitudData)
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, 'Error al crear la evaluación de aptitud'),
            );
    },
};
