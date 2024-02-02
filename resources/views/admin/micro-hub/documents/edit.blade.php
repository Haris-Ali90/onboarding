@extends('admin.layouts.app')

@section('content')
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">{{ $pageTitle }} <small></small></h3>
        {{ Breadcrumbs::render('micro-hub.documentList.edit', $document->id) }}
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
                    <i class="fa fa-edit"></i> {{ $pageTitle }}
                </div>
            </div>

            <div class="portlet-body">

                <h4>&nbsp;</h4>

                <form method="POST" action="{{ route('micro-hub.documentList.update', $document->id) }}" class="form-horizontal" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label for="name" class="col-md-2 control-label">Document Name *</label>
                        <div class="col-md-4">
                            <input type="text" name="name" maxlength="150" value="{{ old('name', $document->document_name) }}"
                                   class="form-control"/>
                        </div>
                        @if ($errors->has('name'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                        @endif
                    </div>


                    <div class="form-group" id="file_type" >
                        <label for="order_count" class="col-md-2 control-label">Mandatory</label>
                        <div class="col-md-4">
                            <select  id="file_type" name="file_type" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                <option id="0" value="0" @if ($document->is_optional == '0') selected="selected"@endif  >Yes</option>
                                <option id="1" value="1" @if ($document->is_optional == '1') selected="selected"@endif >No</option>
                            </select>
                        </div>
                        @if ($errors->has('file_type'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('file_type') }}</strong>
                                    </span>
                        @endif

                    </div>





                    <div class="form-group" id="document_type" >
                        <label class="col-md-2 control-label">Document Type</label>
                        <div class="col-md-4">
                            <select  id="document_type" name="document_type" class="js-example-basic-multiple form-control col-md-7 col-xs-12 test-11 " >
                                <option  value="file" @if ($document->document_type == 'file') selected="selected"@endif >File</option>
                                <option  value="text" @if ($document->document_type == 'text') selected="selected"@endif >Text</option>
                                <option value="sin" @if ($document->document_type == 'sin') selected="selected"@endif >Sin</option>
                            </select>
                        </div>
                    </div>
                                   @if ($errors->has('document_type'))
                                       <span class="help-block">
                                                   <strong>{{ $errors->first('document_type') }}</strong>
                                               </span>
                                   @endif

                    <div class="form-group" id="limit" style="display: none;">
                        <label class="col-md-2 control-label">Max. Character limit</label>
                        <div class="col-md-4">
                            <input type="number" id="character_limit" name="max_characters_limit" value="{{ old('max_characters_limit', $document->max_characters_limit) }}" class="form-control" min="0" max="100" />
                        </div>
                    </div>

                    <div class="form-group" id="is_expiry_date">
                        <label class="col-md-2 control-label">Is Expiry Date</label>
                        <div class="col-md-4">
                            <select  id="is_expiry_date" name="is_expiry_date" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                <option value="1" @if ($document->exp_date == '1') selected="selected"@endif >Yes</option>
                                <option  value="0" @if ($document->exp_date == '0') selected="selected"@endif >No</option>
                            </select>
                        </div>
                    </div>
                                           @if ($errors->has('is_expiry_date'))
                                               <span class="help-block">
                                                           <strong>{{ $errors->first('is_expiry_date') }}</strong>
                                                       </span>
                                           @endif




                    <div class="form-group" id="option" >
                        <label class="col-md-2 control-label">Upload option</label>
                        <div class="col-md-4">
                            <select  id="option" name="option" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                <option value="1" @if ($document->upload_option == '1') selected="selected"@endif  >Yes</option>
                                <option  value="0" @if ($document->upload_option == '0') selected="selected"@endif >No</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            <input type="submit" class="btn blue" id="save" value="Save">
                            <input type="button" class="btn black" name="cancel" id="cancel" value="Cancel">
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
<script type="text/javascript" src="{{ asset('assets/admin/plugins/ckeditor/ckeditor.js')}}"></script>
<script src="{{ asset('assets/admin/scripts/core/app.js') }}"></script>
<script>
jQuery(document).ready(function() {
   // initiate layout and plugins
   App.init();
   Admin.init();
   $('#cancel').click(function() {
        window.location.href = "{{route('micro-hub.documentList.index') }}";
   });

    $('#file_type').val('{{$document->is_optional}}');
    $('#document_type').val('{{$document->document_type}}');
    $('#is_expiry_date').val('{{$document->exp_date}}');
    $('#option').val('{{$document->upload_option}}');


    selection_hendler();




    function selection_hendler()
    {
        let selected_value = $('.test-11').val();

        console.log(selected_value);
        if(selected_value == 'text')
        {
            $('#character_limit').prop('required',true);
            $('#limit').show();
        }
        else
        {
            $('#character_limit').prop('required',false);
            $('#limit').hide();
        }
    }
    $('.test-11').change(function(){
        selection_hendler();
    });


});

</script>
@stop
