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
            <a href="{{url('leagues')}}/{{$team->league->id}}/teams">{{$team->league->name}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>{{$team->name}}</span>
        </li>
    </ul>
</div>
<h1 class="page-title"> Команда "{{$team->name}}" </h1>
<div class="portlet light portlet-fit bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-settings font-red"></i>
            <span class="caption-subject font-red sbold uppercase">Предоплата</span>
        </div>
    </div>
    <div class="alert alert-danger display-hide" style="margin:1%;">Заполните все поля!<button class="close" data-close="alert"></button></div>
    <div class="portlet-body">
        <div class="table-toolbar">
            <div class="row">
                <div class="col-md-6">
                    <div class="btn-group">
                        <a id="sample_add_new"  href="/teams/{{$team->id}}/prepays" class="btn green"> Добавить
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @if($team->prepays)
        <table class="table table-striped table-hover table-bordered" id="prepays_table">
            <thead>
                <tr>
                    <th> Дата </th>
                    <th> Сумма предоплаты </th>
                    <th> Редактировать </th>
                    <th> Удалить/Отменить </th>
                </tr>
            </thead>
            <tbody>
                @foreach($team->prepays as $prepay)
                <tr>
                    <td> {{$prepay->payday}} </td>
                    <td> {{$prepay->amount}} </td>
                    <td>
                        <a class="edit"  data-id="{{$prepay->id}}" href="javascript:;"> Редактировать </a>
                    </td>
                    <td>
                        <a class="delete" data-id="{{$prepay->id}}" href="javascript:;"> Удалить </a>
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
{!!Html::script('/assets/pages/scripts/prepays-table.js')!!}
{!!Html::script('/assets/pages/scripts/prepays-initpickers.js')!!}
@endsection