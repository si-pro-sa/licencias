<template>
    <v-navigation-drawer
        floating
        permanent
        expand-on-hover
        dark
        left
        app
    >
        <template v-slot:prepend>
            <v-list-item two-line>
                <v-list-item-avatar>
                    <img
                        class="logo"
                        src="http://dyb3lop9kdviu.cloudfront.net/assets/favicon/favicon-96x96-478c05a4e78c23f90988716c7478c6aa9e904d20904cd8ebe8a204fa666bbe62.png"
                    />
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title
                        >{{ user.nombreusuario }}
                    </v-list-item-title>
                    <v-list-item-subtitle>{{ role }}</v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
        </template>

        <v-divider></v-divider>

        <v-list dense>
            <v-list-item
                v-if="can('ver-visado')"
                :to="items[0].route"
                link
            >
                <v-list-item-icon>
                    <v-icon>{{ items[0].icon }}</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                    <v-list-item-title>{{ items[0].title }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item
                :to="items[6].route"
                link
            >
                <v-list-item-icon>
                    <v-icon>{{ items[6].icon }}</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                    <v-list-item-title>{{ items[6].title }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item
                v-if="can('ver-sancion')"
                :to="items[1].route"
                link
            >
                <v-list-item-icon>
                    <v-icon>{{ items[1].icon }}</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                    <v-list-item-title>{{ items[1].title }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item
                v-if="can('ver-grupoFamiliar')"
                :to="items[2].route"
                link
            >
                <v-list-item-icon>
                    <v-icon>{{ items[2].icon }}</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                    <v-list-item-title>{{ items[2].title }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item
                v-if="can('ver-antiguedad')"
                :to="items[3].route"
                link
            >
                <v-list-item-icon>
                    <v-icon>{{ items[3].icon }}</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                    <v-list-item-title>{{ items[3].title }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item
                v-if="can('ver-importarLAO')"
                :to="items[4].route"
                link
            >
                <v-list-item-icon>
                    <v-icon>{{ items[4].icon }}</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                    <v-list-item-title>{{ items[4].title }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-list-item
                v-if="can('ver-importarSancion')"
                :to="items[5].route"
                link
            >
                <v-list-item-icon>
                    <v-icon>{{ items[5].icon }}</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                    <v-list-item-title>{{ items[5].title }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
        <v-divider></v-divider>
        <div>
            <v-list dense>
                <v-list-group>
                    <template v-slot:activator>
                        <v-list-item-icon>
                            <v-icon>mdi-account-question</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>Consultas</v-list-item-title>
                        </v-list-item-content>
                    </template>
                    <v-list-item
                        v-if="can('ver-consultaLicencia')"
                        :to="consultas[0].route"
                        link
                    >
                        <v-list-item-icon>
                            <v-icon>{{ consultas[0].icon }}</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title
                                >{{ consultas[0].title }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item
                        v-if="can('ver-consultaSancion')"
                        :to="consultas[1].route"
                        link
                    >
                        <v-list-item-icon>
                            <v-icon>{{ consultas[1].icon }}</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title
                                >{{ consultas[1].title }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item
                        v-if="can('ver-consultaLAO')"
                        :to="consultas[2].route"
                        link
                    >
                        <v-list-item-icon>
                            <v-icon>{{ consultas[2].icon }}</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title
                                >{{ consultas[2].title }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item
                        v-if="can('ver-consultaAgente')"
                        :to="consultas[3].route"
                        link
                    >
                        <v-list-item-icon>
                            <v-icon>{{ consultas[3].icon }}</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title
                                >{{ consultas[3].title }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-group>
            </v-list>
        </div>
        <v-divider></v-divider>

        <v-list dense>
            <v-list-group>
                <template v-slot:activator>
                    <v-list-item-icon>
                        <v-icon>mdi-receipt</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Licencias</v-list-item-title>
                    </v-list-item-content>
                </template>

                <div v-if="can('ver-licSaludOcupacional')">
                    <v-list-group
                        no-action
                        sub-group
                    >
                        <template v-slot:activator>
                            <v-list-item-content>
                                <v-list-item-title
                                    >{{ licencias[0][0] }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </template>

                        <v-list-item
                            v-for="(salud, i) in saludOcupacional"
                            :key="i"
                            :to="{
                                name: 'licencia',
                                params: { tipoLicencia: salud[1] },
                            }"
                            link
                        >
                            <v-list-item-content>
                                <v-list-item-title
                                    >{{ salud[0] }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-group>
                </div>

                <div v-if="can('ver-licAnualReglamentaria')">
                    <v-list-group
                        sub-group
                        no-action
                    >
                        <template v-slot:activator>
                            <v-list-item-content>
                                <v-list-item-title
                                    >{{ licencias[1][0] }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </template>
                        <v-list-item
                            v-for="(lic, i) in anualReglamentaria"
                            :key="i"
                            :to="{
                                name: 'licencia',
                                params: { tipoLicencia: lic[1] },
                            }"
                            link
                        >
                            <v-list-item-content>
                                <v-list-item-title
                                    >{{ lic[0] }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-group>
                </div>

                <div v-if="can('ver-licSinGoce')">
                    <v-list-group
                        sub-group
                        no-action
                    >
                        <template v-slot:activator>
                            <v-list-item-content>
                                <v-list-item-title
                                    >{{ licencias[2][0] }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </template>
                        <v-list-item
                            v-for="(lic, i) in filteredParticularesSinGoce"
                            :key="i"
                            :to="{
                                name: 'licencia',
                                params: { tipoLicencia: lic[1] },
                            }"
                            link
                        >
                            <v-list-item-content>
                                <v-list-item-title
                                    >{{ lic[0] }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-group>
                </div>

                <div v-if="can('ver-licConGoce')">
                    <v-list-group
                        sub-group
                        no-action
                    >
                        <template v-slot:activator>
                            <v-list-item-content>
                                <v-list-item-title
                                    >{{ licencias[3][0] }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </template>
                        <v-list-item
                            v-for="(lic, i) in particularesConGoce"
                            :key="i"
                            :to="{
                                name: 'licencia',
                                params: { tipoLicencia: lic[1] },
                            }"
                            link
                        >
                            <v-list-item-content>
                                <v-list-item-title
                                    >{{ lic[0] }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-group>
                </div>

                <div v-if="can('ver-licCapacitacion')">
                    <v-list-group
                        sub-group
                        no-action
                    >
                        <template v-slot:activator>
                            <v-list-item-content>
                                <v-list-item-title
                                    >{{ licencias[4][0] }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </template>
                        <v-list-item
                            v-for="(lic, i) in capacitacion"
                            :key="i"
                            :to="{
                                name: 'licencia',
                                params: { tipoLicencia: lic[1] },
                            }"
                            link
                        >
                            <v-list-item-content>
                                <v-list-item-title
                                    >{{ lic[0] }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-group>
                </div>
            </v-list-group>
        </v-list>
        <v-divider></v-divider>
        <v-list dense>
            <v-list-group>
                <template v-slot:activator>
                    <v-list-item-icon>
                        <v-icon>mdi-receipt</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Inasistencia</v-list-item-title>
                    </v-list-item-content>
                </template>
                <v-list-item
                    v-for="(au, i) in ausentismo"
                    :key="i"
                    :to="{
                        name: 'licencia',
                        params: { tipoLicencia: au[1] },
                    }"
                    link
                >
                    <v-list-item-content>
                        <v-list-item-title class="ml-9">{{
                            au[0]
                        }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-group>
        </v-list>
        <v-divider></v-divider>
        <v-list dense>
            <v-list-group>
                <template v-slot:activator>
                    <v-list-item-icon>
                        <v-icon>mdi-receipt</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Concepto de Pago</v-list-item-title>
                    </v-list-item-content>
                </template>
                <v-list-item
                    v-for="(pa, i) in pagoDiscapacitado"
                    :key="i"
                    :to="{
                        name: 'licencia',
                        params: { tipoLicencia: pa[1] },
                    }"
                    link
                >
                    <v-list-item-content>
                        <v-list-item-title class="ml-9">{{
                            pa[0]
                        }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item
                    :to="informePagos.route"
                    link
                >
                    <div>
                        <v-list-item-title class="ml-9">{{
                            informePagos.title
                        }}</v-list-item-title>
                    </div>
                </v-list-item>
            </v-list-group>
        </v-list>
        <v-divider></v-divider>
        <v-list
            v-if="can('ver-capacitacionAdmin')"
            dense
        >
            <v-list-group>
                <template v-slot:activator>
                    <v-list-item-icon>
                        <v-icon>mdi-school</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Capacitaciones</v-list-item-title>
                    </v-list-item-content>
                </template>
                <v-list-item
                    :to="capacitaciones[0].route"
                    link
                >
                    <v-list-item-icon>
                        <v-icon>{{ capacitaciones[0].icon }}</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title
                            >{{ capacitaciones[0].title }}
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-group>
        </v-list>
        <v-divider></v-divider>
        <v-list dense>
            <v-list-group>
                <template v-slot:activator>
                    <v-list-item-icon>
                        <v-icon>mdi-medical-bag</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Salud Ocupacional</v-list-item-title>
                    </v-list-item-content>
                </template>

                <v-list-item
                    :to="{ name: 'historia-clinica' }"
                    link
                >
                    <v-list-item-icon>
                        <v-icon>mdi-book-open-page-variant</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Historia Clínica</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item
                    :to="{ name: 'juntas-medicas' }"
                    link
                >
                    <v-list-item-icon>
                        <v-icon>mdi-account-group</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Juntas Médicas</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item
                    :to="{ name: 'formulario-04' }"
                    link
                >
                    <v-list-item-icon>
                        <v-icon>mdi-file-document</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Formulario 04</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-group>
        </v-list>
        <v-divider></v-divider>
        <v-list dense>
            <v-list-group>
                <template v-slot:activator>
                    <v-list-item-icon>
                        <v-icon>mdi-hospital-building</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Gestión Clínica</v-list-item-title>
                    </v-list-item-content>
                </template>

                <v-list-item
                    :to="{ name: 'prestadores' }"
                    link
                >
                    <v-list-item-icon>
                        <v-icon>mdi-doctor</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Prestadores</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item
                    :to="{ name: 'establecimientos' }"
                    link
                >
                    <v-list-item-icon>
                        <v-icon>mdi-hospital-marker</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Establecimientos</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-group>
        </v-list>
        <template v-slot:append>
            <div class="pa-2">
                <v-btn
                    block
                    @click="logout"
                >
                    <v-icon left>mdi-exit-to-app</v-icon>
                    Cerrar Sesion
                </v-btn>
            </div>
        </template>
    </v-navigation-drawer>
</template>

<script>
import _ from 'lodash';
import Cookie from 'js-cookie';

export default {
    props: { user: Object, role: String, permisos: Array },
    data() {
        return {
            dependenciaSelect: null,
            admins: [
                ['Management', 'mdi-account-multiple-outline'],
                ['Settings', 'mdi-cog-outline'],
            ],
            cruds: [
                ['Create', 'mdi-plus-outline'],
                ['Read', 'mdi-file-outline'],
                ['Update', 'mdi-update'],
                ['Delete', 'mdi-delete'],
            ],
            saludOcupacional: [
                ['Corto Tratamiento', '1'],
                ['Largo Tratamiento', '2'],
                ['Enfermedades Criticas', '3'],
                ['ART', '4'],
                ['Jubilacion por Invalidez', '7'],
                ['Preparto', '8'],
                ['Familiar Enfermo', '11'],
                ['4.1', '21'],
                ['4.2', '22'],
                ['Dia Femenino', '38'],
                ['Prevencion y Cuidado de la salud femenina anual', '40'],
                ['Prevencion y Cuidado de la salud femenina semestral', '39'],
                ['Violencia de Genero', '37'],
            ],
            pagoDiscapacitado: [
                ['5.1', '35'],
                ['5.2', '36'],
            ],
            anualReglamentaria: [
                ['DLA', '17'],
                ['Anual Ordinario', '16'],
                ['Proporcional', '25'],
                ['Anticipo de LAO', '27'],
            ],
            particularesSinGoce: [
                ['Obligacion Militar', '29'],
                ['Actividad Gremial', '5'],
                ['Suspencion', '41', 'ver-licSuspencion'],
                ['Deportiva Rentada', '6'],
                ['Deportiva No Rentada', '32'],
                ['Sin Goce de Sueldo', '15'],
                ['Cargo Mayor Jerarquía o Cargo Político', '30'],
                //['Otra Causal PE', '31'],
            ],
            particularesConGoce: [
                ['Matrimonio', '9'],
                ['Nacimiento', '12'],
                //['Gremial', '5'],
                ['PostParto', '10'],
                //['Deportiva No Rentada', '32'],
                ['Adopcion', '13'],
                ['Duelo Familiar', '14'],
                ['Examen', '28'],
                //['Obligacion Militar', '29']
                //['Pandemia', '33']
            ],
            capacitacion: [
                ['Capacitacion', '18'],
                ['Capacitacion Prolongada', '19'],
            ],
            licencias: [
                ['Salud Ocupacional', 'salud-ocupacional'],
                ['Anual Reglamentaria', 'anual-reglamentaria'],
                ['Aut. Nivel Central', 'particulares-sin-goce'],
                ['Aut. Efector', 'particulares-con-goce'],
                ['Capacitaciones', 'capacitaciones'],
            ],
            ausentismo: [
                ['ICA', '33'],
                ['ISA', '34'],
            ],
            consultas: [
                {
                    title: 'Consulta Licencias',
                    icon: 'mdi-file-search',
                    route: '/consulta',
                },
                {
                    title: 'Consulta Sanciones',
                    icon: 'mdi-file-search',
                    route: '/consulta-sanciones',
                },
                {
                    title: 'Consulta LAO',
                    icon: 'mdi-file-search',
                    route: '/consulta-lao',
                },
                {
                    title: 'Consulta Agente',
                    icon: 'mdi-file-search',
                    route: '/consulta-agente',
                },
            ],
            items: [
                {
                    title: 'Visado',
                    icon: 'mdi-home-city',
                    route: '/dashboard',
                },
                {
                    title: 'Sanciones',
                    icon: 'mdi-close-box',
                    route: '/sanciones',
                },
                {
                    title: 'Grupo Familiar',
                    icon: 'mdi-account-multiple-plus',
                    route: '/grupoFamiliar',
                },
                {
                    title: 'Computo LAO',
                    icon: 'mdi-account-group-outline',
                    route: '/antiguedades',
                },
                {
                    title: 'Importar Computo LAO',
                    icon: 'mdi-database-import',
                    route: '/importar',
                },
                {
                    title: 'Importar Sanciones',
                    icon: 'mdi-database-import',
                    route: '/importarSancion',
                },
                {
                    title: 'Visado Capacitacion',
                    icon: 'mdi-home-city',
                    route: '/dashboardCapacitacion',
                },
            ],
            capacitaciones: [
                {
                    title: 'Admin Capacitacion',
                    icon: 'mdi-certificate',
                    route: '/capacitaciones',
                    permission: 'ver-capacitaciones',
                },
            ],
            pagos: [
                {
                    title: 'Pagos o Reintegros',
                    icon: 'mdi-cash-sync',
                    route: '/reintegro-tratamiento',
                },
            ],
            informePagos: {
                title: 'Informe Mensual de Pagos',
                icon: 'mdi-cash-sync',
                route: '/informe-pago-mensual',
            },
        };
    },
    methods: {
        can(cadena) {
            var permisos = this.$store.getters['user/permisos'];
            return _.findIndex(permisos, ['name', cadena]) >= 0 ? true : false;
        },
        async logout() {
            await axios
                .post('api/user/logout')
                .then((res) => {
                    this.$store.dispatch('user/getDefaultState');
                    this.$store.dispatch('licencia/getDefaultState');
                    this.$store.dispatch('sancion/getDefaultState');
                    this.$store.dispatch('antiguedad/getDefaultState');
                    this.$emit('deslogueado', true);
                    //localStorage.removeItem('licencias_session');
                    Cookie.remove('licencias_session');
                    Cookie.remove('XSRF-TOKEN');
                    this.$router.push('login');

                    return res;
                })
                .catch((error) => {
                    console.log('Error del logout');
                });
        },
        async handler(event) {
            event.preventDefault();
            try {
                // API call here
                await this.logout();
                return ''; // Prompt with default browser message
            } catch (err) {
                return undefined; // Skip prompt
            }
        },
    },
    created() {
        window.addEventListener('beforeunload', (e) => this.handler(e));
        this.$store.dispatch('licencia/fetchTipoLicencias');
    },
    destroyed() {
        window.removeEventListener('beforeunload', (e) => this.handler(e));
    },
    computed: {
        role_display() {
            return this.$store.getters['user/role_display'];
        },
        dependencias() {
            return this.$store.getters['user/dependencias'];
        },
        filteredParticularesSinGoce() {
            return this.particularesSinGoce.filter((lic) => {
                // Si no hay permiso especificado, incluir el elemento
                if (!lic[2]) return true;
                // Si hay permiso, verificar si el usuario lo tiene
                return this.can(lic[2]);
            });
        },
    },
};
</script>

<style scoped></style>
