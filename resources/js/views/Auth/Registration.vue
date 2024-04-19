<template>
    <Header/>
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3">
                <h2 class="mt-4 mb-5">Registration</h2>
                <form>
                    <div class="mb-3">
                        <label for="login" class="form-label">Логин</label>
                        <input type="text" class="form-control" id="login" @input="setLogin">
                        <div v-if="errorLogin" class="text-danger">{{errorLogin}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="firstName" class="form-label">Имя</label>
                        <input type="text" class="form-control" id="firstName" @input="setFirstName">
                        <div v-if="errorFirstName" class="text-danger">{{errorFirstName}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Фамилия</label>
                        <input type="text" class="form-control" id="lastName" @input="setLastName">
                        <div v-if="errorLastName" class="text-danger">{{errorLastName}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="birthday" class="form-label">Дата рождения</label>
                        <input type="date" class="form-control" id="birthday" @input="setBirthday">
                        <div v-if="errorBirthday" class="text-danger">{{errorBirthday}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" class="form-control" id="password" @input="setPassword">
                        <div v-if="errorPassword" class="text-danger">{{errorPassword}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">Повторите пароль</label>
                        <input type="password" class="form-control" id="password-confirm" @input="setPasswordConfirm">
                        <div v-if="errorPasswordConfirm" class="text-danger">{{errorPasswordConfirm}}</div>
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
import { reactive, toRefs } from 'vue'
import api from "../../api";
import router from "../../router/router";
import Header from "../../components/Header.vue";

export default {
    name: 'Registration',
    components: {
        Header
    },
    setup(props) {
        const state = reactive({
            login: '',
            firstName: '',
            lastName: '',
            birthday: '',
            password: '',
            passwordConfirm: '',
            errorLogin: '',
            errorFirstName: '',
            errorLastName: '',
            errorBirthday: '',
            errorPassword: '',
            errorPasswordConfirm: ''
        })

        const setLogin = (e) => {
            state.login = e.target.value;
            state.errorLogin = '';
        }

        const setFirstName = (e) => {
            state.firstName = e.target.value;
            state.errorFirtsName = '';
        }

        const setLastName = (e) => {
            state.lastName = e.target.value;
            state.errorLastName = '';
        }

        const setBirthday = (e) => {
            state.birthday = e.target.value;
            state.errorBirthday = '';
        }

        const setPassword = (e) => {
            state.password = e.target.value;
            state.errorPassword = '';
        }

        const setPasswordConfirm = (e) => {
            state.passwordConfirm = e.target.value
            state.errorPasswordConfirm = '';
        }

        const sendForm = (e) => {
            e.preventDefault()

            if(state.login === '') {
                state.errorLogin = 'Введите login';
            }

            if(state.firstName === '') {
                state.errorFirtsName = 'Введите Имя';
            }

            if(state.lastName === '') {
                state.errorLastName = 'Введите Фамилию';
            }

            if(state.birthday === '') {
                state.errorBirthday = 'Введите дату рождения';
            }

            if(state.password.length < 6) {
                state.errorPassword = 'Пароль должен содержать не менее 6 символов'
            }

            if(state.passwordConfirm !== state.password || state.passwordConfirm === '' ) {
                state.errorPasswordConfirm = 'Пароль должен совпадать'
            }

            if(!state.errorLogin && !state.errorPassword && !state.errorPasswordConfirm) {
                api.post('/api/auth/registration',{
                    login: state.login,
                    first_name: state.firstName,
                    last_name: state.lastName,
                    birthday: state.birthday,
                    password: state.password,
                    password_confirmation: state.passwordConfirm,
                }).then((res) => {
                    localStorage.setItem('access_token', res.data.result.access_token)
                    router.push('/')
                }).catch(error => {
                    state.errorLogin = error.response.data.message
                })
            }
        }

        return {
            ...toRefs(state),
            setLogin,
            setFirstName,
            setLastName,
            setBirthday,
            setPassword,
            setPasswordConfirm,
            sendForm,
        }
    }
}
</script>
