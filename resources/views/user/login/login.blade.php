@extends('user.layout.page-app')

@section('content')
    <div class="h-100 d-flex login-bg">
        <div class="app-logo mb-4 text-center">
            <h1 class="primary-color">{{ App_Name() }}</h1>
        </div>
        <div class="app-login-box">
            <div>
                <h5 class="mb-0 font-weight-bold">
                    <span class="d-block mb-2">Welcome back, User</span>
                    <span>Please sign in to your account.</span>
                </h5>
                <form method="POST" id="login_form">
                    <div class="form-row mt-4">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Email or Mobile</label>
                                <input name="email" placeholder="Email or Mobile here..." type="email" class="form-control" autofocus>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Password</label>
                                <input name="password" placeholder="Password here..." type="password" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-row mt-4">
                        <div class="col-sm-12 text-center text-sm-left">
                            <button class="btn btn-default my-2 btn-block" onclick="save_login()" type="button">Login</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        function save_login() {
            $("#dvloader").show();
            var formData = new FormData($("#login_form")[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("user.save.login") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'login_form', '{{ route("user.dashboard") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg, 'failed');
                }
            });
        }

        // Press Enter Key & Save Form
        $('#login_form').keypress((e) => { 
            // Enter key corresponds to number 13 
            if (e.which === 13) { 
                save_login();
            } 
        })
    </script>
@endsection