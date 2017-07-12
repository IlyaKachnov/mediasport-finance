@extends('tables.editable')
@section('table-content')
<div class="portlet light portlet-fit bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-settings font-red"></i>
            <span class="caption-subject font-red sbold uppercase">Лиги</span>
        </div>
    </div>
    <div id="error-empty" class="alert alert-danger display-hide" style="margin:1%;">Заполните все поля!<button class="close" data-close="alert"></button></div>
    <div id="error-unique" class="alert alert-danger display-hide" style="margin:1%;">Лига уже существует!<button class="close" data-close="alert"></button></div>
    <div class="portlet-body">
        <div class="table-toolbar">
            <div class="row">
                <div class="col-md-6">
                    <div class="btn-group">
                        <a id="sample_editable_1_new"  href="{{url('leagues')}}" class="btn green"> Добавить
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @if($leagues)
        <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
            <thead>
                <tr>
                    <th> Название </th>
                    <th> Сумма взноса </th>
                    <th> Судья </th>
                    <th> Команды </th>
                    <th> Редактировать </th>
                    <th> Удалить </th>
                </tr>
            </thead>
            <tbody>
                @foreach($leagues as $league)
                <tr>
                    <td> {{$league->name}}</td>
                    <td> {{$league->total_fees}} </td>
                    <td> {{$league->referee_cost}} </td>
                    <td><a href="/leagues/{{$league->id}}/teams"> Перейти </a></td>
                    <td>
                        <a class="edit"  data-id="{{$league->id}}" href="javascript:;"> Редактировать </a>
                    </td>
                    <td>
                        <a class="delete" data-id="{{$league->id}}" href="javascript:;"> Удалить </a>
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
{!!Html::script('/assets/pages/scripts/leagues-table.js')!!}
{!!Html::script('/assets/pages/scripts/leagues-input-mask.js')!!}
@endsection