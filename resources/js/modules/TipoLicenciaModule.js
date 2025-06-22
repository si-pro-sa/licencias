export default {
    namespaced: true,
    state: {
        tipoLicencias: [],
        tipoLicencia: {},
    },
    mutations: {
        FETCH_TIPOLICENCIAS(state, tipoLicencias) {
            return (state.tipoLicencias = tipoLicencias);
        },
        FETCH_TIPOLICENCIA(state, tipoLicencia) {
            state.tipoLicencias.push(tipoLicencia);
            return (state.tipoLicencia = tipoLicencia);
        },
        DELETE_TIPOLICENCIA(state, tipoLicencia) {
            let index = state.tipoLicencias.findIndex(
                item => item.idtipoLicencia === tipoLicencia.idtipoLicencia
            );
            state.tipoLicencias.splice(index, 1);
        },
        EDIT_TIPOLICENCIA(state, tipoLicencia) {
            let index = state.tipoLicencias.findIndex(
                item => item.idtipoLicencia === tipoLicencia.idtipoLicencia
            );
            state.tipoLicencias.splice(index, 1);
            state.tipoLicencias.unshift(tipoLicencia);
        },
    },
    actions: {
        async all({commit}) {
            await axios
                .get(`/api/tipolicencias`)
                .then(res => {
                    console.log("get tipolicencias ", res.data.data);
                    commit("FETCH_TIPOLICENCIAS", res.data.data);
                })
                .catch(err => {
                    console.log("Error en get tipoLicencias: " + err);
                });
        },
        async show({commit}, idtipoLicencia) {
            await axios
                .get(`/api/tipolicencias/${idtipoLicencia}`)
                .then(res => {
                    console.log("get tipolicencia ", res.data.data);
                    commit("FETCH_TIPOLICENCIA", res.data.data);
                })
                .catch(err => {
                    console.log("Error en get tipoLicencia: " + err);
                });
        },
        async post({commit}, tipoLicencias) {
            console.log("inicio de post");
            axios
                .post(`/api/tipolicencias`, tipoLicencias)
                .then((res) => {
                    if (res.data.success)
                        commit("FETCH_TIPOLICENCIA", tipoLicencias[0]);
                })
                .catch(error =>
                    console.log(`Error con la insertada de TipoLicencia: ${error}`)
                );
        },
        async delete({commit}, idtipoLicencias) {
            console.log("Comienza borrado");
            axios
                .delete(`/api/tipolicencias/${idtipoLicencias}`)
                .then(res => {
                    if (res.data.success) commit("DELETE_TIPOLICENCIA", tipoLicencias);
                })
                .catch(err => {
                    console.log("Error al borrar la tipoLicencias: " + err);
                });
        },
        async update({commit}, tipoLicencias) {
            console.log("Comienza actualizacion");
            axios
                .put(`/api/tipolicencias/${tipoLicencias.idtipoLicencias}`, tipoLicencias)
                .then(res => {
                    if (res.data.success) commit("EDIT_TIPOLICENCIA", tipoLicencias);
                })
                .catch(err => {
                    console.log("Error al modificar TipoLicencia" + err);
                });
            console.log("Sale de la actualizacion de TipoLicencia");
        },
    },
    getters: {
        tipoLicencias(state) {
            return state.tipoLicencias;
        },
        tipoLicencia(state) {
            return state.tipoLicencia;
        },
    }
};
