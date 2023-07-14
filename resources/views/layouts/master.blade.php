
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="blog" />
    <meta name="author" content="Hatem Mohamed" />
    <title>{{setting('site_title_general', 'Blog')}}</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="/{{setting('site_logo_general')}}" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{site_assets('css/styles.css')}}" rel="stylesheet" />
</head>
<body>
<!-- Responsive navbar-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img width="30px" src="/{{setting('site_logo_general')}}" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{route('home')}}">Home</a></li>
                @auth
                    @if(auth()->user()->is_admin)
                        <li class="nav-item"><a class="nav-link" href="{{route('admin')}}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('setting.show')}}">Settings</a></li>
                    @endif
                    <li class="nav-item"><a class="nav-link" href="{{route('account.show')}}">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="document.getElementById('logout').submit()">Logout</a></li>
                        <form id="logout" style="display: none" action="{{route('logout')}}" method="post"> @csrf </form>
                @endauth
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{route('login')}}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route('register')}}">Register</a></li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
<!-- Page content-->
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-12">
            @yield('content')
        </div>
    </div>
</div>
<!-- Footer-->
<footer class="py-5 bg-dark">
    <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Hatem {{date('Y')}}</p></div>
</footer>
<!-- Bootstrap core JS-->
{{--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>--}}
<!-- Core theme JS-->
<script src="{{site_assets('js/scripts.js')}}"></script>
</body>
</html>
