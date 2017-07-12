@extends('layouts.master')
@section('header')
{!!Html::style('/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')!!}
{!!Html::style('/assets/pages/css/profile.min.css')!!}
@endsection
@section('content')
<h1 class="page-title"> Информация о пользователе аккаунта
</h1>

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PROFILE SIDEBAR -->
        <div class="profile-sidebar">
            <!-- PORTLET MAIN -->
            <div class="portlet light profile-sidebar-portlet ">
                <!-- SIDEBAR USERPIC -->
                <div class="profile-userpic">
                    <img src="/avatars/{{Auth::user()->avatar}}" class="img-responsive" alt=""> </div>

                <div class="profile-usertitle" style="padding-bottom: 10px !important;">
                    <div class="profile-usertitle-name"> {{Auth::user()->name}} </div>
                    <div class="profile-usertitle-job"> {{Auth::user()->role->caption}} </div>
                </div>

            </div>

        </div>

        <div class="profile-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light ">
                        <div class="portlet-title tabbable-line">
                            <div class="caption caption-md">
                                <i class="icon-globe theme-font hide"></i>
                                <span class="caption-subject font-blue-madison bold uppercase">Профиль</span>
                            </div>
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_1_1" data-toggle="tab">Персональная информация</a>
                                </li>
                                <li>
                                    <a href="#tab_1_2" data-toggle="tab">Сменить аватар</a>
                                </li>

                                <li>
                                    <a href="#tab_1_3" data-toggle="tab">Изменение пароля</a>
                                </li>

                            </ul>
                        </div>
                        <div class="portlet-body">
                            <div class="tab-content">
                                <!-- PERSONAL INFO TAB -->
                                <div class="tab-pane active" id="tab_1_1">
                                    {!!Form::model(Auth::user(),['method'=>'patch','action'=>['AdministratorController@update',Auth::user()]])!!}
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label class="control-label">Имя</label>
                                        {!! Form::text('firstname', null,[ 'class'=>'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Фамилия</label>
                                        {!! Form::text('surname',null,[ 'class'=>'form-control']) !!} </div>
                                    <div class="form-group">
                                        <label class="control-label">Электронная почта</label>
                                        {!! Form::email('email', null,[ 'class'=>'form-control']) !!} </div>
                                    <div class="margiv-top-10">
                                        {!! Form::submit('Сохранить изменения', ['class'=>'btn green']) !!}
                                        <a href="{{url('/')}}" class="btn default"> Отменить </a>
                                    </div>
                                    {!!Form::close()!!}
                                </div>
                                <div class="tab-pane" id="tab_1_2">
                                    {!!Form::open(['method'=>'post','action'=>['AdministratorController@storeAvatar',Auth::user()],'files'=>true])!!}
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" /> </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                            <div>
                                                <span class="btn default btn-file">
                                                    <span class="fileinput-new"> Выбрать </span>
                                                    <span class="fileinput-exists"> Изменить </span>
                                                    <input type="file" name="avatar"> </span>
                                                <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Удалить </a>
                                            </div>
                                        </div>

                                    </div>

                                    <span> Допустимый формат изображений: jpg, jpeg, png </span>
                                    <div class="margin-top-10">
                                        {!! Form::submit('Загрузить', ['class'=>'btn green']) !!}
                                        <a href="{{url('/')}}" class="btn default"> Отменить </a>
                                    </div>
                                    {!!Form::close()!!}
                                </div>

                                <div class="tab-pane" id="tab_1_3">
                                    <form id="form_sample_3" role="form" method="POST" action="{{ action('AdministratorController@postChange',Auth::user())}}">
                                        {{ csrf_field() }}
                                        <div class="alert alert-danger display-hide">
                                            <button class="close" data-close="alert"></button> Введенные данные содержат ошибки. </div>
                                        <div class="form-group">
                                            <label class="control-label">Текущий пароль</label>
                                            <input id = "old_password" name="old_password" type="password" class="form-control" data-required="1"/> </div>

                                        <div class="form-group">
                                            <label class="control-label">Новый пароль</label>
                                            <input id="password" type="password" name ="password" class="form-control" data-required="1" /> </div>
                                    
                                        <div class="form-group">
                                            <label class="control-label">Подтверждение нового пароля</label>
                                            <input id="password-confirm" name="password_confirmation" type="password" class="form-control" data-required="1"/> </div>
                                     
                                        <div class="margin-top-10">
                                            <button type="submit" class="btn green"> Изменить пароль </button>
                                            <button type="reset" class="btn default"> Отменить</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@section('plugins')
{!!Html::script('/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')!!}
{!!Html::script('/assets/global/plugins/jquery.sparkline.min.js')!!}
{!!Html::script('/assets/global/plugins/jquery-validation/js/jquery.validate.min.js')!!}
{!!Html::script('/assets/global/plugins/jquery-validation/js/additional-methods.min.js')!!}
@endsection
@section('scripts')
{!!Html::script('/assets/pages/scripts/profile.min.js')!!}
{!!Html::script('/assets/pages/scripts/password-validation.js')!!}
@endsection