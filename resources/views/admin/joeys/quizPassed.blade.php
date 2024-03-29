@extends('admin.layouts.app')

@section('css')
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="{{ URL::to('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{!! URL::to('assets/admin/plugins/select2/select2.css') !!}"/>
<link rel="stylesheet" type="text/css" href="{!! URL::to('assets/admin/plugins/select2/select2-metronic.css') !!}"/>
{{--<link rel="stylesheet" href="{!! URL::to('assets/admin/plugins/data-tables/DT_bootstrap.css') !!}"/>--}}
<!-- END PAGE LEVEL STYLES -->
@stop

@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.partials.errors')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">{{ $pageTitle ?? '' }} <small></small></h3>
        {{ Breadcrumbs::render('joeys.quizPassed') }}
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <div class="grid-filter">
            <form method="get" action="">
                <div class="row">
                    <!-- <div class=" col-sm-3">
                        <label>Joey</label>
                        <select class="js-example-basic-multiple form-control" name="joey" >
                            <option value=""> Select Joey </option>
                            @foreach( $joeys as $joey )
                                <option value="{{ $joey->id }}" {{ ($joey->id ==  $selectjoey)?'selected': '' }}> {{ $joey->first_name.' '.$joey->last_name.' ('.$joey->id.')' }}</option>
                            @endforeach
                        </select>
                    </div> -->
                    <div class="col-md-3 model-input-col">
                        <label>Joeys list</label>
                        <select class="form-control joeys-list" name="joey">
                            <option value="">Select Joeys</option>
                            @foreach($joeys as $joey)
                                <option value="{{$joey->id}}"  {{ ($joey->id ==  $selectjoey)?'selected': '' }}>{{$joey->first_name.' '.$joey->last_name}} {{$joey->id}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class=" col-sm-3">
                        <label>Phone</label>
                        <input type="text" name="phone" value="@if($old_request_data){{trim($old_request_data['phone'])}}@endif" class="form-control" placeholder="Phone Search"/>
                    </div>
                    <div class=" col-sm-3">
                        <label>email</label>
                        <input type="email" name="email" value="@if($old_request_data){{trim($old_request_data['email'])}}@endif" class="form-control" placeholder="Email Search"/>
                    </div>
                    <div class=" col-sm-2">
                        <button class="btn btn-primary" type="submit" style="margin-top: 25px;">
                            Go</a> </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- Action buttons Code Start -->
        <div class="row">
            <div class="col-md-12">
                <!-- Add New Button Code Moved Here -->
                <div class="table-toolbar pull-right">
                    <div class="btn-group">


                    </div>
                </div>
                <!-- Add New Button Code Moved Here -->
            </div>
        </div>
        <!-- Action buttons Code End -->



        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box blue">

            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-list"></i> {{ $pageTitle ?? '' }}
                </div>
            </div>

            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover yajrabox" id="sample_1">
                    <thead>
                        <tr>

                            <th style="width: 5%;min-width: 50px"  class="text-center">ID</th>
                            <th style="width: 25%;min-width: 50px" class="text-center" >Full Name</th>
                            <th style="width: 10%;min-width: 80px" class="text-center" >Email</th>
                            <th style="width: 10%;min-width: 70px" class="text-center">Phone</th>
                            <th style="width: 10%;min-width: 80px" class="text-center" >Work Type</th>
                            <th style="width: 10%;min-width: 80px" class="text-center" >Preferred Zone</th>
                            <th style="width: 10%;min-width: 80px" class="text-center" >Heard From</th>
                            <th style="width: 10%;min-width: 80px" class="text-center" >SignUp Date</th>
                            {{--<th style="width: 10%;min-width: 90px" class="text-center">Sign-Up Steps</th>
                            <th style="width: 10%;min-width: 90px" class="text-center">Documents Verification</th>
                            <th style="width: 10%;min-width: 90px" class="text-center">Training Completion</th>
                            <th style="width: 10%;min-width: 90px" class="text-center">Quiz Verification</th>--}}
           {{--                 <th style="width: 10%;min-width: 70px"  class="text-center">Image</th>

                            <th style="width: 10%"  class="text-center">Actions</th>--}}
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop

@section('footer-js')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="{{ asset('assets/admin/plugins/select2/select2.min.js') }}"></script>
{{--<script type="text/javascript" src="{!! URL::to('assets/admin/plugins/data-tables/jquery.dataTables.js') !!}"></script>
<script type="text/javascript" src="{!! URL::to('assets/admin/plugins/data-tables/DT_bootstrap.js') !!}"></script>--}}
<!-- END PAGE LEVEL PLUGINS -->
<script src="{{ asset('assets/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset('assets/admin/scripts/core/app.js')}}"></script>
<script src="{{ asset('assets/admin/scripts/custom/pages.js')}}"></script>
<script src="{{ asset('assets/admin/scripts/custom/user-administrators.js')}}"></script>
<script>

    jQuery(document).ready(function() {
        // initiate layout and plugins
        App.init();
        Admin.init();
        $('#cancel').click(function() {
            window.location.href = "{!! URL::route('joeys-list.index') !!}";
        });
    });
    $(function () {
        appConfig.set('yajrabox.ajax', '{{ route('joeys.quizPassedData') }}');
        appConfig.set('dt.order', [0, 'desc']);
        appConfig.set('yajrabox.scrollx_responsive',true);
        appConfig.set('yajrabox.ajax.data', function (data) {
            data.joey = jQuery('[name=joey]').val();
            data.phone = jQuery('[name=phone]').val();
            data.email = jQuery('[name=email]').val();
        });
        appConfig.set('yajrabox.columns', [
           {data: 'id',   orderable: true,   searchable: true, className:'text-center'},
//            {data: 'DT_RowIndex', orderable: false, searchable: false, className:'text-center'},
            {data: 'first_name',   orderable: true,   searchable: true, className:'text-center'},
            {data: 'email',            orderable: true,   searchable: true, className:'text-center'},
            {data: 'phone',         orderable: true,    searchable: true, className:'text-center'},
            {data: 'work_type',         orderable: true,    searchable: true, className:'text-center'},
            {data: 'preferred_zone',         orderable: true,    searchable: true, className:'text-center'},
            {data: 'hear_from',         orderable: true,    searchable: true, className:'text-center'},
            {data: 'created_at',         orderable: true,    searchable: true, className:'text-center'},
          /*  {data: 'is_active',         orderable: true,    searchable: true, className:'text-center'},
            {data: 'document_verification',orderable: true,    searchable: true, className:'text-center'},
            {data: 'training_completion',orderable: true,    searchable: true, className:'text-center'},
            {data: 'quiz_completion',orderable: true,    searchable: true, className:'text-center'},*/
/*            {data: 'image',         orderable: true,    searchable: true, className:'text-center'},
            {data: 'action',            orderable: false,   searchable: false, className:'text-center'}*/

        ]);
    })
//for joeys list
    $(document).ready(function () {
        $('.joeys-list').select2({
            minimumInputLength: 2,
            placeholder: "Search a joey",
            allowClear: true,
        });

    });
</script>
@stop
