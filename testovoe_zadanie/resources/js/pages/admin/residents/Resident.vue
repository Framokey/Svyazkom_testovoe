<script setup>

import axios from "axios";
import { onMounted, ref} from "vue";
import {useRoute} from "vue-router";

const route = useRoute()
const residentFIO = route.params.resident

const resident = ref([]);
const area = ref([]);
const bills = ref([]);
const regDate = ref([]);
const loading = ref(true)

const getResidentInfo = () => {
    axios
        .get('/api/residents/' + residentFIO)
        .then((response) => {
            bills.value = response.data;
            area.value = bills.value[0]['resident_id']['area'];
            regDate.value = bills.value[0]['resident_id']['start_date']
            loading.value= false;
        })
}

onMounted(() => {
    getResidentInfo();
})

</script>
<template>

    <div class="loading-img" v-if="loading">
        <div id="load">
            <div>G</div>
            <div>N</div>
            <div>I</div>
            <div>D</div>
            <div>A</div>
            <div>O</div>
            <div>L</div>
        </div>
    </div>

    <div class="content-header" v-if="!loading">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ residentFIO }}</h1>
                    <h1 class="m-0">Площадь участка: {{ area }}</h1>
                    <h1>Дата регистрации: {{ new Date(regDate).toLocaleString('default', { day: 'numeric', month: 'long', year: 'numeric' }) }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content" v-if="!loading">
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
                            <th>{{ bill['amount_rub'] }}₽</th>
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
