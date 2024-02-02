@extends('admin.layouts.app')

@section('css')
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/select2/select2-metronic.css') }}"/>
{{--    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/data-tables/DT_bootstrap.css') }}"/>--}}

    <!-- END PAGE LEVEL STYLES -->
@stop
<main id="chat_page">
@section('content')

    <!-- BEGIN PAGE HEADER-->
    @include('admin.partials.errors')

    <section class="chat_page">
            <div class="row">
                <aside id="left_sidebar" class="col-12 col-lg-4 bc1-lightest-bg">
                    <div class="inner">
                        <div class="widget_sidebar d-none d-lg-block">
                            <div class="widgetTitle_wrap flexbox align-items-center justify-content-between">
                                <h5 class="widgetTitle">Tickets</h5>
                                <div class="form_create_ticket_wrap dd_wrap position-r">
                                    <button class="dd_btn btn btn-white btn-border btn-icon btn-xs"><i class="fa fa-plus"></i></button>
                                    <div class="form_create_ticket_box dd_box">
                                        <form  method="GET" action="" id="ticket_reasons_form" class="needs-validation" novalidate>                                
                                            <div class="form-group no-min-h">
                                                <label for="email">Select the type of issue</label>
                                                <select class="form-control form-control-lg" id="reasonCategoryDD" name="type" required>
                                                    
                                                </select>
                                            </div>
                                            <div class="form-group no-min-h">
                                                <label for="email">Select the reason</label>
                                                <select class="form-control form-control-lg" id="reasonDD" name="type" required>
                                                    
                                                </select>
                                            </div>
                                            <div class="btn-group nomargin">
                                                <button type="submit" class="btn btn-primary submitButton">Create Ticket</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="chat_cat_list_wrap widgetInfo">
                                <div class="thread_type_block">
                                    <h2>Realtime Threads</h2>
                                    <div id="thread_list" class="thread_list">
                                        <div class="row"></div>
                                    </div>
                                </div>
                                <div class="thread_type_block">
                                    <h2>Active Threads</h2>
                                    <div id="active_thread_list" class="thread_list">
                                        <div class="row"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="widget_sidebar d-none d-lg-block">
                            <div class="widgetTitle_wrap flexbox align-items-center justify-content-between">
                                <h5 class="widgetTitle">Groups</h5>
                                <div class="form_create_ticket_wrap dd_wrap position-r">
                                    <button class="dd_btn btn btn-white btn-border btn-icon btn-xs"><i class="fa fa-plus"></i></button>
                                    <div class="form_create_ticket_box dd_box">
                                        <form  method="GET" action="" id="create_group_form" class="needs-validation" novalidate>                                
                                            <div class="form-group no-min-h">
                                                <label for="email">Enter Group Title</label>
                                                <input class="form-control form-control-lg" id="groupName" name="groupName" required/>
                                            </div>
                                            <div class="btn-group nomargin">
                                                <button type="submit" class="btn btn-primary submitButton">Create Group</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="group_list_wrap widgetInfo">
                                <div class="group_list">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
                
                <aside id="right_content" class="chat_page_col col-12 col-lg-8">
                    <div class="chat_inner inner">
                        <div class="chat_header" style="display: none;">
                            <div class="info">
                                <h1 class="h6">
                                    <span class="reason_category f14 dp-block lh-10 regular bf-color"></span>
                                    <span class="reason"></span>
                                </h1>
                            </div>
                        </div>
                        
                        <div class="chat_wrap">
                            <div class="no-result no-thread-selected">
                                <div class="hgroup">
                                    <h4>No Ticket or Group selected</h4>
                                    <p>Please select/create a ticket or group to start chat</p>
                                </div>
                            </div>
                            <div class="chat_list messageArea">
                                <!-- <div class="chat_box incoming">
                                    <div class="inner">
                                        <h4 class="name">Farzan Ahmed</h4>
                                        <div class="message"><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, ipsa.</p></div>
                                    </div>
                                </div>
                                <div class="chat_box outgoing">
                                    <div class="inner">
                                        <h4 class="name">Owais</h4>
                                        <div class="message"><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, ipsa.</p></div>
                                    </div>
                                </div> -->
                            </div>
                            <div class="chat_textarea">
                                <div class="alert_messages"></div>
                                <div id="chatFiles" class="chat_files"></div>
                                <div class="form-group no-min-h nomargin">
                                    <div class="textMessage_wrap">
                                        <input name="textarea" class="form-control form-control-lg textMessageInput" placeholder="Write a message..."/>
                                        <button id="send_msg_btn" class="send_msg_btn btn btn-primary">Send</button>
                                        <button id="attachFileBtn" class="attach_btn">Attach</button>
                                    </div>
                                </div>
                                <div class="divider center sm"></div>
                                <div class="end_thread align-center">
                                    <a href="#" id="endThreadBtn" class="endThreadBtn btn btn-default">End Chat</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </section>
        <!-- END PAGE CONTENT-->
    <!-- END PAGE CONTENT-->
@stop

@section('footer-js')
</main>
@stop
