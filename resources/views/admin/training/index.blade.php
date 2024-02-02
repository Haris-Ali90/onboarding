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
        {{ Breadcrumbs::render('training.index') }}
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
                        <label>Type</label>
                        <select class="js-example-basic-multiple form-control" name="training_type" >
                            <option value=""> Select Type </option>
                            @foreach( $training_type as $training )
                                <option value="{{ $training->type }}" {{ ($training->type ==  $selectTrainingType)?'selected': '' }}> {{ $training->type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class=" col-sm-3">
                        <label>Category</label>
                        <select class="js-example-basic-multiple form-control" name="training_category" >
                            <option value=""> Select Category </option>
                            @foreach( $training_category as $category )
                                <option value="{{ $category->id }}" {{ ($category->id ==  $selectTrainingCategory)?'selected': '' }}> {{ $category->name }}</option>
                            @endforeach
                        </select>
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
                       {{-- @if(HasPermissionAccess($userPermissoins['type'],'add',$userPermissoins['permissions']))
                        <a href="{!! URL::route('training.create') !!}" id="sample_editable_1_new" class="btn blue">
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
        <div class="row">
            <div class="col-sm-12 mb-5 text-left " style="margin-bottom: 10px">
                @if(can_access_route('training.create',$userPermissoins))
                    <a href="{!! URL::route('training.create') !!}" style="background-color: #e36d29" title="create"
                       class="btn btn-sm btn-primary">
                        <i class="fa fa-add">CREATE </i>
                    </a>

                @endif
            </div>
        </div>
        <div class="portlet box blue">

            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-list"></i>  {{ $pageTitle ?? '' }}
                </div>
            </div>

            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover yajrabox" id="sample_1">
                    <thead>
                        <tr>
                            <th style="width: 5%;min-width: 70px" class="text-center" >ID</th>
                            <th style="width: 30%;min-width: 70px" class="text-center" >File Name</th>
                            <th style="width: 30%;min-width: 70px" class="text-center" >Title</th>
                            <th style="width: 5%;min-width: 70px" class="text-center" > File type</th>
                            <th style="width: 25%;min-width: 60px" class="text-center" >Category</th>
                            <th style="width: 25%;min-width: 150px" class="text-center" >Mandatory/Optional</th>
                        {{--    <th style="width: 25%" class="text-center" > Vendor</th>--}}
                            <th style="width: 5%;min-width: 70px" class="text-center" > Preview</th>
                            <th style="width: 5%;min-width: 70px" class="text-center">Actions</th>
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
            window.location.href = "{!! URL::route('training.index') !!}";
        });
    });
    $(function () {
        appConfig.set('yajrabox.ajax', '{{ route('training.data') }}');
        appConfig.set('dt.order', [0, 'desc']);
        appConfig.set('yajrabox.scrollx_responsive',true);
        appConfig.set('yajrabox.ajax.data', function (data) {
            data.training_type = jQuery('[name=training_type]').val();
            data.training_category = jQuery('[name=training_category]').val();
        });
        appConfig.set('yajrabox.columns', [
            {data: 'id',   orderable: true,   searchable: true, className:'text-center'},
            {data: 'name',   orderable: true,   searchable: true, className:'text-center'},
            {data: 'title',   orderable: true,   searchable: true, className:'text-center'},
            {data: 'type',         orderable: true,    searchable: true, className:'text-center'},
            {data: 'orderCategory',name:'orderCategory.name',  orderable: true,   searchable: true, className:'text-center'},
            {data: 'mandatory', name:'trainings.is_compulsory',  orderable: true,    searchable: true, className:'text-center'},
     /*       {data: 'vendors',  name:'vendors.first_name',     orderable: true,    searchable: true, className:'text-center'},*/
            {data: 'link',         orderable: false,    searchable: false, className:'text-center'},
            {data: 'action',            orderable: false,   searchable: false, className:'text-center'}

        ]);
    })

</script>
@stop
