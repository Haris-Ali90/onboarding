@if(can_access_route('joey-document-verification.edit',$userPermissoins))
    <a href="{{route('joey-document-verification.edit', $docomuentSubmission->id)}}" title="Edit"
       class="btn btn-xs btn-success" target="_blank">
        View Document
    </a>
@endif
