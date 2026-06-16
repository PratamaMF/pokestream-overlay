<!-- 
* Pradash - Bootstrap 5 HTML Admin Template *
============================================================== 
* Author: M.Fajar Pratama 
* Created: 2026 
* Copyright 2026 Pradash Admin. All rights reserved. 
* For inquiries or purchase: 
* Website: https://praport.netlify.app/ *
Email: xfajarpratamaaa@gmail.com *
=============================================================== 
-->
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
        <title>@yield('title')</title>

        @if(Auth::user()->logo)
            <link rel="icon" type="image/png" href="{{ asset('storage/' . Auth::user()->logo) }}" />
        @endif
        <link
            href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css"
            rel="stylesheet"
        />
        <link href="{{asset('pradash')}}/css/styles.css" rel="stylesheet" />
        <link href="{{asset('pradash')}}/css/pradash-styles.css" rel="stylesheet" />
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap"
            rel="stylesheet"
        />

        <script
            src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"
            crossorigin="anonymous"
        ></script>
        
        <!-- Load jQuery first -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <!-- Load SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark">
            @if(Auth::user()->logo)
                <a class="navbar-brand" href="/">
                    <img 
                        class="img-profile rounded-circle me-2"
                        src="{{ asset('storage/' . Auth::user()->logo) }}"
                        alt="{{ Auth::user()->name }}"
                    /> {{ Auth::user()->name }}
                </a>

            @endif
            <button
                class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0"
                id="sidebarToggle"
                href="#!"
            >
                <i class="fas fa-bars"></i>
            </button>

            <div class="ms-auto"></div>

            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown d-flex align-items-center">
                    <span class="text-white small me-2 d-none d-md-inline"
                        >{{ Auth::user()->name }}</span
                    >

                    <a
                        class="nav-link dropdown-toggle"
                        id="navbarDropdown"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <i class="fas fa-user fa-fw"></i>
                    </a>
                    <ul
                        class="dropdown-menu dropdown-menu-end shadow border-0 mt-3"
                    >
                        <li><a class="dropdown-item" href="{{ route('settings.index') }}">Settings</a></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('activity.index') }}">Activity Log</a>
                        </li>
                        <li><hr class="dropdown-divider" /></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Logout</button>
                            </form>   
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>