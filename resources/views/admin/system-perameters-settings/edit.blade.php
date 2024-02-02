@extends('admin.layouts.app')

@section('content')
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">{{ $pageTitle ?? 'Edit Record' }} <small></small></h3>
        {{ Breadcrumbs::render('system-perameters-settings.edit', $system_parameter) }}

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
                    <i class="fa fa-edit"></i> {{ $pageTitle ?? '' }}
                </div>
            </div>

            <div class="portlet-body">

                <h4>&nbsp;</h4>

                <form method="POST" action="{{ route('system-parameters.update', $system_parameter->id) }}" class="form-horizontal" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{$system_parameter->id}}" />
                    <div class="form-group">
                        <label for="first_name" class="col-md-2 control-label">Name *</label>
                        <div class="col-md-4">
                            <input type="text" name="name"  value="{{ old('key', $system_parameter->name) }}" class="form-control" required />
                        </div>
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="last_name" class="col-md-2 control-label">Value *</label>
                        <div class="col-md-4">
                            <input type="text" name="value"  value="{{ old('value', $system_parameter->value) }}" class="form-control" />
                        </div>
                        @if ($errors->has('value'))
                            <span class="help-block">
                                <strong>{{ $errors->first('value') }}</strong>
                            </span>
                        @endif
                    </div>


                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            <input type="submit" class="btn orange" id="save" value="Save">
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
        window.location.href = "{!! URL::route('system-parameters.index') !!}";
   });
});

</script>
@stop
