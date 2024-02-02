@extends('admin.layouts.app')

@section('css')
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="{{ URL::to('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{!! URL::to('assets/admin/plugins/select2/select2.css') !!}"/>
<link rel="stylesheet" type="text/css" href="{!! URL::to('assets/admin/plugins/select2/select2-metronic.css') !!}"/>
<link href="{{ URL::to('assets/admin/plugins/chosen/chosen.css') }}" rel="stylesheet">
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

        {{ Breadcrumbs::render('joey-document-verification.expiredDocument') }}
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- Action buttons Code Start -->
        <div class="col">
            <div class="col-md-12">
                <!-- Add New Button Code Moved Here -->
          {{--      <div class="table-toolbar pull-right">
                    <div class="btn-group">
                        <a href="{!! URL::route('admin.joeyDocumentVerification.create') !!}" id="sample_editable_1_new" class="btn blue">
                            Add <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>--}}
                <!-- Add New Button Code Moved Here -->



            </div>
        </div>

        <div class="box-body row col-sm-9">


            <div class="clearfix"></div>

            <div class="grid-filter">
                <form method="get" action="">
                    <div class="row">
                        <div class=" col-sm-2">
                            <label>Joey Id</label>
                           <input type="text"  id="joeyID"  name="id" class="form-control" value="{{ isset($id)?$id:'' }}">
                        </div>
                        <div class=" col-sm-2">
                            <label>Status</label>
                            <select name="documentStatus" class="form-control document-status" >
                                <option value="">Select Status</option>
                                <option value="0">Pending</option>
                                <option value="1">Approved</option>
                                <option value="2">Rejected</option>
                            </select>
                        </div>
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
                            <label>Type</label>
                                <select name="type" class="form-control" >
                                    <option value="">Select Type</option>
                                    @foreach( $joey_document as $doc )
                                        <option value="{{ $doc }}" {{ ($doc ==  $type)?'selected': '' }}> {{ucwords(str_replace('_',' ',$doc))}}</option>
                                    @endforeach
                                    {{--<option value="driver_permit" {{ ($type ==  'driver_permit')?'selected': '' }}>Driver's Permit</option>
                                    <option value="driver_license" {{ ($type ==  'driver_license')?'selected': '' }}>Driver's license</option>
                                    <option value="study_permit" {{ ($type ==  'study_permit')?'selected': '' }}>Study Permit</option>
                                    <option value="vehicle_insurance" {{ ($type ==  'vehicle_insurance')?'selected': '' }}>Vehicle Insurance</option>
                                    <option value="additional_document_1" {{ ($type ==  'additional_document_1')?'selected': '' }}>Additional Document 1</option>
                                    <option value="additional_document_2" {{ ($type ==  'additional_document_2')?'selected': '' }}>Additional Document 2</option>
                                    <option value="additional_document_3" {{ ($type ==  'additional_document_3')?'selected': '' }}>Additional Document 3</option>
                                    <option value="8" {{ ($type ==  'sin')?'selected': 'sin' }}>Sin</option>--}}

                                </select>
                        </div>


                        <div class=" col-sm-2">
                            <button class="btn btn-primary" type="submit" style="margin-top: 25px;">
                                Go</a> </button>
                        </div>

                    </div>
                </form>
            </div>

            <div class="clearfix"></div>

            <br>


        </div>

        <!-- Action buttons Code End -->

        <div class="clearfix"></div>

        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box blue">

            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-list"></i> {{ $pageTitle ?? '' }}
                </div>
            </div>

            <div class="portlet-body">
                <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover yajrabox" id="sample_1">
                    <thead>
                        <tr>
                            <th style="width: 5%;"  class="text-center">ID</th>
                            <th style="width: 25%" >Joey Name</th>

                            <th style="width: 20%" >Type</th>
                            <th style="width: 10%" class="text-center">Document</th>
                             <th style="width: 15%" class="text-center">Expiry Date </th>
                            <th style="width: 15%" class="text-center">Status</th>
                            {{--<th style="width: 10%"  class="text-center">Actions</th>--}}
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop

