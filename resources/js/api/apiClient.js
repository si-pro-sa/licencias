/**
 * @file api/apiClient.js
 * @description Cliente Axios con configuración e interceptores
 */
import axios from 'axios';
import store from '../store';
import router from '../router';

// Crear instancia de axios con configuración predeterminada
const apiClient = axios.create({
    baseURL: '/api',
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
    },
});

// Interceptor de solicitudes
apiClient.interceptors.request.use(
    (config) => {
        // Añadir token de autorización si está disponible
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }

        // Añadir marca de tiempo para depuración
        config.metadata = { startTime: new Date().getTime() };

        // Registrar solicitudes salientes en desarrollo
        if (process.env.NODE_ENV !== 'production') {
            console.log('Solicitud API:', {
                método: config.method.toUpperCase(),
                url: config.url,
                params: config.params,
                datos: config.data,
            });
        }

        return config;
    },
    (error) => {
        console.error('Error en solicitud:', error);
        return Promise.reject(error);
    },
);

// Interceptor de respuestas
apiClient.interceptors.response.use(
    (response) => {
        // Calcular duración de la solicitud
        const duration =
            new Date().getTime() - response.config.metadata.startTime;

        // Registrar tiempo de respuesta en desarrollo
        if (process.env.NODE_ENV !== 'production') {
            console.log(`Respuesta API (${duration}ms):`, {
                estado: response.status,
                datos: response.data,
            });
        }

        return response;
    },
    (error) => {
        // Manejar errores 401 redirigiendo al login
        if (error.response && error.response.status === 401) {
            // Eliminar token
            localStorage.removeItem('token');

            // Redirigir a login si no estamos ya en login
            if (router.currentRoute.name !== 'login') {
                router.push({
                    name: 'login',
                    query: { redirect: router.currentRoute.fullPath },
                });
            }
        }

        // Registrar errores de API
        console.error('Error de API:', {
            mensaje: error.message,
            estado: error.response?.status,
            datos: error.response?.data,
        });

        return Promise.reject(error);
    },
);

export default apiClient;
