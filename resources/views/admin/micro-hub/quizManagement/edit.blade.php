@extends('admin.layouts.app')

@section('css')
    <link href="{{ asset('assets/admin/css/customPreview.css') }}" rel="stylesheet" type="text/css"/>
@stop
@section('content')
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle ?? '' }} <small></small></h3>
        {{ Breadcrumbs::render('quiz-management-list.edit', $quizManagement ?? '') }}
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
                        <i class="fa fa-edit"></i> {{ $pageTitle ?? '' }}
                    </div>
                </div>

                <div class="portlet-body">

                    <div class="portlet-body">

                        <h4>&nbsp;</h4>

                        <form method="POST"
                              action="{{ route('quiz-management-list.update', $quizManagement->id) }}"
                              class="form-horizontal" role="form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="order_count" class="col-md-2 control-label">Select Category *</label>
                                <div class="col-md-4">
                                    <select id="category_id" name="category_id" class="js-example-basic-multiple form-control col-md-7 col-xs-12" >
                                        <option  value="" disabled selected>Please select category</option>
                                        @foreach($Ordercategories as $oc)
                                            <option value="{{$oc->id}}"
                                                    @if($oc->id == $quizManagement->order_category_id)
                                                    selected="selected"
                                                    @endif

                                            >{{$oc->name}}</option>
                                        @endforeach


                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Question *</label>
                                <div class="col-md-4">
                                <textarea name='question' id='someid' rows="10"
                                          class='form-control form-control col-md-7 col-xs-12'
                                          placeholder='Question..'  required>{{ $quizManagement->question}}</textarea>
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
                                    <img style="max-width: 350px;height: 150px;margin-top: 4px" onClick="preview(this);"  src="{{$quizManagement->image}}" />
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
                                                   value="{{$dataQuizAnswer[0]['answer']}}" required/>
                                            <input type='hidden' name='old-id[]' value="{{$dataQuizAnswer[0]['id']}}"/>

                                        </div>
                                        <div class="col-md-5 col-xs-11" >
                                            {{ Form::file('answer1', ['name'=>'image[]','class' => 'form-control ','onchange'=>"checkFileExtension(this)"]) }}
                                            <img style="max-width: 350px;height: 150px;margin-top: 4px" onClick="preview(this);"  src="{{$dataQuizAnswer[0]->image}}" />
                                        </div>
                                        @if ( $errors->has('answer1') )
                                            <p class="help-block">{{ $errors->first('answer1') }}</p>
                                        @endif


                                        <div class="col-md-2 col-xs-1">
                                            <label><input type='radio' id='right1' name='right'
                                                          value={{$dataQuizAnswer[0]['id']}}
                                                          @if($dataQuizAnswer[0]['id'] == $quizManagement->correct_answer_id) checked @endif
                                                >a.</label>
                                        </div>
                                    </div>


                                    <div class="col-md-12 col-xs-12" style="margin-left: -29px">
                                        <div class="col-md-5 col-xs-11">
                                            <input type='text' style="margin-bottom: 10px; padding-bottom: 12px; "
                                                   name='ans[]' id='ans2' class='form-control' placeholder='Answer2..'
                                                   value="{{$dataQuizAnswer[1]['answer']}}" required />
                                            <input type='hidden' name='old-id[]' value="{{$dataQuizAnswer[1]['id']}}"/>

                                        </div>
                                        <div class="col-md-5 col-xs-11" >
                                            {{ Form::file('answer2', [ 'name'=>'image[]','class' => 'form-control ','onchange'=>"checkFileExtension(this)"]) }}
                                            <img style="max-width: 350px;height: 150px;margin-top: 4px" onClick="preview(this);"  src="{{$dataQuizAnswer[1]->image}}" />
                                        </div>
                                        @if ( $errors->has('answer2') )
                                            <p class="help-block">{{ $errors->first('answer2') }}</p>
                                        @endif
                                        <div class="col-md-2 col-xs-1">
                                            <label><input type='radio' id='right2' name='right'
                                                          value={{$dataQuizAnswer[1]['id']}}
                                                          @if($dataQuizAnswer[1]['id'] == $quizManagement->correct_answer_id) checked @endif
                                                >b.</label>
                                        </div>
                                    </div>



                                    <div class="col-md-12 col-xs-12" style="margin-left: -29px">
                                        <div class="col-md-5 col-xs-11">
                                            <input type='text' style="margin-bottom: 10px; padding-bottom: 12px; "
                                                   name='ans[]' id='ans3' class='form-control' placeholder='Answer3..'
                                                   value="{{$dataQuizAnswer[2]['answer']}}" required />
                                            <input type='hidden' name='old-id[]' value="{{$dataQuizAnswer[2]['id']}}"/>
                                        </div>

                                        <div class="col-md-5 col-xs-11" >
                                            {{ Form::file('answer3', ['name'=>'image[]','class' => 'form-control ','onchange'=>"checkFileExtension(this)"]) }}
                                            <img style="max-width: 350px;height: 150px;margin-top: 4px" onClick="preview(this);"  src="{{$dataQuizAnswer[2]->image}}" />
                                        </div>
                                        @if ( $errors->has('answer3') )
                                            <p class="help-block">{{ $errors->first('answer3') }}</p>
                                        @endif
                                        <div class="col-md-2 col-xs-1">
                                            <label><input type='radio' id='right3' name='right'
                                                          value={{$dataQuizAnswer[2]['id']}}
                                                          @if($dataQuizAnswer[2]['id'] == $quizManagement->correct_answer_id) checked @endif
                                                >c.</label>
                                        </div>
                                    </div>


                                    <div class="col-md-12 col-xs-12" style="margin-left: -29px">
                                        <div class="col-md-5 col-xs-11">
                                            <input type='text' style="margin-bottom: 10px; padding-bottom: 12px; "
                                                   name='ans[]' id='ans4' class='form-control' placeholder='Answer4..'
                                                   value="{{$dataQuizAnswer[3]['answer']}}" required />
                                            <input type='hidden' name='old-id[]' value="{{$dataQuizAnswer[3]['id']}}"/>

                                        </div>
                                        <div class="col-md-5 col-xs-11" >
                                            {{ Form::file('answer4', ['name'=>'image[]','class' => 'form-control ','onchange'=>"checkFileExtension(this)"]) }}
                                            <img style="max-width: 350px;height: 150px;margin-top: 4px" onClick="preview(this);"  src="{{$dataQuizAnswer[3]->image}}" />
                                        </div>
                                        @if ( $errors->has('answer4') )
                                            <p class="help-block">{{ $errors->first('answer4') }}</p>
                                        @endif
                                        <div class="col-md-2 col-xs-1">
                                            <label><input type='radio' id='right4' name='right'
                                                          value={{$dataQuizAnswer[3]['id']}}
                                                          @if($dataQuizAnswer[3]['id'] == $quizManagement->correct_answer_id) checked @endif
                                                >d.</label>
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
            {{--<script type="text/javascript" src="{!! URL::to('assets/admin/plugins/ckeditor/ckeditor.js') !!}"></script>--}}
            <script src="{{ asset('assets/admin/scripts/core/app.js')}}"></script>
            <script src="{{ asset('assets/admin/scripts/custom/customPreview.js') }}"></script>
            <script>
                jQuery(document).ready(function () {
                    // initiate layout and plugins
                    App.init();
                    Admin.init();
                    $('#cancel').click(function () {
                        window.location.href = "{!! URL::route('quiz-management-list.index') !!}";
                    });

                });

                //Check extention of upload files
                function checkFileExtension(element) {
                    var el = $(element);
                    var selectedText = el.val();
                    extension = selectedText.split('.').pop();
                    var allowed_ext = ['jpeg','png','jpg','doc','docx','pdf','PNG','JPEG','JPG','DOC','DOCX','mp4','mov'];
                    if (!allowed_ext.includes(extension)) {

                        alert("Sorry! Invalid file. Allowed extensions are: jpeg, png, jpg, doc, docx, 'mp4', 'mov' & pdf. ");
                        location.reload();
                    }
                };


            </script>
@stop
