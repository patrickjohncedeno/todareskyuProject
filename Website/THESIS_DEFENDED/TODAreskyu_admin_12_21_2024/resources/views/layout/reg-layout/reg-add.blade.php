<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TODAreskyu Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="{{asset('logout.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
    <link rel="stylesheet" href="{{ asset('color.css') }}">
    <link rel="stylesheet" href="{{ asset('newstyle.css') }}">
    <link rel="icon" href="{{ asset('images/todareskyu_logo.png') }}" type="image/x-icon">

</head>

<body class="container-fluid bg-white-50 bg-light-gray">
    <!-- Floating Navigation Bar Start -->
    <div class="bg-dark my-3 p-3 position-fixed rounded-3 bg-gradient-dark shadow-lg" style="width: 20%; height: 94%;">
        <ul class="nav flex-column">
            <svg class="align-self-center" width="120" height="100" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 16 16">
                <image href="{{ asset('images/todareskyu_logo.png') }}" x="0" y="0" width="16px" height="16px" />
            </svg>

            <li class="nav-item mb-1 rounded">
                <a href="{{ route('index') }}"
                    class="d-flex flex-row align-items-center ps-3 text-decoration-none text-white w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#ffffff"
                        class="bi bi-graph-up-arrow" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M0 0h1v15h15v1H0zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5" />
                    </svg>
                    <span class="fs-6 ms-2 p-2">Dashboard</span>
                </a>
            </li>

            <li class="nav-item mb-1 active rounded">
                <a href="#complaintsMenu" class="d-flex flex-row align-items-center ps-3 text-decoration-none text-white w-100 rounded" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="complaintsMenu">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#ffffff" class="bi bi-flag-fill" viewBox="0 0 16 16">
                    <path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12 12 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A20 20 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a20 20 0 0 0 1.349-.476l.019-.007.004-.002h.001" />
                  </svg>
                  <span class="fs-6 ms-2 p-2">Complaints</span>
                </a>
              </li>
              <div class="" id="complaintsMenu">
                <ul class="nav flex-column ps-4">
                  <li class="nav-item mb-1 submenu-item ">

                    
                    <a href="{{route('complaints.reg-inqueue')}}" class="d-flex flex-row align-items-center text-decoration-none text-light w-100 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                            <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                          </svg>
                          <span class="fs-6 ms-2 p-2">Registered Complaints</span>
                    </a>
                </li>
                <li class="nav-item mb-1 submenu-item">
                    <a href="{{route('complaints.unreg-inqueue')}}" class="d-flex flex-row align-items-center text-decoration-none text-light w-100 rounded">
                        <span class="fs-6 ms-2 p-2">Unregistered Complaints</span>
                    </a>
                </li>
                
                </ul>
              </div>


            <li class="nav-item mb-1 rounded ">
                <a href="{{route('violations')}}"
                    class="d-flex flex-row align-items-center ps-3 text-decoration-none text-white w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#ffffff"
                        class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                        <path
                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                    </svg>
                    <span class="fs-6 ms-2 p-2">Violations</span>
                </a>
            </li>

            <li class="nav-item mb-1 rounded">
                <a href="{{ route('drivers') }}"
                    class="d-flex flex-row align-items-center ps-3 text-decoration-none text-white w-100">
                    <svg class="align-self-center" width="20" height="35" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 16 16">
                        <image href="{{ asset('images/trike-removebg-preview.png') }}" x="0" y="0" width="16px"
                            height="16px" style="filter: brightness(0) invert(1);" />
                    </svg>
                    <span class="fs-6 ms-2 p-2">Tricycle</span>
                </a>
            </li>

            <li class="nav-item mb-1 rounded">
                <a href="{{ route('userinfo') }}"
                    class="d-flex flex-row align-items-center ps-3 text-decoration-none text-white w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#ffffff"
                        class="bi bi-person-walking" viewBox="0 0 16 16">
                        <path
                            d="M9.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0M6.44 3.752A.75.75 0 0 1 7 3.5h1.445c.742 0 1.32.643 1.243 1.38l-.43 4.083a1.8 1.8 0 0 1-.088.395l-.318.906.213.242a.8.8 0 0 1 .114.175l2 4.25a.75.75 0 1 1-1.357.638l-1.956-4.154-1.68-1.921A.75.75 0 0 1 6 8.96l.138-2.613-.435.489-.464 2.786a.75.75 0 1 1-1.48-.246l.5-3a.75.75 0 0 1 .18-.375l2-2.25Z" />
                        <path
                            d="M6.25 11.745v-1.418l1.204 1.375.261.524a.8.8 0 0 1-.12.231l-2.5 3.25a.75.75 0 1 1-1.19-.914zm4.22-4.215-.494-.494.205-1.843.006-.067 1.124 1.124h1.44a.75.75 0 0 1 0 1.5H11a.75.75 0 0 1-.531-.22Z" />
                    </svg>
                    <span class="fs-6 ms-2 p-2">Commuters</span>
                </a>
            </li>

            <li class="nav-item mb-1 rounded">
                <a href="{{ route('announcement') }}"
                    class="d-flex flex-row align-items-center ps-3 text-decoration-none text-white w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#ffffff"
                        class="bi bi-people-fill" viewBox="0 0 16 16">
                        <path
                            d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm-4-6a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9 0a2 2 0 1 1 4 0 2 2 0 0 1-4 0zM1 13s-1 0-1-1 1-4 5-4c.345 0 .681.03 1 .087A5.99 5.99 0 0 0 6 12c0 .35-.06.687-.169 1H1z" />
                    </svg>
                    <span class="fs-6 ms-2 p-2">Announcements</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- Floating Navigation Bar End -->
    <div class="p-3" style="margin-left: 21%;">
        <div class="d-flex flex-row align-items-center justify-content-between mb-3">

            <div>
                <p class="m-0 fs-2 fw-bold">@yield('content_title')</p>
            </div>



            <div class="d-flex">
                {{-- NOTIFICATIONS --}}
                {{-- <button type="button" class="position-relative me-5" data-bs-toggle="dropdown"
                    aria-expanded="false" style="border: none; background-color: transparent;">
                    <svg class="align-self-center" xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                        fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                        <path
                            d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6" />
                    </svg>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        99
                        <span class="visually-hidden">unread messages</span>
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-lg-end">
                    <li><button class="dropdown-item" type="button">Action</button></li>
                    <li><button class="dropdown-item" type="button">Another action</button></li>
                    <li><button class="dropdown-item" type="button">Something else here</button></li>
                </ul> --}}

                <svg class="me-3 border border border-danger border-1 rounded-circle"
                    xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor"
                    class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                    <path fill-rule="evenodd"
                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                </svg>
                

                <div class="d-flex justify-content-center align-items-center fs-6 me-3">
                    {{ Auth::user()->name }}
                  </div>
                  

                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="button" class="btn" onclick="confirmLogout()">Logout</button>
                </form>
            </div>

        </div>


        @yield('content')

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

</body>

</html>
