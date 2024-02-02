@extends('admin.layouts.app')

@section('content')
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle ?? '' }} <small></small></h3>
        {{ Breadcrumbs::render('zones.edit', $zone ?? '') }}
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
                        <i class="fa fa-edit"></i> {{ $pageTitle ?? '' }}
                    </div>
                </div>

                <div class="portlet-body">

                    <h4>&nbsp;</h4>

                    <form method="POST" action="{{ route('zones.update', $zone ?? ''->id) }}" class="form-horizontal"
                          role="form">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name" class="col-md-2 control-label">Name *</label>
                            <div class="col-md-4">
                                <input type="text" id="name" maxlength="190" name="name" value="{{$zone->name}}"
                                       class="form-control" required="required"/>
                            </div>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="address" class="col-md-2 control-label">Address *</label>
                            <div class="col-md-4">
                                <input type="text" id="address" maxlength="190" name="address" value=""
                                       class="form-control address-map google-address" required="required"/>
                            </div>
                            @if ($errors->has('address'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="map" class="col-md-2 control-label">Map *</label>
                            <div class="col-md-4" id="map" style="height:400px; ">
                                {{--<input type="text" id="map" name="map" value="{{ old('map') }}" class="form-control" style="width: 100%; height: 100%" />--}}
                            </div>
                            @if ($errors->has('map'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('map') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <!-- Hidden input field of latitude -->
                        <input type="hidden" id="latitude" maxlength="190" name="latitude"
                               value="{{$zone->latitude}}" class="form-control lat"/>
                        <!-- Hidden input field of longitude -->
                        <input type="hidden" id="longitude" maxlength="190" name="longitude"
                               value="{{$zone->longitude}}" class="form-control lng"/>

                        <div class="form-group">
                            <label for="radius" class="col-md-2 control-label">Radius *</label>
                            <div class="col-md-4">
                                <input type="text" id="radius" maxlength="190" name="radius" value="{{$zone->radius}}"
                                       class="form-control" required="required"/>
                            </div>
                            @if ($errors->has('radius'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('radius') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="time_zone" class="col-md-2 control-label">Time Zones *</label>
                            <div class="col-md-4">
                                <input type="text" id="time_zone" maxlength="190" name="time_zone"
                                       value="{{$zone->timezone}}" class="form-control" required="required"/>
                            </div>
                            @if ($errors->has('time_zone'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('time_zone') }}</strong>
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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTK4viphUKcrJBSuoidDqRhVA4AWnHOo0&libraries=places" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function () {
            // initiate layout and plugins
            App.init();
            Admin.init();
            $('#cancel').click(function () {
                window.location.href = "{!! URL::route('zones.index') !!}";
            });

            let lat = $('.lat').val();
            let lng = $('.lng').val();
            let lat_Num = Math.abs(lng);
            let l_num = lat_Num.toString().length
            data_lat = lat.substring(0, 2) + "." + lat.substring(2, lat.length);
            if (l_num >= 9){

                data_lng = lng.substring(0, 4) + "." + lng.substring(4, lng.length);

            }
            else {
                data_lng = lng.substring(0, 3) + "." + lng.substring(3, lng.length);
            }

            data_int_lat = parseFloat(data_lat);
            data_int_lng = parseFloat(data_lng);
            // console.log(data_int_lat, data_int_lng,lat_Num,l_num);
            const map = new google.maps.Map(document.getElementById("map"), {
                center: {lat: data_int_lat, lng: data_int_lng},
                zoom: 6,
            });

            var myLatlng = new google.maps.LatLng(data_int_lat, data_int_lng);
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                draggable: true,
                title:"Fast marker"

            });

            const drag_map = new google.maps.Map(document.getElementById("map"), {
                center: {lat: data_int_lat, lng: data_int_lng},
                zoom: 15,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            marker.setMap(drag_map);
            var latlng = new google.maps.LatLng(data_int_lat, data_int_lng);

            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {
                        let drag_address = results[1].formatted_address;
                        $(".google-address").val(drag_address);
                        $('.lat').val(data_int_lat);
                        $('.lng').val(data_int_lng);
                    }
                }

            });




            // ajax function to show google map address suggestion
            $(document).on('click', '.address-map', function () {
                var acInputs = document.getElementsByClassName("google-address");

                /*const map = new google.maps.Map(document.getElementById("map"), {
                    center: {lat: 46.567163, lng: -75.976419},
                    zoom: 6,
                });*/

                const options = {
                    componentRestrictions: {country: "ca"},
                    fields: ["formatted_address", "geometry", "name","address_components"],
                    origin: map.getCenter(),
                    strictBounds: false,
                    types: ["establishment"],
                };

                for (var i = 0; i < acInputs.length; i++) {

                    var autocomplete = new google.maps.places.Autocomplete(acInputs[i], options);
//                    autocomplete.setComponentRestrictions({
//                        country: ["ca"],
//                    });

                    autocomplete.inputId = acInputs[i].id;
                }
                var address_sorted_object = {};

                google.maps.event.addListener(autocomplete, 'place_changed', function () {
                    var place = autocomplete.getPlace();
                    var address_components = place.address_components;
                    //console.log(address_components);
                    address_components.forEach(function (currentValue) {
                        address_sorted_object[currentValue.types[0]] = currentValue;
                    });

                    //var last_element = hh[hh.length - 1];
                    // add lat lng
                    let lat = place.geometry.location.lat();
                    let lng = place.geometry.location.lng();
                    $('.lat').val(lat);
                    $('.lng').val(lng);

                    //Getting Mark ON Location
                    var myLatlng = new google.maps.LatLng(lat, lng);
                    var myOptions = {
                        zoom: 12,
                        center: myLatlng,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    }
                    var map = new google.maps.Map(document.getElementById("map"), myOptions);
                    var marker = new google.maps.Marker({
                        position: myLatlng,
                        map: map,
                        draggable: true,
                        title:"Fast marker"

                    });
                    //Getting Address From Dragging Pointer On Map
                    google.maps.event.addListener(marker, 'dragend', function(evt){
                        let drag_tal = evt.latLng.lat();
                        let drag_lng = evt.latLng.lng();
                        const drag_map = new google.maps.Map(document.getElementById("map"), {
                            center: {lat: drag_tal, lng: drag_lng},
                            zoom: 15,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        });
                        marker.setMap(drag_map);
                        var latlng = new google.maps.LatLng(drag_tal, drag_lng);

                        var geocoder = new google.maps.Geocoder();
                        geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                if (results[1]) {
                                    let drag_address = results[1].formatted_address;
                                    $(".google-address").val(drag_address);
                                    $('.lat').val(drag_tal);
                                    $('.lng').val(drag_lng);
                                }
                            }

                        });
                    });



                });

            });

        });


    </script>
@stop
