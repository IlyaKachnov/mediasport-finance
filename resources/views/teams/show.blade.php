@extends('tables.report')
@section('table-content')
<div class="portlet light portlet-fit portlet-datatable bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-settings font-green"></i>
            <span class="caption-subject font-green sbold uppercase">Отчет команды</span>
        </div>
        <div class="actions">

            <div class="btn-group">
                <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                    <i class="fa fa-share"></i>
                    <span class="hidden-xs"> Экспорт отчета </span>
                    <i class="fa fa-angle-down"></i>
                </a>
              <ul class="dropdown-menu pull-right" id="sample_3_tools">
                    <li>
                        <a href="javascript:;" data-action="0" class="tool-action">
                            <i class="icon-printer"></i> Печать</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-action="1" class="tool-action">
                            <i class="icon-check"></i> Копировать</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-action="2" class="tool-action">
                            <i class="icon-doc"></i> PDF</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-action="3" class="tool-action">
                            <i class="icon-paper-clip"></i> Excel</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-action="4" class="tool-action">
                            <i class="icon-cloud-upload"></i> CSV</a>
                    </li>
                    <li class="divider"> </li>
                    <li>
                        <a href="javascript:;" data-action="5" class="tool-action">
                            <i class="icon-refresh"></i> Перезагрузить</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
     <div class="alert alert-danger display-hide" style="margin:1%;">Необходимо указать обе даты!<button class="close" data-close="alert"></button></div>
    <div class="row-fluid clearfix margin-bottom-10 margin-top-20">

        <div class="col-sm-2 clearfix">
            <div class="input-group date date-picker margin-bottom-5 " data-date-format="dd.mm.yyyy" >
                <input type="text" id="date_from" class="form-control form-filter input-sm" placeholder="Дата с" readonly >
                <span class="input-group-btn">
                    <button class="btn btn-sm default" type="button">
                        <i class="fa fa-calendar"></i>
                    </button>
                </span>
            </div>

        </div>
        <div class="col-sm-2 clearfix" >
            <div class="input-group date date-picker margin-bottom-5" data-date-format="dd.mm.yyyy">
                <input type="text" id="date_until" class="form-control form-filter input-sm" placeholder="Дата до" readonly>
                <span class="input-group-btn">
                    <button class="btn btn-sm default" type="button">
                        <i class="fa fa-calendar"></i>
                    </button>
                </span>
            </div>
        </div>
        <div class="col-sm-3">
            <select id="league_list" class="form-control input-sm select2-multiple select2-hidden-accessible" tabindex="-1" aria-hidden="true">
              <option></option>
              @foreach($leagues as $league)
              <option value="{{$league->id}}">{{$league->name}}</option>
              @endforeach
            </select>
        </div>
        <div class="col-sm-2">
            <label class="col-sm-4 control-label">Взносы</label>
            <div class="col-sm-8">
                
                    <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" id="fees" value="1"/>
                        <span></span>
                    </label>
               
            </div>
        </div>
        <div class="col-sm-3">
            <div class="actions pull-right">
                <div class="btn-group btn-group-devided" data-toggle="buttons">

                    <button type="button" id="send_btn" data-href="{{route('teams.filter')}}" class="btn btn-default mt-ladda-btn ladda-button btn-circle btn-sm" data-style="expand-left" data-spinner-color="#333">
                        <span class="ladda-label">Отчет</span>
                    </button>

                    <button type="button" id="reload_btn" data-href="{{route('teams.filter')}}" class="btn btn-default mt-ladda-btn ladda-button btn-circle btn-sm" data-style="expand-left" data-spinner-color="#333">
                        <span class="ladda-label">Весь период</span>
                    </button>
                </div>
            </div>

        </div>   

    </div>

    <div class="portlet-body">
        <div class="table-container">
            @if($teams)
            <table class="table table-striped table-bordered table-hover" id="sample_3">
                <thead>
                    <tr>
                        <th> Команда </th>
                        <th> Расходы нал </th>
                        <th> Расходы безнал </th>
                        <th> Расходы карта </th>
                        <td> Расходы </td>
                        <th> Доходы нал </th>
                        <th> Доходы безнал </th>
                        <th> Доходы карта </th>
                        <td> Доходы </td>
                        <td> Итог </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teams as $team)
                    <tr>

                        <td data-id="{{$team->id}}"> {{$team->name}} </td>
                        <td> {{$team->cash_expenses}} </td>
                        <td> {{$team->cashless_expenses}} </td>
                         <td> {{$team->card_expenses}} </td>
                        <td>{{$team->total_expenses}}  </td>
                        <td> {{$team->cash_incomes}} </td>
                        <td> {{$team->cashless_incomes}} </td>
                          <td> {{$team->card_incomes}} </td>
                        <td>{{$team->total_incomes}}  </td>
                        <td>{{$team->total}}  </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>

@endsection
@section('table-js')
{!!Html::script('/assets/pages/scripts/components-select2.min.js')!!}
{!!Html::script('/assets/pages/scripts/teams-report.js')!!}
@endsection