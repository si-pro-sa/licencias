export default {
    namespaced: true,
    state: {
        user: {},
        agente: {},
        role_display: '',
        role_name: '',
        tipoLicencia: 0,
        permiso: false,
        permisos: [],
        dependencias: [],
        dependencia: {},
        esPuestoValido: false,
        dependencia_padre: {},
    },
    mutations: {
        FETCH_PERMISO(state, permiso) {
            return (state.permiso = permiso);
        },
        FETCH_PERMISOS(state, permisos) {
            return (state.permisos = permisos);
        },
        FETCH_AGENTE(state, agente) {
            return (state.agente = agente);
        },
        FETCH_DEPENDENCIA(state, dependencia) {
            return (state.dependencia = dependencia);
        },
        FETCH_DEPENDENCIAS(state, dependencias) {
            return (state.dependencias = dependencias);
        },
        FETCH_DEPENDENCIA_PADRE(state, dependencia_padre) {
            return (state.dependencia_padre = dependencia_padre);
        },
        FETCH_USER(state, user) {
            return (state.user = user);
        },
        FETCH_ROLE(state, role) {
            return (state.role = role);
        },
        FETCH_ROLE_DISPLAY(state, role_display) {
            return (state.role_display = role_display);
        },
        FETCH_ROLE_NAME(state, role_name) {
            return (state.role_name = role_name);
        },
        FETCH_PUESTO(state, esPuestoValido) {
            return (state.esPuestoValido = esPuestoValido);
        },
        RESET_STATE(state) {
            state.user = {};
            state.agente = {};
            state.role_display = '';
            state.role_name = '';
            state.tipoLicencia = 0;
            state.permiso = false;
            state.permisos = [];
            state.dependencias = [];
            state.esPuestoValido = false;
            state.dependencia = {};
            state.dependencia_padre = {};
        },
    },
    actions: {
        getDefaultState({ commit }) {
            commit('RESET_STATE');
        },
        async puestoValidado({ commit }) {
            await axios
                .get('api/user/puestoValidado')
                .then((res) => {
                    if (res.data === true) {
                        commit('FETCH_PUESTO', res.data);
                    }
                    console.log('puesto validado ', res.data);
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        async login({ commit }, obj) {
            await axios
                .post('api/user/login', obj)
                .then((res) => {
                    commit('FETCH_USER', res.data);
                    console.log(res.data);
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        async logout({ commit }) {
            await axios
                .post('api/user/logout')
                .then((res) => {
                    return res;
                })
                .catch((error) => {
                    console.log('Error del logout');
                });
        },
        async getUser({ commit }, idusuario) {
            await axios
                .get(`/api/user/licencias`)
                .then((res) => {
                    commit('FETCH_USER', res.data[0]);
                    commit('FETCH_ROLE_DISPLAY', res.data[1]);
                    commit('FETCH_ROLE_NAME', res.data[2]);
                })
                .catch((err) => {
                    console.log('Error en get user: ', err);
                });
        },
        async getPermisos({ commit }) {
            await axios
                .get(`/api/user/permisos`)
                .then((res) => {
                    console.log('Permisos obtenidos: ', res.data);
                    commit('FETCH_PERMISOS', res.data);
                })
                .catch((err) => {
                    console.log('Error en get permisos: ', err);
                });
        },
        async getDatosAgente({ commit, state }) {
            await axios
                .get(`/api/user/agente/${state?.user?.idusuario}`)
                .then((res) => {
                    console.log(
                        'datos usuario  traidos ',
                        state?.user?.idusuario,
                    );
                    commit('FETCH_AGENTE', res.data.data);
                })
                .catch((err) => {
                    console.log('Error en get agente del usuario: ', err);
                });
        },
        async getDependenciasVisibles({ commit, state }) {
            await axios
                .get(`/api/user/${state.user.idusuario}/dependencias-visibles`)
                .then((res) => {
                    commit('FETCH_DEPENDENCIAS', res.data);
                    commit('FETCH_DEPENDENCIA_PADRE', {
                        dependencia:
                            res.data.agente.departmentDetails.DependenciaPadre,
                        iddependencia:
                            res.data.agente.departmentDetails.IdDependencia,
                    });
                })
                .catch((err) => {
                    console.log('Error en get dependencias del agente: ', err);
                });
        },
        async can({ commit }, obj) {
            await axios
                .get(`/api/user/permisos/${obj}`)
                .then((res) => {
                    console.log(' El permiso devuelto tuiene : ', res.data);
                    commit('FETCH_PERMISO', res.data);
                })
                .catch((err) => {
                    console.log('Error en get permisos: ', err);
                });
        },
        setDependencia({ commit }, dependencia) {
            commit('FETCH_DEPENDENCIA', dependencia);
        },
    },
    getters: {
        esPuestoValido(state) {
            return state.esPuestoValido;
        },
        tipoLicencia(state) {
            return state.tipoLicencia;
        },
        user(state) {
            return state.user;
        },
        role_display(state) {
            return state.role_display;
        },
        role_name(state) {
            return state.role_name;
        },
        role(state) {
            let result = 0;
            switch (state.role_name) {
                case 'despacho':
                case 'gerencia':
                    result = 5;
                    break;
                case 'saludOcupacional':
                case 'direccionFormacion':
                    result = 2;
                    break;
                case 'directorEfector':
                    result = 1;
                    break;
                case 'jefepersonalaocargadereemplazosyguardias':
                case 'jefedepersonahcargadereemplazosyguardias':
                case 'jefepersonalaocargaderi':
                case 'jefepersonalh':
                case 'jefepersonalao':
                case 'refrrhh':
                    result = 0;
                    break;
                default:
                    result = -1;
            }
            return result;
        },
        permiso(state) {
            return state.permiso;
        },
        permisos(state) {
            return state.permisos;
        },
        dependencias(state) {
            return state.dependencias;
        },
        agente(state) {
            return state.agente;
        },
        dependencia(state) {
            return state.dependencia;
        },
        dependencia_padre(state) {
            return state.dependencia_padre;
        },
    },
};
