@if(can_access_route('micro-hub.users.statusUpdate',$userPermissoins))
    <select name="status" class="doc-status-change form-control" id="{{$record->id}}">
        <option value="0" id="pending" {{ $record->status == 0 ? 'Selected' :''}} >Pending</option>
        <option value="1" id="approved" {{ $record->status == 1 ? 'Selected' :''}} >Approved</option>
        <option value="2" id="rejected" {{ $record->status == 2 ? 'Selected' :''}} >Declined</option>

    </select>
@endif



