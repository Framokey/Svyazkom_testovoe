import ListResidents from "./pages/residents/ListResidents.vue";
import Resident from "./pages/residents/Resident.vue";
import PumpMeterRecords from "./pages/water_pump_records/PumpMeterRecords.vue";
import Tariff from "./pages/tariff/Tariff.vue";
import Info from "./pages/info/Info.vue";
import DetailInfo from "./pages/info/DetailInfo.vue";
import Home from "./pages/home/Home.vue";

export default [
    {
        path: '/admin',
        name: 'admin',
        component: Home
    },

    {
        path: '/admin/residents',
        name: 'admin.residents',
        component: ListResidents,
    },

    {
        path: '/admin/residents/:resident',
        name: 'admin.residents.resident',
        component: Resident,
    },

    {
        path: '/admin/records',
        name: 'admin.records',
        component: PumpMeterRecords,
    },

    {
        path: '/admin/tariffs',
        name: 'admin.tariffs',
        component: Tariff,
    },

    {
        path: '/admin/info',
        name: 'admin.info',
        component: Info
    },

    {
        path: '/admin/info/:period_id',
        name: 'admin.info.detail',
        component: DetailInfo
    }
]
