@extends('admin.layouts.app')

@section('content')
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">{{ $pageTitle}} <small></small></h3>
        {{ Breadcrumbs::render('joey-checklist.create') }}
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
                    <i class="fa fa-plus"></i> {{ $pageTitle}}
                </div>
            </div>

            <div class="portlet-body">

                <h4>&nbsp;</h4>
                <form method="POST" action="{{ route('joey-checklist.store') }}" class="form-horizontal" role="form">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label for="workType" class="col-md-2 control-label">Title *</label>
                        <div class="col-md-4">
                            <input  type="text"  id="title" maxlength="190" name="title" value="{{ old('title') }}" class="form-control" />
                        </div>
                        @if ($errors->has('title'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                        @endif
                    </div>


                    <div class="form-group">
                        <label for="order_count" class="col-md-2 control-label">Category *</label>
                        <div class="col-md-4">
                            <select id="category_id" name="category_id" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                <option  value="" disabled selected>Please select category</option>
                                @foreach($categories as $oc)
                                    <option value="{{$oc->id}}">{{$oc->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('category_id'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('category_id') }}</strong>
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
<script>
jQuery(document).ready(function() {

    // initiate layout and plugins
    App.init();
    Admin.init();
    $('#cancel').click(function() {
        window.location.href = "{!! URL::route('joey-checklist.index') !!}";
    });
});


</script>
<script type="text/javascript">
</script>
@stop
