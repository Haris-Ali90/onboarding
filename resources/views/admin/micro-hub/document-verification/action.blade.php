{{--
@if(can_access_route('micro-hub.documentVerificationData.show',$userPermissoins))

<a href="{{ route('micro-hub.documentVerificationData.show', $record->user_id) }}" title="Detail"
   class="btn btn-xs btn-primary">
    <i class="fa fa-eye"></i>
</a>
@endif
@if(can_access_route('micro-hub.documentVerificationData.edit',$userPermissoins))
    <a href="{{route('micro-hub.documentVerificationData.edit', $record->user_id)}}" title="Edit"
       class="btn btn-xs btn-info">
        <i class="fa fa-pencil-square"></i>
    </a>
@endif
--}}

@if(can_access_route('micro-hub.documentVerificationData.show',$userPermissoins))

    <a href="{{ route('micro-hub.documentVerificationData.show', $record->jc_users_id) }}" title="Detail"
       class="btn btn-xs btn-primary">
        <i class="fa fa-eye"></i>
    </a>
@endif
@if(can_access_route('micro-hub.documentVerificationData.edit',$userPermissoins))
    <a href="{{route('micro-hub.documentVerificationData.edit', $record->jc_users_id)}}" title="Edit"
       class="btn btn-xs btn-info">
        <i class="fa fa-pencil-square"></i>
    </a>
@endif
