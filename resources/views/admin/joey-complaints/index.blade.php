@extends('admin.layouts.app')

@section('css')
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ URL::to('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{!! URL::to('assets/admin/plugins/select2/select2.css') !!}"/>
    <link rel="stylesheet" type="text/css" href="{!! URL::to('assets/admin/plugins/select2/select2-metronic.css') !!}"/>
{{--    <link rel="stylesheet" href="{!! URL::to('assets/admin/plugins/data-tables/DT_bootstrap.css') !!}"/>--}}

    <!-- END PAGE LEVEL STYLES -->
@stop
@section('content')
    <!-- BEGIN PAGE HEADER-->
    @include('admin.partials.errors')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle }} <small></small></h3>
        {{ Breadcrumbs::render('joeys-complaints.index') }}
        <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <div class="box-body row col-sm-9">


                <div class="clearfix"></div>

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
                                <label class="control-label">Select Joey</label>
                                <select class="form-control joeys-list" name="joey">
                                    <option value="">Select Joeys</option>
                                    @foreach($joeys as $joey)
                                        <option value="{{$joey->id}}" {{ ($joey->id ==  $selectjoey)?'selected': '' }}>{{$joey->first_name.' '.$joey->last_name}} {{$joey->id}}</option>
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
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box blue">

                <div class="portlet-title">
                    <div class="caption">
                        {{ $pageTitle }}
                    </div>
                </div>

                <div class="portlet-body">
                    <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover yajrabox" id="sample_1">
                        <thead>
                        <tr>
                            {{--<th style="width: 5%" class="text-center ">ID</th>--}}
                            <th style="width: 5%" class="text-center ">Joey Id</th>
                            <th style="width: 10%" class="text-center ">Joey Name</th>
                            <th style="width: 45%" class="text-center ">Complaints</th>
                            <th style="width: 20%" class="text-center ">Types</th>
                            <th style="width: 20%" class="text-center ">Complaint Status</th>
                            <th style="width: 20%" class="text-center ">Created at</th>
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
{{--   <script type="text/javascript"--}}
{{--            src="{!! URL::to('assets/admin/plugins/data-tables/jquery.dataTables.js') !!}"></script>--}}
{{--    <script type="text/javascript" src="{!! URL::to('assets/admin/plugins/data-tables/DT_bootstrap.js') !!}"></script>--}}

    <script src="{{ URL::to('assets/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::to('assets/admin/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::to('assets/admin/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{!! URL::to('assets/admin/scripts/core/app.js') !!}"></script>
    <script src="{!! URL::to('assets/admin/scripts/custom/user-administrators.js') !!}"></script>

    <script>

        jQuery(document).ready(function() {
            // initiate layout and plugins
            App.init();
            Admin.init();
            $('#cancel').click(function() {
                window.location.href = "{!! URL::route('dashboard.index') !!}";
            });

            //appConfig.set( 'dt.searching', true );
/*            $('#joeyComplaints').DataTable( {
                scrollX: true,   // enables horizontal scrolling,
                scrollCollapse: true,
                columnDefs: [
                    { width: '20%', targets: 0 }
                ],
                fixedColumns: true,
            } );*/

        });

        $(function () {
            appConfig.set('yajrabox.ajax', '{{ route('joeys-complaints.data') }}');
            appConfig.set('dt.order', [0, 'desc']);
            appConfig.set('yajrabox.ajax.data', function (data) {
                data.id = jQuery('[name=id]').val();
                data.joey = jQuery('[name=joey]').val();
                data.type = jQuery('[name=type]').val();
            });
            appConfig.set('yajrabox.columns', [
                {data: 'joey_id', orderable: true,   searchable: true, className:'text-center'},
                {data: 'first_name', orderable: true,   searchable: true, className:'text-center'},
                {data: 'Complaints',   orderable: true,   searchable: true,className:'text-center'},
                {data: 'Type',   orderable: true,   searchable: true,className:'text-center'},
                {data: 'status',            orderable: false,   searchable: false, className:'text-center'},
                {data: 'created_at',            orderable: true,   searchable: true, className:'text-center'}

            ]);
        })

        $(document).on('change','.complaint-status-change',function () {
            //let old_selected_val = ($(this).attr('data-selected-val') >= 0) ? $(this).attr('data-selected-val') : 0 ;
            let selected_val = $(this).val();
            let Id = $(this).attr('id');
            console.log(selected_val,Id)
            let confirm_val = confirm("Are you sure you want to change the status?");

            // send ajax request if confirm
            if(confirm_val)
            {
                $.ajax({
                    type: "GET",
                    url: "joeys-complaints/status/update",
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
                alert('aaa');
                //$(this).val(old_selected_val);
            }
        });
        $(document).ready(function () {
            $('.joeys-list').select2({
                minimumInputLength: 2,
                placeholder: "Search a joey",
                allowClear: true,
            });

        });

    </script>
@stop
