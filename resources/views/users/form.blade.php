<div class="form-body">
    @if (count($errors) > 0)
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
    <div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button> Введенные данные содержат ошибки. </div>
    <div class="form-group">
        <label class="control-label col-md-2">Имя пользователя
            <span class="required"> * </span>
        </label>
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-user"></i>
                </span>
                {!! Form::text('name', null,['class'=>'form-control', 'data-required'=>'1']) !!}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 control-label">Email
            <span class="required"> * </span>
        </label>
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-envelope"></i>
                </span>
                {!! Form::email('email', null,[ 'class'=>'form-control', 'data-required'=>'1']) !!} </div>
        </div>
    </div>
        <div class="form-group">
        <label class="control-label col-md-2">Пароль
            <span class="required"> * </span>
        </label>
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-key"></i>
                </span>
                {!! Form::password('password',[ 'class'=>'form-control', 'data-required'=>'1']) !!}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 control-label">Имя
        </label>
        <div class="col-md-4">
            {!! Form::text('firstname', null,['class'=>'form-control']) !!} </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 control-label">Фамилия
        </label>
        <div class="col-md-4">                
            {!! Form::text('surname', null,['class'=>'form-control']) !!} </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2">Роль
            <span class="required"> * </span>
        </label>
        <div class="col-md-4">
            {!! Form::select('roles_list', $roles, $selectedRole, ['class'=>'form-control select2me']) !!}

        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2"> Назначить на залы
            <span class="required"> </span>
        </label>
        <div class="col-md-4">
            {!! Form::select('gym_list', $gyms, $selectedGyms, ['class'=>'form-control select2me']) !!}
        </div>
    </div>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-2 col-md-9">
                {!! Form::submit($submitBtnText, ['class'=>'btn green']) !!}
                <a href="{{url('users')}}" role="button" class="btn default">Отмена</a>
            </div>
        </div>
    </div>
</div>