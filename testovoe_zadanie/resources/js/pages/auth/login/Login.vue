<script setup>
import axios from 'axios';
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';


const router = useRouter();

const form = reactive({
    email: '',
    password: '',
});

const loading = ref(false);

const errorMessage = ref('');
const handleSubmit = () => {
    loading.value = true;
    errorMessage.value = '';
    axios.get('/sanctum/csrf-cookie').then(() => {
        axios.post('/login', form)
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
    <div class="hold-transition login-page">
    <div class="login-box">

        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <div class="h1"><b>Водокачка</b> +</div>
            </div>
            <div class="card-body">
                <div v-if="errorMessage" class="alert alert-danger" role="alert">
                    {{ errorMessage }}
                </div>
                <form @submit.prevent="handleSubmit">
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



                    <div class="row">

                        <div class="col-4 m-auto">
                            <button type="submit" class="btn btn-primary btn-block" :disabled="loading">
                                <div v-if="loading" class="spinner-border spinner-border-sm" role="status">
                                    <span class="sr-only">Загрузка</span>
                                </div>
                                <span v-else>Войти</span>
                            </button>
                        </div>

                    </div>
                </form>

                <p class="mb-0 mt-1">
                    <a href="/register" class="text-center">Зарегистрироваться</a>
                </p>

            </div>

        </div>
    </div>
    </div>
<!--    <div class="container">-->
<!--        <div class="row justify-content-center">-->
<!--            <div class="col-md-8">-->
<!--                <div class="card">-->
<!--                    <div class="card-header">{{ __('Login') }}</div>-->

<!--                    <div class="card-body">-->
<!--                        <form method="POST" action="{{ route('login') }}">-->
<!--                            @csrf-->

<!--                            <div class="row mb-3">-->
<!--                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>-->

<!--                                <div class="col-md-6">-->
<!--                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>-->

<!--                                    @error('email')-->
<!--                                    <span class="invalid-feedback" role="alert">-->
<!--                                        <strong>{{ $message }}</strong>-->
<!--                                    </span>-->
<!--                                    @enderror-->
<!--                                </div>-->
<!--                            </div>-->

<!--                            <div class="row mb-3">-->
<!--                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>-->

<!--                                <div class="col-md-6">-->
<!--                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">-->

<!--                                    @error('password')-->
<!--                                    <span class="invalid-feedback" role="alert">-->
<!--                                        <strong>{{ $message }}</strong>-->
<!--                                    </span>-->
<!--                                    @enderror-->
<!--                                </div>-->
<!--                            </div>-->

<!--                            <div class="row mb-3">-->
<!--                                <div class="col-md-6 offset-md-4">-->
<!--                                    <div class="form-check">-->
<!--                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>-->

<!--                                        <label class="form-check-label" for="remember">-->
<!--                                            {{ __('Remember Me') }}-->
<!--                                        </label>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->

<!--                            <div class="row mb-0">-->
<!--                                <div class="col-md-8 offset-md-4">-->
<!--                                    <button type="submit" class="btn btn-primary">-->
<!--                                        {{ __('Login') }}-->
<!--                                    </button>-->

<!--                                    @if (Route::has('password.request'))-->
<!--                                    <a class="btn btn-link" href="{{ route('password.request') }}">-->
<!--                                        {{ __('Forgot Your Password?') }}-->
<!--                                    </a>-->
<!--                                    @endif-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </form>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
</template>

<style scoped>

</style>
