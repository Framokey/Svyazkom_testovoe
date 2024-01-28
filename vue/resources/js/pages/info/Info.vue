<script setup>
import axios from "axios";
import {onMounted, ref} from "vue";
import {useRouter} from "vue-router";

const allData = ref([]);

const periods = ref([]);
const tariffs = ref([]);
const records = ref([]);

const years = ref([]);
const months = ref([]);

const filterYear = ref(null);
const filterMonth = ref(null);

const router = useRouter();
const loading = ref(true)

const getInfo = () => {
    axios
        .get('/api/info')
        .then((response) => {
            periods.value = response.data[0];
            tariffs.value = response.data[1];
            records.value = response.data[2];

            periods.value.forEach((per) => {

                years.value.push(new Date(per['end_date']).toLocaleString('default', { year: 'numeric' }))

                if(records.value.find(record => record['period_id'] === per['id'])){
                    months.value.push(new Date(per['end_date']).toLocaleString('default', { month: 'long' }))
                }

            })

            years.value = [... new Set(years.value)];
            months.value = [... new Set(months.value)];
            loading.value = false;
            compileAllData(records.value);

        })
}

const compileAllData = (records) => {

    allData.value = [];
    if(records)
    {
        records.forEach((value) => {
            allData.value.push({
                period: periods.value.find(period => period['id'] === value['period_id']),
                tariff: tariffs.value.find(tariff => tariff['period_id'] === value['period_id']),
                record: value
            })
        });
    }
}

const filtering = () => {

    let filterPeriod = null;
    let filterPeriods = null;
    let filterRecords = [];

    if(filterMonth.value && filterYear.value)
    {
        const condition = filterMonth.value + ' ' + filterYear.value + ' г.'

        filterPeriod = periods.value.find(period => new Date(period['end_date']).toLocaleString('default', { month: 'long', year: 'numeric' }) === condition);

        if(filterPeriod)
        {
            filterRecords = records.value.filter(record => record['period_id'] === filterPeriod['id']);
        }

        compileAllData(filterRecords);
    }

    if(filterMonth.value && !filterYear.value)
    {
        filterPeriods = periods.value.filter(period => new Date(period['end_date']).toLocaleString('default', { month: 'long' }) === filterMonth.value);
        if(filterPeriods)
        {
            filterPeriods.forEach((period) => {
                let tmp = records.value.find(record => record['period_id'] === period['id'])
                console.log(tmp)
                if(tmp)
                {
                    filterRecords.push(tmp)
                }
            })
        }
        compileAllData(filterRecords);
    }

    if(!filterMonth.value && filterYear.value)
    {
        filterPeriods = periods.value.filter(period => new Date(period['end_date']).toLocaleString('default', { year: 'numeric' })=== filterYear.value)
        filterRecords = [];

        if(filterPeriods)
        {
            filterPeriods.forEach((period) => {
                let tmp = records.value.find(record => record['period_id'] === period['id'])
                if(tmp)
                {
                    filterRecords.push(tmp)
                }
            });
        }

        compileAllData(filterRecords);
    }

}

onMounted(() => {
    getInfo();
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
                    <h1 class="m-0">Info</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Info</li>
                    </ol>
                </div>
            </div>

            <div class="col-sm-6">

                <div class="form-group">
                    <label>Select</label>
                    <select class="form-control" id="monthFilter" v-model="filterMonth" v-on:change="filtering()">
                        <option v-for="month in months">{{ month }}</option>
                    </select>
                    <label>Select</label>
                    <select class="form-control" id="yearFilter" v-model="filterYear" v-on:change="filtering()">
                        <option v-for="year in years">{{ year }}</option>
                    </select>
                </div>

            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Дата</th>
                            <th>Тариф</th>
                            <th>Показания счётчика</th>
                            <th>Подробнее</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(data, index) in allData">
                            <th>{{ index + 1 }}</th>
                            <th>{{ new Date(data['period']['end_date']).toLocaleString('default', { month: 'long', year: 'numeric' }) }}</th>
                            <th v-if="data['tariff']">{{ data['tariff']['tariff'] }}</th>
                            <th v-else>Значение отсутствует!</th>
                            <th v-if="data['record']">{{ data['record']['amount_volume'] }}</th>
                            <th v-else>Значение отсутствует!</th>
                            <th><a href="#" @click.prevent="router.push({ name: 'admin.info.detail', params: { period_id : data['period']['id'] } })"><i class="bi bi-info-square"></i></a></th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</template>

