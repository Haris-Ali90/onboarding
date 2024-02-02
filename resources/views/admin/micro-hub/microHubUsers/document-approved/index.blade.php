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
        {{ Breadcrumbs::render('micro-hub.documentApproved.index') }}
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
          appConfig.set('yajrabox.ajax', '{{ route('micro-hub.documentApproved.data') }}');
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
              //{data: 'action',        orderable: true,    searchable: false, className:'text-center'}
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
                    url: "{{route('micro-hub.users.statusUpdate')}}",
                    data:{
                        id:Id,
                        status:selected_val
                    },
                    /*success: function(data)
                    {
                        console.log(data)
                        //location.reload();
                    },*/
                    success: function (response) {

                        if (response.status == true) // notifying user  the update is completed
                        {

                            // checking hendle type for respoce
                            if(response.body.handle_type == 'redirect')
                            {
                                window.location.href = response.body.redirect_url;
                            }
                            else if(response.body.handle_type == 'reload')
                            {
                                location.reload();
                                // show session alert
                                ShowSessionAlert('success', response.body.message);
                            }
                        }
                        else // update  failed by server
                        {
                            // show session alert
                            ShowSessionAlert('danger', response.body.message);
                        }

                    },
                    error:function (error)
                    {
                        alert('Alert Error view console');
                        console.log(error);
                    }
                });
            }
            else
            {
                //location.reload();
                $(this).val(old_selected_val);
            }
        });
    </script>
@stop
