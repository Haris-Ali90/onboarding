@extends('admin.layouts.app')

@section('css')
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="{{ URL::to('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{!! URL::to('assets/admin/plugins/select2/select2.css') !!}"/>
<link rel="stylesheet" type="text/css" href="{!! URL::to('assets/admin/plugins/select2/select2-metronic.css') !!}"/>
{{--<link rel="stylesheet" href="{!! URL::to('assets/admin/plugins/data-tables/DT_bootstrap.css') !!}"/>--}}
<!-- END PAGE LEVEL STYLES -->
    <style>
        .dt-buttons button {
            background-color: #bbd82b;
        }
    </style>
@stop

@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.partials.errors')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">{{ $pageTitle ?? '' }} <small></small></h3>
        {{ Breadcrumbs::render('newSignUpJoeys.index') }}
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
                    <div class=" col-sm-3">
                        <label>Start Date</label>
                        <input type="date" name="start_date" value="@if($old_request_data){{trim($old_request_data['start_date'])}}@endif" class="form-control"/>
                    </div>
                    <div class=" col-sm-3">
                        <label>End Date</label>
                        <input type="date" name="end_date" value="@if($old_request_data){{trim($old_request_data['end_date'])}}@endif" class="form-control"/>
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
                <table class="table table-striped table-bordered table-hover new-signup-joeys-table">
                    <thead>
                        <tr>

                           {{-- <th style="width: 5%;min-width: 50px"  class="text-center">ID</th>--}}
                            <th style="width: 5%;min-width: 50px"  class="text-center">ID</th>
                            <th style="width: 25%;min-width: 50px" class="text-center" >Name</th>
                            <th style="width: 10%;min-width: 80px" class="text-center" >Email</th>
                            <th style="width: 10%;min-width: 70px" class="text-center">Phone</th>
                            <th style="width: 10%;min-width: 80px" class="text-center" >Work Type</th>
                            <th style="width: 10%;min-width: 80px" class="text-center" >Preferred Zone</th>
                            <th style="width: 10%;min-width: 80px" class="text-center" >Heard From</th>
                            <th style="width: 10%;min-width: 80px" class="text-center" >Signup Date</th>
                            <th style="width: 10%;min-width: 80px" class="text-center" >ID</th>
                            <th style="width: 10%;min-width: 80px" class="text-center" >Referral Name</th>
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
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
{{--<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>--}}
<script>

    jQuery(document).ready(function() {
        // initiate layout and plugins
        App.init();
        Admin.init();
        $('#cancel').click(function() {
            window.location.href = "{!! URL::route('joeys-list.index') !!}";
        });
    });
    var filter_date = '{{date("Y-m-d")}}';
    // dwonload btn clcik genete file name
    function getExportFileName() {
        // setting up filter date
        var filter_start_date = ($('input[name="start_date"]').val() == "")? filter_date : $('input[name="start_date"]').val();
        var filter_start_end = ($('input[name="end_date"]').val() == "")? filter_date : $('input[name="end_date"]').val();
        return 'New Signup Joeys '+filter_start_date+" to "+filter_start_end;

    };

    /*$(function () {
        appConfig.set('yajrabox.ajax', '{{ route('newSignUpJoeys.data') }}');
        appConfig.set('dt.order', [0, 'desc']);
        appConfig.set('yajrabox.scrollx_responsive',true);
        appConfig.set('yajrabox.ajax.data', function (data) {
            data.start_date = jQuery('[name=start_date]').val();
            data.end_date = jQuery('[name=end_date]').val();
        });
        appConfig.set('yajrabox.buttons', [{
                extend:     'excel',
                text:       'CSV Export',
                filename:function () { return getExportFileName();},
            }]
        );
        appConfig.set('yajrabox.columns', [
            {data: 'id',   orderable: true,   searchable: true, className:'text-center'},
            {data: 'first_name',   orderable: true,   searchable: true, className:'text-center'},
            {data: 'email',            orderable: true,   searchable: true, className:'text-center'},
            {data: 'phone',         orderable: true,    searchable: true, className:'text-center'},
            {data: 'work_type',         orderable: true,    searchable: true, className:'text-center'},
            {data: 'preferred_zone',         orderable: false,    searchable: false, className:'text-center'},
            {data: 'hear_from',         orderable: true,    searchable: true, className:'text-center'},
            {data: 'created_at',         orderable: true,    searchable: true, className:'text-center'},
            {data: 'referral_id',         orderable: false,    searchable: false, className:'text-center'},
            {data: 'referral_name',         orderable: false,    searchable: false, className:'text-center'},

        ]);
    })*/
    $DataTable = $('.new-signup-joeys-table').DataTable({
        order: [[ 0, 'desc']],
        searching: true,
        select: false,
        pageLength: 250,
        autoWidth: false,
        scrollX: true,   // enables horizontal scrolling,
        scrollCollapse: true,
        fixedColumns: true,
        dom: 'Bflrtip',
        buttons:    [{
            extend: 'excel',
            text:       'Export To Excel',
            filename:function () { return getExportFileName();},
        }/*,{
            extend: 'csv',
            text:       'CSV',
            filename:function () { return getExportFileName();},
        }*/],
        //lengthMenu: [250,500,750,1000],
        serverSide: true,
        processing: true,
        ajax: {
            url: '{{ route('newSignUpJoeys.data') }}',
            data: function(data) {
                data.start_date = jQuery('[name=start_date]').val();
                data.end_date = jQuery('[name=end_date]').val();
            },
        },
        columns: [
            {data: 'id',   orderable: true,   searchable: true, className:'text-center'},
            {data: 'first_name',   orderable: true,   searchable: true, className:'text-center'},
            {data: 'email',            orderable: true,   searchable: true, className:'text-center'},
            {data: 'phone',         orderable: true,    searchable: true, className:'text-center'},
            {data: 'work_type',         orderable: true,    searchable: true, className:'text-center'},
            {data: 'preferred_zone',         orderable: false,    searchable: false, className:'text-center'},
            {data: 'hear_from',         orderable: true,    searchable: true, className:'text-center'},
            {data: 'created_at',         orderable: true,    searchable: true, className:'text-center'},
            {data: 'referral_id',         orderable: false,    searchable: false, className:'text-center'},
            {data: 'referral_name',         orderable: false,    searchable: false, className:'text-center'},

        ]
    });

</script>
@stop
