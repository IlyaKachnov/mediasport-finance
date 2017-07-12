@extends('forms.basic-layout')
@section('title')
{{'Редактировать:'}} {{$referee->lastname}}
@endsection
@section('form')
{!!Form::model($referee,['id'=>'form_sample_2','class'=>'form-horizontal','method'=>'patch','route'=>['referees.update',$referee]])!!}
{{ csrf_field() }}
@include('referees.form',['submitBtnText'=>'Обновить',               

])
{!!Form::close()!!}
@endsection
