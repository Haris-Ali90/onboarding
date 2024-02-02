
    <select name="status" class="complaint-status-change form-control" id="{{$record->id}}"
            >
        <option value="0" id="pending" {{ $record->status == 0 ? 'Selected' :''}} >Pending</option>
        <option value="1" id="approved" {{ $record->status == 1 ? 'Selected' :''}} >Approved</option>
        <option value="2" id="rejected" {{ $record->status == 2 ? 'Selected' :''}} >Rejected</option>

    </select>




