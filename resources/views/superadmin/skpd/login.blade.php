<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Masuk Sebagai SKPD</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="/theme/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/theme/dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    @toastr_css
</head>

<body class="hold-transition lockscreen">
    <!-- Automatic element centering -->
    <div class="lockscreen-wrapper">
        <div class="lockscreen-logo">
            <a href="/theme/index2.html"><b>{{$skpd->nama}}</b></a>
        </div>
        <!-- User name -->
        <div class="lockscreen-name">{{$skpd->kode_skpd}}</div>

        <!-- START LOCK SCREEN ITEM -->
        <div class="lockscreen-item">
            <!-- lockscreen image -->
            <div class="lockscreen-image">
                <img src="/theme/dist/img/user1-128x128.jpg" alt="User Image">
            </div>
            <!-- /.lockscreen-image -->

            <!-- lockscreen credentials (contains the form) -->
            <form class="lockscreen-credentials" method="POST" action="/superadmin/skpd/login/{{$skpd->id}}">
                @csrf
                <div class="input-group">
                    <input type="password" name="password_superadmin" class="form-control" placeholder="password">
                    <input type="hidden" name="username" class="form-control" value="{{$skpd->kode_skpd}}">

                    <div class="input-group-append">
                        <button type="submit" class="btn"><i class="fas fa-arrow-right text-muted"></i></button>
                    </div>
                </div>
            </form>
            <!-- /.lockscreen credentials -->

        </div>
    </div>
    <!-- /.center -->

    <!-- jQuery -->
    <script src="/theme/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/theme/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    @toastr_js
    @toastr_render
</body>

</html>