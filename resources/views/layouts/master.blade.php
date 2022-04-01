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
        @include('common.footer')
    </div>
</body>
</html>