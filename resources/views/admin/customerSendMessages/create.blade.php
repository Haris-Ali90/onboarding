@extends('admin.layouts.app')

@section('content')
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle }} <small></small></h3>
        {{ Breadcrumbs::render('customer-send-messages.create') }}
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

                    <form method="POST" action="{{ route('customer-send-messages.store') }}" class="form-horizontal"
                          role="form" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
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
            {{--            <div class="form-group">
                            <label for="description" class="col-md-2 control-label">FAQ Description *</label>
                            <div class="col-md-4">
                                <input type="text" name="description" maxlength="500" value="{{ old('description') }}"
                                       class="form-control"/>
                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>

                        </div>--}}



                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10" style="margin-top: 5px;">
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
                window.location.href = "{{route('customer-send-messages.index') }}";
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
