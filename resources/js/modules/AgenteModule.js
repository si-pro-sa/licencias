export default {
    namespaced: true,

    state: {
        logueado: 11806, // Estela Nuñez idagente
        agente: {},
        agentes: [],
        dependencias: [],
        puesto: {},
        horarios: [],
        capacitaciones: [],
        errores_agente: '',
    },
    mutations: {
        CREATE_DEPENDENCIAS(state, dependencia) {
            state.dependencias.unshift(dependencia);
        },
        FETCH_DEPENDENCIAS(state, dependencias) {
            return (state.dependencias = dependencias);
        },
        FETCH_CAPACITACIONES(state, data) {
            state.capacitaciones = data;
        },
        DELETE_DEPENDENCIA(state, dependencia) {
            let index = state.dependencias.findIndex(
                (item) => item.iddependencia === dependencia.iddependencia,
            );
            state.dependencias.splice(index, 1);
        },
        FETCH_AGENTE(state, agente) {
            return (state.agente = agente);
        },
        FETCH_AGENTES(state, agentes) {
            return (state.agentes = agentes);
        },
        RESET_AGENTE(state) {
            state.agente = { idagente: 0 };
        },
        RESET_HORARIOS(state) {
            state.horarios = [];
        },
        RESET_AGENTES(state) {
            state.agentes = [];
        },
        RESET_CAPACITACIONES(state) {
            state.capacitaciones = [];
        },
        CREATE_AGENTE(state, agente) {
            state.agente.unshift(agente);
        },
        FETCH_PUESTO(state, puesto) {
            return (state.puesto = puesto);
        },
        FETCH_HORARIOS(state, horarios) {
            return (state.horarios = horarios);
        },
        // Guardar los mensajes de errores de la validacion dependiendo de que api viene guardar
        SET_ERRORES_AGENTE(state, errores_agente) {
            state.errores_agente = errores_agente;
        },
    },
    actions: {
        setAgente({ commit }, item) {
            commit('FETCH_AGENTE', item);
        },
        async getAgente({ commit }, documento) {
            if (parseInt(documento[1]) > 0) {
                await axios
                    .get(
                        `/api/grupofamiliar/${documento[0]}/agente/${documento[1]}`,
                    )
                    .then((res) => {
                        if (res.data.data[0]) {
                            commit('RESET_AGENTES');
                            commit('FETCH_AGENTE', res.data.data[0]);
                        } else {
                            console.error('reset agente');
                            commit('RESET_AGENTE');
                        }
                    })
                    .catch((err) => {
                        console.error('agente ' + err);
                        console.error(
                            'Error en la busqueda de agente ' + err.message,
                        );
                    });
            } else {
                commit('RESET_AGENTE');
            }
        },
        /**
         * Jefe de personal, director del efector y ref RRHH tienen esto los demas ven todo
         * @param commit
         * @param documento
         * @returns {Promise<void>}
         */
        async getAgentesHijos({ commit }, obj) {
            const params = new URLSearchParams();
            if (obj.documento > 0) params.append('documento', obj.documento);
            if (obj.nombre.length > 2) params.append('nombre', obj.nombre);
            if (obj.apellido.length > 2)
                params.append('apellido', obj.apellido);
            if (obj.funcion > 0) params.append('funcion', obj.funcion);
            if ('dependencia' in obj)
                params.append('dependencia', obj.dependencia);
            await axios
                .get(`/api/agente/hijos`, { params: params })
                .then((res) => {
                    if (res.data.success && res.data.data != null) {
                        commit('FETCH_AGENTES', res.data.data);
                    } else {
                        commit('RESET_AGENTES');
                    }
                })
                .catch((err) => {
                    commit('RESET_AGENTES');
                    console.error('Error en la busqueda de agentes ' + err);
                });
        },
        /**
         *
         * @param commit
         * @param obj
         * @returns {Promise<void>}
         */
        async getAgenteRapido({ commit }, obj) {
            console.log('agente rapido');
            const params = new URLSearchParams();
            if (obj.documento > 0) params.append('documento', obj.documento);
            // Mandamos el objeto de la dependencia del agente
            // El objeto dependencia tiene que tener el id de la dependencia y el nombre de la dependencia
            if ('dependencia' in obj) {
                params.append('dependencia', obj.dependencia);
            }
            await axios
                .get(`/api/agente/rapido`, { params: params })
                .then((res) => {
                    if (res.data.success && res.data.data != null) {
                        commit('FETCH_AGENTE', res.data.data[0]);
                        commit('FETCH_HORARIOS', res.data.data[1]);
                    } else {
                        commit('RESET_AGENTE');
                        commit('RESET_HORARIOS');
                    }
                })
                .catch((err) => {
                    commit('SET_ERRORES_AGENTE', err.response.data.message);
                    commit('RESET_AGENTE');
                    commit('RESET_HORARIOS');
                });
        },
        async getCapacitacion({ commit }, obj) {
            try {
                const res = await axios.get(
                    `/api/agentes/capacitaciones/${obj}`,
                );
                if (res) {
                    commit('FETCH_CAPACITACIONES', res.data.data);
                }
            } catch (error) {
                console.error(error);
            }
        },
        resetCapacitaciones({ commit }) {
            commit('RESET_CAPACITACIONES');
        },
    },
    getters: {
        foundAgente: (state) => {
            if (state.agente.idagente > 0) {
                return true;
            } else {
                return false;
            }
        },
        agente: (state) => {
            return state.agente;
        },
        dependencias: (state) => {
            return state.dependencias;
        },
        puesto: (state) => {
            return state.puesto;
        },
        agentes: (state) => {
            return state.agentes;
        },
        horarios: (state) => {
            return state.horarios;
        },
        capacitaciones: (state) => {
            return state.capacitaciones;
        },
        errores_agente: (state) => {
            return state.errores_agente;
        },
    },
};
