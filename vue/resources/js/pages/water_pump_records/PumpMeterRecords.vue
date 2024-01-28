<script setup>

import {onMounted, ref} from "vue";
import {Field, Form} from "vee-validate";
import * as yup from "yup";
import axios from "axios";

const records = ref([]);
const periods = ref([]);
const tariffs = ref([]);
const freePeriods = ref([]);

const editing = ref(false);
const id = ref();
const period_id = ref();
const recordInput = ref();
const period = ref();
const form = ref(null);

const getRecordsInfo = () => {
    axios
        .get('/api/records')
        .then((response) =>{
            records.value = response.data[0]
            periods.value = response.data[1]
            tariffs.value = response.data[2]

            periods.value.forEach((value) => {
                const freeTariffDate = tariffs.value.find(tariff => tariff['period_id'] === value['id']);

                if (freeTariffDate !== undefined) {
                    if (! records.value.find(record => record['period_id'] === freeTariffDate['period_id']))
                    {
                        freePeriods.value.push({
                            data : value,
                            renderDate: new Date(value['end_date']).toLocaleString('default', { month: 'long', year: 'numeric' })
                        })
                    }

                }
            })
        })
}

const schema = yup.object({
    record: yup.number().required()
});

const createRecord = (values, { resetForm }) => {
    let tmp = freePeriods.value.find(freePeriod => freePeriod['renderDate'] === period.value)


    const toCreateData = {
        period_id: tmp['data']['id'],
        amount_volume: values['record']
    }

    axios
        .post('/api/records', toCreateData)
        .then((response) => {
            records.value.unshift(response.data)
            $('#recordFormModal').modal('hide');
            resetForm();
            period.value = null;
        })
};

const addRecord = () => {
    form.value.resetForm();
    editing.value = false;
    $('#recordFormModal').modal('show');
};

const editRecord = (record) => {
    id.value = record['id']
    period_id.value = record['period_id']
    recordInput.value = record.amount_volume;
    editing.value = true;
    $('#recordFormModal').modal('show');
};

const updateRecord = (values) => {

    const toCreateData = {
        period_id: period_id.value,
        amount_volume: values['record']
    };
    console.log(toCreateData)
    axios.put('/api/records/' + id.value, toCreateData)
        .then((response) => {
            const index = records.value.findIndex(record => record.id === response.data.id);
            records.value[index] = response.data;
            $('#recordFormModal').modal('hide');
        });
}

const handleSubmit = (values, actions) => {
    if (editing.value) {
        updateRecord(values, actions);
    } else {
        createRecord(values, actions);
    }
}

onMounted(() => {
    getRecordsInfo()
})

</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">WaterPumpRecords</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">WaterPumpRecords</li>
                    </ol>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <button type="button" @click="addRecord" class="mb-2 btn btn-primary">
                        <i class="fa fa-plus-circle mr-1"></i>
                        Добавить показание
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
                        <tr v-for="(record, index) in records">
                            <th>{{ index + 1 }}</th>
                            <th>{{ new Date(periods.find(period => period.id === record.period_id)['end_date']).toLocaleString('default', { month: 'long', year: 'numeric' }) }}</th>
                            <th>{{ record['amount_volume'] }}</th>
                            <th><a href="#" @click.prevent="editRecord(record)"><i class="bi bi-pencil-square"></i></a></th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="recordFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <span v-if="editing">Редактирование</span>
                        <span v-else>Добавить показания</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleSubmit" :validation-schema="schema" v-slot="{ errors }">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="record" class="col-form-label">Показание счётчика:</label>
                            <Field name="record" v-model="recordInput" type="text" class="form-control" :class="{ 'is-invalid': errors.record }" id="record" aria-describedby="nameHelp" placeholder="Введите показания счётчика..."/>
                            <span class="invalid-feedback">{{ errors.record }}</span>
                        </div>
                        <div class="form-group" v-if="editing !== true">
                            <label for="period" class="col-form-label">Период:</label>
                            <select class="form-select" v-model="period" aria-label="Default select example">
                                <option v-for="period in freePeriods" selected>{{ period['renderDate'] }}</option>
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

