<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Login - Angsek</title>

    <link rel="icon"
          href="<?= base_url('assets/img/favicon.png') ?>">

    <link href="<?= base_url('assets/sbadmin2/vendor/fontawesome-free/css/all.min.css') ?>"
          rel="stylesheet">

    <link href="<?= base_url('assets/sbadmin2/css/sb-admin-2.min.css') ?>"
          rel="stylesheet">

    <style>

        /* =====================================================
           GLOBAL
        ===================================================== */

        html,
        body {
            min-height: 100%;
        }

        body {
            margin: 0;

            font-family:
                "Nunito",
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;

            background:
                linear-gradient(
                    135deg,
                    #eef5ff 0%,
                    #f8fbff 45%,
                    #e8f1ff 100%
                );

            overflow-x: hidden;
        }


        /* =====================================================
           BACKGROUND DECORATION
        ===================================================== */

        .login-page {
            position: relative;

            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 30px 15px;

            overflow: hidden;
        }

        .bg-circle {
            position: absolute;

            border-radius: 50%;

            pointer-events: none;
        }

        .bg-circle-one {
            width: 360px;
            height: 360px;

            top: -170px;
            right: -100px;

            background: rgba(78, 115, 223, .10);
        }

        .bg-circle-two {
            width: 280px;
            height: 280px;

            bottom: -150px;
            left: -90px;

            background: rgba(78, 115, 223, .08);
        }

        .bg-circle-three {
            width: 110px;
            height: 110px;

            top: 20%;
            left: 12%;

            background: rgba(54, 162, 235, .06);
        }


        /* =====================================================
           LOGIN WRAPPER
        ===================================================== */

        .login-wrapper {
            position: relative;
            z-index: 5;

            width: 100%;
            max-width: 440px;
        }


        /* =====================================================
           BRAND
        ===================================================== */

        .login-brand {
            text-align: center;

            margin-bottom: 20px;
        }

        .brand-icon {
            width: 64px;
            height: 64px;

            margin: 0 auto 14px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 18px;

            color: #fff;

            background:
                linear-gradient(
                    135deg,
                    #6ea8fe,
                    #4e73df
                );

            box-shadow:
                0 10px 25px rgba(78, 115, 223, .25);

            font-size: 1.65rem;
        }

        .brand-title {
            margin: 0;

            color: #273142;

            font-size: 1.45rem;
            font-weight: 800;

            letter-spacing: -.3px;
        }

        .brand-subtitle {
            margin-top: 5px;

            color: #858796;

            font-size: .82rem;
        }


        /* =====================================================
           CARD
        ===================================================== */

        .login-card {
            border: 0;

            border-radius: 22px;

            background: rgba(255,255,255,.96);

            box-shadow:
                0 20px 50px rgba(45, 65, 100, .12);

            overflow: hidden;
        }

        .login-card-body {
            padding: 34px;
        }


        /* =====================================================
           ALERT
        ===================================================== */

        .login-alert {
            border: 0;

            border-radius: 11px;

            padding: 12px 14px;

            font-size: .8rem;

            box-shadow: none;
        }


        /* =====================================================
           FORM
        ===================================================== */

        .form-label {
            display: block;

            margin-bottom: 7px;

            color: #4a5060;

            font-size: .78rem;
            font-weight: 800;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;

            top: 50%;
            left: 15px;

            transform: translateY(-50%);

            color: #9aa3b5;

            font-size: .9rem;

            pointer-events: none;

            z-index: 3;
        }

        .login-input {
            height: 48px;

            padding-left: 43px;
            padding-right: 43px;

            border: 1px solid #dfe5ef;

            border-radius: 11px;

            color: #343a40;

            background: #fbfcff;

            font-size: .85rem;

            transition: .2s ease;
        }

        .login-input::placeholder {
            color: #a4aaba;
        }

        .login-input:hover {
            border-color: #c8d1e2;
        }

        .login-input:focus {
            border-color: #7ea1ed;

            background: #fff;

            box-shadow:
                0 0 0 .18rem rgba(78,115,223,.10);
        }


        /* =====================================================
           PASSWORD TOGGLE
        ===================================================== */

        .password-toggle {
            position: absolute;

            top: 50%;
            right: 13px;

            width: 32px;
            height: 32px;

            transform: translateY(-50%);

            display: flex;
            align-items: center;
            justify-content: center;

            border: 0;

            border-radius: 8px;

            color: #929aaa;

            background: transparent;

            cursor: pointer;

            transition: .15s ease;
        }

        .password-toggle:hover {
            color: #4e73df;

            background: #edf2ff;
        }


        /* =====================================================
           LOGIN BUTTON
        ===================================================== */

        .btn-login {
            position: relative;

            height: 48px;

            margin-top: 7px;

            border: 0;

            border-radius: 11px;

            color: #fff;

            background:
                linear-gradient(
                    135deg,
                    #6ea8fe,
                    #4e73df
                );

            font-size: .84rem;
            font-weight: 800;

            box-shadow:
                0 7px 18px rgba(78,115,223,.20);

            transition: .2s ease;
        }

        .btn-login:hover {
            color: #fff;

            transform: translateY(-1px);

            box-shadow:
                0 10px 23px rgba(78,115,223,.27);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: .75;

            cursor: not-allowed;

            transform: none;
        }


        /* =====================================================
           SECURITY NOTE
        ===================================================== */

        .login-security {
            display: flex;
            align-items: center;
            justify-content: center;

            gap: 6px;

            margin-top: 18px;

            color: #9aa0ad;

            font-size: .7rem;
        }

        .login-security i {
            color: #4e73df;
        }


        /* =====================================================
           FOOTER
        ===================================================== */

        .login-footer {
            margin-top: 20px;

            text-align: center;

            color: #8b92a1;

            font-size: .72rem;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 575.98px) {

            .login-page {
                padding: 20px 14px;
            }

            .login-card-body {
                padding: 27px 21px;
            }

            .brand-icon {
                width: 56px;
                height: 56px;

                border-radius: 16px;

                font-size: 1.4rem;
            }

            .brand-title {
                font-size: 1.3rem;
            }

            .bg-circle-one {
                width: 260px;
                height: 260px;
            }

            .bg-circle-two {
                width: 220px;
                height: 220px;
            }
        }

    </style>

