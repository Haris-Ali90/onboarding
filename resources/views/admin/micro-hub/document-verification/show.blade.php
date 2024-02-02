@extends('admin.layouts.app')

@section('css')
    <style>
        .col-md-2 {
            width: 49.666667%;
        }
        .text-type {
    margin: 28px 0px 0px 39px;
}
    </style>
@stop
@section('content')
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle ?? '' }} <small></small></h3>
        {{ Breadcrumbs::render('micro-hub.documentVerificationData.show', $userDocumentVerification) }}
        <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

        @include('admin.partials.errors')

        <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet box blue">

                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-eye"></i> {{ $pageTitle ?? '' }}
                    </div>
                </div>

                <div class="portlet-body">

                    <h4></h4>

                        <div class="form-horizontal" role="form">

                            @foreach($userDocument as $doc)

                                <div class="row1">

                                    <!-- for driver_permit -->
                                    <div class="col-md-6 col-xs-12">
                                        <div class="doc_item_wrap">
                                            <div class="doc_heading">
                                                <h4>{{ucwords(str_replace('_',' ',$doc->document_type))}}</h4>
                                                @if (isset($doc) && $doc->is_approved == 1)
                                                    <div class="doc_status_approved">
                                                        <i class="fa fa-check" aria-hidden="true"></i>
                                                    </div>
                                                @elseif (isset($doc) && $doc->is_approved == 2)
                                                    <div class="doc_status_pending">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="doc_item">
                                                <div class="doc_thumb">
                                                    @php
                                                    $mada = \App\Models\Documents::where('id',$doc->document_type_id)->get();
//dd($mada);
                                                    foreach ($mada as $doc_type)
                                                    {

                                                            $type = $doc_type;
                                                    }

                                                    @endphp
                                                    @if($type->document_type == 'text')
                                                        @if (isset($doc))
                                                            <label class="text-type">{{$doc->document_data}}</label>
                                                        @else
                                                            <div class="no-image">No Text Found</div>
                                                        @endif
                                                    @elseif($type->document_type == 'sin')
                                                        @if (isset($doc))
                                                            <label class="text-type">{{$doc->document_data}}</label>
                                                        @else
                                                            <div class="no-image">No Sin Number Found</div>
                                                        @endif
                                                    @else
                                                        @if (isset($doc))

                                                                @php
                                                                    $doc_extensions = array("doc", "docx");
                                                                    $pdf_ex = "pdf";
                                                                    $explode_name = explode('.', $doc->document_data);
                                                                    $file_extention = end($explode_name);
                                                                    if (in_array($file_extention, $doc_extensions)) {
                                                                    $filePath = \Illuminate\Support\Facades\URL::to('/') . '/backends/thumbnail.png';
                                                                    } elseif ($file_extention == $pdf_ex) {
                                                                    $filePath = \Illuminate\Support\Facades\URL::to('/') . '/backends/pdf-thumbnail.jpg';
                                                                    } else {
                                                                    $filePath = $doc->document_data;
                                                                    }
                                                                @endphp
                                                            @if(filter_var($filePath, FILTER_VALIDATE_URL))
                                                                    <a href="{{$doc->document_data}}" target="_blank"><img src="{{$filePath}}" style=" height: 75px;width: 130px;" class="img-responsive avatar-view"/></a>
                                                            @else
                                                                    <span>{{$doc->document_data}}</span>
                                                            @endif
                                                            <!-- <img class="img-responsive avatar-view" src="{{$doc->document_data}}" alt="Avatar"/> -->
                                                        @else
                                                            <div class="no-image">No Image Found</div>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="doc_fields">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6 col-xs-12">
                                                                <label>Expiry Date:</label>
                                                                @if (isset($doc) && $doc->exp_date != '')
                                                                    <input type="date" class="form-control" id="driver_permit_date" name="driver_permit_date" value= @if (isset($doc) && $doc->exp_date != ''){{ $doc->exp_date}} @endif/ disabled>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6 col-xs-12">
                                                                <label>Status</label>
                                                                @if (isset($doc) )
                                                                    <select name="driver_permit_status"   class="doc-status-change form-control" id="{{$userDocumentVerification}}" disabled>
                                                                        <option value="0" id="pending" @if (isset($doc) && $doc->is_approved == 0) {{'Selected'}} @endif >pending</option>
                                                                        <option value="1" id="approved" @if (isset($doc) && $doc->is_approved == 1) {{'Selected'}} @endif  >approved</option>
                                                                        <option value="2" id="rejected"  @if (isset($doc) && $doc->is_approved == 2) {{'Selected'}} @endif  >rejected</option>
                                                                    </select>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @endforeach



                        <!-- for driver_permit -->

                            <div class="row">

                            </div>





                        </div>

                </div>
            </div>
            <!-- END SAMPLE FORM PORTLET-->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
@stop

@section('footer-js')
    <script src="{{ asset('assets/admin/scripts/core/app.js') }}"></script>
    <script>
        jQuery(document).ready(function() {
            // initiate layout and plugins
            App.init();
            Admin.init();
            $('#cancel').click(function() {
                window.location.href = "{{ route('micro-hub.documentVerificationData.index') }}";
            });
        });
    </script>
@stop
