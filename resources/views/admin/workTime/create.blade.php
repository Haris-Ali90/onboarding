@extends('admin.layouts.app')

@section('content')
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">{{ $pageTitle ?? '' }} <small></small></h3>
        {{ Breadcrumbs::render('work-time.create') }}
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
                    <i class="fa fa-plus"></i> {{ $pageTitle ?? '' }}
                </div>
            </div>

            <div class="portlet-body">

                <h4>&nbsp;</h4>
                <form method="POST" action="{{ route('work-time.store') }}" class="form-horizontal" role="form">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label for="startTime" class="col-md-2 control-label ">Start Time *</label>
                        <div class="col-md-4">
                            <input type="time" id="startTime" maxlength="190" name="startTime"  value="{{ old('startTime') }}" class="form-control datetimepicker" />
                        </div>
                        @if ($errors->has('startTime'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('startTime') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="form-group" >
                        <label for="endTime" class="col-md-2 control-label">End Time *</label>
                        <div class="col-md-4" >

                            <input  type="time" id="endTime" maxlength="190" name="endTime"   value="{{ old('endTime') }}" class="form-control  datetimepicker  " />

                        </div>
                        @if ($errors->has('endTime'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('endTime') }}</strong>
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
<script type="text/javascript" src="{{ asset('assets/admin/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{!! URL::to('assets/admin/scripts/core/app.js') !!}"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" integrity="sha256-b5ZKCi55IX+24Jqn638cP/q3Nb2nlx+MH/vMMqrId6k=" crossorigin="anonymous" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" integrity="sha256-5YmaxAwMjIpMrVlK84Y/+NjCpKnFYa8bWWBbUHSBGfU=" crossorigin="anonymous"></script>
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
<script type="text/javascript">

</script>
@stop
