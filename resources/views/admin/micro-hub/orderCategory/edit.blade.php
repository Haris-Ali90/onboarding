@extends('admin.layouts.app')

@section('content')
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">{{ $pageTitle }} <small></small></h3>
        {{ Breadcrumbs::render('order-category-list.edit', $orderCategory) }}
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

                <form method="POST" action="{{ route('order-category-list.update', $orderCategory->id) }}" class="form-horizontal" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name" class="col-md-2 control-label"> Name *</label>
                        <div class="col-md-4">
                            <input type="text" name="name" maxlength="150" value="{{ old('name', $orderCategory->name) }}" class="form-control" />
                        </div>
                        @if ($errors->has('name'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                        @endif
                    </div>



                    <div class="form-group">
                        <label for="order_count" class="col-md-2 control-label">Type *</label>
                        <div class="col-md-4">

                            <select  id="category_type" name="category_type" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                <option  value="" disabled>Please select type</option>

                                <option id="basic" value="basic"  {{--@if ($orderCategory->type == 'basic') selected="selected"@endif--}} >Basic</option>

                                <option id="special" value="special"  {{--@if ($orderCategory->type == 'special') selected="selected"@endif--}}>Special</option>
                            </select>
                        </div>

                    </div>


                    <div class="form-group" id="div-count" style="display: none;">
                        <label class="col-md-2 control-label">Count</label>
                        <div class="col-md-4">
                            <input type="number" id="count" name="count" value="{{ old('count', $orderCategory->order_count) }}" class="form-control" min="0" max="100"  />
                        </div>
                    </div>


                    {{--<div id="count" style="display: none;">--}}
                    {{--<label for="count">Count</label> <input type="text" id="count" name="count" /><br />--}}
                    {{--</div>--}}



                    <div class="form-group">
                        <label for="score" class="col-md-2 control-label">Score *</label>
                        <div class="col-md-4">
                            <input type="text" name="score"  value="{{ old('score', $orderCategory->score) }}"
                                   class="form-control"/>
                        </div>
                        @if ($errors->has('score'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('score') }}</strong>
                                    </span>
                        @endif
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
        window.location.href = "{{route('order-category-list.index') }}";
   });

    $('#category_type').val('{{$orderCategory->type}}');
    selection_hendler();

    // selection  hendler
    function selection_hendler()
    {
        let selected_value = $('#category_type').val();
        if(selected_value == 'special')
        {
            $('#count').prop('required',true);
            $('#div-count').show();
        }
        else
        {
            $('#count').prop('required',false);
            $('#div-count').hide();
        }
    }

    $('#category_type').change(function(){
        selection_hendler();
    });
});

</script>
@stop
