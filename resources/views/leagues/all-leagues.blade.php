@extends('tables.editable')
@section('table-content')
    <div class="portlet light portlet-fit bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-settings font-red"></i>
                <span class="caption-subject font-red sbold uppercase">Выберите лигу</span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-toolbar">

            </div>
            @if($leagues)
                <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                    <thead>
                    <tr>
                        <th> Лига </th>
                        <th> Перейти к взносам </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($leagues as $league)
                        <tr>
                            <td> {{$league->name}} </td>
                            <td>
                                <a href="{{'/leagues/'.$league->id.'/fees'}}"> Перейти </a>
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
    {!!Html::script('/assets/pages/scripts/all-table.js')!!}
@endsection