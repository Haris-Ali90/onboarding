@extends('admin.layouts.app')

@section('content')
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle }} <small></small></h3>
        {{ Breadcrumbs::render('faqs.create') }}
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

                    <form method="POST" action="{{ route('faqs.store') }}" class="form-horizontal"
                          role="form" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="form-group">
                            <label for="title" class="col-md-2 control-label">FAQ Title *</label>
                            <div class="col-md-4">
                                <input type="text" name="title" maxlength="150" value="{{ old('title') }}"
                                       class="form-control"/>
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>

                        </div>
                        <div class="form-group">
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

                        </div>


                        <div class="form-group" >
                            <label class="col-md-2 control-label">Select Vendor *</label>

                            <div class="vendorDD col-md-4">


                                <select id="vendor_id" name="vendor_id" class="form-control col-md-7 col-xs-12"
                                        required>
                                    <option value="" disabled selected>Please Select Option</option>
                                    @foreach($vendors as $v)
                                        <option value="{{$v->id}}">{{$v->name}}</option>
                                    @endforeach

                                </select>
                            </div>

                        </div>
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
                window.location.href = "{{route('faqs.index') }}";
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
