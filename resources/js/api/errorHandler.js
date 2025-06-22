/**
 * @file api/errorHandler.js
 * @description Manejo centralizado de errores de API
 */
import {
    ApiError,
    NetworkError,
    NotFoundError,
    ValidationError,
    AuthError,
    ForbiddenError,
    ServerError,
} from './errorClasses';
import store from '../store';

/**
 * Maneja errores de API de forma consistente
 * @param {Error} error - El error capturado
 * @param {string} defaultMessage - Mensaje de error por defecto
 * @throws {Error} Error transformado con contexto adicional
 */
export function handleError(error, defaultMessage = 'Ha ocurrido un error') {
    // Registrar el error con contexto
    console.error('Error de API:', {
        mensaje: error.message,
        estado: error.response?.status,
        datos: error.response?.data,
    });

    // Actualizar estado global de error
    store.commit(
        'app/SET_API_ERROR',
        {
            message: getErrorMessage(error) || defaultMessage,
            code: error.response?.status || 'unknown',
            timestamp: new Date().toISOString(),
        },
        { root: true },
    );

    // Mostrar mensaje de error en el snackbar
    store.commit(
        'app/SET_SNACKBAR',
        {
            message: getErrorMessage(error) || defaultMessage,
            color: 'error',
        },
        { root: true },
    );

    // Manejar diferentes tipos de error
    if (error.response) {
        // El servidor respondió con un estado de error (4xx, 5xx)
        const status = error.response.status;

        if (status === 401) {
            throw new AuthError(
                error.response.data.message ||
                    'Sesión expirada o usuario no autenticado',
            );
        } else if (status === 403) {
            throw new ForbiddenError(
                error.response.data.message ||
                    'No tiene permisos para realizar esta acción',
            );
        } else if (status === 404) {
            throw new NotFoundError(
                error.response.data.message || 'Recurso no encontrado',
            );
        } else if (status === 422) {
            throw new ValidationError(
                error.response.data.message || 'Error de validación',
                error.response.data.errors || {},
            );
        } else if (status >= 500) {
            throw new ServerError(
                error.response.data.message || 'Error interno del servidor',
            );
        } else {
            throw new ApiError(
                getErrorMessage(error) || defaultMessage,
                error.response.status,
            );
        }
    } else if (error.request) {
        // Se realizó la solicitud pero no se recibió respuesta (error de red)
        throw new NetworkError(
            'Error de conexión, verifique su conexión a internet',
        );
    } else {
        // Error al configurar la solicitud
        throw new Error(error.message || defaultMessage);
    }
}

/**
 * Extrae mensaje de error de varios formatos de respuesta de error
 * @param {Error} error - El objeto de error
 * @returns {string|null} Mensaje de error extraído
 */
function getErrorMessage(error) {
    if (!error.response) return error.message || null;

    const { data } = error.response;

    // Manejar diferentes formatos de respuesta de error
    if (typeof data === 'string') return data;
    if (data.message) return data.message;
    if (data.error) return data.error;
    if (data.errors) {
        if (typeof data.errors === 'string') return data.errors;
        if (Array.isArray(data.errors)) return data.errors[0];
        if (typeof data.errors === 'object') {
            const firstKey = Object.keys(data.errors)[0];
            return data.errors[firstKey][0] || 'Error de validación';
        }
    }

    return null;
}

/**
 * Formatea errores de validación en un mensaje legible
 * @param {Object} errors - Objeto de errores de validación
 * @returns {string|null} Mensaje formateado o null
 */
function formatValidationErrors(errors) {
    if (!errors || typeof errors !== 'object') return null;

    const errorMessages = [];

    // Recorrer todos los campos con errores
    Object.keys(errors).forEach((field) => {
        if (Array.isArray(errors[field])) {
            errors[field].forEach((message) => {
                errorMessages.push(message);
            });
        } else if (typeof errors[field] === 'string') {
            errorMessages.push(errors[field]);
        }
    });

    if (errorMessages.length === 0) return null;

    // Si hay un solo error, devolverlo directamente
    if (errorMessages.length === 1) return errorMessages[0];

    // Si hay múltiples errores, listarlos con viñetas
    return errorMessages.map((msg) => `• ${msg}`).join('\n');
}
