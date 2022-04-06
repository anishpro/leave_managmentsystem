<!DOCTYPE html>
<html lang="en">
<head>
    @include('common.header')
    
</head>
<body class="fix-header fix-sidebar">
    <div id="app">
        @yield('content')
        @yield('css')
        @yield('js')
    </div>
    @include('common.footer')
    @auth
        <script>
            window.user = @json(auth()->user()->roles->pluck('name'));           
        </script>
    @endauth
</body>
</html>