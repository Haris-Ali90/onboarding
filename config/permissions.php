<?php


/**
 * Permissions config
 *
 * @author Muhammad Adnan <adnannadeem1994@gmail.com>
 * @date   23/10/2020
 */

return [
    /*'Statistics'=>
        [
            'Statistics' => 'joeys.statistics|joeys.basicRegistration|joeys.docSubmission|joeys.totalApplicationSubmissionTable|joeys.totalTrainingwatchedTable|joeys.totalQuizPassedTable',
        ],*/
    'Roles'=>
        [
            'Role List' => 'role.index',
            'Create' => 'role.create|role.store',
            'Edit' => 'role.edit|role.update',
            'View' => 'role.show',
            'Set Permissions' => 'role.set-permissions|role.set-permissions.update',
        ],
    'Sub Admins'=>
        [
            'Sub Admin List' => 'sub-admin.index|sub-admin.data',
            'Create' => 'sub-admin.create|sub-admin.store',
            'Edit' => 'sub-admin.edit|sub-admin.update',
            'Status Change' => 'sub-admin.active|sub-admin.inactive',
            'View' => 'sub-admin.show',
            'Delete' => 'sub-admin.destroy',
        ],
    'Joeys List'=>
        [
            'Joeys List' => 'joeys-list.index|joeys.data',
			'Active Status' => 'joeys-list.active|joeys-list.inactive',
            'Edit' => 'joeys-list.edit|joeys-list.update',
            'Document Not Uploaded' => 'joeys.documentNotUploaded|joeys.documentNotUploadedData|joeys.documentNotUploadedNotification|joeys.bulkDocumentNotUploadedNotification',
            'Document Not Approved' => 'joeys.documentNotApproved|joeys.documentNotApprovedData',
            'Document Approved' => 'joeys.documentApproved|joeys.documentApprovedData',
            'Not Trained' => 'joeys.notTrained|joeys.notTrainedData|joeys.notTrainedNotification|joeys.bulkNotTrainedNotification',
            'Quiz Pending' => 'joeys.quizPending|joeys.quizPendingData|joeys.quizPendingNotification|joeys.bulkQuizPendingNotification',
            'Quiz Passed' => 'joeys.quizPassed|joeys.quizPassedData',
            'New Signup Joeys List' => 'newSignUpJoeys.index|newSignUpJoeys.data',
            'Joeys Agreement Not Signed' => 'joeys.agreementNotSigned|joeys.agreementNotSignedData',
        ],
    'Joey Complaint List'=>
        [
            'Joey Complaint List' => 'joeys-complaints.index|joeys-complaints.data|joeys-complaints.statusUpdate',
        ],
    'Joey Document Verification'=>
        [
            'Joey Document Verification List' => 'joey-document-verification.index|joey-document-verification.data',
            'View' => 'joey-document-verification.show',
            'Edit' => 'joey-document-verification.edit|joey-document-verification.update',
            'Joey Expired Document List' => 'joey-document-verification.expiredDocument|joey-expired-document.data',
            'Status change' => 'joey-document-verification.statusUpdate',
        ],
     'Joey Attempted Quiz'=>
        [
            'Joey Attempt Quiz List' => 'joey-attempt-quiz.index|joey-attempt-quiz.data',
            'View' => 'joey-attempt-quiz.show',

        ],
    'Joey Broadcasting Notification'=>
        [
            'Broadcasting Notification' => 'notification.index|notification.send',
        ],
    'Customer Send Messages'=>
        [
            'Customer Send Messages List' => 'customer-send-messages.index|customer-send-messages.data',
            'Create' => 'customer-send-messages.create|customer-send-messages.store',
            'Edit' => 'customer-send-messages.edit|customer-send-messages.update',
            'Delete' => 'customer-send-messages.destroy',
        ],
        'Flagging System'=>
        [
            'Flag List' => 'customer-service.index',
            'Create' => 'customer-service.create|customer-service.store',
            'Edit' => 'customer-service.edit|customer-service.update|customer-services.sub-category.delete',
            'Category Status Change' => 'customer-service.isEnable|customer-service.isDisable',
            'View' => 'customer-service.show',
            'Flag Incident List' => 'flag-incident.index',
            'Incident Create' => 'flag-incident.create|flag-incident.store',
            'Incident Edit' => 'flag-incident.edit|flag-incident.update',
            'Incident Status Change' => 'flag-incident.isEnable|flag-incident.isDisable',
        ],

     'Categories Order Count'=>
        [
            'Categories Order Count List' => 'categores.index|categores.data',
            'Create' => 'categores.create|categores.store',
            'Edit' => 'categores.edit|categores.update',
            'Delete' => 'categores.destroy',
        ],
    'Joey Checklists '=>
        [
            'Joey Checklists List' => 'joey-checklist.index|joey-checklist.data',
            'Create' => 'joey-checklist.create|joey-checklist.store',
            'Edit' => 'joey-checklist.edit|joey-checklist.update',
            'Delete' => 'joey-checklist.destroy',
        ],
    'Documents'=>
        [
            'Documents List' => 'documents.index|documents.data',
            'Create' => 'documents.create|documents.store',
            'Edit' => 'documents.edit|documents.update',
            'Delete' => 'documents.destroy',
        ],

    'Zones'=>
        [
            'Zones List' => 'zones.index|zones.data',
/*            'Create' => 'zones.create|zones.store',
            'Edit' => 'zones.edit|zones.update',
            'Delete' => 'zones.destroy',*/
        ],
    'Work Time'=>
        [
            'Prefered Work Time List' => 'work-time.index|work-time.data',
            'Create' => 'work-time.create|work-time.store',
            'Edit' => 'work-time.edit|work-time.update',
            'Delete' => 'work-time.destroy',
        ],
    'Work Type'=>
        [
            'Work Type List' => 'work-type.index|work-type.data',
            'Create' => 'work-type.create|work-type.store',
            'Edit' => 'work-type.edit|work-type.update',
            'Delete' => 'work-type.destroy',
        ],

/*    'Job Types '=>
        [
            'Job Types list' => 'job-type.index|job-type.data',
            'Create' => 'job-type.create|job-type.store',
            'Edit' => 'job-type.edit|job-type.update',
            'Delete' => 'job-type.destroy',
        ],*/


 /*   'Basic Vendors '=>
        [
            'Basic Vendors list' => 'basic-vendor.index|basic-vendor.data',
            'Create' => 'basic-vendor.create|basic-vendor.store',
            'Delete' => 'basic-vendor.destroy',
        ],
    'Basic Categories'=>
        [
            'Basic Categories list' => 'basic-category.index|basic-category.data',
            'Create' => 'basic-category.create|basic-category.store',
            'Delete' => 'basic-category.destroy',
        ],*/
/*    'Vendors Score'=>
        [
            'Vendors Score list' => 'vendor-score.index|vendor-score.data',
            'Create' => 'vendor-score.create|vendor-score.store',
            'Edit' => 'vendor-score.edit|vendor-score.update',
            'Delete' => 'vendor-score.destroy',
        ],
    'Categories Score'=>
        [
            'Categories Score list' => 'category-score.index|category-score.data',
            'Create' => 'category-score.create|category-score.store',
            'Edit' => 'category-score.edit|category-score.update',
            'Delete' => 'category-score.destroy',
        ],*/
    /*'Vendors'=>
        [
            'Vendors List' => 'vendors.index|vendors.data',
            'Create'=> 'vendors.create|vendors.store',
            'Edit' => 'vendors.edit|vendors.update',
            'Delete' => 'vendors.destroy',
        ],*/

/*    'Order Categories'=>
        [
            'Order Categories list' => 'order-category.index|order-category.data',
            'Create' => 'order-category.create|order-category.store',
            'Edit' => 'order-category.edit|order-category.update',
            'Delete' => 'order-category.destroy',
        ],*/
    'Setting'=>
        [
            'Setting Main Page' => 'dashboard.index',
            'Change Password' => 'users.change-password',
            'Edit Profile' => 'users.edit-profile',
        ],

    'Order Categories'=>
        [
            'Order Categories List' => 'order-category.index|order-category.data',
            'Create' => 'order-category.create|order-category.store',
            'Edit' => 'order-category.edit|order-category.update',
            'Delete' => 'order-category.destroy',
        ],

    'Training Videos and Documents'=>
        [
            'Training Videos & Documents List' => 'training.index|training.data',
            'Create' => 'training.create|training.store',
            'Edit' => 'training.edit|training.update',
            'Delete' => 'training.destroy',
        ],

    'Quizes Management'=>
        [
            'Quizes Management List' => 'quiz-management.index|quiz-management.data',
            'Create' => 'quiz-management.create|quiz-management.store',
            'Edit' => 'quiz-management.edit|quiz-management.update',
            'Delete' => 'quiz-management.destroy',
            'View' => 'quiz-management.show',
        ],

    'FAQs'=>
        [
            'FAQ List' => 'faqs.index|faqs.data',
            'Create' => 'faqs.create|faqs.store',
            'Edit' => 'faqs.edit|faqs.update',
            'Delete' => 'faqs.destroy',
        ],

    'Micro Hub Roles'=>
        [
            'Micro Hub Role List' => 'micro-hub.role.index',
            'Micro Hub Create' => 'micro-hub.role.create|micro-hub.role.store',
            'Micro Hub Edit' => 'micro-hub.role.edit|micro-hub.role.update',
            'Micro Hub View' => 'micro-hub.role.show',
            'Micro Hub Set Permissions' => 'micro-hub.role.set-permissions|micro-hub.role.set-permissions.update',
        ],
    'Micro Hub Sub Admins'=>
        [
            'Sub Admin List' => 'micro-hub.sub-admin.index|micro-hub.sub-admin.data',
            'Create' => 'micro-hub.sub-admin.create|micro-hub.sub-admin.store',
            'Status Change' => 'micro-hub.sub-admin.active|micro-hub.sub-admin.inactive',
            'Edit' => 'micro-hub.sub-admin.edit|micro-hub.sub-admin.update',
            'View' => 'micro-hub.sub-admin.show',
            'Delete' => 'micro-hub.sub-admin.delete',
        ],
    'Edit User'=>
        [
            'Edit Profile' => 'micro-hub.users.edit-profile',
            'Change Password' => 'micro-hub.users.change-password',
            'Logout' => 'micro-hub.login|micro-login|micro-hub.logout',
        ],

    'Micro Hub Users'=>
        [
            'Micro Hub Users List' => 'micro-hub.users.index|micro-hub.users.data',
            'Micro Hub User Status' => 'micro-hub.users.statusUpdate|micro-hub.profile-status.edit|micro-hub.profile-status.update',
        ],

    'Micro Hub Manager List'=>
        [
            'Approved List' => 'micro-hub.approved.index|micro-hub.approved.data|micro-hub.hubProfileEdit.edit',
            'Edit Hub Permission' => 'micro-hub.HubPermission.update',
            'Approved Postal Code Create' => 'postal-code.create|postal-code-create-model-html-render',
            'Document Approved List' => 'micro-hub.documentApproved.index|micro-hub.documentApproved.data',
            'Document Not Approved List' => 'micro-hub.documentNotApproved.index|micro-hub.documentNotApproved.data',
            'Approved Zone Create' => 'zone.create|zone-create-model-html-render',
            'Not Approved List' => 'micro-hub.notApproved.index|micro-hub.notApproved.data',
            'Document Not Uploaded' => 'micro-hub.documentNotUploaded.index|micro-hub.documentNotUploadedData.data',
            'Not Trained List' => 'micro-hub.notTrained.index|micro-hub.notTrained.data',
            'Quiz Pending List' => 'micro-hub.quizPending.index|micro-hub.quizPending.data',
            'Quiz Passed List' => 'micro-hub.quizPassed.index|micro-hub.quizPassed.data',
        ],

    'Micro Hub Document Verification'=>
        [
            'Document Verification' => 'micro-hub.documentVerificationData.index|micro-hub.documentVerificationData.data',
            'Status Change' => 'micro-hub.document-verification.statusUpdate',
            'Show' => 'micro-hub.documentVerificationData.show',
            'Edit' => 'micro-hub.documentVerificationData.edit|micro-hub.documentVerificationData.update',
        ],

    'Micro Hub Documents'=>
        [
            'Documents List' => 'micro-hub.documentList.index|micro-hub.documentList.data',
            'Create' => 'micro-hub.documentList.create|micro-hub.documentList.store',
            'Edit' => 'micro-hub.documentList.edit|micro-hub.documentList.update',
            'Delete' => 'micro-hub.documentList.destroy',
        ],
    'Micro Hub Training Videos and Documents'=>
        [
            'Training Videos & Documents List' => 'training-list.index|micro-hub.training.data',
            'Create' => 'training-list.create|training-list.store',
            'Edit' => 'training-list.edit|training-list.update',
            'Delete' => 'training-list.destroy',
        ],
    'Micro Hub Order Categories'=>
        [
            'Order Categories List' => 'order-category-list.index|order-category-list.data',
            'Create' => 'order-category-list.create|order-category-list.store',
            'Edit' => 'order-category-list.edit|order-category-list.update',
            'Delete' => 'order-category-list.destroy',
        ],
    'Micro Hub Quiz Management'=>
        [
            'Quiz Management List' => 'quiz-management-list.index|quiz-management-list.data',
            'Create' => 'quiz-management-list.create|quiz-management-list.store',
            'Edit' => 'quiz-management-list.edit|quiz-management-list.update',
            'Show' => 'quiz-management-list.show',
            'Delete' => 'quiz-management-list.destroy',
        ],
    'Micro Hub Attempted Quiz'=>
        [
            'Attempt Quiz List' => 'quiz-attempt.index|quiz-attempt.data',
            'View' => 'quiz-attempt.show',

        ],
		'Micro Hub Assign'=>
        [
            'Micro Hub Assign List' => 'micro-hub-assign.index|micro-hub-assign.data',
            'Micro Assign Update' => 'micro-hub-assign.edit|micro-hub-assign.update|city-hub-assign.update',
        ],

	'Chat Thread'=>
    [
        'Chat Thread' => 'thread.index',
    ],

];
