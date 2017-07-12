@extends('tables.editable')
@section('table-content')
<div class="portlet light portlet-fit bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-settings font-red"></i>
            <span class="caption-subject font-red sbold uppercase">Доходы</span>
        </div>
    </div>
     <div class="alert alert-danger display-hide" style="margin:1%;">Выделенные поля должны быть заполнены!<button class="close" data-close="alert"></button></div>
    <div class="portlet-body">
        <div class="table-toolbar">
            <div class="row">
                <div class="col-md-6">
                    <div class="btn-group">
                        <a id="sample_editable_1_new"  href="{{url('incomes')}}" class="btn green"> Добавить
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @if($incomes)
        <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
            <thead>
                <tr>
                    <th> Дата внесения </th>
                    <th> Тип </th>
                    <th> Сумма  </th>
                    <th> Описание </th>
                    <th> Метод оплаты </th>
                    <th> Редактировать </th>
                    <th> Удалить/Отменить </th>

                </tr>
            </thead>
            <tbody>
                @foreach($incomes as $income)
                <tr>
                    <td> {{$income->created_at}} </td>
                    <td> {{$income->incomeType->name}}</td>
                    <td> {{$income->income_sum}} </td>
                    <td> {{$income->comment}} </td>
                    <td> {{ $income->paymentMethod->name }} </td>
                    <td>
                        <a class="edit"  data-id="{{$income->id}}" href="javascript:;"> Редактировать </a>
                    </td>
                    <td>
                        <a class="delete" data-id="{{$income->id}}" href="javascript:;"> Удалить </a>
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
{!!Html::script('/assets/pages/scripts/incomes-table.js')!!}
{!!Html::script('/assets/pages/scripts/prepays-initpickers.js')!!}
@endsection