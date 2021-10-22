<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PHP Task Demo - @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" href="//pro.fontawesome.com/releases/v5.10.0/css/all.css"
          integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="{{asset('css/prism-okaidia.css')}}">
    <link rel="stylesheet" href="{{asset('css/custom.min.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">.
    <meta name="csrf-header" content="X-CSRF-TOKEN"/>
</head>
<body>
<div class="navbar navbar-expand-lg fixed-top navbar-dark bg-primary">
    <div class="container">
        <a href="{{url("/")}}" class="navbar-brand">PHP Task Demo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            @section('navbar')
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('/dashboard')}}">儀表板</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('/form')}}">表單填寫</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-md-auto">
                    @guest
                        <li class="nav-item">
                            <a target="_top" rel="noopener" class="nav-link" href="{{url('/login')}}"><i
                                    class="fa fa-twitter"></i> Login</a>
                        </li>
                    @endguest
                    @auth
                        <li class="nav-item">
                            <a target="_top" rel="noopener" class="nav-link" href="{{url('/logout')}}"><i
                                    class="fa fa-twitter"></i> Logout</a>
                        </li>
                    @endauth
                </ul>
            @show
        </div>
    </div>
</div>

<div class="container">
    @yield('content')
    <footer id="footer">
        <div class="row">
            <p class="col-lg-12">
            <ul class="list-unstyled">
                <li class="float-end"><a href="#top">Back to top</a></li>
                <li><a href="{{url('/welcome')}}/">Welcome</a></li>
                <li><a href="https://portal.ncu.edu.tw/" target="_blank">Portal</a></li>
            </ul>
            <p>Copyright &copy; 2021 <a href="https://www.cc.ncu.edu.tw/" target="_blank">NCUCC</a>, Jiann-Ching Liu. --- Laravel
                v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p>
        </div>
    </footer>
</div>
</footer>
</div>

<script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('js/prism.js')}}" data-manual></script>
<script src="{{asset('js/custom.js')}}"></script>
<script src="{{asset('js/helper.js?version=0.0.1')}}"></script>
@yield('scripts')
</body>
</html>
