@extends('admin.layouts.app')

@section('content')
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle }} <small></small></h3>
        {{ Breadcrumbs::render('micro-hub.sub-admin.create') }}
        <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

{{--        @include('admin.partials.errors')--}}

        <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet box blue">

                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-plus"></i> {{ $pageTitle }}
                    </div>
                </div>

                <div class="portlet-body">

                    <h4>&nbsp;</h4>

                    <form method="POST" name="subAdmin" action="{{ route('micro-hub.sub-admin.store') }}" class="form-horizontal"
                          role="form" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="form-group">
                            <label for="first_name" class="col-md-2 control-label">First Name *</label>
                            <div class="col-md-4">
                                <input type="text" name="first_name" maxlength="150" value="{{ old('first_name') }}" required
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('first_name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="last_name" class="col-md-2 control-label">Last Name *</label>
                            <div class="col-md-4">
                                <input type="text" name="last_name" maxlength="32" value="{{ old('last_name') }}"
                                       class="form-control" required/>
                            </div>
                            @if ($errors->has('last_name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-2 control-label">Email *</label>
                            <div class="col-md-4">
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="form-control email-address" onchange="ValidateEmail(this)" />
                            </div>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="phone" class="col-md-2 control-label">Phone *</label>
                            <div class="col-md-4">
                                <input type="text" name="phone" maxlength="32" value="{{ old('phone') }}"
                                       class="form-control phone_us" required/>
                            </div>
                            @if ($errors->has('phone'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="address" class="col-md-2 control-label">Address (Optional)</label>
                            <div class="col-md-4">
                                <input type="text" name="address" maxlength="32" value="{{ old('address') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('address'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="rights" class="col-md-2 control-label">Role Type *</label>
                            <div class="col-md-4">
                                <select class="form-control col-md-7 col-xs-12 role-type" name="role" required>
                                    <option value="" disabled selected>Select an option</option>
                                    @foreach($role_list as $role)
                                        <option value="{{$role->id}}">{{$role->display_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('role'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('role') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-2 control-label">Password *</label>
                            <div class="col-md-4">
                                <input type="password" name="password" maxlength="32" value=""
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="confirm_password" class="col-md-2 control-label">Confirm Password *</label>
                            <div class="col-md-4">
                                <input type="password" name="confirm_password" value="" class="form-control" />
                            </div>
                            @if ($errors->has('confirm_password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('confirm_password') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group" >
                            {{ Form::label('upload_file', 'Upload Picture', ['class'=>'col-md-2 control-label']) }}
                            <div class="col-md-4">
                                {{ Form::file('upload_file', ['class' => 'form-control ','onchange'=>"checkFileExtension(this)"]) }}
                            </div>
                            @if ( $errors->has('upload_file') )
                                <p class="help-block">{{ $errors->first('upload_file') }}</p>
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
    <script type="text/javascript" src="{{ asset('assets/admin/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/admin/scripts/core/app.js') }}"></script>
    <script>
        jQuery(document).ready(function () {
            // initiate layout and plugins
            App.init();
            Admin.init();
            $('#cancel').click(function () {
                window.location.href = "{{route('micro-hub.sub-admin.index') }}";
            });
        });

        function ValidateEmail(inputText)
        {
            var mailFormat = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if(inputText.value.match(mailFormat))
            {
                document.subAdmin.email.focus();
                return true;
            }
            else
            {
                alert("You have entered an invalid email address!");
                $('.email-address').val('');
                document.subAdmin.email.focus();
                return false;
            }
        }

        $(document).ready(function() {
            $('.phone_us').change(function(){
                $('.phone_us').mask('+10000000000', {placeholder: "(+1xx)(xxx)(xxxx)"});
            });

        });
        $(document).ready(function() {
            $('.phone_us').mask('+10000000000', {placeholder: "(+1xx)(xxx)(xxxx)"});
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
@stop
