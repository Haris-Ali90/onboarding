<?php

namespace App\Services\Menu;

use App\Models\DashboardUsers;
use App\Models\DeliveryProcessType;
use App\Models\HubProcess;
use App\Models\MicroHubPermission;
use Illuminate\Support\Facades\Auth;
use Spatie\Menu\Laravel\Link;
use Spatie\Menu\Laravel\Menu;

/**
 * Class AdminMenu
 *
 * @author Muzafar Ali Jatoi <muzfr7@gmail.com>
 * @date   23/9/18
 */
class AdminMenu
{
    public function register()
    {
        Menu::macro('admin', function () {

            /*getting user permissions*/

            $userPermissoins = Auth::user()->getPermissions();
            //Checking Permission For Micro Hub
            $dashboardUser = DashboardUsers::where('role_id',5)->where('micro_sub_admin', 1)->pluck('id')->toArray();
            $process_id = MicroHubPermission::whereIn('micro_hub_user_id', $dashboardUser)->pluck('hub_process_id')->toarray();
            $hubProcessInActive = HubProcess::whereIn('id', $process_id)->where('is_active',0)->pluck('process_id')->toArray();
            $hubProcessInActive = DeliveryProcessType::whereIn('id', $hubProcessInActive)->count();
            $permissionCount = '';
            if ($hubProcessInActive > 0)
            {
                $permissionCount = '<span class="permission-counts badge badge-light" title="Permission Requested"><label class="count-label">'.$hubProcessInActive. '</label></span>';
            }
            return Menu::new()

                ->addClass('page-sidebar-menu')
                ->setAttribute('data-keep-expanded', 'false')
                ->setAttribute('data-auto-scroll', 'true')
                ->setAttribute('data-slide-speed', '200')
                ->html('<div class="sidebar-toggler hidden-phone"></div>')

                ->add(Link::toRoute(
                    'joeys.statistics',
                    '<i class="fa fa-home"></i> <span class="title">Dashboard</span>'
                )

                ->addParentClass('start'))

                // Micro Hub Side Menu Open
                ->submenuIf(can_access_route(['micro-hub.role.index', 'micro-hub.role.create'], $userPermissoins) && Auth::user()->login_type == 'micro_hub', '

                    <a href="javascript:;">
                        <i class="fa fa-exclamation-circle"></i>
                        <span class="title">Roles </span>
                        <span class="arrow"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('micro-hub.role.index', $userPermissoins),
                            Link::toRoute('micro-hub.role.index', '<span class="title">Role List</span>'))
                        ->addIf(can_access_route('micro-hub.role.create', $userPermissoins),
                            Link::toRoute('micro-hub.role.create', '<span class="title">Add Role</span>'))
                )
                ->submenuIf(can_access_route(['micro-hub.sub-admin.index', 'micro-hub.sub-admin.create'], $userPermissoins) && Auth::user()->login_type == 'micro_hub', '
                    <a href="javascript:;">
                        <i class="fa fa-users"></i>
                        <span clas1s="title">Sub Admins </span>
                        <span class="arrow"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('micro-hub.sub-admin.index', $userPermissoins) && Auth::user()->login_type == 'micro_hub',
                            Link::toRoute('micro-hub.sub-admin.index', '<span class="title">Sub Admins List</span>'))
                        ->addIf(can_access_route('micro-hub.sub-admin.create', $userPermissoins),
                            Link::toRoute('micro-hub.sub-admin.create', '<span class="title">Add Sub Admin</span>'))
                )
                ->submenuIf(can_access_route(['micro-hub.users.index'], $userPermissoins) && Auth::user()->login_type == 'micro_hub', '
                    <a href="javascript:;">
                        <i class="fa fa-users"></i>
                        <span class="title">New Microhub Requests </span>
                        <span class="arrow"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('micro-hub.users.index', $userPermissoins),
                            Link::toRoute('micro-hub.users.index', '<span class="title">New Microhub Requests</span>'))
                )
                ->submenuIf(can_access_route(['micro-hub.documentApproved.index','micro-hub.documentNotApproved.index','micro-hub.documentNotUploaded.index','micro-hub.approved.index','micro-hub.notApproved.index','micro-hub.notTrained.index','micro-hub.quizPending.index','micro-hub.quizPassed.index'], $userPermissoins) && Auth::user()->login_type == 'micro_hub', '
                    <a href="javascript:;">
                        <i class="fa fa-users"></i>
                        <span class="title">Microhubs Users Controls </span>
                        <span class="arrow"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('micro-hub.approved.index', $userPermissoins),
                            Link::toRoute('micro-hub.approved.index', '<span class="title">Approved '.$permissionCount.' </span>'))
                        ->addIf(can_access_route('micro-hub.notApproved.index', $userPermissoins),
                            Link::toRoute('micro-hub.notApproved.index', '<span class="title">Not Approved List</span>'))
                        ->addIf(can_access_route('micro-hub.documentApproved.index', $userPermissoins),
                            Link::toRoute('micro-hub.documentApproved.index', '<span class="title">Document Approved List</span>'))
                        ->addIf(can_access_route('micro-hub.documentNotApproved.index', $userPermissoins),
                            Link::toRoute('micro-hub.documentNotApproved.index', '<span class="title">Document Not Approved List</span>'))
                        ->addIf(can_access_route('micro-hub.documentNotUploaded.index', $userPermissoins),
                            Link::toRoute('micro-hub.documentNotUploaded.index', '<span class="title">Document Not Uploaded List</span>'))
                        ->addIf(can_access_route('micro-hub.notTrained.index', $userPermissoins),
                            Link::toRoute('micro-hub.notTrained.index', '<span class="title">Not Trained List</span>'))
                        ->addIf(can_access_route('micro-hub.quizPending.index', $userPermissoins),
                            Link::toRoute('micro-hub.quizPending.index', '<span class="title">Quiz Pending List</span>'))
                        ->addIf(can_access_route('micro-hub.quizPassed.index', $userPermissoins),
                            Link::toRoute('micro-hub.quizPassed.index', '<span class="title">Quiz Passed List</span>'))
                )

				->submenuIf(can_access_route(['micro-hub-assign.index'], $userPermissoins) && Auth::user()->login_type == 'micro_hub', '
						<a href="javascript:;">
							<i class="fa fa-users"></i>
							<span class="title">Microhub Reporting Incharges </span>
							<span class="arrow"></span>
							<!--<span class="selected"></span>-->
						</a>
						',
						Menu::new()
							->addClass('sub-menu')
							->addIf(can_access_route('micro-hub-assign.index', $userPermissoins),
								Link::toRoute('micro-hub-assign.index', '<span class="title">Microhub Reporting Incharges List</span>'))
                )

                ->submenuIf(can_access_route(['micro-hub.documentVerificationData.index'], $userPermissoins) && Auth::user()->login_type == 'micro_hub', '
                    <a href="javascript:;">
                        <i class="fa fa-check-square-o"></i>
                        <span class="title">Document Verification </span>
                        <span class="arrow"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('micro-hub.documentVerificationData.index', $userPermissoins),
                            Link::toRoute('micro-hub.documentVerificationData.index', '<span class="title">Document Verification</span>'))
                )

                ->submenuIf(can_access_route(['quiz-attempt.index'], $userPermissoins) && Auth::user()->login_type == 'micro_hub', '
                    <a href="javascript:;">
                        <i class="fa fa-question-circle"></i>
                        <span class="title">Attempted Quiz </span>
                        <span class="arrow"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('quiz-attempt.index', $userPermissoins),
                            Link::toRoute('quiz-attempt.index', '<span class="title">Attempted Quiz</span>'))
                )

                ->submenuIf(can_access_route(['micro-hub.documentList.index','training-list.index','order-category-list.index','quiz-management-list.index'], $userPermissoins) && Auth::user()->login_type == 'micro_hub', '
                    <a href="javascript:;">
                        <i class="fa fa-cog"></i>
                        <span class="title">Setting </span>
                        <span class="arrow"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('micro-hub.documentList.index', $userPermissoins),
                            Link::toRoute('micro-hub.documentList.index', '<span class="title">Documents List</span>'))
                        ->addIf(can_access_route('training-list.index', $userPermissoins),
                            Link::toRoute('training-list.index', '<span class="title">Training videos and Documents List</span>'))
                        ->addIf(can_access_route('order-category-list.index', $userPermissoins),
                            Link::toRoute('order-category-list.index', '<span class="title">Order Categories List</span>'))
                        ->addIf(can_access_route('quiz-management-list.index', $userPermissoins),
                            Link::toRoute('quiz-management-list.index', '<span class="title">Quiz Management List</span>'))
                )

                // Micro Hub Side Menu Close

                ->submenuIf(can_access_route(['role.index','role.create'],$userPermissoins),'
                    <a href="javascript:;">
                        <i class="fa fa-exclamation-circle"></i>
                        <span class="title">Roles </span>
                        <span class="arrow"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('role.index',$userPermissoins),
                            Link::toRoute('role.index', '<span class="title">Role List</span>'))
                        ->addIf(can_access_route('role.create',$userPermissoins),
                            Link::toRoute('role.create', '<span class="title">Add Role</span>'))
                )

                ->submenuIf(can_access_route(['sub-admin.index','sub-admin.create'],$userPermissoins),'
                    <a href="javascript:;">
                        <i class="fa fa-users"></i>
                        <span class="title">Sub Admins </span>
                        <span class="arrow"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('sub-admin.index',$userPermissoins),
                            Link::toRoute('sub-admin.index', '<span class="title">Sub Admins List</span>'))
                        ->addIf(can_access_route('sub-admin.create',$userPermissoins),
                            Link::toRoute('sub-admin.create', '<span class="title">Add Sub Admin</span>'))
                )


                ->submenuIf(can_access_route(['joeys-list.index'], $userPermissoins), '
                    <a href="javascript:;">
                        <i class="fa fa-truck"></i>
                        <span class="title">Joeys List</span>
                        <span class="arrow"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('joeys-list.index', $userPermissoins),
                            Link::toRoute('joeys-list.index', '<span class="title">Joeys List</span>'))
                        ->addIf(can_access_route('joeys.agreementNotSigned', $userPermissoins),
                            Link::toRoute('joeys.agreementNotSigned', '<span class="title">Agreement Not Signed</span>'))
                        ->addIf(can_access_route('newSignUpJoeys.index', $userPermissoins),
                            Link::toRoute('newSignUpJoeys.index', '<span class="title">New Signups</span>'))
                        ->addIf(can_access_route('joeys.documentNotUploaded', $userPermissoins),
                            Link::toRoute('joeys.documentNotUploaded', '<span class="title">Document Not Uploaded Joey List</span>'))
                        ->addIf(can_access_route('joeys.documentNotApproved', $userPermissoins),
                            Link::toRoute('joeys.documentNotApproved', '<span class="title">Document Not Approved Joey List</span>'))
                        ->addIf(can_access_route('joeys.documentApproved', $userPermissoins),
                            Link::toRoute('joeys.documentApproved', '<span class="title">Document Approved Joey List</span>'))
                        ->addIf(can_access_route('joeys.notTrained', $userPermissoins),
                            Link::toRoute('joeys.notTrained', '<span class="title">Joeys Not Trained List</span>'))
                        ->addIf(can_access_route('joeys.quizPending', $userPermissoins),
                            Link::toRoute('joeys.quizPending', '<span class="title">Joeys Quiz Pending List</span>'))
                        ->addIf(can_access_route('joeys.quizPassed', $userPermissoins),
                            Link::toRoute('joeys.quizPassed', '<span class="title">Joeys Quiz Passed List</span>'))

                )

                ->submenuIf(can_access_route(['joeys-complaints.index'],$userPermissoins),'
                    <a href="javascript:;">
                        <i class="fa fa-comments-o"></i>
                        <span class="title">Joey Complaint List </span>
                        <span class="arrow"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('joeys-complaints.index',$userPermissoins),
                            Link::toRoute('joeys-complaints.index', '<span class="title">Joey Complaint List</span>'))
                )




