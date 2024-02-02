{{--
@if(can_access_route('postal-code.create',$userPermissoins))
<button class="btn btn-success createPostalCode" data-toggle="modal" data-email_address='{"email_address":"{{$record->email_address}}"}'>
    <i class="fa fa-plus"></i>
    Postal Code

</button>
<br>
@endif
<br>
@if(can_access_route('zone.create',$userPermissoins))
    <button class="btn btn-success createZones" data-toggle="modal" data-email_address='{"email_address":"{{$record->email_address}}"}'>
        <i class="fa fa-plus"></i>
        Zone
    </button>
@endif--}}
<style>
    .custom_list {
        float: left;
        width: 100%;
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        align-items: center;
    }
    .custom_list label.control-label {
        margin-left: 10px;
        width: 100%;
        display: block;
        text-align: left;
    }
    .list_divider {
        float: left;
        width: 100%;
        display: grid;
        grid-gap: 2%;
        grid-template-columns: 49% 49%;
        grid-row-gap: 8px;
    }
    .form-group {
        float: left;
        width: 100%;
    }
    .list_btn .col-md-offset-2.col-md-10 {
        margin: 0;
        float: left;
        width: 100%;
        text-align: left;
        padding-left: 50px;
        padding-top: 20px;
    }
    .createZones {
        margin: 2px 0px 0px 0px;
        width: 100%;
    }
    .request_message {
        font-weight: bold;
    }
    .badge.badge-light {
        background-color: red;
        color: white;
    }
    .permission-count {
        width: 100%;
    }
</style>

@if(can_access_route('micro-hub.hubProfileEdit.edit',$userPermissoins))

    <a href="{{route('micro-hub.hubProfileEdit.edit', $record->id)}}" title="Edit"
       class="btn btn-primary permission-count">
        <i class="fa fa-pencil-square"> Edit
            @if(count($hub_process_in_active) > 0)
                  <span class="badge badge-light">
                      <label>
                      {{count($hub_process_in_active)}}
                 </label>
                  </span>
            @endif
        </i>
    </a>
@endif

@if(can_access_route('postal-code.create',$userPermissoins))
    @if(isset($hub_process))
        @if(in_array('zone-creation',$hub_process))
            <button class="btn btn-success createPostalCode" data-toggle="modal" data-email_address='{"email_address":"{{$record->email_address}}"}'>
                <i class="fa fa-plus"></i>
                Postal Code

            </button>
            <br>
        @endif
    @endif
@endif
{{--@if(can_access_route('micro-hub.HubPermission.update',$userPermissoins))--}}
    {{--<button data-hub_permission_values='{{$record->dashboardUser->id}}' class='btn btn-warning fa fa-pencil-square hubPermission'>--}}
        {{--Hub Permission--}}
    {{--</button>--}}
{{--@endif--}}

@if(can_access_route('zone.create',$userPermissoins))
    @if(isset($hub_process))
        @if(!in_array('zone-creation',$hub_process))
            <br>
            <button class="btn btn-success createZones" data-toggle="modal" data-email_address='{"email_address":"{{$record->email_address}}"}'>
                <i class="fa fa-plus"></i>
                Zone
            </button>
        @endif
    @endif
@endif




<!--model-for-hub-permission-open-->
{{--<div class="modal fade" id="hub-permission-modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Delivery Process Type *</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-12 hoverable-dropdown-main-wrap">
                            <form method="POST" name="subAdmin" action="{{ route('micro-hub.HubPermission.update') }}" class="form-horizontal"
                                  role="form" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="user_id" id="model_hub_permission_id" value=''>
                                <ul class="hoverable-dropdown-main-ul">
                                    <div class="form-group">
                                        <div class="col-md-12 list_divider">
                                            @foreach($deliveryProcessTypes as $deliveryProcessType)

                                                <div class="custom_list">
                                                    <label class="control-label">{{$deliveryProcessType->process_title}}</label>
                                                    <input type="checkbox" class="form-control "  name="delivery_process_type[]"
                                                           value="{{$deliveryProcessType->id}}"
                                                           @if(!empty($hub_process))
                                                           @if(in_array($deliveryProcessType->process_label,$hub_process))
                                                           checked
                                                            @endif
                                                            @endif/>
                                                    @if(!empty($hub_process))
                                                        @if(in_array($deliveryProcessType->process_label,$hub_process_in_active))
                                                            &nbsp &nbsp
                                                            <div class="request_message">
                                                                 Requested
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            @endforeach

                                        </div>
                                        @if ($errors->has('hub_id'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('hub_id') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </ul>
                                <div class="form-group list_btn">
                                    <div class="col-md-offset-2 col-md-10">
                                        <input type="submit" class="btn blue" id="save" value="Save">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>--}}
<!--model-for-hub-permission-close-->
<script>
    //Call to open modal
    $(document).on('click', '.hubPermission', function (e) {
        // getting data from button and send to model
        let passing_data = $(this).attr("data-hub_permission_values");
        // showing model and getting el of model
        let model_el = $('#hub-permission-modal').modal();
        // setting data to model
        $('#model_hub_permission_id').val(passing_data);
    });

</script>