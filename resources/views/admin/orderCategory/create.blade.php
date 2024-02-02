@extends('admin.layouts.app')

@section('content')
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle }} <small></small></h3>
        {{ Breadcrumbs::render('order-category.create') }}
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

                    <form method="POST" action="{{ route('order-category.store') }}" class="form-horizontal"
                          role="form" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="form-group">
                            <label for="name" class="col-md-2 control-label">Name *</label>
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


                        <div class="form-group">
                            <label for="order_count" class="col-md-2 control-label">Type *</label>
                            <div class="col-md-4">
                                <select  id="category_type" name="category_type" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                    <option  value="" disabled selected>Please select type</option>
                                    <option id="basic" value="basic">Basic</option>
                                    <option id="special" value="special">Special</option>
                                </select>
                            </div>
                            @if ($errors->has('category_type'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('category_type') }}</strong>
                                    </span>
                            @endif

                        </div>


                        <div class="form-group" id="count" style="display: none;">
                            <label class="col-md-2 control-label">Count</label>
                            <div class="col-md-4">
                                <input type="number" id="count-input" name="count" class="form-control" min="1" max="100" />
                            </div>
                        </div>


                        {{--<div id="count" style="display: none;">--}}
                            {{--<label for="count">Count</label> <input type="text" id="count" name="count" /><br />--}}
                        {{--</div>--}}



                        <div class="form-group">
                            <label for="score" class="col-md-2 control-label">Score *</label>
                            <div class="col-md-4">
                                <input type="text" name="score"  value="{{ old('score') }}"
                                       class="form-control"/>
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
    <script type="text/javascript" src="{{ asset('assets/admin/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/admin/scripts/core/app.js') }}"></script>
    <script>

        jQuery(document).ready(function () {
            // initiate layout and plugins
            App.init();
            Admin.init();
            $('#cancel').click(function () {
                window.location.href = "{{route('order-category.index') }}";
            });

            $('#category_type').change(function(){

                let selected_value = $(this).val();
                if(selected_value == 'special')
                {
                    $('#count-input').prop('required',true);
                    $('#count').show();
                }
                else
                {
                    $('#count-input').prop('required',false);
                    $('#count').hide();
                }

            });

        });

    </script>
@stop
