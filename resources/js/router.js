import Vue from 'vue';
import Router from 'vue-router';
import store from './store.js';
import Dashboard from './pages/Dashboard.vue';
import DashboardCapacitacion from './pages/DashboardCapacitacion.vue';
import Welcome from './pages/Welcome.vue';
import CapacitacionIndex from './components/capacitacion/CapacitacionIndex.vue';
import Sancion from './components/sancion/SancionesIndex.vue';
import Antiguedad from './components/antiguedad/AntiguedadIndex.vue';
import GrupoFamiliar from './components/grupoFamiliar/GrupoFamiliarIndex.vue';
import AgenteIndex from './components/agenteLicencia/AgenteIndex.vue';
import LicenciaIndex from './components/licencia/LicenciaIndex.vue';
import LicenciaDetail from './components/licencia/LicenciaDetail.vue';
import ImportarAntiguedadIndex from './components/importarAntiguedad/ImportarAntiguedadIndex.vue';
import ImportarSancionIndex from './components/importarSancion/ImportarSancionIndex.vue';
import ImportarCapacitacionIndex from './components/capacitacion/ImportarCapacitacionIndex.vue';
import Consulta from './components/consulta/ConsultaIndex.vue';
import SaldoLao from './components/consulta/SaldosLao.vue';
import ConsultaSanciones from './components/consulta/ConsultaSanciones.vue';
import ConsultaAgente from './components/consulta/ConsultaAgente.vue';
import ReintegrosTratamientosIndex from './components/pagosReintegros/ReintegrosTratamientosIndex.vue';
import InformePagoMensual from './pages/InformePagoMensual.vue';
import Login from './pages/Login.vue';
import App from './pages/App.vue';
import Cookie from 'js-cookie';

Vue.use(Router);

const router = new Router({
    mode: 'history',
    base: process.env.MIX_APP_ENV === 'local' ? '/' : '/licencias',
    routes: [
        {
            path: '/app',
            name: 'app',
            component: App,
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
        },
        {
            path: '/dashboard',
            name: 'dashboard',
            component: Dashboard,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/dashboardCapacitacion',
            name: 'dashboardCapacitacion',
            component: DashboardCapacitacion,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/welcome',
            name: 'welcome',
            component: Welcome,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/consulta',
            name: 'consulta',
            component: Consulta,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/consulta-agente',
            name: 'consulta-agente',
            component: ConsultaAgente,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/consulta-lao',
            name: 'consulta-lao',
            component: SaldoLao,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/consulta-sanciones',
            name: 'consulta-sanciones',
            component: ConsultaSanciones,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/sanciones',
            name: 'sancion',
            component: Sancion,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/antiguedades',
            name: 'antiguedad',
            component: Antiguedad,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/importar',
            name: 'importar',
            component: ImportarAntiguedadIndex,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/importarSancion',
            name: 'importarSancion',
            component: ImportarSancionIndex,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/importarCapacitacion',
            name: 'importarCapacitacion',
            component: ImportarCapacitacionIndex,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/grupoFamiliar',
            name: 'grupoFamiliar',
            component: GrupoFamiliar,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/agente',
            name: 'agente',
            component: AgenteIndex,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/licencia/:tipoLicencia',
            name: 'licencia',
            component: LicenciaIndex,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/licencias/:licenciaId',
            name: 'licenciaDetail',
            component: LicenciaDetail,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/capacitaciones',
            name: 'capacitaciones',
            component: CapacitacionIndex,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/reintegro-tratamiento',
            name: 'reintegro-tratamiento',
            component: ReintegrosTratamientosIndex,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: '/informe-pago-mensual',
            name: 'informe-pago-mensual',
            component: InformePagoMensual,
        },
    ],
});

router.beforeEach((to, from, next) => {
    if (to.matched.some((record) => record.meta.requiresAuth)) {
        console.log('usuario route', store.state.user.user.idusuario);
        if (store.state.user.user.idusuario > 0) {
            next();
            return;
        } else {
            axios
                .post('api/user/logout')
                .then((res) => {
                    next('/login');
                })
                .catch((err) => {
                    next('/login');
                });
        }
    } else {
        next();
    }
});
export default router;
