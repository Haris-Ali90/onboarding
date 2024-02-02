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
        {{ Breadcrumbs::render('joey-document-verification.show', $joeyDocumentVerification) }}
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
                            {{--      <div class="form-group">
                                      <label class="col-md-2 control-label"><strong>Name:</strong> </label>
                                      <div class="col-md-8">
                                          <label class="control-label">{{ $joeyDocumentVerification->display_name}}</label>
                                      </div>
                                  </div>--}}

                            @foreach($joeyDocument as $doc)

                                {{--{{$document_data[$a->document_type] = $a->toArray()}}--}}

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
                                                    foreach ($mada as $doc_type)
                                                    {
                                                            $type = $doc_type;
                                                    }

                                                    @endphp
													@if(isset($type))
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
                                                                    <select name="driver_permit_status"   class="doc-status-change form-control" id="{{$joeyDocumentVerification->id}}" disabled>
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

                                <!-- for driver_permit -->
                            {{--                      <div class="col-md-6 col-xs-12">
                                                      <div class="doc_item_wrap">
                                                          <div class="doc_heading">
                                                              <h4>Driver Permit</h4>
                                                              @if (isset($document_data['driver_permit']) && $document_data['driver_permit']['is_approved'] == 1)
                                                                  <div class="doc_status_approved">
                                                                      <i class="fa fa-check" aria-hidden="true"></i>
                                                                  </div>
                                                              @elseif (isset($document_data['driver_permit']) && $document_data['driver_permit']['is_approved'] == 2)
                                                                  <div class="doc_status_pending">
                                                                      <i class="fa fa-times" aria-hidden="true"></i>
                                                                  </div>
                                                              @endif
                                                          </div>
                                                          <div class="doc_item">
                                                              <div class="doc_thumb">

                                                                      @if (isset($document_data['driver_permit']['document_data']))
                                                                          <img class="img-responsive avatar-view" src="{{$document_data['driver_permit']['document_data']}}" alt="Avatar"/>
                                                                      @else
                                                                          <div class="no-image">No Image Found</div>
                                                                      @endif
                                                              </div>
                                                               <div class="doc_fields">
                                                                  <div class="form-group">
                                                                      <div class="row">
                                                                          <div class="col-md-6 col-xs-12">
                                                                              <label>Expiry Date:</label>
                                                                              @if (isset($document_data['driver_permit']) && $document_data['driver_permit']['exp_date'] != '')
                                                                                  <input type="date" class="form-control" id="driver_permit_date" name="driver_permit_date" value= @if (isset($document_data['driver_permit']) && $document_data['driver_permit']['exp_date'] != ''){{ $document_data['driver_permit']['exp_date']}} @endif/ disabled>
                                                                              @endif
                                                                          </div>
                                                                          <div class="col-md-6 col-xs-12">
                                                                              <label>Status</label>
                                                                              @if (isset($document_data['driver_permit']) )
                                                                                  <select name="driver_permit_status"   class="doc-status-change form-control" id="{{$joeyDocumentVerification->id}}" disabled>
                                                                                      <option value="0" id="pending" @if (isset($document_data['driver_permit']) && $document_data['driver_permit']['is_approved'] == 0) {{'Selected'}} @endif >pending</option>
                                                                                      <option value="1" id="approved" @if (isset($document_data['driver_permit']) && $document_data['driver_permit']['is_approved'] == 1) {{'Selected'}} @endif  >approved</option>
                                                                                      <option value="2" id="rejected"  @if (isset($document_data['driver_permit']) && $document_data['driver_permit']['is_approved'] == 2) {{'Selected'}} @endif  >rejected</option>
                                                                                  </select>
                                                                              @endif
                                                                          </div>
                                                                      </div>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>--}}

                            <!-- for Driver License -->
                                {{--<div class="col-md-6 col-xs-12">
                                    <div class="doc_item_wrap">
                                        <div class="doc_heading">
                                            <h4>Driver License</h4>
                                            @if (isset($document_data['driver_license']) && $document_data['driver_license']['is_approved'] == 1)
                                                <div class="doc_status_approved">
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                </div>
                                            @elseif (isset($document_data['driver_license']) && $document_data['driver_license']['is_approved'] == 2)
                                                <div class="doc_status_pending">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="doc_item">

                                            <div class="doc_thumb">
                                                @if (isset($document_data['driver_license']['document_data']))
                                                    <img class="img-responsive avatar-view" src="{{$document_data['driver_license']['document_data']}}" alt="Avatar"/>
                                                @else
                                                    <div class="no-image">No Image Found</div>
                                                @endif
                                            </div>

                                            <div class="doc_fields">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 col-xs-12">
                                                            <label>Expiry Date:</label>
                                                            @if (isset($document_data['driver_license']))
                                                                <input type="date" class="form-control" id="driver_license_date" name="driver_license_date"  value= @if (isset($document_data['driver_license']) && $document_data['driver_license']['exp_date'] != ''){{ $document_data['driver_license']['exp_date']}} @endif/ disabled>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6 col-xs-12">
                                                            <label>Status</label>
                                                            @if (isset($document_data['driver_license']))
                                                                <select name="driver_license_status"   class="doc-status-change form-control" id="{{$joeyDocumentVerification->id}}" disabled>
                                                                    <option value="0" id="pending" @if (isset($document_data['driver_license']) && $document_data['driver_license']['is_approved'] == 0) {{'Selected'}} @endif >pending</option>
                                                                    <option value="1" id="approved" @if (isset($document_data['driver_license']) && $document_data['driver_license']['is_approved'] == 1) {{'Selected'}} @endif  >approved</option>
                                                                    <option value="2" id="rejected"  @if (isset($document_data['driver_license']) && $document_data['driver_license']['is_approved'] == 2) {{'Selected'}} @endif  >rejected</option>
                                                                </select>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <div class="doc_item_wrap">
                                        <div class="doc_heading">
                                            <h4>Study Permit</h4>
                                            @if (isset($document_data['study_permit']) && $document_data['study_permit']['is_approved'] == 1)
                                                <div class="doc_status_approved">
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                </div>
                                            @elseif (isset($document_data['study_permit']) && $document_data['study_permit']['is_approved'] == 2)
                                                <div class="doc_status_pending">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="doc_item">
                                            <div class="doc_thumb">
                                                @if (isset($document_data['study_permit']['document_data']))
                                                    <img class="img-responsive avatar-view" src="{{$document_data['study_permit']['document_data']}}" alt="Avatar"/>
                                                @else
                                                    <div class="no-image">No Image Found</div>
                                                @endif
                                            </div>
                                            <div class="doc_fields">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 col-xs-12">
                                                            <label>Expiry Date:</label>
                                                            @if (isset($document_data['study_permit']))
                                                                <input type="date" class="form-control" id="study_permit_date" name="study_permit_date"  value= @if (isset($document_data['study_permit']) && $document_data['study_permit']['exp_date'] != ''){{$document_data['study_permit']['exp_date']}} @endif/ disabled>
                                                            @else
                                                                <div>Not available</div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6 col-xs-12">
                                                            <label>Status</label>
                                                            @if (isset($document_data['study_permit']))
                                                                <select name="study_permit_status"   class="doc-status-change form-control" id="{{$joeyDocumentVerification->id}}"disabled>
                                                                    <option value="0" id="pending" @if (isset($document_data['study_permit']) && $document_data['study_permit']['is_approved'] == 0) {{'Selected'}} @endif >pending</option>
                                                                    <option value="1" id="approved" @if (isset($document_data['study_permit']) && $document_data['study_permit']['is_approved'] == 1) {{'Selected'}} @endif  >approved</option>
                                                                    <option value="2" id="rejected"  @if (isset($document_data['study_permit']) && $document_data['study_permit']['is_approved'] == 2) {{'Selected'}} @endif  >rejected</option>
                                                                </select>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <div class="doc_item_wrap">
                                        <div class="doc_heading">
                                            <h4>Vehicle Insurance</h4>
                                            @if (isset($document_data['vehicle_insurance']) && $document_data['vehicle_insurance']['is_approved'] == 1)
                                                <div class="doc_status_approved">
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                </div>
                                            @elseif (isset($document_data['vehicle_insurance']) && $document_data['vehicle_insurance']['is_approved'] == 2)
                                                <div class="doc_status_approved">
                                                    <i class="fa fa-cancel" aria-hidden="true"></i>
                                                </div>
                                            @endif

                                        </div>
                                        <div class="doc_item">
                                            <div class="doc_thumb">
                                                @if (isset($document_data['vehicle_insurance']['document_data']))
                                                    <img class="img-responsive avatar-view" src="{{$document_data['vehicle_insurance']['document_data']}}" alt="Avatar"/>
                                                @else
                                                    <div class="no-image">No Image Found</div>
                                                @endif
                                            </div>
                                            <div class="doc_fields">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 col-xs-12">
                                                            <label>Expiry Date:</label>
                                                            @if (isset($document_data['vehicle_insurance']))
                                                                <input type="date" class="form-control" id="vehicle_insurance_date" name="vehicle_insurance_date" value= @if (isset($document_data['vehicle_insurance']) && $document_data['vehicle_insurance']['exp_date'] != ''){{$document_data['vehicle_insurance']['exp_date']}} @endif/ disabled>
                                                            @else
                                                                <div>Not available</div>
                                                            @endif

                                                        </div>
                                                        <div class="col-md-6 col-xs-12">
                                                            <label>Status</label>
                                                            @if (isset($document_data['vehicle_insurance']))
                                                                <select name="vehicle_insurance_status"   class="doc-status-change form-control" id="{{$joeyDocumentVerification->id}}" disabled>
                                                                    <option value="0" id="pending" @if (isset($document_data['vehicle_insurance']) && $document_data['vehicle_insurance']['is_approved'] == 0) {{'Selected'}} @endif >pending</option>
                                                                    <option value="1" id="approved" @if (isset($document_data['vehicle_insurance']) && $document_data['vehicle_insurance']['is_approved'] == 1) {{'Selected'}} @endif  >approved</option>
                                                                    <option value="2" id="rejected"  @if (isset($document_data['vehicle_insurance']) && $document_data['vehicle_insurance']['is_approved'] == 2) {{'Selected'}} @endif  >rejected</option>
                                                                </select>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <div class="doc_item_wrap">
                                        <div class="doc_heading">
                                            <h4>Additional Document 1</h4>
                                            @if (isset($document_data['additional_document_1']) && $document_data['additional_document_1']['is_approved'] == 1)
                                                <div class="doc_status_approved">
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                </div>
                                            @elseif (isset($document_data['additional_document_1']) && $document_data['additional_document_1']['is_approved'] == 2)
                                                <div class="doc_status_pending">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </div>
                                                @else

                                            @endif
                                        </div>
                                        <div class="doc_item">
                                            <div class="doc_thumb">
                                                @if (isset($document_data['additional_document_1']['document_data']))
                                                    <img class="img-responsive avatar-view" src="{{$document_data['additional_document_1']['document_data']}}" alt="Avatar"/>
                                                @else
                                                    <div class="no-image">No Image Found</div>
                                                @endif
                                            </div>
                                            <div class="doc_fields">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 col-xs-12">
                                                            <label>Expiry Date:</label>
                                                            @if (isset($document_data['additional_document_1']))
                                                                <input type="date" class="form-control" id="additional_document_1_date" name="additional_document_1_date" value= @if (isset($document_data['additional_document_1']) && $document_data['additional_document_1']['exp_date'] != ''){{$document_data['additional_document_1']['exp_date'] }} @endif/ disabled>
                                                            @else
                                                                <div>Not available</div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6 col-xs-12">
                                                            <label>Status</label>
                                                            @if (isset($document_data['additional_document_1']))
                                                                <select name="additional_document_1_status"   class="doc-status-change form-control" id="{{$joeyDocumentVerification->id}}" disabled>
                                                                    <option value="0" id="pending" @if (isset($document_data['additional_document_1']) && $document_data['additional_document_1']['is_approved'] == 0) {{'Selected'}} @endif >pending</option>
                                                                    <option value="1" id="approved" @if (isset($document_data['additional_document_1']) && $document_data['additional_document_1']['is_approved'] == 1) {{'Selected'}} @endif  >approved</option>
                                                                    <option value="2" id="rejected"  @if (isset($document_data['additional_document_1']) && $document_data['additional_document_1']['is_approved'] == 2) {{'Selected'}} @endif  >rejected</option>
                                                                </select>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <div class="doc_item_wrap">
                                        <div class="doc_heading">
                                            <h4>Additional Document 2</h4>
                                            @if (isset($document_data['additional_document_2']) && $document_data['additional_document_2']['is_approved'] == 1)
                                                <div class="doc_status_approved">
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                </div>
                                            @elseif (isset($document_data['additional_document_2']) && $document_data['additional_document_2']['is_approved'] == 2)
                                                <div class="doc_status_pending">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="doc_item">
                                            <div class="doc_thumb">
                                                @if (isset($document_data['additional_document_2']['document_data']))
                                                    <img class="img-responsive avatar-view" src="{{$document_data['additional_document_2']['document_data']}}" alt="Avatar"/ >
                                                @else
                                                    <div class="no-image">No Image Found</div>
                                                @endif
                                            </div>
                                            <div class="doc_fields">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 col-xs-12">
                                                            <label>Expiry Date:</label>
                                                            @if (isset($document_data['additional_document_2']))
                                                                <input type="date" class="form-control" id="additional_document_2_date" name="additional_document_2_date" value= @if (isset($document_data['additional_document_2']) && $document_data['additional_document_2']['exp_date'] != ''){{ $document_data['additional_document_2']['exp_date']}} @endif/ disabled>
                                                            @else
                                                                <div>Not available</div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6 col-xs-12">
                                                            <label>Status</label>
                                                            @if (isset($document_data['additional_document_2']))
                                                                <select name="additional_document_2_status"   class="doc-status-change form-control" id="{{$joeyDocumentVerification->id}}" disabled>
                                                                    <option value="0" id="pending" @if (isset($document_data['additional_document_2']) && $document_data['additional_document_2']['is_approved'] == 0) {{'Selected'}} @endif >pending</option>
                                                                    <option value="1" id="approved" @if (isset($document_data['additional_document_2']) && $document_data['additional_document_2']['is_approved'] == 1) {{'Selected'}} @endif  >approved</option>
                                                                    <option value="2" id="rejected"  @if (isset($document_data['additional_document_2']) && $document_data['additional_document_2']['is_approved'] == 2) {{'Selected'}} @endif  >rejected</option>
                                                                </select>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <div class="doc_item_wrap">
                                        <div class="doc_heading">
                                            <h4>Additional Document 3</h4>
                                            @if (isset($document_data['additional_document_3']) && $document_data['additional_document_3']['is_approved'] == 1)
                                                <div class="doc_status_approved">
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                </div>
                                            @elseif (isset($document_data['additional_document_3']) && $document_data['additional_document_3']['is_approved'] == 2)
                                                <div class="doc_status_pending">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="doc_item">
                                            <div class="doc_thumb">
                                                @if (isset($document_data['additional_document_3']['document_data']))
                                                    <img class="img-responsive avatar-view" src="{{$document_data['additional_document_3']['document_data']}}" alt="Avatar"/>
                                                @else
                                                    <div class="no-image">No Image Found</div>
                                                @endif
                                            </div>
                                            <div class="doc_fields">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 col-xs-12">
                                                            <label>Expiry Date:</label>
                                                            @if (isset($document_data['additional_document_3']))
                                                                <input type="date" class="form-control" id="additional_document_3_date" name="additional_document_3_date" value= @if (isset($document_data['additional_document_3']) && $document_data['additional_document_3']['exp_date'] != ''){{ $document_data['additional_document_3']['exp_date'] }} @endif/ disabled>
                                                            @else
                                                                <div>Not available</div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6 col-xs-12">
                                                            <label>Status</label>
                                                            @if (isset($document_data['additional_document_3']))
                                                                <select name="additional_document_3_status"   class="doc-status-change form-control" id="{{$joeyDocumentVerification->id}}" disabled>
                                                                    <option value="0" id="pending" @if (isset($document_data['additional_document_3']) && $document_data['additional_document_3']['is_approved'] == 0) {{'Selected'}} @endif >pending</option>
                                                                    <option value="1" id="approved" @if (isset($document_data['additional_document_3']) && $document_data['additional_document_3']['is_approved'] == 1) {{'Selected'}} @endif  >approved</option>
                                                                    <option value="2" id="rejected"  @if (isset($document_data['additional_document_3']) && $document_data['additional_document_3']['is_approved'] == 2) {{'Selected'}} @endif  >rejected</option>
                                                                </select>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <div class="doc_item_wrap">
                                        <div class="doc_heading">
                                            <h4>SIN</h4>
                                            @if (isset($document_data['sin']) && $document_data['sin']['is_approved'] == 1)
                                                <div class="doc_status_approved">
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                </div>
                                            @elseif (isset($document_data['sin']) && $document_data['sin']['is_approved'] == 2)
                                                <div class="doc_status_pending">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="doc_item">
                                            <div class="doc_fields sin_fields">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-12">
                                                            <label>SIN:</label>
                                                            @if (isset($document_data['sin']['document_data']))
                                                                <input type="text" name="sin" class="form-control" value={{$document_data['sin']['document_data']}} / disabled>
                                                            @endif
                                                        </div>

                                                        <div class="col-md-4 col-xs-12">
                                                            <label>Expiry Date:</label>
                                                            @if (isset($document_data['sin']))
                                                                <input type="date" class="form-control" class="form-control" id="sin_date" name="sin_date" value= @if (isset($document_data['sin']) && $document_data['sin']['exp_date'] != ''){{ $document_data['sin']['exp_date']}} @endif/ disabled>
                                                            @else
                                                                <div>Not available</div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-4 col-xs-12">
                                                            <label>Status</label>
                                                            @if (isset($document_data['sin']))
                                                                <select name="sin_status" class="doc-status-change form-control" id="{{$joeyDocumentVerification->id}}" disabled>
                                                                    <option value="0" id="pending" @if (isset($document_data['additional_document_3']) && $document_data['sin']['is_approved'] == 0) {{'Selected'}} @endif >pending</option>
                                                                    <option value="1" id="approved" @if (isset($document_data['additional_document_3']) && $document_data['sin']['is_approved'] == 1) {{'Selected'}} @endif  >approved</option>
                                                                    <option value="2" id="rejected"  @if (isset($document_data['additional_document_3']) && $document_data['sin']['is_approved'] == 2) {{'Selected'}} @endif  >rejected</option>
                                                                </select>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>--}}

                            </div>





                        </div>

                </div>
                </form>
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
        });
    </script>
@stop
