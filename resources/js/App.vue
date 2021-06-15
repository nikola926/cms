<template>
    <div id="app">
        <nav class="nav">
            <router-link to="/" class="nav-link">Home</router-link>
            <div v-if="currentUser" >
                <a class="nav-link" @click.prevent="logOut">LogOut</a>
            </div>
            <div v-else>
                <router-link to="/login" class="nav-link">Login</router-link>
            </div>

            <router-link to="/register" class="nav-link">Register</router-link>
        </nav>

        <br>

        <router-view/>
    </div>
</template>

<script>



export default {
    name: 'App',
    data() {
        return {

        }
    },
    computed: {
        currentUser() {
            return this.$store.state.auth.user;
        },

    },
    methods: {
        logOut() {
            this.$store.dispatch('auth/logout');
            this.$router.push('/login');
        }
    },

}

</script>

<style>

</style>
