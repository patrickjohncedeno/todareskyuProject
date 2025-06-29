<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('styles1.css') }}">

    <title>{{ config('app.name') }} Login</title>

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: hsl(0, 0%, 96%);
        }

        .container-fluid {
            height: 100%;
        }

        .row {
            height: 100%;
        }

        .card {
            max-width: 100%;
        }

        .btn-center {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>

<body>

    @auth
        <script>
            window.location = "{{ route('index') }}";
        </script>
    @else
        <!-- Jumbotron -->
        @if (session('success'))
            <div class=" mt-2 alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="container" id="container">

            <div class="form-container sign-up-container">
                <form action="{{ route('register') }}" method="POST">
                    <h1>Create Account</h1>
                    @csrf
                    <input type="text" id="form3Example1" class="form-control" placeholder="Full Name" name="name" />
                    @error('name')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                    <input type="email" class="form-control" name="create_email" placeholder="Email">
                    @error('create_email')
                        <span class="text-danger small h6 p-0">{{ $message }}</span>
                    @enderror
                    <input type="password" class="form-control" name="createpassword" placeholder="Password">
                    @error('createpassword')
                        <span class="text-danger small h6 p-0">{{ $message }}</span>
                    @enderror
                    <input class="form-control" type="password" name="createpassword_confirmation"
                        placeholder="Confirm Password">
                    @error('createpassword_confirmation')
                        <span class="text-danger small h6 p-0">{{ $message }}</span>
                    @enderror
                    <button type="submit" class="btn btn-primary mt-3 bg-danger border-danger">Sign Up</button>
                </form>
            </div>
            <div class="form-container sign-in-container">
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <h1 class="mb-5">Sign In</h1>
                    <input class="bg-light" type="email" class="form-control" name="email" placeholder="Email">
                    @error('email')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                    <input class="bg-light" type="password" class="form-control" name="password" placeholder="Password">
                    @error('password')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                    <button type="submit" class="btn btn-primary mt-3 bg-danger border-danger">Sign In</button>
                </form>
            </div>
            <div class="overlay-container">
                <div class="overlay">
                    <div class="overlay-panel overlay-left">
                        <img src="{{ asset('images/todareskyu_logo.png') }}" alt="Image" class="img-fluid"
                            height="150px" width="150px">
                        <h1>Hello, Admin!</h1>
                        <p>To manage the system, please log in with your admin credentials</p>
                        <button class="btn btn-light ghost text-white" id="signIn">Sign In</button>
                    </div>
                    <div class="overlay-panel overlay-right">
                        <img src="{{ asset('images/todareskyu_logo.png') }}" alt="Image" class="img-fluid"
                            height="150px" width="150px">
                        <h1>Welcome, Admin!</h1>
                        <p>Enter your details to set up your admin account and manage the system</p>
                    </div>
                </div>
            </div>
        </div>
    @endauth
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });
    </script>
</body>

</html>
