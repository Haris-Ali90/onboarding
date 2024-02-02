@extends('admin.layouts.app')

@section('content')
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">{{ $pageTitle ?? '' }} <small></small></h3>
        {{ Breadcrumbs::render('vendors.edit', $vendor) }}
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">

 {{--       @include('admin.partials.errors')--}}

        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box blue">

            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-edit"></i> {{ $pageTitle }}
                </div>
            </div>

            <div class="portlet-body">

                <h4>&nbsp;</h4>

                <form method="POST" action="{{ route('vendors.update', $vendor->id) }}" class="form-horizontal" role="form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="order_count" class="col-md-2 control-label">Vendors *</label>
                        <div class="col-md-4">
                            <select id="category_id" name="vendors_id" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                <option  value="">please select your vendor</option>
                                @foreach($vendors as $vc)
                                    <option  value="{{$vc->id}}"
                                             @if ($vc->id == $vendor->id)
                                             selected="selected"
                                        @endif
                                    >{{$vc->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('vendors_id'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('vendors_id') }}</strong>
                                    </span>
                        @endif
                    </div>


                    <div class="form-group">
                        <label for="order_count" class="col-md-2 control-label">Type *</label>
                        <div class="col-md-4">

                            <select  id="type" name="type" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                <option  value="">Please select type</option>

                                <option id="basic" value="basic" >Basic</option>

                                <option id="special" value="special" >Special</option>
                            </select>
                        </div>
                        @if ($errors->has('type'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                        @endif

                    </div>


                    <div class="form-group" id="count" style="display: none;">
                        <label class="col-md-2 control-label">Count</label>
                        <div class="col-md-4">
                            <input type="text" id="count-input" name="count" value="{{ old('count', $vendor->order_count) }}" class="form-control" />
                        </div>
                    </div>



                    <div class="form-group">
                        <label for="score" class="col-md-2 control-label">Score *</label>
                        <div class="col-md-4">
                            <input type="text" name="score"  value="{{ old('score', $vendor->score) }}"
                                   class="form-control"/>
                        </div>
                        @if ($errors->has('score'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('score') }}</strong>
                                    </span>
                        @endif
                    </div>



                    {{--      <div class="form-group">
                              <label for="order_count" class="col-md-2 control-label">Order Count *</label>
                              <div class="col-md-4">
                                  <input  type="text"  id="order_count" maxlength="190" name="order_count" value="{{ $vendor->order_count }}" class="form-control" />
                              </div>
                              @if ($errors->has('order_count'))
                                  <span class="help-block">
                                              <strong>{{ $errors->first('order_count') }}</strong>
                                          </span>
                              @endif
                          </div>--}}

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
{{--<script type="text/javascript" src="{!! URL::to('assets/admin/plugins/ckeditor/ckeditor.js') !!}"></script>--}}
<script src="{{ asset('assets/admin/scripts/core/app.js') }}"></script>
<script>
jQuery(document).ready(function() {
    // initiate layout and plugins
    App.init();
    Admin.init();
    $('#cancel').click(function() {
        window.location.href = "{!! URL::route('vendors.index') !!}";
    });


    $('#type').val('{{$vendor->type}}');
    selection_hendler();

    // selection  hendler
    function selection_hendler()
    {
        let selected_value = $('#type').val();
        if(selected_value == 'special')
        {
            $('#count-input').prop('required',true);
            $('#count').show();
        }
        else
        {
            $('#count').hide();
            $('#count-input').prop('required',false);
        }
    }

    $('#type').change(function(){
        selection_hendler();
    });

});


</script>
@stop
