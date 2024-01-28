<script setup>

import axios from "axios";
import {computed, onMounted, ref} from "vue";
import {useRoute} from "vue-router";

const route = useRoute()
const residentFIO = route.params.resident

let resident = ref([])
let area = ref([])
let bills = ref([])


const getResidentInfo = () => {
    axios
        .get('/api/residents/' + residentFIO)
        .then((response) => {
            bills.value = response.data;
            area = bills.value[0]['resident_id']['area'];
        })
}

onMounted(() => {
    getResidentInfo();
})

</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ residentFIO }}</h1>
                    <h1 class="m-0">Площадь участка: {{ area }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">{{ residentFIO }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h2>Счета:</h2>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Дата</th>
                            <th>Сумма</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(bill, index) in bills" :key="bill.id">
                            <th>{{ index + 1 }}</th>
                            <th>{{ new Date(bill['period_id']['end_date']).toLocaleString('default', { month: 'long', year: 'numeric' }) }}</th>
                            <th>{{ bill.amount_rub }}</th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

<!--            <h1>{{ resident.fio }}</h1>-->
<!--            <h2>{{ resident.area }}</h2>-->
<!--            <div>Счета дачника:</div>-->
        </div>
    </div>
</template>
