import { defineStore } from "pinia";
import {ref} from "vue";
import axios from "axios";



export const useAuthUserStore =  defineStore('AuthUserStore', () => {
    const user = ref({
        name: '',
        email: '',
    });

    const getAuthUser = async () => {
        await axios
            .get('/api/user')
            .then((response) => {
                user.value = response.data;
            })
    };

    return { user, getAuthUser };

});

