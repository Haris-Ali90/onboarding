@extends('admin.layouts.app')

@section('content')
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">{{ $pageTitle ?? '' }} <small></small></h3>
        {{ Breadcrumbs::render('vendor-score.edit', $vendorScore) }}
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">

 {{--       @include('admin.partials.errors')--}}

        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box blue">

            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-edit"></i> {{ $pageTitle }}
                </div>
            </div>

            <div class="portlet-body">

                <h4>&nbsp;</h4>

                <form method="POST" action="{{ route('vendor-score.update', $vendorScore->id) }}" class="form-horizontal" role="form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="vendors_id" class="col-md-2 control-label">Vendors *</label>
                        <div class="col-md-4">
                            <select id="vendors_id" name="vendors_id" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                <option  value="">please select your vendor</option>
                                @foreach($vendor as $vc)

                                    <option  value="{{$vc->id}}"
                                             @if ($vc->id == $vendorScore->id)
                                             selected="selected"
                                        @endif
                                    >{{$vc->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="score" class="col-md-2 control-label">Score *</label>
                        <div class="col-md-4">
                            <input  type="text"  id="score" maxlength="190" name="score" value="{{ $vendorScore->score }}" class="form-control" />
                        </div>
                        @if ($errors->has('score'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('score') }}</strong>
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
<script src="{{ asset('assets/admin/scripts/core/app.js') }}"></script>
<script>
jQuery(document).ready(function() {
    // initiate layout and plugins
    App.init();
    Admin.init();
    $('#cancel').click(function() {
        window.location.href = "{!! URL::route('vendor-score.index') !!}";
    });

});


</script>
@stop
