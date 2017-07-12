<body class="login" style="background-color: #dadada !important;">
    <!-- BEGIN LOGO -->
    <div class="logo">
		<a href="">
                <img src="/public/assets/pages/img/logo-big.jpg" alt="" /> </a>
    </div>
    <!-- END LOGO -->
    <!-- BEGIN LOGIN -->
    <div class="content">
        <!-- BEGIN LOGIN FORM -->
        <form class="login-form" action="{{route('login')}}" method="post">
                 {{ csrf_field() }}
            <h3 class="form-title font-green">Войти</h3>
            <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button>
                <span> Введите логин и пароль. </span>
            </div>
          
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label class="control-label visible-ie8 visible-ie9">Username</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Логин" value="{{ old('name') }}" name="name"/> 
            </div>
                 @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                <label class="control-label visible-ie8 visible-ie9">Password</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Пароль" name="password"/> 
            </div>
                                     @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
            <div class="form-actions">
                <button type="submit" class="btn green uppercase" style="width: 150px; margin-left: auto; margin-right: auto; display: block;">Войти</button>
                <!--<label class="rememberme check mt-checkbox mt-checkbox-outline">
                    <input type="checkbox" name="remember" value="1" />Запомнить
                    <span></span>
                </label>-->
                <!--<a href="javascript:;" id="forget-password" class="forget-password">Forgot Password?</a>-->
            </div>
  
        </form>

    </div>