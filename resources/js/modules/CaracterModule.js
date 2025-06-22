export default {
    namespaced: true,
    state: {
        caracters: []
    },
    mutations: {
        FETCH(state, data) {
            return (state.caracters = data)
        }
    },
    actions: {
        async all({ commit }) {
            await axios
                .get(`/api/caracter`)
                .then(res => {
                    commit('FETCH', res.data.data)
                })
                .catch(err => {
                    console.error('Error en get caracters: ' + err)
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
                        `Error con la insertada de caracters: ${error}`
                    )
                )
        },
        async delete({ commit }, id) {
            console.log('Comienza borrado')
            axios
                .delete(`/api/caracter/${id}`)
                .then(res => {
                    console.log(res)
                })
                .catch(err => {
                    console.error('Error al borrar la caracters: ' + err)
                })
        },
        async update({ commit }, payload) {
            console.log('Comienza actualizacion')
            axios
                .put(`/api/caracter/${payload.idCaracter}`, payload)
                .then(res => {
                    if (res.data.success) console.log('success')
                })
                .catch(err => {
                    console.error('Error al modificar caracters' + err)
                })
        }
    },
    getters: {
        caracters(state) {
            return state.caracters
        }
    }
}
