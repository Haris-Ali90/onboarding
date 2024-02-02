@extends('admin.layouts.app')
@section('css')

    <link rel="stylesheet" type="text/css" href="{!! URL::to('assets/admin/plugins/select2/select2.css') !!}"/>
    <link rel="stylesheet" type="text/css" href="{!! URL::to('assets/admin/plugins/select2/select2-metronic.css') !!}"/>
    <style>
        .radio input[type=radio], .radio-inline input[type=radio], .checkbox input[type=checkbox], .checkbox-inline input[type=checkbox] {
            float: left;
            margin: 0px 0px 0px 0px !important;
        }
        .custom_divider {
            display: grid;
            grid-gap: 2%;
            grid-template-columns: 49% 49%;
            grid-row-gap: 7px;
            align-items: center;
        }
        label.control-label {
            display: flex;
            order: 2;
        }
        .checker {
            display: flex;
            order: 1;
        }
        .custom_list {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: center;
            justify-content: flex-start;
        }
        .checked {
            display: flex;
            order: 1;
        }
        .request_message {
            display: flex;
            order: 3;
            margin-left: 6px;
            font-weight: 900;
        }
        .custom_list label {
            padding: 0 !important;
        }
        .radio_butn {
            float: left;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }
        .radio_butn label.control-label {
            margin-right: 10px;
        }
        .radio_butn label.control-label .radio {
            padding: 0px;
        }
        label.col-md-2.control-label {
            margin: 0px 0px 0px 119px;
        }
    </style>
