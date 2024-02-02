@if(can_access_route('joey-document-verification.edit',$userPermissoins))
<a href="{{route('joey-document-verification.edit', $record->id)}}" title="Joey Document Link"
     class="btn btn-xs btn-info">
       <i class="fa fa-pencil-square"></i>
</a>
@endif