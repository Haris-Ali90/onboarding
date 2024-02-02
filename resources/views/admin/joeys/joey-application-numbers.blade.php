@extends('admin.layouts.app')

@section('css')
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ URL::to('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{!! URL::to('assets/admin/plugins/select2/select2.css') !!}"/>
    <link rel="stylesheet" type="text/css" href="{!! URL::to('assets/admin/plugins/select2/select2-metronic.css') !!}"/>
    {{--<link rel="stylesheet" href="{!! URL::to('assets/admin/plugins/data-tables/DT_bootstrap.css') !!}"/>--}}
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        .link-wrap {
            position: relative;
        }

        .link {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: block;
        }

        @media only screen and (min-width: 768px) {
            .filter_bar {
                display: flex;
                align-items: center;
                padding: 15px;
            }
        }

        .filter_bar .total_joeys_signup {
        }

        .filter_bar .total_joeys_signup h3 {
            color: #e36d29;
            font-size: 45px;
            margin: 0;
            padding: 0;
            font-weight: bold !important;
        }

        .filter_bar .total_joeys_signup p {
            margin: 0;
            padding: 0;
            font-size: 16px;
        }

        .filter_bar .filter_form {
            margin-left: 20px;
        }

        .filter_bar .filter_form:after {
            content: "";
            display: block;
            clear: both;
        }

        .filter_bar .filter_form > * {
            float: left;
        }

        .filter_bar .filter_form .form-control {
            border: solid 1px #ddd;
            height: 44px;
            border-radius: 100px !important;
            width: 220px;
            padding: 0 15px;
            font-size: 16px;
        }

        .filter_bar .btn {
            cursor: pointer;
            height: 44px;
            margin-left: 8px;
            background: #e36d29;
            color: #fff;
            border: none;
            padding: 0 20px;
            font-size: 16px;
            border-radius: 100px !important;
            font-weight: bold;
        }

        .stages_list {
        }

        .stages_list:after {
            content: "";
            display: block;
            clear: both;
        }

        .stages_list .col {
            padding: 8px 12px;
            width: 20%;
            float: left;
        }

        .stages_list .stage_box {
            padding: 20px 5px 12px;
            box-shadow: 0 4px 16px #e6e6e6;
            border: solid 3px #fff;
            background: #fff;
            text-align: center;
            transition: all 0.2s ease-in-out;
            border-radius: 10px !important;
        }

        .stages_list .stage_box .number {
            color: #e36d29;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 0px;
            line-height: 1em;
        }

        .stages_list .stage_box .label {
            font-size: 15px;
            line-height: 1.4em;
            color: #666666;
            white-space: normal;
            min-height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stages_list .col .stage_box .fa {
            display: none;
        }

        .stages_list .col.active .stage_box {
            border-color: #e36d29 !important;
        }

        .stages_list .col.active .stage_box .fa {
            display: block;
            color: #e36d29;
            position: absolute;
            bottom: -26px;
            left: 50%;
            margin-left: -10px;
            width: 25px;
            height: 25px;
            font-size: 40px;
        }

        .graph_n_data_wrap {
            text-align: center;
            margin-top: 40px;
        }

        .graph_n_data_wrap .total_number {
            color: #e36d29;
            font-size: 32px;
            font-weight: bold;
            margin-top: 12px;
        }

        .graph_n_data_wrap .number_txt {
            margin-top: 15px;
            font-size: 16px;
        }

        .graph_n_data_wrap .number_txt strong {
            color: #e36d29;
        }

        .graph_n_data {
        }

        .loading_wrap {
            display: none;
        }

        .flex-wrapper {
            display: flex;
            flex-flow: row nowrap;
        }

        .single-chart {
            width: 230px;
            margin: 0 auto;
            justify-content: space-around;
        }

        .circular-chart {
            display: block;
            margin: 10px auto;
            max-width: 80%;
            max-height: 250px;
        }

        .circle-bg {
            fill: none;
            stroke: #eee;
            stroke-width: 3.8;
        }

        .circle {
            fill: none;
            stroke-width: 2.8;
            stroke-linecap: round;
            animation: progress 1s ease-out forwards;
        }

        @keyframes progress {
            0% {
                stroke-dasharray: 0 100;
            }
        }

        .circular-chart.orange .circle {
            stroke: #e36d29;
        }

        .percentage {
            fill: #e36d29;
            font-family: sans-serif;
            font-size: 0.5em;
            text-anchor: middle;
        }

        @media only screen and (max-width: 1160px) {
        }

        @media only screen and (max-width: 767px) {
            .stages_list .col {
                width: 50%;
            }

            .filter_bar .total_joeys_signup {
                text-align: center;
                margin-bottom: 12px;
            }

            .stages_list .stage_box {
            }
        }

        @media only screen and (max-width: 480px) {
            .stages_list .col {
                width: 100%;
            }
        }
    </style>
    <!-- END PAGE LEVEL STYLES -->
@stop

@section('content')
    <!-- BEGIN PAGE HEADER-->
    @include('admin.partials.errors')
    @if(auth()->user()->login_type == "micro_hub")
        <!-- BEGIN PAGE HEADER-->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->

                <h3 class="page-title"> Micro Hub Statistics
                    <small></small>
                </h3>
            {{ Breadcrumbs::render('micro-hub.joeys.statistics') }}
            <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div>
        <!-- END PAGE HEADER-->
        <div>
            <!-- Filter bar -->
            <!-- Filter bar -->
            <form method="get" action="">
                <div class="row">
                    <div class="filter_bar">
                        <div class="filter_form">
                            <select name="days" id="days" class="form-control">
                                <option value="lastweek" name="lastweek">Last Week</option>
                                <option value="onemonth" name="onemonth">One Month</option>
                                <option value="sixmonth" name="sixmonth">Six Month</option>
                            </select>
                            <select class="form-control zone" id="zone" name="zone">
                                <option value="">Select Zone</option>
                                @foreach($zone as $zoneData)

                                    <option
                                        value="{{$zoneData->name}}" {{ ($zoneData->name ==  $selectzone)?'selected': '' }}>{{$zoneData->name}}</option>
                                @endforeach
                            </select>

                            <input type="number" class="form-control" id="radius"
                                   value="{{ request()->get('area_radius') }}" name="area_radius"
                                   placeholder="Area Radius">

                            <button type="submit" class="btn btn-primary" id="filter">Filter</button>

                        </div>
                    </div>

                </div>
            </form>
            <!-- signup stages list - [start] -->
            <div class="stages_list">
                <div class="col active">
                    <div class="stage_box link-wrap newMicrohubRequest ">
                        <i class="fa fa-caret-down"></i>
                        <a href="#" class="link" data-id="newMicrohubRequestTable"></a>
                        <div class="number">{{$newHubRequestCount}}</div>
                        <div class="label">Microhub New Request</div>
                    </div>
                </div>
                <div class="col">
                    <div class="stage_box link-wrap">
                        <i class="fa fa-caret-down"></i>
                        <a href="#" class="link" data-id="newActiveMicrohubTable"></a>
                        <div class="number">{{$activatedHubRequestCount}}</div>
                        <div class="label">Microhub New Activated Request</div>
                    </div>
                </div>
                <div class="col">
                    <div class="stage_box link-wrap">
                        <i class="fa fa-caret-down"></i>
                        <a href="#" class="link" data-id="approvedMicrohubTable"></a>
                        <div class="number">{{$approvedHubRequestCount}}</div>
                        <div class="label">Microhub Approved Request</div>
                    </div>
                </div>
                <div class="col">
                    <div class="stage_box link-wrap">
                        <i class="fa fa-caret-down"></i>
                        <a href="#" class="link" data-id="declinedMicrohubTable"></a>
                        <div class="number">{{$declinedHubRequestCount}}</div>
                        <div class="label">Microhub Declined Request</div>
                    </div>
                </div>

            </div>

            <div class="col-md-12">
                <div class="loading_wrap">
                    <img src="<?php echo e(asset('assets/admin/img/giphy.gif')); ?>" alt="">
                </div>
                <div class="data_list_wrap">
                    <div class="portlet-body">
                        <table id="newMicrohubRequestTable"
                               class="table table-striped table-bordered table-hover hidden data-table">
                            <thead>
                            <tr>
                                <th style="width: 5%" class="text-center ">ID</th>
                                <th style="width: 30%" class="text-center ">Name</th>
                                <th style="width: 10%" class="text-center ">Email</th>
                                <th style="width: 10%" class="text-center ">Phone</th>
                                <th style="width: 20%" class="text-center ">Address</th>
                                <th style="width: 20%" class="text-center ">Area Radius</th>
                                <th style="width: 20%" class="text-center ">State</th>
                                <th style="width: 20%" class="text-center ">Zone</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <table id="newActiveMicrohubTable"
                               class="table table-striped table-bordered table-hover hidden data-table">
                            <thead>
                            <tr>
                                <th style="width: 5%" class="text-center ">ID</th>
                                <th style="width: 30%" class="text-center ">Name</th>
                                <th style="width: 10%" class="text-center ">Email</th>
                                <th style="width: 20%" class="text-center ">Phone</th>
                                <th style="width: 10%" class="text-center ">Address</th>
                                <th style="width: 20%" class="text-center ">Area Radius</th>
                                <th style="width: 20%" class="text-center ">State</th>
                                <th style="width: 20%" class="text-center ">Zone</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <table id="approvedMicrohubTable"
                               class="table table-striped table-bordered table-hover hidden data-table">
                            <thead>
                            <tr>
                                <th style="width: 5%" class="text-center ">ID</th>
                                <th style="width: 30%" class="text-center ">Name</th>
                                <th style="width: 10%" class="text-center ">Email</th>
                                <th style="width: 20%" class="text-center ">Phone</th>
                                <th style="width: 10%" class="text-center ">Address</th>
                                <th style="width: 20%" class="text-center ">Area Radius</th>
                                <th style="width: 20%" class="text-center ">State</th>
                                <th style="width: 20%" class="text-center ">Zone</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <table id="declinedMicrohubTable"
                               class="table table-striped table-bordered table-hover hidden data-table">
                            <thead>
                            <tr>
                                <th style="width: 5%" class="text-center ">ID</th>
                                <th style="width: 30%" class="text-center ">Name</th>
                                <th style="width: 10%" class="text-center ">Email</th>
                                <th style="width: 20%" class="text-center ">Phone</th>
                                <th style="width: 10%" class="text-center ">Address</th>
                                <th style="width: 20%" class="text-center ">Area Radius</th>
                                <th style="width: 20%" class="text-center ">State</th>
                                <th style="width: 20%" class="text-center ">Zone</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- BEGIN PAGE CONTENT-->
            <div class="row">
                <div class="col-md-12">
                    <div class="grid-filter">

                        {{--<form method="get" action="">
                            <div class="row">
                                <div class=" col-sm-2">
                                    <label>Email</label>
                                    <input type="text"  id="email"  name="email" class="form-control" value="{{ isset($email)?$email:'' }}">
                                </div>
                                <div class=" col-sm-2">
                                    <label>Status</label>
                                    <select name="status" class="form-control document-status" >
                                        <option value="null">Select Status</option>
                                        <option value="1" {{ ($status ==  1)?'selected': '' }}>Pending</option>
                                        <option value="2" {{ ($status ==  2)?'selected': '' }}>Approved</option>
                                        <option value="3" {{ ($status ==  3)?'selected': '' }}>Declined</option>
                                    </select>
                                </div>
                                <div class="col-md-2 model-input-col">
                                    <label>Zone list</label>

                                    <select class="form-control zone" name="zone">
                                        <option value="">Select Zone</option>
                                        @foreach($zone as $zoneData)

                                            <option value="{{$zoneData->name}}"  {{ ($zoneData->name ==  $selectzone)?'selected': '' }}>{{$zoneData->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class=" col-sm-2">
                                    <button class="btn btn-primary" type="submit" style="margin-top: 25px;">
                                        Go</a> </button>
                                </div>

                            </div>
                        </form>--}}
                    </div>
                    <br>
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet box blue">

                    {{--<div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-list"></i> Micro Hub Statistics
                        </div>
                    </div>--}}

                    <!-- BEGIN PAGE CONTENT DATA-->


                        <!-- End PAGE CONTENT DATA-->


                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
            <!-- END PAGE CONTENT-->

        </div>
    @else
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->

                <h3 class="page-title">{{ $pageTitle ?? '' }}
                    <small></small>
                </h3>
            {{ Breadcrumbs::render('joeys.statistics') }}
            <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div>
        <!-- END PAGE HEADER-->

        <div>
            <!-- Filter bar -->
            <form method="get" action="">
                <div class="filter_bar">
                    <div class="total_joeys_signup">
                        <h3 class="totalSignupNumber">{{$totalSignUps}}</h3>
                        <p>Total Signups</p>
                    </div>
                    <div class="filter_form">
                        <select name="days" id="days" class="form-control">
                            <option value="all" name="all">All</option>
                            <option value="3days" name="3days">Last 3 days</option>
                            <option value="lastweek" name="lastweek">Last week</option>
                            <option value="15days" name="15days">Last 15 days</option>
                            <option value="lastmonth" name="lastmonth">Last Month (July)</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            <!-- signup stages list - [start] -->
            <div class="stages_list">
                <div class="col active">
                    <div class="stage_box link-wrap basicRegistration ">
                        <i class="fa fa-caret-down"></i>
                        <a href="#" class="link" data-id="basicRegistrationTable"></a>
                        <div class="number">{{$basicRegistration}}</div>
                        <div class="label">Basic Registration</div>
                    </div>
                </div>
                <div class="col">
                    <div class="stage_box link-wrap">
                        <i class="fa fa-caret-down"></i>
                        <a href="#" class="link" data-id="totalApplicationSubmissionTable"></a>
                        <div class="number">{{$totalApplicationSubmissionCount}}</div>
                        <div class="label">Application Submission</div>
                    </div>
                </div>
                <div class="col">
                    <div class="stage_box link-wrap">
                        <i class="fa fa-caret-down"></i>
                        <a href="#" class="link" data-id="docSubmissionTable"></a>
                        <div class="number">{{$totalDocumentSubmissionCount}}</div>
                        <div class="label">Document Submission</div>
                    </div>
                </div>
                <div class="col">
                    <div class="stage_box link-wrap">
                        <i class="fa fa-caret-down"></i>
                        <a href="#" class="link" data-id="totalTrainingwatchedTable"></a>
                        <div class="number">{{$totalTrainingwatchedCount}}</div>
                        <div class="label">Training Watched</div>
                    </div>
                </div>
                <div class="col">
                    <div class="stage_box link-wrap">
                        <i class="fa fa-caret-down"></i>
                        <a href="#" class="link" data-id="totalQuizPassedTable"></a>
                        <div class="number">{{$totalQuizPassedCount}}</div>
                        <div class="label">Quiz Passed</div>
                    </div>
                </div>
            </div>
            <!-- signup stages list - [/end] -->

            <div class="graph_n_data_wrap">
                <div class="row graph_n_data">
                    <div class="col-md-4">
                        <div class="graph">

                            <div class="flex-wrapper">
                                <div class="single-chart">
                                    <svg viewBox="0 0 36 36" class="circular-chart orange">
                                        <path class="circle-bg"
                                              d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
                                        />
                                        <path class="circle" id="percentageCircle"
                                              stroke-dasharray="{{$percentage}}, 100"
                                              d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
                                        />
                                        <text x="18" y="20.35" class="percentage">{{$percentage}}%</text>
                                    </svg>
                                </div>
                            </div>

                        </div>
                        <div class="total_number">{{$basicRegistration}}</div>
                        <div class="number_txt">
                            Joeys submitted the documents to verify out of <strong>{{$totalSignUps}}</strong>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="loading_wrap">
                            <img src="<?php echo e(asset('assets/admin/img/giphy.gif')); ?>" alt="">
                        </div>
                        <div class="data_list_wrap">
                            <div class="portlet-body">
                                <table id="basicRegistrationTable"
                                       class="table table-striped table-bordered table-hover hidden data-table">
                                    <thead>
                                    <tr>
                                        <th style="width: 5%" class="text-center ">ID</th>
                                        <th style="width: 30%" class="text-center ">Name</th>
                                        <th style="width: 10%" class="text-center ">Address</th>
                                        <th style="width: 10%" class="text-center ">Email</th>
                                        <th style="width: 20%" class="text-center ">Phone</th>
                                        <th style="width: 20%" class="text-center ">Action</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                                <table id="docSubmissionTable"
                                       class="table table-striped table-bordered table-hover hidden data-table">
                                    <thead>
                                    <tr>
                                        <th style="width: 5%" class="text-center ">ID</th>
                                        <th style="width: 30%" class="text-center ">Name</th>
                                        <th style="width: 10%" class="text-center ">Address</th>
                                        <th style="width: 10%" class="text-center ">Email</th>
                                        <th style="width: 20%" class="text-center ">Phone</th>
                                        <th style="width: 20%" class="text-center ">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                                <table id="totalApplicationSubmissionTable"
                                       class="table table-striped table-bordered table-hover hidden data-table">
                                    <thead>
                                    <tr>
                                        <th style="width: 5%" class="text-center ">ID</th>
                                        <th style="width: 30%" class="text-center ">Name</th>
                                        <th style="width: 10%" class="text-center ">Address</th>
                                        <th style="width: 10%" class="text-center ">Email</th>
                                        <th style="width: 20%" class="text-center ">Phone</th>
                                        <th style="width: 20%" class="text-center ">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                                <table id="totalTrainingwatchedTable"
                                       class="table table-striped table-bordered table-hover hidden data-table">
                                    <thead>
                                    <tr>
                                        <th style="width: 5%" class="text-center ">ID</th>
                                        <th style="width: 30%" class="text-center ">Name</th>
                                        <th style="width: 10%" class="text-center ">Address</th>
                                        <th style="width: 10%" class="text-center ">Email</th>
                                        <th style="width: 20%" class="text-center ">Phone</th>
                                        <th style="width: 20%" class="text-center ">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                                <table id="totalQuizPassedTable"
                                       class="table table-striped table-bordered table-hover hidden data-table">
                                    <thead>
                                    <tr>
                                        <th style="width: 5%" class="text-center ">ID</th>
                                        <th style="width: 30%" class="text-center ">Name</th>
                                        <th style="width: 10%" class="text-center ">Address</th>
                                        <th style="width: 10%" class="text-center ">Email</th>
                                        <th style="width: 20%" class="text-center ">Phone</th>
                                        <th style="width: 20%" class="text-center ">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <input type="hidden" value="{{auth()->user()->login_type}}" name="authType" id="authType">
@stop

@section('footer-js')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script type="text/javascript" src="{{ asset('assets/admin/plugins/select2/select2.min.js') }}"></script>
    {{--   <script type="text/javascript"--}}
    {{--            src="{!! URL::to('assets/admin/plugins/data-tables/jquery.dataTables.js') !!}"></script>--}}
    {{--    <script type="text/javascript" src="{!! URL::to('assets/admin/plugins/data-tables/DT_bootstrap.js') !!}"></script>--}}

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
                window.location.href = "{{route('documents.index') }}";
            });
        });
        let authType = $('#authType').val()
        if (authType == 'micro_hub') {
            $(function () {
                appConfig.set('yajrabox.ajax', '{{ route('micro-hub-data') }}');
                appConfig.set('dt.order', [0, 'desc']);
                appConfig.set('yajrabox.scrollx_responsive', true);
                appConfig.set('yajrabox.ajax.data', function (data) {
                    data.email = jQuery('[name=email]').val();
                    data.zone = jQuery('[name=zone]').val();
                    data.radius = jQuery('[name=area_radius]').val();
                    data.status = jQuery('[name=status]').val();
                });
                appConfig.set('yajrabox.columns', [
                    // {data: 'detail',            orderable: false,   searchable: false, className: 'details-control'},
                    // {data: 'check-box',         orderable: false,   searchable: false, visible: multi},
                    {data: 'id', orderable: true, searchable: true, className: 'text-center'},
                    {data: 'full_name', orderable: true, searchable: true, className: 'text-center'},
                    {data: 'email_address', orderable: true, searchable: true, className: 'text-center'},
                    {data: 'phone_no', orderable: true, searchable: true, className: 'text-center'},
                    {data: 'city', orderable: true, searchable: true, className: 'text-center'},
                    {data: 'own_joeys', orderable: false, searchable: false, className: 'text-center'},
                    {data: 'address', orderable: true, searchable: true, className: 'text-center'},
                    {data: 'city', orderable: false, searchable: false, className: 'text-center'},
                    {data: 'status', orderable: false, searchable: false, className: 'text-center'}
                ]);
            })

            $(function () {

                var totalSignups = $('.total_joeys_signup .totalSignupNumber').text();

                $('.stage_box a').click(function (e) {
                    e.preventDefault();

                    var thisId = $(this).data('id'),

                        thisNumber = $(this).closest('.stage_box').find('.number').text();
                    calculation = Math.round(parseInt(thisNumber) / parseInt(totalSignups) * 100);

                    percentage = (calculation) ? calculation : 0;

                    $('#percentageCircle').attr('stroke-dasharray', percentage + ', 100');
                    $('.percentage').attr('data-test', 'test').text(percentage + '%');
                    console.log('percentage: ', percentage);

                    $('.dataTables_wrapper').addClass('hidden');
                    $('#' + thisId).removeClass('hidden');
                    $('.total_number').text(thisNumber);

                    $('#' + thisId).attr('data-test', 'test');
                    $('#' + thisId).DataTable().destroy();

                    var filterVal = $('#days').val();
                    var zoneVal = $('#zone').val();
                    var radius = $('#radius').val();

                    $('#' + thisId).dataTable().fnDestroy();

                    console.log(zoneVal, "dd");
                    $('#' + thisId).DataTable({
                        ajax: {

                            url: '{{url("micro-hub/joey")}}' + '/' + thisId + '?days=' + filterVal,
                            data: {
                                zoneVal: zoneVal,
                                radius: radius,
                            },
                            type: "GET",
//                        error: function (e) {
//                        },
//                        dataSrc: function (d) {
//                            console.log('d: ', d);
//                            return d
//                        }
                        },
                        pageLength: 10,
                        lengthMenu: [10, 200, 300, 400, 500],
                        "processing": true,
                        "serverSide": true,
                        columns: [
                            {data: 'id'},
                            {data: 'full_name'},
                            {data: 'email_address'},
                            {data: 'phone_no'},
                            {data: 'address'},
                            {data: 'area_radius'},
                            {data: 'state'},
                            {data: 'city'},
                        ]
                    });


                });

                $('.stage_box').on('click', function () {

                    $('.col').removeClass('active');
                    $(this).closest('.col').addClass('active');

                    $('.graph_n_data_wrap .loading_wrap').css('display', 'block');
                    $('.graph_n_data_wrap .data_list_wrap').css('display', 'none');

                    setTimeout(function () {
                        $('.graph_n_data_wrap .loading_wrap').css('display', 'none');
                        $('.graph_n_data_wrap .data_list_wrap').css('display', 'block');
                    }, 1000);
                })
            })
            $('#days').val('<?php echo (isset($_GET['days'])) ? $_GET['days'] : 'lastweek'; ?>');

        } else {
            $(function () {

                var totalSignups = $('.total_joeys_signup .totalSignupNumber').text();

                $('.stage_box a').click(function (e) {
                    e.preventDefault();

                    var thisId = $(this).data('id'),

                        thisNumber = $(this).closest('.stage_box').find('.number').text();
                    calculation = Math.round(parseInt(thisNumber) / parseInt(totalSignups) * 100);

                    percentage = (calculation) ? calculation : 0;

                    $('#percentageCircle').attr('stroke-dasharray', percentage + ', 100');
                    $('.percentage').attr('data-test', 'test').text(percentage + '%');
                    console.log('percentage: ', percentage);

                    $('.dataTables_wrapper').addClass('hidden');
                    $('#' + thisId).removeClass('hidden');
                    $('.total_number').text(thisNumber);

                    $('#' + thisId).attr('data-test', 'test');
                    $('#' + thisId).DataTable().destroy();

                    var filterVal = $('#days').val();
                    $('#' + thisId).dataTable().fnDestroy();

                    console.log(thisId, filterVal);
                    $('#' + thisId).DataTable({
                        ajax: {
                            url: '{{url("micro-hub/joey")}}' + '/' + thisId + '?days=' + filterVal,
                            type: "GET",
//                        error: function (e) {
//                        },
//                        dataSrc: function (d) {
//                            console.log('d: ', d);
//                            return d
//                        }
                        },

                        pageLength: 10,
                        lengthMenu: [10, 200, 300, 400, 500],
                        "processing": true,
                        "serverSide": true,
                        columns: [

                            {data: 'id'},
                            {data: 'first_name'},
                            {data: 'address'},
                            {data: 'email'},
                            {data: 'phone'},
                            {data: 'action'}
                        ]
                    });


                });

                $('.stage_box').on('click', function () {

                    $('.col').removeClass('active');
                    $(this).closest('.col').addClass('active');

                    $('.graph_n_data_wrap .loading_wrap').css('display', 'block');
                    $('.graph_n_data_wrap .data_list_wrap').css('display', 'none');

                    setTimeout(function () {
                        $('.graph_n_data_wrap .loading_wrap').css('display', 'none');
                        $('.graph_n_data_wrap .data_list_wrap').css('display', 'block');
                    }, 1000);
                })
            })
            $('#days').val('<?php echo (isset($_GET['days'])) ? $_GET['days'] : 'all'; ?>');
        }


    </script>
@stop
