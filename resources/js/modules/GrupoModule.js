
export default {
    namespaced: true,

    state: {
        personas: [],
        personasActivas: [],
        personaNueva: {},
        parentescos: {},
        declarado: false,
        dias: 0
    },
    mutations: {
        ADD_PERSONA(state, persona) {
            state.personas.push(persona);
        },
        ADD_PERSONAS(state, personas) {
            state.personas = personas;
        },
        FETCH_PERSONAS_ACTIVAS(state, personas) {
            return (state.personasActivas = Array.from(personas));
        },
        FETCH_PERSONAS(state, personas) {
            return (state.personas = Array.from(personas));
        },
        FETCH_PERSONA(state, persona) {
            return (state.personaNueva = persona);
        },
        RESET_PERSONA(state) {
            state.personaNueva = {};
            return (state.personaNueva.idpersona = 0);
        },
        CREATE_PERSONA(state, persona) {
            state.personas.unshift(persona);
        },
        FETCH_PARENTESCOS(state, parentescos) {
            return (state.parentescos = Array.from(parentescos));
        },
        DECLARADO_DISCAPACITADO(state, declarado) {
            return (state.declarado = declarado);
        },
        DIAS_CONSUMIDOS(state, dias) {
            return (state.dias = dias);
        }
    },
    actions: {
        async getPersonasActivas({commit},idagente)
        {
            await window.axios
                .get(`/api/personas/activos/${idagente}`)
                .then((res) => {

                        commit("FETCH_PERSONAS_ACTIVAS", res.data.data);


                })
                .catch(err => {
                    console.log("Error en la busqueda de persona " + err);
                });
        },
        async getPersona({ commit }, documento) {
            console.log(documento[1]);
            console.log(documento[1] > 0);
            console.log(parseInt(documento[1]) > 0);
            if (parseInt(documento[1]) > 0 && documento[0] !== documento[1]) {
                await window.axios
                    .get(`/api/personas/documento/${documento[1]}`)
                    .then(res => {
                        console.log("estoy en then de persona");
                        if (res.data.data[0]) {
                            console.log("res.data.data[0] " + res.data.data[0]);
                            commit("FETCH_PERSONA", res.data.data[0]);
                        } else {
                            console.log("reset persona");
                            /*let persona = {
                                documento: documento[1],
                                nombre: "",
                                apellido: "",
                                fehca_nacimiento: "1970-01-01",
                                discapacidad: ""
                            };
                            commit("FETCH_PERSONA", persona);*/
                            //commit("RESET_PERSONA");
                        }
                    })
                    .catch(err => {
                        console.log("Error en la busqueda de persona " + err);
                    });
            } else {
                console.log("else getPersona ");
                let persona = {
                    idpersona: 0,
                    documento: documento[1],
                    nombre: "",
                    apellido: "",
                    fehca_nacimiento: "",
                    discapacidad: ""
                };
                commit("FETCH_PERSONA", persona);
                //commit("RESET_PERSONA");
            }
        },
        async getDiasRestanteDiscapacitado({commit}, obj) {
            if ('idlicencia' in obj) {
                await axios.get(`/api/personas/dias/${obj.idpersona}/${obj.idtipoLicencia}/${obj.idlicencia}`)
                    .then(res => {
                        commit("DIAS_CONSUMIDOS", res.data.data);
                    })
                    .catch((err) => {
                        console.error(`Error en el get dias restantes discapacitado ${err}`)
                    });
            } else {
                await axios.get(`/api/personas/dias/${obj.idpersona}/${obj.idtipoLicencia}`)
                    .then(res => {
                        commit("DIAS_CONSUMIDOS", res.data.data);
                    })
                    .catch((err) => {
                        console.error(`Error en el get dias restantes discapacitado ${err}`)
                    });
            }

        },
        async getDiscapacitado({commit}, documento) {
            if (parseInt(documento[1]) > 0 && documento[0] !== documento[1]) {
                await window.axios
                    .get(
                        `/api/personas/discapacitado/${documento[0]}/${documento[1]}`
                    )
                    .then(res => {
                        console.log(
                            "estoy en then de persona getDiscapacitado"
                        );
                        if (res.data.data[0]) {
                            console.log(
                                "buscando discapacitado " + res.data.data[0]
                            );
                            commit("DECLARADO_DISCAPACITADO", res.data.data[0]);
                        }
                    })
                    .catch(err => {
                        console.log("Error en la busqueda de persona " + err);
                    });
            } else {
                commit("DECLARADO_DISCAPACITADO", false);
            }
        },
        async getPersonaPorExpediente({ commit }, expediente) {
            await window.axios
                .get(`/api/personas/expediente/${expediente}`)
                .then(res => {
                    if (res.data.data[0]) {
                        console.log("res.data.data " + res.data.data);
                        commit("FETCH_PERSONAS", res.data.data);
                    } else {
                        console.log(
                            "no hubo resultado al buscar personas por expediente"
                        );
                    }
                })
                .catch(err => {
                    console.log("Error en la busqueda de persona " + err);
                });
        },
        async getParentescos({ commit }) {
            await window.axios
                .get(`/api/tipoparentescos`)
                .then(res => {
                    if (res.data.data[0]) {
                        console.log("res.data.data " + res.data.data);
                        commit("FETCH_PARENTESCOS", res.data.data);
                    } else {
                        console.log("no hubo resultado al buscar parentescos");
                    }
                })
                .catch(err => {
                    console.log("Error en la busqueda de parentescos " + err);
                });
        },
        async postPersona(context, personas) {
            console.log("inicio de post");
            await window.axios
                .post(`/api/persona/`, personas)
                .then(() => console.log(`Se hizo el post de las personas`))
                .catch(error =>
                    console.log(`Error con la insertada de Persona: ${error}`)
                );
        },
        async deletePersona({ commit }, expediente) {
            console.log("Comienza borrado");
            await window.axios
                .delete(`/api/grupofamiliar/${expediente.idgrupoFamiliar}`)
                .then(res => {
                    if (res.data.success) commit("DELETE_POST", expediente);
                })
                .catch(err => {
                    console.log("Error al borrar Expediente: " + err);
                });
        },
        async updatePersona({ commit }, expediente) {
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
        },
    },
    getters: {
        dias(state) {
            return state.dias;
        },
        get_declarado(state) {
            return state.declarado;
        },
        por_nombre(state) {
            return nombre => state.personas.filter(f => f.nombre === nombre);
        },
        familiar(state) {
            return id => state.personas.find(f => f.id === id);
        },
        por_dni(state) {
            return documento =>
                state.personas.filter(f => f.documento == documento);
        },
        find_documento(state) {
            return documento =>
                state.personas.find(f => f.documento == documento);
        },
        get_personas(state) {
            return state.personas;
        },
        get_parentescos(state) {
            return state.parentescos;
        },
        obtenerPersona(state) {
            return state.personaNueva;
        },
        obtenerPersonas(state) {
            return state.personas;
        },
        obtenerPersonasActivas(state) {
            return state.personasActivas;
        },
        foundPersona(state) {
            if (state.personaNueva) {
                return true;
            } else {
                false;
            }
        }
    }
};
