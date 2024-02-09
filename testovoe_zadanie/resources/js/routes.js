import ListResidents from "./pages/admin/residents/ListResidents.vue";
import Resident from "./pages/admin/residents/Resident.vue";
import PumpMeterRecords from "./pages/admin/water_pump_records/PumpMeterRecords.vue";
import Tariff from "./pages/admin/tariff/Tariff.vue";
import Info from "./pages/admin/info/Info.vue";
import DetailInfo from "./pages/admin/info/DetailInfo.vue";
import Home from "./pages/admin/home/Home.vue";
import AdminLayout from "./pages/admin/AdminLayout.vue";
import Login from "./pages/auth/login/Login.vue";
import Register from "./pages/auth/register/Register.vue";

export default [
    {
        path: '/login',
        name: 'login',
        component: Login
    },

    {
      path: '/register',
      name: 'register',
      component: Register
    },

    {
        path: '/admin',
        name: 'admin',
        component: AdminLayout,
        children: [
            {
                path: '',
                name: 'home',
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
    },

    {
        path: '/:pathMatch(.*)*',
        redirect: '/admin'
    }

]
