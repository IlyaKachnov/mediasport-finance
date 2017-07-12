<div class="form-body">
    @if (count($errors) > 0)
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
    <div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button> Введенные данные содержат ошибки! </div>
    <div class="form-group">
        <label class="col-md-2 control-label">Название
            <span class="required"> * </span>
        </label>
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-check"></i>
                </span>
                {!! Form::text('name', null,[ 'class'=>'form-control', 'data-required'=>'1']) !!} </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2">Адрес
            <span class="required"> * </span>
        </label>
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-check"></i>
                </span>
                {!! Form::text('address', null,['class'=>'form-control', 'data-required'=>'1']) !!}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2">Аренда
            <span class="required"> * </span>
        </label>
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-check"></i>
                </span>
                {!! Form::text('rent', null,['class'=>'form-control', 'data-required'=>'1']) !!}
            </div>
        </div>
    </div>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-2 col-md-9">
                {!! Form::submit($submitBtnText, ['class'=>'btn green']) !!}
                <a href="{{url('gyms')}}" role="button" class="btn default">Отмена</a>
            </div>
        </div>
    </div>

</div>