<template>
    <div class="container">
    <v-app id="inspire">
        <div>
            <v-alert type="success">
                I'm a success alert. {{authUser}}
            </v-alert>

            <v-alert type="info">
                I'm an info alert.
            </v-alert>

            <v-alert type="warning">
                I'm a warning alert.
            </v-alert>

            <v-alert type="error">
                I'm an error alert.
            </v-alert>
        </div>
    </v-app>
    <div class="scss">algo</div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        I'm an example component.

                    </div>
                    <button class="button" type="button" onclick="alert('Hello world!')">Click Me!</button>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        name: 'side-profile',
        props: ['authUser']
    }
</script>

<style lang="scss" scoped>
    @use "sass:map";
    @use "sass:color";
    @import "~@/_variables.scss";
    .button {
        $primary-color: $yellow;
        color: $primary-color;
        border: 1px solid color.scale($primary-color, $lightness: 20%);
    }


</style>
