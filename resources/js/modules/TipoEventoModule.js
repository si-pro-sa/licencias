export default {
    namespaced: true,
    state: {
        tipoEventos: []
    },
    mutations: {
        FETCH(state, data) {
            return (state.tipoEventos = data)
        }
    },
    actions: {
        async all({ commit }) {
            await axios
                .get(`/api/tipo-evento`)
                .then(res => {
                    commit('FETCH', res.data.data)
                })
                .catch(err => {
                    console.error('Error en get tipo-evento: ' + err)
                })
        },
        async post({ commit }, payload) {
            axios
                .post(`/api/tipo-evento`, payload)
                .then(res => {
                    if (res.data.success) console.log('success')
                })
                .catch(error =>
                    console.error(
                        `Error con la insertada de tipo-evento: ${error}`
                    )
                )
        },
        async delete({ commit }, id) {
            console.log('Comienza borrado')
            axios
                .delete(`/api/tipo-evento/${id}`)
                .then(res => {
                    console.log(res)
                })
                .catch(err => {
                    console.error('Error al borrar la tipo-evento: ' + err)
                })
        },
        async update({ commit }, payload) {
            console.log('Comienza actualizacion')
            axios
                .put(`/api/tipo-evento/${payload.idTipoEvento}`, payload)
                .then(res => {
                    if (res.data.success) console.log('success')
                })
                .catch(err => {
                    console.error('Error al modificar tipo-evento' + err)
                })
        }
    },
    getters: {
        tipoEventos(state) {
            return state.tipoEventos
        }
    }
}
