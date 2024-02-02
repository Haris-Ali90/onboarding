@extends('admin.layouts.app')

@section('css')
    <link href="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/select2/select2-metronic.css') }}"/>
    <style>
        .child-1-cat-main-wrap {
            display: block;
            width: 100%;
        }
        .child-2-cat-main-wrap {
            display: block !important;
            width: 100%;
        }
        .datatable-td-milti-data-show-box ul .child-2-cat-main-wrap ul {
            list-style: disc;
            padding-left: 20px;
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
        {{ Breadcrumbs::render('customer-service.index') }}
        <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
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
                                <th class="text-center">Name</th>
                                <th class="text-center">Parent Category Count </th>
                                <th class="text-center">Parent Tree </th>
                                <th class="text-center">Created At</th>
                                <th class="text-center">Enable / Disable</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            @foreach($flagCategory as $index  => $category)
                                <tr>
                                    <td class="text-center">{{$category->id}}</td>
                                    <td class="text-center">{{$category->category_name}}</td>
                                    @if($category->have_childs > 0)
                                    <td class="text-center">{{$category->have_childs}}</td>
                                    @else
                                    <td></td>
                                    @endif
                                    <td class="text-center">
                                        <div class="datatable-td-milti-data-show-box">
                                            <ul>
                                                @foreach($category->getChilds as $index  => $child)
                                                    <li class="@if($index == 0) datatable-td-milti-data-show-box-btn-li datatable-td-milti-data-show-li @else datatable-td-milti-data-show-li hide @endif">
                                                        <span class="child-1-cat-main-wrap">{{$child->category_name}}
                                                            @foreach($child->getChilds as $grandChilds)
                                                                @if($loop->iteration == 1)
                                                                <span class="child-2-cat-main-wrap">
                                                                      <ul>
                                                                @endif
                                                                        <li>{{$grandChilds->category_name}}</li>
                                                                @if($loop->iteration == $loop->last)
                                                                      </ul>
                                                                  </span>
                                                                @endif
                                                            @endforeach
                                                        </span>
                                                        @if($index== 0 && $category->getChilds->count() > 1)
                                                        <span href="#" class="show-datatable-td-list-btn btn btn-xs btn-primary orange " ><i class="fa fa-angle-down"></i></span>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="text-center">{{$category->created_at}}</td>
                                    <td class="text-center">
                                        @if(can_access_route(['customer-service.isEnable','customer-service.isDisable'],$userPermissoins))

                                        @if($category->parent_id == 0)
                                            {{--{!! $category->status_text_formatted !!}--}}
                                                @if($category->is_enable == 1)
                                                    <a  class="btn btn-xs remove-incident" type="button" data-toggle="modal"
                                                        data-target="#statusModal{{$category->id}}">
                                                        <span class="label label-success">Enable</span>
                                                    </a>
                                                    <div id="statusModal{{$category->id}}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                                                aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title">Confirm Status?</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to disable category?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                                                                    {!! Form::model($category, ['method' => 'get',  'url' => 'customer-service/inactive/'.$category->id, 'class' =>'form-inline form-edit']) !!}
                                                                    {!! Form::hidden('id', $category->id) !!}
                                                                    {!! Form::submit('Yes', ['class' => 'btn btn-success btn-flat']) !!}
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <a  class="btn btn-xs remove-incident" type="button" data-toggle="modal"
                                                        data-target="#statusModal{{$category->id}}">
                                                        <span class="label label-warning">Disable</span>
                                                    </a>
                                                    <div id="statusModal{{$category->id}}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                                                aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title">Confirm Status?</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to enable category?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                                                                    {!! Form::model($category, ['method' => 'get',  'url' => 'customer-service/active/'.$category->id, 'class' =>'form-inline form-edit']) !!}
                                                                    {!! Form::hidden('id', $category->id) !!}
                                                                    {!! Form::submit('Yes', ['class' => 'btn btn-success btn-flat']) !!}
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                        @endif
                                        @endif
                                    </td>
                                    <td class="text-center">
                                    @if($category->parent_id == 0)
                                            @if(can_access_route('customer-service.edit',$userPermissoins))
                                        <a href="{!! URL::route('customer-service.edit', $category->id) !!}" data-toggle="tooltip" data-placement="bottom" title="Edit"
                                           class="btn btn-xs btn-primary">
                                            <i class="fa fa-pencil-square"></i>
                                        </a>
                                            @endif
                                            @if(can_access_route('customer-service.show',$userPermissoins))
                                            <a href="{!! URL::route('customer-service.show', $category->id) !!}" data-toggle="tooltip" data-placement="bottom" title="Edit"
                                               class="btn btn-xs btn-primary">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @endif
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
            $('#cancel').click(function() {
                window.location.href = "{{route('customer-service.index') }}";
            });
            appConfig.set( 'dt.searching', true );
        });

    </script>
@stop
