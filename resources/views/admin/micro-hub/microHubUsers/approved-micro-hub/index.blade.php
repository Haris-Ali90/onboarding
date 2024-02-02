@extends('admin.layouts.app')

@section('css')
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/select2/select2-metronic.css') }}"/>
{{--    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/data-tables/DT_bootstrap.css') }}"/>--}}

    <link href="{{ asset('assets/admin/css/customPreview.css') }}" rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL STYLES -->
    <style>
        #add {
            margin: 0px 0px 0px 12px;
        }
        #inputs{
            width:66%!important;
        }
        #title{
            width:66%!important;
        }
        #zone_type{
            width:66%!important;
        }
        .postal-code{
            width:66%!important;
        }
        .remScnt {
            margin: -55px 0px 0px 338px
        ;
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
        {{ Breadcrumbs::render('micro-hub.microHubUsers.approved.index') }}
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




                    <table class="table table-striped table-bordered table-hover yajrabox" id="sample_1">
                        <thead>
                        <tr>
                            <th style="width: 5%">ID</th>
                            <th style="width: 10%">Name</th>
                            <th style="width: 14%">Email</th>
                            <th style="width: 10%">Phone</th>
                            <th style="width: 10%">City</th>
                            <th style="width: 10%">State</th>
                            <th style="width: 6%">Street</th>
                            <th style="width: 6%">Area Radius</th>
                            <th style="width: 2%">Own Joeys</th>
                            <th style="width: 20%">Address</th>
                            <th style="width: 10%">Postal Code</th>
                            <th style="width: 10%">Action</th>
                            {{--<th style="width: 10%">status</th>--}}
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

    <!--model-for-Create-Postal-Code-open-->
    <div class="modal fade" id="create-postal-code-modal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create Postal Code</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-12 hoverable-dropdown-main-wrap">
                                <!--model-append-html-main-wrap-open-->
                                <div class="col-sm-12 model-zone-append-html-main-wrap">

                                </div>
                                <!--model-append-html-main-wrap-close-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <!--model-for-Create-Postal-Code-close-->
    <!--model-for-zone-create-open-->
    <div class="modal fade" id="create-zone-modal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create Zone</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-12 hoverable-dropdown-main-wrap">
                                <!--model-append-html-main-wrap-open-->
                                <div class="col-sm-12 model-zone-append-html-main-wrap">

                                </div>
                                <!--model-append-html-main-wrap-close-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <!--model-for-zone-create-close-->


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

    <script src="{{ asset('assets/admin/scripts/custom/customPreview.js') }}"></script>

    <script>

        jQuery(document).ready(function() {
            // initiate layout and plugins
            App.init();
            Admin.init();

            var scntDiv = $('#add_words');
            var wordscount = 1;

            var i = 0;
            $('#add').click(function() {

                // alert()
                var inputFields = parseInt($('#inputs').val());
                console.log(inputFields);
                for (var n = i; n < inputFields; ++ n){
                    wordscount++;
                    $('<div class="form-group"><input class=" form-control postal-code" placeholder="Postal Code" type="text" value="" name="postal[]" maxlength="3 " required  /><button class="remScnt btn btn-danger btn-sm">x</button></div>').appendTo(scntDiv);
                    i++;
                }
                return false;
            });

            //    Remove button
            $('#add_words').on('click', '.remScnt', function() {
                if (i > 0) {
                    $(this).parent().remove();
                    i--;
                }
                return false;
            });



        });


      $(function () {
          appConfig.set('yajrabox.ajax', '{{ route('micro-hub.approved.data') }}');
          appConfig.set('dt.order', [0, 'desc']);
          appConfig.set('yajrabox.scrollx_responsive',true);
          appConfig.set('yajrabox.ajax.data', function (data) {
              data.email = jQuery('[name=email]').val();
              data.phone = jQuery('[name=phone]').val();
              data.status = jQuery('[name=status]').val();
          });
          appConfig.set('yajrabox.columns', [
              // {data: 'detail',            orderable: false,   searchable: false, className: 'details-control'},
              // {data: 'check-box',         orderable: false,   searchable: false, visible: multi},
              {data: 'id',   orderable: true,   searchable: true, className:'text-center'},
              {data: 'full_name',   orderable: true,   searchable: true, className:'text-center' },
              {data: 'email_address',         orderable: true,    searchable: true, className:'text-center'},
              {data: 'phone_no',         orderable: true,    searchable: true, className:'text-center'},
              {data: 'city',         orderable: true,    searchable: true, className:'text-center'},
              {data: 'state',         orderable: true,    searchable: true, className:'text-center'},
              {data: 'street',         orderable: true,    searchable: true, className:'text-center'},
              {data: 'area_radius',         orderable: false,    searchable: false, className:'text-center'},
              {data: 'own_joeys',         orderable: false,    searchable: false, className:'text-center'},
              {data: 'address',        orderable: true,    searchable: true, className:'text-center'},
              {data: 'postal_code',        orderable: false,    searchable: false, className:'text-center'},
              {data: 'action',        orderable: false,    searchable: false, className:'text-center'}
          ]);
      })

        //Call to open modal
        $(document).on('click', '.createPostalCode', function (e) {
            let passing_data = $(this).attr("data-email_address");
            // showing model and getting el of model
            //let model_el = $('#create-postal-code-modal').modal();
            // setting data to model
            getDataOrderPostalCodeCreate(passing_data);
            //$('#email_address').val(passing_data);

        });

        // get data of postal code create and show on model
        function getDataOrderPostalCodeCreate(data) {
            //show loader
            showLoader();

            let parse_data =  JSON.parse(data);

            $.ajax({
                type: "POST",
                url: "{{route('postal-code-create-model-html-render')}}",
                data:parse_data,
                beforeSend: function (request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function (response) {
                    hideLoader();
                    // showing model and getting el of model
                    let model_el = $('#create-postal-code-modal').modal();
                    // appending html of zone create
                    model_el.find('.model-zone-append-html-main-wrap').html(response.html);
                },
                error: function (error) {
                    hideLoader();
                    ShowSessionAlert('danger', 'Something wrong');
                    console.log(error);
                }
            });

        }

        //Call to open modal
        $(document).on('click', '.createZones', function (e) {
            let passing_data = $(this).attr("data-email_address");

            // showing model and getting el of model
            //let model_el = $('#create-zone-modal').modal();
            // setting data to model
            //$('#email_address').val(passing_data);
            getDataOrderZoneCreate(passing_data);

        });

        // get data of zone create and show on model
        function getDataOrderZoneCreate(data) {
            //show loader
            showLoader();

            let parse_data =  JSON.parse(data);

            $.ajax({
                type: "POST",
                url: "{{route('zone-create-model-html-render')}}",
                data:parse_data,
                beforeSend: function (request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function (response) {
                    hideLoader();
                    // showing model and getting el of model
                    let model_el = $('#create-zone-modal').modal();
                    // appending html of zone create
                    model_el.find('.model-zone-append-html-main-wrap').html(response.html);
                },
                error: function (error) {
                    hideLoader();
                    ShowSessionAlert('danger', 'Something wrong');
                    console.log(error);
                }
            });

        }


    </script>
@stop
