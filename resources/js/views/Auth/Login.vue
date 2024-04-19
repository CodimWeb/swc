<template>
    <Header/>
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3">
                <h2 class="mt-4 mb-5">Login</h2>
                <form>
                    <div v-if="errorCredentials" class="alert alert-danger mb-3" role="alert">
                        Не верный логин или пароль
                    </div>
                    <div class="mb-3">
                        <label for="login" class="form-label">Login</label>
                        <input type="text" class="form-control" id="login" @input="setLogin">
                        <div v-if="errorLogin" class="text-danger">{{errorLogin}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" @input="setPassword">
                        <div v-if="errorPassword" class="text-danger">{{errorPassword}}</div>
                    </div>
                    <div>
                        <button class="btn btn-success" @click="sendForm">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import {reactive, toRefs} from "vue";
import router from "../../router/router";
import api from "../../api";
import Header from "../../components/Header.vue";


export default {
    name: 'Login',
    components: {
        Header
    },

    setup(props) {
        const state = reactive({
            login: '',
            password: '',
            errorLogin: '',
            errorPassword: '',
            errorCredentials: false
        })

        const setLogin = (e) => {
            state.login = e.target.value;
            state.errorLogin = '';
        }

        const setPassword = (e) => {
            state.password = e.target.value;
            state.errorPassword = '';
        }

        const sendForm = (e) => {
            e.preventDefault()

            if(state.login === '') {
                state.errorLogin = 'Введите login';
            }

            if(state.password.length < 6) {
                state.errorPassword = 'Пароль должен содержать не менее 6 символов'
            }

            if(!state.errorLogin && !state.errorPassword) {
                api.post('/api/auth/login',{
                    login: state.login,
                    password: state.password,
                }).then((res) => {
                    localStorage.setItem('access_token', res.data.result.access_token)
                    router.push('/')
                }).catch(error => {
                    state.errorCredentials = true
                })
            }
        }

        return {
            ...toRefs(state),
            setLogin,
            setPassword,
            sendForm,
        }
    }
}
</script>
