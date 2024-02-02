@extends('admin.layouts.app')

@section('css')
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/select2/select2-metronic.css') }}"/>
    <!-- END PAGE LEVEL STYLES -->
@stop
@section('content')

    <!-- BEGIN PAGE HEADER-->
    @include('admin.partials.errors')

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle }} <small></small></h3>
        {{ Breadcrumbs::render('flag-incident.index') }}
        <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-12 mb-5 text-left " style="margin-bottom: 10px">
                    @if(can_access_route('flag-incident.create',$userPermissoins))
                        <a href="{!! URL::route('flag-incident.create') !!}" style="background-color: #e36d29" title="create"
                           class="btn btn-sm btn-primary">
                            <i class="fa fa-add">CREATE </i>
                        </a>

                    @endif
                </div>
            </div>
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box blue">

                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-list"></i> {{ $pageTitle }}
                    </div>
                </div>

                <div class="portlet-body">
                    <!--Table Open-->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover datatable">
                            <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Incident Name</th>
                                <th class="text-center">Days Duration</th>
                                <th class="text-center">Enable / Disable</th>
                                <th class="text-center">Created At</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            @foreach($flagIncidentValue as $index  => $flagIncident)
                                <tr>
                                    <td class="text-center">{{$flagIncident->id}}</td>
                                    <td class="text-center">{{$flagIncident->name}}</td>
                                    <td class="text-center">{{$flagIncident->days_duration}}</td>
                                    <td class="text-center">
                                        @if(can_access_route(['flag-incident.isEnable','flag-incident.isDisable'],$userPermissoins))

                                                @if($flagIncident->is_active == 1)
                                                    <a  class="btn btn-xs remove-incident" type="button" data-toggle="modal"
                                                        data-target="#statusModal{{$flagIncident->id}}">
                                                        <span class="label label-success">Enable</span>
                                                    </a>
                                                    <div id="statusModal{{$flagIncident->id}}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                                                aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title">Confirm Status?</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to disable?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                                                                    {!! Form::model($flagIncident, ['method' => 'get',  'url' => 'flag-incident/inactive/'.$flagIncident->id, 'class' =>'form-inline form-edit']) !!}
                                                                    {!! Form::hidden('id', $flagIncident->id) !!}
                                                                    {!! Form::submit('Yes', ['class' => 'btn btn-success btn-flat']) !!}
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <a  class="btn btn-xs remove-incident" type="button" data-toggle="modal"
                                                        data-target="#statusModal{{$flagIncident->id}}">
                                                        <span class="label label-warning">Disable</span>
                                                    </a>
                                                    <div id="statusModal{{$flagIncident->id}}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                                                aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title">Confirm Status?</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to enable?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                                                                    {!! Form::model($flagIncident, ['method' => 'get',  'url' => 'flag-incident/active/'.$flagIncident->id, 'class' =>'form-inline form-edit']) !!}
                                                                    {!! Form::hidden('id', $flagIncident->id) !!}
                                                                    {!! Form::submit('Yes', ['class' => 'btn btn-success btn-flat']) !!}
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                        @endif
                                    </td>
                                    <td class="text-center">{{$flagIncident->created_at}}</td>
                                    <td class="text-center">

                                        @if(can_access_route('flag-incident.edit',$userPermissoins))
                                                <a href="{!! URL::route('flag-incident.edit', $flagIncident->id) !!}" data-toggle="tooltip" data-placement="bottom" title="Edit"
                                                   class="btn btn-xs btn-primary">
                                                    <i class="fa fa-pencil-square"></i>
                                                </a>
                                            @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <!--Table Close-->
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

            appConfig.set( 'dt.searching', true );
        });

    </script>
@stop
