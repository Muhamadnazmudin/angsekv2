<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Login - Angsek</title>

    <link href="<?= base_url('assets/sbadmin2/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/sbadmin2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

<div class="container">

    <div class="row justify-content-center">

        <div class="col-xl-5 col-lg-6 col-md-8">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-5">

                    <!-- HEADER -->
                    <div class="text-center mb-4">
                        <h1 class="h4 text-gray-900">Login Angsek</h1>
                        <small class="text-muted">Silakan masuk untuk melanjutkan</small>
                    </div>

                    <!-- ALERT ERROR -->
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <?= $this->session->flashdata('error'); ?>
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- FORM -->
                    <form method="post" action="<?= site_url('auth/login') ?>">
                        <input type="hidden"
                               name="<?= $this->security->get_csrf_token_name(); ?>"
                               value="<?= $this->security->get_csrf_hash(); ?>" />

                        <!-- USERNAME -->
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                                <input type="text"
                                       name="username"
                                       class="form-control"
                                       placeholder="Username"
                                       autofocus
                                       required>
                            </div>
                        </div>

                        <!-- PASSWORD -->
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                <input type="password"
                                       name="password"
                                       class="form-control"
                                       placeholder="Password"
                                       required>
                            </div>
                        </div>

                        <!-- BUTTON -->
                        <button class="btn btn-primary btn-block">
                            <i class="fas fa-sign-in-alt mr-1"></i> Masuk
                        </button>
                    </form>

                </div>
            </div>

            <!-- FOOTER -->
            <div class="text-center text-white small">
                © <?= date('Y') ?> Angsek
            </div>

        </div>

    </div>

</div>

<script src="<?= base_url('assets/sbadmin2/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/sbadmin2/js/sb-admin-2.min.js') ?>"></script>

</body>
</html>
