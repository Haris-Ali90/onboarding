@extends('admin.layouts.app')

@section('content')
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle }} <small></small></h3>
        {{ Breadcrumbs::render('documents.create') }}
        <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

{{--        @include('admin.partials.errors')--}}

        <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet box blue">

                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-plus"></i> {{ $pageTitle }}
                    </div>
                </div>

                <div class="portlet-body">

                    <h4>&nbsp;</h4>

                    <form method="POST" action="{{ route('documents.store') }}" class="form-horizontal"
                          role="form" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="form-group">
                            <label for="name" class="col-md-2 control-label">Document Name *</label>
                            <div class="col-md-4">
                                <input type="text" name="name" maxlength="150" value="{{ old('name') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>


                        <div class="form-group" id="file_type" >
                            <label for="order_count" class="col-md-2 control-label">Is Compulsory</label>
                            <div class="col-md-4">
                                <select  id="file_type" name="file_type" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                    <option  value="">Please select option</option>
                                    <option id="1" value="1">Yes</option>
                                    <option id="0" value="0" selected>No</option>
                                </select>
                            </div>
                            @if ($errors->has('file_type'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('file_type') }}</strong>
                                    </span>
                            @endif

                        </div>


                        <div class="form-group" id="document_type" >
                            <label class="col-md-2 control-label">Document Type</label>
                            <div class="col-md-4">
                                <select  id="document_type" name="document_type" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                    <option  value="">Please select option</option>
                                    <option id="file" value="file" selected >File</option>
                                    <option id="text" value="text">Text</option>
                                </select>
                            </div>
                        </div>
         {{--               @if ($errors->has('document_type'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('document_type') }}</strong>
                                    </span>
                        @endif--}}


                        <div class="form-group" id="is_expiry_date">
                            <label class="col-md-2 control-label">Is Expiry Date</label>
                            <div class="col-md-4">
                                <select  id="is_expiry_date" name="is_expiry_date" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                    <option  value="">Please select option</option>
                                    <option value="1">Yes</option>
                                    <option  value="0" selected >No</option>
                                </select>
                            </div>
                        </div>
                        @if ($errors->has('is_expiry_date'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('is_expiry_date') }}</strong>
                                    </span>
                        @endif


                        <div class="form-group" id="option" >
                            <label class="col-md-2 control-label">Upload option</label>
                            <div class="col-md-4">
                                <select  id="option" name="option" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                    <option  value="">Please select option</option>
                                    <option  value="1">Yes</option>
                                    <option  value="0" selected>No</option>
                                </select>
                            </div>
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
    <script src="{{ asset('assets/admin/scripts/core/app.js') }}"></script>
    <script>

        jQuery(document).ready(function () {
            // initiate layout and plugins
            App.init();
            Admin.init();
            $('#cancel').click(function () {
                window.location.href = "{{route('documents.index') }}";
            });

//            $('#category_type').change(function(){
//
//                let selected_value = $(this).val();
//                if(selected_value == 'special')
//                {
//                    $('#count-input').prop('required',true);
//                    $('#count').show();
//                }
//                else
//                {
//                    $('#count-input').prop('required',false);
//                    $('#count').hide();
//                }
//
//            });

        });

    </script>
@stop
