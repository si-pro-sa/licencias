export default {
    namespaced: true,

    state: {
        saldos: [],
        saldos_totales: []
    },
    mutations: {
        FETCH_SALDOS(state, saldos) {
            return (state.saldos = saldos);
        },
        FETCH_SALDOS_TOTALES(state, saldos) {
            return (state.saldos_totales = saldos);
        },
    },
    actions: {
        async getLicenciasSaldos({commit}, obj) {
            await axios
                .get(
                    `/api/saldos/agente/${obj["idagente"]}/${obj["tipoLicencia"]}`
                )
                .then(res => {
                    commit("FETCH_SALDOS", res.data.data);
                })
                .catch(err => {
                    console.log("Error en getLicenciasSaldos: " + err);
                });
        },
        async getLicenciasSaldosTotalesHabiles({commit}, obj) {
            await axios
                .get(`/api/saldos/habiles/${obj["idagente"]}`)
                .then(res => {
                    commit("FETCH_SALDOS_TOTALES", res.data.data);
                })
                .catch(err => {
                    console.log("Error en getLicenciasSaldosTotales: " + err);
                });
        },
    },
    getters: {
        obtenerSaldos(state) {
            return state.saldos;
        },
        obtenerSaldosTotales(state) {
            return state.saldos_totales;
        },

    }
};
