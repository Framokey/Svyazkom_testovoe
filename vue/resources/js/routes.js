import Dashboard from "./components/Dashboard.vue";
import ListResidents from "./pages/residents/ListResidents.vue";
import UpdateSettings from "./pages/settings/UpdateSettings.vue";
import UpdateProfile from "./pages/profile/UpdateProfile.vue";
import Resident from "./pages/residents/Resident.vue";
import PumpMeterRecords from "./pages/water_pump_records/PumpMeterRecords.vue";
import Tariff from "./pages/tariff/Tariff.vue";

export default [
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: Dashboard,
    },

    {
        path: '/admin/residents',
        name: 'admin.residents',
        component: ListResidents,
    },

    {
        path: '/admin/residents/:resident',
        name: 'admin-residents-resident',
        component: Resident,
    },

    {
        path: '/admin/settings',
        name: 'admin.settings',
        component: UpdateSettings,
    },

    {
        path: '/admin/profile',
        name: 'admin.profile',
        component: UpdateProfile,
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
]
