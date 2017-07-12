@extends('layouts.master')
@section('header')
{!!Html::style('/assets/global/plugins/select2/css/select2.min.css')!!}
{!!Html::style('/assets/global/plugins/select2/css/select2-bootstrap.min.css')!!}
{!!Html::style('/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')!!}
{!!Html::style('/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css')!!}
{!!Html::style('/assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css')!!}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-form bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark sbold uppercase">@yield('title')</span>
                </div>
            </div>
            <div class="portlet-body">
               @yield('form')
            </div>
        </div>
    </div>
</div>
@endsection
@section('plugins')
{!!Html::script('/assets/global/plugins/select2/js/select2.full.min.js')!!}
{!!Html::script('/assets/global/plugins/jquery-validation/js/jquery.validate.min.js')!!}
{!!Html::script('/assets/global/plugins/jquery-validation/js/additional-methods.min.js')!!}
{!!Html::script('/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')!!}
{!!Html::script('/assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js')!!}
{!!Html::script('/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js')!!}
{!!Html::script('/assets/global/plugins/ckeditor/ckeditor.js')!!}
{!!Html::script('/assets/global/plugins/bootstrap-markdown/lib/markdown.js')!!}
{!!Html::script('/assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js')!!}
@endsection
@section('scripts')
{!!Html::script('/assets/pages/scripts/form-validation.js')!!}
@endsection