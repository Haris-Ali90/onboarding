@extends('admin.layouts.app')

@section('content')
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle ?? '' }} <small></small></h3>

        {{ Breadcrumbs::render('training-list.edit', $training ?? '') }}
        <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        @include( 'admin.layouts.loader' )
        <div class="col-md-12">

        {{--@include('admin.partials.errors')--}}

        <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet box blue">

                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-plus"></i> {{ $pageTitle ?? '' }}
                    </div>
                </div>

                <div class="portlet-body">

                    <h4>&nbsp;</h4>

                    <form method="POST" action="{{ route('training-list.update', $training->id) }}" class="form-horizontal" role="form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="title" class="col-md-2 control-label">Title *</label>
                            <div class="col-md-4">
                                <input type="text" name="title" maxlength="150" value="{{ old('title',$training->title) }}"
                                       class="form-control document-title" required/>
                            </div>
                            @if ($errors->has('title'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="order_count" class="col-md-2 control-label">Select Category *</label>
                            <div class="col-md-4">
                                <select id="category_id" name="category_id" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                    <option  value="" disabled selected>Please select category</option>
                                    @foreach($order_categories as $oc)
                                        <option value="{{$oc->id}}"
                                                @if($oc->id == $training->order_category_id)
                                                selected="selected"
                                                @endif
                                        >{{$oc->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Is Mandatory*</label>
                            <div class="compulsory col-md-4" >

                                <select id="compulsory" name="compulsory"
                                        class="form-control col-md-7 col-xs-12" required>
                                    <option value="1" @if($training->is_compulsory!=0) selected="selected"@endif>Mandatory</option>
                                    <option value="0" @if( $training->is_compulsory==0)selected="selected"@endif>Optional</option>
                                </select>
                            </div>

                        </div>





                        <div class="form-group" >
                            {{ Form::label('upload_file', 'Upload ', ['class'=>'col-md-2 control-label']) }}
                            <div class="col-md-4">
                                {{ Form::file('upload_file', ['class' => 'form-control upload_file','onchange'=>"checkFileExtension(this)"]) }}

                                  @if($training->type == 'image/jpeg')
                                    <img style="max-width: 150px;height: 150px;    margin-top: 10px;" onClick="preview(this);"  src="{{$training->url}}" />
                                @endif


                                @if($training->type == 'application/pdf' )
                                    <iframe style="max-width: 150px;height: 150px; margin-top: 10px;" onClick="preview(this);"  src="{{$training->url}}" ></iframe>
                                @endif
                                @if($training->type == 'document' )
                                    <a href="{{$training->url}}"
                                       target="_blank"><img src="{{\Illuminate\Support\Facades\URL::to('/') . '/backends/thumbnail.png'}}"
                                                            class="img-responsive avatar-view"/></a>
                                @endif
                                @if($training->type == 'video/mp4')
                                    <video style="max-width: 150px;height: 150px;" onClick="preview(this);"  src="{{$training->url}}" ></video>
                                @endif
                                {{--@if($training->type == 'document')--}}
                                    {{--< style="max-width: 150px;height: 150px;" onClick="preview(this);"  src="{{$training->url}}" ></>--}}
                                {{--@endif--}}
                            </div>
                            @if ( $errors->has('upload_file') )
                                <p class="help-block">{{ $errors->first('upload_file') }}</p>
                            @endif

                        </div>


                        <audio id="audio"></audio>
                        <div class="form-group" id="duration-block" style='display:none'>
                            {{ Form::label('Duration', 'Duration', ['class'=>'col-md-2 control-label','onchange'=>"checkFileExtension(this)"]) }}
                            <div class="col-md-4">
                                <input type='text' class="form-control" name='duration' id='duration' value="{{$training->duration}}" readonly>
                            </div>
                        </div>
                        <div class="form-group" >
                            <div class="col-md-offset-2 col-md-10">
                                <input type="submit" class="btn blue training-video" id="save" value="Save" >
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
    <script type="text/javascript" src="{{ asset('assets/admin/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{!! URL::to('assets/admin/scripts/core/app.js') !!}"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" integrity="sha256-b5ZKCi55IX+24Jqn638cP/q3Nb2nlx+MH/vMMqrId6k=" crossorigin="anonymous" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" integrity="sha256-5YmaxAwMjIpMrVlK84Y/+NjCpKnFYa8bWWBbUHSBGfU=" crossorigin="anonymous"></script>
    <script>
        jQuery(document).ready(function() {

            // initiate layout and plugins
            App.init();
            Admin.init();
            $('#cancel').click(function() {
                window.location.href = "{!! URL::route('training-list.index') !!}";
            });
        });



    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var type = "<?php echo $training->training_type; ?>";

            if(type == 'orderCategoryDD'){

               // document.getElementById('order_category_id').selectedIndex  = 0;
                $(".orderCategoryDD").show();
                $(".vendorDD").hide();
                $(".justshow").hide();

            }else{
               // document.getElementById('vendor_id').selectedIndex  = 0;
                $(".orderCategoryDD").hide();
                $(".vendorDD").show();
                $(".justshow").hide();


            }

            // submit tracking form action
            $(document).on('click', '.training-video', function (e){

                let title = $('.document-title').val();
                let orderCategoryId = $('#category_id').val();
                let compulsory = $('#compulsory').val();
                let uploadFile = $('.upload_file').val();
                if(orderCategoryId != null && compulsory != null && uploadFile != '' && title != '')
                {
                    showLoader();
                }
            });


        });
        $('.cat_type').change(function (){
            let selected_val = $(this).val();

            if(selected_val == 'orderCategoryDD'){

                document.getElementById('order_category_id').selectedIndex  = 0;
                $(".orderCategoryDD").show();
                $(".vendorDD").hide();
                $(".justshow").hide();

            }else{
                document.getElementById('vendor_id').selectedIndex  = 0;
                $(".orderCategoryDD").hide();
                $(".vendorDD").show();
                $(".justshow").hide();


            }
        });



        var objectUrl;

        $("#audio").on("canplaythrough", function(e){
            var seconds = e.currentTarget.duration;
            var duration = moment.duration(seconds, "seconds");
            var time = "";
            var hours = duration.hours();
            if (hours > 0) { time = hours + ":" ; }
			if(duration.seconds() < 10 && time + duration.minutes() < 10)
        	{
        	    time = "0"+hours + ":" + "0"+time + duration.minutes() + ":" +"0"+ duration.seconds();
        	}
        	else if (duration.seconds() < 10)
        	{
        	    time = "0"+hours + ":" + time + duration.minutes() + ":" +"0"+ duration.seconds();
        	}
        	else if (time + duration.minutes() < 10)
        	{
        	    time = "0"+hours + ":" + "0"+time + duration.minutes() + ":" + duration.seconds();
        	}
        	else
        	{
        	    time = "0"+hours + ":" + time + duration.minutes() + ":" + duration.seconds();
        	}
            //time = time + duration.minutes() + ":" + duration.seconds();
            if(time){
                $('#duration-block').show();
            }
            $("#duration").val(time);

        });

        $("#upload_file").change(function(e){
            var file = e.currentTarget.files[0];
            objectUrl = URL.createObjectURL(file);
            $("#audio").prop("src", objectUrl);
        });

        //Check extention of upload files
        function checkFileExtension(element) {
            var el = $(element);
            var selectedText = el.val();
            extension = selectedText.split('.').pop();
            var allowed_ext = ['jpeg','png','jpg','pdf','doc','docx','PNG','JPEG','JPG','DOC','DOCX','mp4','mov'];
            if (!allowed_ext.includes(extension)) {

                alert("Sorry! Invalid file. Allowed extensions are: jpeg, png, jpg,  pdf, doc, docx, 'mp4', 'mov'. ");
                location.reload();
            }
        };

    </script>
@stop
