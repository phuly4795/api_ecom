<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>
<body>
    <div class="main">
        <div class="header">
            <div class="menu">
                <div class="logo">
                    <h3>Logo</h3>
                </div>
                <div class="mainMenu">
                    <ul>
                       
                        <li><a href="{{route('home')}}">Trang chủ</a></li>
                        <li><a href="">Danh mục</a></li>
                        <li><a href="">Sản Phẩm</a></li>
                        {{-- {{dd(Auth::check())}} --}}
                        @auth
                            <li><a href="">{{Auth::user()->name}}</a></li> --}}
                            <li><a href="">Đăng xuất</a></li>
                        @else
                            <li><a href="{{route('login')}}">Đăng nhập</a></li>
                            <li><a href="">Đăng ký</a></li>
                        @endauth

                       
                    </ul>
                </div>          
            </div>
        </div>
        <div class="body">
            @yield('content')
        </div>
    </div>
  
</body>
</html>