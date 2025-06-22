/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')
import 'vuetify/dist/vuetify.min.css'
import Vue from 'vue'
import router from './router.js'
import store from './store.js'
import Vuetify from 'vuetify'
import VCalendar from 'v-calendar'
import JsonExcel from 'vue-json-excel'
Vue.component('downloadExcel', JsonExcel)
Vue.use(Vuetify)
Vue.use(VCalendar, {
    componentPrefix: 'vc'
})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
//Vue.component('prueba-component', require('./components/PruebaLogueo.vue').default);
//Vue.component('app-component', require('./pages/App.vue').default);

import App from './pages/App.vue'

//Filtros
Vue.filter('dni', function(value) {
    if (!value) return ''
    value = value.toString()
    return value.slice(0, 2) + '.' + value.slice(2, 5) + '.' + value.slice(5)
})
Vue.filter('fechaAcomodada', function(value) {
    if (value) {
        return moment(value, 'YYYY-MM-DD')
            .utcOffset('+0300')
            .format('DD/MM/YYYY')
    } else {
        return 'No se ingreso una fecha'
    }
})
Vue.filter('mayuscula', function(value) {
    return value.toString().toUpperCase()
})

// Directivas
Vue.directive('desglose', {
    bind: (element, binding) => {
        console.log('elemento: ' + element)
        console.log('binding: ' + binding)
        const { arg, modif } = binding
        console.log('argunto 1: ' + arg)
        console.log('modificador 1: ' + modif)
        const menuPermissions = {
            Favorites: true,
            Home: true,
            About: false,
            '': false
        }
        if (!menuPermissions[arg]) {
            console.log('entre')
            element.style.display = 'none'
        }
    }
})

Vue.directive('restrict', {
    bind(el, binding) {
        el.addEventListener('keydown', e => {
            // delete, backpsace, tab, escape, enter,
            let special = [46, 8, 9, 27, 13]
            if (binding.modifiers['decimal']) {
                // decimal(numpad), period
                special.push(110, 190)
            }
            // special from above
            if (
                special.indexOf(e.keyCode) !== -1 ||
                // Ctrl+A
                (e.keyCode === 65 && e.ctrlKey === true) ||
                // Ctrl+C
                (e.keyCode === 67 && e.ctrlKey === true) ||
                // Ctrl+X
                (e.keyCode === 88 && e.ctrlKey === true) ||
                // home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)
            ) {
                return // allow
            }
            if (
                binding.modifiers['alpha'] &&
                // a-z/A-Z
                e.keyCode >= 65 &&
                e.keyCode <= 90
            ) {
                return // allow
            }
            if (
                binding.modifiers['number'] &&
                // number keys without shift
                ((!e.shiftKey && e.keyCode >= 48 && e.keyCode <= 57) ||
                    // numpad number keys
                    (e.keyCode >= 96 && e.keyCode <= 105))
            ) {
                return // allow
            }
            // otherwise stop the keystroke
            e.preventDefault() // prevent
        }) // end addEventListener
    } // end bind
}) // end directive

const app = new Vue({
    vuetify:new Vuetify(),
    store,
    router, //Para desarrollos con la vista por separado
    /*created() {
        this.getCookie();
    },
    methods:{
        async getCookie(){
            await window.axios.get('sanctum/csrf-cookie');
        }
    },*/ el:
        '#app',
    render: h => h(App) //este renderiza sobre el primer app que encuentre y laravel ya empieza con uno
}).$mount('#app')

export default app
