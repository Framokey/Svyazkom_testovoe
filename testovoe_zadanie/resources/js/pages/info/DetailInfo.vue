<script setup>
import axios from "axios";
import {useRoute} from "vue-router";
import {onMounted, ref} from "vue";

const period = ref([]);
const tariff = ref([]);
const record = ref([]);

const bills = ref([]);
const residents = ref([]);

const route = useRoute();
const period_id = route.params.period_id;
const billsData = ref([]);

const loading = ref(true);

const getDetailInfo = () => {
    axios
        .get('/api/info/' + period_id)
        .then((response) => {
            period.value = response.data[0];
            tariff.value = response.data[1];
            record.value = response.data[2];
            bills.value = response.data[3];
            residents.value = response.data[4];

            bills.value.forEach((value) => {
                billsData.value.push({
                    bill: value,
                    resident: residents.value.find(resident => resident['id'] === value['resident_id'])
                });
            });

            loading.value = false;

        })
};


onMounted(() => {
    getDetailInfo();
});

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
                    <h1 class="m-0">Подробная информация:</h1>
                    <h2>{{ new Date(period['end_date']).toLocaleString('default', { month: 'long', year: 'numeric' }) }}</h2>
                    <h2>Тариф: {{ tariff['tariff'] }}₽ м<sup>3</sup></h2>
                    <h2>Объём воды: {{ record['amount_volume'] }} м<sup>3</sup></h2>
                </div>
                <div class="col-sm-6">
                    <h2>Общая площад: {{ residents.map(resident => resident['area']).reduce((partialSum, a) => partialSum + parseFloat(a), 0).toFixed(2) }} м<sup>3</sup></h2>
                    <h2>Общая сумма счетов: {{ bills.map(bill => bill['amount_rub']).reduce((partialSum, a) => partialSum + parseFloat(a), 0).toFixed(2) }}₽</h2>
                    <h2>Кол-во счетов: {{ bills.filter(bill => bill['period_id'] === period['id']).reduce(partialSum => partialSum + 1, 0) }}</h2>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>ФИО</th>
                            <th>Площадь участка</th>
                            <th>Выставленный счёт</th>
                            <th>Дата регистрации</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(data, index) in billsData">
                            <th>{{ index + 1 }}</th>
                            <th>{{ data['resident']['fio'] }}</th>
                            <th>{{ data['resident']['area'] }} м<sup>3</sup></th>
                            <th>{{ data['bill']['amount_rub'] }}₽</th>
                            <th>{{ new Date(data['resident']['start_date']).toLocaleString('default', { day: 'numeric', month: 'long', year: 'numeric' }) }}</th>

                            <!--                            <th v-if="data['tariff']">{{ data['tariff']['tariff'] }}</th>-->
<!--                            <th v-else>Значение отсутствует!</th>-->
<!--                            <th v-if="data['record']">{{ data['record']['amount_volume'] }}</th>-->
<!--                            <th v-else>Значение отсутствует!</th>-->
<!--                            <th><a href="#"><i class="bi bi-info-square"></i></a></th>-->
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</template>


