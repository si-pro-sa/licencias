<template>
    <div>
        <v-app>
            <div v-if="auth">
                <v-app-bar
                    dark
                    color="light-blue"
                >
                    <v-toolbar-title class="ml-14"
                        >SIARHU - LICENCIAS</v-toolbar-title
                    >
                    <v-spacer></v-spacer>
                    <div v-if="dependencias?.dependenciasHijas?.length > 0">
                        <v-select
                            class="mt-4"
                            dense
                            :items="dependencias.dependenciasHijas"
                            item-text="dependencia"
                            item-value="iddependencia"
                            v-model="select"
                            return-object
                            label="Dependencias"
                        ></v-select>
                    </div>
                    <div v-else></div>
                </v-app-bar>
                <core-drawer
                    @deslogueado="limpiar"
                    v-bind:user="user"
                    v-bind:role="role_display"
                    v-bind:permisos="permisos"
                ></core-drawer>
            </div>

            <v-main
                :class="classObject"
                class="pl-12"
            >
                <transition
                    name="component-fade"
                    mode="out-in"
                >
                    <component
                        v-bind:is="view"
                        @logueado="cambiarComponente"
                    ></component>
                </transition>
            </v-main>
        </v-app>
    </div>
</template>

<script>
import CoreDrawer from '../components/core/Drawer';
import CoreView from '../components/core/View';
import Login from '../pages/Login';
import Welcome from './Welcome';
import app from '../app';

export default {
    name: 'App',
    data: function () {
        return {
            select: null,
            auth: false,
            view: 'Login',
            drawer: false,
        };
    },
    components: {
        CoreDrawer,
        CoreView,
        Login,
        Welcome,
    },

    methods: {
        limpiar() {
            this.auth = false;
            this.view = 'Login';
        },
        guardarUsuario() {
            this.auth = true;
            this.getUser();
        },
        async getUser() {
            try {
                await this.$store.dispatch('user/getUser');
            } catch (e) {
                console.log(e);
            }
            this.getDatosAgente();
            this.getPermisos();
        },
        async getDatosAgente() {
            try {
                await this.$store.dispatch(
                    'user/getDatosAgente',
                    this.user.idusuario,
                );
            } catch (e) {
                console.log(e);
            }
        },
        async getPermisos() {
            try {
                await this.$store.dispatch('user/getPermisos');
                await this.$store.dispatch('user/getDependenciasVisibles');
            } catch (e) {
                console.log(e);
            }
        },
        cambiarComponente(value) {
            if (this.view === 'Login') {
                this.$router.push('welcome');
                this.view = 'CoreView';
                this.guardarUsuario(value);
            }
        },
    },
    watch: {
        select: {
            handler: function (val) {
                this.$store.dispatch('user/setDependencia', val);
            },
            deep: true,
        },
    },
    computed: {
        classObject: function () {
            return {
                marcaAgua: this.view === 'Login',
            };
        },
        role_display() {
            return this.$store.getters['user/role_display'];
        },
        user() {
            return this.$store.getters['user/user'];
        },
        permisos() {
            return this.$store.getters['user/permisos'];
        },
        permiso() {
            return this.$store.getters['user/permiso'];
        },
        dependencias() {
            return this.$store.getters['user/dependencias'];
        },
        dependencia_padre() {
            return this.$store.getters['user/dependencia_padre'];
        },
    },
};
</script>

<style scoped lang="scss">
.component-fade-enter-active,
.component-fade-leave-active {
    transition: opacity 0.2s ease;
}

.component-fade-enter,
.component-fade-leave-to {
    opacity: 0;
}

.marcaAgua {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    /*al svg lo sacaste de codepen y de un codigo que se metia sobre el document.body.style, luego tratamos de cambiar la clase app pero las clases son sobreescritas asi que revisamos y el content fue el ideal*/
    background-image: url('data:image/svg+xml;base64,PHN2ZyBpZD0iZGlhZ3RleHQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+dGV4dCB7IGZpbGw6IGdyYXk7b3BhY2l0eTogMC41OyBmb250LWZhbWlseTogQXZlbmlyLCBBcmlhbCwgSGVsdmV0aWNhLCBzYW5zLXNlcmlmOyB9PC9zdHlsZT48ZGVmcz48cGF0dGVybiBpZD0idHdpdHRlcmhhbmRsZSIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgd2lkdGg9IjQwMCIgaGVpZ2h0PSIyMDAiPjx0ZXh0IHk9IjMwIiBmb250LXNpemU9IjQwIiBpZD0ibmFtZSI+U0lBUkhVPC90ZXh0PjwvcGF0dGVybj48cGF0dGVybiB4bGluazpocmVmPSIjdHdpdHRlcmhhbmRsZSI+PHRleHQgeT0iMTEwIiB4PSIyMDAiIGZvbnQtc2l6ZT0iMzAiIGlkPSJvY2N1cGF0aW9uIj5MaWNlbmNpYXM8L3RleHQ+PC9wYXR0ZXJuPjxwYXR0ZXJuIGlkPSJjb21ibyIgeGxpbms6aHJlZj0iI3R3aXR0ZXJoYW5kbGUiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSgtNDUpIj48dXNlIHhsaW5rOmhyZWY9IiNuYW1lIiAvPjx1c2UgeGxpbms6aHJlZj0iI29jY3VwYXRpb24iIC8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2NvbWJvKSIgLz48L3N2Zz4=');
    background-size: cover;
    transform: scale(1.1);
}
</style>
