/**
 * @file store/index.js
 * @description Configuración principal de Vuex store
 */
import Vue from 'vue';
import Vuex from 'vuex';
import users from './modules/users';

Vue.use(Vuex);

// Módulo de la aplicación para manejar el estado global
const app = {
    namespaced: true,
    state: {
        apiError: null,
        sidebarVisible: true,
    },
    mutations: {
        SET_API_ERROR(state, error) {
            state.apiError = error;
        },
        CLEAR_API_ERROR(state) {
            state.apiError = null;
        },
        SET_SIDEBAR_VISIBLE(state, visible) {
            state.sidebarVisible = visible;
        },
    },
    actions: {
        clearApiError({ commit }) {
            commit('CLEAR_API_ERROR');
        },
        toggleSidebar({ commit, state }) {
            commit('SET_SIDEBAR_VISIBLE', !state.sidebarVisible);
        },
    },
    getters: {
        getApiError: (state) => state.apiError,
        isSidebarVisible: (state) => state.sidebarVisible,
    },
};

export default new Vuex.Store({
    modules: {
        app,
        users,
    },
});
