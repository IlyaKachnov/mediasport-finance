@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><i class="fa fa-file-text-o"></i>Добавить проект</h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <div class="panel-body">
                {!!Form::open (['class'=>'form-horizontal','method'=>'post','action'=>['MatchController@store',$league]])!!}
                {{ csrf_field() }}
                <div class="form-group">
                    <div class="col-sm-12">
                        {!! Form::text('home_fee', null,['id'=>'name', 'class'=>'form-control', 'placeholder'=>'home fee',]) !!}
                    </div>

                </div>
                <div class="form-group">
                    {!! Form::label('homeez',null,['class'=>'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('home_list', $teams, ['class'=>'form-control',]) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        {!! Form::text('guest_fee', null,['id'=>'name', 'class'=>'form-control', 'placeholder'=>'guest fee',]) !!}
                    </div>

                </div>
                <div class="form-group">
                    {!! Form::label('guests',null,['class'=>'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('guest_list', $teams, ['class'=>'form-control',]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('referees',null,['class'=>'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('referee_list', $referees, ['class'=>'form-control',]) !!}
                    </div>
                </div>
                     <div class="form-group">
                    {!! Form::label('referee_methods',null,['class'=>'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('referee_method_list', $paymentMethods, ['class'=>'form-control',]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('gyms',null,['class'=>'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('gym_list', $gyms, ['class'=>'form-control',]) !!}
                    </div>
                </div>
                <!--payment methods-->
                <div class="form-group">
                    {!! Form::label('home_method',null,['class'=>'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('home_method_list', $paymentMethods, ['class'=>'form-control',]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('guest_method',null,['class'=>'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('guest_method_list', $paymentMethods, ['class'=>'form-control',]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('has_photo',null,['class'=>'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('has_photo_list', $paymentMethods, ['class'=>'form-control',]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('has_viedo',null,['class'=>'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('has_video_list', $paymentMethods, ['class'=>'form-control',]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('has_doctor',null,['class'=>'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('has_doctor_list', $paymentMethods, ['class'=>'form-control',]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('videp_edit',null,['class'=>'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('video_edit_list', $paymentMethods, ['class'=>'form-control',]) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        {!! Form::text('other', null,['id'=>'name', 'class'=>'form-control', 'placeholder'=>'other']) !!}
                    </div>

                </div>
                <div class="form-group">
                    {!! Form::label('other_methods',null,['class'=>'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('other_method_list', $paymentMethods, ['class'=>'form-control',]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('Дата',null,['class'=>'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        {!! Form::date('match_date' ,null,['class'=>'form-control' ]) !!}
                    </div>
                </div>

                <div class="form-group clearfix">
                    {!! Form::label('Commnet',null,['class'=>'control-label text-left col-sm-12', 'style'=>"text-align:left;margin-bottom:15px"]) !!}
                    <div class="col-sm-12">
                        {!! Form::textarea('comment', null, ['class'=>'form-control']) !!}
                    </div>
                </div>
                <div class="submit">
                    {!! Form::submit('Done', ['class'=>'btn btn-lg btn-primary']) !!}
                </div>

                {!!Form::close()!!}

        </section>

    </div>
</div>


@endsection
