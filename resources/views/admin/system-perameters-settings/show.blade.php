@extends('admin.layouts.app')

@section('content')
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">{{ $pageTitle ?? 'Details' }} <small></small></h3>
        {{ Breadcrumbs::render('system-perameters-settings.show', $system_parameter) }}
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->

<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">

        @include('admin.partials.errors')

        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box blue">

            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-search"></i> {{ $pageTitle ?? 'Details' }}
                </div>
            </div>

            <div class="portlet-body">
                <div class="form-horizontal detail-box-wrap" role="form">
                    <div class="form-group detail-box-from-group">
                        <label class="col-md-2 control-label"><strong>Name:</strong> </label>
                        <div class="col-md-4 detail-box">
                            <label >{{ $system_parameter->name }}</label>
                        </div>
                        <label class="col-md-2 control-label"><strong>Value:</strong> </label>
                        <div class="col-md-4 detail-box">
                            <label >{{ $system_parameter->value }}</label>
                        </div>
                    </div>
                    <div class="form-group detail-box-from-group">
                        <label class="col-md-2 control-label"><strong>Created at:</strong> </label>
                        <div class="col-md-4 detail-box">
                            <label >{{ $system_parameter->created_at }}</label>
                        </div>
                    </div>
                    <div class="form-group detail-box-from-group">
                        <div class="col-md-offset-2 col-md-10">
                            <button class="btn orange" id="cancel" onclick="window.location.href = '{!! URL::route('system-parameters.index') !!}'"> < Back..</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop

@section('footer-js')
<script src="{!! URL::to('assets/admin/scripts/core/app.js') !!}"></script>
<script>
jQuery(document).ready(function() {
   // initiate layout and plugins
   App.init();
   Admin.init();
});
</script>
@stop
