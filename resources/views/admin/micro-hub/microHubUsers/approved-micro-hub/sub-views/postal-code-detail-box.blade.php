<!--datatable-td-milti-data-show-box-open-->

<div class="datatable-td-milti-data-show-box">
    <ul>
                @foreach($record->dashboardUser->getPostalCode() as $index => $postal_code)
            <li class="
                                                    @if($index == 0)
                    datatable-td-milti-data-show-box-btn-li datatable-td-milti-data-show-li
@else
                    datatable-td-milti-data-show-li hide @endif">
                <span>{{$postal_code->postal_code}} </span>
                @if($index== 0 && $record->dashboardUser->getPostalCode()->count() > 1)
                    <span href="#" class="show-datatable-td-list-btn btn btn-xs btn-primary orange " ><i class="fa fa-angle-down"></i></span>@endif
            </li>
        @endforeach
    </ul>
</div>
<!--datatable-td-milti-data-show-box-close-->