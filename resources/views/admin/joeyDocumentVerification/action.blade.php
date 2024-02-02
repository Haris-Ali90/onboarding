@if(can_access_route('joey-document-verification.show',$userPermissoins))
<a href="{{ route('joey-document-verification.show', $record->joey_id) }}" title="Detail"
   class="btn btn-xs btn-primary">
    <i class="fa fa-eye"></i>
</a>
@endif
@if(can_access_route('joey-document-verification.edit',$userPermissoins))
    <a href="{{route('joey-document-verification.edit', $record->joey_id)}}" title="Edit"
       class="btn btn-xs btn-info">
        <i class="fa fa-pencil-square"></i>
    </a>
@endif
