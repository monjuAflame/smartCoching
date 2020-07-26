<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Smart Coching') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  
</head>
<body class="hold-transition lockscreen">
    <div id="app">
      <div class="nav-menu">
        <ul>
          <li> <a class="nav-link" href="{{ route('frontEnd.about') }}">{{ __('About') }}</a></li>
          <li> <a class="nav-link" href="{{ route('frontEnd.contact') }}">{{ __('Contact') }}</a></li>
        </ul>
      </div>
        <div class="lockscreen-wrapper">
          <div class="lockscreen-logo">
            <a href="{{ route('frontEnd.home') }}"><b>Smart</b>Coching</a>
          </div>
          <div class="lockscreen-name">Hi, Student</div>

          <div class="lockscreen-item">
            <div class="lockscreen-image">
              <img src="{{ asset('img/fuser.png') }}" alt="User Image">
            </div>

            <form class="lockscreen-credentials" method="POST" action="{{ route('frontEnd.searchStudentId') }}">
                @csrf
              <div class="input-group">
                <input type="text" name="student_id" class="form-control" placeholder="Student ID">

                <div class="input-group-append">
                  <button type="submit" class="btn"><i class="fas fa-arrow-right text-muted"></i></button>
                </div>
              </div>
            </form>

          </div>
          <!-- /.lockscreen-item -->
          <div class="help-block text-center">
            Enter your student ID & see your Details.
          </div>
          <div class="text-center">
            <b>Or Contact us & Create course</b>
          </div>
          <div class="lockscreen-footer text-center">
            Copyright &copy; 2019-2020 <b><a href="http://adminlte.io" class="text-black">SmartCoching.org</a></b><br>
            All rights reserved
          </div>
        </div>
    </div>
</body>
</html>