//                ->submenuIf(can_access_route(['zones.index','zones.create'],$userPermissoins),'
//                    <a href="javascript:;">
//                        <i class="fa fa-cog"></i>
//                        <span class="title">Zones </span>
//                        <span class="arrow open"></span>
//                        <!--<span class="selected"></span>-->
//                    </a>
//                    ',
//                    Menu::new()
//                        ->addClass('sub-menu')
//                        ->addIf(can_access_route('zones.index',$userPermissoins),
//                            Link::toRoute('zones.index', '<span class="title">Zones List</span>'))
//                       /* ->addIf(can_access_route('zones.create',$userPermissoins),
//                            Link::toRoute('zones.create', '<span class="title">Add Zone</span>'))*/
//                )


//                ->submenuIf(can_access_route(['work-time.index','work-time.create'],$userPermissoins),'
//                    <a href="javascript:;">
//                        <i class="fa fa-clock-o"></i>
//                        <span class="title">Preferred Work Time</span>
//                        <span class="arrow open"></span>
//                        <!--<span class="selected"></span>-->
//                    </a>
//                    ',
//                    Menu::new()
//                        ->addClass('sub-menu')
//                        ->addIf(can_access_route('work-time.index',$userPermissoins),
//                            Link::toRoute('work-time.index', '<span class="title">Preferred Work Time List</span>'))
//                        ->addIf(can_access_route('work-time.create',$userPermissoins),
//                            Link::toRoute('work-time.create', '<span class="title">Add Preferred Work Time</span>'))
//                )


