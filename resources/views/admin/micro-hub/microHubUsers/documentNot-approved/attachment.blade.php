@if(can_access_route('micro-hub.documentVerificationData.edit',$userPermissoins))
<a href="{{route('micro-hub.documentVerificationData.edit', $record->id)}}" title="Joey Document Link"
     class="btn btn-xs btn-info">
       <i class="fa fa-pencil-square"></i>
</a>
@endif