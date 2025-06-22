export default {
    namespaced: true,

    state: {
        expedientes: []
    },
    mutations: {
        ADD_EXPEDIENTE(state, expediente) {
            state.expedientes.push(expediente);
        },
        ADD_EXPEDIENTES(state, expedientes) {
            state.expedientes = expedientes;
        },
        CREATE_EXPEDIENTE(state, expediente) {
            state.expedientes.unshift(expediente);
        },
        FETCH_EXPEDIENTE(state, expedientes) {
            return (state.expedientes = expedientes);
        },
        DELETE_EXPEDIENTE(state, expediente) {
            let index = state.expedientes.findIndex(
                item => item.idgrupoFamiliar === expediente.idgrupoFamiliar
            );
            state.expedientes.splice(index, 1);
        },
        EDIT_EXPEDIENTE(state, expediente) {
            var expedientes = state.expedientes;
            expedientes.splice(expedientes.indexOf(expediente), 1);
            state.expedientes.unshift(expediente);
        }
    },
    actions: {
        async getExpedientes({ commit }, idagente) {
            await window.axios
                .get(`/api/grupofamiliar/expediente/${idagente}`)
                .then(res => {
                    commit("FETCH_EXPEDIENTE", res.data.data);
                })
                .catch(err => {
                    console.log("Error en getExpediente: " + err);
                });
        },

        async postExpediente(context, expediente) {
            console.log("inicio de post");
            await window.axios
                .post(`/api/grupofamiliar/complete`, expediente)
                .then(() => console.log("Se hizo el post:", expediente))
                .catch(error =>
                    console.log(
                        `Error con la insertada de Expediente: ${error}`
                    )
                );
        },

        async deleteExpediente({ commit }, expediente) {
            console.log("Comienza borrado");
            await window.axios
                .delete(`/api/grupofamiliar/${expediente.idgrupoFamiliar}`)
                .then(res => {
                    if (res.data.success)
                        console.log("se ejecuta DELETE_EXPEDIENTE");
                    commit("DELETE_EXPEDIENTE", expediente);
                })
                .catch(err => {
                    console.log("Error al borrar Expediente: " + err);
                });
        },

        async updateExpediente({ commit }, expediente) {
            console.log("Comienza actualizacion");
            await window.axios
                .put(
                    `/api/grupofamiliar/${expediente.idgrupoFamiliar}`,
                    expediente
                )
                .then(res => {
                    if (res.data.success) commit("EDIT_EXPEDIENTE", expediente);
                })
                .catch(err => {
                    console.log("Error al modificar Expediente" + err);
                });
        }
    },
    getters: {
        expediente(state) {
            return id => state.expedientes.find(f => f.id === id);
        },
        por_expediente(state) {
            return nExpediente =>
                state.expedientes.filter(f => f.nExpediente == nExpediente);
        },
        find_expediente(state) {
            return nExpediente =>
                state.expedientes.find(f => f.nExpediente == nExpediente);
        },
        obtenerExpedientes(state) {
            return state.expedientes;
        },
        compareValues(state, key, order = "asc") {
            return function(a, b) {
                if (!a.hasOwnProperty(key) || !b.hasOwnProperty(key)) {
                    return 0;
                }

                const varA =
                    typeof a[key] === "string" ? a[key].toUpperCase() : a[key];
                const varB =
                    typeof b[key] === "string" ? b[key].toUpperCase() : b[key];

                let comparison = 0;
                if (varA > varB) {
                    comparison = 1;
                } else if (varA < varB) {
                    comparison = -1;
                }
                return order == "desc" ? comparison * -1 : comparison;
            };
        }
    }
};
