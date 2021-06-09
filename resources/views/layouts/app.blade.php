<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>TPP</title>
  @include('layouts.css')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark navbar-lightblue">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link">@yield('title')</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar  sidebar-light-lightblue elevation-4">
    <a href="#" class="brand-link navbar-lightblue">
      <img src="https://p.kindpng.com/picc/s/78-786207_user-avatar-png-user-avatar-icon-png-transparent.png" alt="User" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light text-white text-sm">{{Auth::user()->name}}</span>
    </a>
    <div class="sidebar">
      
      @if (Auth::user()->hasRole('superadmin'))
        @include('layouts.menu_superadmin')
      @elseif (Auth::user()->hasRole('admin'))
        @include('layouts.menu_admin')
          
      @elseif (Auth::user()->hasRole('pegawai'))
        @include('layouts.menu_pegawai')
      
      @elseif (Auth::user()->hasRole('walikota'))
        @include('layouts.menu_walikota')

      @endif


    </div>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
          @yield('content')
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>

  <footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
    </div>
    <strong>Copyright &copy; 2021 Diskominfotik Banjarmasin</strong>
    <div class="float-right d-none d-sm-inline-block">
      
      <!-- Histats.com  (div with counter) -->
      <div id="histats_counter"></div>
      <!-- Histats.com  START  (aync)-->
      <script type="text/javascript">var _Hasync= _Hasync|| [];
        _Hasync.push(['Histats.start', '1,4555231,4,107,170,20,00000001']);
        _Hasync.push(['Histats.fasi', '1']);
        _Hasync.push(['Histats.track_hits', '']);
        (function() {
        var hs = document.createElement('script'); hs.type = 'text/javascript'; hs.async = true;
        hs.src = ('//s10.histats.com/js15_as.js');
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(hs);
        })();</script>
        <noscript><a href="/" target="_blank"><img  src="//sstatic1.histats.com/0.gif?4555231&101" alt="site hit counter" border="0"></a></noscript>
        <!-- Histats.com  END  -->
    </div>
  </footer>
</div>

@include('layouts.js')
</body>
</html>
