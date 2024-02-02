@if(can_access_route('micro-hub-assign.edit',$userPermissoins))
<a href="{{route('micro-hub-assign.edit', base64_encode ($record->id))}}" title="Edit"
     class="btn btn-xs btn-info">
       <i class="fa fa-pencil-square"></i>
</a>
@endif
