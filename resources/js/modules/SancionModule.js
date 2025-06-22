import { param } from 'jquery'

export default {
    namespaced: true,
    state: {
        sanciones: [],
        sancion: {},
        dosAnios: false
    },
    mutations: {
        RESET_STATE(state) {
            state.sanciones = []
            state.sancion = {}
            state.dosAnios = false
        },
        FETCH_SANCIONES(state, sanciones) {
            return (state.sanciones = sanciones)
        },
        FETCH_SANCION(state, sancion) {
            state.sanciones.push(sancion)
            return (state.sancion = sancion)
        },
        DELETE_SANCION(state, sancion) {
            let index = state.sanciones.findIndex(
                item => item.idsancion === sancion.idsancion
            )
            state.sanciones.splice(index, 1)
        },
        EDIT_SANCION(state, sancion) {
            let index = state.sanciones.findIndex(
                item => item.idsancion === sancion.idsancion
            )
            state.sanciones.splice(index, 1)
            state.sanciones.unshift(sancion)
        },
        VERIFY_SANCION(state, sancion) {
            state.dosAnios = sancion ? true : false
        }
    },
    actions: {
        getDefaultState({ commit }) {
            commit('RESET_STATE')
        },
        async fetch({ commit }, obj) {
            console.info(obj)
            const params = new URLSearchParams()
            if (obj.iddependencia > 0)
                params.append('iddependencia', obj.iddependencia)
            if (obj.idagente > 0) params.append('idagente', obj.idagente)
            if (obj.idsancion > 0) params.append('idsancion', obj.idagente)
            var result = await axios
                .get('/api/sanciones/fetch', { params: params })
                .then(res => {
                    commit('FETCH_SANCIONES', res.data.data)
                })
                .catch(err => {
                    console.error('Error en get sanciones: ' + err)
                })
        },
        async getSanciones({ commit }, idagente) {
            var result = await axios
                .get(`/api/sanciones/agente/${idagente}`)
                .then(res => {
                    commit('FETCH_SANCIONES', res.data.data)
                })
                .catch(err => {
                    console.error('Error en get sanciones: ' + err)
                })
        },
        async postSancion(context, sancion) {
            console.log('inicio de post')
            var result = await axios
                .post(`/api/sanciones`, sancion)
                .then(res => {
                    if (res.data.success)
                        context.commit('FETCH_SANCION', sancion[0])
                })
                .catch(error =>
                    console.error(`Error con la insertada de Sancion: ${error}`)
                )
        },
        async deleteSancion({ commit }, sancion) {
            console.log('Comienza borrado')
            var result = await axios
                .delete(`/api/sanciones/${sancion}`)
                .then(res => {
                    if (res.data.success) commit('DELETE_SANCION', sancion)
                })
                .catch(err => {
                    console.error('Error al borrar la Sancion: ' + err)
                })
        },
        async updateSancion({ commit }, sancion) {
            console.log('Comienza actualizacion')
            var result = await axios
                .put(`/api/sanciones/${sancion.idsancion}`, sancion)
                .then(res => {
                    if (res.data.success) commit('EDIT_SANCION', sancion)
                })
                .catch(err => {
                    console.error('Error al modificar Sancion' + err)
                })
        },
        async getSancion({ commit }, idsancion) {
            var result = await axios
                .get(`/api/sanciones/${idsancion}`)
                .then(res => {
                    commit('FETCH_SANCION', res.data.data)
                })
                .catch(err => {
                    console.log('Error en get Sancion: ', err)
                })
        },
        async existSancion({ commit }, idagente) {
            var result = await axios
                .get(`/api/sanciones/agente/exist/${idagente}`)
                .then(res => {
                    if (res.data.success) {
                        console.info(
                            `devuelve la api una sancion? ${
                                res.data.data ? true : false
                            }`
                        )
                        commit('VERIFY_SANCION', res.data.data)
                    }
                })
                .catch(err => {
                    console.error('Error en get Existe sancion: ', err)
                })
        }
    },
    getters: {
        sancion(state) {
            return idsancion =>
                state.sanciones.find(f => f.idsancion === idsancion)
        },
        sanciones(state) {
            return state.sanciones
        },
        existe(state) {
            return state.dosAnios
        }
    }
}