//                ->submenuIf(can_access_route(['work-type.index', 'work-type.create'], $userPermissoins), '
//                    <a href="javascript:;">
//                        <i class="fa fa-briefcase"></i>
//                        <span class="title">Preferred Work Types </span>
//                        <span class="arrow open"></span>
//                        <!--<span class="selected"></span>-->
//                    </a>
//                    ',
//                    Menu::new()
//                        ->addClass('sub-menu')
//                        ->addIf(can_access_route('work-type.index', $userPermissoins),
//                            Link::toRoute('work-type.index', '<span class="title">Preferred Work Types List</span>'))
//                        ->addIf(can_access_route('work-type.create', $userPermissoins),
//                            Link::toRoute('work-type.create', '<span class="title">Add Preferred Work Type</span>'))
//                )

                ->submenuIf(can_access_route(['joey-document-verification.index', 'joey-document-verification.expiredDocument', 'joey-document-verification.create'], $userPermissoins), '
                    <a href="javascript:;">
                        <i class="fa fa-check-square-o"></i>
                        <span class="title">Joey Documents Verification </span>
                        <span class="arrow"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('joey-document-verification.index', $userPermissoins),
                            Link::toRoute('joey-document-verification.index', '<span class="title">Joey Documents Verification List</span>'))
                        ->addIf(can_access_route('joey-document-verification.expiredDocument', $userPermissoins),
                            Link::toRoute('joey-document-verification.expiredDocument', '<span class="title">Joey Expired Documents List</span>'))

                )


                /*->submenuIf(can_access_route(['training.index', 'training.create'], $userPermissoins), '
                    <a href="javascript:;">
                        <i class="fa fa-video-camera"></i>
                        <span class="title">Training Videos and Documents </span>
                        <span class="arrow open"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('training.index', $userPermissoins),
                            Link::toRoute('training.index', '<span class="title">Training Videos and Documents List</span>'))
                        ->addIf(can_access_route('training.create', $userPermissoins),
                            Link::toRoute('training.create', '<span class="title">Add Training Videos and Document</span>'))
                )*/


                /*              ->submenuIf(can_access_route(['categores.index', 'categores.create'], $userPermissoins), '
                                  <a href="javascript:;">
                                      <i class="fa fa-tag"></i>
                                      <span class="title">Categories Order Count </span>
                                      <span class="arrow open"></span>
                                      <!--<span class="selected"></span>-->
                                  </a>
                                  ',
                                  Menu::new()
                                      ->addClass('sub-menu')
                                      ->addIf(can_access_route('categores.index', $userPermissoins),
                                          Link::toRoute('categores.index', '<span class="title">Categories Order Count List</span>'))
                                      ->addIf(can_access_route('categores.create', $userPermissoins),
                                          Link::toRoute('categores.create', '<span class="title">Add Categories Order Count</span>'))
                              )*/


                /*->submenuIf(can_access_route(['quiz-management.index', 'quiz-management.create'], $userPermissoins), '
                    <a href="javascript:;">
                        <i class="fa fa-question"></i>
                        <span class="title">Quiz Management </span>
                        <span class="arrow open"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('quiz-management.index', $userPermissoins),
                            Link::toRoute('quiz-management.index', '<span class="title">Quiz Management List</span>'))
                        ->addIf(can_access_route('quiz-management.create', $userPermissoins),
                            Link::toRoute('quiz-management.create', '<span class="title">Add Quiz</span>'))
                )*/


                /*->submenuIf(can_access_route(['order-category.index', 'order-category.create'], $userPermissoins), '
                    <a href="javascript:;">
                        <i class="fa fa-tasks"></i>
                        <span class="title">Order Categories</span>
                        <span class="arrow open"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('order-category.index', $userPermissoins),
                            Link::toRoute('order-category.index', '<span class="title">Order Categories List</span>'))
                        ->addIf(can_access_route('order-category.create', $userPermissoins),
                            Link::toRoute('order-category.create', '<span class="title">Add Order Category</span>'))
                )*/

           /*     ->submenuIf(can_access_route(['job-type.index', 'job-type.create'], $userPermissoins), '
                    <a href="javascript:;">
                        <i class="fa fa-tasks"></i>
                        <span class="title">Job Types </span>
                        <span class="arrow open"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('job-type.index', $userPermissoins),
                            Link::toRoute('job-type.index', '<span class="title">Job Types List</span>'))
                        ->addIf(can_access_route('job-type.create', $userPermissoins),
                            Link::toRoute('job-type.create', '<span class="title">Add Job Type</span>'))
                )*/

