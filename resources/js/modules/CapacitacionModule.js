export default {
    namespaced: true,
    state: {
        capacitaciones: [],
        agentes: [],
        loading: false,
        error: null,
        message: '',
    },
    mutations: {
        FETCH(state, data) {
            state.capacitaciones = data;
        },
        FETCH_AGENTE(state, data) {
            state.agentes = data;
        },
        SET_LOADING(state, loading) {
            state.loading = loading;
        },
        SET_ERROR(state, error) {
            state.error = error;
            state.message = error.message || 'An error occurred';
        },
        SET_MESSAGE(state, message) {
            state.message = message;
        },
        CLEAR_MESSAGE(state) {
            state.message = '';
            state.error = null;
        },
    },
    actions: {
        async all({ commit }) {
            commit('SET_LOADING', true);
            try {
                const res = await axios.get(`/api/capacitacion`);
                commit('FETCH', res.data.data);
                commit('SET_LOADING', false);
            } catch (error) {
                commit('SET_ERROR', error);
                commit('SET_LOADING', false);
            }
        },
        async post({ commit }, payload) {
            commit('SET_LOADING', true);
            try {
                const res = await axios.post(`/api/capacitacion`, payload, {
                    headers: { 'Content-Type': 'multipart/form-data' },
                });
                commit('SET_MESSAGE', 'Capacitación creada con éxito');
            } catch (error) {
                commit('SET_ERROR', error);
            } finally {
                commit('SET_LOADING', false);
            }
        },
        async delete({ commit }, id) {
            commit('SET_LOADING', true);
            try {
                await axios.delete(`/api/capacitacion/${id}`);
                commit('SET_MESSAGE', 'Capacitación eliminada con éxito');
            } catch (error) {
                commit('SET_ERROR', error);
            } finally {
                commit('SET_LOADING', false);
            }
        },
        async update({ commit }, payload) {
            commit('SET_LOADING', true);
            try {
                await axios.post(
                    `/api/capacitacion/${payload.get('idCapacitacion')}`,
                    payload,
                    {
                        headers: { 'Content-Type': 'multipart/form-data' },
                    },
                );
                commit('SET_MESSAGE', 'Capacitación actualizada con éxito');
            } catch (error) {
                commit('SET_ERROR', error);
            } finally {
                commit('SET_LOADING', false);
            }
        },
        async agentes({ commit }, id) {
            commit('SET_LOADING', true);
            try {
                const res = await axios.get(`/api/capacitacion/agentes/${id}`);
                commit('FETCH_AGENTE', res.data.data);
            } catch (error) {
                commit('SET_ERROR', error);
            } finally {
                commit('SET_LOADING', false);
            }
        },
        async uploadPrograma({ commit }, payload) {
            commit('SET_LOADING', true);
            try {
                await axios.post(
                    `/api/capacitaciones/upload/programa/${payload.get(
                        'idCapacitacion',
                    )}`,
                    payload,
                    {
                        headers: { 'Content-Type': 'multipart/form-data' },
                    },
                );
                commit('SET_MESSAGE', 'Programa subido con éxito');
            } catch (error) {
                commit('SET_ERROR', error);
            } finally {
                commit('SET_LOADING', false);
            }
        },
        async getProgramaURL({ commit }, id) {
            commit('SET_LOADING', true);
            try {
                const res = await axios.get(
                    `/api/capacitaciones/programa/${id}`,
                );
                commit('SET_LOADING', false);
                return res.data.url;
            } catch (error) {
                commit('SET_ERROR', error);
                commit('SET_LOADING', false);
            }
        },
    },
    getters: {
        capacitaciones(state) {
            return state.capacitaciones;
        },
        agentes(state) {
            return state.agentes;
        },
    },
};
