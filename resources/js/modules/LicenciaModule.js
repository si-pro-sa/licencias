import moment from 'moment';
export default {
    namespaced: true,
    state: {
        idlicencia: 0,
        licencias: [],
        licencias_totales: [],
        licencias_dependientes: [],
        licencias_masivo_total: 0,
        licencias_dependientes_capacitacion: [],
        licencias_retroactiva: [],
        licencias_mensual: [],
        licencias_masivo_total_capacitacion: 0,
        licencia: {},
        personas: [],
        dia: 0,
        feriados: [],
        role: '',
        guardias: '',
        saldos: [],
        saldo: {},
        saldos_totales: [],
        saldos_personas: [],
        total_licencias: 0,
        capacitaciones: [],
        stateTrigger: false,
        controller: null,
        tipoLicencias: [],
        diagnostico: {},
    },
    mutations: {
        RESET_STATE(state) {
            state.idlicencia = 0;
            state.licencias = [];
            state.licencias_totales = [];
            state.licencias_dependientes = [];
            state.licencias_masivo_total = 0;
            state.licencias_retroactiva = [];
            state.licencias_mensual = [];
            state.licencia = {};
            state.personas = [];
            state.dia = 0;
            state.feriados = [];
            state.role = '';
            state.guardias = '';
            state.saldos = [];
            state.saldo = {};
            state.saldos_totales = [];
            state.total_licencias = 0;
            state.capacitaciones = [];
            state.tipoLicencias = [];
            state.diagnostico = {};
        },
        ADD_PERSONAS_SALDOS(state, data) {
            state.saldos_personas = [...data];
        },
        FETCH_CAPACITACIONES(state, data) {
            state.capacitaciones = data;
        },
        FETCH_LICENCIAS_MASIVO_TOTAL(state, dato) {
            state.licencias_masivo_total = dato;
        },
        FETCH_PERSONAS(state, personas) {
            return (state.personas = Array.from(personas));
        },
        ADD_LICENCIA(state, licencia) {
            state.licencias.push(licencia);
        },
        ADD_LICENCIAS(state, licencias) {
            state.licencias = licencias;
        },
        CREATE_LICENCIA(state, licencia) {
            state.licencia.unshift(licencia);
        },
        FETCH_LICENCIAS(state, licencias) {
            return (state.licencias = licencias);
        },
        FETCH_LICENCIAS_TOTALES(state, licencias_totales) {
            return (state.licencias_totales = licencias_totales);
        },
        FETCH_LICENCIAS_DEPENDIENTES(state, licencias_dependientes) {
            return (state.licencias_dependientes = licencias_dependientes);
        },
        FETCH_LICENCIAS_DEPENDIENTES_MENSUAL(state, licencias_dependientes) {
            return (state.licencias_mensual = licencias_dependientes);
        },
        FETCH_LICENCIAS_DEPENDIENTES_RETROACTIVA(
            state,
            licencias_dependientes,
        ) {
            return (state.licencias_retroactiva = licencias_dependientes);
        },
        FETCH_LICENCIAS_DEPENDIENTES_CAPACITACION(
            state,
            licencias_dependientes,
        ) {
            return (state.licencias_dependientes_capacitacion =
                licencias_dependientes);
        },
        FETCH_LICENCIAS_MASIVO_TOTAL_CAPACITACION(state, dato) {
            state.licencias_masivo_total_capacitacion = dato;
        },
        FETCH_LICENCIA(state, licencia) {
            return (state.licencia = licencia);
        },
        DELETE_LICENCIA(state, licencia) {
            let index = state.licencias.findIndex(
                (item) => item.idlicencia === licencia.idlicencia,
            );
            state.licencias.splice(index, 1);
        },
        EDIT_LICENCIA(state, licencia) {
            let index = state.licencias.findIndex(
                (item) => item.idlicencia === licencia.idlicencia,
            );
            state.licencias.splice(index, 1);
            state.licencias.unshift(licencia);
        },
        ADD_DIA(state, dia) {
            state.dia = dia;
        },
        FETCH_SALDOS(state, saldos) {
            return (state.saldos = saldos);
        },
        FETCH_SALDO(state, saldo) {
            return (state.saldo = saldo);
        },
        FETCH_SALDOS_TOTALES(state, saldos) {
            return (state.saldos_totales = saldos);
        },
        CREATE_SALDO(state, saldo) {
            state.saldo.unshift(saldo);
        },
        ADD_SALDO(state, saldo) {
            state.saldos.push(saldo);
        },
        DELETE_SALDO(state, saldo) {
            let index = state.saldos.findIndex(
                (item) => item.idlicencia === saldo.idlicencia,
            );
            state.saldos.splice(index, 1);
        },
        EDIT_SALDO(state, saldo) {
            let index = state.saldos.findIndex(
                (item) => item.idlicencia === saldo.idlicencia,
            );
            state.saldos.splice(index, 1);
            state.saldos.unshift(saldo);
        },
        FETCH_FERIADOS(state, feriados) {
            state.feriados = feriados;
        },
        FETCH_TOTALLICENCIAS(state, total_licencias) {
            state.total_licencias = total_licencias;
        },
        BLOCK_CARD(state, payload) {
            state.bloquearTarjetaAgente = payload;
        },
        FETCH_TIPO_LICENCIAS(state, tipoLicencias) {
            return (state.tipoLicencias = tipoLicencias);
        },
        FETCH_DIAGNOSTICO(state, diagnostico) {
            return (state.diagnostico = diagnostico);
        },
        ADD_DIAGNOSTICO(state, diagnostico) {
            state.diagnostico = diagnostico;
        },
        ADD_LICENCIA_ID(state, idlicencia) {
            state.idlicencia = idlicencia;
        },
    },
    actions: {
        async getLicenciasDependientesCapacitacion({ commit, state }, obj) {
            if (state.stateTrigger === true) {
                state.controller.abort();
                state.stateTrigger = false;
                console.log('abortar conexion');
            }
            state.controller = new AbortController();
            state.stateTrigger = true;
            let params = new URLSearchParams();
            const { page, itemsPerPage, sortBy, sortDesc } = obj.options;
            const { dependencia } = obj;
            const {
                efector,
                codigo_nombre,
                documento,
                apellido_nombre,
                tipoLicencia,
                dias,
                idlicencia,
                fecha_pedido_inicio,
                fecha_pedido_final,
                primer_visado,
                segundo_visado,
                fecha_efectiva_inicio,
                fecha_efectiva_final,
                fecha_visado_primero,
                fecha_visado_segundo,
                cuarta_visado,
                fecha_interrupcion_inicio,
                alcance,
                caracter,
                tipo_evento,
                evento_nombre,
                evento_lugar,
            } = obj.filters;
            params.append('idagente', obj.idagente);
            params.append('page', page);
            params.append('itemsPerPage', itemsPerPage);
            params.append('sortBy', sortBy);
            params.append('sortDesc', sortDesc);
            if (efector) {
                params.append('efector', efector.toUpperCase());
            }

            if (codigo_nombre) {
                params.append('codigo_nombre', codigo_nombre.toUpperCase());
            }

            if (documento) {
                params.append('documento', documento);
            }

            if (apellido_nombre) {
                params.append('apellido_nombre', apellido_nombre.toUpperCase());
            }

            if (tipoLicencia) {
                params.append('tipoLicencia', tipoLicencia.toLowerCase());
            }

            if (dias) {
                params.append('dias', dias);
            }

            if (idlicencia) {
                params.append('idlicencia', idlicencia);
            }

            if (fecha_pedido_inicio) {
                params.append('fecha_pedido_inicio', fecha_pedido_inicio);
            }

            if (fecha_pedido_final) {
                params.append('fecha_pedido_final', fecha_pedido_final);
            }

            if (fecha_visado_primero) {
                params.append('fecha_visado_primero', fecha_visado_primero);
            }

            if (fecha_visado_segundo) {
                params.append('fecha_visado_segundo', fecha_visado_segundo);
            }

            if (primer_visado >= 0) {
                params.append('primer_visado', primer_visado);
            }

            if (segundo_visado >= 0) {
                params.append('segundo_visado', segundo_visado);
            }

            if (cuarta_visado) {
                params.append('cuarta_visado', cuarta_visado);
            }

            if (fecha_efectiva_inicio) {
                params.append('fecha_efectiva_inicio', fecha_efectiva_inicio);
            }

            if (fecha_efectiva_final) {
                params.append('fecha_efectiva_final', fecha_efectiva_final);
            }
            if (fecha_interrupcion_inicio) {
                params.append(
                    'fecha_interrupcion_inicio',
                    fecha_interrupcion_inicio,
                );
            }
            if (alcance) {
                params.append('alcance', alcance);
            }
            if (caracter) {
                params.append('caracter', caracter);
            }
            if (tipo_evento) {
                params.append('tipoEvento', tipo_evento);
            }

            if (evento_lugar) {
                params.append('evento_lugar', evento_lugar);
            }

            if (evento_nombre) {
                params.append('evento_nombre', evento_nombre);
            }

            if (dependencia) {
                params.append('dependencia', dependencia);
            }

            await axios
                .get(`/api/licencias/masivo/capacitacion/`, {
                    params: params,
                    signal: state.controller.signal,
                })
                .then((res) => {
                    commit(
                        'FETCH_LICENCIAS_DEPENDIENTES_CAPACITACION',
                        res.data.data.data,
                    );
                    commit(
                        'FETCH_LICENCIAS_MASIVO_TOTAL_CAPACITACION',
                        res.data.data.total,
                    );
                })
                .catch((err) => {
                    console.error('Error en getLicencias: ' + err);
                })
                .finally(() => {
                    state.stateTrigger = false;
                });
        },
        getDefaultState({ commit }) {
            commit('RESET_STATE');
        },
        getPersonasSaldos({ commit }, { idagente }) {
            axios
                .get(`/api/licencias/saldos/salud/personas/${idagente}`)
                .then((res) => {
                    commit('ADD_PERSONAS_SALDOS', res.data.data);
                    return res;
                })
                .catch((err) => {
                    console.error('Error en getPersonasSaldos: ' + err);
                });
        },
        async getDiasPosibles({ commit }, idagente) {
            await axios
                .get(`/api/licencias/dias/${idagente}`)
                .then((res) => {
                    commit('ADD_DIA', res.data.data);
                })
                .catch((err) => {
                    console.error('Error en getDiasPosibles: ' + err);
                });
        },
        async getFeriados({ commit }) {
            await axios
                .get(`/api/licencias/feriados`)
                .then((res) => {
                    commit('FETCH_FERIADOS', res.data.data);
                })
                .catch((err) => {
                    console.error('Error en getFeriados: ' + err);
                });
        },
        async getPersonasActivas({ commit }, arr) {
            await axios
                .get(`/api/licencias/personas/${arr[1]}`)
                .then((res) => {
                    commit('FETCH_PERSONAS', res.data.data);
                })
                .catch((err) => {
                    console.error('Error en getPersonasActivas: ' + err);
                });
        },
        async getPersonasDiscapacitadaActivas({ commit }, idagente) {
            await axios
                .get(`/api/licencias/personasDiscapacitada/${idagente}`)
                .then((res) => {
                    commit('FETCH_PERSONAS', res.data.data);
                })
                .catch((err) => {
                    console.error(
                        'Error en getPersonasDiscapacitadaActivas: ' + err,
                    );
                });
        },
        async getLicenciasSaldos({ commit }, obj) {
            await axios
                .get(
                    `/api/saldos/agente/${obj['idagente']}/${obj['tipoLicencia']}`,
                )
                .then((res) => {
                    commit('FETCH_SALDOS', res.data.data);
                })
                .catch((err) => {
                    console.error('Error en getLicenciasSaldos: ' + err);
                });
        },
        async fetchLicenciasSaldos({ commit }, obj) {
            await axios
                .get(`/api/saldos/agente/${obj['idagente']}`)
                .then((res) => {
                    commit('FETCH_SALDOS_TOTALES', res.data.data);
                })
                .catch((err) => {
                    console.error('Error en fetchLicenciasSaldos: ' + err);
                });
        },
        async getLicenciasSaldosTotalesHabiles({ commit }, obj) {
            await axios
                .get(`/api/saldos/habiles/${obj['idagente']}`)
                .then((res) => {
                    commit('FETCH_SALDOS_TOTALES', res.data.data);
                })
                .catch((err) => {
                    console.error('Error en getLicenciasSaldosTotales: ' + err);
                });
        },
        async getLicencia({ commit }, idlicencia) {
            await axios
                .get(`/api/licencias/${idlicencia}`)
                .then((res) => {
                    commit('FETCH_LICENCIA', res.data.data);
                })
                .catch((err) => {
                    console.error('Error en getLicencia: ', err);
                });
        },
        async getLicencias({ commit }, obj) {
            await axios
                .get(
                    `/api/licencias/agente/${obj['idagente']}/${obj['tipoLicencia']}`,
                )
                .then((res) => {
                    commit('FETCH_LICENCIAS', res.data.data);
                })
                .catch((err) => {
                    console.error('Error en getLicencias: ' + err);
                });
        },
        async getLicenciasTotales({ commit }, obj) {
            await axios
                .get(`/api/licencias/agente/${obj['idagente']}`)
                .then((res) => {
                    commit('FETCH_LICENCIAS_TOTALES', res.data.data);
                })
                .catch((err) => {
                    console.log('Error en getLicencias: ' + err);
                });
        },
        async getLicenciasDependientes({ commit, state }, obj) {
            if (state.stateTrigger === true) {
                state.controller.abort();
                state.stateTrigger = false;
                console.log('abortar conexion');
            }
            state.controller = new AbortController();
            state.stateTrigger = true;
            let params = new URLSearchParams();
            const { page, itemsPerPage, sortBy, sortDesc } = obj.options;
            const { dependencia } = obj;
            const {
                efector,
                codigo_nombre,
                documento,
                apellido_nombre,
                tipoLicencia,
                dias,
                idlicencia,
                fecha_pedido_inicio,
                fecha_pedido_final,
                primer_visado,
                segundo_visado,
                fecha_efectiva_inicio,
                fecha_efectiva_final,
                cuarta_visado,
                fecha_interrupcion_inicio,
            } = obj.filters;
            params.append('idagente', obj.idagente);
            params.append('page', page);
            params.append('itemsPerPage', itemsPerPage);
            params.append('sortBy', sortBy);
            params.append('sortDesc', sortDesc);
            if (efector) {
                params.append('efector', efector.toUpperCase());
            }

            if (codigo_nombre) {
                params.append('codigo_nombre', codigo_nombre.toUpperCase());
            }

            if (documento) {
                params.append('documento', documento);
            }

            if (apellido_nombre) {
                params.append('apellido_nombre', apellido_nombre.toUpperCase());
            }

            if (tipoLicencia) {
                params.append('tipoLicencia', tipoLicencia.toLowerCase());
            }

            if (dias) {
                params.append('dias', dias);
            }

            if (idlicencia) {
                params.append('idlicencia', idlicencia);
            }

            if (fecha_pedido_inicio) {
                params.append('fecha_pedido_inicio', fecha_pedido_inicio);
            }

            if (fecha_pedido_final) {
                params.append('fecha_pedido_final', fecha_pedido_final);
            }

            if (primer_visado >= 0) {
                params.append('primer_visado', primer_visado);
            }

            if (segundo_visado >= 0) {
                params.append('segundo_visado', segundo_visado);
            }

            if (cuarta_visado) {
                params.append('cuarta_visado', cuarta_visado);
            }

            if (fecha_efectiva_inicio) {
                params.append('fecha_efectiva_inicio', fecha_efectiva_inicio);
            }

            if (fecha_efectiva_final) {
                params.append('fecha_efectiva_final', fecha_efectiva_final);
            }
            if (fecha_interrupcion_inicio) {
                params.append(
                    'fecha_interrupcion_inicio',
                    fecha_interrupcion_inicio,
                );
            }
            if (dependencia) {
                params.append('dependencia', dependencia);
            }
            console.log('se ejecuta dependientes');
            console.log('params');
            console.log(params);
            console.log(primer_visado);
            console.log(segundo_visado);
            await axios
                .get(`/api/licencias/dependiente/`, {
                    params: params,
                    signal: state.controller.signal,
                })
                .then((res) => {
                    console.log(res);
                    commit('FETCH_LICENCIAS_DEPENDIENTES', res.data.data.data);
                    commit('FETCH_LICENCIAS_MASIVO_TOTAL', res.data.data.total);
                })
                .catch((err) => {
                    console.error('Error en getLicencias: ' + err);
                })
                .finally(() => {
                    state.stateTrigger = false;
                });
        },
        async getLicenciasConsulta({ commit }, obj) {
            console.dir(obj);
            const params = new URLSearchParams();
            params.append('fecha_desde', obj.fecha_desde);
            params.append('fecha_hasta', obj.fecha_hasta);
            if ('dependencia' in obj) {
                params.append('dependencia', obj.dependencia);
            }
            if ('tipo_licencias' in obj) {
                params.append('tipo_licencias', obj.tipo_licencias);
            }
            await axios
                .get(`/api/licencias/consulta`, { params: params })
                .then((res) => {
                    commit('FETCH_LICENCIAS_DEPENDIENTES', res.data.data);
                })
                .catch((err) => {
                    console.error('Error en getLicencias: ' + err);
                });
        },
        async getLicenciasMensuales({ commit }, obj) {
            const params = new URLSearchParams();
            params.append('mes', obj.mes);
            if ('dependencia' in obj) {
                params.append('dependencia', obj.dependencia);
            }
            if ('tipo_licencias' in obj) {
                params.append('tipo_licencias', obj.tipo_licencias);
            }
            await axios
                .get(`/api/licencias/consulta/mensual`, { params: params })
                .then((res) => {
                    commit(
                        'FETCH_LICENCIAS_DEPENDIENTES_MENSUAL',
                        res.data.data,
                    );
                })
                .catch((err) => {
                    console.error('Error en getLicencias: ' + err);
                });
        },
        async getLicenciasRetroactiva({ commit }, obj) {
            const params = new URLSearchParams();
            params.append('mes', obj.mes);
            if ('dependencia' in obj) {
                params.append('dependencia', obj.dependencia);
            }
            if ('tipo_licencias' in obj) {
                params.append('tipo_licencias', obj.tipo_licencias);
            }
            await axios
                .get(`/api/licencias/consulta/mensual/retroactiva`, {
                    params: params,
                })
                .then((res) => {
                    commit(
                        'FETCH_LICENCIAS_DEPENDIENTES_RETROACTIVA',
                        res.data.data,
                    );
                })
                .catch((err) => {
                    console.error('Error en getLicencias: ' + err);
                });
        },
        async exportXLS({ commit }, obj) {
            await axios.put(`/api/licencias/exportar`, obj);
        },
        async postLicencia(context, licencia) {
            // si la licencia es de salud ocupacional entonces agregar el diagnostico
            let salud_ocupacional = [
                1, 2, 3, 4, 7, 8, 11, 21, 22, 38, 40, 39, 38, 37,
            ];
            let licencia_id = 0;
            await axios
                .post(`/api/licencias/complete`, licencia)
                .then((res) => {
                    licencia_id = res.data.data.idlicencia;
                    context.commit('ADD_LICENCIA', licencia[0]);
                })
                .catch((error) =>
                    console.error(
                        `Error con la insertada de Licencia: ${error}`,
                    ),
                );
            if (
                salud_ocupacional.includes(licencia[0].idtipoLicencia) &&
                licencia[4]?.codigo !== ''
            ) {
                const formData = await licencia[4];
                await axios
                    .post(
                        `/api/diagnosticos/licencia/${licencia_id}`,
                        formData,
                        {
                            headers: { 'Content-Type': 'multipart/form-data' },
                        },
                    )
                    .then(() => console.log('Diagnostico agregado'))
                    .catch((error) =>
                        console.error(
                            `Error con la insertada de Diagnostico: ${error}`,
                        ),
                    );
            }
        },
        async primerVisadoTodo({ commit, dispatch }, obj) {
            await axios.put(`/api/licencias/masivo/primer`, obj);
        },
        async segundoVisadoTodo({ commit, dispatch }, obj) {
            await axios.put(`/api/licencias/masivo/segundo`, obj);
        },
        async deleteLicencia({ commit }, licencia) {
            await axios
                .delete(`/api/licencias/${licencia.idlicencia}`)
                .then((res) => {
                    if (res.data.success) commit('DELETE_LICENCIA', licencia);
                })
                .catch((err) => {
                    console.error('Error al borrar Licencia: ' + err);
                });
        },
        async desvisarLicencia({ commit }, licencia) {
            console.log('Comienza desvisado');
            await window.axios
                .put(`/api/licencias/desvisar/${licencia.idlicencia}`)
                .then((res) => {
                    if (res.data.success) commit('EDIT_LICENCIA', licencia);
                })
                .catch((err) => {
                    console.error('Error al modificar Licencia' + err);
                });
        },
        async updateLicencia({ commit }, licencia) {
            let salud_ocupacional = [
                1, 2, 3, 4, 7, 8, 11, 21, 22, 38, 40, 39, 38, 37,
            ];
            let licencia_id = licencia[0].idlicencia;
            await axios
                .put(`/api/licencias/${licencia_id}`, licencia)
                .then((res) => {
                    if (res.data.success) commit('EDIT_LICENCIA', licencia);
                })
                .catch((err) => {
                    console.error('Error al modificar Licencia' + err);
                });
            if (
                salud_ocupacional.includes(licencia[0].idtipoLicencia) &&
                licencia[4]?.codigo !== ''
            ) {
                const formData = await licencia[4];
                await axios
                    .put(
                        `/api/diagnosticos/licencia/${licencia_id}`,
                        formData,
                        {
                            headers: { 'Content-Type': 'multipart/form-data' },
                        },
                    )
                    .then(() => console.log('Diagnostico actualizado'))
                    .catch((error) =>
                        console.error(
                            `Error con la insertada de Diagnostico: ${error}`,
                        ),
                    );
            }
        },
        async getCapacitacion({ commit }, obj) {
            const params = new URLSearchParams();
            params.append('fecha_desde', obj[0]);
            params.append('fecha_hasta', obj[1]);
            if (obj.length > 2) {
                params.append('dependencia', obj[2]);
            }
            try {
                const res = await axios.get(`/api/licencias/capacitaciones`, {
                    params: params,
                });
                if (res) {
                    commit('FETCH_CAPACITACIONES', res.data.data);
                }
            } catch (error) {
                console.error(error);
            }
        },
        async fetchTipoLicencias({ commit }) {
            try {
                const res = await axios.get(`/api/tipo-licencias`);
                if (res) {
                    commit('FETCH_TIPO_LICENCIAS', res.data.data);
                }
            } catch (error) {
                console.error(error);
            }
        },
    },
    getters: {
        licencia(state) {
            return (idlicencia) =>
                state.licencias.find((f) => f.idlicencia === idlicencia);
        },
        licencias(state) {
            return state.licencias_totales;
        },
        inasistencias(state) {
            return state.licencias_totales.filter(
                (el) => el.idtipoLicencia === 33 || el.idtipoLicencia === 34,
            );
        },
        licencias_masivo_total(state) {
            return state.licencias_masivo_total;
        },
        licencias_masivo_total_capacitacion(state) {
            return state.licencias_masivo_total_capacitacion;
        },
        licencias_mensual(state) {
            return state.licencias_mensual;
        },
        licencias_retroactiva(state) {
            return state.licencias_retroactiva;
        },
        saldos_personas(state) {
            return state.saldos_personas;
        },
        total_licencias(state) {
            return state.total_licencias;
        },
        licencias_dependientes(state) {
            return state.licencias_dependientes;
        },
        licencias_dependientes_capacitacion(state) {
            return state.licencias_dependientes_capacitacion;
        },
        obtenerLicencias(state) {
            return state.licencias;
        },
        obtenerLicenciasTotales(state) {
            return state.licencias_totales;
        },
        obtenerSaldos(state) {
            return state.saldos;
        },
        obtenerSaldosTotales(state) {
            return state.saldos_totales;
        },
        obtenerLicenciasFiltradas(state, tipo_licencia) {
            return state.licencias_totales.filter(
                (f) => f.idtipoLicencia === tipo_licencia,
            );
        },
        obtenerDias(state) {
            return _.sumBy(state.licencias, 'dias');
        },
        capacitaciones(state) {
            return state.capacitaciones;
        },
        personas(state) {
            return state.personas;
        },
        tipoLicencia: (state) => (idtipoLicencia) => {
            if (idtipoLicencia === 'undefined') return '';
            return state.tipoLicencias.find(
                (f) => f.idtipoLicencia === idtipoLicencia,
            );
        },
        diagnostico(state) {
            return state.diagnostico;
        },
    },
};