@stop()
@section('content')
    <div id="map"></div>
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle }}
                <small></small>
            </h3>
        {{ Breadcrumbs::render('micro-hub.profile-status.edit', $hub_user) }}
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
                    <form method="POST" action="{{ route('micro-hub.profile-status.update', $hub_user) }}"
                          class="form-horizontal" role="form" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        {{  Form::hidden('url',\Illuminate\Support\Facades\URL::previous()) }}
                        <input type="hidden" name="user_id" value="{{$hub_user}}"/>
                        <div class="form-group">
                            <label for="full_name" class="col-md-2 control-label">Name *</label>
                            <div class="col-md-4">
                                <input type="text" name="full_name" id="fullName" maxlength="150"
                                       value="{{ old('full_name', $hubUsers->full_name) }}"
                                       class="form-control" required/>
                            </div>
                            @if ($errors->has('full_name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('full_name') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="email_address" class="col-md-2 control-label">Email Address</label>
                            <div class="col-md-4">
                                <input type="text" name="email_address" maxlength="32"
                                       value="{{ old('email_address', $hubUsers->email_address) }}"
                                       class="form-control" readonly/>
                            </div>
                            @if ($errors->has('email_address'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email_address') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="phone_no" class="col-md-2 control-label">Business Phone No *</label>
                            <div class="col-md-4">
                                <input type="number" min="11" name="phone_no" id="phoneNo" maxlength="32"
                                       value="{{ old('phone_no', $hubUsers->phone_no) }}"
                                       class="form-control" required/>
                            </div>
                            @if ($errors->has('phone_no'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('phone_no') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">

                            <label for="address" class="col-md-2 control-label"> Business Address *</label>
                            <div class="col-md-4">
                                <input type="text" maxlength="32" name="address"
                                       value="{{ old('address', $hubUsers->address) }}"
                                       class="form-control update-address-on-change google-address" required/>
                                <input type="hidden" name="address_latitude" class="form-control address-latitude" value="{{isset($hub_select_data->hub_latitude) ? $hub_select_data->hub_latitude : ''}}"/>
                                <input type="hidden" name="address_longitude" class="form-control address-longitude" value="{{isset($hub_select_data->hub_longitude) ? $hub_select_data->hub_longitude : ''}}"/>
                                <input type="hidden" name="address_city" class="form-control address-city" value="{{ old('city', $hubUsers->city) }}"/>
                                <input type="hidden" name="address_state" class="form-control address-state" value="{{ old('state', $hubUsers->state) }}"/>
                                <input type="hidden" name="address_postal_code" class="form-control address-postal_code" value="{{isset($hub_select_data->postal__code) ? $hub_select_data->postal__code : ''}}"/>
                                <input type="hidden" name="address_street" class="form-control address-street" value="{{ old('street', $hubUsers->street) }}"/>
                            </div>
                            @if ($errors->has('address'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="personal_phone_no" class="col-md-2 control-label">Personal Phone No *</label>
                            <div class="col-md-4">
                                <input type="number" min="11" name="personal_phone_no" id="personalPhoneNo" maxlength="32"
                                       value="{{ old('personal_phone_no', $hubUsers->user_phone) }}"
                                       class="form-control" required/>
                            </div>
                            @if ($errors->has('personal_phone_no'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('personal_phone_no') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">

                            <label for="personal_address" class="col-md-2 control-label"> Personal Address *</label>
                            <div class="col-md-4">
                                <input type="text" maxlength="32" name="personal_address" value="{{ old('personal_address', $hubUsers->user_address) }}"
                                       class="form-control update-personal-address-on-change google-personal-address" required/>
                                <input type="hidden" name="personal_address_latitude" class="form-control personal_address-latitude" value=""/>
                                <input type="hidden" name="personal_address_longitude" class="form-control personal_address-longitude" value=""/>
                                <input type="hidden" name="personal_address_city" class="form-control personal_address-city" value="{{ old('city', $hubUsers->city) }}"/>
                                <input type="hidden" name="personal_address_state" class="form-control personal_address-state" value="{{ old('state', $hubUsers->state) }}"/>
                                <input type="hidden" name="personal_address_postal_code" class="form-control personal_address-postal_code" value=""/>
                                <input type="hidden" name="personal_address_street" class="form-control personal_address-street" value="{{ old('street', $hubUsers->street) }}"/>
                            </div>
                            @if ($errors->has('personal_address'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('personal_address') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="area_radius" class="col-md-2 control-label">Area Sq.ft</label>
                            <div class="col-md-4">
                                <input type="number" name="area_radius" id="areaRadius" maxlength="32"
                                       value="{{ old('area_radius', $hubUsers->area_radius) }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('area_radius'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('area_radius') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="own_joeys" class="col-md-2 control-label">Own Joeys *</label>
                            <div class="col-md-4 radio_butn">
                                <label class="control-label ">
                                    <input
                                            type="radio"
                                            class="form-control own-joey"
                                            name="own_joeys"
                                            value="0"
                                            @if($hubUsers->own_joeys == 0)
                                            checked
                                            @endif
                                    >

                                    </input>No</label>
                                <label class="control-label own-joey">
                                    <input
                                            type="radio"
                                            class="form-control own-joey"
                                            name="own_joeys"
                                            value="1"
                                            @if($hubUsers->own_joeys == 1)
                                            checked
                                            @endif
                                    />

                                    </input>Yes</label>
                                {{-- <input type="number" name="own_joeys" id="ownJoeys" maxlength="4"
                                        value="{{ old('own_joeys', $hubUsers->own_joeys) }}"
                                        class="form-control"/>--}}
                            </div>
                            @if ($errors->has('own_joeys'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('own_joeys') }}</strong>
                                    </span>
                            @endif
                        </div>
                        {{--<div class="form-group">
                            <label for="is_consolidated" class="col-md-2 control-label">Is Consolidated *</label>
                            <div class="col-md-4 radio_butn">
                                <label class="control-label ">
                                    <input
                                            type="radio"
                                            class="form-control is-consolidated"
                                            name="is_consolidated"
                                            value="0"
                                            @if($hubUsers->is_consolidated == 0)
                                            checked
                                            @endif
                                    >
                                    </input>No</label>
                                <label class="control-label own-joey">
                                    <input
                                            type="radio"
                                            class="form-control own-joey"
                                            name="is_consolidated"
                                            value="1"
                                            @if(isset($user_data->getHubCode()->is_consolidated))
                                            @if($user_data->getHubCode()->is_consolidated == 1)
                                            checked
                                            @endif
                                            @endif
                                    />

                                    </input>Yes</label>
                            </div>
                            @if ($errors->has('is_consolidated'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('is_consolidated') }}</strong>
                                    </span>
                            @endif
                        </div>--}}
                        <div class="form-group">
                            <label for="is_active" class="col-md-2 control-label">Is Active *</label>
                            <div class="col-md-4 radio_butn ">
                                <label class="control-label ">
                                    <input
                                            type="radio"
                                            class="form-control is-active"
                                            name="is_active"
                                            value="0"
                                            @if(isset($user_data->status ))
                                            @if($user_data->status == 0)
                                            checked
                                            @endif
                                            @endif
                                    >
                                    </input>No</label>
                                <label class="control-label own-joey">
                                    <input
                                            type="radio"
                                            class="form-control own-joey"
                                            name="is_active"
                                            value="1"
                                            @if(isset($user_data->status ))
                                            @if($user_data->status == 1)
                                            checked
                                            @endif
                                            @endif
                                    />

                                    </input>Yes</label>
                            </div>
                            @if ($errors->has('is_active'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('is_active') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="average_capacity" class="col-md-2 control-label">Average Capacity *</label>
                            <div class="col-md-4">
                                <input type="number" name="average_capacity" id="averagecapacity" minlength="1" min="1"
                                       value="{{ old('average_capacity', $hubUsers->average_capacity) }}"
                                       class="form-control" required/>
                            </div>
                            @if ($errors->has('average_capacity'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('average_capacity') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="minimum_capacity" class="col-md-2 control-label">Minimum Capacity</label>
                            <div class="col-md-4">
                                <input type="number" name="minimum_capacity" id="minimumCapacity"
                                       value="{{ old('minimum_capacity', $hubUsers->minimum_capacity) }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('minimum_capacity'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('minimum_capacity') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="maximum_capacity" class="col-md-2 control-label">Maximum Capacity *</label>
                            <div class="col-md-4">
                                <input type="number" name="maximum_capacity" id="maximumCapacity" maxlength="4"
                                       value="{{ old('maximum_capacity', $hubUsers->maximum_capacity) }}"
                                       class="form-control" required/>
                            </div>
                            @if ($errors->has('maximum_capacity'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('maximum_capacity') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group joey-control">
                            <label for="joey_assign" class="col-md-2 control-label">Assign Joeys *</label>
                            <div class="col-md-4">
                                <select class="form-control joeys-list" name="joey[]" multiple required>
                                    <option value="">Select Joeys</option>
                                    @foreach($joeys as $joey)
                                        <option value="{{$joey->id}}" {{in_array($joey->id,$assignedJoey) ?'selected': '' }}>
                                            {{$joey->first_name.' '.$joey->last_name}}{{$joey->id}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('joey_assign'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('joey_assign') }}</strong>
                                    </span>
                            @endif
                        </div>
                        {{--<div class="form-group">
                            <label for="hub_id" class="col-md-2 control-label">Parent Hub *</label>
                            <div class="col-md-4">
                                <select class="form-control col-md-7 col-xs-12 hub-id" name="hub_id" required>
                                    <option value="">Select an Hub</option>
                                    @foreach($hub_data as $hub)
                                        <option value="{{$hub->id}}">{{$hub->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('hub_id'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('hub_id') }}</strong>
                                    </span>
                            @endif
                        </div>--}}
                        {{--<div class="form-group">
                            <label for="title" class="col-md-2 control-label">Hub Title *</label>
                            <div class="col-md-4">
                                <input type="text" name="title" id="title" maxlength="32"
                                       value="{{!empty($hub_select_data) ? $hub_select_data->title : ''}}"
                                       class="form-control" required/>
                            </div>
                            @if ($errors->has('title'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                            @endif
                        </div>--}}
                        <div class="form-group">
                            <label for="job_assign" class="col-md-2 control-label">Assign Jobs</label>
                            <div class="col-md-4">
                                <select class="form-control mi-list" name="miList[]" multiple>
                                    @foreach($miJobs as $jobs)
                                        <option value="{{$jobs->id}}" {{in_array($jobs->id,$assignedJobs) ?'selected': '' }}>
                                            {{$jobs->title}}{{$jobs->id}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('job_assign'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('job_assign') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="hub_id" class="col-md-2 control-label">Delivery Process Type *</label>
                            <div class="col-md-4 custom_divider">
                                @foreach($deliveryProcessType as $deliveryProcess)
                                    <div class="custom_list">
                                        <label class="control-label">{{$deliveryProcess->process_title}}</label>
                                        <input type="checkbox"
                                               class="form-control "
                                               name="delivery_process_type[]"
                                               value="{{$deliveryProcess->id}}"
                                               @if(!empty($hub_process))
                                               @if(in_array($deliveryProcess->id,$hub_process))
                                               checked
                                                @endif
                                                @endif/>
                                        @if(in_array($deliveryProcess->process_label,$hub_process_in_active))

                                            <div class="request_message">
                                                Requested
                                            </div>
                                        @endif
                                    </div>

                                @endforeach
                                {{--<label class="control-label">Routing</label>
                                <input type="checkbox" class="form-control " name="delivery_process_type[]" value="{{ 2, isset($hub_process) ? 'checked' : '') }}"/>
                                <label class="control-label">Mid Mile</label>
                                <input type="checkbox" class="form-control " name="delivery_process_type[]" value="1"/>
                                <label class="control-label">Last Mile</label>
                                <input type="checkbox" class="form-control " name="delivery_process_type[]" value="3"/>--}}

                            </div>
                            @if ($errors->has('hub_id'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('hub_id') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10">
                                <button type="submit" class="btn blue datatable-input-update-btn" id="save">Save
                                </button>
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
    <script type="text/javascript" src="{!! URL::to('assets/admin/plugins/select2/select2.min.js') !!}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/admin/scripts/core/app.js') }}"></script>
    <script src="{{ asset('assets/admin/scripts/custom/customPreview.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTK4viphUKcrJBSuoidDqRhVA4AWnHOo0&libraries=places"
            type="text/javascript"></script>

    <script>

        jQuery(document).ready(function () {
            // initiate layout and plugins
            App.init();
            Admin.init();

            $('input[name="own_joeys"]').click(function(){
                var inputValue = $(this).attr("value");

                if(inputValue == 0)
                {
                    var assign_joeys = $('.joeys-list');
                    assign_joeys.prop('required', false);
                    $(".joey-control").hide();

                }
                else if(inputValue == 1)
                {
                    $(".joey-control").show();
                    $('.joeys-list').prop('required', true);
                }
            });

            var inputValue = $('input[name=own_joeys]:checked').val();

            if(inputValue == 0)
            {
                $(".joey-control").hide();
                $('.joeys-list').prop('required', false);
            }

            $('#cancel').click(function () {
                window.location.href = "{{ route('micro-hub.users.index') }}";
            });


            // ajax function to show google map address suggestion for business address
            $(document).on('click', '.update-address-on-change', function () {

                let triggerAjax = true;

                // var acInputs = document.getElementsByClassName("google-address");
                var acInputs = this;
                var element = $(this);

                // remove error class if exist
                element.removeClass('input-error');

                const map = new google.maps.Map(document.getElementById("map"), {
                    center: {lat: 40.749933, lng: -73.98633},
                    zoom: 13,
                });

                const options = {
                    componentRestrictions: {country: "ca"},
                    fields: ["formatted_address", "geometry", "name", "address_components"],
                    origin: map.getCenter(),
                    strictBounds: false,
                    //types: ["establishment"],
                };
                var autocomplete = new google.maps.places.Autocomplete(acInputs, options);

                var address_sorted_object = {};
                google.maps.event.addListener(autocomplete, 'place_changed', function () {


                    var place = autocomplete.getPlace();
                    var address_components = place.address_components;

                    address_components.forEach(function (currentValue) {
                        address_sorted_object[currentValue.types[0]] = currentValue;
                    });

                    //var last_element = hh[hh.length - 1];
                    // add lat lng
                    //$(element).attr('data-lat', place.geometry.location.lat());
                    //$(element).attr('data-lng', place.geometry.location.lng());

                    let data_latitude = place.geometry.location.lat();
                    let data_longitude = place.geometry.location.lng();
                    $('.address-latitude').val(data_latitude);
                    $('.address-longitude').val(data_longitude);
                    // checking data is completed
                    if (!("postal_code" in address_sorted_object)) {
                        // show session alert
                        ShowSessionAlert('danger', 'Your selected address does not contain a Postal Code. Kindly select a nearby address! ');
                        element.val(element.attr('data-old-val'));
                        element.siblings(".datatable-input-update-btn").hide();
                        element.addClass('input-error');
                        console.log(address_sorted_object);
                        return;
                    }
                    else if (!("locality" in address_sorted_object)) {
                        // show session alert
                        ShowSessionAlert('danger', 'Your Selected address does not contain city kindly select near by address !');
                        element.val(element.attr('data-old-val'));
                        element.siblings(".datatable-input-update-btn").hide();
                        element.addClass('input-error');
                        console.log(address_sorted_object);
                        return;
                    }

                    //element.attr('data-postal-code', address_sorted_object.postal_code.long_name);
                    //element.attr('data-city', address_sorted_object.locality.long_name);
                    let data_city = address_sorted_object.locality.long_name;
                    let data_postal_code = address_sorted_object.postal_code.long_name;
                    let date_state = address_sorted_object.administrative_area_level_1.long_name;
                    let date_street = address_sorted_object.street_number.long_name;
                    $('.address-city').val(data_city);
                    $('.address-state').val(date_state);
                    $('.address-postal_code').val(data_postal_code);
                    $('.address-street').val(date_street);
                    console.log(data_latitude,data_longitude,data_city,data_postal_code,date_state,date_street);
                    return false;


                });

            });


            // ajax function to show google map address suggestion for personal address
            $(document).on('click', '.update-personal-address-on-change', function () {

                let triggerAjax = true;

                // var acInputs = document.getElementsByClassName("google-address");
                var acInputs = this;
                var element = $(this);

                // remove error class if exist
                element.removeClass('input-error');

                const map = new google.maps.Map(document.getElementById("map"), {
                    center: {lat: 40.749933, lng: -73.98633},
                    zoom: 13,
                });

                const options = {
                    componentRestrictions: {country: "ca"},
                    fields: ["formatted_address", "geometry", "name", "address_components"],
                    origin: map.getCenter(),
                    strictBounds: false,
                    //types: ["establishment"],
                };
                var autocomplete = new google.maps.places.Autocomplete(acInputs, options);

                var address_sorted_object = {};
                google.maps.event.addListener(autocomplete, 'place_changed', function () {


                    var place = autocomplete.getPlace();
                    var address_components = place.address_components;

                    address_components.forEach(function (currentValue) {
                        address_sorted_object[currentValue.types[0]] = currentValue;
                    });

                    //var last_element = hh[hh.length - 1];
                    // add lat lng
                    //$(element).attr('data-lat', place.geometry.location.lat());
                    //$(element).attr('data-lng', place.geometry.location.lng());

                    let data_latitude = place.geometry.location.lat();
                    let data_longitude = place.geometry.location.lng();
                    $('.personal_address-latitude').val(data_latitude);
                    $('.personal_address-longitude').val(data_longitude);
                    // checking data is completed
                    if (!("postal_code" in address_sorted_object)) {
                        // show session alert
                        ShowSessionAlert('danger', 'Your selected address does not contain a Postal Code. Kindly select a nearby address! ');
                        element.val(element.attr('data-old-val'));
                        element.siblings(".datatable-input-update-btn").hide();
                        element.addClass('input-error');
                        console.log(address_sorted_object);
                        return;
                    }
                    else if (!("locality" in address_sorted_object)) {
                        // show session alert
                        ShowSessionAlert('danger', 'Your Selected address does not contain city kindly select near by address !');
                        element.val(element.attr('data-old-val'));
                        element.siblings(".datatable-input-update-btn").hide();
                        element.addClass('input-error');
                        console.log(address_sorted_object);
                        return;
                    }

                    //element.attr('data-postal-code', address_sorted_object.postal_code.long_name);
                    //element.attr('data-city', address_sorted_object.locality.long_name);
                    let data_city = address_sorted_object.locality.long_name;
                    let data_postal_code = address_sorted_object.postal_code.long_name;
                    let date_state = address_sorted_object.administrative_area_level_1.long_name;
                    let date_street = address_sorted_object.street_number.long_name;
                    $('.personal_address-city').val(data_city);
                    $('.personal_address-state').val(date_state);
                    $('.personal_address-postal_code').val(data_postal_code);
                    $('.personal_address-street').val(date_street);

                    return false;


                });

            });

        });

        $(document).ready(function () {
            $('.phone_us').mask('+10000000000', {placeholder: "(+100)(000)(0000)"});
        });
        $(document).ready(function () {
            $('.joeys-list').select2({
                minimumInputLength: 2,
                placeholder: "Search a joey",
                allowClear: true,
            });
            $('.mi-list').select2({placeholder: "Assign Jobs",});

        });
    </script>
@stop
