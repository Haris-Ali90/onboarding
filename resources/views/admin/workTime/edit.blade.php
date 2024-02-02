@extends('admin.layouts.app')

@section('content')
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">{{ $pageTitle ?? '' }} <small></small></h3>
        {{ Breadcrumbs::render('work-time.edit', $workTime ?? '') }}
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">

        {{--@include('admin.partials.errors')--}}

        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box blue">

            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-edit"></i> {{ $pageTitle ?? '' }}
                </div>
            </div>

            <div class="portlet-body">

                <h4>&nbsp;</h4>

                <form method="POST" action="{{ route('work-time.update', $workTime->id) }}" class="form-horizontal" role="form">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="start_time" class="col-md-2 control-label">Start Time *</label>
                        <div class="col-md-4">
                            <input type="time" id="start_time" maxlength="190" name="start_time" value="{{$workTime->start_time}}" class="form-control" />
                        </div>
                        @if ($errors->has('start_time'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('start_time') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="end_time" class="col-md-2 control-label">End Time *</label>
                        <div class="col-md-4">
                            <input type="time" id="end_time" maxlength="190" name="end_time" value="{{$workTime->end_time}}" class="form-control" />
                        </div>
                        @if ($errors->has('end_time'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('end_time') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            <input type="submit" class="btn blue" id="save" value="Save">
                            <input type="button" class="btn black" name="cancel" id="cancel" value="Cancel">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop

@section('footer-js')
{{--<script type="text/javascript" src="{!! URL::to('assets/admin/plugins/ckeditor/ckeditor.js') !!}"></script>--}}
<script src="{{ asset('assets/admin/scripts/core/app.js')}}"></script>
<script>
jQuery(document).ready(function() {
    // initiate layout and plugins
    App.init();
    Admin.init();
    $('#cancel').click(function() {
        window.location.href = "{!! URL::route('work-time.index') !!}";
    });

});


</script>
@stop
