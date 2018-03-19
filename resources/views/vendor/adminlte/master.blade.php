<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title_prefix', config('adminlte.title_prefix', ''))
@yield('title', config('adminlte.title', 'AdminLTE 2'))
@yield('title_postfix', config('adminlte.title_postfix', ''))</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/AdminLTE.min.css') }}">
    <!-- Folha Css Caderno -->
    <link rel="stylesheet" href="{{ url('/assets/css/folhaCss.css') }} ">
    <!-- Caderno JS-->
    <script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!--Favicon-->
    <link rel="icon" type="image/png" href="{{ url('/data/images/default_estabelecimentos/estabelecimento.png') }}">
    
    @if(config('adminlte.plugins.select2'))
        <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('assets/js/remote/cdnjs.cloudflare.com_ajax_libs_select2_4.0.3_css/select2.css') }}">
    @endif

    @if(config('adminlte.plugins.datatables'))
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('assets/js/remote/cdn.datatables.net_1.10.15_css/jquery.dataTables.min.css') }}">
    @endif

    @yield('adminlte_css')

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="{{ asset('assets/js/remote/fonts.googleapis.com/css_fontes.css') }}">
</head>
<body class="hold-transition @yield('body_class')">
@yield('body')

@if(config('adminlte.plugins.select2'))
    <!-- Select2 -->
    <script src="{{ asset('assets/js/remote/cdnjs.cloudflare.com_ajax_libs_select2_4.0.3_js/select2.min.js') }}"></script>
@endif

@if(config('adminlte.plugins.datatables'))
    <!-- DataTables -->
    <script src="{{ asset('assets/js/remote/cdn.datatables.net_1.10.15_js/jquery.dataTables.min.js') }}"></script>
@endif

@yield('adminlte_js')

</body>
</html>
