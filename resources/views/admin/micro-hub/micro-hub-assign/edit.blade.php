@extends('admin.layouts.app')
@section('css')
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/select2/select2-metronic.css') }}"/>
    <!-- END PAGE LEVEL STYLES -->
    <style>
/*        span.select2-selection.select2-selection--multiple {
            width: 522%;
        }*/
    </style>
@stop

@section('content')
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">{{ $pageTitle }} <small></small></h3>
        {{ Breadcrumbs::render('micro-hub-assign.edit', $userData) }}
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<link href="{{ asset('assets/admin/css/customPreview.css') }}" rel="stylesheet" type="text/css"/>
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">

        @include('admin.partials.errors')

        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box blue">

            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-edit"></i> {{ $pageTitle }}
                </div>
            </div>

            <div class="portlet-body">

                <h4>&nbsp;</h4>
                <form method="POST" action="{{ route('micro-hub-assign.update', $userData->id) }}" class="form-horizontal" role="form" enctype="multipart/form-data">

                        <input type="hidden" id="user_id" value="{{$userData->id}}">

                        <input type="hidden" id="city_id" value="{{isset($selectedCity) ? $selectedCity : ''}}">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label for="userType" class="col-md-2 control-label">Position *</label>
                        <div class="col-md-4">

                            <select class="js-example-basic-multiple form-control col-md-7 col-xs-12 user-type" name="userType" required>
                                <option value="city_incharge" {{(in_array('city_incharge', [$userData->userType])) ? 'Selected' : ''}}>City Incharge</option>
                                <option value="zone_incharge"{{(in_array('zone_incharge', [$userData->userType])) ? 'Selected' : ''}}>Zone Incharge</option>
                                <option value="incharge" {{(in_array('incharge', [$userData->userType])) ? 'Selected' : ''}}>Incharge</option>
                            </select>
                        </div>
                        @if ($errors->has('userType'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('userType') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="city" class="col-md-2 control-label">City *</label>
                        <div class="col-md-4">

                            <select class="form-control col-md-7 col-xs-12 micro-hub js-example-basic-multiple micro-hub-city" name="city" required>
                                <option value="" readonly="">Select City</option>
                                @foreach($cities as $city)
                                    <option value="{{$city->id}}">{{$city->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('city'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="form-group hubAssign">
                        <label for="hubPermission" class="col-md-2 control-label">Hubs *</label>
                        <div class="col-md-4">

                            <select class="form-control col-md-7 col-xs-12 city-micro-hub js-example-basic-multiple assignMicroHub" name="hubPermission[]" multiple required>
                                {{--@foreach($allHub as $hub)
                                    <option value="{{$hub->id}}">{{$hub->title}}</option>
                                @endforeach--}}
                            </select>
                        </div>
                        @if ($errors->has('hubPermission'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('hubPermission') }}</strong>
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
<script src="{{ asset('assets/admin/scripts/custom/customPreview.js') }}"></script>

<script>

    window.onload = function() {
        let city = $('#city_id').val();

        getDataOrderPostalCodeCreate(city);
    }

    function getDataOrderPostalCodeCreate(data) {

        //show loader
        showLoader();

        let id =  JSON.parse(data);

        let user_id =  $('#user_id').val();

        $.ajax({
            type: "get",
            url: "{{route('city-hub-assign.update')}}",
            data:{
                id:id,
                user_id:user_id
            },

            success: function (response) {
                var options_html = '';
                Object.keys(response).forEach(function (key) {
                    response[key].forEach(function(el){
                        options_html+='<option data-id="'+el.id+'" value="' + el.id + '" >' + el.title + '</option>';

                        var current_el = $('.city-micro-hub');
                        var current_selected_val = current_el.val();


                        current_el.find('option')
                            .remove()
                            .end()
                            .append(options_html)
                            .val(current_selected_val)
                    });

                    make_multi_option_selected('.assignMicroHub','{{$selectedPermission}}');



                })
            },
            error: function (error) {
                // hideLoader();
                //ShowSessionAlert('danger', 'Something wrong');
                // console.log(error);
            }
        });
    }

    jQuery(document).ready(function() {

        $('.user-type').on('change', function() {
            let user_type = $(this).val();

            if (user_type == 'city_incharge')
            {

                $('.hubAssign').hide();
                $('.city-micro-hub').prop('required',false);
                $('.micro-hub-city').prop('required',true);
            }
            else {
                $('.hubAssign').show();
                $('.city-micro-hub').prop('required',true);
                $('.micro-hub-city').prop('required',true);
            }
        });

        if ($('.user-type').val() == 'city_incharge')
        {
            $('.hubAssign').hide();
            $('.city-micro-hub').prop('required',false);
            $('.micro-hub-city').prop('required',true);
        }


        $('.micro-hub-city').on('change', function() {
            let city = $(this).val();

            getDataOrderPostalCodeCreate(city);
        });

// get data of postal code create and show on model
        function getDataOrderPostalCodeCreate(data) {

            //show loader
            showLoader();

            let id =  JSON.parse(data);

            let user_id =  $('#user_id').val();

            $.ajax({
                type: "get",
                url: "{{route('city-hub-assign.update')}}",
                data:{
                    id:id,
                    user_id:user_id
                },

                success: function (response) {
                    var options_html = '';
                    Object.keys(response).forEach(function (key) {
                        response[key].forEach(function(el){

                            options_html+='<option data-id="'+el.id+'" value="' + el.id + '" >' + el.title + '</option>';

                            var current_el = $('.city-micro-hub');
                            var current_selected_val = current_el.val();
                            current_el.find('option')
                                .remove()
                                .end()
                                .append(options_html)
                                .val(current_selected_val)
                        });

                        make_multi_option_selected('.assignMicroHub','{{$selectedPermission}}');



                })
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }

{{--console.log({{$selectedPermission}}, {{$selectedCity}});--}}
        make_multi_option_selected('.micro-hub','{{$selectedCity}}');
   // initiate layout and plugins
   App.init();
   Admin.init();
   $('#cancel').click(function() {
        window.location.href = "{{ route('micro-hub-assign.index') }}";
   });
});

</script>
@stop
