<script setup>

import {onMounted, ref} from "vue";
import {Field, Form} from "vee-validate";
import * as yup from "yup";
import axios from "axios";

const tariffs = ref([]);
const periods = ref([]);
const freePeriods = ref([]);
const editing = ref(false);

const id = ref();
const tariffInput = ref();
const period = ref();
const form = ref(null);

const getTariffInfo = () => {
    axios
        .get('/api/tariffs')
        .then((response) =>{
            tariffs.value = response.data[0];
            periods.value = response.data[1];

            periods.value.forEach((value) => {
                let tmp = tariffs.value.find(tariffs => tariffs['period_id'] === value['id']);
                if (tmp === undefined) {
                    console.log(value)
                    freePeriods.value.push({
                        data : value,
                        renderDate: new Date(value['end_date']).toLocaleString('default', { month: 'long', year: 'numeric' })
                    })
                }
            })
        })
}

const schema = yup.object({
    tariff: yup.number().required(),
});
const createTariff = (values, { resetForm }) => {
    let tmp = freePeriods.value.find(freePeriods => freePeriods['renderDate'] === period.value)

    const toCreateData = {
        period_id: tmp['data']['id'],
        tariff: values['tariff']
    }

    axios
        .post('/api/tariffs', toCreateData)
        .then((response) => {
            tariffs.value.unshift(response.data)
            console.log(response.data)
            $('#tariffFormModal').modal('hide');
            resetForm();
            period.value = null;
        })
};

const addTariff = () => {
    form.value.resetForm();
    editing.value = false;
    $('#tariffFormModal').modal('show');
};

const updateTariff = (values) => {
    axios
        .put('/api/tariffs/' + id.value, values)
        .then((response) => {
            const index = tariffs.value.findIndex(tariffs => tariffs.id === response.data.id);
            tariffs.value[index] = response.data;
            $('#tariffFormModal').modal('hide');
        });
}
const editTariff = (tariff) => {
    id.value = tariff.id;
    tariffInput.value = tariff.tariff;
    editing.value = true;
    $('#tariffFormModal').modal('show');
};

const handleSubmit = (values, actions) => {
    if (editing.value) {
        updateTariff(values, actions);
    } else {
        createTariff(values, actions);
    }
}

onMounted(() => {
    getTariffInfo()
})
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tariff</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Tariff</li>
                    </ol>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <button type="button" @click="addTariff" class="mb-2 btn btn-primary">
                        <i class="fa fa-plus-circle mr-1"></i>
                        Добавить тариф
                    </button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Дата</th>
                            <th>Показания счётчика</th>
                            <th>Редактировать</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(tariff, index) in tariffs">
                            <th>{{ index + 1 }}</th>
                            <th>{{ new Date(periods.find(period => period.id === tariff.period_id)['end_date']).toLocaleString('default', { month: 'long', year: 'numeric' }) }}</th>
                            <th>{{ tariff['tariff'] }}</th>
                            <th><a href="#" @click.prevent="editTariff(tariff)"><i class="bi bi-pencil-square"></i></a></th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tariffFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <span v-if="editing">Редактирование</span>
                        <span v-else>Добавление тарифа на месяц</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form"  @submit="handleSubmit" :validation-schema="schema" v-slot="{ errors }">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="tariff" class="col-form-label">Тариф:</label>
                            <Field name="tariff" v-model="tariffInput" type="text" class="form-control" :class="{'is-invalid': errors.tariff}" id="tariff" aria-describedby="nameHelp" placeholder="Введите тариф..."/>
                            <span class="invalid-feedback">{{ errors.tariff }}</span>
                        </div>
                        <div class="form-group" v-if="editing===false">
                            <label for="area" class="col-form-label">Период:</label>
                            <select class="form-select" v-model="period" aria-label="Default select example">
                                <option v-for="period in freePeriods" selected>{{ period.renderDate }}</option>
                            </select>
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
</template>

