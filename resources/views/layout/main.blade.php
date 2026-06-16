@include('layout.header')
@include('layout.sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4 fw-bold text-dark">@yield('namepage')</h2>
            
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-4 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="@yield('route')" class="text-decoration-none text-muted">@yield('namepage')</a></li>
                <li class="breadcrumb-item active fw-bold">@yield('namemenu')</li>
              </ol>
            </nav>

            @yield('content')
            
        </div>
    </main>

@include('layout.footer')