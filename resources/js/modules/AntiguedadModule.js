export default {
    namespaced: true,
    state: {
        antiguedades: [],
        antiguedad: {},
        antiguedades_consulta: [],
        organismos: [],
    },
    mutations: {
        FETCH_ORGANISMOS(state, organismos) {
            return (state.organismos = organismos);
        },
        FETCH_ANTIGUEDADES_CONSULTA(state, antiguedades) {
            return (state.antiguedades_consulta = antiguedades);
        },
        RESET_STATE(state) {
            state.antiguedades = [];
            state.antiguedad = {};
        },
        FETCH_ANTIGUEDADES(state, antiguedades) {
            return (state.antiguedades = antiguedades);
        },
        FETCH_ANTIGUEDAD(state, antiguedad) {
            state.antiguedades.push(antiguedad);
            return (state.antiguedad = antiguedad);
        },
        DELETE_ANTIGUEDAD(state, antiguedad) {
            let index = state.antiguedades.findIndex(
                (item) => item.idantiguedad === antiguedad.idantiguedad,
            );
            state.antiguedades.splice(index, 1);
        },
        EDIT_ANTIGUEDAD(state, antiguedad) {
            let index = state.antiguedades.findIndex(
                (item) => item.idantiguedad === antiguedad.idantiguedad,
            );
            state.antiguedades.splice(index, 1);
            state.antiguedades.unshift(antiguedad);
        },
    },
    actions: {
        getDefaultState({ commit }) {
            commit('RESET_STATE');
        },
        async vigenteAntiguedades({ commit }, obj) {
            await window.axios
                .put(`/api/antiguedades/vigente`, obj)
                .then((res) => {
                    console.log('success: ', res.data.success);
                })
                .catch((err) => {
                    console.log('Error al modificar antiguedad' + err);
                });
            console.log('Sale de la actualizacion de antiguedad');
        },
        async getAntiguedadConsulta({ commit }, obj) {
            const params = new URLSearchParams();
            params.append('iddependencia', obj.iddependencia);
            params.append('fecha_desde', obj.fecha_desde);
            params.append('fecha_hasta', obj.fecha_hasta);
            await axios
                .get(`/api/antiguedades/consulta/mmk/ppp/fd`, {
                    params: params,
                })
                .then((res) => {
                    commit('FETCH_ANTIGUEDADES_CONSULTA', res.data.data);
                })
                .catch((err) => {
                    console.log('Error en getAntiguedadesConsulta: ' + err);
                });
        },
        async getAntiguedades({ commit }, idagente) {
            await window.axios
                .get(`/api/antiguedades/agente/${idagente}`)
                .then((res) => {
                    console.log(
                        'Se ejecuta getAntiguedades y se trajo esto: ',
                        res.data.data,
                    );
                    commit('FETCH_ANTIGUEDADES', res.data.data);
                })
                .catch((err) => {
                    console.log('Error en get antiguedades: ' + err);
                });
        },
        async getOrganismos({ commit }, obj) {
            await window.axios
                .get(
                    `/api/dependencia/consulta-lao/efectores/${obj.dependencia.iddependencia}`,
                )
                .then((res) => {
                    commit('FETCH_ORGANISMOS', res.data.data);
                })
                .catch((err) => {
                    console.log('Error en get organismos: ' + err);
                });
        },
        async getAntiguedadesMenosLicencia({ commit }, obj) {
            await window.axios
                .get(
                    `/api/antiguedades/licencia/${obj.idagente}/${obj.idlicencia}`,
                )
                .then((res) => {
                    commit('FETCH_ANTIGUEDADES', res.data.data);
                })
                .catch((err) => {
                    console.log('Error en get antiguedades: ' + err);
                });
        },
        async postAntiguedad(context, antiguedad) {
            console.log('inicio de post');
            antiguedad.pedido = 0;
            await window.axios
                .post(`/api/antiguedades`, antiguedad)
                .then((res) => {
                    if (res.data.success)
                        context.commit('FETCH_ANTIGUEDAD', antiguedad[0]);
                })
                .catch((error) =>
                    console.log(
                        `Error con la insertada de antiguedad: ${error}`,
                    ),
                );
        },
        async deleteAntiguedad({ commit }, antiguedad) {
            console.log('Comienza borrado');
            await window.axios
                .delete(`/api/antiguedades/${antiguedad}`)
                .then((res) => {
                    if (res.data.success)
                        commit('DELETE_ANTIGUEDAD', antiguedad);
                })
                .catch((err) => {
                    console.log('Error al borrar la antiguedad: ' + err);
                });
        },
        async updateAntiguedad({ commit }, antiguedad) {
            console.log('Comienza actualizacion');
            await window.axios
                .put(`/api/antiguedades/${antiguedad.idantiguedad}`, antiguedad)
                .then((res) => {
                    if (res.data.success) commit('EDIT_ANTIGUEDAD', antiguedad);
                })
                .catch((err) => {
                    console.log('Error al modificar antiguedad' + err);
                });
            console.log('Sale de la actualizacion de antiguedad');
        },
        async getAntiguedad({ commit }, idantiguedad) {
            await window.axios
                .get(`/api/antiguedades/${idantiguedad}`)
                .then((res) => {
                    commit('FETCH_ANTIGUEDAD', res.data.data);
                })
                .catch((err) => {
                    console.log('Error en get antiguedad: ', err);
                });
        },
    },
    getters: {
        antiguedad(state) {
            return (idantiguedad) =>
                state.antiguedades.find((f) => f.idantiguedad === idantiguedad);
        },
        antiguedades(state) {
            return state.antiguedades;
        },
        antiguedades_consulta(state) {
            return state.antiguedades_consulta;
        },
        organismos(state) {
            return state.organismos;
        },
    },
};
