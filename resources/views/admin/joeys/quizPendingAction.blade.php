@if(can_access_route('joeys.quizPendingNotification',$userPermissoins))
<button title="Notify"  onclick="Notification({{$record->id}})"
   class="btn btn-xs btn-primary">
    <i class="fa fa-bell"></i>
</button>
@endif

