<!DOCTYPE html>
<html>

<head>
  <title>PABWE - Todo App</title>
  <link href="{{ asset('assets/vendor/bootstrap-5.3.3-dist/css/bootstrap.min.css') }}" rel="stylesheet" />
</head>

<body>
  <div class="container-fluid p-5">
    @yield('content')
  </div>
  <script src="{{ asset('assets/vendor/bootstrap-5.3.3-dist/js/bootstrap.min.js') }}"></script>
  @yield('other-js')
</body>

</html>