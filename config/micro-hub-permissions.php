<?php


/**
 * Permissions config
 *
 * @author Muhammad Adnan <adnannadeem1994@gmail.com>
 * @date   23/10/2020
 */

return [
    //Micro Hub Permissions

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
		'Micro Hub Assign'=>
        [
            'Micro Hub Assign List' => 'micro-hub-assign.index|micro-hub-assign.data',
            'Micro Assign Update' => 'micro-hub-assign.edit|micro-hub-assign.update|city-hub-assign.update',
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

];
