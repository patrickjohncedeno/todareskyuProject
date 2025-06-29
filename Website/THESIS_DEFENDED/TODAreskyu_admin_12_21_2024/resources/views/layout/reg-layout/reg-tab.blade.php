<!-- base.html -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TODAreskyu Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
    <link rel="stylesheet" href="{{ asset('color.css') }}">
    <link rel="stylesheet" href="{{ asset('newstyle.css') }}">
    <script src="{{asset('script.js')}}"></script>

</head>

<style>

    .nav-tabs .nav-link.active img {
        filter: brightness(0) saturate(100%) invert(21%) sepia(100%) saturate(4731%) hue-rotate(358deg) brightness(97%) contrast(101%);
    }
</style>

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
                    class="d-flex flex-row align-items-center ps-3 text-decoration-none text-white w-100 rounded">
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
                    class="d-flex flex-row align-items-center ps-3 text-decoration-none text-white w-100 rounded">
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
                    class="d-flex flex-row align-items-center ps-3 text-decoration-none text-white w-100 rounded">
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
                    class="d-flex flex-row align-items-center ps-3 text-decoration-none text-white w-100 rounded">
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
                    class="d-flex flex-row align-items-center ps-3 text-decoration-none text-white w-100 rounded">
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



        <ul class="nav d-flex nav-fill align-content-start shadow-sm bg-white mb-2 rounded-4 overflow-hidden py-1 border-bottom">
            <li class="nav-item" role="presentation ">
                <a class="nav-link text-black d-flex flex-row flex-fill align-items-center justify-content-center position-relative ps-1 pe-1 {{ request()->is('*registered/inqueue*') ? 'active' : '' }}" href="{{ route('complaints.reg-inqueue') }}">
                    <div class="position-relative me-2">
                        @if(request()->is('*registered/inqueue*'))
                            <!-- Active state with bi-clock-fill icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="rgba(237,92,89,255)" class="bi bi-clock-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
                              </svg>
                        @else
                            <!-- Default state with bi-clock icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#000000" class="bi bi-clock" viewBox="0 0 16 16">
                                <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0"/>
                            </svg>
                        @endif
                    </div>
                    <p class="m-0 fs-6">In Queue</p>
                    <span class="badge bg-danger p-1 ms-2" style="float: right">@yield('pending')</span>
                </a>
            </li>
        
            <li class="nav-item" role="presentation">
                <a class="nav-link text-black d-flex flex-row flex-fill align-items-center justify-content-center position-relative ps-1 {{ request()->is('*registered/inprocess*') ? 'active' : '' }}" href="{{ route('complaints.reg-inprocess') }}">
                    <div class="position-relative me-2">
                        @if(request()->is('*registered/inprocess*'))
                            <!-- Active state with bi-clock-fill icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="rgba(237,92,89,255)" class="bi bi-gear-fill" viewBox="0 0 16 16">
                                <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                              </svg>
                        @else
                            <!-- Default state with bi-clock icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#000000" class="bi bi-gear" viewBox="0 0 16 16">
                                <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                                <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
                              </svg>
                        @endif
                    </div>
                    <p class="m-0 fs-6">In Process</p>
                    <span class="badge bg-danger p-1 ms-2" style="float: right">@yield('inProcess')</span>
                </a>
            </li>
        
            <li class="nav-item" role="presentation">
                <a class="nav-link text-black d-flex flex-row flex-fill align-items-center justify-content-center position-relative ps-1 {{ request()->is('*registered/settled*') ? 'active' : '' }}" href="{{ route('complaints.reg-settled') }}">
                    <div class="position-relative me-2">
                        @if(request()->is('*registered/settled*'))
                            <!-- Active state with bi-clock-fill icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="rgba(237,92,89,255)" class="bi bi-patch-check-fill" viewBox="0 0 16 16">
                                <path d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01zm.287 5.984-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708.708"/>
                              </svg>
                        @else
                            <!-- Default state with bi-clock icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#000000" class="bi bi-patch-check" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M10.354 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                                <path d="m10.273 2.513-.921-.944.715-.698.622.637.89-.011a2.89 2.89 0 0 1 2.924 2.924l-.01.89.636.622a2.89 2.89 0 0 1 0 4.134l-.637.622.011.89a2.89 2.89 0 0 1-2.924 2.924l-.89-.01-.622.636a2.89 2.89 0 0 1-4.134 0l-.622-.637-.89.011a2.89 2.89 0 0 1-2.924-2.924l.01-.89-.636-.622a2.89 2.89 0 0 1 0-4.134l.637-.622-.011-.89a2.89 2.89 0 0 1 2.924-2.924l.89.01.622-.636a2.89 2.89 0 0 1 4.134 0l-.715.698a1.89 1.89 0 0 0-2.704 0l-.92.944-1.32-.016a1.89 1.89 0 0 0-1.911 1.912l.016 1.318-.944.921a1.89 1.89 0 0 0 0 2.704l.944.92-.016 1.32a1.89 1.89 0 0 0 1.912 1.911l1.318-.016.921.944a1.89 1.89 0 0 0 2.704 0l.92-.944 1.32.016a1.89 1.89 0 0 0 1.911-1.912l-.016-1.318.944-.921a1.89 1.89 0 0 0 0-2.704l-.944-.92.016-1.32a1.89 1.89 0 0 0-1.912-1.911z"/>
                              </svg>
                        @endif
                    </div>
                    <p class="m-0 fs-6">Resolved</p>
                    <span class="badge bg-danger p-1 ms-2" style="float: right">@yield('settled')</span>
                </a>
            </li>
        
            <li class="nav-item" role="presentation">
                <a class="nav-link text-black d-flex flex-row flex-fill align-items-center justify-content-center position-relative ps-1 {{ request()->is('*registered/unresolved*') ? 'active' : '' }}" href="{{ route('complaints.reg-unresolved') }}">
                    <div class="position-relative me-2">
                        @if(request()->is('*registered/unresolved*'))
                            <!-- Active state with bi-clock-fill icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="rgba(237,92,89,255)" class="bi bi-exclamation-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2"/>
                              </svg>
                        @else
                            <!-- Default state with bi-clock icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#000000" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                              </svg>
                        @endif
                    </div>
                    <p class="m-0 fs6">Unresolved</p>
                    <span class="badge bg-danger p-1 ms-2" style="float: right">@yield('unresolved')</span>
                </a>
            </li>
        
            <li class="nav-item" role="presentation">
                <a class="nav-link text-black d-flex flex-row flex-fill align-items-center justify-content-center position-relative ps-1 {{ request()->is('*registered/denied*') ? 'active' : '' }}" href="{{ route('complaints.reg-denied') }}">
                    <div class="position-relative me-2">
                        @if(request()->is('*registered/denied*'))
                            <!-- Active state with bi-clock-fill icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="rgba(237,92,89,255)" class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                                <path d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708"/>
                              </svg>
                        @else
                            <!-- Default state with bi-clock icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#000000" class="bi bi-x-octagon" viewBox="0 0 16 16">
                                <path d="M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353zM5.1 1 1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1z"/>
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                              </svg>
                        @endif
                    </div>
                    <p class="m-0 fs6">Denied</p>
                    <span class="badge bg-danger p-1 ms-2" style="float: right">@yield('denied')</span>
                </a>
            </li>
        </ul>
        

        @yield('content')

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
        <script src="{{ asset('logout.js') }}"></script>

</body>


</html>
