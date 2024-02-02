@if(empty($record->exp_date))
    <span>{{$record->exp_date}}  </span><br><span ></span>
@elseif(is_null($record->exp_date))
    <span> </span><br><span ></span>
@else
    <span style="color: red">{{$record->exp_date}}  </span><br><span style="color: red">*Document Expired</span>
@endif
<!-- <span style="color: red">{{$record->exp_date}}  </span><br><span style="color: red">*Document Expired</span> -->