<template>
    <v-snackbar
        v-model="snackbarVisible"
        :color="snackbar.color"
        :timeout="snackbar.timeout"
        bottom
        right
    >
        {{ snackbar.message }}
        <template v-slot:action="{ attrs }">
            <v-btn
                text
                v-bind="attrs"
                @click="closeSnackbar"
            >
                Cerrar
            </v-btn>
        </template>
    </v-snackbar>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex';

export default {
    name: 'GlobalSnackbar',

    computed: {
        ...mapGetters({
            snackbar: 'app/getSnackbar',
        }),

        snackbarVisible: {
            get() {
                return this.snackbar.show;
            },
            set(value) {
                if (!value) this.closeSnackbar();
            },
        },
    },

    methods: {
        ...mapMutations({
            hideSnackbar: 'app/HIDE_SNACKBAR',
        }),

        closeSnackbar() {
            this.hideSnackbar();
        },
    },
};
</script>
