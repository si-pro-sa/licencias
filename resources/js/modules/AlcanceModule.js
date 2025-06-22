export default {
    namespaced: true,
    state: {
        alcances: []
    },
    mutations: {
        FETCH(state, data) {
            return (state.alcances = data)
        }
    },
    actions: {
        async all({ commit }) {
            await axios
                .get(`/api/alcance`)
                .then(res => {
                    commit('FETCH', res.data.data)
                })
                .catch(err => {
                    console.error('Error en get tipoLicencias: ' + err)
                })
        },
        async post({ commit }, payload) {
            axios
                .post(`/api/alcance`, payload)
                .then(res => {
                    if (res.data.success) console.log('success')
                })
                .catch(error =>
                    console.error(`Error con la insertada de Alcance: ${error}`)
                )
        },
        async delete({ commit }, id) {
            console.log('Comienza borrado')
            axios
                .delete(`/api/alcance/${id}`)
                .then(res => {
                    console.log(res)
                })
                .catch(err => {
                    console.error('Error al borrar la Alcance: ' + err)
                })
        },
        async update({ commit }, payload) {
            console.log('Comienza actualizacion')
            axios
                .put(`/api/alcance/${payload.idAlcance}`, payload)
                .then(res => {
                    if (res.data.success) console.log('success')
                })
                .catch(err => {
                    console.error('Error al modificar Alcance' + err)
                })
        }
    },
    getters: {
        alcances(state) {
            return state.alcances
        }
    }
}
