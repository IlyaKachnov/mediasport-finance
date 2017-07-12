 <div class="form-body">  
<div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button> Введенные данные содержат ошибки! </div>
    <div class="form-group">
        <label class="col-md-2 control-label">Фамилия
            <span class="required"> * </span>
        </label>
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-check"></i>
                </span>
                {!! Form::text('lastname', null,[ 'class'=>'form-control', 'data-required'=>'1']) !!} </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2">Имя
            <span class="required"> * </span>
        </label>
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-check"></i>
                </span>
                {!! Form::text('firstname', null,['class'=>'form-control', 'data-required'=>'1']) !!}
            </div>
        </div>
    </div>
        <div class="form-group">
        <label class="control-label col-md-2">Отчество
            <span class="required"> * </span>
        </label>
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-check"></i>
                </span>
                {!! Form::text('middlename',null,[ 'class'=>'form-control', 'data-required'=>'1']) !!}
            </div>
        </div>
    </div>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-2 col-md-9">
                {!! Form::submit($submitBtnText, ['class'=>'btn green']) !!}
                <a href="{{url('referees')}}" role="button" class="btn default">Отмена</a>
            </div>
        </div>
    </div>
 </div>