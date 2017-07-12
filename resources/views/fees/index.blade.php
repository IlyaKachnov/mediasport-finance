@extends('tables.editable')
@section('table-content')
<div class="portlet light portlet-fit bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-settings font-red"></i>
            <span class="caption-subject font-red sbold uppercase">Взносы</span>
        </div>
    </div>
       <div class="alert alert-danger display-hide" style="margin:1%;">Заполните все поля!<button class="close" data-close="alert"></button></div>
    <div class="portlet-body">
        <div class="table-toolbar">
            <div class="row">
                <div class="col-md-6">
                    <div class="btn-group">
                        <a id="sample_editable_1_new"  href="{{action('EntranceFeeController@index',$league)}}" class="btn green"> Добавить
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @if($fees)
        <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
            <thead>
                <tr>
                    <th> Команда </th>
                    <th> Сумма  </th>
                    <th> Метод оплаты </th>
                    <th> Процент </th>
                    <th> Редактировать </th>
                    <th> Удалить/Отменить </th>

                </tr>
            </thead>
            <tbody>
                @foreach($fees as $fee)
                <tr>
                    <td> {{$fee->team->name}}</td>
                    <td> {{$fee->paid_fee}} </td>
                    <td> {{$fee->method->name}} </td>
                    <td class="percents"> {{ $fee->fee_percent }}% </td>
                    <td>
                        <a class="edit"  data-id="{{$fee->id}}" href="javascript:;"> Редактировать </a>
                    </td>
                    <td>
                        <a class="delete" data-id="{{$fee->id}}" href="javascript:;"> Удалить </a>
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
{!!Html::script('/assets/pages/scripts/fees-table.js')!!}
{!!Html::script('/assets/pages/scripts/leagues-input-mask.js')!!}
@endsection