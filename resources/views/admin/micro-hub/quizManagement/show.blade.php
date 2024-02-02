@extends('admin.layouts.app')

@section('css')
    <style>
    .correctanswer
    {
        color:red;
        font-weight: bold;
    }
    </style>
    <link href="{{ asset('assets/admin/css/customPreview.css') }}" rel="stylesheet" type="text/css"/>
@stop


@section('content')
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle }} <small></small></h3>
        {{ Breadcrumbs::render('quiz-management-list.show', $quizManagement) }}
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
                        <i class="fa fa-eye"></i> {{ $pageTitle }}
                    </div>
                </div>

                <div class="portlet-body">

                    <h4>&nbsp;</h4>

                    <div class="form-horizontal" role="form">

                        <div class="row  details-page-wrap">
                            <!--show-details-box-open-->
                            <div class="col-sm-12 show-details-box">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th class="text-center" style="width: 10%">Question No.</th>
                                            <th class="text-center" style="width: 20%">Question</th>

                                            <th class="text-center"style="width: 20%">Answers</th>

                                            <th class="text-center"style="width: 10%">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                     @foreach($quizManagement->questionsMicroHub as $key => $question )




                                            <tr>
                                                <td class="text-center">{{$question->id}}</td>
                                                <td class="text-center" style="text-align: left">{{ $question->question??''}}

                                                    <img style="max-width: 350px;height: 70px;margin-top: 4px ; margin-left: 76px;   " onClick="preview(this);"  src="{{$question->image}}" />

                                                </td>

                                                <td class="text-center">
                                                @foreach($question->answers as $key => $answer )

                                                    <li class="text-center @if($question->correct_answer_id == $answer->id) correctanswer @endif" style="text-align: left" id="{{ $answer->id}}">{{ $answer->answer??''}}</li>
                                                        <img style="max-width: 350px;height: 70px;margin-top: 4px" onClick="preview(this);"  src="{{$answer->image}}" />
                                                @endforeach
                                                </td>

                                                <td class="text-center">

                                                    <a href="{!! URL::route('quiz-management-list.edit', $question->id) !!}" title="Edit"
                                                       class="btn btn-xs btn-primary">
                                                        <i class="fa fa-pencil-square"></i>
                                                    </a>

                                                    <a class="btn btn-xs btn-danger delete" type="button" title="Delete" data-toggle="modal" data-target="#deleteModal{{ $question->id }}">
                                                        <i class="fa fa-trash-o"></i>
                                                    </a>
                                                    <div id="deleteModal{{ $question->id }}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                                                aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title">Confirm Delete</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to delete this quiz?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                                                                    {!! Form::model($question, ['method' => 'delete',  'url' => 'micro-hub/quiz-management-list/'.$question->id, 'class' =>'form-inline form-delete']) !!}
                                                                    {!! Form::hidden('id', $question->id) !!}
                                                                    {!! Form::submit('Yes', ['class' => 'btn btn-success btn-flat']) !!}
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>






                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--show-details-box-close-->

                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10">
                                <button class="btn black" id="cancel"
                                        onclick="window.location.href = '{!! URL::route('quiz-management-list.index') !!}'">
                                    Back..
                                </button>
                            </div>
                        </div>
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
    <script src="{{ asset('assets/admin/scripts/custom/customPreview.js') }}"></script>
    <script>
        jQuery(document).ready(function () {
            // initiate layout and plugins
            App.init();
            Admin.init();
        });

        $(document).ready(function()
        {
            $( ".accordion" ).click(function()
            {
                //toggleactiveclass
                if ($(this).hasClass('active')) {
                    $(this).removeClass('active');
                } else {
                    $(this).addClass('active');
                }

                //addcssClass
                if ($(this).hasClass('active')) {
                    $(this).parent().find(".panell").css({
                        "maxHeight": "20px",
                    });
                } else {
                    $(this).parent().find(".panell").css({
                        "maxHeight": "0px",
                    });
                }
            });
        });
    </script>
@stop
