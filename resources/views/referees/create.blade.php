@extends('forms.basic-layout')
@section('title')
{{'Добавить'}}
@endsection
@section('form')
{!!Form::open (['id'=>'form_sample_2','class'=>'form-horizontal','method'=>'post','route'=>'referees.store'])!!}
{{ csrf_field() }}
@include('referees.form',['submitBtnText'=>'Создать',               
])
{!!Form::close()!!}

@endsection