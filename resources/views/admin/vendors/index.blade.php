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
        {{ Breadcrumbs::render('vendors.index') }}
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- Action buttons Code Start -->
        <div class="row">
            <div class="col-md-12">
                <!-- Add New Button Code Moved Here -->
                <div class="table-toolbar pull-right">
                    <div class="btn-group">
                        {{--@if(HasPermissionAccess($userPermissoins['type'],'add',$userPermissoins['permissions']))
                        <a href="{!! URL::route('vendors.create') !!}" id="sample_editable_1_new" class="btn blue">
                            Add <i class="fa fa-plus"></i>
                        </a>
                        @endif--}}
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
                            <th style="width: 5%" class="text-center">ID</th>
                            <th style="width: 40%" class="text-center">Name</th>
                            <th style="width: 20%" class="text-center">Email</th>
                            <th style="width: 10%" class="text-center">Order Count</th>
                            <th style="width: 10%" class="text-center">Score</th>
                            <th style="width: 5%" class="text-center">Actions</th>
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
<script src="{{ asset('assets/admin/scripts/core/app.js') }}"></script>
<script src="{{ asset('assets/admin/scripts/custom/pages.js') }}"></script>
<script src="{{ asset('assets/admin/scripts/custom/user-administrators.js') }}"></script>
<script>

    jQuery(document).ready(function() {
        // initiate layout and plugins
        App.init();
        Admin.init();
        $('#cancel').click(function() {
            window.location.href = "{!! URL::route('vendors.index') !!}";
        });
    });
    $(function () {
        appConfig.set('yajrabox.ajax', '{{ route('vendors.data') }}');
        appConfig.set('dt.order', [0, 'desc']);
        appConfig.set('yajrabox.ajax.data', function (data) {
            data.status = jQuery('select[name=status]').val();
        });
        appConfig.set('yajrabox.columns', [
            {data: 'id',   orderable: true,   searchable: false, className:'text-center'},
            {data: 'vendor' ,  orderable: true,   searchable: true, className:'text-center'},
            {data: 'email' ,  orderable: true,   searchable: true, className:'text-center'},
            {data: 'order_count', orderable: true,   searchable: true,className:'text-center'},
            {data: 'score' ,  orderable: true,   searchable: true,className:'text-center'},
            {data: 'action',            orderable: false,   searchable: false, className:'text-center'}

        ]);
    })

</script>
@stop
