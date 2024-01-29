<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Starter</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <router-link to="/admin" class="nav-link">Home</router-link>
            </li>
        </ul>


    </nav>


    <aside class="main-sidebar sidebar-dark-primary elevation-4">

        <div class="sidebar">

            <div class="user-panel mt-3 pb-3 mb-3 d-flex">

                <div class="info">
                    <a href="#" class="d-block">{{ $user['name']  }}</a>
                </div>
            </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                        <li class="nav-item">
                            <router-link to="/admin/info" active-class="active" class="nav-link">
                                <i class="bi bi-info-square-fill"></i>
                                <p>
                                    Счета
                                </p>
                            </router-link>
                        </li>

                        <li class="nav-item">
                            <router-link to="/admin/residents" active-class="active" class="nav-link">
                                <i class="nav-icon bi bi-people-fill"></i>
                                <p>
                                    Дачники
                                </p>
                            </router-link>
                        </li>

                        <li class="nav-item">
                            <router-link to="/admin/tariffs" active-class="active" class="nav-link">
                                <i class="nav-icon bi bi-clipboard2-pulse-fill"></i>
                                <p>
                                    Тариф
                                </p>
                            </router-link>
                        </li>

                        <li class="nav-item">
                            <router-link to="/admin/records" active-class="active" class="nav-link">
                                <i class="nav-icon bi bi-file-bar-graph-fill"></i>
                                <p>
                                    Показания
                                </p>
                            </router-link>
                        </li>

                        <li class="nav-item">

                            <a href="{{ route('logout') }}" class="nav-link">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>
                                    Выйти
                                </p>
                            </a>
                        </li>

                    </ul>
                </nav>

        </div>

    </aside>

    <div class="content-wrapper">
        <router-view></router-view>
    </div>


    <aside class="control-sidebar control-sidebar-dark">

        <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>

</div>
</body>
</html>
