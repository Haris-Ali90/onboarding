@if(can_access_route('quiz-management-list.show',$userPermissoins))
<a href="{!! URL::route('quiz-management-list.show', $record->id) !!}" title="Detail"
   class="btn btn-xs btn-primary">
    <i class="fa fa-eye"></i>
</a>
@endif