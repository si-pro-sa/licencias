/**
 * @file api/juntaMedicaApi.js
 * @description Servicio de API para gestión de juntas médicas
 */
import axios from 'axios';
import { handleError } from './errorHandler';

// Cliente axios con configuración por defecto
const apiClient = axios.create({
    baseURL: '/api',
    timeout: 30000,
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
});

/**
 * Servicio de API para juntas médicas
 * @namespace JuntaMedicaApi
 */
export default {
    /**
     * Obtiene la lista de juntas médicas con paginación y filtros opcionales
     * @memberof JuntaMedicaApi
     * @param {Object} params - Parámetros de consulta
     * @param {number} params.page - Número de página
     * @param {number} params.per_page - Elementos por página
     * @param {string} params.search - Término de búsqueda
     * @param {string} params.sort_by - Campo para ordenar
     * @param {string} params.sort_dir - Dirección del ordenamiento (asc, desc)
     * @param {string} params.estado - Filtro por estado
     * @param {string} params.tipo - Filtro por tipo
     * @param {string|number} params.empleado_id - Filtro por empleado
     * @returns {Promise<Object>} Respuesta con datos de juntas médicas
     */
    getJuntasMedicas(params = {}) {
        return apiClient
            .get('/juntas-medicas', { params })
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, 'Error al obtener juntas médicas'),
            );
    },

    /**
     * Obtiene una junta médica por su ID
     * @memberof JuntaMedicaApi
     * @param {number|string} id - ID de la junta médica
     * @returns {Promise<Object>} Datos de la junta médica
     */
    getJuntaMedica(id) {
        if (!id) throw new Error('ID de junta médica es requerido');

        return apiClient
            .get(`/juntas-medicas/${id}`)
            .then((response) => response.data)
            .catch((error) =>
                handleError(
                    error,
                    `Error al obtener junta médica con ID ${id}`,
                ),
            );
    },

    /**
     * Crea una nueva junta médica
     * @memberof JuntaMedicaApi
     * @param {FormData|Object} data - Datos de la junta médica
     * @returns {Promise<Object>} Datos de la junta médica creada
     */
    createJuntaMedica(data) {
        const headers =
            data instanceof FormData
                ? { 'Content-Type': 'multipart/form-data' }
                : {};

        return apiClient
            .post('/juntas-medicas', data, { headers })
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, 'Error al crear junta médica'),
            );
    },

    /**
     * Actualiza una junta médica existente
     * @memberof JuntaMedicaApi
     * @param {number|string} id - ID de la junta médica
     * @param {FormData|Object} data - Datos actualizados
     * @returns {Promise<Object>} Datos de la junta médica actualizada
     */
    updateJuntaMedica(id, data) {
        if (!id) throw new Error('ID de junta médica es requerido');

        const headers =
            data instanceof FormData
                ? { 'Content-Type': 'multipart/form-data' }
                : {};

        // Si es FormData, necesitamos agregar el método PUT manualmente
        if (data instanceof FormData) {
            data.append('_method', 'PUT');
            return apiClient
                .post(`/juntas-medicas/${id}`, data, { headers })
                .then((response) => response.data)
                .catch((error) =>
                    handleError(
                        error,
                        `Error al actualizar junta médica con ID ${id}`,
                    ),
                );
        }

        return apiClient
            .put(`/juntas-medicas/${id}`, data, { headers })
            .then((response) => response.data)
            .catch((error) =>
                handleError(
                    error,
                    `Error al actualizar junta médica con ID ${id}`,
                ),
            );
    },

    /**
     * Elimina una junta médica
     * @memberof JuntaMedicaApi
     * @param {number|string} id - ID de la junta médica
     * @returns {Promise<Object>} Respuesta de confirmación
     */
    deleteJuntaMedica(id) {
        if (!id) throw new Error('ID de junta médica es requerido');

        return apiClient
            .delete(`/juntas-medicas/${id}`)
            .then((response) => response.data)
            .catch((error) =>
                handleError(
                    error,
                    `Error al eliminar junta médica con ID ${id}`,
                ),
            );
    },

    /**
     * Descarga un documento adjunto a una junta médica
     * @memberof JuntaMedicaApi
     * @param {number|string} documentoId - ID del documento
     * @returns {Promise<Blob>} Blob del documento para descarga
     */
    downloadDocumento(documentoId) {
        if (!documentoId) throw new Error('ID de documento es requerido');

        return apiClient
            .get(`/documentos/${documentoId}/download`, {
                responseType: 'blob',
            })
            .then((response) => {
                // Verificar si la respuesta contiene el archivo
                const contentType = response.headers['content-type'];
                const contentDisposition =
                    response.headers['content-disposition'];

                if (contentType && contentDisposition) {
                    // Extraer el nombre del archivo del header Content-Disposition
                    const filenameRegex =
                        /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    const matches = filenameRegex.exec(contentDisposition);
                    let filename = 'documento.pdf'; // Nombre por defecto

                    if (matches != null && matches[1]) {
                        filename = matches[1].replace(/['"]/g, '');
                    }

                    return {
                        blob: response.data,
                        filename,
                        contentType,
                    };
                }

                throw new Error(
                    'Error al descargar el documento: formato de respuesta inválido',
                );
            })
            .catch((error) =>
                handleError(
                    error,
                    `Error al descargar documento con ID ${documentoId}`,
                ),
            );
    },

    /**
     * Elimina un documento adjunto
     * @memberof JuntaMedicaApi
     * @param {number|string} documentoId - ID del documento
     * @returns {Promise<Object>} Respuesta de confirmación
     */
    deleteDocumento(documentoId) {
        if (!documentoId) throw new Error('ID de documento es requerido');

        return apiClient
            .delete(`/documentos/${documentoId}`)
            .then((response) => response.data)
            .catch((error) =>
                handleError(
                    error,
                    `Error al eliminar documento con ID ${documentoId}`,
                ),
            );
    },

    /**
     * Obtiene estadísticas de juntas médicas
     * @memberof JuntaMedicaApi
     * @param {Object} params - Parámetros para filtrar estadísticas
     * @returns {Promise<Object>} Datos estadísticos
     */
    getEstadisticas(params = {}) {
        return apiClient
            .get('/juntas-medicas/estadisticas', { params })
            .then((response) => response.data)
            .catch((error) =>
                handleError(
                    error,
                    'Error al obtener estadísticas de juntas médicas',
                ),
            );
    },
};
