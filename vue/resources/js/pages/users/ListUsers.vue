<script>
import axios from "axios";
import {onMounted} from "vue";

let users = [];
const getUsers = () => {
    axios.get('/api/users')
        .then((response) => {
            users = response.data;
        })
}

onMounted(() => {
    getUsers();
});

</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <div>Table</div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>email</th>
                    </tr>
                </thead>
                <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td>{ user.id }</td>
                    <td>{ user.name }</td>
                    <td>vitaliy@mail.ru</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
