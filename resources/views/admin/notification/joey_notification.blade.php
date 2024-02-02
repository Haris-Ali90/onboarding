@extends('admin.layouts.app')

@section('css')
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/select2/select2-metronic.css') }}"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    {{--    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/data-tables/DT_bootstrap.css') }}"/>--}}

    <!-- END PAGE LEVEL STYLES -->
@stop
@section('content')
    <!-- BEGIN PAGE HEADER-->
    @include('admin.partials.errors')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle ?? '' }} <small></small></h3>
        {{ Breadcrumbs::render('notification.index') }}
        <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">




            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet-body">

                <h4>&nbsp;</h4>
                <form method="POST" action="{{ route('notification.send') }}" class="form-horizontal" role="form">
                    @csrf
                    @method('POST')

                    <div class="form-group" id="subjectClass">
                        <label for="subject" class="col-md-2 control-label">Joeys*</label>
                        <div class="col-md-4">
                        <select data-placeholder="Please Select Option" id="joey_ids" name="joey_ids[]"
                                class="form-control js-example-basic-multiple" multiple required>
                            {{--<option value="" disabled>Please Select Option</option>--}}
                            @foreach($joeys as $joey)
                                <option value="{{$joey->id}}">{{$joey->first_name.' '.$joey->last_name .' ('. $joey->id.')'}}</option>
                            @endforeach

                        </select>
                        </div>
                    </div>

                    <div class="form-group" id="subjectClass">
                        <label for="subject" class="col-md-2 control-label">Subject*</label>
                        <div class="col-md-4">
                            <input type="text" id="subject" name="subject" class="form-control" required="required" maxlength = "50" placeholder="Subject (maximum 50 character)" />
                        </div>
                        @if ($errors->has('subject'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('subject') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-md-2 control-label">Message Here*</label>
                        <div class="col-md-4">
                            <textarea id="message" name="message" class="form-control" rows="6" cols="50" required="required" maxlength = "200" placeholder="Message (maximum 200 character)"></textarea>

                        </div>
                        @if ($errors->has('message'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('message') }}</strong>
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
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
@stop

@section('footer-js')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script type="text/javascript" src="{{ asset('assets/admin/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{ asset('assets/admin/scripts/core/app.js') }}"></script>
    <script src="{{ asset('assets/admin/scripts/custom/user-administrators.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>

        jQuery(document).ready(function () {
            // initiate layout and plugins
            App.init();
            Admin.init();
            $('#cancel').click(function () {
                window.location.href = "{{route('dashboard.index') }}";
            });
        });


        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
    </script>
@stop
