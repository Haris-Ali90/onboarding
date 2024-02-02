@extends('admin.layouts.app')

@section('css')

    <link href="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/plugins/select2/select2-metronic.css') }}"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <!-- END PAGE LEVEL STYLES -->
@stop
@section('content')
    <!-- BEGIN PAGE HEADER-->
    @include('admin.partials.errors')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle }} <small></small></h3>
        {{ Breadcrumbs::render('job-type.index') }}
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
                          {{--  @if(HasPermissionAccess($userPermissoins['type'],'add',$userPermissoins['permissions']))
                            <a href="{{route('job-type.create') }}" id="sample_editable_1_new"
                               class="btn blue">
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
                        <i class="fa fa-list"> </i>{{ $pageTitle }}
                    </div>
                </div>

                <div class="portlet-body">

                    {{--
                    <div class="table-toolbar">
                        <div class="btn-group">
                            <a href="{!! URL::route('admin.users.create') !!}" id="sample_editable_1_new" class="btn green">
                            Add New <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    --}}


                    <table class="table table-striped table-bordered table-hover yajrabox" id="sample_1">
                        <thead>
                        <tr>
                            <th style="width: 5%" class="text-center ">ID</th>
                            <th style="width: 55%" class="text-center">Title</th>

                            <th style="width: 25%">Created Date</th>
                           <th style="width: 10%" class="text-center">Actions</th>
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
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>

    <script src="http://localhost/project/vendor/yajra/laravel-datatables-buttons/src/resources/assets/buttons.server-side.js"></script>
    <script>

        jQuery(document).ready(function() {
            // initiate layout and plugins
            App.init();
            Admin.init();
            $('#cancel').click(function() {
                window.location.href = "{{route('dashboard.index') }}";
            });
        });


      $(function () {
          appConfig.set('yajrabox.ajax', '{{ route('job-type.data') }}');
          appConfig.set('dt.order', [0, 'desc']);
          appConfig.set('yajrabox.ajax.data', function (data) {
              data.status = jQuery('select[name=status]').val();
          });
          appConfig.set('yajrabox.columns', [
              // {data: 'detail',            orderable: false,   searchable: false, className: 'details-control'},
              // {data: 'check-box',         orderable: false,   searchable: false, visible: multi},
              {data: 'id',   orderable: true,   searchable: true, className:'text-center'},
              {data: 'title',   orderable: true,   searchable: true , className:'text-center' },

                {data: 'created_at',        orderable: true,    searchable: false, className:'text-center'},
              {data: 'action',            orderable: false,   searchable: false, className:'text-center'}
          ]);
      })

    </script>
@stop