//                ->submenuIf(can_access_route(['joey-checklist.index', 'joey-checklist.create'], $userPermissoins), '
//                    <a href="javascript:;">
//                        <i class="fa fa-check"></i>
//                        <span class="title">Joey Checklists </span>
//                        <span class="arrow open"></span>
//                        <!--<span class="selected"></span>-->
//                    </a>
//                    ',
//                    Menu::new()
//                        ->addClass('sub-menu')
//                        ->addIf(can_access_route('joey-checklist.index', $userPermissoins),
//                            Link::toRoute('joey-checklist.index', '<span class="title">Joey Checklists List</span>'))
//                        ->addIf(can_access_route('joey-checklist.create', $userPermissoins),
//                            Link::toRoute('joey-checklist.create', '<span class="title">Add Joey Checklist</span>'))
//                )

         /*       ->submenuIf(can_access_route(['basic-vendor.index', 'basic-vendor.create'], $userPermissoins), '
                    <a href="javascript:;">
                        <i class="fa fa-barcode"></i>
                        <span class="title">Basic Vendors</span>
                        <span class="arrow open"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('basic-vendor.index', $userPermissoins),
                            Link::toRoute('basic-vendor.index', '<span class="title">Basic Vendors List</span>'))
                        ->addIf(can_access_route('basic-vendor.create', $userPermissoins),
                            Link::toRoute('basic-vendor.create', '<span class="title">Add Basic Vendor</span>'))
                )*/


     /*           ->submenuIf(can_access_route(['basic-category.index', 'basic-category.create'], $userPermissoins), '
                    <a href="javascript:;">
                        <i class="fa fa-certificate"></i>
                        <span class="title">Basic Categories</span>
                        <span class="arrow open"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('basic-category.index', $userPermissoins),
                            Link::toRoute('basic-category.index', '<span class="title">Basic Categories</span>'))
                        ->addIf(can_access_route('basic-category.create', $userPermissoins),
                            Link::toRoute('basic-category.create', '<span class="title">Add Basic Category</span>'))
                )*/

