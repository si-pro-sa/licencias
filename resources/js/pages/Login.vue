<template>
    <!--    Este fill-height y fluid permiten que se centre sobre toda la pantalla pero no la rellenan -->
    <v-container fill-height fluid>
        <!--    Estas alineaciones funcionan en conjunto con fill-height y fluid -->
        <v-row align="center" justify="center">
            <v-col cols="12">
                <v-card width="700" class="fondo3 mx-auto mt-5">
                    <v-card-title class="pb-0">
                        <h1>
                            <v-icon large color="#acc7dc">mdi-account</v-icon>
                            Licencias
                        </h1>
                    </v-card-title>
                    <v-card-text>
                        <v-form>
                            <v-text-field
                                label="Usuario"
                                v-model="form.nombreusuario"
                                prepend-icon="mdi-account-circle"
                            />
                            <v-text-field
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                label="Password"
                                prepend-icon="mdi-lock"
                                @keyup.enter="login"
                                :append-icon="
                                    showPassword ? 'mdi-eye' : 'mdi-eye-off'
                                "
                                @click:append="showPassword = !showPassword"
                            />
                            <v-alert v-show="errorLogueo" type="error">
                                Credenciales incorrectas
                            </v-alert>
                            <v-alert v-show="errorPuesto" type="error">
                                No tiene puesto vigente
                            </v-alert>
                        </v-form>
                    </v-card-text>
                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-btn block color="#8ac5c3" @click="login">
                            <v-icon>mdi-login</v-icon>
                            Ingresar
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    name: 'Login',
    mounted() {
        console.log(document.cookie)
    },
    data() {
        return {
            errorLogueo: false,
            errorPuesto: false,
            user: {},
            form: { nombreusuario: '', password: '' },
            showPassword: false
        }
    },
    methods: {
        async login() {
            //await axios.get("/sanctum/csrf-cookie");
            await axios
                .post('api/user/login', {
                    nombreusuario: this.form.nombreusuario,
                    password: this.form.password
                })
                .then(res => {
                    this.$store.state.user.user = res.data
                    this.$store.dispatch('user/puestoValidado').then(() => {
                        if (this.$store.getters['user/esPuestoValido']) {
                            this.$emit('logueado', true)
                        } else {
                            this.errorPuesto = true
                        }
                    })
                    console.log(res.data)
                })
                .catch(err => {
                    this.errorLogueo = true
                    console.log(err)
                })
        },
        async instalar() {
            //await axios.get("/sanctum/csrf-cookie");
            await axios
                .post('api/user/instalar')
                .then(res => {
                    console.log(res.data)
                })
                .catch(err => {
                    this.errorLogueo = true
                    console.log(err)
                })
        }
    }
}
</script>

<style scoped>
/* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#acc7dc+0,d6dde3+23,d6dde3+71,d6dde3+71,ffffff+100 */
.fondo {
    /* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#acc7dc+0,d6dde3+23,d6dde3+71,d6dde3+71,ffffff+100 */
    background: rgb(172, 199, 220); /* Old browsers */
    background: -moz-linear-gradient(
        -45deg,
        rgba(172, 199, 220, 1) 0%,
        rgba(214, 221, 227, 1) 23%,
        rgba(214, 221, 227, 1) 71%,
        rgba(214, 221, 227, 1) 71%,
        rgba(255, 255, 255, 1) 100%
    ); /* FF3.6-15 */
    background: -webkit-linear-gradient(
        -45deg,
        rgba(172, 199, 220, 1) 0%,
        rgba(214, 221, 227, 1) 23%,
        rgba(214, 221, 227, 1) 71%,
        rgba(214, 221, 227, 1) 71%,
        rgba(255, 255, 255, 1) 100%
    ); /* Chrome10-25,Safari5.1-6 */
    background: linear-gradient(
        135deg,
        rgba(172, 199, 220, 1) 0%,
        rgba(214, 221, 227, 1) 23%,
        rgba(214, 221, 227, 1) 71%,
        rgba(214, 221, 227, 1) 71%,
        rgba(255, 255, 255, 1) 100%
    ); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#acc7dc', endColorstr='#ffffff',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
}
.fondo2 {
    /* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#edece8+0,eae1bb+100,ffffff+100 */
    background: rgb(237, 236, 232); /* Old browsers */
    background: -moz-linear-gradient(
        -45deg,
        rgba(237, 236, 232, 1) 0%,
        rgba(234, 225, 187, 1) 100%,
        rgba(255, 255, 255, 1) 100%
    ); /* FF3.6-15 */
    background: -webkit-linear-gradient(
        -45deg,
        rgba(237, 236, 232, 1) 0%,
        rgba(234, 225, 187, 1) 100%,
        rgba(255, 255, 255, 1) 100%
    ); /* Chrome10-25,Safari5.1-6 */
    background: linear-gradient(
        135deg,
        rgba(237, 236, 232, 1) 0%,
        rgba(234, 225, 187, 1) 100%,
        rgba(255, 255, 255, 1) 100%
    ); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#edece8', endColorstr='#ffffff',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
}
.fondo3 {
    /* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#edece8+0,ffffff+55,ffffff+76,eae1bb+100 */
    /* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#edece8+0,ffffff+55,ffffff+76,eae1bb+100&1+0,0.47+100 */
    background: -moz-linear-gradient(
        -45deg,
        rgba(237, 236, 232, 1) 0%,
        rgba(255, 255, 255, 0.71) 55%,
        rgba(255, 255, 255, 0.6) 76%,
        rgba(234, 225, 187, 0.47) 100%
    ); /* FF3.6-15 */
    background: -webkit-linear-gradient(
        -45deg,
        rgba(237, 236, 232, 1) 0%,
        rgba(255, 255, 255, 0.71) 55%,
        rgba(255, 255, 255, 0.6) 76%,
        rgba(234, 225, 187, 0.47) 100%
    ); /* Chrome10-25,Safari5.1-6 */
    background: linear-gradient(
        135deg,
        rgba(237, 236, 232, 1) 0%,
        rgba(255, 255, 255, 0.71) 55%,
        rgba(255, 255, 255, 0.6) 76%,
        rgba(234, 225, 187, 0.47) 100%
    ); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#edece8', endColorstr='#78eae1bb',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
}
</style>
