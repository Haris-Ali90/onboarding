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
        .incident-input-box-inner .remove-incident, .child-input-box-inner .remove-child-input-box {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
            color: red;
        }
        .incident-input-box-org .remove-incident {
            display: none;
        }
        .form-group {
            position: relative;
        }
        .error
        {
            display: block !important;
        }
        .child-input-box-inner.error, .child-input-box-inner.error input, .child-input-box-inner.error select
        {
            border-color:#ff0000;

        }
    </style>
@stop

@section('content')
    <!-- BEGIN PAGE HEADER-->
    @include('admin.partials.errors')

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle }} <small></small></h3>
        {{ Breadcrumbs::render('customer-service.edit', $customer_service) }}
        <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet box blue">

                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-plus"></i> {{ $pageTitle }}
                    </div>
                </div>

                <div class="portlet-body">
                    <form method="POST" action="{{ route('customer-service.update',$customer_service->id) }}" class="form-horizontal"
                          role="form" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!--from-input-wrapper-open-->
                        <div class="row from-input-wraper">
                            <div class="col-md-12">
                                <p class="section-heading">Edit Grand Parent Category Name</p>
                            </div>

                            <input type="hidden" name="main_cat_uid" class="main_cat_uid" value="{{$customer_service->uid}}">
                            <!--from-input-col-open-->
                            <div class="col-md-4 col-sm-4 from-input-col">
                                <div class="form-group">
                                    <label class="control-label">Grand Parent Flag Category Name *</label>
                                    <input type="text" name="category_name" value="{{ $customer_service->category_name }}" class="form-control" required />
                                    @if ($errors->has('category_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('category_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!--from-input-col-close-->

                            <!--from-input-col-open-->
                            <div class="col-md-4 col-sm-4 from-input-col">
                                <div class="form-group">
                                    <label class="control-label">Portal Type *</label>
                                    <select class="js-example-basic-multiple form-control col-md-7 col-xs-12 portal-type" name="portal_type[]"  multiple="multiple" required>
                                        <option value="dashboard" >Dashboard</option>
                                        <option value="finance_dashboard" >Finance Dashboard</option>
                                    </select>
                                    @if ($errors->has('type'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('type') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!--from-input-col-close-->
                            <!--from-input-col-open-->
                            <div class="col-md-4 col-sm-4 from-input-col">
                                <div class="form-group">
                                    <label class="control-label">Order Type *</label>
                                    <select class="js-example-basic-multiple form-control col-md-7 col-xs-12 order-type"
                                            name="order_type[]" multiple="multiple" required>
                                        @foreach($flag_order_types as $flag_order_type)
                                            <option value="{{$flag_order_type->label}}">{{$flag_order_type->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!--from-input-col-close-->
                        </div>
                        <!--from-input-wrapper-close-->
                        <!--from-input-wrapper-open-->
                        <div class="row from-input-wraper">
                            <!--from-input-col-open-->
                            <div class="col-md-4 col-sm-4 from-input-col">
                                <div class="form-group">
                                    <label class="control-label">Vendors (Optional)</label>
                                    <select class="js-example-basic-multiple form-control col-md-7 col-xs-12 vendors"
                                            name="vendors[]" multiple="multiple">
                                        @foreach($vendor_list as $vendor)
                                            <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!--from-input-col-close-->
                            <!--from-input-col-open-->
                            <div class="col-md-4 col-sm-4 from-input-col">
                                <div class="form-group">
                                    <label class="control-label">Enable For (Route Info) Flagging *</label>
                                    <select class="form-control col-md-7 col-xs-12 is-show-route"
                                            name="is_show_route" required>
                                        <option value="1" selected>Yes</option>
                                        <option value="0">No</option>
                                        <option value="2">Both</option>
                                    </select>
                                </div>
                            </div>
                            <!--from-input-col-close-->
                        </div>
                        <!--from-input-wrapper-close-->

                        <!--from-input-wrapper-sub-category-open-->
                        <div class="row from-input-wraper form-section-3">

                            <div class="col-md-12">
                                <p class="section-heading">Add Parent Category</p>
                            </div>

                            <div class="col-md-12">
                                <a class="btn blue add-child-box"> Add Parent Category </a>
                            </div>

                            <!--detail-form-one-wrap-open-->
                            <div class="row detail-form-wrap bracket-pricing-detail-form-wrap">

                                <!--incident-input-box-open-->
                                <div class="col-sm-12 child-input-outter-wrapper">
                                @php
                                    $third_child_data = [];
                                    $second_child_json_data_Set = [
                                        "0"=>[
                                            "local_id"=>"",
                                            "id"=>0,
                                            "text"=>"Please Select an option"
                                        ],
                                    ];
                                @endphp
                                @foreach($customer_service->getChilds as $second_child)
                                    @php

                                        $third_child_data = array_merge($third_child_data,Arr::flatten($second_child->getChilds));

                                        //updating jason data
                                        $second_child_json_data_Set[$second_child->uid] = [
                                                    "local_id"=>$second_child->uid,
                                                    "id"=>$second_child->id,
                                                    "text"=>$second_child->category_name,
                                                    ];

                                    @endphp
                                    <!--Incident-input-box-inner-open-->
                                        <div class="col-sm-12 incident child-input-box-inner @if($loop->iteration == 1) child-input-box-inner-org @else child-input-box-inner-clone @endif">

                                            <input type="hidden" class="child_uid" name="child_uid[]" value="{{$second_child->uid}}" />
                                            <input type="hidden" class="second_child_id" name="second_child_id[]" value="{{$second_child->id}}" />
                                            <div class="col-md-6 col-sm-6 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">Parent Category Name *</label>
                                                    <input type="text" name="child_category_name[]"
                                                           class="form-control child_category_name" value="{{$second_child->category_name}}" required/>
                                                </div>
                                            </div>

                                        </div>
                                        <!--incident-input-box-inner-close-->
                                    @endforeach

                                </div>
                            </div>
                            <!--from-input-col-open-->
                            <div class="col-sm-12">
                                <button type="button" class="btn blue set-child-data">Add</button>
                            </div>
                        </div>
                        <!--from-input-wrapper-sub-category-close-->


                        <!--from-input-wrapper-sub-category-open-->
                        <div class="row from-input-wraper form-section-3">

                            <div class="col-md-12">
                                <p class="section-heading">Add Child Category</p>
                            </div>

                            <div class="col-md-12">
                                <a class="btn blue add-incident-input-box"> Add Child Category </a>
                            </div>

                            <!--detail-form-one-wrap-open-->
                            <div class="row detail-form-wrap bracket-pricing-detail-form-wrap">

                                <!--incident-input-box-open-->
                                <div class="col-sm-12 incident-input-box ">

                                @foreach($third_child_data as $third_child)

                                    <!--Incident-input-box-inner-open-->
                                        <div class="col-sm-12 incident parent-input-box-inner incident-input-box-inner @if($loop->remaining == 0) incident-input-box-org @else incident-input-box-appended @endif">
                                            <input type="hidden" class="update_incident_input_id" name="child_ids[]" value="{{$third_child->id}}" />
                                            {{--<i class="fa fa-times-circle remove-incident" data-id="{{$third_child->id}}"></i>--}}
                                            <div class="col-md-6 col-sm-6 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label section-heading">Child Category Name *</label>
                                                    <input type="text" name="sub_category_name[]" value="{{ $third_child->category_name }}" class="form-control sub_category_name" max="100" required />
                                                    @if ($errors->has('sub_category_name'))
                                                        <span class="help-block">
                                        <strong>{{ $errors->first('sub_category_name') }}</strong>
                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <input type="hidden" class="grand_child_uid" name="grand_child_uid[]" value="{{$third_child->uid}}" />
                                            <div class="col-md-6 col-sm-6 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label section-heading">Select Parent Category *</label>
                                                    <select class="Parent-selector form-control"
                                                            name="parent_cat[]" required  >
                                                        <option data-id="{{$third_child->getParent->id}}" value="{{$third_child->getParent->uid}}"  selected> {{$third_child->getParent->category_name}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!--Incidents-open-->

                                            <!--Incidents-heading-open-->
                                            <div class="col-sm-12 col-md-12">
                                                <h4 class="section-heading">Incidents</h4>
                                            </div>
                                            <!--Incidents-heading-close-->
                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col ">
                                                <div class="form-group">
                                                    <label class="control-label">1st Incident *</label>

                                                    <select data-index="1" class="form-control col-md-7 col-xs-12 incident-selection incident-selection-1 select-opt"  required>
                                                        <option value="">Select an option</option>
                                                        @foreach($incident_list as $id => $incident)
                                                            <option value="{{$id}}" @if($third_child->FlagCategoryValues->incident_1_ref_id == $id) selected @endif>{{$incident}}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="incident_1_ref_id[]" class="selection-hidden" />
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">1st Incident Refresh Rate *</label>
                                                    <select data-index="1"
                                                            class="form-control col-md-7 col-xs-12 refresh-rate-selection refresh-rate-selection-1"
                                                            name="refresh_rate_incident_1[]" required>
                                                        <option value="">Select an option</option>
														<option value="0" @if($third_child->FlagCategoryValues->refresh_rate_incident_1 == 0) selected @endif>0</option>
                                                        <option value="0.5" @if($third_child->FlagCategoryValues->refresh_rate_incident_1 == 0.5) selected @endif >0.5</option>
                                                        <option value="1" @if($third_child->FlagCategoryValues->refresh_rate_incident_1 == 1) selected @endif >1 month</option>
                                                        <option value="1.5" @if($third_child->FlagCategoryValues->refresh_rate_incident_1 == 1.5) selected @endif >1.5 month</option>
                                                        <option value="2" @if($third_child->FlagCategoryValues->refresh_rate_incident_1 == 2) selected @endif >2 months</option>
                                                        <option value="3" @if($third_child->FlagCategoryValues->refresh_rate_incident_1 == 3) selected @endif >3 months</option>
                                                        <option value="4" @if($third_child->FlagCategoryValues->refresh_rate_incident_1 == 4 ) selected @endif >4 months</option>
                                                        <option value="5" @if($third_child->FlagCategoryValues->refresh_rate_incident_1 == 5) selected @endif >5 months</option>
                                                        <option value="6" @if($third_child->FlagCategoryValues->refresh_rate_incident_1 == 6) selected @endif >6 months</option>
                                                        <option value="7" @if($third_child->FlagCategoryValues->refresh_rate_incident_1 == 7) selected @endif >7 months</option>
                                                        <option value="8" @if($third_child->FlagCategoryValues->refresh_rate_incident_1 == 8) selected @endif >8 months</option>
                                                        <option value="9" @if($third_child->FlagCategoryValues->refresh_rate_incident_1 == 9) selected @endif >9 months</option>
                                                        <option value="10" @if($third_child->FlagCategoryValues->refresh_rate_incident_1 == 10) selected @endif >10 months</option>
                                                        <option value="11" @if($third_child->FlagCategoryValues->refresh_rate_incident_1 == 11) selected @endif >11 months</option>
                                                        <option value="12" @if($third_child->FlagCategoryValues->refresh_rate_incident_1 == 12) selected @endif >12 months</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <!--from-input-col-close-->

                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">2nd Incident *</label>

                                                    <select data-index="2" class="form-control col-md-7 col-xs-12 incident-selection incident-selection-2"  required>
                                                        <option value="">Select an option</option>
                                                        @foreach($incident_list as $id => $incident)
                                                            <option value="{{$id}}" @if($third_child->FlagCategoryValues->incident_2_ref_id == $id) selected @endif>{{$incident}}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="incident_2_ref_id[]" class="selection-hidden" />
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">2st Incident Refresh Rate *</label>
                                                    <select data-index="1"
                                                            class="form-control col-md-7 col-xs-12 refresh-rate-selection refresh-rate-selection-2"
                                                            name="refresh_rate_incident_2[]" required>
                                                        <option value="">Select an option</option>
														<option value="0" @if($third_child->FlagCategoryValues->refresh_rate_incident_1 == 0) selected @endif>0</option>
                                                        <option value="0.5" @if($third_child->FlagCategoryValues->refresh_rate_incident_2 == 0.5) selected @endif >0.5</option>
                                                        <option value="1" @if($third_child->FlagCategoryValues->refresh_rate_incident_2 == 1) selected @endif >1 month</option>
                                                        <option value="1.5" @if($third_child->FlagCategoryValues->refresh_rate_incident_2 == 1.5) selected @endif >1.5 month</option>
                                                        <option value="2" @if($third_child->FlagCategoryValues->refresh_rate_incident_2 == 2) selected @endif >2 months</option>
                                                        <option value="3" @if($third_child->FlagCategoryValues->refresh_rate_incident_2 == 3) selected @endif >3 months</option>
                                                        <option value="4" @if($third_child->FlagCategoryValues->refresh_rate_incident_2 == 4 ) selected @endif >4 months</option>
                                                        <option value="5" @if($third_child->FlagCategoryValues->refresh_rate_incident_2 == 5) selected @endif >5 months</option>
                                                        <option value="6" @if($third_child->FlagCategoryValues->refresh_rate_incident_2 == 6) selected @endif >6 months</option>
                                                        <option value="7" @if($third_child->FlagCategoryValues->refresh_rate_incident_2 == 7) selected @endif >7 months</option>
                                                        <option value="8" @if($third_child->FlagCategoryValues->refresh_rate_incident_2 == 8) selected @endif >8 months</option>
                                                        <option value="9" @if($third_child->FlagCategoryValues->refresh_rate_incident_2 == 9) selected @endif >9 months</option>
                                                        <option value="10" @if($third_child->FlagCategoryValues->refresh_rate_incident_2 == 10) selected @endif >10 months</option>
                                                        <option value="11" @if($third_child->FlagCategoryValues->refresh_rate_incident_2 == 11) selected @endif >11 months</option>
                                                        <option value="12" @if($third_child->FlagCategoryValues->refresh_rate_incident_2 == 12) selected @endif >12 months</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <!--from-input-col-close-->

                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">3nd Incident *</label>

                                                    <select data-index="3"  class="form-control col-md-7 col-xs-12 incident-selection incident-selection-3"  required>
                                                        <option value="">Select an option</option>
                                                        @foreach($incident_list as $id => $incident)
                                                            <option value="{{$id}}" @if($third_child->FlagCategoryValues->incident_3_ref_id == $id) selected @endif>{{$incident}}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="incident_3_ref_id[]" class="selection-hidden" />
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">3st Incident Refresh Rate *</label>
                                                    <select data-index="1"
                                                            class="form-control col-md-7 col-xs-12 refresh-rate-selection refresh-rate-selection-3"
                                                            name="refresh_rate_incident_3[]" required>
                                                        <option value="">Select an option</option>
														<option value="0" @if($third_child->FlagCategoryValues->refresh_rate_incident_1 == 0) selected @endif>0</option>
                                                        <option value="0.5" @if($third_child->FlagCategoryValues->refresh_rate_incident_3 == 0.5) selected @endif >0.5</option>
                                                        <option value="1" @if($third_child->FlagCategoryValues->refresh_rate_incident_3 == 1) selected @endif >1 month</option>
                                                        <option value="1.5" @if($third_child->FlagCategoryValues->refresh_rate_incident_3 == 1.5) selected @endif >1.5 month</option>
                                                        <option value="2" @if($third_child->FlagCategoryValues->refresh_rate_incident_3 == 2) selected @endif >2 months</option>
                                                        <option value="3" @if($third_child->FlagCategoryValues->refresh_rate_incident_3 == 3) selected @endif >3 months</option>
                                                        <option value="4" @if($third_child->FlagCategoryValues->refresh_rate_incident_3 == 4 ) selected @endif >4 months</option>
                                                        <option value="5" @if($third_child->FlagCategoryValues->refresh_rate_incident_3 == 5) selected @endif >5 months</option>
                                                        <option value="6" @if($third_child->FlagCategoryValues->refresh_rate_incident_3 == 6) selected @endif >6 months</option>
                                                        <option value="7" @if($third_child->FlagCategoryValues->refresh_rate_incident_3 == 7) selected @endif >7 months</option>
                                                        <option value="8" @if($third_child->FlagCategoryValues->refresh_rate_incident_3 == 8) selected @endif >8 months</option>
                                                        <option value="9" @if($third_child->FlagCategoryValues->refresh_rate_incident_3 == 9) selected @endif >9 months</option>
                                                        <option value="10" @if($third_child->FlagCategoryValues->refresh_rate_incident_3 == 10) selected @endif >10 months</option>
                                                        <option value="11" @if($third_child->FlagCategoryValues->refresh_rate_incident_3 == 11) selected @endif >11 months</option>
                                                        <option value="12" @if($third_child->FlagCategoryValues->refresh_rate_incident_3 == 12) selected @endif >12 months</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <!--from-input-col-close-->

                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">Conclusion *</label>
                                                    <select data-index="4" class="form-control col-md-7 col-xs-12 incident-selection incident-selection-4"  required>
                                                        <option value="">Select an option</option>
                                                        @foreach($incident_list as $id => $incident)
                                                            <option value="{{$id}}" @if($third_child->FlagCategoryValues->conclusion_ref_id == $id) selected @endif>{{$incident}}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="conclusion_ref_id[]" class="selection-hidden" />
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Conclusion Refresh Rate *</label>
                                                    <select data-index="1"
                                                            class="form-control col-md-7 col-xs-12 refresh-rate-selection refresh-rate-selection-4"
                                                            name="refresh_rate_conclusion[]" required>
                                                        <option value="">Select an option</option>
														<option value="0" @if($third_child->FlagCategoryValues->refresh_rate_incident_1 == 0) selected @endif>0</option>
                                                        <option value="0.5" @if($third_child->FlagCategoryValues->refresh_rate_conclusion == 0.5) selected @endif >0.5</option>
                                                        <option value="1" @if($third_child->FlagCategoryValues->refresh_rate_conclusion == 1) selected @endif >1 month</option>
                                                        <option value="1.5" @if($third_child->FlagCategoryValues->refresh_rate_conclusion == 1.5) selected @endif >1.5 month</option>
                                                        <option value="2" @if($third_child->FlagCategoryValues->refresh_rate_conclusion == 2) selected @endif >2 months</option>
                                                        <option value="3" @if($third_child->FlagCategoryValues->refresh_rate_conclusion == 3) selected @endif >3 months</option>
                                                        <option value="4" @if($third_child->FlagCategoryValues->refresh_rate_conclusion == 4 ) selected @endif >4 months</option>
                                                        <option value="5" @if($third_child->FlagCategoryValues->refresh_rate_conclusion == 5) selected @endif >5 months</option>
                                                        <option value="6" @if($third_child->FlagCategoryValues->refresh_rate_conclusion == 6) selected @endif >6 months</option>
                                                        <option value="7" @if($third_child->FlagCategoryValues->refresh_rate_conclusion == 7) selected @endif >7 months</option>
                                                        <option value="8" @if($third_child->FlagCategoryValues->refresh_rate_conclusion == 8) selected @endif >8 months</option>
                                                        <option value="9" @if($third_child->FlagCategoryValues->refresh_rate_conclusion == 9) selected @endif >9 months</option>
                                                        <option value="10" @if($third_child->FlagCategoryValues->refresh_rate_conclusion == 10) selected @endif >10 months</option>
                                                        <option value="11" @if($third_child->FlagCategoryValues->refresh_rate_conclusion == 11) selected @endif >11 months</option>
                                                        <option value="12" @if($third_child->FlagCategoryValues->refresh_rate_conclusion == 12) selected @endif >12 months</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <!--from-input-col-close-->

                                            <!--Incidents-close-->

                                            <!--Finance-incidents-open-->

                                            <!--Finance-Incidents-heading-open-->
                                            <div class="col-sm-12 col-md-12">
                                                <h4 class="section-heading">Finance Incidents</h4>
                                            </div>
                                            <!--Finance-Incidents-heading-close-->

                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">1st Finance Incident *</label>
                                                    <input  type="number" min="0" step="0.1" name="finance_incident_1[]" class="form-control" value="{{ $third_child->FlagCategoryValues->finance_incident_1 }}" data-input-type="number" required />
                                                </div>
                                            </div>
                                            <!--from-input-col-close-->

                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">1st Finance Incident Operator *</label>
                                                    <select class="form-control col-md-7 col-xs-12" name="finance_incident_1_operator[]" required>
                                                        <option value="">Select an option</option>
                                                        <option value="+" @if($third_child->FlagCategoryValues->finance_incident_1_operator == '+') selected @endif>Add</option>
                                                        <option value="-" @if($third_child->FlagCategoryValues->finance_incident_1_operator == '-') selected @endif>Subtract</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--from-input-col-close-->

                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">2nd Finance Incident *</label>
                                                    <input  type="number" min="0" step="0.1" name="finance_incident_2[]" class="form-control" value="{{ $third_child->FlagCategoryValues->finance_incident_2 }}" data-input-type="number" required />
                                                </div>
                                            </div>
                                            <!--from-input-col-close-->

                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">2nd Finance Incident Operator *</label>
                                                    <select class="form-control col-md-7 col-xs-12" name="finance_incident_2_operator[]" required>
                                                        <option value="">Select an option</option>
                                                        <option value="+" @if($third_child->FlagCategoryValues->finance_incident_2_operator == '+') selected @endif>Add</option>
                                                        <option value="-" @if($third_child->FlagCategoryValues->finance_incident_2_operator == '-') selected @endif>Subtract</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--from-input-col-close-->

                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">3rd Finance Incident *</label>
                                                    <input  type="number" min="0" step="0.1" name="finance_incident_3[]" class="form-control" value="{{ $third_child->FlagCategoryValues->finance_incident_3 }}" data-input-type="number" required />
                                                </div>
                                            </div>
                                            <!--from-input-col-close-->

                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">3rd Finance Incident Operator *</label>
                                                    <select class="form-control col-md-7 col-xs-12" name="finance_incident_3_operator[]" required>
                                                        <option value="">Select an option</option>
                                                        <option value="+" @if($third_child->FlagCategoryValues->finance_incident_3_operator == '+') selected @endif>Add</option>
                                                        <option value="-" @if($third_child->FlagCategoryValues->finance_incident_3_operator == '-') selected @endif>Subtract</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--from-input-col-close-->

                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">Finance Conclusion *</label>
                                                    <input  type="number" min="0" step="0.1" name="finance_conclusion[]" value="{{ $third_child->FlagCategoryValues->finance_conclusion }}" class="form-control" data-input-type="number" required />
                                                </div>
                                            </div>
                                            <!--from-input-col-close-->

                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">Finance Conclusion Operator *</label>
                                                    <select class="form-control col-md-7 col-xs-12" name="finance_conclusion_operator[]" required>
                                                        <option value="">Select an option</option>
                                                        <option value="+" @if($third_child->FlagCategoryValues->finance_conclusion_operator == '+') selected @endif>Add</option>
                                                        <option value="-" @if($third_child->FlagCategoryValues->finance_conclusion_operator == '-') selected @endif>Subtract</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--from-input-col-close-->

                                            <!--Finance-incidents-close-->

                                            <!--Rating-heading-open-->
                                            <div class="col-sm-12 col-md-12">
                                                <h4 class="section-heading" >Ratings</h4>
                                            </div>
                                            <!--Rating-heading-close-->
                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">1st Rating *</label>
                                                    <input  type="number" step="0.01" min="0" max="10" name="rating_1[]" class="form-control" value="{{ $third_child->FlagCategoryValues->rating_1 }}" data-input-type="number" required />
                                                </div>
                                            </div>
                                            <!--from-input-col-close-->

                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">1st Rating Operator *</label>
                                                    <select class="form-control col-md-7 col-xs-12" name="rating_1_operator[]" required>
                                                        <option value="">Select an option</option>
                                                        <option value="+" @if($third_child->FlagCategoryValues->rating_1_operator == '+') selected @endif>Add</option>
                                                        <option value="-" @if($third_child->FlagCategoryValues->rating_1_operator == '-') selected @endif>Subtract</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--from-input-col-close-->

                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">2nd Rating *</label>
                                                    <input  type="number" step="0.01" min="0" max="10" name="rating_2[]" class="form-control" value="{{ $third_child->FlagCategoryValues->rating_2 }}" data-input-type="number" required />
                                                </div>
                                            </div>
                                            <!--from-input-col-close-->

                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">2nd Rating Operator *</label>
                                                    <select class="form-control col-md-7 col-xs-12" name="rating_2_operator[]" required>
                                                        <option value="">Select an option</option>
                                                        <option value="+" @if($third_child->FlagCategoryValues->rating_2_operator == '+') selected @endif>Add</option>
                                                        <option value="-" @if($third_child->FlagCategoryValues->rating_2_operator == '-') selected @endif>Subtract</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--from-input-col-close-->

                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">3rd Rating *</label>
                                                    <input  type="number" step="0.01" min="0" max="10" name="rating_3[]" class="form-control" value="{{ $third_child->FlagCategoryValues->rating_3 }}" data-input-type="number" required />
                                                </div>
                                            </div>
                                            <!--from-input-col-close-->

                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">3rd Rating Operator *</label>
                                                    <select class="form-control col-md-7 col-xs-12" name="rating_3_operator[]" required>
                                                        <option value="">Select an option</option>
                                                        <option value="+" @if($third_child->FlagCategoryValues->rating_3_operator == '+') selected @endif>Add</option>
                                                        <option value="-" @if($third_child->FlagCategoryValues->rating_3_operator == '-') selected @endif>Subtract</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--from-input-col-close-->

                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">4th Rating *</label>
                                                    <input  type="number" step="0.01" min="0" max="10" name="rating_4[]" class="form-control" value="{{ $third_child->FlagCategoryValues->rating_4 }}" data-input-type="number" required />
                                                </div>
                                            </div>
                                            <!--from-input-col-close-->

                                            <!--from-input-col-open-->
                                            <div class="col-md-3 col-sm-3 from-input-col">
                                                <div class="form-group">
                                                    <label class="control-label">4th Rating Operator *</label>
                                                    <select class="form-control col-md-7 col-xs-12" name="rating_4_operator[]" required>
                                                        <option value="">Select an option</option>
                                                        <option value="+" @if($third_child->FlagCategoryValues->rating_4_operator == '+') selected @endif>Add</option>
                                                        <option value="-" @if($third_child->FlagCategoryValues->rating_4_operator == '-') selected @endif>Subtract</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--from-input-col-close-->

                                        </div>
                                        <!--incident-input-box-inner-close-->
                                    @endforeach
                                </div>
                            </div>
                            <!--from-input-col-open-->
                            <div class="col-sm-12 text-right ">
                                <div class="form-group">
                                    <button type="submit" class="btn blue" id="save"> Save </button>
                                    <input type="button" class="btn black" name="cancel" id="cancel" value="Cancel">
                                </div>
                            </div>
                        </div>
                        <!--from-input-wrapper-sub-category-close-->

                    </form>
                </div>
            </div>
            <!-- END SAMPLE FORM PORTLET-->
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

        jQuery(document).ready(function() {
            // initiate layout and plugins
            App.init();
            Admin.init();
            /*$(document).ready(function() {
                $('.js-example-basic-multiple').select2();
            });*/

            // add bracket pricing
            /*$('.add-incident-input-box').click(function(){
                // cloning the html
                let incident_html = $('.incident-input-box-org').clone();
                // remove old input
                incident_html.find('.update_incident_input_id').remove();

                //empty inputs
                incident_html.find('input').removeAttr('value');
                incident_html.find('select option').removeAttr('selected');
                console.log(incident_html.find('select'));
                //console.log([incident_html.find('input').removeAttr('value'),incident_html.find('select').append('<option value=>My option</option>')])
                //return false;
                // converting object clone to html
                incident_html = incident_html.html().trim();
                //console.log(incident_html);
                let append_incident_html = '<div class="col-sm-12 incident incident-input-box-inner incident-input-box-appended">  <input type="hidden" class="update_incident_input_id" name="child_ids[]" value="0" /> '+incident_html+' <i class="fa fa-times-circle remove-incident" data-id="0"></i></div>';
                $('.incident-input-box').prepend(append_incident_html);

            });*/

            //return back to index page
            /*$('#cancel').click(function () {
                window.location.href = "{!! URL::route('customer-service.index') !!}";
            });*/


            // removing incident input
            /*$(document).on('click','.remove-incident',function(){
                // checking the bracket pricing already save or currently added
                var id = parseInt($(this).attr('data-id'));
                var el = $(this);
                var confirmation = confirm("Are you sure you want to delete");
                if(confirmation)
                {

                    deleteCategoryValueFromDatabase(el,id);
                }

            });*/

            // ajax function for deleted the bracket from data base
            /* function deleteCategoryValueFromDatabase (el,id) {

                 // checking the id is exist
                 if(id <= 0)
                 {

                     $(el).parent('.incident-input-box-appended').remove();
                     return ;
                 }

                 //show loader
                 showLoader();

                 // sending ajax
                 $.ajax({
                     type: "get",
                     url: "{{ url('customer-service/delete/sub-category') }}",
                    data:{id:id},
                    success: function (response) {

                        //hide loader
                        hideLoader();
                        if(response.status) // removing element
                        {
                            $(el).parent('.incident-input-box-appended').remove();
                        }
                    },
                    error:function (error) {
                        hideLoader();
                        alert('some error please see the console');
                        console.log(error);
                    }
                });

            }*/





            let ViewHandler = {
                "binding_instance":{
                    "cancel_btn":$('#cancel').click(function () {
                        ViewHandler.methods.return_redirect_back();
                    }),
                    "parent_selector_el":$('.Parent-selector')/*.select2({
                        placeholder: "Select an option"
                    })*/,
                    "add_incident_btn": $('.add-incident-input-box').click(function () {
                        ViewHandler.methods.add_grand_child_html();
                        ViewHandler.methods.re_load_second_child_data();
                    }),
                    "remove_incident_btn": $(document).on('click', '.remove-incident', function () {
                        ViewHandler.methods.remove_incident_box(this);
                    }),
                    "add_child_btn": $('.add-child-box').click(function () {
                        ViewHandler.methods.add_child_html();
                    }),
                    "remove_child_btn": $(document).on('click', '.remove-child-input-box', function () {
                        ViewHandler.methods.remove_child_box(this);
                    }),
                    "set_child_data_btn": $(document).on('click', '.set-child-data', function () {
                        ViewHandler.methods.update_second_child_data()
                    }),
                    "select_incident_el": $(document).on('change', '.incident-selection', function () {
                        ViewHandler.methods.forward_selection_handler(this);
                    }),
                    "validate_same_name_btn": $(document).on('submit', 'form', function () {
                        return !ViewHandler.methods.validate_same_name_category();
                    }),
                },
                "el_instance":{
                    "incident_input_box":$('.incident-input-box'),
                    "child_input_outter_wrapper":$('.child-input-outter-wrapper'),
                    "child_input_box_inner":$('.child-input-box-inner'),
                },
                "data": {
                    "server_uid":"{{uniqid('',true)}}",
                    //"incident_box_html":$('.incident-input-box-org').html().trim(),
                    "incident_box_html":$('.incident-input-box-org').clone(),
                    "child_box_clone":$('.child-input-box-inner-org').clone(),
                    "second_child_data":{!! json_encode($second_child_json_data_Set) !!},
                },
                "methods":{
                    "generate_uid":function () {
                        return ViewHandler.data.server_uid+''+Date.now()+''+Math.floor(Math.random() * 101);
                    },
                    "return_redirect_back":function () {
                        window.location.href = "{{route('customer-service.index') }}";
                    },
                    "add_grand_child_html":function (){
                        var incident_html = ViewHandler.data.incident_box_html;
                        //empty inputs
                        incident_html.find('input').removeAttr('value');
                        incident_html.find('select option').removeAttr('selected');
                        incident_html.find('select').val('');
                        //adding new local uid
                        incident_html.find('.grand_child_uid').val(ViewHandler.methods.generate_uid());
                        // converting object clone to html
                        incident_html = incident_html.html().trim();

                        var append_incident_html = '<div class="col-sm-12 incident incident-input-box-inner incident-input-box-appended">' + incident_html + ' <i class="fa fa-times-circle remove-incident"></i></div>';
                        ViewHandler.el_instance.incident_input_box.prepend(append_incident_html);
                    },
                    "add_child_html":function (){
                        // cloning the html
                        var child_box_clone = ViewHandler.data.child_box_clone;
                        //empty inputs
                        child_box_clone.find('input').removeAttr('value');
                        //adding new local uid
                        child_box_clone.find('.child_uid').val(ViewHandler.methods.generate_uid());
                        // converting object clone to html
                        child_box_clone = child_box_clone.html().trim();
                        child_box_clone = '<div class="col-sm-12 incident child-input-box-inner child-input-box-inner-clone">'+child_box_clone+'<i class="fa fa-times-circle remove-child-input-box"></i> </div>';
                        //prepend html
                        ViewHandler.el_instance.child_input_outter_wrapper.prepend(child_box_clone);
                    },
                    "remove_incident_box":function (el){
                        var confirmation = confirm("Are you sure you want to delete");
                        if (confirmation) {
                            $(el).parent('.incident-input-box-appended').remove();
                        }
                    },
                    "remove_child_box":function (el){
                        var confirmation = confirm("Are you sure you want to delete");
                        if (confirmation) {
                            var current_el = $(el);
                            var parent = current_el.parent('.child-input-box-inner');
                            var current_child_cat_local_id = parent.find('.child_uid').val();

                            // removing child option data
                            delete ViewHandler.data.second_child_data[current_child_cat_local_id];

                            // reloading optoins data
                            ViewHandler.methods.re_load_second_child_data();
                            // removing element
                            parent.remove();

                        }
                    },
                    "update_second_child_data":function (){

                        var dataset = {
                            "0":{
                                "local_id":"",
                                "id":0,
                                "text":"Please Select an option",
                            },
                        };

                        var optoins_html = '<option data-id="0" value="" >Please Select an option</option>';
                        var local_id, text,current_el,id;
                        var error = false;
                        var previous_name_data = [];
                        var message = '';
                        var trim_text = '';
                        // removeing old error class
                        $('.child-input-box-inner').removeClass('error');
                        // getting every child category
                        $('.child-input-box-inner').each(function (index){
                            current_el =$(this);
                            local_id = current_el.find('.child_uid').val();
                            text = current_el.find('.child_category_name').val();
                            id = current_el.find('.second_child_id').val();
                            trim_text = text.replace(/\s/g,'');
                            // validating the input is not empty
                            if(text == '')
                            {
                                current_el.addClass('error');
                                message = 'Kindly fill in the name of the second child';
                                error = true;
                            }
                            else if (previous_name_data.includes(trim_text)) // checking the value is not duplicate
                            {
                                current_el.addClass('error');
                                message = 'The parent category name is already exist '+text;
                                error = true;
                            }

                            // updating previous name data
                            previous_name_data.push(trim_text);
                            // creating options
                            optoins_html+='<option data-id="'+id+'" value="'+local_id+'" >'+text+'</option>';
                            // setting every element data to object for setting selection
                            dataset[local_id] = {
                                "local_id":local_id,
                                "id":id,
                                "text":text,
                            };
                        });

                        // checking error
                        if(error)
                        {
                            alert(message);
                            return false;
                        }

                        // updating every parent selction box of grand child select
                        $('.Parent-selector').each(function(){
                            var current_el = $(this);
                            var current_selected_val = current_el.val();

                            current_el.find('option')
                                .remove()
                                .end()
                                .append(optoins_html)
                                .val(current_selected_val)
                        });

                        // setting second child data to main data
                        ViewHandler.data.second_child_data = dataset;

                    },
                    "validate_same_name_category":function (){

                        var text,text_data,current_el;
                        var trim_text = '';
                        var error = false;
                        var previous_name_data = [];
                        $('.sub_category_name').removeClass('error');
                        $('.sub_category_name').each(function (index){
                            current_el = $(this);
                            text = current_el.val();
                            trim_text = text.replace(/\s/g,'');

                            // validating the input is not empty
                            if (previous_name_data.includes(trim_text)) // checking the value is not duplicate
                            {
                                current_el.addClass('error');
                                current_el.val('');
                                text_data = text;
                                error = true;
                            }

                            // updating previous name data
                            previous_name_data.push(trim_text);

                        });

                        // checking error
                        if(error)
                        {
                            alert('The parent category '+text_data+' name is already exist ');
                        }

                        return error;
                    },
                    "re_load_second_child_data":function () {
                        // create options
                        var options_data =  ViewHandler.data.second_child_data

                        var options_html;
                        // looping on old data for relaod all select box
                        for(var optoins_key in options_data)
                        {
                            options_html+='<option data-id="'+options_data[optoins_key].id+'" value="'+options_data[optoins_key].local_id+'" >'+options_data[optoins_key].text+'</option>';
                        }

                        // updating every parent selction box of grand child select
                        $('.Parent-selector').each(function(){

                            var current_el = $(this);
                            var current_selected_val = current_el.val();
                            console.log(current_selected_val);
                            // updating
                            current_el.find('option')
                                .remove()
                                .end()
                                .append(options_html)
                                .val(current_selected_val)

                        });

                    },
                    "forward_selection_handler": function (e) {
                        var select_el = $(e);
                        var index = parseInt(select_el.data('index')) +1;
                        var selection_class_prefix = "incident-selection";
                        var parent_box = select_el.parents('.incident-input-box-inner');
                        var total_selection_length = parent_box.find('.' + selection_class_prefix).length;
                        var current_value_selected = select_el.val();
                        var current_value_selected_text = select_el.find('option:selected').text();

                        // updating selectino value into input
                        select_el.next().val(current_value_selected);

                        // checking the selected value is Terminations or not
                        if(current_value_selected_text == 'Termination')
                        {
                            parent_box.find('.' +selection_class_prefix+'-'+index).val(current_value_selected).trigger('change');
                            parent_box.find('.' + selection_class_prefix+'-'+index).attr("disabled", true);
                        }
                        else
                        {
                            parent_box.find('.' + selection_class_prefix+'-'+index).attr("disabled", false);
                        }

                    }
                },
                "init":function (){
                    // make multi select box selected
                    make_multi_option_selected('.portal-type','{{$selected_portal_types}}');
                    make_multi_option_selected('.order-type','{{$selected_order_type}}');
                    make_multi_option_selected('.vendors','{{$selected_vendors}}');
                    make_multi_option_selected('.is-show-route','{{$selected_is_show_on_route}}');
                    // reload current bindings
                    ViewHandler.methods.re_load_second_child_data();
                    // trigger selection
                    $('.incident-selection').trigger('change');
                },
            };
            ViewHandler.init();


        });



    </script>
@stop