/*                ->submenuIf(can_access_route(['vendor-score.index', 'vendor-score.create'], $userPermissoins), '
                    <a href="javascript:;">
                        <i class="fa fa-sort-numeric-asc"></i>
                        <span class="title">Vendors Score</span>
                        <span class="arrow open"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('vendor-score.index', $userPermissoins),
                            Link::toRoute('vendor-score.index', '<span class="title">Vendors Score</span>'))
                        ->addIf(can_access_route('vendor-score.create', $userPermissoins),
                            Link::toRoute('vendor-score.create', '<span class="title">Add Vendor Score</span>'))
                )*/

/*
                ->submenuIf(can_access_route(['category-score.index', 'category-score.create'], $userPermissoins), '
                    <a href="javascript:;">
                        <i class="fa fa-sort-numeric-asc"></i>
                        <span class="title">Categories Score</span>
                        <span class="arrow open"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('category-score.index', $userPermissoins),
                            Link::toRoute('category-score.index', '<span class="title">Categories Score</span>'))
                        ->addIf(can_access_route('category-score.create', $userPermissoins),
                            Link::toRoute('category-score.create', '<span class="title">Add Category Score</span>'))
                )*/


//                ->submenuIf(can_access_route(['vendors.index', 'vendors.create'], $userPermissoins), '
//                    <a href="javascript:;">
//                        <i class="fa fa-tasks"></i>
//                        <span class="title">Vendors </span>
//                        <span class="arrow open"></span>
//                        <!--<span class="selected"></span>-->
//                    </a>
//                    ',
//                    Menu::new()
//                        ->addClass('sub-menu')
//                        ->addIf(can_access_route('vendors.index', $userPermissoins),
//                            Link::toRoute('vendors.index', '<span class="title">Vendors</span>'))
//                        /*->addIf(can_access_route('vendors.create', $userPermissoins),
//                            Link::toRoute('vendors.create', '<span class="title">Add Vendor Order Count</span>'))*/
//                )

