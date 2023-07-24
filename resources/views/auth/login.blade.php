<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ env('APP_NAME') }} | Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Bootstrap -->
    <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('assets/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{ asset('assets/vendors/animate.css/animate.min.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ asset('assets/build/css/custom.min.css') }}" rel="stylesheet">
</head>

<body class="login">
    <div>
        <a class="hiddenanchor" id="signup"></a>
        <a class="hiddenanchor" id="signin"></a>

        <div class="login_wrapper">
            <div class="alert alert-danger" role="alert" hidden>
            </div>
            <form>
                <h1>SPK Login</h1>
                <div class="form-group">
                    <input type="text" class="form-control" name="user" id="user" placeholder="Username" />
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="pass" id="pass" placeholder="Password" />
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success" id="btn_login">Masuk</button>
                </div>
            </form>
        </div>
    </div>
</body>
<!-- jQuery -->
<script src="{{ asset('assets/vendors/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<!-- Jquery Validate -->
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
    $("form").validate({
        rules: {
            user: "required",
            pass: "required",
        },
        messages: {
            user: "Username tidak boleh kosong",
            pass: "Password tidak boleh kosong",
        },
        onkeyup: function(element) {
            $(element).valid();
        },
        onchange: function(element) {
            $(element).valid();
        },
        highlight: function(element) {
            $(element).closest(".form-control").addClass("is-invalid");
        },
        unhighlight: function(element) {
            $(element).closest(".form-control").removeClass("is-invalid");
        },
        errorElement: "div",
        errorClass: "invalid-feedback",
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            doLogin();
        },
        invalidHandler: function(form, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {
                validator.errorList[0].element.focus();
            }
        },
    });
    $('form').valid();

    function doLogin() {
        $.ajax({
            type: "POST",
            url: "{{ route('doLogin') }}",
            data: $('form').serialize(),
            dataType: "json",
            beforeSend: function() {
                $('.alert-danger').text('');
                $('.alert-danger').prop('hidden', true);
                $('#btn_login').prop('disabled', true);
                $('#btn_login').html(
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
                );
            },
            success: function(response) {
                window.location = "{{ route('dashboard') }}"
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $('#btn_login').prop('disabled', false);
                $('#btn_login').html(
                    `Masuk`
                );
                if (XMLHttpRequest.status == 500) {
                    $('.alert-danger').text('Internal Server Error!');
                    $('.alert-danger').prop('hidden', false);
                } else {
                    var error_response = XMLHttpRequest.responseJSON;
                    if (error_response.errors.length > 0) {

                    } else {
                        $('.alert-danger').text(error_response.message);
                        $('.alert-danger').prop('hidden', false);
                    }
                }
            }
        });
    }
</script>

</html>
