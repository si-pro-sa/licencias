/**
 * @file api/interceptors.js
 * @description Interceptores axios para peticiones API
 */
import store from '../store';
import router from '../router';

/**
 * Configura interceptores axios
 * @param {Object} axiosInstance - Instancia de axios
 */
export function setupInterceptors(axiosInstance) {
    // Interceptor de peticiones
    axiosInstance.interceptors.request.use(
        (config) => {
            // Añadir encabezado de autorización con token JWT si está disponible
            const token = store.getters['auth/getToken'];
            if (token) {
                config.headers.Authorization = `Bearer ${token}`;
            }

            // Añadir marca de tiempo para depuración
            config.metadata = { startTime: new Date().getTime() };

            // Registrar peticiones salientes en desarrollo
            if (process.env.NODE_ENV !== 'production') {
                console.log('Petición API:', {
                    method: config.method.toUpperCase(),
                    url: config.url,
                    params: config.params,
                    data: config.data,
                });
            }

            return config;
        },
        (error) => {
            console.error('Error de petición:', error);
            return Promise.reject(error);
        },
    );

    // Interceptor de respuestas
    axiosInstance.interceptors.response.use(
        (response) => {
            // Calcular duración de la petición
            const duration =
                new Date().getTime() - response.config.metadata.startTime;

            // Registrar tiempo de respuesta en desarrollo
            if (process.env.NODE_ENV !== 'production') {
                console.log(`Respuesta API (${duration}ms):`, {
                    status: response.status,
                    data: response.data,
                });
            }

            return response;
        },
        (error) => {
            // Manejar errores 401 No autorizado redirigiendo al login
            if (error.response && error.response.status === 401) {
                store.dispatch('auth/logout');
                router.push({
                    name: 'login',
                    query: { redirect: router.currentRoute.fullPath },
                });
            }

            // Registrar errores API
            console.error('Error API:', {
                message: error.message,
                status: error.response?.status,
                data: error.response?.data,
            });

            return Promise.reject(error);
        },
    );
}