//                ->submenuIf(can_access_route(['documents.index', 'documents.create'], $userPermissoins), '
//                    <a href="javascript:;">
//                        <i class="fa fa-tasks"></i>
//                        <span class="title">Documents</span>
//                        <span class="arrow open"></span>
//                        <!--<span class="selected"></span>-->
//                    </a>
//                    ',
//                    Menu::new()
//                        ->addClass('sub-menu')
//                        ->addIf(can_access_route('documents.index', $userPermissoins),
//                            Link::toRoute('documents.index', '<span class="title">Documents List</span>'))
//                        ->addIf(can_access_route('documents.create', $userPermissoins),
//                            Link::toRoute('documents.create', '<span class="title">Add Documents</span>'))
//                )
//

//
//                ->submenuIf(can_access_route(['faqs.index', 'faqs.create'], $userPermissoins), '
//                    <a href="javascript:;">
//                        <i class="fa fa-check"></i>
//                        <span class="title">FAQ </span>
//                        <span class="arrow open"></span>
//                        <!--<span class="selected"></span>-->
//                    </a>
//                    ',
//                    Menu::new()
//                        ->addClass('sub-menu')
//                        ->addIf(can_access_route('faqs.index', $userPermissoins),
//                            Link::toRoute('faqs.index', '<span class="title">FAQ List</span>'))
//                        ->addIf(can_access_route('faqs.create', $userPermissoins),
//                            Link::toRoute('faqs.create', '<span class="title">Add FAQ</span>'))
//                )


                ->submenuIf(can_access_route(['joey-attempt-quiz.index'], $userPermissoins), '
                    <a href="javascript:;">
                        <i class="fa fa-check"></i>
                        <span class="title">Joey Attempted Quiz </span>
                        <span class="arrow"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('joey-attempt-quiz.index', $userPermissoins),
                            Link::toRoute('joey-attempt-quiz.index', '<span class="title">Joey Attempted Quiz</span>'))

                )


                ->submenuIf(can_access_route(['notification.index'],$userPermissoins),'
                    <a href="javascript:;">
                        <i class="fa fa-bell"></i>
                        <span class="title">Joey BroadCasting Notification</span>
                        <span class="arrow"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('notification.index',$userPermissoins),
                            Link::toRoute('notification.index', '<span class="title">Joey BroadCasting Notification</span>'))
                )


                /*  ->submenuIf(can_access_route(['notification.index'],$userPermissoins),'
                      <a href="javascript:;">
                          <i class="fa fa-bell"></i>
                          <span class="title">Push Notification </span>
                          <span class="arrow open"></span>
                          <!--<span class="selected"></span>-->
                      </a>
                      ',
                      Menu::new()
                          ->addClass('sub-menu')
                          ->addIf(can_access_route('notification.index',$userPermissoins),
                              Link::toRoute('notification.index', '<span class="title">Push Notification</span>'))
                  )


  */


                ->submenuIf(can_access_route(['customer-send-messages.index', 'customer-send-messages.create'], $userPermissoins), '
                    <a href="javascript:;">
                        <i class="fa fa-check"></i>
                        <span class="title">Customer Send <br> Messages </span>
                        <span class="arrow"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('customer-send-messages.index', $userPermissoins),
                            Link::toRoute('customer-send-messages.index', '<span class="title">Customer Send Messages List</span>'))
                        ->addIf(can_access_route('customer-send-messages.create', $userPermissoins),
                            Link::toRoute('customer-send-messages.create', '<span class="title">Add Customer Send Messages</span>'))
                )

                ->submenuIf(can_access_route(['customer-service.index', 'customer-service.create'], $userPermissoins), '
                    <a href="javascript:;">
                        <i class="fa fa-check"></i>
                        <span class="title">Flagging System </span>
                        <span class="arrow"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')
                        ->addIf(can_access_route('customer-service.index', $userPermissoins),
                            Link::toRoute('customer-service.index', '<span class="title">Flag List</span>'))
                        ->addIf(can_access_route('customer-service.create', $userPermissoins),
                            Link::toRoute('customer-service.create', '<span class="title">Add Flag</span>'))
                        ->addIf(can_access_route('flag-incident.index', $userPermissoins),
                            Link::toRoute('flag-incident.index', '<span class="title">Flag Incident List</span>'))
                )


                /*->addIf(can_access_route('users.change-password', $userPermissoins),(Link::toRoute(
                    'users.change-password',
                    '<i class="fa fa-lock"></i> <span class="title">Change Password</span>'
                )))*/
                ->submenuIf(can_access_route(['dashboard.index','zones.index','work-time.index','work-type.index','joey-checklist.index','documents.index','faqs.index','training.index','quiz-management.index','order-category.index','users.change-password'], $userPermissoins), '
                    <a href="javascript:;">
                        <i class="fa fa-cog"></i>
                        <span class="title">Setting</span>
                        <span class="arrow"></span>
                        <!--<span class="selected"></span>-->
                    </a>
                    ',
                    Menu::new()
                        ->addClass('sub-menu')


                        ->addIf(can_access_route('dashboard.index', $userPermissoins),
                            Link::toRoute('dashboard.index', '<span class="title">Setting Main Page</span>'))
                        ->addIf(can_access_route('zones.index', $userPermissoins),
                            Link::toRoute('zones.index', '<span class="title">Zones List</span>'))
                        ->addIf(can_access_route('work-time.index',$userPermissoins),
                            Link::toRoute('work-time.index', '<span class="title">Preferred Work Time List</span>'))
                        ->addIf(can_access_route('work-type.index', $userPermissoins),
                            Link::toRoute('work-type.index', '<span class="title">Preferred Work Types List</span>'))
                        ->addIf(can_access_route('joey-checklist.index', $userPermissoins),
                            Link::toRoute('joey-checklist.index', '<span class="title">Joey Checklists List</span>'))
                        ->addIf(can_access_route('documents.index', $userPermissoins),
                            Link::toRoute('documents.index', '<span class="title">Documents List</span>'))
                        ->addIf(can_access_route('faqs.index', $userPermissoins),
                            Link::toRoute('faqs.index', '<span class="title">FAQ List</span>'))
                        ->addIf(can_access_route('training.index', $userPermissoins),
                            Link::toRoute('training.index', '<span class="title">Training Videos and Documents List</span>'))
                        ->addIf(can_access_route('quiz-management.index', $userPermissoins),
                            Link::toRoute('quiz-management.index', '<span class="title">Quiz Management List</span>'))
                        ->addIf(can_access_route('order-category.index', $userPermissoins),
                            Link::toRoute('order-category.index', '<span class="title">Order Categories List</span>'))
                        ->addIf(can_access_route('users.change-password', $userPermissoins),(Link::toRoute(
                            'users.change-password',
                            '<span class="title">Change Password</span>'
                        )))



                )


                ->addIf(Auth::user()->login_type == 'micro_hub',
                    Link::toRoute('micro-hub.logout', '<span class="title" >Logout</span>')
                        ->setAttribute('id', 'leftnav-logout-link')
                        ->setAttribute('data-logout-url', route('micro-hub.logout')))
                ->addIf(Auth::user()->login_type != 'micro_hub',
                    Link::toRoute('logout', '<span class="title" data-logout-url="">Logout</span>')
                        ->setAttribute('id', 'leftnav-logout-link')
                        ->setAttribute('data-logout-url', route('logout')));
        });
    }
}
