@extends('admin.layouts.app')

@section('content')
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle ?? '' }} <small></small></h3>
        {{ Breadcrumbs::render('quiz-management.create') }}
        <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
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
                    <form method="POST" action="{{ route('quiz-management.store') }}" class="form-horizontal"
                          role="form" enctype="multipart/form-data" >
                        @csrf
                        @method('POST')
                  {{--      <div class="form-group">
                            <label class="col-md-2 control-label ">Type *</label>
                            <div name="type" class="col-md-4">
                                <label>
                                    <input type="radio" class="cat_type" name="type" value="orderCategoryDD"  />By Order Category</label>

                                <label>
                                    <input type="radio" class="cat_type" name="type" value="vendorDD" />By Vendor</label>
                            </div>
                            @if ($errors->has('type'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                            @endif
                        </div>--}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">Select Category *</label>
                     {{--       <div class="justshow col-md-4">

                                <select id="" name="" class="form-control col-md-7 col-xs-12">
                                    required </select>
                            </div>--}}
                            <div class="orderCategoryDD col-md-4" >

                                <select id="order_category_id" name="order_category_id"
                                        class="form-control col-md-7 col-xs-12" required>
                                    <option value="" disabled selected>Please Select Option</option>
                                    @foreach($order_categories as $oc)
                                        <option value="{{$oc->id}}">{{$oc->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                      {{--      <div class="vendorDD col-md-4" hidden>


                                <select id="vendor_id" name="vendor_id" class="form-control col-md-7 col-xs-12"
                                        required>

                                    @foreach($vendors as $v)
                                        <option value="{{$v->id}}">{{$v->first_name}}</option>
                                    @endforeach

                                </select>
                            </div>--}}

                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Question *</label>
                            <div class="col-md-4">
                                <textarea name='question' id='someid' rows="10"
                                          class='form-control form-control col-md-7 col-xs-12'
                                          placeholder='Question..'  required>{{old('question')}}</textarea>
                            </div>
                            @if ($errors->has('question'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('question') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group" >
                            {{ Form::label('questionImage', 'Question Image *', ['class'=>'col-md-2 control-label']) }}
                            <div class="col-md-4">
                                {{ Form::file('questionImage', ['class' => 'form-control ']) }}
                            </div>
                            @if ( $errors->has('questionImage') )
                                <p class="help-block">{{ $errors->first('questionImage') }}</p>
                            @endif
                        </div>





                        <div class="form-group" id='ans1'>
                            <label class="col-md-2 control-label">Options *</label>
                            <div class="col-md-10 ">
                                <div class="col-md-12 col-xs-12" style="margin-left: -29px">

                                    <div class="col-md-5 col-xs-11">
                                        <input type='text' style="margin-bottom: 10px; padding-bottom: 12px;"
                                               name='ans[]' id='ans1' class='form-control' placeholder='Answer1..'
                                               value="{{ old('ans.0') }}" required/>

                                    </div>
                                        <div class="col-md-5 col-xs-11" >
                                            {{ Form::file('answer1', ['name'=>'image[]','class' => 'form-control ','onchange'=>"checkFileExtension(this)"]) }}
                                        </div>
                                        @if ( $errors->has('answer1') )
                                            <p class="help-block">{{ $errors->first('answer1') }}</p>
                                        @endif

                                    <div class="col-md-2 col-xs-1">
                                        <label class="label-answer"><input type='radio' id='right1' name='right' value="1" required >a.</label>
                                    </div>
                                </div>


                                <div class="col-md-12 col-xs-12" style="margin-left: -29px">
                                    <div class="col-md-5 col-xs-11"><br>
                                        <input type='text' style="margin-bottom: 10px; padding-bottom: 12px; "
                                               name='ans[]' id='ans2' class='form-control' placeholder='Answer2..'
                                               value="{{ old('ans.1') }}"  required/>
                                    </div>
                                    <div class="col-md-5 col-xs-11" ><br>
                                        {{ Form::file('answer2', [ 'name'=>'image[]','class' => 'form-control ','onchange'=>"checkFileExtension(this)"]) }}
                                    </div>
                                    @if ( $errors->has('answer2') )
                                        <p class="help-block">{{ $errors->first('answer2') }}</p>
                                    @endif
                                    <div class="col-md-2 col-xs-1">
                                        <label class="label-answer"><input type='radio' id='right2' name='right' value="2" required>b.</label>
                                    </div>
                                </div>



                                <div class="col-md-12 col-xs-12" style="margin-left: -29px">
                                    <div class="col-md-5 col-xs-11"><br>
                                        <input type='text' style="margin-bottom: 10px; padding-bottom: 12px; "
                                               name='ans[]' id='ans3' class='form-control' placeholder='Answer3..'
                                               value="{{ old('ans.2') }}" required />
                                           </div>
                                    <div class="col-md-5 col-xs-11" ><br>
                                        {{ Form::file('answer3', ['name'=>'image[]','class' => 'form-control ','onchange'=>"checkFileExtension(this)"]) }}
                                    </div>
                                    @if ( $errors->has('answer3') )
                                        <p class="help-block">{{ $errors->first('answer3') }}</p>
                                    @endif
                                    <div class="col-md-2 col-xs-1">
                                        <label class="label-answer"><input type='radio' id='right3' name='right' value="3" required>c.</label>
                                    </div>
                                </div>



                                <div class="col-md-12 col-xs-12" style="margin-left: -29px">
                                    <div class="col-md-5 col-xs-11"><br>
                                        <input type='text' style="margin-bottom: 10px; padding-bottom: 12px; "
                                               name='ans[]' id='ans4' class='form-control' placeholder='Answer4..'
                                               value="{{ old('ans.3') }}" required />
                                                </div>
                                    <div class="col-md-5 col-xs-11" ><br>
                                        {{ Form::file('answer4', ['name'=>'image[]','class' => 'form-control ','onchange'=>"checkFileExtension(this)"]) }}
                                    </div>
                                    @if ( $errors->has('answer4') )
                                        <p class="help-block">{{ $errors->first('answer4') }}</p>
                                    @endif
                                    <div class="col-md-2 col-xs-1">
                                        <label class="label-answer"><input type='radio' id='right4' name='right' value="4" required>d.</label>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="form-group" style="    padding-left: 131px;">
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
    <script type="text/javascript" src="{{ asset('assets/admin/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{!! URL::to('assets/admin/scripts/core/app.js') !!}"></script>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css"
          integrity="sha256-b5ZKCi55IX+24Jqn638cP/q3Nb2nlx+MH/vMMqrId6k=" crossorigin="anonymous"/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"
        integrity="sha256-5YmaxAwMjIpMrVlK84Y/+NjCpKnFYa8bWWBbUHSBGfU=" crossorigin="anonymous"></script>
    <script>
        jQuery(document).ready(function () {

            // initiate layout and plugins
            App.init();
            Admin.init();
            $('#cancel').click(function () {
                window.location.href = "{!! URL::route('quiz-management.index') !!}";
            });
        });
        $(document).ready(function () {
            $('#submit').click(function () {
                var radios = $('input[type=radio]')
                var current = radios.filter(':checked');
                var next = radios.eq(radios.index(current) + 1);
                if (next.length === 0) {
                    next = radios.first();
                }
                next.prop('checked', true);
            });
        });

        $('.cat_type').change(function () {
            let selected_val = $(this).val();

            if (selected_val == 'orderCategoryDD') {

                document.getElementById('order_category_id').selectedIndex = 0;
                $(".orderCategoryDD").show();
                $(".vendorDD").hide();
                $(".justshow").hide();
            } else {
                document.getElementById('vendor_id').selectedIndex = 0;
                $(".orderCategoryDD").hide();
                $(".vendorDD").show();
                $(".justshow").hide();
            }
        });
        //Check extention of upload files
        function checkFileExtension(element) {
            var el = $(element);
            var selectedText = el.val();
            extension = selectedText.split('.').pop();
            var allowed_ext = ['jpeg','png','jpg','PNG','JPEG','JPG'];
            if (!allowed_ext.includes(extension)) {
                alert("Sorry! Invalid file. Allowed extensions are: jpeg, png, jpg. ");
                location.reload();
            }
        };
    </script>
    <script type="text/javascript">

    </script>
@stop
