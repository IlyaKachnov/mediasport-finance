@extends('forms.basic-layout')
@section('title')
{{'Редактировать:'}} {{$gym->name}}
@endsection
@section('form')
{!!Form::model($gym,['id'=>'form_sample_1','class'=>'form-horizontal','method'=>'patch','route'=>['gyms.update',$gym]])!!}
{{ csrf_field() }}
@include('gyms.form',['submitBtnText'=>'Обновить',              

])
{!!Form::close()!!}
@endsection