</head>


<body>

<div class="login-page">

    <!-- BACKGROUND -->
    <div class="bg-circle bg-circle-one"></div>
    <div class="bg-circle bg-circle-two"></div>
    <div class="bg-circle bg-circle-three"></div>


    <div class="login-wrapper">


        <!-- =================================================
             BRAND
        ================================================== -->

        <div class="login-brand">

            <div class="brand-icon">

                <i class="fas fa-chart-pie"></i>

            </div>

            <h1 class="brand-title">
                Angsek
            </h1>

            <div class="brand-subtitle">
                Sistem Administrasi Anggaran Sekolah
            </div>

        </div>


        <!-- =================================================
             LOGIN CARD
        ================================================== -->

        <div class="card login-card">

            <div class="login-card-body">


                <!-- HEADER -->

                <div class="mb-4">

                    <h2 style="
                        margin:0;
                        color:#343a40;
                        font-size:1.05rem;
                        font-weight:800;
                    ">
                        Selamat datang kembali
                    </h2>

                    <p style="
                        margin:5px 0 0;
                        color:#858796;
                        font-size:.78rem;
                    ">
                        Silakan masuk menggunakan akun Anda.
                    </p>

                </div>


                <!-- =================================================
                     ERROR
                ================================================== -->

                <?php if ($this->session->flashdata('error')): ?>

                    <div class="alert alert-danger
                                alert-dismissible
                                fade show
                                login-alert"
                         role="alert">

                        <i class="fas fa-exclamation-circle mr-2"></i>

                        <?= html_escape(
                            $this->session->flashdata('error')
                        ) ?>

                        <button type="button"
                                class="close"
                                data-dismiss="alert"
                                style="font-size:1rem;">

                            <span>&times;</span>

                        </button>

                    </div>

                <?php endif; ?>


                <!-- =================================================
                     FORM
                ================================================== -->

                <form method="post"
                      action="<?= site_url('auth/login') ?>"
                      id="loginForm"
                      autocomplete="off">

                    <!-- CSRF -->

                    <input type="hidden"
                           name="<?= $this->security->get_csrf_token_name(); ?>"
                           value="<?= $this->security->get_csrf_hash(); ?>">


                    <!-- USERNAME -->

                    <div class="form-group mb-3">

                        <label class="form-label">
                            Username
                        </label>

                        <div class="input-wrapper">

                            <i class="fas fa-user input-icon"></i>

                            <input type="text"
                                   name="username"
                                   id="username"
                                   class="form-control login-input"
                                   placeholder="Masukkan username"
                                   autocomplete="username"
                                   autofocus
                                   required>

                        </div>

                    </div>


                    <!-- PASSWORD -->

                    <div class="form-group mb-3">

                        <label class="form-label">
                            Password
                        </label>

                        <div class="input-wrapper">

                            <i class="fas fa-lock input-icon"></i>

                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control login-input"
                                   placeholder="Masukkan password"
                                   autocomplete="current-password"
                                   required>

                            <button type="button"
                                    class="password-toggle"
                                    id="togglePassword"
                                    tabindex="-1"
                                    title="Tampilkan password">

                                <i class="fas fa-eye"></i>

                            </button>

                        </div>

                    </div>


                    <!-- LOGIN -->

                    <button type="submit"
                            class="btn btn-login btn-block"
                            id="loginButton">

                        <span id="loginButtonText">

                            <i class="fas fa-sign-in-alt mr-1"></i>

                            Masuk

                        </span>

                    </button>


                    <!-- SECURITY -->

                    <div class="login-security">

                        <i class="fas fa-shield-alt"></i>

                        Sistem terlindungi dengan keamanan sesi

                    </div>

                </form>

            </div>

        </div>


        <!-- =================================================
             FOOTER
        ================================================== -->

        <div class="login-footer">

            &copy; <?= date('Y') ?> Angsek

            <span class="mx-1">
                &bull;
            </span>

            Sistem Administrasi Anggaran Sekolah

        </div>


    </div>

</div>


<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script src="<?= base_url('assets/sbadmin2/vendor/jquery/jquery.min.js') ?>"></script>

<script src="<?= base_url('assets/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

<script src="<?= base_url('assets/sbadmin2/js/sb-admin-2.min.js') ?>"></script>


<script>

/* =========================================================
   TOGGLE PASSWORD
========================================================= */

$('#togglePassword').on('click', function()
{
    var password = $('#password');

    var icon = $(this).find('i');

    if (password.attr('type') === 'password') {

        password.attr(
            'type',
            'text'
        );

        icon
            .removeClass('fa-eye')
            .addClass('fa-eye-slash');

        $(this).attr(
            'title',
            'Sembunyikan password'
        );

    } else {

        password.attr(
            'type',
            'password'
        );

        icon
            .removeClass('fa-eye-slash')
            .addClass('fa-eye');

        $(this).attr(
            'title',
            'Tampilkan password'
        );
    }
});


/* =========================================================
   LOGIN LOADING
========================================================= */

$('#loginForm').on('submit', function()
{
    var button = $('#loginButton');

    button
        .prop('disabled', true);

    $('#loginButtonText').html(
        '<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...'
    );
});

</script>

</body>

</html>