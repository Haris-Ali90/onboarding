@if(can_access_route('quiz-attempt.show',$userPermissoins))
    <a href="{!! URL::route('quiz-attempt.show', $record->id) !!}" title="Detail"
       class="btn btn-xs btn-success">
        <i >Details</i>

    </a>

@endif