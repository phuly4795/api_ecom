@extends('home')
@section('content')
    <div class="container">
        <div class="loginHome">
            <h3>Đăng nhập</h3>
            <form id="login-form" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-login">
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" id="email" name="email">
                        </div>
                      </div>
                      <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                          <input type="password" class="form-control" id="password" name="password">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <button type="submit" id="submitBtn" class="btn btn-primary">Đăng nhập</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    {{-- <script>
        function login(event) {
            event.preventDefault(); // Ngăn chặn hành vi mặc định của nút submit (nếu nó là nút trong một form)

            // Lấy token từ thẻ meta trong trang
            var token = $('meta[name="csrf-token"]').attr('content');

            // Lấy giá trị email và password từ form đăng nhập
            var email = $("#staticEmail").val();
            var password = $("#inputPassword").val();

            // Thiết lập header cho tất cả các yêu cầu Ajax
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });

            // Gửi yêu cầu Ajax
            $.ajax({
                url: 'http://127.0.0.1:8000/api/auth/login', // Thay thế '/your-endpoint' bằng đường dẫn tới endpoint của bạn
                type: 'POST',
                data: {
                    email: email,
                    password: password
                },
                success: function(response) {
                    // Xử lý phản hồi thành công
                    var Token = response.access_token;
                    localStorage.setItem('token', Token);
                    Toastify({
                        text: "Đăng nhập thành công",
                        duration: 3000, // Thời gian hiển thị thông báo (ms)
                        gravity: "bottom", // Vị trí hiển thị (bottom, top, left, right)
                        backgroundColor: "green",
                        stopOnFocus: true // Dừng hiển thị khi người dùng tương tác
                    }).showToast();
                    window.location.replace('/');
                },
                error: function(xhr) {
                    // Xử lý lỗi
                    Toastify({
                        text: xhr.responseJSON.message,
                        duration: 3000,
                        gravity: "bottom",
                        backgroundColor: "red",
                        stopOnFocus: true
                    }).showToast();
                }
            });
        }

        // Gán sự kiện submit form đăng nhập
        $("#form_login").on("submit", login);
    </script> --}}

    <script>
        $(document).ready(function() {
            $('#login-form').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                var method = form.attr('method');
                var data = form.serialize();
                console.log(data,method, url, form);
                $.ajax({
                    url: url,
                    type: method,
                    data: data,
                    success: function(response) {    
         
                        var Token = response.access_token;
                        var user = response.user;
                        localStorage.setItem('token', Token);
                        localStorage.setItem('user', user);

                        Toastify({
                        text: response.message,
                        duration: 3000,
                        gravity: "bottom",
                        backgroundColor: "green",
                        stopOnFocus: true
                    }).showToast();

                    window.location.href = response.redirect_url;     
                        
                        
                    },
                    error: function(response) {
                        Toastify({
                        text:  response.message ? response.message: response.statusText,
                        duration: 3000,
                        gravity: "bottom",
                        backgroundColor: "red",
                        stopOnFocus: true
                    }).showToast();
                    }
                });
            });
        });
    </script>
@endsection
