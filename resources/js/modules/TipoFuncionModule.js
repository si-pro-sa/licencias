export default {
    namespaced: true,
    state: {
        tipoFunciones: [],
    },
    mutations: {
        FETCH_TIPO_FUNCIONES(state, tipoFunciones) {
            return (state.tipoFunciones = tipoFunciones);
        },
    },
    actions: {
        async all({commit}) {
            await axios
                .get(`/api/tipoFuncion`)
                .then(res => {
                    console.info("get tipoFunciones ", res.data);
                    commit("FETCH_TIPO_FUNCIONES", res.data);
                })
                .catch(err => {
                    console.error("Error en get tipoFunciones: " + err);
                });
        }
    },
    getters: {
        tipoFunciones(state) {
            return state.tipoFunciones;
        }
    }
};
