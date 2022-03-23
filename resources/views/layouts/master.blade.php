<!DOCTYPE html>
<html lang="en">
<head>
    @include('common.header')
    
</head>
<body class="fix-header fix-sidebar">
    @yield('content')
    @yield('css')
    @yield('js')
    @include('common.footer')
</body>
</html>