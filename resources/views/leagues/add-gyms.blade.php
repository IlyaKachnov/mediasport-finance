@extends('tables.editable')
@section('table-content')
<div class="portlet light portlet-fit bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-settings font-red"></i>
            <span class="caption-subject font-red sbold uppercase">Места проведения: {{$league->name}}</span>
        </div>
    </div>
    <div class="portlet-body">
        <div class="table-toolbar">
            <div class="row">
                <div class="col-md-6">
                    <div class="btn-group">
                        <a id="sample_editable_1_new"  href="{{url('leagues')}}/{{$league->id}}" class="btn green"> Добавить
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
            <thead>
                <tr>
                    <th> Зал </th>
                    <th> Сумма взноса </th>
                    <th> Редактировать </th>
                    <th> Удалить/Отменить </th>
                </tr>
            </thead>
            <tbody>
                @foreach($league->gyms as $gym)
                <tr>
                    <td>{{$gym->name}}</td>
                    <td>{{$gym->pivot->rent_price}}</td>
                    <td>
                        <a class="edit"  data-id="{{$gym->id}}" href="javascript:;"> Редактировать </a>
                    </td>
                    <td>
                        <a class="delete" data-id="{{$gym->id}}" href="javascript:;"> Удалить </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('table-js')
{!!Html::script('/assets/pages/scripts/add-gyms.js')!!}
{!!Html::script('/assets/pages/scripts/add-gyms-input-mask.js')!!}
@endsection