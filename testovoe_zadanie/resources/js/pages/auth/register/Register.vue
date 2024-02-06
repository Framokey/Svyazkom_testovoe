<script setup>
import axios from 'axios';
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';


const router = useRouter();

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});


const loading = ref(false);

const errorMessage = ref('');
const handleSubmit = () => {
    loading.value = true;
    errorMessage.value = '';
    axios.get('/sanctum/csrf-cookie').then(() => {
        axios.post('/register', form)
            .then(() => {
                router.push('/admin')
            })
            .catch((error) => {
                errorMessage.value = error.response.data.message;
            })
            .finally(() => {
                loading.value = false;
            });
    });

};
</script>

<template>
    <div class="hold-transition register-page">
        <div class="register-box">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <div class="h1"><b>Водокачка</b> +</div>
                </div>
                <div class="card-body">

                    <form @submit.prevent="handleSubmit">
                        <div v-if="errorMessage" class="alert alert-danger" role="alert">
                            {{ errorMessage }}
                        </div>
                        <div class="input-group mb-3">
                            <input v-model="form.name" type="text" class="form-control" placeholder="ФИО">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input v-model="form.email" type="email" class="form-control" placeholder="Email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input v-model="form.password" type="password" class="form-control" placeholder="Пароль">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input v-model="form.password_confirmation" type="password" class="form-control" placeholder="Повтор пароля">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-7 m-auto">
                                <button type="submit" class="btn btn-primary btn-block" :disabled="loading">
                                    <div v-if="loading" class="spinner-border spinner-border-sm" role="status">
                                        <span class="sr-only">Загрузка</span>
                                    </div>
                                    <span v-else>Зарегистрироваться</span>
                                </button>
                            </div>

                        </div>
                    </form>


                    <a href="login.html" class="text-center mt-1">Я уже зарегистрировался</a>
                </div>
            </div>
        </div>
    </div>
</template>


