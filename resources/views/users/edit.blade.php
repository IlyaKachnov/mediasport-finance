@extends('forms.basic-layout')
@section('title')
{{'Редактировать:'}} {{$user->name}}
@endsection
@section('form')
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
{!!Form::model($user,['id'=>'form_sample_3','class'=>'form-horizontal','method'=>'patch','route'=>['users.update',$user]])!!}
{{ csrf_field() }}
    <div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button> Введенные данные содержат ошибки. </div>
    <div class="form-group">
        <label class="control-label col-md-2">Роль
            <span class="required"> * </span>
        </label>
        <div class="col-md-4">
            {!! Form::select('roles_list', $roles, $user->role->id, ['class'=>'form-control select2me']) !!}

        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2"> Назначить на залы
            <span class="required"> </span>
        </label>
        <div class="col-md-4">
            {!! Form::select('gym_list[]', $gyms, $user->selected_gyms, ['class'=>'form-control select2me','multiple'=>true]) !!}
        </div>
    </div>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-2 col-md-9">
                {!! Form::submit('Обновить', ['class'=>'btn green']) !!}
                <a href="{{url('users')}}" role="button" class="btn default">Отмена</a>
            </div>
        </div>
    </div>
</div>
{!!Form::close()!!}
@endsection