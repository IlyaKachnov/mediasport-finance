@extends('tables.editable')
@section('table-content')
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="{{url('dashboard')}}">Главная</a>
            <i class="fa fa-circle"></i>
        </li>
          <li>
            <a href="{{url('leagues')}}">Лиги</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>{{$league->name}}</span>
        </li>
    </ul>
</div>
<h1 class="page-title"> {{$league->name}} </h1>
<div class="portlet light portlet-fit bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-settings font-red"></i>
            <span class="caption-subject font-red sbold uppercase"> Команды </span>
        </div>
    </div>
    <div id="error-empty" class="alert alert-danger display-hide" style="margin:1%;">Заполните все поля!<button class="close" data-close="alert"></button></div>
    <div id="error-unique" class="alert alert-danger display-hide" style="margin:1%;">Команда уже существует!<button class="close" data-close="alert"></button></div>
    <div class="portlet-body">
        <div class="table-toolbar">
            <div class="row">
                <div class="col-md-6">
                    <div class="btn-group">
                        <a id="sample_add_new"  href="{{url('leagues')}}/{{$league->id}}/teams" class="btn green"> Добавить
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @if($league->teams)
        <table class="table table-striped table-hover table-bordered" id="teams_datatable">
            <thead>
                <tr>
                    <th> Название команды </th>
                    <th> Остаток </th>
                    <th> Общая аренда </th>
                    <th> Предоплата </th>
                    <th> Долги </th>
                    <th> Редактировать </th>
                    <th> Удалить/Отменить </th>
                </tr>
            </thead>
            <tbody>
                @foreach($league->teams as $team)
                <tr>
                    <td> {{$team->name}} </td>
                    <td class="new-row">{{$team->balance}}</td>
                    <td class="new-row">{{$team->total_prepays}}</td>
                    <td>
                        <a href="/teams/{{$team->id}}/prepays"> Перейти </a>
                    </td>
                      <td>
                        <a href="/teams/{{$team->id}}/debts"> Перейти </a>
                    </td>
                        <td>
                        <a class="edit"  data-id="{{$team->id}}" href="javascript:;"> Редактировать </a>
                    </td>
                    <td>
                        <a class="delete" data-id="{{$team->id}}" href="javascript:;"> Удалить </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection
@section('table-js')
{!!Html::script('/assets/pages/scripts/teams-datatable.js')!!}
@endsection