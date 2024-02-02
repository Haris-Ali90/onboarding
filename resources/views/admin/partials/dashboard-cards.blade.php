<div class="row">

    @if(can_view_cards('sub_admin_card_count',$dashbord_cards_rights))
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="dashboard-stat dashboard-cart-box-one class green">
                <div class="visual" style="margin-right: 12px;">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        {!! isset($subAdmin) ? $subAdmin : 0 !!}
                    </div>
                    <div class="desc">
                        Sub Admins
                    </div>
                </div>
                <a class="more" href="{!! route('sub-admin.index') !!}">
                    See Sub Admins <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
    @endif
    @if(can_view_cards('roles_card_count',$dashbord_cards_rights))
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="dashboard-stat dashboard-cart-box-one class green">
                <div class="visual" style="margin-right: 12px;">
                    <i class="fa fa-exclamation-circle"></i>
                </div>
                <div class="details">
                    <div class="number">
                        {!! isset($roles) ? $roles : 0 !!}
                    </div>
                    <div class="desc">
                        Roles
                    </div>
                </div>
                <a class="more" href="{!! route('role.index') !!}">
                    See Roles list <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
    @endif
    @if(can_view_cards('joey_checklist_card_count',$dashbord_cards_rights))
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="dashboard-stat dashboard-cart-box-one class green">
                <div class="visual" style="margin-right: 12px;">
                    <i class="fa fa-tag"></i>
                </div>
                <div class="details">
                    <div class="number">
                        {!! isset($joeyCheckList) ? $joeyCheckList : 0 !!}
                    </div>
                    <div class="desc">
                        Joey CheckList
                    </div>
                </div>
                <a class="more" href="{!! route('joey-checklist.index') !!}">
                    See Joey Checklist <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
    @endif
</div>

<div class="row">
    @if(can_view_cards('zones_card_count',$dashbord_cards_rights))
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="dashboard-stat dashboard-cart-box-one class green">
                <div class="visual" style="margin-right: 12px;">
                    <i class="fa fa-cog"></i>
                </div>
                <div class="details">
                    <div class="number">
                        {!! isset($zones) ? $zones : 0 !!}
                    </div>
                    <div class="desc">
                        Zones
                    </div>
                </div>
                <a class="more" href="{!! route('zones.index') !!}">
                    See Zones <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
    @endif
    @if(can_view_cards('work_time_card_count',$dashbord_cards_rights))
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="dashboard-stat dashboard-cart-box-one class green">
                <div class="visual" style="margin-right: 12px;">
                    <i class="fa fa-clock-o"></i>
                </div>
                <div class="details">
                    <div class="number">
                        {!! isset($worktime) ? $worktime : 0 !!}
                    </div>
                    <div class="desc">
                        Work Times
                    </div>
                </div>
                <a class="more" href="{!! route('work-time.index') !!}">
                    See Work Times <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
    @endif
    @if(can_view_cards('work_type_card_count',$dashbord_cards_rights))
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="dashboard-stat dashboard-cart-box-one class green">
                <div class="visual" style="margin-right: 12px;">
                    <i class="fa fa-briefcase"></i>
                </div>
                <div class="details">
                    <div class="number">
                        {!! isset($worktype) ? $worktype : 0 !!}
                    </div>
                    <div class="desc">
                        Work Types
                    </div>
                </div>
                <a class="more" href="{!! route('work-type.index') !!}">
                    See Work Types <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
    @endif
</div>

<div class="row">
    @if(can_view_cards('training_by_category_card_count',$dashbord_cards_rights))
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="dashboard-stat dashboard-cart-box-one class green">
                <div class="visual" style="margin-right: 12px;">
                    <i class="fa fa-video-camera"></i>
                </div>
                <div class="details">
                    <div class="number">
                        {!! isset($trainingBycategory) ? $trainingBycategory : 0 !!}
                    </div>
                    <div class="desc">
                        Training Videos By Category
                    </div>
                </div>
                <a class="more" href="{!! route('training.index') !!}">
                    See Training Videos<i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
    @endif

        {{--@if(can_view_cards('training_by_vendor_card_count',$dashbord_cards_rights))
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="dashboard-stat dashboard-cart-box-one class green">
                    <div class="visual" style="margin-right: 12px;">
                        <i class="fa fa-video-camera"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            {!! isset($trainingByVendor) ? $trainingByVendor : 0 !!}
                        </div>
                        <div class="desc">
                            Training Videos By Vendor
                        </div>
                    </div>
                    <a class="more" href="{!! route('training.index') !!}">
                        See Training Videos<i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
        @endif--}}
    @if(can_view_cards('quiz_management_by_category_card_count',$dashbord_cards_rights))
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="dashboard-stat dashboard-cart-box-one class green">
                <div class="visual" style="margin-right: 12px;">
                    <i class="fa fa-question"></i>
                </div>
                <div class="details">
                    <div class="number">
                        {!! isset($quizByCategory) ? $quizByCategory : 0 !!}
                    </div>
                    <div class="desc">
                       Category Quiz
                    </div>
                </div>
                <a class="more" href="{!! route('quiz-management.index') !!}">
                    See Quizzes <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>

    @endif

</div>
{{--<div class="row">--}}
    {{--@if(can_view_cards('quiz_management_by_vendor_card_count',$dashbord_cards_rights))--}}
        {{--<div class="col-lg-4 col-md-4 col-sm-4">--}}
            {{--<div class="dashboard-stat dashboard-cart-box-one class green">--}}
                {{--<div class="visual" style="margin-right: 12px;">--}}
                    {{--<i class="fa fa-question"></i>--}}
                {{--</div>--}}
                {{--<div class="details">--}}
                    {{--<div class="number">--}}
                        {{--{!! isset($quizByVendor) ? $quizByVendor : 0 !!}--}}
                    {{--</div>--}}
                    {{--<div class="desc">--}}
                        {{--Vendor Quizzes--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<a class="more" href="{!! route('quiz-management.index') !!}">--}}
                    {{--See Quizzes <i class="m-icon-swapright m-icon-white"></i>--}}
                {{--</a>--}}
            {{--</div>--}}
        {{--</div>--}}

    {{--@endif--}}
{{--</div>--}}
<div class="clearfix"></div>
<!-- Dashboard cards close -->