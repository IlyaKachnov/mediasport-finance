@extends('tables.editable')
@section('table-content')
<h1 class="page-title"> Игровой день зал "{{$gym->name}}" </h1>
<div class="portlet light portlet-fit bordered">
    <div class="row-fluid clearfix margin-bottom-10 margin-top-20">
        <form class="form-horizontal" method="POST" action="{{route('matches.reload',$gym)}}">
            {{ csrf_field() }}
            <div class="col-lg-2 col-xs-6">
                <div id ="main-date" class="input-group date" data-date-format="dd-mm-yyyy">
                    <input type="text" name="main_date" id="global-date" class="form-control form-filter input-sm" value="{{isset($selectedDate)?$selectedDate:\Carbon\Carbon::today()->format('d-m-Y')}}" readonly>
                    <span class="input-group-btn"><button class="btn btn-sm default" type="button">
                            <i class="fa fa-calendar"></i></button></span></div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <button class="btn btn-sm blue" type="submit">Показать</button>
                <a href="{{action('MatchController@index',$gym)}}" class="btn btn-sm blue-soft" role="button">Текущая дата</a>  
            </div> 
        </form>
    </div>
    <div class="portlet-body">
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-red"></i>
                    <span class="caption-subject font-red sbold uppercase"> Матчи </span>
                </div>
            </div>
            <div id="match-error" class="alert alert-danger display-hide" style="margin:1%;">Не все поля заполнены или есть ошибки!<button class="close" data-close="alert"></button></div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <a id="sample_editable_1_new"  data-gym="{{$gym->id}}" href="{{action('MatchController@index',$gym)}}" class="btn green"> Добавить
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @if($matches)
                <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                    <thead>
                        <tr>
                            <th> Лига </th>
                            <th> Хозяева </th>
                            <th> Сумма  </th>
                            <th> М/О </th>
                            <th> Гости </th>
                            <th> Сумма  </th>
                            <th> М/О </th>
                            <th> Судья </th>
                            <th> Редактировать </th>
                            <th> Удалить/Отменить </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matches as $match)
                        <tr>
                            <td>{{$match->league->name}}</td>
                            <td> {{$match->homeTeam->name}}</td>
                            <td> {{$match->home_fee}} </td>
                            <td> {{$match->homeMethod->name}} </td>
                            <td> {{$match->guestTeam->name}}</td>
                            <td> {{$match->guest_fee}} </td>
                            <td> {{$match->guestMethod->name}} </td>
                            <td> {{ $match->referee->lastname }} </td>
                            <td>
                                <a class="edit"  data-id="{{$match->id}}" href="javascript:;"> Редактировать </a>
                            </td>
                            <td>
                                <a class="delete" data-id="{{$match->id}}" href="javascript:;"> Удалить </a>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-red"></i>
                    <span class="caption-subject font-red sbold uppercase"> Расходы </span>
                </div>
            </div>
            <div id="exp-error" class="alert alert-danger display-hide" style="margin:1%;">Выделенные поля должны быть заполнены!<button class="close" data-close="alert"></button></div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <a id="sample_editable_2_new"  href="/gyms/{{$gym->id}}" class="btn green"> Добавить
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover table-bordered" id="sample_editable_2">
                    <thead>
                        <tr>
                            <th> Фото </th>
                            <th> М/О </th>
                            <th> Видео  </th>
                            <th> М/О </th>
                            <th> Нарезка </th>
                            <th> М/О </th>
                            <th> Аренда  </th>
                            <th> М/О </th>
                            <th> Врач </th>
                            <th> М/О </th>
                            <th> Куратор </th>
                            <th> М/О </th>
                            <th> Иное </th>
                            <th> М/О </th>
                            <th> Комментарий </th>
                            <th> Редактировать </th>
                            <th> Удалить/Отменить </th>

                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($dExp))
                        <tr>
                            <td> {{$dExp->photo_cost ? $dExp->photo_cost : ''}} </td>
                            <td> {{$dExp->hasPhoto ? $dExp->hasPhoto->name : ''}} </td>
                            <td> {{$dExp->video_cost ? $dExp->video_cost : ''}} </td>
                            <td> {{$dExp->hasVideo ? $dExp->hasVideo->name : ''}} </td>
                            <td> {{$dExp->edit_cost ? $dExp->edit_cost : ''}} </td>
                            <td> {{$dExp->videoEdit ? $dExp->videoEdit->name : ''}} </td>
                            <td> {{$dExp->rent_cost ? $dExp->rent_cost : ''}} </td>
                            <td> {{$dExp->hasRent->name}} </td>
                            <td> {{$dExp->doctor_cost ? $dExp->doctor_cost : ''}} </td>
                            <td> {{$dExp->hasDoctor ? $dExp->hasDoctor->name : ''}} </td>
                            <td> {{$dExp->curator_cost ? $dExp->curator_cost : ''}} </td>
                            <td> {{$dExp->hasCurator->name}} </td>
                            <td> {{$dExp->other ? $dExp->other : ''}} </td>
                            <td> {{$dExp->otherMethod ? $dExp->otherMethod->name : ''}} </td>
                            <td> {{$dExp->comment ? $dExp->comment : ''}} </td>
                            <td>
                                <a class="edit"  data-id="{{$dExp->id}}" href="javascript:;"> Редактировать </a>
                            </td>
                            <td>
                                <a class="delete" data-id="{{$dExp->id}}" href="javascript:;"> Удалить </a>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>

            </div>
        </div>
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-green"></i>
                    <span class="caption-subject font-green sbold uppercase">Итог</span>
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
            <div class="portlet-body">
                <div class="table-container">
                    <table class="table table-striped table-bordered table-hover" id="sample_3">
                        <thead>
                            <tr>
                                <th> Доход </th>
                                <th> Расход </th>
                                <th> Долг команд </th>
                                <th> Итог </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="incomes"> {{isset($incomes) ? $incomes : $gym->day_incomes}} </td>
                                <td id="expenses"> {{isset ($expenses) ? $expenses : $gym->day_expense}}  </td>
                                <td id="debt"> {{isset($debt) ? $debt : $gym->day_debt}}   </td>
                                <td id="total"> {{isset($total) ? $total : $gym->day_total}} </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>     
    </div>
</div>
@endsection
@section('table-js')
{!!Html::script('/assets/pages/scripts/matches-table.js')!!}
{!!Html::script('/assets/pages/scripts/total-table.js')!!}
{!!Html::script('/assets/pages/scripts/matches-initpickers.js')!!}
@endsection