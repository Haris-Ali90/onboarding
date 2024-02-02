<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use App\Models\Store;
use App\Models\SideBanner;
use App\Models\Event;
use App\Models\Offer;

/**
 * Class ViewComposerServiceProvider
 *
 * @author Ghulam Mustafa <ghulam.mustafa@vservices.com>
 * @date   30/11/18
 */
class ViewComposerServiceProvider extends ServiceProvider
{


    public function boot()
    {
        $this->composeAdminPages();
        view()->composer('*', function ($view)
        {
            $date = date('Y-m-d');

            /*setting default variables */
            $userPermissoins = [];
            $dashbord_cards_rights = false;

            /*checking user is login or not */
            if(Auth::check())
            {
                $auth_user = Auth::user();
                $userPermissoins = $auth_user->getPermissions();
                $dashbord_cards_rights = $auth_user->DashboardCardRightsArray();
            }

            /*composing data to all views */
            $view->with(compact(
                'userPermissoins',
                'dashbord_cards_rights'
            ));

        });
    }

    public function register()
    {
        //
    }

    /**
     * Compose the admin pages
     *
     * e-g: admin page titles etc.
     */
    private function composeAdminPages()
    {
        /*
         * Dashboard
         */
        view()->composer('admin.dashboard.index', function ($view) {
            $view->with(['pageTitle' => 'Setting Main Page']);
        });


        /*
       * role & permissions
       */

        view()->composer('admin.role.index', function ($view) {
            $view->with(['pageTitle' => 'Role List']);
        });
        view()->composer('admin.role.create', function ($view) {
            $view->with(['pageTitle' => 'Role Create']);
        });
        view()->composer('admin.role.show', function ($view) {
            $view->with(['pageTitle' => 'Role View']);
        });
        view()->composer('admin.role.edit', function ($view) {
            $view->with(['pageTitle' => 'Role Edit']);
        });
        view()->composer('admin.role.set-permissions', function ($view) {
            $view->with(['pageTitle' => 'Set Role Permissions']);
        });

        /*
        * Sub Admin
        */

        view()->composer('admin.subAdmin.index', function ($view) {
            $view->with(['pageTitle' => 'Sub Admins List']);
        });
        view()->composer('admin.subAdmin.create', function ($view) {
            $view->with(['pageTitle' => 'Sub Admin Create']);
        });
        view()->composer('admin.subAdmin.show', function ($view) {
            $view->with(['pageTitle' => 'Sub Admin View']);
        });
        view()->composer('admin.subAdmin.edit', function ($view) {
            $view->with(['pageTitle' => 'Sub Admin Edit']);
        });

        /*
        * Zone Index
        */

        view()->composer('admin.zones.index', function ($view) {
            $view->with(['pageTitle' => 'Zones List']);
        });
        view()->composer('admin.zones.create', function ($view) {
            $view->with(['pageTitle' => 'Zone Create']);
        });
        view()->composer('admin.zones.show', function ($view) {
            $view->with(['pageTitle' => 'Zone View']);
        });
        view()->composer('admin.zones.edit', function ($view) {
            $view->with(['pageTitle' => 'Zone Edit']);
        });

        /*
        * WORK TIME
        */

        view()->composer('admin.workTime.index', function ($view) {
            $view->with(['pageTitle' => 'Preferred Work Time List']);
        });
        view()->composer('admin.workTime.create', function ($view) {
            $view->with(['pageTitle' => 'Preferred Work Time Create']);
        });
        view()->composer('admin.workTime.show', function ($view) {
            $view->with(['pageTitle' => 'Preferred Work Time View']);
        });
        view()->composer('admin.workTime.edit', function ($view) {
            $view->with(['pageTitle' => 'Preferred Work Time Edit']);
        });

        /*
        * WORK TYPE
        */

        view()->composer('admin.workType.index', function ($view) {
            $view->with(['pageTitle' => 'Preferred Work Types List']);
        });
        view()->composer('admin.workType.create', function ($view) {
            $view->with(['pageTitle' => 'Preferred Work Type Create']);
        });
        view()->composer('admin.workType.show', function ($view) {
            $view->with(['pageTitle' => 'Preferred Work Type View']);
        });
        view()->composer('admin.workType.edit', function ($view) {
            $view->with(['pageTitle' => 'Preferred Work Type Edit']);
        });

        /*
        * JOEYS
        */

        view()->composer('admin.joeys.index', function ($view) {
            $view->with(['pageTitle' => 'Joeys List']);
        });
        view()->composer('admin.joeys.agreementNotSigned', function ($view) {
            $view->with(['pageTitle' => 'Agreement Not Signed']);
        });
        view()->composer('admin.joeys.new-sign-up-joeys-list', function ($view) {
            $view->with(['pageTitle' => 'New Signup Joeys List']);
        });
        view()->composer('admin.joeys.edit', function ($view) {
            $view->with(['pageTitle' => 'Joey Edit']);
        });
        view()->composer('admin.joeys.joey-application-numbers', function ($view) {
            $view->with(['pageTitle' => 'Joey Statistics']);
        });
        view()->composer('admin.joeys.documentNotUploaded', function ($view) {
            $view->with(['pageTitle' => 'Document Not Uploaded Joey List']);
        });

        view()->composer('admin.joeys.documentNotApproved', function ($view) {
            $view->with(['pageTitle' => 'Document Not Approved Joey List']);
        });
        view()->composer('admin.joeys.documentApproved', function ($view) {
            $view->with(['pageTitle' => 'Document Approved Joey List']);
        });

        view()->composer('admin.joeys.notTrained', function ($view) {
            $view->with(['pageTitle' => 'Joeys Not Trained List']);
        });

        view()->composer('admin.joeys.quizPending', function ($view) {
            $view->with(['pageTitle' => 'Joeys Quiz Pending List']);
        });
        view()->composer('admin.joeys.quizPassed', function ($view) {
            $view->with(['pageTitle' => 'Joeys Quiz Passed List']);
        });



        /*
        * JOEY DOCUMENT VERIFICATION
        */

        view()->composer('admin.joeyDocumentVerification.index', function ($view) {
            $view->with(['pageTitle' => 'Joey Documents Verification List']);
        });
        view()->composer('admin.joeyDocumentVerification.expired-document', function ($view) {
            $view->with(['pageTitle' => 'Joey Expired Documents List']);
        });
        view()->composer('admin.joeyDocumentVerification.create', function ($view) {
            $view->with(['pageTitle' => 'Joey Document Verification Create']);
        });
        view()->composer('admin.joeyDocumentVerification.show', function ($view) {
            $view->with(['pageTitle' => 'Joey Document Verification View']);
        });
        view()->composer('admin.joeyDocumentVerification.edit', function ($view) {
            $view->with(['pageTitle' => 'Joey Document Verification Edit']);
        });

        /*
        * Training Videos and documents
        */

        view()->composer('admin.training.index', function ($view) {
            $view->with(['pageTitle' => 'Training Videos & Documents List']);
        });
        view()->composer('admin.training.create', function ($view) {
            $view->with(['pageTitle' => 'Training Video And Document Create']);
        });
        view()->composer('admin.training.show', function ($view) {
            $view->with(['pageTitle' => 'Training Video And Document View']);
        });
        view()->composer('admin.training.edit', function ($view) {
            $view->with(['pageTitle' => 'Training Video And Document Edit']);
        });

        /*
        * Categories
        */

        view()->composer('admin.categores.index', function ($view) {
            $view->with(['pageTitle' => 'Categories Order Count List']);
        });
        view()->composer('admin.categores.create', function ($view) {
            $view->with(['pageTitle' => 'Category Create']);
        });
        view()->composer('admin.categores.show', function ($view) {
            $view->with(['pageTitle' => 'Category View']);
        });
        view()->composer('admin.categores.edit', function ($view) {
            $view->with(['pageTitle' => 'Category Edit']);
        });

        /*
        * Quiz
        */

        view()->composer('admin.quizManagement.index', function ($view) {
            $view->with(['pageTitle' => 'Quiz Management List']);
        });
        view()->composer('admin.quizManagement.create', function ($view) {
            $view->with(['pageTitle' => 'Quiz Create']);
        });
        view()->composer('admin.quizManagement.show', function ($view) {
            $view->with(['pageTitle' => 'Quiz View']);
        });
        view()->composer('admin.quizManagement.edit', function ($view) {
            $view->with(['pageTitle' => 'Quiz Edit']);
        });

        /*
        * Job Type
        */

        view()->composer('admin.jobType.index', function ($view) {
            $view->with(['pageTitle' => 'Job Types List']);
        });
        view()->composer('admin.jobType.create', function ($view) {
            $view->with(['pageTitle' => 'Job Type Create']);
        });
        view()->composer('admin.jobType.show', function ($view) {
            $view->with(['pageTitle' => 'Job Type View']);
        });
        view()->composer('admin.jobType.edit', function ($view) {
            $view->with(['pageTitle' => 'Job Type Edit']);
        });


        /*
        * Order Category
        */

        view()->composer('admin.orderCategory.index', function ($view) {
            $view->with(['pageTitle' => 'Order Categories List']);
        });
        view()->composer('admin.orderCategory.create', function ($view) {
            $view->with(['pageTitle' => 'Order Category Create']);
        });
        view()->composer('admin.orderCategory.show', function ($view) {
            $view->with(['pageTitle' => 'Order Category View']);
        });
        view()->composer('admin.orderCategory.edit', function ($view) {
            $view->with(['pageTitle' => 'Order Category Edit']);
        });

        /*
                 * Joey Check List
                 */

        view()->composer('admin.joeyChecklist.index', function ($view) {
            $view->with(['pageTitle' => 'Joey Checklist']);
        });
        view()->composer('admin.joeyChecklist.create', function ($view) {
            $view->with(['pageTitle' => 'Joey Checklist Create']);
        });
        view()->composer('admin.joeyChecklist.show', function ($view) {
            $view->with(['pageTitle' => 'Joey Checklist  View']);
        });
        view()->composer('admin.joeyChecklist.edit', function ($view) {
            $view->with(['pageTitle' => 'Joey Checklist Edit']);
        });


        /*
       * vendors order count
       */

        view()->composer('admin.vendors.index', function ($view) {
            $view->with(['pageTitle' => 'Vendors Order Counts']);
        });
        view()->composer('admin.vendors.create', function ($view) {
            $view->with(['pageTitle' => 'Vendors Order Count Create']);
        });
        view()->composer('admin.vendors.show', function ($view) {
            $view->with(['pageTitle' => 'Vendors Order Count View']);
        });
        view()->composer('admin.vendors.edit', function ($view) {
            $view->with(['pageTitle' => 'Vendors Order CountEdit']);
        });

        /*
                * Basic Vendor
                */

        view()->composer('admin.basicVendor.index', function ($view) {
            $view->with(['pageTitle' => 'Basic Vendors List']);
        });
        view()->composer('admin.basicVendor.create', function ($view) {
            $view->with(['pageTitle' => 'Basic Vendor Create']);
        });


        /*
        * Basic category
        */

        view()->composer('admin.basicCategory.index', function ($view) {
            $view->with(['pageTitle' => 'Basic Categories List']);
        });
        view()->composer('admin.basicCategory.create', function ($view) {
            $view->with(['pageTitle' => 'Basic Category Create']);
        });


        /*
       * Vendor Score
       */

        view()->composer('admin.vendorScore.index', function ($view) {
            $view->with(['pageTitle' => 'Vendors Score List']);
        });
        view()->composer('admin.vendorScore.create', function ($view) {
            $view->with(['pageTitle' => 'Vendor Score Create']);
        });
        view()->composer('admin.vendorScore.show', function ($view) {
            $view->with(['pageTitle' => 'Vendor Score View']);
        });
        view()->composer('admin.vendorScore.edit', function ($view) {
            $view->with(['pageTitle' => 'Vendor Score Edit']);
        });


        /*
        * Category Score
        */

        view()->composer('admin.categoryScore.index', function ($view) {
            $view->with(['pageTitle' => 'Categories Score List']);
        });
        view()->composer('admin.categoryScore.create', function ($view) {
            $view->with(['pageTitle' => 'Category Score Create']);
        });
        view()->composer('admin.categoryScore.show', function ($view) {
            $view->with(['pageTitle' => 'Category Score View']);
        });
        view()->composer('admin.categoryScore.edit', function ($view) {
            $view->with(['pageTitle' => 'Category Score Edit']);
        });

 /*
         * Joey Mails
         */
        view()->composer('admin.notification.joey_notification', function ($view) {
            $view->with(['pageTitle' => 'Joey BroadCasting Notification']);
        });

        /*
             * Documents
             */

        view()->composer('admin.documents.index', function ($view) {
            $view->with(['pageTitle' => 'Documents List']);
        });
        view()->composer('admin.documents.create', function ($view) {
            $view->with(['pageTitle' => 'Documents Create']);
        });
        view()->composer('admin.documents.show', function ($view) {
            $view->with(['pageTitle' => 'Documents View']);
        });
        view()->composer('admin.documents.edit', function ($view) {
            $view->with(['pageTitle' => 'Documents Edit']);
        });






        /*
      * Faqs
      */

        view()->composer('admin.faqs.index', function ($view) {
            $view->with(['pageTitle' => 'FAQ List']);
        });
        view()->composer('admin.faqs.create', function ($view) {
            $view->with(['pageTitle' => 'FAQ Create']);
        });
        view()->composer('admin.faqs.show', function ($view) {
            $view->with(['pageTitle' => 'FAQ View']);
        });
        view()->composer('admin.faqs.edit', function ($view) {
            $view->with(['pageTitle' => 'FAQ Edit']);
        });


        /*
    * Joey Attempt Quiz
    */

        view()->composer('admin.joey-attempt-quiz.index', function ($view) {
            $view->with(['pageTitle' => 'Joey Attempt Quiz List']);
        });
//        view()->composer('admin.faqs.create', function ($view) {
//            $view->with(['pageTitle' => 'Faqs Create']);
//        });
        view()->composer('admin.joey-attempt-quiz.show', function ($view) {
          $view->with(['pageTitle' => 'Joey Attempt Quiz Details']);
        });
//        view()->composer('admin.faqs.edit', function ($view) {
//            $view->with(['pageTitle' => 'Faqs Edit']);
//        });





        /*
      * Customer send Messages
      */

        view()->composer('admin.customerSendMessages.index', function ($view) {
            $view->with(['pageTitle' => 'Customer Send Messages List']);
        });
        view()->composer('admin.customerSendMessages.create', function ($view) {
            $view->with(['pageTitle' => 'Customer Send Messages Create']);
        });
        view()->composer('admin.customerSendMessages.show', function ($view) {
            $view->with(['pageTitle' => 'Customer Send Messages View']);
        });
        view()->composer('admin.customerSendMessages.edit', function ($view) {
            $view->with(['pageTitle' => 'Customer Send Messages Edit']);
        });

        /*
                    * Customer Services
                    */

        view()->composer('admin.customer-services.index', function ($view) {
            $view->with(['pageTitle' => 'Flag List']);
        });
        view()->composer('admin.customer-services.create', function ($view) {
            $view->with(['pageTitle' => 'Add Flag']);
        });
        view()->composer('admin.customer-services.edit', function ($view) {
            $view->with(['pageTitle' => 'Flag Edit']);
        });
        view()->composer('admin.customer-services.detail', function ($view) {
            $view->with(['pageTitle' => 'Flag Category Detail']);
        });
        //Flag Incident
        view()->composer('admin.customer-services.flag-incident.index', function ($view) {
            $view->with(['pageTitle' => 'Flag Suspension Policy List']);
        });
        view()->composer('admin.customer-services.flag-incident.create', function ($view) {
            $view->with(['pageTitle' => 'Add Flag Suspension Policy']);
        });
        view()->composer('admin.customer-services.flag-incident.edit', function ($view) {
            $view->with(['pageTitle' => 'Flag Suspension Policy Edit']);
        });

       /*
  * Joeys Complaints
  */

        view()->composer('admin.joey-complaints.index', function ($view) {
            $view->with(['pageTitle' => 'Joey Complaints List']);
        });



        /*
         * Change Password
         */
        view()->composer('admin.users.changePassword', function ($view) {
            $view->with(['pageTitle' => 'Change Password']);
        });

        /*
         * Change Password
         */
        view()->composer('admin.users.profile', function ($view) {
            $view->with(['pageTitle' => 'Edit Profile']);
        });



        //Micro Hub Page Title
        /**
         * Micro Hub role & permissions
         */
        view()->composer('admin.micro-hub.role.index', function ($view) {
            $view->with(['pageTitle' => 'Role List']);
        });
        view()->composer('admin.micro-hub.role.create', function ($view) {
            $view->with(['pageTitle' => 'Role Create']);
        });
        view()->composer('admin.micro-hub.role.edit', function ($view) {
            $view->with(['pageTitle' => 'Role Edit']);
        });
        view()->composer('admin.micro-hub.role.show', function ($view) {
            $view->with(['pageTitle' => 'Role View']);
        });
        view()->composer('admin.micro-hub.role.set-permissions', function ($view) {
            $view->with(['pageTitle' => 'Set Role Permissions']);
        });

        /**
         * Micro Hub Sub Admin
         */
        view()->composer('admin.micro-hub.subAdmin.index', function ($view) {
            $view->with(['pageTitle' => 'Sub Admins List']);
        });
        view()->composer('admin.micro-hub.subAdmin.create', function ($view) {
            $view->with(['pageTitle' => 'Sub Admin Create']);
        });
        view()->composer('admin.micro-hub.subAdmin.edit', function ($view) {
            $view->with(['pageTitle' => 'Sub Admin Edit']);
        });
        view()->composer('admin.micro-hub.subAdmin.show', function ($view) {
            $view->with(['pageTitle' => 'Sub Admin View']);
        });

        /**
         * Micro Hub Users List
         */
        view()->composer('admin.micro-hub.microHubUsers.index', function ($view) {
            $view->with(['pageTitle' => 'MicroHub Users List']);
        });
        view()->composer('admin.micro-hub.microHubUsers.hub-user-profile-update', function ($view) {
            $view->with(['pageTitle' => 'MicroHub User Profile Update']);
        });

        /**
         * Micro Hub Approved List
         */
        view()->composer('admin.micro-hub.microHubUsers.approved-micro-hub.index', function ($view) {
            $view->with(['pageTitle' => 'Approved User List']);
        });
        /**
         * Micro Hub Not Approved List
         */
        view()->composer('admin.micro-hub.microHubUsers.notApproved-list.index', function ($view) {
            $view->with(['pageTitle' => 'Not Approved User List']);
        });
        /**
         * Micro Hub Document Approved List
         */
        view()->composer('admin.micro-hub.microHubUsers.document-approved.index', function ($view) {
            $view->with(['pageTitle' => 'Document Approved User List']);
        });
        /**
         * Micro Hub Document Not Approved List
         */
        view()->composer('admin.micro-hub.microHubUsers.documentNot-approved.index', function ($view) {
            $view->with(['pageTitle' => 'Document Not Approved User List']);
        });
        /**
         * Micro Hub Document Not Uploaded List
         */
        view()->composer('admin.micro-hub.microHubUsers.documentNotUploaded.index', function ($view) {
            $view->with(['pageTitle' => 'Document Not Uploaded User List']);
        });
        /**
         * Micro Hub Not Trained List
         */
        view()->composer('admin.micro-hub.microHubUsers.notTrained-list.index', function ($view) {
            $view->with(['pageTitle' => 'Not Trained User List']);
        });
        /**
         * Micro Hub Quiz Pending List
         */
        view()->composer('admin.micro-hub.microHubUsers.quiz-pending-list.index', function ($view) {
            $view->with(['pageTitle' => 'Quiz Pending User List']);
        });
        /**
         * Micro Hub Quiz Passed List
         */
        view()->composer('admin.micro-hub.microHubUsers.quizPassed-list.index', function ($view) {
            $view->with(['pageTitle' => 'Quiz Passed User List']);
        });

        /**
         * Micro Hub Document verification
         */
        view()->composer('admin.micro-hub.document-verification.index', function ($view) {
            $view->with(['pageTitle' => 'Document Verification']);
        });
        /**
         * Micro Hub Document verification Show
         */
        view()->composer('admin.micro-hub.document-verification.show', function ($view) {
            $view->with(['pageTitle' => 'Document Verification']);
        });
        /**
         * Micro Hub Document verification Edit
         */
        view()->composer('admin.micro-hub.document-verification.edit', function ($view) {
            $view->with(['pageTitle' => 'Document Verification Edit']);
        });
		
		/**
		 * Micro Hub Assign
		 */
		view()->composer('admin.micro-hub.micro-hub-assign.index', function ($view) {
			$view->with(['pageTitle' => 'Micro Hub User List']);
		});
		view()->composer('admin.micro-hub.micro-hub-assign.edit', function ($view) {
			$view->with(['pageTitle' => 'Hub Assign']);
		});

        /**
         * Micro Hub Training Videos and documents
         */
        view()->composer('admin.micro-hub.training.index', function ($view) {
            $view->with(['pageTitle' => 'Trainings Videos & Documents List']);
        });
        view()->composer('admin.micro-hub.training.create', function ($view) {
            $view->with(['pageTitle' => 'Trainings Video And Document Create']);
        });
        view()->composer('admin.micro-hub.training.show', function ($view) {
            $view->with(['pageTitle' => 'Trainings Video And Document View']);
        });
        view()->composer('admin.micro-hub.training.edit', function ($view) {
            $view->with(['pageTitle' => 'Trainings Video And Document Edit']);
        });

        /**
         * Micro Hub Document Type
         */
        view()->composer('admin.micro-hub.documents.index', function ($view) {
            $view->with(['pageTitle' => 'Document List']);
        });
        view()->composer('admin.micro-hub.documents.create', function ($view) {
            $view->with(['pageTitle' => 'Document Create']);
        });
        view()->composer('admin.micro-hub.documents.edit', function ($view) {
            $view->with(['pageTitle' => 'Document Edit']);
        });

        /**
         * Order Category
         */
        view()->composer('admin.micro-hub.orderCategory.index', function ($view) {
            $view->with(['pageTitle' => 'Order Categories List']);
        });
        view()->composer('admin.micro-hub.orderCategory.create', function ($view) {
            $view->with(['pageTitle' => 'Order Category Create']);
        });
        view()->composer('admin.micro-hub.orderCategory.show', function ($view) {
            $view->with(['pageTitle' => 'Order Category View']);
        });
        view()->composer('admin.micro-hub.orderCategory.edit', function ($view) {
            $view->with(['pageTitle' => 'Order Category Edit']);
        });

        /**
         * Quiz
         */
        view()->composer('admin.micro-hub.quizManagement.index', function ($view) {
            $view->with(['pageTitle' => 'Quiz Management List']);
        });
        view()->composer('admin.micro-hub.quizManagement.create', function ($view) {
            $view->with(['pageTitle' => 'Quiz Create']);
        });
        view()->composer('admin.micro-hub.quizManagement.show', function ($view) {
            $view->with(['pageTitle' => 'Quiz View']);
        });
        view()->composer('admin.micro-hub.quizManagement.edit', function ($view) {
            $view->with(['pageTitle' => 'Quiz Edit']);
        });

        /**
         * Joey Attempt Quiz
         */
        view()->composer('admin.micro-hub.attempt-quiz.index', function ($view) {
            $view->with(['pageTitle' => 'Attempt Quiz List']);
        });
        view()->composer('admin.micro-hub.attempt-quiz.show', function ($view) {
            $view->with(['pageTitle' => 'Attempt Quiz Details']);
        });

        /**
         * Edit Profile
         */
        view()->composer('admin.micro-hub.users.profile', function ($view) {
            $view->with(['pageTitle' => 'Edit Profile']);
        });

        /**
         * Change Password
         */
        view()->composer('admin.micro-hub.users.profile', function ($view) {
            $view->with(['pageTitle' => 'Edit Profile']);
        });

        /**
         * Change Password
         */
        view()->composer('admin.micro-hub.users.changePassword', function ($view) {
            $view->with(['pageTitle' => 'Change Password']);
        });

    }
}
