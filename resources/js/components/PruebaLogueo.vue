<template>
    <div>
        <button class="button" type="button" @click="logueado">Click Me!</button>
        <button class="button" type="button" @click="agente">Click Me!</button>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        methods:{
            async logueado(){
                await window.axios.post("login", {nombreusuario:'guadagramajo',password: 'daniel1'})
                let response = await window.axios.get('api/user')
                console.log(response.data)
            },
            async agente(){
                let response = await window.axios.get('api/user')
                console.log(response.data)
            }
        },
    }
</script>

<style scoped>

</style>
