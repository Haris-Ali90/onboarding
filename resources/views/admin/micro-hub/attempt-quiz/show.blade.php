@extends('admin.layouts.app')

@section('content')
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle }} <small></small></h3>
        {{ Breadcrumbs::render('quiz-attempt.show', $attemptQuiz) }}
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

                        <!--details-page-wrap-open-->
                            <div class="row  details-page-wrap">
                                <!--show-details-box-open-->
                                <div class="col-sm-12 show-details-box">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th class="text-center" style="width: 10%">Question No.</th>
                                                <th class="text-center" style="width: 40%">Question</th>
                                                <th class="text-center"style="width: 40%">Answer</th>
                                                <th class="text-center"style="width: 40%">Correct</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @foreach($details as $key => $details )

                                                <tr>
                                                    <td class="text-center">{{$loop->iteration}}</td>
                                                    <td class="text-center">{{ (isset($details->question->question))?$details->question->question:'' }}</td>
                                                    <td class="text-center">{{(isset($details->answers->answer))?$details->answers->answer:'' }}</td>
                                                    <td class="text-center">
                                                        @if($details->is_correct!=0)
                                                              True
                                                            @else
                                                               False
                                                            @endif
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
                                        onclick="window.location.href = '{!! URL::route('quiz-attempt.index') !!}'">
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
    <script>
        jQuery(document).ready(function () {
            // initiate layout and plugins
            App.init();
            Admin.init();
        });
    </script>
@stop
