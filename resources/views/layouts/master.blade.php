<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8" />
        <title>Национальная мини-футбольная лига</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />

        <meta content="" name="author" />

        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
        {!!Html::style('/assets/global/plugins/font-awesome/css/font-awesome.min.css')!!}
        {!!Html::style('/assets/global/plugins/simple-line-icons/simple-line-icons.min.css')!!}
        {!!Html::style('/assets/global/plugins/bootstrap/css/bootstrap.min.css')!!}
        {!!Html::style('/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')!!}

        @if(Auth::check())
        @yield('header')
        @endif
        {!!Html::style('/assets/global/css/components-rounded.min.css')!!}
        {!!Html::style('/assets/global/css/plugins.min.css')!!}
        {!!Html::style('/assets/layouts/layout/css/layout.min.css')!!}
        {!!Html::style('/assets/layouts/layout/css/themes/darkblue.min.css')!!}
        {!!Html::style('/assets/layouts/layout/css/custom.min.css')!!}
        <link rel="shortcut icon" href="favicon.ico" />
        <script>
            window.Laravel = <?php
echo json_encode([
    'csrfToken' => csrf_token(),
]);
?>

        </script>
    </head>
    @if(Auth::check())
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">

        <div class="page-wrapper">
            @include('layouts.header')
            <div class="page-container">
                @include('layouts.sidebar')
                <div class="page-content-wrapper">
                    <div class="page-content" style="min-height: 1436px;">

                        @yield('content')
                    </div>
                </div>
            </div>
            @include('layouts.footer')


        </div>

        {!!Html::script('/assets/global/plugins/jquery.min.js')!!}
        {!!Html::script('/assets/global/plugins/bootstrap/js/bootstrap.min.js')!!}
        {!!Html::script('/assets/global/plugins/js.cookie.min.js')!!}
        {!!Html::script('/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')!!}
        {!!Html::script('/assets/global/plugins/jquery.blockui.min.js')!!}
        {!!Html::script('/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')!!}

        {!!Html::script('/assets/pages/custom-scripts/ajax-setup.js')!!}
        @if(Auth::check())
        @yield('plugins')
        {!!Html::script('/assets/global/scripts/app.min.js')!!}
        @yield('scripts')
        {!!Html::script('/assets/layouts/layout/scripts/layout.min.js')!!}
        {!!Html::script('/assets/layouts/layout/scripts/demo.min.js')!!}
        {!!Html::script('/assets/layouts/global/scripts/quick-sidebar.min.js')!!}
        {!!Html::script('/assets/layouts/global/scripts/quick-nav.min.js')!!}

        @endif

    </body>
    @else
    @yield('content')
    @endif
</html>