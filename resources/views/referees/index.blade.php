@extends('tables.layout')
@section('table-content')
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption font-dark">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject bold uppercase"> Судьи</span>
        </div>
    </div>
    <div class="portlet-body">
        <div class="table-toolbar">
            <div class="row">
                <div class="col-md-6">
                    <div class="btn-group">
                        <a href="{{route('referees.create')}}" id="sample_editable_1_new" class="btn sbold green"> Добавить
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
<!--       <div class="col-md-6">
                    <div class="btn-group pull-right">
                        <button class="btn green btn-outline dropdown-toggle" data-toggle="dropdown">Действия
                            <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a href="javascript:;"> Печать </a>
                            </li>
                            <li>
                                <a href="javascript:;"> Сохранить в PDF </a>
                            </li>
                            <li>
                                <a href="javascript:;"> Экспорт в Excel </a>
                            </li>
                        </ul>
                    </div>
                </div>-->
            </div>
        </div>
        @if($referees)
        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
            <thead>
                <tr>
                    <th>
                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                            <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                            <span></span>
                        </label>
                    </th>
                    <th> Имя </th>
                    <th> Фамилия </th>
                    <th> Отчество </th>
                    <th> Действия </th>
                </tr>
            </thead>
            <tbody>

                @foreach($referees as $referee)
                <tr class="odd gradeX">
                    <td>
                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                            <input type="checkbox" class="checkboxes" value="1" />
                            <span></span>
                        </label>
                    </td>
                    <td> {{$referee->lastname}} </td>
                 
                    <td> {{$referee->firstname}} </td>
                    <td> {{$referee->middlename}} </td>
             
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Действия
                                <i class="fa fa-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-left" role="menu">
                                <li>
                                    <a href="{{route('referees.edit',$referee)}}">
                                        <i class="icon-pencil"></i> Редактировать </a>
                                </li>
                                <li>
                                    <a class="delete-item" data-href="{{route('referees.destroy',$referee)}}" data-title="Удалить элемент?" data-toggle="confirmation" data-placement="top" data-btn-ok-label="Да" data-btn-ok-icon="icon-like" data-btn-ok-class="btn-success" data-btn-cancel-label="Отмена" data-id="{{$referee->id}}" method="delete" data-token="{{csrf_token()}}">
                                        <i class="icon-trash"></i> Удалить </a>
                                </li>

                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
        @endif
    </div>
</div>
@endsection
