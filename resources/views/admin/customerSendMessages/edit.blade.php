@extends('admin.layouts.app')

@section('content')
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">{{ $pageTitle }} <small></small></h3>
        {{ Breadcrumbs::render('customer-send-messages.edit', $customer_send_message ?? '') }}
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


                <form method="POST"
                      action="{{ route('customer-send-messages.update', $customer_send_message->id) }}"
                      class="form-horizontal" role="form">
                    @csrf
                    @method('PUT')


                    <div class="form-group">
                        <label for="message" class="col-md-2 control-label">Message Here*</label>
                        <div class="col-md-4">
                            <textarea id="message" name="message" class="form-control" rows="6" cols="50" required="required" maxlength = "200" placeholder="Message (maximum 200 character)" >{{ old('message',$customer_send_message->message) }}</textarea>

                        </div>
                        @if ($errors->has('message'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('message') }}</strong>
                                    </span>
                        @endif
                    </div>

                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-10" style="margin-top: 5px;">
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
        window.location.href = "{{route('customer-send-messages.index') }}";
   });







/*    selection_hendler();

    // selection  hendler
    function selection_hendler()
    {
        let selected_value = $('#category_type').val();
        if(selected_value == 'special')
        {
            $('#count').prop('required',true);
            $('#count').show();
        }
        else
        {
            $('#count').hide();
            $('#count').prop('required',false);
        }
    }

    $('#category_type').change(function(){
        selection_hendler();
    });*/
});

</script>
@stop
