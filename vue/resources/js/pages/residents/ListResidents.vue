<script setup>
import axios from 'axios';
import {ref, onMounted, reactive} from "vue";
import { useRouter } from "vue-router";
import {Form, Field, ErrorMessage, useResetForm } from 'vee-validate'
import * as yup from 'yup'
import Resident from "./Resident.vue";

const residents = ref([]);
const editing = ref(false)
let id = ref();
let fio = ref();
let area = ref();
const form = ref(null);
const residentToDelete = ref();

const router = useRouter()

const getResidents = () => {
    axios
        .get('/api/residents')
        .then((response) => {
            residents.value = response.data;
        })
};

const schema = yup.object({
    fio: yup.string().required(),
    area: yup.number().required(),
});
const createResident = (values, { resetForm }) => {
    axios
        .post('/api/residents', values)
        .then((response) => {
            residents.value.unshift(response.data)
            $('#residentFormModal').modal('hide');
            resetForm();
        })
};

const addResident = () => {
    form.value.resetForm();
    editing.value = false;
    $('#residentFormModal').modal('show');
};

const updateResident = (values) => {
    axios
        .put('/api/residents/' + id.value, values)
        .then((response) => {
            const index = residents.value.findIndex(resident => resident.id === response.data.id);
            residents.value[index] = response.data;
            $('#residentFormModal').modal('hide');
        });
}
const editResident = (resident) => {
    id.value = resident.id;
    fio.value = resident.fio;
    area.value = resident.area;
    editing.value = true;
    $('#residentFormModal').modal('show');
};

const handleSubmit = (values, actions) => {
    if (editing.value) {
        updateResident(values, actions);
    } else {
        createResident(values, actions);
    }
}

const confirmResidentDeletion = (resident) => {
    residentToDelete.value = resident;
    $('#deleteResidentModal').modal('show');
};

const deleteResident = () => {
    console.log(residentToDelete.value)
    axios.delete('/api/residents/' + residentToDelete.value.id)
        .then(() => {
            $('#deleteResidentModal').modal('hide');
            residents.value = residents.value.filter(resident => resident.id !== residentToDelete.value.id);
        });
};

onMounted(() => {
    getResidents();
});
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Residents</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Residents</li>
                    </ol>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <button type="button" @click="addResident" class="mb-2 btn btn-primary">
                        <i class="fa fa-plus-circle mr-1"></i>
                        Добавить дачника
                    </button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Дата регистрации</th>
                            <th>ФИО</th>
                            <th>Площадь участка</th>
                            <th>Перейти</th>
                            <th>Редактироовать</th>
                            <th>Удаление</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(resident, index) in residents" :key="resident.id">
                            <th>{{ index + 1 }}</th>
                            <th>{{ new Date(resident['start_date']).toLocaleString('default', { day: 'numeric', month: 'long', year: 'numeric' }) }}</th>
                            <th>{{ resident.fio }}</th>
                            <th>{{resident.area }}</th>
                            <th><a href="#" @click.prevent="router.push({ name: 'admin.residents.resident', params: { resident : resident.fio } })"><i class="bi bi-person"></i></a></th>
                            <th>
                                <a href="#" @click.prevent="editResident(resident)"><i class="bi bi-pencil-square"></i></a>
                            </th>
                            <th>
                                <button type="button" @click="confirmResidentDeletion(resident)" class="btn btn-danger">Удалить</button>
                            </th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="residentFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <span v-if="editing">Редактирование</span>
                        <span v-else>Добавление дачника</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleSubmit" :validation-schema="schema" v-slot="{ errors }">
                <div class="modal-body">
                        <div class="form-group">
                            <label for="fio" class="col-form-label">ФИО:</label>
                            <field name="fio" v-model="fio" type="text" class="form-control" :class="{'is-invalid': errors.fio}" id="fio" aria-describedby="nameHelp" placeholder="Введите полное имя..."/>
                            <span class="invalid-feedback">{{ errors.fio }}</span>
                        </div>
                        <div class="form-group">
                            <label for="area123" class="col-form-label">Площадь</label>
                            <field name="area" v-model="area" class="form-control" :class="{ 'is-invalid': errors.area }" id="area" aria-describedby="nameHelp" placeholder="Введите площадь участка..."/>
                            <span class="invalid-feedback">{{ errors.area }}</span>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                </Form>
            </div>
        </div>
    </div>

    <div class="modal" id="deleteResidentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Удаление дачника</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <p>Вы уверены?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button @click.prevent="deleteResident" type="button" class="btn btn-danger">Удалить</button>
                </div>
            </div>
        </div>
    </div>
<!--    <div class="content">-->
<!--        <div class="container-fluid">-->
<!--            <table class="table">-->
<!--                <thead>-->
<!--                <tr>-->
<!--                    <th scope="col">#</th>-->
<!--                    <th scope="col">Дата регистрации</th>-->
<!--                    <th scope="col">ФИО</th>-->
<!--                    <th scope="col">Обращение</th>-->
<!--                    <th scope="col">Действие</th>-->
<!--                    <th scope="col">Подробнее</th>-->
<!--                </tr>-->
<!--                </thead>-->
<!--                <tbody>-->
<!--                    <tr v-for="resident in residents">-->
<!--                        <th>{{ resident.id }}</th>-->
<!--                        <th>{{ resident.start_date }}</th>-->
<!--                        <th>{{ resident.fio }}</th>-->
<!--                        <th>{{resident.area }}</th>-->
<!--                        <th><button>Удалить</button></th>-->
<!--                        <th><router-link :to="{ name: 'admin.residents.resident', params: { residentId:  resident.id }}" active-class="active" class="nav-link">Перейти</router-link></th>-->
<!--                    </tr>-->
<!--                </tbody>-->
<!--            </table>-->
<!--        </div>-->
<!--    </div>-->
</template>


