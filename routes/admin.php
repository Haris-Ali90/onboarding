<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('admin', 'IndexController@index')->name('login'); // for redirection purpose

Route::get('/download-file', function () {
    // getting current domain path
    $domain_path = url('');
    // getting request file path
    $request_file_path = str_replace($domain_path,"",request()->fileData);
    // getting file path
    $file_path  = public_path().$request_file_path;
    return response()->download($file_path);
})->name('download-file');

Route::get('push',function (){
    $devices = \App\Models\UserDevice::where('user_id', 32092)->pluck('device_token')->toArray();
    \App\Classes\Fcm::sendPush("customtest live", 'test','test',null, $devices);
    dd("tt");
}); // for redirection purpose


/*Route::name('/')->group(
    function () {*/

Route::get('/', 'IndexController@index');
Route::get('/index', 'IndexController@home')->name('index');
Route::get('csv-test','IndexController@csvcheck');
Route::group(['middleware' => 'backendAuthenticate'], function () {

    Route::get('joeys/statistics', 'JoeyController@statistics')->name('joeys.statistics');


    Route::get('joey/basicRegistrationTable', 'JoeyController@basicRegistration')->name('joeys.basicRegistration');
    Route::get('joey/docSubmissionTable', 'JoeyController@docSubmissionTable')->name('joeys.docSubmission');
    Route::get('joey/totalApplicationSubmissionTable', 'JoeyController@totalApplicationSubmissionTable')->name('joeys.totalApplicationSubmissionTable');
    Route::get('joey/totalTrainingwatchedTable', 'JoeyController@totalTrainingwatchedTable')->name('joeys.totalTrainingwatchedTable');
    Route::get('joey/totalQuizPassedTable', 'JoeyController@totalQuizPassedTable')->name('joeys.totalQuizPassedTable');

    Route::group(['middleware' => ['backendAuthenticate','PermissionHandler']], function () {

        Route::get('/dashboard', ['uses' => 'DashboardController@index', 'as' => 'dashboard.index']);

         ###role management routes###
        Route::resource('role', 'RoleController');
        Route::get('role/set-permissions/{role}', 'RoleController@setPermissions')->name('role.set-permissions');
        Route::post('role/set-permissions/update/{role}', 'RoleController@setPermissionsUpdate')->name('role.set-permissions.update');

        ###Sub Admins###
        Route::get('sub-admin/data', 'SubAdminController@data')->name('sub-admin.data');
        Route::resource('sub-admin', 'SubAdminController');
        Route::get('sub-admin/active/{record}', 'SubAdminController@active')->name('sub-admin.active');
        Route::get('sub-admin/inactive/{record}', 'SubAdminController@inactive')->name('sub-admin.inactive');


        Route::get('zones/data', 'ZonesController@data')->name('zones.data');
        Route::resource('zones', 'ZonesController');


        Route::get('work-time/data', 'WorkTimeController@data')->name('work-time.data');
        Route::resource('work-time', 'WorkTimeController');


        Route::get('work-type/data', 'WorkTypeController@data')->name('work-type.data');
        Route::resource('work-type', 'WorkTypeController');

        Route::get('joey-document-verification/data', 'JoeyDocumentVerificationController@data')->name('joey-document-verification.data');
        Route::get('joey-document-verification/expired/data', 'JoeyDocumentVerificationController@expiredData')->name('joey-expired-document.data');
        Route::resource('joey-document-verification', 'JoeyDocumentVerificationController');
        Route::get('joey-document-verification-expired', 'JoeyDocumentVerificationController@expiredDocument')->name('joey-document-verification.expiredDocument');
        Route::get('joeyDocumentVerification/status/update/statusUpdate', 'JoeyDocumentVerificationController@statusUpdate')->name('joey-document-verification.statusUpdate');

        Route::get('joeys/data', 'JoeyController@data')->name('joeys.data');
        Route::resource('joeys-list', 'JoeyController');
		//Joeys Active / Inactive
		Route::get('joeys-list/active/{record}', 'JoeyController@active')->name('joeys-list.active');
		Route::get('joeys-list/inactive/{record}', 'JoeyController@inactive')->name('joeys-list.inactive');

        Route::get('joeys/agreement-not-signed-data', 'JoeyController@agreementNotSignedData')->name('joeys.agreementNotSignedData');
        Route::get('agreement-not-signed', 'JoeyController@agreementNotSigned')->name('joeys.agreementNotSigned');

         // fordatatable Docuement NOt uploaded

        Route::get('joeys/documentNotUploadedData', 'JoeyController@documentNotUploadedData')->name('joeys.documentNotUploadedData');
        Route::get('joeys/documentNotUploaded', 'JoeyController@documentNotUploaded')->name('joeys.documentNotUploaded');
  Route::get('joeys/documentNotUploadedNotification', 'JoeyController@documentNotUploadedNotification')->name('joeys.documentNotUploadedNotification');
        Route::get('joeys/bulkDocumentNotUploadedNotification', 'JoeyController@bulkDocumentNotUploadedNotification')->name('joeys.bulkDocumentNotUploadedNotification');
         // fordatatable Docuement NOt appvoved


        Route::get('joeys/documentNotApprovedData', 'JoeyController@documentNotApprovedData')->name('joeys.documentNotApprovedData');
        Route::get('joeys/documentNotApproved', 'JoeyController@documentNotApproved')->name('joeys.documentNotApproved');


        // fordatatable Docuement appvoved

        Route::get('joeys/documentApprovedData', 'JoeyController@documentApprovedData')->name('joeys.documentApprovedData');
        Route::get('joeys/documentApproved', 'JoeyController@documentApproved')->name('joeys.documentApproved');


        // fordatatable not Trained

        Route::get('joeys/notTrainedData', 'JoeyController@notTrainedData')->name('joeys.notTrainedData');
        Route::get('joeys/notTrained', 'JoeyController@notTrained')->name('joeys.notTrained');
Route::get('joeys/notTrainedNotification', 'JoeyController@notTrainedNotification')->name('joeys.notTrainedNotification');
        Route::get('joeys/bulkNotTrainedNotification', 'JoeyController@bulkNotTrainedNotification')->name('joeys.bulkNotTrainedNotification');


        // fordatatable quiz pending

        Route::get('joeys/quizPendingData', 'JoeyController@quizPendingData')->name('joeys.quizPendingData');
        Route::get('joeys/quizPending', 'JoeyController@quizPending')->name('joeys.quizPending');
Route::get('joeys/quizPendingNotification', 'JoeyController@quizPendingNotification')->name('joeys.quizPendingNotification');
        Route::get('joeys/bulkQuizPendingNotification', 'JoeyController@bulkQuizPendingNotification')->name('joeys.bulkQuizPendingNotification');


        // fordatatable quiz passed

        Route::get('joeys/quizPassedData', 'JoeyController@quizPassedData')->name('joeys.quizPassedData');
        Route::get('joeys/quizPassed', 'JoeyController@quizPassed')->name('joeys.quizPassed');

        //Getting New SignUp Joeys
        Route::get('new-sign-up-joeys/data', 'JoeyController@newSignUpJoeysData')->name('newSignUpJoeys.data');
        Route::get('new-sign-up-joeys', 'JoeyController@newSignUpJoeys')->name('newSignUpJoeys.index');

        Route::get('training/data', 'TrainingController@data')->name('training.data');
        Route::resource('training', 'TrainingController');

        Route::get('categores/data', 'CategoresController@data')->name('categores.data');
        Route::resource('categores', 'CategoresController');


        Route::get('quiz-management/data', 'QuizController@data')->name('quiz-management.data');
        Route::resource('quiz-management', 'QuizController');


        Route::get('job-type/data', 'JobTypeController@data')->name('job-type.data');
        Route::resource('job-type', 'JobTypeController');


        Route::get('order-category/data', 'OrderCategoryController@data')->name('order-category.data');
        Route::resource('order-category', 'OrderCategoryController');

        Route::get('vendors/data', 'VendorsController@data')->name('vendors.data');
        Route::resource('vendors', 'VendorsController');

        Route::get('joey-checklist/data', 'JoeyChecklistController@data')->name('joey-checklist.data');
        Route::resource('joey-checklist', 'JoeyChecklistController');


        Route::get('basic-vendor/data', 'BasicVendorController@data')->name('basic-vendor.data');
        Route::resource('basic-vendor', 'BasicVendorController');


        Route::get('basic-category/data', 'BasicCategoryController@data')->name('basic-category.data');
        Route::resource('basic-category', 'BasicCategoryController');

        Route::get('vendor-score/data', 'VendorScoreController@data')->name('vendor-score.data');
        Route::resource('vendor-score', 'VendorScoreController');

        Route::get('category-score/data', 'CategoryScoreController@data')->name('category-score.data');
        Route::resource('category-score', 'CategoryScoreController');


        Route::resource('site-settings', 'SiteSettingsController');
        //Route::resource('countries', 'CountriesController');
        //Route::resource('cities', 'CitiesController');

        ###Mail Send To Joey###
        Route::get('notification', 'NotificationController@showNotification')->name('notification.index');
        Route::post('notification/send', 'NotificationController@sendNotification')->name('notification.send');

        ###Documents ###
        Route::get('documents/data', 'DocumentsController@data')->name('documents.data');
        Route::resource('documents', 'DocumentsController');


        ###Faqs ###
        Route::get('faqs/data', 'FaqsController@data')->name('faqs.data');
        Route::resource('faqs', 'FaqsController');


        ###Joey Quiz attempt ###
        Route::get('joey-attempt-quiz/data', 'JoeyAttemptQuizController@data')->name('joey-attempt-quiz.data');
        Route::resource('joey-attempt-quiz', 'JoeyAttemptQuizController');


        ###Customer send messages ###
        Route::get('customer-send-messages/data', 'CustomerSendMessagesController@data')->name('customer-send-messages.data');
        Route::resource('customer-send-messages', 'CustomerSendMessagesController');

        ###Customer Service###
        Route::resource('customer-service', 'CustomerServiceController');
        Route::get('customer-service/delete/sub-category', 'CustomerServiceController@deleteSubCategoryData')->name('customer-services.sub-category.delete');

        ###Active###
        Route::get('customer-service/active/{record}', 'CustomerServiceController@isEnable')->name('customer-service.isEnable');
        ###In-Active###
        Route::get('customer-service/inactive/{record}', 'CustomerServiceController@isDisable')->name('customer-service.isDisable');

### Flag Incident Value ###
Route::get('flag-incident', 'CustomerServiceController@flagIncidentIndex')->name('flag-incident.index');
Route::get('flag-incident/create', 'CustomerServiceController@flagIncidentCreate')->name('flag-incident.create');
Route::POST('flag-incident/store', 'CustomerServiceController@flagIncidentStore')->name('flag-incident.store');
Route::get('flag-incident/edit/{record}', 'CustomerServiceController@flagIncidentEdit')->name('flag-incident.edit');
Route::post('flag-incident/update/{record}', 'CustomerServiceController@flagIncidentUpdate')->name('flag-incident.update');
###Flag Incident Active###
Route::get('flag-incident/active/{record}', 'CustomerServiceController@flagIncidentEnable')->name('flag-incident.isEnable');
###Flag Incident In-Active###
Route::get('flag-incident/inactive/{record}', 'CustomerServiceController@flagIncidentDisable')->name('flag-incident.isDisable');



        ###Joey Complaint List ###
        Route::get('joeys-complaints/data', 'JoeysComplaintsController@data')->name('joeys-complaints.data');
        Route::resource('joeys-complaints', 'JoeysComplaintsController');

        ###Joey Complaint Status Change ###
        Route::get('joeys-complaints/status/update', 'JoeysComplaintsController@statusUpdate')->name('joeys-complaints.statusUpdate');

			###Chat Thread###
			Route::get('threads', 'ChatThreadController@index')->name('thread.index');

        Route::get('/edit-profile', [
            'uses' => 'UsersController@editProfile',
            'as' => 'users.edit-profile'
        ]);

        Route::post('/edit-profile', [
            'uses' => 'UsersController@updateEditProfile',
            'as' => 'users.edit-profile'
        ]);


        Route::get('/change-password', [
            'uses' => 'UsersController@changePassword',
            'as' => 'users.change-password'
        ]);


        Route::post('/change-password', [
            'uses' => 'UsersController@processChangePassword',
            'as' => 'users.change-password'
        ]);
    });
}
);
Route::get('/login', [
    'uses' => 'Auth\LoginController@showLoginForm',
    'as' => 'login'
]);

