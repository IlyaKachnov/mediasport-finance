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
            <span class="caption-subject font-red sbold uppercase">Долги</span>
        </div>
    </div>
    <div class="portlet-body">
        @if($team->debts)
        <table class="table table-striped table-hover table-bordered" id="debts_table">
            <thead>
                <tr>
                    <th> Дата </th>
                    <th> Сумма долга </th>
                    <th> Статус </th>
                    <th> Действие </th>
                </tr>
            </thead>
            <tbody>
                @foreach($team->debts as $debt)
                <tr>
                    <td> {{$debt->debt_day}} </td>
                    <td> {{$debt->debt_amount}} </td>
                    <td class="status"> {{$debt->is_repaid ?  'Погашен' : 'Активен' }} </td>
                    <td>
                        <a class="change-status btn {{$debt->is_repaid ? 'red-mint' : 'green-haze'}}"  data-status="{{$debt->is_repaid}}" data-id="{{$debt->id}}" href="javascript:;"> {{$debt->is_repaid ?  'Активировать' : 'Погасить' }} </a>
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
{!!Html::script('/assets/pages/scripts/debts-table.js')!!}
@endsection