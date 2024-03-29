@extends('admin.layouts.app')

@section('content')
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">{{ $pageTitle }} <small></small></h3>
        {{ Breadcrumbs::render('micro-hub.sub-admin.edit', $sub_admin) }}
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<link href="{{ asset('assets/admin/css/customPreview.css') }}" rel="stylesheet" type="text/css"/>
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

                <form method="POST" action="{{ route('micro-hub.sub-admin.update', $sub_admin->id) }}" class="form-horizontal" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label for="first_name" class="col-md-2 control-label">First Name *</label>
                        <div class="col-md-4">
                            <input type="text" name="first_name" maxlength="150" value="{{ old('first_name', $sub_admin->first_name) }}"
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
                            <input type="text" name="last_name" maxlength="32" value="{{ old('last_name', $sub_admin->last_name) }}"
                                   class="form-control"/>
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
                            <input type="text" name="email" maxlength="32" value="{{ old('email', $sub_admin->email) }}"
                                   class="form-control" readonly/>
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
                            <input type="text" name="phone" maxlength="32" value="{{ old('phone', $sub_admin->phone) }}"
                                   class="form-control phone_us"/>
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
                            <input type="text" name="address" maxlength="32" value="{{ old('address', $sub_admin->address) }}"
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
                                <option value="">Select an option</option>
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
                        <label for="password" class="col-md-2 control-label">Password</label>
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
                    <div>

                        {{ Form::label('upload_file', 'Upload Picture', ['class'=>'col-md-2 control-label']) }}
                        <div class="col-md-4">
                            {{ Form::file('upload_file', ['class' => 'form-control ','onchange'=>"checkFileExtension(this)"]) }}
                            <img style="max-width: 350px;height: 150px;margin-top: 4px" onClick="preview(this);"  src="{{$sub_admin->profile_picture}}" />

                        </div>
                        @if ( $errors->has('upload_file') )
                            <p class="help-block">{{ $errors->first('upload_file') }}</p>
                        @endif


                    </div>
                    </div>
                   {{-- <div class="form-group">
                        <label for="confirm_password" class="col-md-2 control-label">Confirm Password *</label>
                        <div class="col-md-4">
                            <input type="password" name="confirm_password" value="" class="form-control" />
                        </div>
                        @if ($errors->has('confirm_password'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('confirm_password') }}</strong>
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
<script type="text/javascript" src="{{ asset('assets/admin/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('assets/admin/scripts/core/app.js') }}"></script>
<script src="{{ asset('assets/admin/scripts/custom/customPreview.js') }}"></script>

<script>

    jQuery(document).ready(function() {
   // initiate layout and plugins
   App.init();
   Admin.init();
   $('#cancel').click(function() {
        window.location.href = "{{ route('micro-hub.sub-admin.index') }}";
   });
});

$(document).ready(function() {

    $('.phone_us').mask('+10000000000', {placeholder: "(+1xx)(xxx)(xxxx)"});
    make_option_selected('.role-type','{{ old('role',$sub_admin->role_id) }}');
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
