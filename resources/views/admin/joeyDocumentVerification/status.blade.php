
    <select name="status" class="doc-status-change form-control" id="{{$record->id}}"
            >
        <option value="0" id="pending" {{ $record->is_approved == 0 ? 'Selected' :''}} >Pending</option>
        <option value="1" id="approved" {{ $record->is_approved == 1 ? 'Selected' :''}} >Approved</option>
        <option value="2" id="rejected" {{ $record->is_approved == 2 ? 'Selected' :''}} >Rejected</option>

    </select>




