/**
 * @file api/errorClasses.js
 * @description Clases de error personalizadas para el manejo de errores de API
 */

/**
 * Error base para errores de API
 */
export class ApiError extends Error {
    /**
     * @param {string} message - Mensaje de error
     * @param {number} status - Código de estado HTTP
     */
    constructor(message, status) {
        super(message);
        this.name = 'ApiError';
        this.status = status;
    }
}

/**
 * Error para problemas de autenticación (401)
 */
export class AuthError extends ApiError {
    constructor(message = 'Sesión expirada o usuario no autenticado') {
        super(message, 401);
        this.name = 'AuthError';
    }
}

/**
 * Error para accesos no autorizados (403)
 */
export class ForbiddenError extends ApiError {
    constructor(message = 'No tiene permisos para realizar esta acción') {
        super(message, 403);
        this.name = 'ForbiddenError';
    }
}

/**
 * Error para recursos no encontrados (404)
 */
export class NotFoundError extends ApiError {
    constructor(message = 'Recurso no encontrado') {
        super(message, 404);
        this.name = 'NotFoundError';
    }
}

/**
 * Error para errores de validación (422)
 */
export class ValidationError extends ApiError {
    /**
     * @param {string} message - Mensaje de error principal
     * @param {Object} errors - Objeto con errores de validación por campo
     */
    constructor(message = 'Error de validación', errors = {}) {
        super(message, 422);
        this.name = 'ValidationError';
        this.errors = errors;
    }
}

/**
 * Error de red cuando no hay respuesta del servidor
 */
export class NetworkError extends Error {
    constructor(
        message = 'Error de conexión, verifique su conexión a internet',
    ) {
        super(message);
        this.name = 'NetworkError';
    }
}

/**
 * Error del servidor (500)
 */
export class ServerError extends ApiError {
    constructor(message = 'Error interno del servidor') {
        super(message, 500);
        this.name = 'ServerError';
    }
}