Route::post('/login', [
    'uses' => 'Auth\LoginController@login',
    'as' => 'login'
]);


Route::any('/logout', [
    'uses' => 'Auth\LoginController@logout',
    'as' => 'logout'
]);

/*        Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::post('/password/reset', 'Auth\ResetPasswordController@reset');
        Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
        Route::get('reset-password', 'auth\ResetPasswordController@reset_password_from_show')->name('reset.password.show');
        Route::post('reset-password-update', 'auth\Resetpasswordcontroller@reset_password_update')->name('reset.password.update');*/




###Reset Password###
Route::post('/password/email', 'Auth\ForgotPasswordController@send_reset_link_email')->name('password.email');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset_password_update')->name('reset.password.update');
//Route::post('/password/reset', 'Auth\ResetPasswordController@reset_password_update');
Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');

Route::get('/password/reset/{email}/{token}/{role_type}', 'Auth\ResetPasswordController@reset_password_from_show')->name('password.reset');

//Route::any('micro-hub/logout', 'Auth\LoginController@microHubLogout')->name('micro-hub.logout');
Route::group(['prefix' => 'micro-hub'], function(){

    Route::group(['middleware' => 'backendAuthenticate'], function () {

        Route::get('joeys/statistics', 'JoeyController@statistics')->name('micro-hub.joeys.statistics');

		//Use For Micro Hub User Request
        Route::get('joey/newMicrohubRequestTable', 'JoeyController@newMicrohubRequestData')->name('joeys.newMicrohubRequestTable');
        Route::get('joey/newActiveMicrohubTable', 'JoeyController@newActiveMicrohubData')->name('joeys.newActiveMicrohubTable');
        Route::get('joey/approvedMicrohubTable', 'JoeyController@approvedMicrohubData')->name('joeys.approvedMicrohubTable');
        Route::get('joey/declinedMicrohubTable', 'JoeyController@declinedMicrohubTableData')->name('joeys.declinedMicrohubTable');
        Route::get('micro-hub-data', 'JoeyController@microHubData')->name('micro-hub-data');
        Route::get('joey/basicRegistrationTable', 'JoeyController@basicRegistration')->name('joeys.basicRegistration');
        Route::get('joey/docSubmissionTable', 'JoeyController@docSubmissionTable')->name('joeys.docSubmission');
        Route::get('joey/totalApplicationSubmissionTable', 'JoeyController@totalApplicationSubmissionTable')->name('joeys.totalApplicationSubmissionTable');
        Route::get('joey/totalTrainingwatchedTable', 'JoeyController@totalTrainingwatchedTable')->name('joeys.totalTrainingwatchedTable');
        Route::get('joey/totalQuizPassedTable', 'JoeyController@totalQuizPassedTable')->name('joeys.totalQuizPassedTable');
        // after login routes
//        Route::group(['middleware' => 'PermissionHandler'], function () {

            ### role management routes ###
            //Role Index
            Route::get('role', 'RoleController@microHubIndex')->name('micro-hub.role.index');
            //Role Create
            Route::get('role/create', 'RoleController@microHubCreate')->name('micro-hub.role.create');
            Route::post('role/store', 'RoleController@microHubStore')->name('micro-hub.role.store');
            //Role Update
            Route::get('role/edit/{role}', 'RoleController@microHubEdit')->name('micro-hub.role.edit');
            Route::post('role/update/{role}', 'RoleController@microHubUpdate')->name('micro-hub.role.update');
            //Role detail
            Route::get('role/{role}', 'RoleController@microHubShow')->name('micro-hub.role.show');
            //Role Set Permissions
            Route::get('role/set-permissions/{role}', 'RoleController@setMicroHubPermissions')->name('micro-hub.role.set-permissions');
            Route::post('role/set-permissions/update/{role}', 'RoleController@setMicroHubPermissionsUpdate')->name('micro-hub.role.set-permissions.update');


            ### Sub Admins management routes ###
            Route::get('sub-admin/data', 'SubAdminController@microHubData')->name('micro-hub.sub-admin.data');
            Route::get('sub-admin', 'SubAdminController@microHubIndex')->name('micro-hub.sub-admin.index');
            //Sub Admins Create
            Route::get('sub-admin/create', 'SubAdminController@microHubCreate')->name('micro-hub.sub-admin.create');
            Route::post('sub-admin/store', 'SubAdminController@microHubStore')->name('micro-hub.sub-admin.store');
            //Sub Admins Update
            Route::get('sub-admin/edit/{sub_admin}', 'SubAdminController@microHubEdit')->name('micro-hub.sub-admin.edit');
            Route::post('sub-admin/update/{sub_admin}', 'SubAdminController@microHubUpdate')->name('micro-hub.sub-admin.update');
            //Sub Admin detail
            Route::get('sub-admin/{record}', 'SubAdminController@microHubShow')->name('micro-hub.sub-admin.show');
            //Sub Admin Active / Inactive
            Route::get('sub-admin/active/{record}', 'SubAdminController@microHubActive')->name('micro-hub.sub-admin.active');
            Route::get('sub-admin/inactive/{record}', 'SubAdminController@microHubInactive')->name('micro-hub.sub-admin.inactive');
            //Sub Admin Delete
            Route::delete('sub-admin/{sub_admin}', 'SubAdminController@microHubDestroy')->name('micro-hub.sub-admin.delete');

            ### Micro hub User Requests List management routes ###
            Route::get('user/data', 'MicroHubUserListController@data')->name('micro-hub.users.data');
            Route::get('users', 'MicroHubUserListController@index')->name('micro-hub.users.index');
            Route::get('statusUpdate', 'MicroHubUserListController@statusUpdate')->name('micro-hub.users.statusUpdate');

			 ###Micro Hub Assign###
            Route::get('hub-assign', 'MicroHubAssignController@index')->name('micro-hub-assign.index');
            Route::get('hub-assign/data', 'MicroHubAssignController@data')->name('micro-hub-assign.data');
            Route::get('/hub-assign-edit/{id}', 'MicroHubAssignController@microHubAssignEdit')->name('micro-hub-assign.edit');
            Route::post('hub-assign-update/{id}', 'MicroHubAssignController@microHubAssignUpdate')->name('micro-hub-assign.update');
            Route::get('city-hub-assign', 'MicroHubAssignController@microCityHubAssignUpdate')->name('city-hub-assign.update');

            ### Micro Hub Approved User List routes ###
            Route::get('approved/data', 'MicroHubListController@data')->name('micro-hub.approved.data');
            Route::get('approved', 'MicroHubListController@index')->name('micro-hub.approved.index');
            //Micro Hub Permission Update
            Route::post('hub-permission/update', 'MicroHubListController@hubPermissionUpdate')->name('micro-hub.HubPermission.update');

            ### Approved User List Postal Create routes ###
            Route::post('postal-code/add', 'MicroHubListController@createPostalCode')->name('postal-code.create');
            Route::post('postal-code-create-model', 'MicroHubListController@postalCodeCreateModelHtmlRender')->name('postal-code-create-model-html-render');

            ### Approved User List Zone Create routes ###
            Route::post('zone-create', 'MicroHubListController@createZone')->name('zone.create');
            Route::post('zone-create-model', 'MicroHubListController@zoneCreateModelHtmlRender')->name('zone-create-model-html-render');

            ### Micro Hub Not Approved User List routes ###
            Route::get('not-approved/data', 'MicroHubListController@notApprovedData')->name('micro-hub.notApproved.data');
            Route::get('not-approved/user-list', 'MicroHubListController@notApprovedList')->name('micro-hub.notApproved.index');

            ### Micro Hub Document Approved List routes ###
            Route::get('document-approved/data', 'MicroHubListController@documentApprovedData')->name('micro-hub.documentApproved.data');
            Route::get('document-approved', 'MicroHubListController@documentApprovedIndex')->name('micro-hub.documentApproved.index');
            ### Micro Hub Document Not Approved List routes ###
            Route::get('documentNot-approved/data', 'MicroHubListController@documentNotApprovedData')->name('micro-hub.documentNotApproved.data');
            Route::get('documentNot-approved', 'MicroHubListController@documentNotApprovedIndex')->name('micro-hub.documentNotApproved.index');

            ### Micro Hub Not trained User List routes ###
            Route::get('not-trained/data', 'MicroHubListController@notTrainedData')->name('micro-hub.notTrained.data');
            Route::get('not-trained/user-list', 'MicroHubListController@notTrainedList')->name('micro-hub.notTrained.index');

            ### Micro Hub Quiz Pending User List routes ###
            Route::get('quiz-pending/data', 'MicroHubListController@quizPendingData')->name('micro-hub.quizPending.data');
            Route::get('quiz-pending/user-list', 'MicroHubListController@quizPendingList')->name('micro-hub.quizPending.index');
            ### Micro Hub Quiz Passed User List routes ###
            Route::get('quiz-passed/data', 'MicroHubListController@quizPassedData')->name('micro-hub.quizPassed.data');
            Route::get('quiz-passed/user-list', 'MicroHubListController@quizPassedList')->name('micro-hub.quizPassed.index');

            ### Micro Hub Document Not Uploaded User List routes ###
            Route::get('users-list/documentNotUploadedData', 'MicroHubListController@documentNotUploadedData')->name('micro-hub.documentNotUploadedData.data');
            Route::get('users-list/documentNotUploaded', 'MicroHubListController@documentNotUploaded')->name('micro-hub.documentNotUploaded.index');

            ### Micro Hub Document Verification User List routes ###
            Route::get('document-verificationData/users-list', 'MicroHubDocumentVerificationController@documentVerificationData')->name('micro-hub.documentVerificationData.data');
            Route::get('document-verification/users-list', 'MicroHubDocumentVerificationController@documentVerification')->name('micro-hub.documentVerificationData.index');
            Route::get('document-verification/status/update/statusUpdate', 'MicroHubDocumentVerificationController@statusUpdate')->name('micro-hub.document-verification.statusUpdate');
            Route::get('document-verification/{record}', 'MicroHubDocumentVerificationController@show')->name('micro-hub.documentVerificationData.show');
            Route::get('document-verification/edit/{record}', 'MicroHubDocumentVerificationController@documentEdit')->name('micro-hub.documentVerificationData.edit');
            Route::post('document-verification/update/{record}', 'MicroHubDocumentVerificationController@documentUpdate')->name('micro-hub.documentVerificationData.update');
            ### Micro Hub Documents ###
            Route::get('document-type/data', 'MicroHubDocumentsController@documentData')->name('micro-hub.documentList.data');
            Route::get('document-type/list', 'MicroHubDocumentsController@documentList')->name('micro-hub.documentList.index');
            ### Document Type Create ###
            Route::get('document-type/create', 'MicroHubDocumentsController@documentCreate')->name('micro-hub.documentList.create');
            Route::post('document-type/store', 'MicroHubDocumentsController@documentStore')->name('micro-hub.documentList.store');
            ### Document Type Edit ###
            Route::get('document-type/edit/{document}', 'MicroHubDocumentsController@documentEdit')->name('micro-hub.documentList.edit');
            Route::post('document-type/update/{document}', 'MicroHubDocumentsController@documentUpdate')->name('micro-hub.documentList.update');
            //Document Type Delete
            Route::delete('document-type/{document}', 'MicroHubDocumentsController@documentTypeDestroy')->name('micro-hub.documentList.destroy');
            //Training Routes
            Route::get('training-list/data', 'MicroHubTrainingController@data')->name('micro-hub.training.data');
            Route::resource('training-list', 'MicroHubTrainingController');
            //Order Category Routes
            Route::get('order-category-list/data', 'MicroHubOrderCategoryController@data')->name('order-category-list.data');
            Route::resource('order-category-list', 'MicroHubOrderCategoryController');

            Route::get('quiz-management-list/data', 'MicroHubQuizController@data')->name('quiz-management-list.data');
            Route::resource('quiz-management-list', 'MicroHubQuizController');

            ###Micro-hub Quiz attempt ###
            Route::get('quiz-attempt/data', 'MicroHubAttemptQuizController@data')->name('quiz-attempt.data');
            Route::resource('quiz-attempt', 'MicroHubAttemptQuizController');

            //Profile Status Update
            Route::get('profile-status/edit/{hub_user}', 'MicroHubUserListController@profileStatusEdit')->name('micro-hub.profile-status.edit');
            Route::post('profile-status/update', 'MicroHubUserListController@profileStatusUpdate')->name('micro-hub.profile-status.update');

            //update micro hub data
            Route::get('hub-profile/edit/{id}', 'MicroHubUserListController@hubProfileEdit')->name('micro-hub.hubProfileEdit.edit');

            ### Edit Profile management routes ###
            Route::get('/edit-profile', 'UsersController@microHubEditProfile')->name('micro-hub.users.edit-profile');
            Route::post('/edit-profile', 'UsersController@microHubUpdateEditProfile')->name('micro-hub.users.edit-profile');

            Route::get('/change-password', 'UsersController@microHubChangePassword')->name('micro-hub.users.change-password');
            Route::post('/change-password', 'UsersController@microHubProcessChangePassword')->name('micro-hub.users.change-password');


//        });
    });
    // before login routes
    //Route::get('login', static function(){dd('Reset Cache'); })->name('micro-hub.login');
    Route::get('login','Auth\MicroHubLoginController@showMicroHubLoginForm')->name('micro-hub.login');
    Route::post('login','Auth\MicroHubLoginController@microHubLogin')->name('micro-login');

    Route::get( 'type-auth','Auth\MicroHubLoginController@getMicroHubType');
    Route::post('type/auth','Auth\MicroHubLoginController@postMicroHubTypeauth');
    Route::get('verify-code','Auth\MicroHubLoginController@getMicroHubVerifyCode');
    Route::post('verify/code','Auth\MicroHubLoginController@postMicroHubVerifyCode');

    Route::any('logout', 'Auth\MicroHubLoginController@microHubLogout')->name('micro-hub.logout');


});
Route::get('/micro-hub/password/reset', 'Auth\ForgotPasswordController@showMicroHubLinkRequestForm')->name('micro.password.request');
Route::post('/micro-hub/password/email', 'Auth\ForgotPasswordController@microHubResetLinkEmail')->name('micro.password.email');





//}
//);
