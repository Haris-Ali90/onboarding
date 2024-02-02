@extends('admin.layouts.app')

@section('css')
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/select2/select2-metronic.css') }}"/>
    <!-- END PAGE LEVEL STYLES -->
    <style>
        .incident {
            padding: 10px 0px;
            margin: 10px 0px;
            border: 1px solid #e5e5e5;
            border-radius: 5px !important;
            position: relative;
            box-sizing: border-box;
        }

        .incident-input-box-inner .remove-incident {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
            color: red;
        }

        .form-group {
            position: relative;
        }
    </style>
@stop
@section('content')

    <!-- BEGIN PAGE HEADER-->
    @include('admin.partials.errors')

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle }}
                <small></small>
            </h3>
        {{ Breadcrumbs::render('flag-incident.create') }}
        <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box blue">

                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-list"></i> {{ $pageTitle }}
                    </div>
                </div>

                <div class="portlet-body">
                    <!--Form Open-->
                    <form method="POST" action="{{ route('flag-incident.store') }}" class="form-horizontal"
                          role="form" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="form-group">
                            <label for="name" class="col-md-2 control-label">Incident Name *</label>
                            <div class="col-md-4">
                                <input type="text" name="name" maxlength="150" value="{{ old('name') }}"
                                       class="form-control" required/>
                            </div>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Days Duration *</label>
                            <div class="col-md-4">
                                <input type="number" name="days_duration" class="form-control" min="1" max="365" required/>
                            </div>
                            @if ($errors->has('days_duration'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('days_duration') }}</strong>
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
                    <!--Form Close-->
                </div>
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

    <script>

        jQuery(document).ready(function () {
            // initiate layout and plugins
            App.init();
            Admin.init();
            $('#cancel').click(function () {
                window.location.href = "{{route('flag-incident.index') }}";
            });


        });

    </script>
@stop
