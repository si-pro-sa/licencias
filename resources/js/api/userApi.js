/**
 * @file api/userApi.js
 * @description Servicios API relacionados con usuarios
 */
import axios from 'axios';
import { handleError } from './errorHandler';

// Crear instancia de axios con configuración predeterminada
const apiClient = axios.create({
    baseURL: '/api',
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
});

/**
 * Servicio API de Usuarios
 * @namespace UserApi
 */
export default {
    /**
     * Obtener datos del usuario actual
     * @memberof UserApi
     * @returns {Promise<Object>} Datos del usuario
     */
    getCurrentUser() {
        return apiClient
            .get('/user')
            .then((response) => response.data)
            .catch((error) => {
                console.error('Error al obtener el usuario actual:', error);
                throw error;
            });
    },

    /**
     * Obtener métricas del usuario actual
     * @memberof UserApi
     * @returns {Promise<Object>} Métricas del usuario
     */
    getUserMetrics() {
        return apiClient
            .get('/user/metrics')
            .then((response) => response.data)
            .catch((error) => {
                console.error('Error al obtener métricas de usuario:', error);
                throw error;
            });
    },

    /**
     * Obtener métricas de un usuario específico
     * @memberof UserApi
     * @param {number|string} userId - ID del usuario
     * @returns {Promise<Object>} Métricas del usuario
     */
    getUserMetricsById(userId) {
        if (!userId) throw new Error('Se requiere el ID del usuario');

        return apiClient
            .get(`/users/${userId}/metrics`)
            .then((response) => response.data)
            .catch((error) => {
                console.error(
                    `Error al obtener métricas del usuario ${userId}:`,
                    error,
                );
                throw error;
            });
    },

    /**
     * Obtener todos los usuarios con filtrado opcional
     * @memberof UserApi
     * @param {Object} params - Parámetros de consulta
     * @param {number} params.page - Número de página
     * @param {string} params.sortBy - Campo para ordenar
     * @param {string} params.search - Término de búsqueda
     * @returns {Promise<Object>} Respuesta con datos de usuarios
     */
    getUsers(params = {}) {
        return apiClient
            .get('/users', { params })
            .then((response) => response.data)
            .catch((error) => handleError(error, 'Error al obtener usuarios'));
    },

    /**
     * Obtener un solo usuario por ID
     * @memberof UserApi
     * @param {number|string} id - ID del usuario
     * @returns {Promise<Object>} Datos del usuario
     */
    getUser(id) {
        if (!id) throw new Error('Se requiere el ID del usuario');

        return apiClient
            .get(`/users/${id}`)
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, `Error al obtener usuario ${id}`),
            );
    },

    /**
     * Crear un nuevo usuario
     * @memberof UserApi
     * @param {Object} userData - Datos del usuario
     * @returns {Promise<Object>} Datos del usuario creado
     */
    createUser(userData) {
        // Validar campos requeridos
        if (!userData.name || !userData.email) {
            throw new Error('El nombre y el email son requeridos');
        }

        return apiClient
            .post('/users', userData)
            .then((response) => response.data)
            .catch((error) => handleError(error, 'Error al crear usuario'));
    },

    /**
     * Actualizar un usuario
     * @memberof UserApi
     * @param {number|string} id - ID del usuario
     * @param {Object} userData - Datos actualizados del usuario
     * @returns {Promise<Object>} Datos del usuario actualizado
     */
    updateUser(id, userData) {
        if (!id) throw new Error('Se requiere el ID del usuario');

        return apiClient
            .put(`/users/${id}`, userData)
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, `Error al actualizar usuario ${id}`),
            );
    },

    /**
     * Eliminar un usuario
     * @memberof UserApi
     * @param {number|string} id - ID del usuario
     * @returns {Promise<Object>} Datos de respuesta
     */
    deleteUser(id) {
        if (!id) throw new Error('Se requiere el ID del usuario');

        return apiClient
            .delete(`/users/${id}`)
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, `Error al eliminar usuario ${id}`),
            );
    },
};
