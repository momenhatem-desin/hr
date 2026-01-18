<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>تسجيل الدخول - لوحة التحكم</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🔐</text></svg>">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('assets/admin/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('assets/admin/fonts/ionicons/2.0.1/css/ionicons.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="{{ asset('assets/admin/dist/css/adminlte.min.css') }}">
  <!-- RTL Bootstrap -->
  <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap_rtl-v4.2.1/custom_rtl.css') }}">
  <!-- Google Font -->
  <link href="{{ asset('assets/admin/fonts/SansPro/SansPro.min.css') }}" rel="stylesheet">

  <style>
    :root {
      --primary-color: #4a6bff;
      --hover-color: #3a56d4;
      --shadow-color: rgba(74, 107, 255, 0.2);
    }
    
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
      font-family: 'Cairo', sans-serif;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-box {
      width: 400px;
      margin: 0 auto;
      animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .login-card-body {
      border-radius: 12px;
      box-shadow: 0 10px 30px var(--shadow-color);
      border: none;
      overflow: hidden;
      position: relative;
    }

    .login-card-body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(90deg, var(--primary-color), #6c8eff);
    }

    .login-box-msg {
      font-size: 1.4rem;
      font-weight: bold;
      color: var(--primary-color);
      margin: 20px 0;
      text-align: center;
      position: relative;
    }

    .login-box-msg::after {
      content: '';
      display: block;
      width: 50px;
      height: 3px;
      background: var(--primary-color);
      margin: 10px auto;
      border-radius: 3px;
    }

    .form-control {
      border-radius: 8px !important;
      padding: 12px 15px;
      border: 1px solid #e0e4f0;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem var(--shadow-color);
    }

    .form-control::placeholder {
      text-align: right;
      color: #a0a4b0;
      font-size: 0.9rem;
    }

    .input-group-text {
      background-color: #fff;
      border-left: 0;
      border-radius: 0 8px 8px 0 !important;
      transition: all 0.3s ease;
      color: var(--primary-color);
      border-right: 1px solid #e0e4f0 !important;
      cursor: pointer;
    }

    .input-group-text:hover {
      background-color: #f8f9fa;
    }

    .btn-primary {
      background-color: var(--primary-color);
      border: none;
      font-weight: bold;
      padding: 12px;
      border-radius: 8px;
      font-size: 1.1rem;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px var(--shadow-color);
    }

    .btn-primary:hover {
      background-color: var(--hover-color);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px var(--shadow-color);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    .text-danger {
      font-size: 0.9rem;
      margin-right: 5px;
      display: flex;
      align-items: center;
    }

    .text-danger::before {
      content: '\f06a';
      font-family: 'Font Awesome 5 Free';
      font-weight: 900;
      margin-left: 5px;
      font-size: 0.8rem;
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
      .login-box {
        width: 90%;
      }
    }

    /* Password toggle icon */
    .password-toggle {
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .password-toggle:hover {
      color: var(--hover-color);
    }
  </style>
</head>

<body class="hold-transition login-page" style="background-size: cover; background-image: url('{{ asset('assets/admin/imgs/login.jpg') }}')">
<div class="login-box">
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">تسجيل الدخول إلى لوحة التحكم</p>
      
      @if ($errors->has('login'))
      <div class="alert alert-danger text-center">
        <i class="fas fa-exclamation-triangle ml-2"></i>
        {{ $errors->first('login') }}
      </div>
      @endif

      <form action="{{ route('admin.login') }}" method="POST">
        @csrf

        <!-- Username -->
        <div class="input-group mb-3">
          <input type="text" name="username" class="form-control" placeholder="اسم المستخدم" value="{{ old('username') }}" autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <i class="fas fa-user"></i>
            </div>
          </div>
        </div>
        @error('username')
          <div class="text-danger mb-2">{{ $message }}</div>
        @enderror

        <!-- Password -->
        <div class="input-group mb-3">
          <input type="password" name="password" id="password" class="form-control" placeholder="كلمة المرور">
          <div class="input-group-append">
            <div class="input-group-text password-toggle" id="togglePassword">
              <i class="fas fa-lock" id="passwordIcon"></i>
            </div>
          </div>
        </div>
        @error('password')
          <div class="text-danger mb-2">{{ $message }}</div>
        @enderror

        <!-- Remember Me -->
        <div class="row mb-3">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember" style="font-size: 0.9rem; cursor: pointer;">
                <i class="far fa-bookmark mr-1"></i> تذكرني
              </label>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">
              <i class="fas fa-sign-in-alt ml-2"></i> دخول
            </button>
          </div>
        </div>
      </form>

      <!-- Footer Links -->
      <div class="text-center mt-3">
        <a href="#" class="text-muted" style="font-size: 0.85rem;">
          <i class="fas fa-question-circle mr-1"></i> نسيت كلمة المرور؟
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="{{ asset('assets/admin/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script>
  $(document).ready(function() {
    // Toggle password visibility
    $('#togglePassword').click(function() {
      const passwordInput = $('#password');
      const passwordIcon = $('#passwordIcon');
      
      if (passwordInput.attr('type') === 'password') {
        passwordInput.attr('type', 'text');
        passwordIcon.removeClass('fa-lock').addClass('fa-lock-open');
      } else {
        passwordInput.attr('type', 'password');
        passwordIcon.removeClass('fa-lock-open').addClass('fa-lock');
      }
    });

    // Animation on load
    $('.login-card-body').addClass('animate__animated animate__fadeInUp');
  });
</script>
</body>
</html>