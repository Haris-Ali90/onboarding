@extends('admin.layouts.app')

@section('css')
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/select2/select2-metronic.css') }}"/>
    <!-- END PAGE LEVEL STYLES -->
    <style>
        .category-main {
            color: #FFFFFF;
            font-size: 15px;
        }

        .is-enable {
            text-align: right;
        }

        .modal-title {
            text-align: left;
            margin: 0px 0px 0px 218px !important;
        }

        .modal-body {
            text-align: left;
            margin: 0px 0px 0px 160px;
        }

        .section-heading {
            padding: 5px 10px;
            font-size: 15px;
            background-color: #bbd82b;
            color: #fff;
        }

        .show-title {
            color: #FFFFFF;
        }
    </style>
@stop

@section('content')
    <!-- BEGIN PAGE HEADER-->
    @include('admin.partials.errors')

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle }}
                <small></small>
            </h3>
        {{ Breadcrumbs::render('customer-service.show', $customer_service) }}
        <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet box blue">

                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-plus"></i> {{ $pageTitle }}
                    </div>
                </div>

                <!--portlet-body-open-->
                <div class="portlet-body details-page-main-container">

                    <!--show-details-title-open-->
                    <div class="col-sm-12">
                        <p class="show-details-lable section-heading">Grand Parent Category Detail</p>
                        <div class="col-sm-3 show-details-box">
                            <p class="show-details-lable">Grand Parent Flag Category Name:</p>
                            <p class="show-details-para">{{ $customer_service->category_name }}</p>
                        </div>
                        <div class="col-sm-3 show-details-box">
                            <p class="show-details-lable">Portal Type:</p>
                            <p class="show-details-para">{{ucwords(str_replace("_"," ",$selected_portal_types)) }}</p>
                        </div>
                        <div class="col-sm-3 show-details-box">
                            <p class="show-details-lable">Order Type:</p>
                            <p class="show-details-para">{{ ucwords(str_replace('_','-',$selected_order_type), ", ") }}</p>
                        </div>
                        <div class="col-sm-3 show-details-box">
                            <p class="show-details-lable">Enable For (Route Info) Flagging:</p>
                            @if($selected_is_show_on_route == 0)
                                <p class="show-details-para">No</p>
                                @elseif($selected_is_show_on_route == 1)
                                <p class="show-details-para">Yes</p>
                            @else
                                <p class="show-details-para">Both</p>
                            @endif
                        </div>

                        <!--Table-For-Vendors-Open-->
                        <div class="table-responsive">
                            <p class="show-details-lable">Vendor List</p>
                            <table class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Vendor ID</th>
                                    <th class="text-center">Name</th>
                                </tr>
                                </thead>
                                @foreach($vendors_data as $key => $vendor)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td class="text-center">{{$vendor->id}}</td>
                                        <td class="text-center">{{$vendor->FullName}}</td>
                                    </tr>
                                @endforeach
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!--Table-For-Vendors-Close-->
                    </div>
                    <!--show-details-box-close-->
                @foreach($customer_service->getChilds as $second_child)
                    <!--details-page-wrap-open-->
                        <div class="row  details-page-wrap">
                            <div class="col-sm-12 show-details-box">
                                <p class="show-title">Parent Category Detail</p>
                            </div>
                            <div class="col-sm-12 is-enable">
                                <!--Open-category-enable-disable-condition-->
                                @if(can_access_route(['customer-service.isEnable','customer-service.isDisable'],$userPermissoins))
                                    @if($second_child->is_enable == 1)
                                        <a class="btn btn-xs remove-incident" type="button" data-toggle="modal"
                                           data-target="#statusModal{{$second_child->id}}">
                                            <span class="label label-success">Enable</span>
                                        </a>
                                        <div id="statusModal{{$second_child->id}}" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"><span
                                                                aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title">Confirm Status?</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to disable category?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button"
                                                                class="btn btn-default btn-flat pull-left"
                                                                data-dismiss="modal">Close
                                                        </button>
                                                        {!! Form::model($second_child, ['method' => 'get',  'url' => 'customer-service/inactive/'.$second_child->id, 'class' =>'form-inline form-edit']) !!}
                                                        {!! Form::hidden('id', $second_child->id) !!}
                                                        {!! Form::submit('Yes', ['class' => 'btn btn-success btn-flat']) !!}
                                                        {!! Form::close() !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <a class="btn btn-xs remove-incident" type="button" data-toggle="modal"
                                           data-target="#statusModal{{$second_child->id}}">
                                            <span class="label label-warning">Disable</span>
                                        </a>
                                        <div id="statusModal{{$second_child->id}}" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"><span
                                                                aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title">Confirm Status?</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to enable category?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button"
                                                                class="btn btn-default btn-flat pull-left"
                                                                data-dismiss="modal">Close
                                                        </button>
                                                        {!! Form::model($second_child, ['method' => 'get',  'url' => 'customer-service/active/'.$second_child->id, 'class' =>'form-inline form-edit']) !!}
                                                        {!! Form::hidden('id', $second_child->id) !!}
                                                        {!! Form::submit('Yes', ['class' => 'btn btn-success btn-flat']) !!}
                                                        {!! Form::close() !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                @endif
                            @endif
                                <!--Close-category-enable-disable-condition-->
                            </div>
                            <!--show-details-box-open-->
                            <div class="col-sm-12 show-details-box">
                                <p class="show-details-lable">Parent Category Name:</p>
                                <p class="show-details-para">{{ $second_child->category_name }}</p>
                            </div>
                            <!--show-details-box-close-->

                            <div class="col-sm-12 third-child-cat-main-wrap">
                                @foreach($second_child->getChilds as $third_child)
                                <!--details-page-wrap-open-->
                                    <div class="row  details-page-wrap">
                                        <div class="col-sm-12 show-details-box">
                                            <p class="show-title">Child Category Detail</p>
                                        </div>
                                        <div class="col-sm-12 is-enable">
                                            <!--Open-category-enable-disable-condition-->
                                            @if(can_access_route(['customer-service.isEnable','customer-service.isDisable'],$userPermissoins))
                                                @if($third_child->is_enable == 1)
                                                    <a class="btn btn-xs remove-incident" type="button" data-toggle="modal"
                                                       data-target="#statusModal{{$third_child->id}}">
                                                        <span class="label label-success">Enable</span>
                                                    </a>
                                                    <div id="statusModal{{$third_child->id}}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                            aria-label="Close"><span
                                                                            aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title">Confirm Status?</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to disable category?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                            class="btn btn-default btn-flat pull-left"
                                                                            data-dismiss="modal">Close
                                                                    </button>
                                                                    {!! Form::model($third_child, ['method' => 'get',  'url' => 'customer-service/inactive/'.$third_child->id, 'class' =>'form-inline form-edit']) !!}
                                                                    {!! Form::hidden('id', $third_child->id) !!}
                                                                    {!! Form::submit('Yes', ['class' => 'btn btn-success btn-flat']) !!}
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <a class="btn btn-xs remove-incident" type="button" data-toggle="modal"
                                                       data-target="#statusModal{{$third_child->id}}">
                                                        <span class="label label-warning">Disable</span>
                                                    </a>
                                                    <div id="statusModal{{$third_child->id}}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                            aria-label="Close"><span
                                                                            aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title">Confirm Status?</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to enable category?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                            class="btn btn-default btn-flat pull-left"
                                                                            data-dismiss="modal">Close
                                                                    </button>
                                                                    {!! Form::model($third_child, ['method' => 'get',  'url' => 'customer-service/active/'.$third_child->id, 'class' =>'form-inline form-edit']) !!}
                                                                    {!! Form::hidden('id', $third_child->id) !!}
                                                                    {!! Form::submit('Yes', ['class' => 'btn btn-success btn-flat']) !!}
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            @endif
                                        @endif
                                        <!--Close-category-enable-disable-condition-->
                                        </div>
                                        <!--show-details-box-open-->
                                        <div class="col-sm-12 show-details-box">
                                            <p class="show-details-lable">Child Category Name:</p>
                                            <p class="show-details-para">{{ $third_child->category_name }}</p>
                                        </div>
                                        <!--show-details-box-close-->

                                        @if(isset($third_child->FlagCategoryValues->incident_1_ref_id))
                                            <br>
                                        @endif
                                    <!--Incident-Work-open-->
                                        <div class="col-sm-12 show-details-box">
                                            <p class="section-heading">Incident</p>
                                        </div>
                                        <!--show-details-box-open-->

                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">1st Incident:</p>
                                            <p class="show-details-para">{{ $third_child->FlagCategoryValues->getFirstIncident->name }}</p>
                                        </div>
                                        <!--show-details-box-close-->

                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">1st Incident refresh rate:</p>
                                            <p class="show-details-para">{{ ($third_child->FlagCategoryValues->refresh_rate_incident_1 >= 2) ? $third_child->FlagCategoryValues->refresh_rate_incident_1.' Months' : $third_child->FlagCategoryValues->refresh_rate_incident_1.' Month' }}</p>
                                        </div>
                                        <!--show-details-box-close-->

                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">2nd Incident:</p>
                                            <p class="show-details-para">{{ $third_child->FlagCategoryValues->getSecondIncident->name }}</p>
                                        </div>
                                        <!--show-details-box-close-->

                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">2nd Incident refresh rate:</p>
                                            <p class="show-details-para">{{  ($third_child->FlagCategoryValues->refresh_rate_incident_2 >= 2) ? $third_child->FlagCategoryValues->refresh_rate_incident_2.' Months' : $third_child->FlagCategoryValues->refresh_rate_incident_2.' Month' }}</p>
                                        </div>
                                        <!--show-details-box-close-->

                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">3rd Incident:</p>
                                            <p class="show-details-para">{{ $third_child->FlagCategoryValues->getThirdIncident->name }}</p>
                                        </div>
                                        <!--show-details-box-close-->

                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">3rd Incident refresh rate:</p>
                                            <p class="show-details-para">{{ ($third_child->FlagCategoryValues->refresh_rate_incident_3 >= 2) ? $third_child->FlagCategoryValues->refresh_rate_incident_3.' Months' : $third_child->FlagCategoryValues->refresh_rate_incident_3.' Month'  }}</p>
                                        </div>
                                        <!--show-details-box-close-->

                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">Conclusion:</p>
                                            <p class="show-details-para">{{ $third_child->FlagCategoryValues->getConclusionIncident->name }}</p>
                                        </div>
                                        <!--show-details-box-close-->

                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">Conclusion refresh rate:</p>
                                            <p class="show-details-para">{{ ($third_child->FlagCategoryValues->refresh_rate_conclusion >= 2) ? $third_child->FlagCategoryValues->refresh_rate_conclusion.' Months' : $third_child->FlagCategoryValues->refresh_rate_conclusion.' Month' }}</p>
                                        </div>
                                        <!--show-details-box-close-->

                                        @if(isset($third_child->FlagCategoryValues->finance_incident_1))
                                            <br>
                                        @endif
                                    <!--Incident-Work-close-->

                                        <!--Finance-Incident-Work-open-->
                                        <div class="col-sm-12 show-details-box">
                                            <p class="section-heading">Finance Incidents</p>
                                        </div>
                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">1st Finance Incident:</p>
                                            <p class="show-details-para">{{$third_child->FlagCategoryValues->finance_incident_1}}</p>
                                        </div>
                                        <!--show-details-box-close-->

                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">1st Finance Incident Operator:</p>
                                            @if($third_child->FlagCategoryValues->finance_incident_1_operator == "+")
                                                <p class="show-details-para">Add</p>
                                            @elseif($third_child->FlagCategoryValues->finance_incident_1_operator == "-")
                                                <p class="show-details-para">Subtract</p>
                                            @endif
                                        </div>
                                        <!--show-details-box-close-->

                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">2nd Finance Incident:</p>
                                            <p class="show-details-para">{{$third_child->FlagCategoryValues->finance_incident_2}}</p>
                                        </div>
                                        <!--show-details-box-close-->

                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">2nd Finance Incident Operator:</p>
                                            @if($third_child->FlagCategoryValues->finance_incident_2_operator == "+")
                                                <p class="show-details-para">Add</p>
                                            @elseif($third_child->FlagCategoryValues->finance_incident_2_operator == "-")
                                                <p class="show-details-para">Subtract</p>
                                            @endif
                                        </div>
                                        <!--show-details-box-close-->

                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">3rd Finance Incident:</p>
                                            <p class="show-details-para">{{$third_child->FlagCategoryValues->finance_incident_3}}</p>
                                        </div>
                                        <!--show-details-box-close-->

                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">3rd Finance Incident Operator:</p>
                                            @if($third_child->FlagCategoryValues->finance_incident_3_operator == "+")
                                                <p class="show-details-para">Add</p>
                                            @elseif($third_child->FlagCategoryValues->finance_incident_3_operator == "-")
                                                <p class="show-details-para">Subtract</p>
                                            @endif
                                        </div>
                                        <!--show-details-box-close-->

                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">Finance Conclusion:</p>
                                            <p class="show-details-para">{{$third_child->FlagCategoryValues->finance_conclusion}}</p>
                                        </div>
                                        <!--show-details-box-close-->

                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">Finance Conclusion Operator:</p>
                                            @if($third_child->FlagCategoryValues->finance_conclusion_operator == "+")
                                                <p class="show-details-para">Add</p>
                                            @elseif($third_child->FlagCategoryValues->finance_conclusion_operator == "-")
                                                <p class="show-details-para">Subtract</p>
                                            @endif
                                        </div>
                                        <!--show-details-box-close-->
                                        <!--Finance-Incident-Work-close-->
                                        @if(isset($third_child->FlagCategoryValues->rating))
                                            <br>
                                    @endif
                                    <!--Rating-Work-open-->
                                        <div class="col-sm-12 show-details-box">
                                            <p class="section-heading">Rating</p>
                                        </div>
                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">1st Rating:</p>
                                            <p class="show-details-para">{{$third_child->FlagCategoryValues->rating_1}}</p>
                                        </div>
                                        <!--show-details-box-close-->

                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">1st Rating Operator:</p>
                                            @if($third_child->FlagCategoryValues->rating_1_operator == "+")
                                                <p class="show-details-para">Add</p>
                                            @elseif($third_child->FlagCategoryValues->rating_1_operator == "-")
                                                <p class="show-details-para">Subtract</p>
                                            @endif
                                        </div>
                                        <!--show-details-box-close-->
                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">2nd Rating:</p>
                                            <p class="show-details-para">{{$third_child->FlagCategoryValues->rating_2}}</p>
                                        </div>
                                        <!--show-details-box-close-->

                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">2nd Rating Operator:</p>
                                            @if($third_child->FlagCategoryValues->rating_2_operator == "+")
                                                <p class="show-details-para">Add</p>
                                            @elseif($third_child->FlagCategoryValues->rating_2_operator == "-")
                                                <p class="show-details-para">Subtract</p>
                                            @endif
                                        </div>
                                        <!--show-details-box-close-->
                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">3rd Rating:</p>
                                            <p class="show-details-para">{{$third_child->FlagCategoryValues->rating_3}}</p>
                                        </div>
                                        <!--show-details-box-close-->

                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">3rd Rating Operator:</p>
                                            @if($third_child->FlagCategoryValues->rating_3_operator == "+")
                                                <p class="show-details-para">Add</p>
                                            @elseif($third_child->FlagCategoryValues->rating_3_operator == "-")
                                                <p class="show-details-para">Subtract</p>
                                            @endif
                                        </div>
                                        <!--show-details-box-close-->
                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">4th Rating:</p>
                                            <p class="show-details-para">{{$third_child->FlagCategoryValues->rating_4}}</p>
                                        </div>
                                        <!--show-details-box-close-->

                                        <!--show-details-box-open-->
                                        <div class="col-sm-3 show-details-box">
                                            <p class="show-details-lable">4th Rating Operator:</p>
                                            @if($third_child->FlagCategoryValues->rating_4_operator == "+")
                                                <p class="show-details-para">Add</p>
                                            @elseif($third_child->FlagCategoryValues->rating_4_operator == "-")
                                                <p class="show-details-para">Subtract</p>
                                            @endif
                                        </div>
                                        <!--show-details-box-close-->
                                        <!--Rating-Work-close-->

                                    </div>
                                    <br>
                                    <!--details-page-wrap-close-->
                                @endforeach
                            </div>

                        </div>


                    @endforeach
                </div>
                <!--portlet-body-close-->
            </div>
            <!-- END SAMPLE FORM PORTLET-->
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

        jQuery(document).ready(function () {
            // initiate layout and plugins
            App.init();
            Admin.init();
            $(document).ready(function () {
                $('.js-example-basic-multiple').select2();
            });
            {{--// make multi select box selected--}}
            {{--make_multi_option_selected('.js-example-basic-multiple','{{$portal_type}}');--}}

            //return back to index page
            $('#cancel').click(function () {
                window.location.href = "{!! URL::route('customer-service.index') !!}";
            });
            appConfig.set('dt.searching', true);
        });

    </script>
@stop
