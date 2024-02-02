@extends('admin.layouts.app')

@section('content')
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">{{ $pageTitle }} <small></small></h3>
        {{ Breadcrumbs::render('account-security.edit') }}
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->

<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">

    @include('admin.partials.success')
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box blue">

            <div class="portlet-title">
                <div class="caption"> <i class="fa fa-edit"></i> {{ $pageTitle }}</div>
            </div>

            <div class="portlet-body">

                {{--<form method="POST" action="{{ route('account-security.update') }}" class="form-horizontal" role="form">
                    @csrf
                    <div class="form-group">
                        <label for="is_email" class="col-md-3 control-label">Is Email *</label>
                        <div class="col-md-4">
                            <input type="checkbox" name="is_email" value="{{ old('is_email') }}" class="form-control" />
                        </div>
                        @if ($errors->has('is_email'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('is_email') }}</strong>
                                    </span>
                        @endif

                    </div>
                    <div class="form-group">
                        <label for="is_scan" class="col-md-3 control-label">Is Google Scan</label>
                        <div class="col-md-4">
                            <input type="checkbox" name="is_scan" value="{{ old('is_scan') }}"class="form-control" />
                        </div>
                        @if ($errors->has('is_scan'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('is_scan') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-3 col-md-9">
                            <input type="submit" class="btn blue" id="save" value="Save">
                            <input type="button" class="btn black" name="cancel" id="cancel" value="Cancel">
                        </div>
                    </div>
                </form>--}}


                <form method="POST" action="{{ route('account-security.update') }}" class="form-horizontal"
                      role="form" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label for="is_email" class="col-md-2 control-label">First Name *</label>
                        <div class="col-md-4">
                            <input  {{$user->is_email == 1 ? 'checked' : ''}} name="is_email"  type="checkbox"  required/>
                        </div>
                        @if ($errors->has('is_email'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('is_email') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="is_scan" class="col-md-2 control-label">Last Name *</label>
                        <div class="col-md-4">
                            <input {{$user->is_scan == 1 ? 'checked' : ''}} name="is_scan" type="checkbox" >
                        </div>
                        @if ($errors->has('is_scan'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('is_scan') }}</strong>
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
<script type="text/javascript" src="{!! URL::to('assets/admin/plugins/ckeditor/ckeditor.js') !!}"></script>
<script src="{!! URL::to('assets/admin/scripts/core/app.js') !!}"></script>
<script>
jQuery(document).ready(function() {
   // initiate layout and plugins
   App.init();
   Admin.init();
   $('#cancel').click(function() {
        window.location.href = "{!! URL::route('dashboard.index') !!}";
   });
});
</script>
@stop
