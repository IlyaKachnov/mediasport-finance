@extends('layouts.master')
@section('header')
{!!Html::style('/assets/global/plugins/datatables/datatables.min.css')!!}
{!!Html::style('/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')!!}
@endsection
@section('content')

<div class="row">
    <div class="col-md-12"> 
        @yield('table-content')
    </div>
</div>
@endsection
@section('plugins')
{!!Html::script('/assets/global/scripts/datatable.js')!!}
{!!Html::script('/assets/global/plugins/datatables/datatables.min.js')!!}
{!!Html::script('/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')!!}
{!!Html::script('/assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js')!!}
@endsection
@section('scripts')
{!!Html::script('/assets/pages/scripts/table-datatables-managed.js')!!}
{!!Html::script('/assets/pages/custom-scripts/ajax-crud.js')!!}
@endsection