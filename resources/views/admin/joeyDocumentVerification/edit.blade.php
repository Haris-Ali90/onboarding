@extends('admin.layouts.app')

@section('css')
    <style>
        .col-md-2 {
            width: 49.666667%;
        }
        .doc-status-change{
            width: 26% !important;
        }
    </style>
@stop
@section('content')
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle ?? '' }} <small></small></h3>
        {{ Breadcrumbs::render('joey-document-verification.edit', $joeyDocumentVerification) }}
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
                    <form method="POST" action="{{ route('joey-document-verification.update', $joeyDocumentVerification->id) }}" class="form-horizontal" role="form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-horizontal" role="form">

                            @foreach($document_type as $doc)

                            @php $selected_doc = (isset($joeyDocument_data[$doc->id]))?  ["document_exp_date" => $joeyDocument_data[$doc->id]->exp_date,"document_data" => $joeyDocument_data[$doc->id]->document_data,"id" => $joeyDocument_data[$doc->id]->id,"document_type_id" => $joeyDocument_data[$doc->id]->document_type_id, 'is_approved'=>$joeyDocument_data[$doc->id]->is_approved] : ["document_exp_date" => date('Y-m-d'),"document_data" => "","id" => "", "document_type_id" => "", 'is_approved'=>0]; @endphp
                           {{-- @if($doc->document_name)--}}
                                <div class="row">
                                    <!-- for driver_permit -->
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="doc_item_wrap edit_format">
                                            <div class="doc_heading">
                                                <h4>{{ucwords(str_replace('_',' ',$doc->document_name))}}</h4>
                                                @if (isset($doc) && $selected_doc['is_approved'] == 1)
                                                    <div class="doc_status_approved">
                                                        <i class="fa fa-check" aria-hidden="true"></i>
                                                    </div>
                                                @elseif (isset($doc) && $selected_doc['is_approved'] == 2)
                                                    <div class="doc_status_pending">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </div>
                                                @endif
{{--                                                @if(isset($doc) &&  $selected_doc['is_approved']== 0 ||  $selected_doc['is_approved'] == 2 ||  $selected_doc['document_exp_date'] < date('Y-m-d'))--}}
{{--                                                    @if($selected_doc['is_approved'] == 0 || $selected_doc['is_approved'] == 2 || $selected_doc['document_exp_date'] != null)--}}
{{--                                                            hello--}}
{{--                                                    @endif--}}
{{--                                                @endif--}}
                                            </div>
                                            <div class="doc_item">

                                                <div class="doc_fields full_w">
                                                    <?php
                                                    $doc_extensions = array("doc", "docx");
                                                    $pdf_ex = "pdf";
                                                    if ($doc->document_type == 'file') {
                                                    $explode_name = explode('.', $selected_doc['document_data']);
                                                    $file_extention = end($explode_name);

                                                    if (in_array($file_extention, $doc_extensions)) {
                                                        $filePath = \Illuminate\Support\Facades\URL::to('/') . '/backends/thumbnail.png';
                                                    } elseif ($file_extention == $pdf_ex) {
                                                        $filePath = \Illuminate\Support\Facades\URL::to('/') . '/backends/pdf-thumbnail.jpg';
                                                    } else {
                                                        $filePath = $selected_doc['document_data'];
                                                    }

                                                    if(filter_var($filePath, FILTER_VALIDATE_URL)){
                                                    ?>
                                                        <div class="doc_thumb_wrap" aria-hidden="true">
                                                        <a href="{{$selected_doc['document_data']}}"
                                                         target="_blank"><img src="{{$filePath}}"
                                                                              class="img-responsive avatar-view"/></a>
                                                        </div>
                                                    <?php }
                                                    else {
                                                    ?>
                                                        <div class="doc_thumb_wrap">
                                                        <span>{{$selected_doc['document_data']}}</span>
                                                        </div>
                                                            <?php
                                                    }
                                                    }

                                                    ?>
                                                    <div class="form-group">
                                                        <div class="row inputs-box-wrap">
                                                            @if($doc->document_type == 'text')
                                                                @if (isset($doc))
                                                                    <div class="col-md-4 col-xs-12">
                                                                        <label>Document Text</label>
                                                                        <input type="hidden"  name="document_id[{{$doc->id}}]" value="{{$selected_doc['id']}}" class="form-control" />
                                                                        <input type="hidden"  name="document_type_name[{{$doc->id}}]" value="{{$doc->document_name}}" class="form-control" />
                                                                        <input type="text" name="document[{{$doc->id}}]" class="form-control  file-input" style="margin-bottom: 6px;" value="{{$selected_doc['document_data']}}" <?php if($doc->is_optional==0 && empty($selected_doc['document_data'])) echo "required";?> />
                                                                    </div>
                                                                @endif
                                                            @elseif($doc->document_type == 'sin')
                                                                @if (isset($doc))
                                                                    <div class="col-md-4 col-xs-12">
                                                                        <label>Sin</label>
                                                                        <input type="hidden"  name="document_id[{{$doc->id}}]" value="{{$selected_doc['id']}}" class="form-control" />
                                                                        <input type="hidden"  name="document_type_name[{{$doc->id}}]" value="{{$doc->document_name}}" class="form-control" />
                                                                        <input type="text" name="document[{{$doc->id}}]" class="form-control  file-input" style="margin-bottom: 6px;" value="{{$selected_doc['document_data']}}" <?php if($doc->is_optional==0 && empty($selected_doc['document_data'])) echo "required";?> />
                                                                    </div>
                                                                @endif
                                                            @else
                                                                @if (isset($doc) && $selected_doc['is_approved']== 0 ||  $selected_doc['is_approved'] == 2 ||  $selected_doc['document_exp_date'] < date('Y-m-d'))
                                                                    @if($selected_doc['is_approved'] == 0 || $selected_doc['is_approved'] == 2 || $selected_doc['document_exp_date'] != null)
                                                                        <div class="col-md-4 col-xs-12">
                                                                            <label>Upload Image</label>
                                                                            <input type="hidden"  name="document_id[{{$doc->id}}]" value="{{$selected_doc['id']}}" class="form-control" />
                                                                            <input type="hidden"  name="document_type_name[{{$doc->id}}]" value="{{$doc->document_name}}" class="form-control" />
                                                                            <input onchange="checkFileExtension(this)" type="file" name="document[{{$doc->id}}]" class="form-control  file-input" style="margin-bottom: 6px;" <?php if($doc->is_optional==0 && empty($selected_doc['document_data'])) echo "required";?> />
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            @endif

                                                            @if (isset($doc) && $selected_doc['is_approved']== 0 ||  $selected_doc['is_approved'] == 2 ||  $selected_doc['document_exp_date'] < date('Y-m-d'))
                                                                @if($selected_doc['is_approved'] == 0 || $selected_doc['is_approved'] == 2 || $selected_doc['document_exp_date'] != null)
                                                                    <div class="col-md-4 col-xs-12">
                                                                        <label>Expiry Date:</label>
                                                                        <input type="date" class="form-control" id="document_exp_date" name="document_exp_date[{{$doc->id}}]" value="{{$selected_doc['document_exp_date']}}" />
                                                                    </div>
                                                                @endif
                                                            @else
                                                                <div class="col-md-4 col-xs-12">
                                                                    <label>Expiry Date:</label>
                                                                    <input type="text" class="form-control" disabled value="{{$selected_doc['document_exp_date']}}" />
                                                                </div>
                                                            @endif
                                                            <div class="col-md-4 col-xs-12">
                                                                <label>Status</label>
                                                                <select name="document_status[{{$doc->id}}]"   class="doc-status-change form-control" id="{{$joeyDocumentVerification->id}}" disabled>
                                                                	@if($doc->document_type == 'sin' && $selected_doc['document_data'] == '')
                                                                        <option value="0" selected="selected">Not Updated</option>
                                                                        <option value="0">pending</option>
                                                                    	<option value="1">approved</option>
                                                                    	<option value="2">rejected</option>
                                                                    @elseif($doc->document_type == 'text' && $selected_doc['document_data'] == '')
                                                                        <option value="0" selected="selected">Not Updated</option>
                                                                        <option value="0">pending</option>
                                                                    	<option value="1">approved</option>
                                                                    	<option value="2">rejected</option>
                                                                    @elseif($doc->document_type == 'file' && $selected_doc['document_data'] == '')
                                                                        <option value="0" selected="selected">Not Uploaded</option>
                                                                        <option value="0">pending</option>
                                                                    	<option value="1">approved</option>
                                                                    	<option value="2">rejected</option>
                                                                    @else
                                                                    	<option value="0" id="pending" @if ($selected_doc['is_approved'] == 0) {{'Selected'}} @endif >pending</option>
                                                                    	<option value="1" id="approved" @if ($selected_doc['is_approved'] == 1) {{'Selected'}} @endif  >approved</option>
                                                                    	<option value="2" id="rejected"  @if ($selected_doc['is_approved'] == 2) {{'Selected'}} @endif  >rejected</option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                               {{-- @endif--}}
                            @endforeach

                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-10">
                                    <input type="submit" class="btn blue" id="save" value="Save">
                                    <input type="button" class="btn black" name="cancel" id="cancel" value="Cancel">
                                </div>
                            </div>
                        </div>


                </form>
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
                window.location.href = "{{ route('joey-document-verification.index') }}";
            });

            //for enable / disable status list dropdown
            $('.file-input').change(function(){
                let current_el = $(this);
                current_el.closest('.inputs-box-wrap').find('.doc-status-change').prop('disabled',false);

            });

        });

        //Check extention of upload files
        function checkFileExtension(element) {
            var el = $(element);
            var selectedText = el.val();
            extension = selectedText.split('.').pop();
            var allowed_ext = ['jpeg','png','jpg','PNG','JPEG','JPG'];
            if (!allowed_ext.includes(extension)) {
                alert("Sorry! Invalid file. Allowed extensions are: jpeg, png, jpg.");
                location.reload();
            }
        };

        $(function(){
            var dtToday = new Date();

            var month = dtToday.getMonth() + 1;
            var day = dtToday.getDate();
            var year = dtToday.getFullYear();
            if(month < 10)
                month = '0' + month.toString();
            if(day < 10)
                day = '0' + day.toString();

            var minDate= year + '-' + month + '-' + day;

            $('#document_exp_date').attr('min', minDate);
/*            $('#driver_license_date').attr('min', minDate);
            $('#study_permit_date').attr('min', minDate);
            $('#vehicle_insurance_date').attr('min', minDate);
            $('#additional_document_1_date').attr('min', minDate);
            $('#additional_document_2_date').attr('min', minDate);
            $('#additional_document_3_date').attr('min', minDate);
            $('#sin_date').attr('min', minDate);*/

        });

        {{--$(document).on('change','.doc-status-change',function () {--}}

        {{--let old_selected_val = ($(this).attr('data-selected-val') >= 0) ? $(this).attr('data-selected-val') : 0 ;--}}
        {{--let selected_val = $(this).val();--}}
        {{--let Id = $(this).attr('id');--}}
        {{--let confirm_val = confirm("Are you sure you want to change the status?");--}}

        {{--if(confirm_val)--}}
        {{--{--}}

        {{--$.ajax({--}}
        {{--type: "GET",--}}
        {{--url:"{{url('joeyDocumentVerification/status/update/statusUpdate')}}",--}}
        {{--data:{id:Id,status:selected_val},--}}
        {{--success: function(data){--}}
        {{--console.log(data);--}}
        {{--location.reload();--}}
        {{--},error:function (error){--}}
        {{--alert('Alert Error view console');--}}
        {{--console.log(error);--}}

        {{--}--}}
        {{--});--}}
        {{--}--}}
        {{--else--}}
        {{--{--}}
        {{--alert('2');--}}
        {{--location.reload();--}}
        {{--$(this).val(old_selected_val);--}}
        {{--}--}}
        {{--});--}}
    </script>
@stop
