@extends('forms.basic-layout')
@section('title')
{{'Добавить'}}
@endsection
@section('form')
{!!Form::open (['id'=>'form_sample_1','class'=>'form-horizontal','method'=>'post','route'=>'gyms.store'])!!}
{{ csrf_field() }}
@include('gyms.form',['submitBtnText'=>'Создать',               
])
{!!Form::close()!!}

@endsection