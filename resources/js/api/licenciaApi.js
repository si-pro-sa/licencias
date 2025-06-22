/**
 * @file api/licenciaApi.js
 * @description Servicio API para gestión de licencias
 */
import axios from 'axios';
import { handleError } from './errorHandler';
import { setupInterceptors } from './interceptors';
import moment from 'moment';

// Cliente axios con configuración predeterminada
const apiClient = axios.create({
    baseURL: '/api',
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
    },
});

// Configurar interceptores
setupInterceptors(apiClient);

/**
 * Servicio API de licencias
 * @namespace LicenciaApi
 */
export default {
    /**
     * Obtiene todas las licencias con filtrado opcional
     * @memberof LicenciaApi
     * @param {Object} params - Parámetros de consulta
     * @param {number} params.page - Número de página
     * @param {string} params.search - Término de búsqueda
     * @param {string} params.estado - Estado de la licencia
     * @returns {Promise<Object>} Respuesta con datos de licencias
     */
    getLicencias(params = {}) {
        return apiClient
            .get('/licencias', { params })
            .then((response) => response.data)
            .catch((error) => handleError(error, 'Error al obtener licencias'));
    },

    /**
     * Obtiene una licencia por ID
     * @memberof LicenciaApi
     * @param {number|string} id - ID de la licencia
     * @returns {Promise<Object>} Datos de la licencia
     */
    getLicencia(id) {
        if (!id) throw new Error('Se requiere el ID de la licencia');

        return apiClient
            .get(`/licencias/${id}`)
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, `Error al obtener la licencia ${id}`),
            );
    },

    /**
     * Obtiene las licencias de un agente específico
     * @memberof LicenciaApi
     * @param {number|string} agenteId - ID del agente
     * @param {Object} params - Parámetros adicionales
     * @returns {Promise<Object>} Licencias del agente
     */
    getLicenciasPorAgente(agenteId, params = {}) {
        if (!agenteId) throw new Error('Se requiere el ID del agente');

        return apiClient
            .get(`/agentes/${agenteId}/licencias`, { params })
            .then((response) => response.data)
            .catch((error) =>
                handleError(
                    error,
                    `Error al obtener licencias del agente ${agenteId}`,
                ),
            );
    },

    /**
     * Obtiene licencias de un agente por tipo de licencia
     * @memberof LicenciaApi
     * @param {Object} obj - Objeto con parámetros
     * @param {number|string} obj.idagente - ID del agente
     * @param {number|string} obj.tipoLicencia - ID del tipo de licencia
     * @returns {Promise<Object>} Licencias del agente del tipo específico
     */
    getLicenciasPorAgenteYTipo(obj) {
        if (!obj.idagente) throw new Error('Se requiere el ID del agente');
        if (!obj.tipoLicencia)
            throw new Error('Se requiere el tipo de licencia');

        return apiClient
            .get(`/api/licencias/agente/${obj.idagente}/${obj.tipoLicencia}`)
            .then((response) => response.data)
            .catch((error) => handleError(error, 'Error al obtener licencias'));
    },

    /**
     * Obtiene las licencias totales de un agente
     * @memberof LicenciaApi
     * @param {Object} obj - Objeto con parámetros
     * @param {number|string} obj.idagente - ID del agente
     * @returns {Promise<Object>} Todas las licencias del agente
     */
    getLicenciasTotales(obj) {
        if (!obj.idagente) throw new Error('Se requiere el ID del agente');

        return apiClient
            .get(`/api/licencias/agente/${obj.idagente}`)
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, 'Error al obtener licencias totales'),
            );
    },

    /**
     * Obtiene licencias por dependencia con filtros
     * @memberof LicenciaApi
     * @param {Object} obj - Objeto con parámetros y filtros
     * @returns {Promise<Object>} Licencias filtradas
     */
    getLicenciasDependientes(obj) {
        let params = new URLSearchParams();

        // Parámetros de paginación y ordenamiento
        const { page, itemsPerPage, sortBy, sortDesc } = obj.options || {};
        if (page) params.append('page', page);
        if (itemsPerPage) params.append('itemsPerPage', itemsPerPage);
        if (sortBy) params.append('sortBy', sortBy);
        if (sortDesc) params.append('sortDesc', sortDesc);

        // ID del agente
        if (obj.idagente) params.append('idagente', obj.idagente);

        // Aplicar filtros si existen
        if (obj.filters) {
            const {
                efector,
                codigo_nombre,
                documento,
                apellido_nombre,
                tipoLicencia,
                dias,
                idlicencia,
                fecha_pedido_inicio,
                fecha_pedido_final,
                primer_visado,
                segundo_visado,
                fecha_efectiva_inicio,
                fecha_efectiva_final,
                cuarta_visado,
                fecha_interrupcion_inicio,
            } = obj.filters;

            if (efector) params.append('efector', efector.toUpperCase());
            if (codigo_nombre)
                params.append('codigo_nombre', codigo_nombre.toUpperCase());
            if (documento) params.append('documento', documento);
            if (apellido_nombre)
                params.append('apellido_nombre', apellido_nombre.toUpperCase());
            if (tipoLicencia)
                params.append('tipoLicencia', tipoLicencia.toLowerCase());
            if (dias) params.append('dias', dias);
            if (idlicencia) params.append('idlicencia', idlicencia);
            if (fecha_pedido_inicio)
                params.append('fecha_pedido_inicio', fecha_pedido_inicio);
            if (fecha_pedido_final)
                params.append('fecha_pedido_final', fecha_pedido_final);
            if (primer_visado >= 0)
                params.append('primer_visado', primer_visado);
            if (segundo_visado >= 0)
                params.append('segundo_visado', segundo_visado);
            if (cuarta_visado) params.append('cuarta_visado', cuarta_visado);
            if (fecha_efectiva_inicio)
                params.append('fecha_efectiva_inicio', fecha_efectiva_inicio);
            if (fecha_efectiva_final)
                params.append('fecha_efectiva_final', fecha_efectiva_final);
            if (fecha_interrupcion_inicio)
                params.append(
                    'fecha_interrupcion_inicio',
                    fecha_interrupcion_inicio,
                );
        }

        // Dependencia
        if (obj.dependencia) params.append('dependencia', obj.dependencia);

        return apiClient
            .get('/api/licencias/dependiente/', { params })
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, 'Error al obtener licencias dependientes'),
            );
    },

    /**
     * Obtiene licencias para capacitación
     * @memberof LicenciaApi
     * @param {Object} obj - Objeto con parámetros
     * @returns {Promise<Object>} Licencias de capacitación
     */
    getLicenciasDependientesCapacitacion(obj) {
        let params = new URLSearchParams();

        // Parámetros similares a getLicenciasDependientes
        // Añadiendo parámetros específicos para capacitación
        if (obj.filters) {
            const {
                alcance,
                caracter,
                tipo_evento,
                evento_nombre,
                evento_lugar,
            } = obj.filters;

            if (alcance) params.append('alcance', alcance);
            if (caracter) params.append('caracter', caracter);
            if (tipo_evento) params.append('tipoEvento', tipo_evento);
            if (evento_lugar) params.append('evento_lugar', evento_lugar);
            if (evento_nombre) params.append('evento_nombre', evento_nombre);
        }

        // Añadir los demás parámetros como en getLicenciasDependientes
        // ...

        return apiClient
            .get('/api/licencias/masivo/capacitacion/', { params })
            .then((response) => response.data)
            .catch((error) =>
                handleError(
                    error,
                    'Error al obtener licencias de capacitación',
                ),
            );
    },

    /**
     * Obtiene licencias para consulta en un rango de fechas
     * @memberof LicenciaApi
     * @param {Object} obj - Objeto con fecha_desde, fecha_hasta y dependencia opcional
     * @returns {Promise<Object>} Licencias en el rango de fechas
     */
    getLicenciasConsulta(obj) {
        const params = new URLSearchParams();
        params.append('fecha_desde', obj.fecha_desde);
        params.append('fecha_hasta', obj.fecha_hasta);

        if (obj.dependencia) {
            params.append('dependencia', obj.dependencia);
        }

        if (obj.tipo_licencias) {
            params.append('tipo_licencias', obj.tipo_licencias);
        }

        return apiClient
            .get('/api/licencias/consulta', { params })
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, 'Error al obtener licencias para consulta'),
            );
    },

    /**
     * Obtiene licencias mensuales
     * @memberof LicenciaApi
     * @param {Object} obj - Objeto con mes y dependencia opcional
     * @returns {Promise<Object>} Licencias mensuales
     */
    getLicenciasMensuales(obj) {
        const params = new URLSearchParams();
        params.append('mes', obj.mes);

        if (obj.dependencia) {
            params.append('dependencia', obj.dependencia);
        }

        if (obj.tipo_licencias) {
            params.append('tipo_licencias', obj.tipo_licencias);
        }

        return apiClient
            .get('/api/licencias/consulta/mensual', { params })
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, 'Error al obtener licencias mensuales'),
            );
    },

    /**
     * Obtiene licencias retroactivas
     * @memberof LicenciaApi
     * @param {Object} obj - Objeto con mes y dependencia opcional
     * @returns {Promise<Object>} Licencias retroactivas
     */
    getLicenciasRetroactiva(obj) {
        const params = new URLSearchParams();
        params.append('mes', obj.mes);

        if (obj.dependencia) {
            params.append('dependencia', obj.dependencia);
        }

        if (obj.tipo_licencias) {
            params.append('tipo_licencias', obj.tipo_licencias);
        }

        return apiClient
            .get('/api/licencias/consulta/mensual/retroactiva', { params })
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, 'Error al obtener licencias retroactivas'),
            );
    },

    /**
     * Crea una nueva licencia
     * @memberof LicenciaApi
     * @param {Object} licenciaData - Datos de la licencia
     * @returns {Promise<Object>} Datos de la licencia creada
     */
    crearLicencia(licenciaData) {
        // Validación de campos requeridos se realiza en el API endpoint

        // Verificar si es una licencia de salud ocupacional para incluir diagnóstico
        let salud_ocupacional = [
            1, 2, 3, 4, 7, 8, 11, 21, 22, 38, 40, 39, 38, 37,
        ];

        return apiClient
            .post('/api/licencias/complete', licenciaData)
            .then((response) => {
                // Si hay un diagnóstico y es una licencia de salud ocupacional
                if (
                    salud_ocupacional.includes(
                        licenciaData[0].idtipoLicencia,
                    ) &&
                    licenciaData[4]?.codigo !== ''
                ) {
                    const licencia_id = response.data.data.idlicencia;
                    const formData = licenciaData[4];

                    // Subir diagnóstico
                    return apiClient
                        .post(
                            `/api/diagnosticos/licencia/${licencia_id}`,
                            formData,
                            {
                                headers: {
                                    'Content-Type': 'multipart/form-data',
                                },
                            },
                        )
                        .then(() => response.data);
                }

                return response.data;
            })
            .catch((error) => handleError(error, 'Error al crear la licencia'));
    },

    /**
     * Actualiza una licencia existente
     * @memberof LicenciaApi
     * @param {number|string} id - ID de la licencia
     * @param {Object} licenciaData - Datos actualizados
     * @returns {Promise<Object>} Datos de la licencia actualizada
     */
    actualizarLicencia(id, licenciaData) {
        if (!id) throw new Error('Se requiere el ID de la licencia');

        // Verificar si es una licencia de salud ocupacional para actualizar diagnóstico
        let salud_ocupacional = [
            1, 2, 3, 4, 7, 8, 11, 21, 22, 38, 40, 39, 38, 37,
        ];

        return apiClient
            .put(`/api/licencias/${id}`, licenciaData)
            .then((response) => {
                // Si hay un diagnóstico y es una licencia de salud ocupacional
                if (
                    salud_ocupacional.includes(
                        licenciaData[0].idtipoLicencia,
                    ) &&
                    licenciaData[4]?.codigo !== ''
                ) {
                    const formData = licenciaData[4];

                    // Actualizar diagnóstico
                    return apiClient
                        .put(`/api/diagnosticos/licencia/${id}`, formData, {
                            headers: { 'Content-Type': 'multipart/form-data' },
                        })
                        .then(() => response.data);
                }

                return response.data;
            })
            .catch((error) =>
                handleError(error, `Error al actualizar la licencia ${id}`),
            );
    },

    /**
     * Realiza el primer visado de múltiples licencias
     * @memberof LicenciaApi
     * @param {Object} data - Datos para el visado masivo
     * @returns {Promise<Object>} Resultado de la operación
     */
    primerVisadoTodo(data) {
        return apiClient
            .put('/api/licencias/masivo/primer', data)
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, 'Error al realizar el primer visado masivo'),
            );
    },

    /**
     * Realiza el segundo visado de múltiples licencias
     * @memberof LicenciaApi
     * @param {Object} data - Datos para el visado masivo
     * @returns {Promise<Object>} Resultado de la operación
     */
    segundoVisadoTodo(data) {
        return apiClient
            .put('/api/licencias/masivo/segundo', data)
            .then((response) => response.data)
            .catch((error) =>
                handleError(
                    error,
                    'Error al realizar el segundo visado masivo',
                ),
            );
    },

    /**
     * Desvisar una licencia
     * @memberof LicenciaApi
     * @param {Object} licencia - Licencia a desvisar
     * @returns {Promise<Object>} Licencia actualizada
     */
    desvisarLicencia(licencia) {
        if (!licencia.idlicencia)
            throw new Error('Se requiere el ID de la licencia');

        return apiClient
            .put(`/api/licencias/desvisar/${licencia.idlicencia}`)
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, 'Error al desvisar la licencia'),
            );
    },

    /**
     * Cambia el estado de una licencia
     * @memberof LicenciaApi
     * @param {number|string} id - ID de la licencia
     * @param {string} nuevoEstado - Nuevo estado de la licencia
     * @returns {Promise<Object>} Respuesta del servidor
     */
    cambiarEstadoLicencia(id, nuevoEstado) {
        if (!id) throw new Error('Se requiere el ID de la licencia');
        if (!nuevoEstado) throw new Error('Se requiere el nuevo estado');

        return apiClient
            .patch(`/licencias/${id}/estado`, { estado: nuevoEstado })
            .then((response) => response.data)
            .catch((error) =>
                handleError(
                    error,
                    `Error al cambiar estado de la licencia ${id}`,
                ),
            );
    },

    /**
     * Elimina una licencia
     * @memberof LicenciaApi
     * @param {number|string} id - ID de la licencia
     * @returns {Promise<Object>} Respuesta del servidor
     */
    eliminarLicencia(id) {
        if (!id) throw new Error('Se requiere el ID de la licencia');

        return apiClient
            .delete(`/api/licencias/${id}`)
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, `Error al eliminar la licencia ${id}`),
            );
    },

    /**
     * Exporta licencias a Excel
     * @memberof LicenciaApi
     * @param {Object} data - Datos para la exportación
     * @returns {Promise<Object>} Resultado de la exportación
     */
    exportXLS(data) {
        return apiClient
            .put('/api/licencias/exportar', data)
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, 'Error al exportar licencias a Excel'),
            );
    },

    /**
     * Obtiene días posibles de licencia para un agente
     * @memberof LicenciaApi
     * @param {number|string} idagente - ID del agente
     * @returns {Promise<Object>} Días posibles de licencia
     */
    getDiasPosibles(idagente) {
        if (!idagente) throw new Error('Se requiere el ID del agente');

        return apiClient
            .get(`/api/licencias/dias/${idagente}`)
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, 'Error al obtener días posibles'),
            );
    },

    /**
     * Obtiene feriados
     * @memberof LicenciaApi
     * @returns {Promise<Object>} Lista de feriados
     */
    getFeriados() {
        return apiClient
            .get('/api/licencias/feriados')
            .then((response) => response.data)
            .catch((error) => handleError(error, 'Error al obtener feriados'));
    },

    /**
     * Obtiene personas activas
     * @memberof LicenciaApi
     * @param {Array} arr - Array con parámetros
     * @returns {Promise<Object>} Lista de personas activas
     */
    getPersonasActivas(arr) {
        return apiClient
            .get(`/api/licencias/personas/${arr[1]}`)
            .then((response) => response.data)
            .catch((error) =>
                handleError(error, 'Error al obtener personas activas'),
            );
    },

    /**
     * Obtiene personas discapacitadas activas
     * @memberof LicenciaApi
     * @param {number|string} idagente - ID del agente
     * @returns {Promise<Object>} Lista de personas discapacitadas activas
     */
    getPersonasDiscapacitadaActivas(idagente) {
        return apiClient
            .get(`/api/licencias/personasDiscapacitada/${idagente}`)
            .then((response) => response.data)
            .catch((error) =>
                handleError(
                    error,
                    'Error al obtener personas discapacitadas activas',
                ),
            );
    },
};
