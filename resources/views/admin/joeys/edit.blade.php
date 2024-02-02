@extends('admin.layouts.app')

@section('css')
    <style>
        .heading
        {
            margin-left: 6px;
            background-color: #e36d29;
            color: white;
        }
        .form-horizontal .form-group {
            margin-left: 0px;
            margin-right: 10px;
        }

        .control-label {
            text-align: left !important;
            padding: 8px;
        }

        .verification-link {
            background-color: #e36d29;
            margin: 0px 0px 0px 12px;
            width: 165px;
        }
        a {
            color: black;
        }
    </style>
@stop

@section('content')
    <div id="map"></div>
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle ?? '' }} <small></small></h3>
        {{ Breadcrumbs::render('joeys-list.edit', $joeys_list) }}
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
                        <i class="fa fa-edit"></i> {{ $pageTitle ?? '' }}
                    </div>
                </div>

                <div class="portlet-body">

                    <h4>&nbsp;</h4>

                    <form method="POST" action="{{ route('joeys-list.update', $joeys_list->id) }}" class="form-horizontal" role="form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')


                        <div class="form-group">
                            <label for="plan_information" class="col-md-12  col-xs-12 control-label heading">Plan Information</label>
                        </div>
                        <div class="form-group">
                            <label for="current_plan" class="col-md-2 control-label">Current Plan</label>
                            <div class="col-md-4">
                                <input type="text" name="current_plan" maxlength="150" disabled value="{{ old('current_plan', isset($plan) ? $plan->name : "") }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('current_plan'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('current_plan') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="plan_id" class="col-md-2 control-label">Change To</label>
                            <div class="col-md-4">
                            <select id="plan_id" name="plan_id" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                <option  value="" disabled selected>Please select plan</option>
                                @foreach($joeyplanList as $oc)
                                    <option value="{{$oc->id}}"
                                            @if($oc->id == $joeys_list->plan_id)
                                            selected="selected"
                                            @endif
                                    >{{$oc->name}}</option>
                                @endforeach
                            </select>
                            </div>
                            @if ($errors->has('plan_id'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('plan_id') }}</strong>
                                    </span>
                            @endif
                        </div>



        {{--                <div class="form-group">
                            <label for="vehicle" class="col-md-2 control-label">Default Vehicle</label>
                            <div class="col-md-4">
                                <select id="vehicle_id" name="vehicle_id" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                    <option  value="" disabled selected>Please select vehicle</option>
                                    @foreach($vehiclesList as $oc)
                                        <option value="{{$oc->id}}"
                                                @if($oc->id == $joey->vehicle_id)
                                                selected="selected"
                                                @endif
                                        >{{$oc->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                            @if ($errors->has('vehicle_id'))
                                <span class="help-block">
                                 <strong>{{ $errors->first('vehicle_id') }}</strong>
                                 </span>
                            @endif

                        </div>--}}








                        <div class="form-group">
                        <label for="current_plan" class="col-md-12 col-xs-12 control-label heading">Personal Information</label>
                        </div>
                        <div class="form-group">

                            <label for="first_name" class="col-md-2 control-label">First Name *</label>
                            <div class="col-md-4">
                                <input type="text" name="first_name" maxlength="150" value="{{ old('first_name', $joeys_list->first_name) }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('first_name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="last_name" class="col-md-2 control-label">Last Name *</label>
                            <div class="col-md-4">
                                <input type="text" name="last_name" maxlength="32" value="{{ old('last_name', $joeys_list->last_name) }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('last_name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-2 control-label">Email *</label>
                            <div class="col-md-4">
                                <input type="text" name="email" maxlength="32" value="{{ old('email', $joeys_list->email) }}"
                                       class="form-control" readonly/>
                            </div>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="phone" class="col-md-2 control-label">Phone *</label>
                            <div class="col-md-4">
                                <input type="text" name="phone" maxlength="32" value="{{ old('phone', $joeys_list->phone) }}"
                                       class="form-control phone_us"/>
                            </div>
                            @if ($errors->has('phone'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="phone" class="col-md-2 control-label">Nick name</label>
                            <div class="col-md-4">
                                <input type="text" name="nickname" maxlength="32" disabled value="{{ old('nickname', $joeys_list->nickname) }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('nickname'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('nickname') }}</strong>
                                        </span>
                            @endif
                        </div>


                        <div class="form-group">
                            <div>

                                {{ Form::label('upload_file', 'Upload Picture *', ['class'=>'col-md-2 control-label']) }}
                                <div class="col-md-4">
                                    {{ Form::file('upload_file', ['class' => 'form-control ']) }}
                                    <img style="max-width: 350px;height: 150px;margin-top: 4px " onClick="preview(this);"  src="{{$joeys_list->image}}" />

                                </div>
                                @if ( $errors->has('upload_file') )
                                    <p class="help-block">{{ $errors->first('upload_file') }}</p>
                                @endif


                            </div>
                        </div>
                        <div class="form-group">
                            <label for="about" class="col-md-2 control-label">About</label>
                            <div class="col-md-4">
                                <textarea id="about" name="about" class="form-control" rows="6" cols="50" maxlength = "200" placeholder="Text (maximum 200 character)">{{$joeys_list->about}}</textarea>
                            </div>

                        @if ($errors->has('about'))
                                <span class="help-block">
                                 <strong>{{ $errors->first('about') }}</strong>
                                 </span>
                            @endif

                        </div>


                        <div class="form-group">
                            <label for="hub" class="col-md-2 control-label">Hub</label>
                            <div class="col-md-4">
                                <select id="hub" name="hub" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                    <option  value="" disabled selected>Please select option</option>
                                    @foreach($hubList as $oc)
                                        <option value="{{$oc->id}}"
                                                @if($oc->id == $joeys_list->hub_id)
                                                selected="selected"
                                                @endif
                                        >{{$oc->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('hub'))
                                <span class="help-block">
                                 <strong>{{ $errors->first('hub') }}</strong>
                                 </span>
                            @endif

                        </div>

                        <div class="form-group">


                            <div class="row">

                                <div class="col-md-12">

                                    <label for="is_tax_applied" class="col-md-4 control-label"><input type='checkbox' id='is_tax_applied' name='is_tax_applied'
                                                                                                      @if($joeys_list->is_tax_applied ==1 ) checked @endif>Is Tax Applied</label>

                                    @if ($errors->has('is_tax_applied'))
                                        <span class="help-block">
                                 <strong>{{ $errors->first('is_tax_applied') }}</strong>
                                 </span>
                                    @endif

                                </div>
                                <div class="col-md-12">

                                    <label for="can_create_order" class="col-md-4 control-label"><input type='checkbox' id='can_create_order' name='can_create_order'
                                                                                                        @if($joeys_list->can_create_order ==1 ) checked @endif>Can Create Order</label>

                                    @if ($errors->has('can_create_order'))
                                        <span class="help-block">
                                 <strong>{{ $errors->first('can_create_order') }}</strong>
                                 </span>
                                    @endif


                                </div>

                            </div>
                            @if(!empty($joeys_list->email_verify_token))

                            <div class="row verification-link">
                                <a class='button orange-gradient' href="https://joey.joeyco.com/verify/{{$joeys_list->email_verify_token}}">
                                    Joey Email verification Link
                                </a>
                            </div>

                            @endif

                        </div>


                        <div class="form-group">
                            <label for="plan_information" class="col-md-12 col-xs-12 control-label heading">Location Information</label>
                        </div>
                        <div class="form-group">
                            <label for="address" class="col-md-2 control-label">Addresss</label>
                            <?php
                            if (isset($joeys_list->joeyLocation))
                            {
                                $joeyAddress = $joeys_list->joeyLocation->setDecryptAddressAttribute($joeys_list->joeyLocation->address, $joeys_list->location_id);
                                $joeyPostalCode = $joeys_list->joeyLocation->setDecryptPostalcodeAttribute($joeys_list->joeyLocation->postal_code, $joeys_list->location_id);

                            }
                            elseif ($joeys_list->address != null)
                            {
                                $joeyAddress = $joeys_list->address;
                                $joeyPostalCode = $joeys_list->postal_code;
                            }

                            else
                            {
                                $joeyAddress = null;
                                $joeyPostalCode = null;
                            }


                            ?>
                            <div class="col-md-4">
                                <input type="text" name="address" maxlength="32" value="{{ old('address', $joeyAddress) }}"
                                       class="form-control update-address-on-change google-address"/>
                                <input type="hidden" name="latitude" id="latitude" value="">
                                <input type="hidden" name="longitude" id="longitude" value="">
                                <input type="hidden" name="city" id="city" value="">
                            </div>
                            @if ($errors->has('address'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                            @endif
                        </div>


                        <div class="form-group">
                            <label for="suite" class="col-md-2 control-label">Suite</label>
                            <div class="col-md-4">
                                <input type="text" name="suite" maxlength="32" value="{{ old('suite', $joeys_list->suite) }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('suite'))
                                <span class="help-block">
                                 <strong>{{ $errors->first('suite') }}</strong>
                                 </span>
                            @endif

                        </div>

                        <div class="form-group">
                            <label for="postalCode" class="col-md-2 control-label">Postal code</label>
                            <div class="col-md-4">
                                <input type="text" name="postalCode" id="postal-code" maxlength="32" value="{{ old('postalCode', $joeyPostalCode) }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('postalCode'))
                                <span class="help-block">
                                 <strong>{{ $errors->first('postalCode') }}</strong>
                                 </span>
                            @endif

                        </div>
                        <div class="form-group">
                            <label for="plan_information" class="col-md-12 col-xs-12 control-label heading">Vehicle Information</label>
                        </div>
                        <div class="form-group">
                            <label for="vehicle" class="col-md-2 control-label">Default Vehicle</label>
                            <div class="col-md-4">
                                <select id="vehicle_id" name="vehicle_id" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                    <option  value="" disabled selected>Please select vehicle</option>
                                    @foreach($vehiclesList as $oc)
                                        <option value="{{$oc->id}}"
                                                @if(isset($joeys_list->joeyVehicle))
                                                        @if($oc->id == $joeys_list->joeyVehicle->vehicle_id)
                                                selected="selected"
                                                @endif
                                                @endif
                                        >{{$oc->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('vehicle_id'))
                                <span class="help-block">
                                 <strong>{{ $errors->first('vehicle_id') }}</strong>
                                 </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="suite" class="col-md-2 control-label">Make *</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-lg make" placeholder="Make"
                                       name="make"  maxlength="20" value="{{isset($joeys_list->joeyVehicle)?$joeys_list->joeyVehicle->make:old('make')}}" required>
                            </div>
                            @if ($errors->has('make'))
                                <div class="invalid-feedback">{{ $errors->first('make') }}</div>
                            @endif

                        </div>

                        <div class="form-group">
                            <label for="suite" class="col-md-2 control-label">Model *</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-lg model" placeholder="Model"
                                       name="model" maxlength="20" value="{{isset($joeys_list->joeyVehicle)?$joeys_list->joeyVehicle->model:old('model')}}" required>
                            </div>
                            @if ($errors->has('model'))
                                <div class="invalid-feedback">{{ $errors->first('model') }}</div>
                            @endif

                        </div>
                        <div class="form-group">
                            <label for="suite" class="col-md-2 control-label">Color *</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-lg" placeholder="Color"
                                       name="color" maxlength="20" value="{{isset($joeys_list->joeyVehicle)?$joeys_list->joeyVehicle->color:old('color')}}" required>
                            </div>
                            @if ($errors->has('color'))
                                <div class="invalid-feedback">{{ $errors->first('color') }}</div>
                            @endif

                        </div>
                        <div class="form-group">
                            <label for="suite" class="col-md-2 control-label">License plate *</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-lg license-plate" placeholder="License Plate"
                                       name="license" maxlength="20" value="{{isset($joeys_list->joeyVehicle)?$joeys_list->joeyVehicle->license_plate:old('license')}}" required>
                            </div>
                            @if ($errors->has('license'))
                                <div class="invalid-feedback">{{ $errors->first('license') }}</div>
                            @endif

                        </div>


                        <div class="form-group">
                            <label for="plan_information" class="col-md-12 control-label heading">Details</label>
                        </div>

                        <div class="form-group">


                            <div class="row">
                                  <div class="col-md-12">
                                    <label for="is_itinerary" class="col-md-4 control-label"><input type='checkbox' id='is_itinerary' name='is_itinerary' @if($joeys_list->is_itinerary ==1 ) checked @endif>Show itinerary results</label>

                                    @if ($errors->has('is_itinerary'))
                                        <span class="help-block">
                                         <strong>{{ $errors->first('is_itinerary') }}</strong>
                                         </span>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <label for="has_bag" class="control-label"><input type='checkbox' id='has_bag' name='has_bag' @if($joeys_list->has_bag ==1 ) checked @endif> Has Bag</label>
                                    @if ($errors->has('has_bag'))
                                        <span class="help-block">
                                     <strong>{{ $errors->first('has_bag') }}</strong>
                                     </span>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <label for="is_backcheck" class="col-md-4  col-xs-4 control-label"><input type='checkbox' id='is_backcheck' name='is_backcheck' @if($joeys_list->is_backcheck ==1 ) checked @endif>Back Check</label>

                                    @if ($errors->has('is_backcheck'))
                                        <span class="help-block">
                                         <strong>{{ $errors->first('is_backcheck') }}</strong>
                                         </span>
                                    @endif
                                </div>


                            </div>
                        </div>




                        <div class="form-group">
                            <label for="storeType" class="col-md-2 control-label">Shift Type</label>
                            <div class="col-md-4">
                                <select id="storeType" name="storeType" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                <option  value="" disabled selected>Please select option</option>
                                <option  value="grocery"   @if (isset($joeys_list->shift_store_type)&& $joeys_list->shift_store_type=='grocery') {{'Selected'}} @endif >Grocery</option>
                                <option  value="ecommerce"@if (isset($joeys_list->shift_store_type)&& $joeys_list->shift_store_type=='ecommerce') {{'Selected'}} @endif >E commerce</option>
                                </select>
                            </div>
                            @if ($errors->has('storeType'))
                                <span class="help-block">
                                 <strong>{{ $errors->first('storeType') }}</strong>
                                 </span>
                            @endif

                        </div>

                   {{--     <div class="form-group">
                            <label for="has_bag" class="col-md-2 control-label">Has Bag</label>
                            <div class="col-md-5 col-xs-1">
                                <label><input type='checkbox' id='has_bag' name='has_bag'
                                              @if($joeys_list->has_bag ==1 ) checked @endif></label>
                            </div>
                            @if ($errors->has('has_bag'))
                                <span class="help-block">
                                 <strong>{{ $errors->first('has_bag') }}</strong>
                                 </span>
                            @endif

                        </div>




                        <div class="form-group">
                            <label for="is_backcheck" class="col-md-2 control-label">Back Check</label>
                            <div class="col-md-5 col-xs-1">
                                <label><input type='checkbox' id='is_backcheck' name='is_backcheck'
                                              @if($joeys_list->is_backcheck ==1 ) checked @endif></label>
                            </div>
                            @if ($errors->has('is_backcheck'))
                                <span class="help-block">
                                 <strong>{{ $errors->first('is_backcheck') }}</strong>
                                 </span>
                            @endif

                        </div>--}}


                        <div class="form-group">
                            <label for="prefferedZone" class="col-md-2 control-label">Preffered Zone</label>
                            <div class="col-md-4">
                                <select class="js-example-basic-multiple form-control col-md-7 col-xs-12 preferred-zone" name="prefferedZone" required>
                                    <option value="">Select an option</option>
                                    @foreach($zone_list as $zone)
                                        <option value="{{$zone->id}}">{{$zone->name}}</option>
                                    @endforeach
                                </select>
                               <!--  <input type="text" name="prefferedZone" maxlength="32" value="{{ old('prefferedZone', isset($joeys_list) ? $joeys_list->preferred_zone : "") }}"
                                       class="form-control"/> -->
                            </div>
                            @if ($errors->has('prefferedZone'))
                                <span class="help-block">
                                 <strong>{{ $errors->first('prefferedZone') }}</strong>
                                 </span>
                            @endif

                        </div>
                        <div class="form-group">
                            <label for="ComdataCard" class="col-md-2 control-label">Comdata Card</label>
                            <div class="col-md-4">
                                <input type="text" name="ComdataCard" maxlength="32" value="{{ old('ComdataCard', isset($joeys_list) ? $joeys_list->comdata_cc_num : "") }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('ComdataCard'))
                                <span class="help-block">
                                 <strong>{{ $errors->first('ComdataCard') }}</strong>
                                 </span>
                            @endif

                        </div>

                        <div class="form-group">
                            <label for="HSTNumber" class="col-md-2 control-label">HST Number</label>
                            <div class="col-md-4">
                                <input type="text" name="HSTNumber" maxlength="32" value="{{ old('HSTNumber', isset($joeys_list) ? $joeys_list->hst_number : "") }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('HSTNumber'))
                                <span class="help-block">
                                 <strong>{{ $errors->first('HSTNumber') }}</strong>
                                 </span>
                            @endif

                        </div>

                        <div class="form-group">
                            <label for="RBCDepositCardNumber" class="col-md-2 control-label">RBC Deposit Card Number</label>
                            <div class="col-md-4">
                                <input type="text" name="RBCDepositCardNumber" maxlength="32" value="{{ old('RBCDepositCardNumber', isset($joeys_list) ? $joeys_list->rbc_deposit_number : "") }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('RBCDepositCardNumber'))
                                <span class="help-block">
                                 <strong>{{ $errors->first('RBCDepositCardNumber') }}</strong>
                                 </span>
                            @endif

                        </div>



                        <div class="form-group">
                            <label for="plan_information" class="col-md-12 col-xs-12 control-label heading">Joey Deposit Information</label>
                        </div>

                        <div class="form-group">
                            <label for="institutionNo" class="col-md-2 control-label">Institution No</label>
                            <div class="col-md-4">
                                <input type="text" name="institutionNo" maxlength="32" value="{{ old('institutionNo', isset($joeys_list->Deposit) ? $joeys_list->Deposit->institution_no : "" ) }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('institutionNo'))
                                <span class="help-block">
                                 <strong>{{ $errors->first('institutionNo') }}</strong>
                                 </span>
                            @endif

                        </div>

                        <div class="form-group">
                            <label for="branchNo" class="col-md-2 control-label">Branch No</label>
                            <div class="col-md-4">
                                <input type="text" name="branchNo" maxlength="32" value="{{ old('branchNo', isset($joeys_list->Deposit) ? $joeys_list->Deposit->branch_no : "") }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('branchNo'))
                                <span class="help-block">
                                 <strong>{{ $errors->first('branchNo') }}</strong>
                                 </span>
                            @endif

                        </div>
                        <div class="form-group">
                            <label for="accountNo" class="col-md-2 control-label">Account No</label>
                            <div class="col-md-4">
                                <input type="text" name="accountNo" maxlength="32" value="{{ old('accountNo', isset($joeys_list->Deposit) ? $joeys_list->Deposit->account_no : "") }}"
                                       class="form-control"/>
                            </div>

                            @if ($errors->has('accountNo'))
                                <span class="help-block">
                                 <strong>{{ $errors->first('accountNo') }}</strong>
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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTK4viphUKcrJBSuoidDqRhVA4AWnHOo0&libraries=places" type="text/javascript"></script>

    <script>

        jQuery(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
            // initiate layout and plugins
            App.init();
            Admin.init();
            $('#cancel').click(function() {
                window.location.href = "{{ route('joeys-list.index') }}";
            });
        });

        $(function(){
            var dtToday = new Date();

            var month = dtToday.getMonth() + 1;
            var day = dtToday.getDate();
            var year = dtToday.getFullYear();
            if(month < 10)
                month = '0' + month.toString();
            if(day < 10)
                day = '0' + day.toString();
                day=day+1;
            var minDate= year + '-' + month + '-' + day;

            $('#joeyLicenseExpirydate').attr('min', minDate);
            $('#workPermitDocumentExpirydate').attr('min', minDate);
        });

        $(document).ready(function() {

            $('.phone_us').mask('(000) 000-0000', {placeholder: "(___) ___-____"});
            {{--make_option_selected('.role-type','{{ old('role',$sub_admin ?? ''->role_id) }}');--}}
            make_option_selected('.preferred-zone','{{ old('prefferedZone',$joeys_list->preferred_zone) }}');


// ajax function to show google map address suggestion
            $(document).on('focus', '.update-address-on-change', function () {

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
                    fields: ["formatted_address", "geometry", "name","address_components"],
                    origin: map.getCenter(),
                    strictBounds: false,
                    //types: ["establishment"],
                };
                var autocomplete = new google.maps.places.Autocomplete(acInputs, options);

                var address_sorted_object = {};
                google.maps.event.addListener(autocomplete, 'place_changed', function () {

                    // removing alert
                    $(".session-wrapper").find('.alert').remove();

                    var place = autocomplete.getPlace();
                    var address_components = place.address_components;

                    address_components.forEach(function (currentValue) {
                        address_sorted_object[currentValue.types[0]] = currentValue;
                    });

                    //var last_element = hh[hh.length - 1];
                    // add lat lng
                    //$(element).attr('data-lat',place.geometry.location.lat());
                    //$(element).attr('data-lng',place.geometry.location.lng());
                    $('#latitude').val(place.geometry.location.lat());
                    $('#longitude').val(place.geometry.location.lng());
                    // checking data is completed
                    if(!("postal_code" in address_sorted_object))
                    {
                        // show session alert
                        //ShowSessionAlert('danger', 'Your selected address does not contain a Postal Code. Kindly select a nearby address! ');
                        //element.val(element.attr('data-old-val'));
                        //element.siblings(".datatable-input-update-btn").hide();
                        element.addClass('input-error');
                        console.log(address_sorted_object);
                        return;
                    }
                    else if(!("locality" in address_sorted_object))
                    {
                        // show session alert
                        //ShowSessionAlert('danger', 'Your Selected address does not contain city kindly select near by address !');
                        element.val(element.attr('data-old-val'));
                        element.siblings(".datatable-input-update-btn").hide();
                        element.addClass('input-error');
                        console.log(address_sorted_object);
                        return;
                    }

                    $('#postal-code').val(address_sorted_object.postal_code.long_name);
                    $('#city').val(address_sorted_object.locality.long_name);

                    //element.attr('data-postal-code',address_sorted_object.postal_code.long_name);
                    //element.attr('data-city',address_sorted_object.locality.long_name);
                    console.log(place.geometry.location.lat());
                    // checking the ajax is already not trigger
                    // if(triggerAjax){
                    // now making trigger ajax false for multiple trigger
                    triggerAjax = false;
                    //ColumnValueChangeByAjax(element);
                    // }

                });

            });


        });
    </script>
@stop
