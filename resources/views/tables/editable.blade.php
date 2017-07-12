@extends('layouts.master')
@section('header')
{!!Html::style('/assets/global/plugins/datatables/datatables.min.css')!!}
{!!Html::style('/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')!!}
{!!Html::style('/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')!!}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        @yield('table-content')
    </div>
</div>
@endsection
@section('plugins')
{!!Html::script('/assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js')!!}
{!!Html::script('/assets/global/scripts/datatable.js')!!}
{!!Html::script('/assets/global/plugins/datatables/datatables.min.js')!!}
{!!Html::script('/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')!!}
{!!Html::script('/assets/global/scripts/datatable.js')!!}
{!!Html::script('/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')!!}
@endsection
@section('scripts')
@yield('table-js')
@endsection