@section('footer-js')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="{!! URL::to('assets/admin/plugins/select2/select2.min.js') !!}"></script>
{{--<script type="text/javascript" src="{!! URL::to('assets/admin/plugins/data-tables/jquery.dataTables.js') !!}"></script>
<script type="text/javascript" src="{!! URL::to('assets/admin/plugins/data-tables/DT_bootstrap.js') !!}"></script>--}}
<!-- END PAGE LEVEL PLUGINS -->
<script src="{{ asset('assets/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/chosen/chosen.jquery.min.js') }}"></script>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset('assets/admin/scripts/core/app.js')}}"></script>
<script src="{{ asset('assets/admin/scripts/custom/pages.js') }}"></script>
<script src="{{ asset('assets/admin/scripts/custom/user-administrators.js') }}"></script>
<script>

    jQuery(document).ready(function() {
        // initiate layout and plugins
        App.init();
        Admin.init();

    });
    $(function () {
        {{--appConfig.set('yajrabox.ajax', '{{ route('joey-document-verification.data',$type ?? '') }}');--}}
        appConfig.set('yajrabox.ajax', '{{ route('joey-expired-document.data') }}');
        //appConfig.set('dt.order', [0, 'desc']);
        //appConfig.set('yajrabox.scrollx_responsive',true);
        appConfig.set('yajrabox.ajax.data', function (data) {
            data.id = jQuery('[name=id]').val();
            data.joey = jQuery('[name=joey]').val();
            data.type = jQuery('[name=type]').val();
            data.documentStatus = jQuery('[name=documentStatus]').val();
        });

        appConfig.set('yajrabox.columns', [
            {data: 'id',   orderable: false,   searchable: false, className:'text-center'},
          //  {data: 'DT_RowIndex', orderable: false, searchable: false, className:'text-center'},
            {data: 'joeyDetail', name:'joeyDetail.first_name',  orderable: false,   searchable: false, className:'text-center' },

            {data: 'document_type'   ,   orderable: false,    searchable: false },
            {data: 'document_data',         orderable: false,    searchable: false, className:'text-center'},
            {data: 'exp_date',         orderable: false,    searchable: false, className:'text-center'},
            {data: 'is_approved',         orderable: false,    searchable: false, className:'text-center'},
            /*{data: 'action',            orderable: false,   searchable: false, className:'text-center'}*/

        ]);
    })


    $(document).on('change','.doc-status-change',function () {
       let old_selected_val = ($(this).attr('data-selected-val') >= 0) ? $(this).attr('data-selected-val') : 0 ;
       let selected_val = $(this).val();
       let Id = $(this).attr('id');

       let confirm_val = confirm("Are you sure you want to change the status?");

       // send ajax request if confirm
        if(confirm_val)
        {
            $.ajax({
                type: "GET",
                url: "joeyDocumentVerification/status/update/statusUpdate",
                data:{id:Id,status:selected_val},
                success: function(data){
                    location.reload();
                },error:function (error){
                    alert('Alert Error view console');
                    console.log(error);

                }
            });
        }
        else
        {
            location.reload();
            $(this).val(old_selected_val);
        }
    });


    var sin_pattern = /^[0123456789]+$/;
    //checking input value with match on keyup
    $(document).on('keyup', '#joeyID', function (e) {

        let value = $(this).val();
        if (!$(this).val().match(sin_pattern)) {
            console.log(value);
            // checking the length
            if (value.length > 0) {
                $(this).val(value.slice(0, -1));
            }
        }
    });

    //checking input value with match on keydown
    $(document).on('keydown', '#joeyID', function (e) {

        let value = $(this).val();
        if (!$(this).val().match(sin_pattern)) {
            // checking the length
            if (value.length > 0) {
                $(this).val(value.slice(0, -1));
            }
        }
    });
    //for joeys list
    $(document).ready(function () {
        $('.joeys-list').select2({
            minimumInputLength: 2,
            placeholder: "Search a joey",
            allowClear: true,
        });

    });
    make_option_selected('.document-status','{{ old('documentStatus',$selectStatus) }}');
</script>
@stop
