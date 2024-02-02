<?php

/**
 * routes/breadcrumbs.php
 *
 * @author Muzafar Ali Jatoi <muzfr7@gmail.com>
 * @Date: 19/9/18
 */

/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/

// Dashboard
Breadcrumbs::for('dashboard.index', function ($breadcrumbs) {
	$breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Setting Main Page', route('dashboard.index'));
});



/*
|--------------------------------------------------------------------------
| Role & Permissions
|--------------------------------------------------------------------------
*/


Breadcrumbs::for('role.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Role List', route('role.index'));
});

// Pages > New
Breadcrumbs::for('role.create', function ($breadcrumbs) {
    $breadcrumbs->parent('role.index');
    $breadcrumbs->push('Add', route('role.create'));
});

// Pages > Show
Breadcrumbs::for('role.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('role.index');
    $breadcrumbs->push('Show', route('role.show', $data->id));
});


// Pages > Edit
Breadcrumbs::for('role.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('role.index', $data);
    $breadcrumbs->push('Edit', route('role.edit', $data->id));
});

// Pages > Set Permissions
Breadcrumbs::for('role.set-permissions', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('role.index', $data);
    $breadcrumbs->push('Set Role Permissions', route('role.set-permissions', $data->id));
});


/*
|--------------------------------------------------------------------------
| Sub Admin
|--------------------------------------------------------------------------
*/


Breadcrumbs::for('sub-admin.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Sub Admin List', route('sub-admin.index'));
});

// Pages > New
Breadcrumbs::for('sub-admin.create', function ($breadcrumbs) {
    $breadcrumbs->parent('sub-admin.index');
    $breadcrumbs->push('Add', route('sub-admin.create'));
});

// Pages > Show
Breadcrumbs::for('sub-admin.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('sub-admin.index');
    $breadcrumbs->push('Show', route('sub-admin.show', $data->id));
});

// Pages > Edit
Breadcrumbs::for('sub-admin.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('sub-admin.index', $data);
    $breadcrumbs->push('Edit', route('sub-admin.edit', $data->id));
});



/*
|--------------------------------------------------------------------------
| Zones
|--------------------------------------------------------------------------
*/

// Zones  > Listing
Breadcrumbs::for('zones.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Zones List', route('zones.index'));
});

// Zones > New
Breadcrumbs::for('zones.create', function ($breadcrumbs) {
    $breadcrumbs->parent('zones.index');
    $breadcrumbs->push('Add', route('zones.create'));
});

// Zones > Show
Breadcrumbs::for('zones.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('zones.index');
    $breadcrumbs->push('Show', route('zones.show', $data->id));
});

// zones > Edit
Breadcrumbs::for('zones.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('zones.index', $data);
    $breadcrumbs->push('Edit', route('zones.edit', $data->id));
});




/*
|--------------------------------------------------------------------------
| Work Time
|--------------------------------------------------------------------------
*/

// Work Time  > Listing
Breadcrumbs::for('work-time.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Preferred Work Time List', route('work-time.index'));
});

// workTime > New
Breadcrumbs::for('work-time.create', function ($breadcrumbs) {
    $breadcrumbs->parent('work-time.index');
    $breadcrumbs->push('Add', route('work-time.create'));
});

// workTime > Show
Breadcrumbs::for('work-time.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('work-time.index');
    $breadcrumbs->push('Show', route('work-time.show', $data->id));
});

// workTime > Edit
Breadcrumbs::for('work-time.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('work-time.index', $data);
    $breadcrumbs->push('Edit', route('work-time.edit', $data->id));
});


/*
|--------------------------------------------------------------------------
| Work Type
|--------------------------------------------------------------------------
*/

// workType  > Listing
Breadcrumbs::for('work-type.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Preferred Work Type List', route('work-type.index'));
});

// workType > New
Breadcrumbs::for('work-type.create', function ($breadcrumbs) {
    $breadcrumbs->parent('work-type.index');
    $breadcrumbs->push('Add', route('work-type.create'));
});

// workType > Show
Breadcrumbs::for('work-type.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('work-type.index');
    $breadcrumbs->push('Show', route('work-type.show', $data->id));
});

// workType > Edit
Breadcrumbs::for('work-type.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('work-type.index',$data);
    $breadcrumbs->push('Edit', route('work-type.edit', $data->id));
});






/*
|--------------------------------------------------------------------------
| Joey Document verification
|--------------------------------------------------------------------------
*/

// Joey Document verification> Listing
Breadcrumbs::for('joey-document-verification.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Joey Document Verification List', route('joey-document-verification.index'));
});

Breadcrumbs::for('joey-document-verification.expiredDocument', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Joey Expired Document List', route('joey-document-verification.expiredDocument'));
});

Breadcrumbs::for('joey-document-verification.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('joey-document-verification.index');
    $breadcrumbs->push('Show', route('joey-document-verification.show', $data->id));
});

Breadcrumbs::for('joey-document-verification.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('joey-document-verification.index');
    $breadcrumbs->push('Edit', route('joey-document-verification.edit', $data->id));
});


/*
|--------------------------------------------------------------------------
|Training
|--------------------------------------------------------------------------
*/

// Training  > Listing
Breadcrumbs::for('training.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Training Videos and Documents list', route('training.index'));
});

// Training > New
Breadcrumbs::for('training.create', function ($breadcrumbs) {
    $breadcrumbs->parent('training.index');
    $breadcrumbs->push('Add', route('training.create'));
});

// Training > Edit
Breadcrumbs::for('training.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('training.index',$data);
    $breadcrumbs->push('Edit', route('training.edit', $data->id));
});


/*
|--------------------------------------------------------------------------
| Categores
|--------------------------------------------------------------------------
*/

// Categores  > Listing
Breadcrumbs::for('categores.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Categores List', route('categores.index'));
});

// Categores > New
Breadcrumbs::for('categores.create', function ($breadcrumbs) {
    $breadcrumbs->parent('categores.index');
    $breadcrumbs->push('Add', route('categores.create'));
});

// Categores > Show
Breadcrumbs::for('categores.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('categores.index');
    $breadcrumbs->push('Show', route('categores.show', $data->id));
});

// Categores > Edit
Breadcrumbs::for('categores.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('categores.index',$data);
    $breadcrumbs->push('Edit', route('categores.edit', $data->id));
});





/*
|--------------------------------------------------------------------------
| Quiz Management
|--------------------------------------------------------------------------
*/

// quizManagement  > Listing
Breadcrumbs::for('quiz-management.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Quiz Management List', route('quiz-management.index'));
});

// quizManagement > New
Breadcrumbs::for('quiz-management.create', function ($breadcrumbs) {
    $breadcrumbs->parent('quiz-management.index');
    $breadcrumbs->push('Add', route('quiz-management.create'));
});

// quizManagement > Show
Breadcrumbs::for('quiz-management.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('quiz-management.index');
    $breadcrumbs->push('Show', route('quiz-management.show', $data->id));
});

// quizManagement > Edit
Breadcrumbs::for('quiz-management.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('quiz-management.index',$data);
    $breadcrumbs->push('Edit', route('quiz-management.edit', $data));
});

/*
|--------------------------------------------------------------------------
| Vendors
|--------------------------------------------------------------------------
*/

// Vendors  > Listing
Breadcrumbs::for('vendors.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Vendors Order Count List', route('vendors.index'));
});

// Vendors > New
Breadcrumbs::for('vendors.create', function ($breadcrumbs) {
    $breadcrumbs->parent('vendors.index');
    $breadcrumbs->push('Add', route('vendors.create'));
});

// Vendors > Show
Breadcrumbs::for('vendors.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('vendors.index');
    $breadcrumbs->push('Show', route('vendors.show', $data->id));
});

// Vendors > Edit
Breadcrumbs::for('vendors.edit', function ($breadcrumbs, $data) {

    $breadcrumbs->parent('vendors.index',$data);

    $breadcrumbs->push('Edit', route('vendors.edit', $data->id));
});



/*
|--------------------------------------------------------------------------
| Joeys
|--------------------------------------------------------------------------
*/

// Statistics
Breadcrumbs::for('joeys.statistics', function ($breadcrumbs) {
    $breadcrumbs->push('Joeys Statistics', route('joeys.statistics'));
});


// Joeys  > Listing
Breadcrumbs::for('joeys-list.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Joeys List', route('joeys-list.index'));
});

// Joeys  > Agreement Not Signed
Breadcrumbs::for('joeys.agreementNotSigned', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Agreement Not Signed', route('joeys.agreementNotSigned'));
});

// New Signup Joeys
Breadcrumbs::for('newSignUpJoeys.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('New Signup Joeys List', route('newSignUpJoeys.index'));
});


// Joeyst> Edit
Breadcrumbs::for('joeys-list.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('joeys-list.index', $data);
    $breadcrumbs->push('Edit', route('joeys-list.edit', $data->id));
});
// Joeys document not uploaded
Breadcrumbs::for('joeys.documentNotUploaded', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys-list.index');
    $breadcrumbs->push('Docuement Not Uploaded Joeys List', route('joeys.documentNotUploaded'));
});
// Joeys document not approved
Breadcrumbs::for('joeys.documentNotApproved', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys-list.index');
    $breadcrumbs->push('Docuement Not Approved Joeys List', route('joeys.documentNotApproved'));
});
// Joeys document approved
Breadcrumbs::for('joeys.documentApproved', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys-list.index');
    $breadcrumbs->push('Docuements Approved Joeys List', route('joeys.documentApproved'));
});

// Joeys not trained
Breadcrumbs::for('joeys.notTrained', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys-list.index');
    $breadcrumbs->push('Joeys Not Trained List', route('joeys.notTrained'));
});

// Joeys quiz pending
Breadcrumbs::for('joeys.quizPending', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys-list.index');
    $breadcrumbs->push('Joeys Quiz Pending List', route('joeys.quizPending'));
});

// Joeys quiz pending
Breadcrumbs::for('joeys.quizPassed', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys-list.index');
    $breadcrumbs->push('Joeys Quiz Passed List', route('joeys.quizPassed'));
});


/*
|--------------------------------------------------------------------------
| Joey Checklist
|--------------------------------------------------------------------------
*/


Breadcrumbs::for('joey-checklist.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Joeys CheckList', route('joey-checklist.index'));
});

// Joey Checklist > New
Breadcrumbs::for('joey-checklist.create', function ($breadcrumbs) {
    $breadcrumbs->parent('joey-checklist.index');
    $breadcrumbs->push('Add', route('joey-checklist.create'));
});

// Joey Checklist> Show
Breadcrumbs::for('joey-checklist.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('joey-checklist.index');
    $breadcrumbs->push('Show', route('joey-checklist.show', $data->id));
});

// Joey Checklist> Edit
Breadcrumbs::for('joey-checklist.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('joey-checklist.index', $data);
    $breadcrumbs->push('Edit', route('joey-checklist.edit', $data->id));
});



/*
|--------------------------------------------------------------------------
| basic vendor
|--------------------------------------------------------------------------
*/


Breadcrumbs::for('basic-vendor.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Basic Vendor List', route('basic-vendor.index'));
});

// basic vendor> New
Breadcrumbs::for('basic-vendor.create', function ($breadcrumbs) {
    $breadcrumbs->parent('basic-vendor.index');
    $breadcrumbs->push('Add', route('basic-vendor.create'));
});



/*
|--------------------------------------------------------------------------
| basic category
|--------------------------------------------------------------------------
*/


Breadcrumbs::for('basic-category.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Basic Category List', route('basic-category.index'));

});

// basic vendor> New
Breadcrumbs::for('basic-category.create', function ($breadcrumbs) {
    $breadcrumbs->parent('basic-category.index');
    $breadcrumbs->push('Add', route('basic-category.create'));
});


/*
|--------------------------------------------------------------------------
| Vendor Score
|--------------------------------------------------------------------------
*/


Breadcrumbs::for('vendor-score.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Vendor Quizes Score List', route('vendor-score.index'));
});

//  Vendor Score> New
Breadcrumbs::for('vendor-score.create', function ($breadcrumbs) {
    $breadcrumbs->parent('vendor-score.index');
    $breadcrumbs->push('Add', route('vendor-score.create'));
});

//  Vendor Score> Show
Breadcrumbs::for('vendor-score.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('vendor-score.index');
    $breadcrumbs->push('Show', route('vendor-score.show', $data->id));
});

//  Vendor Score> Edit
Breadcrumbs::for('vendor-score.edit', function ($breadcrumbs, $data) {

    $breadcrumbs->parent('vendor-score.index', $data);
    $breadcrumbs->push('Edit', route('vendor-score.edit', $data->id));
});

/*
|--------------------------------------------------------------------------
| Category Score
|--------------------------------------------------------------------------
*/


Breadcrumbs::for('category-score.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Categories Quizes Score List', route('category-score.index'));
});

//  Vendor Score> New
Breadcrumbs::for('category-score.create', function ($breadcrumbs) {
    $breadcrumbs->parent('category-score.index');
    $breadcrumbs->push('Add', route('category-score.create'));
});

//  Vendor Score> Show
Breadcrumbs::for('category-score.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('category-score.index');
    $breadcrumbs->push('Show', route('category-score.show', $data->id));
});

//  Vendor Score> Edit
Breadcrumbs::for('category-score.edit', function ($breadcrumbs, $data) {

    $breadcrumbs->parent('category-score.index', $data);
    $breadcrumbs->push('Edit', route('category-score.edit', $data->id));
});



/*
|--------------------------------------------------------------------------
| Job Type
|--------------------------------------------------------------------------
*/


Breadcrumbs::for('job-type.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Job Type List', route('job-type.index'));
});

// Pages > New
Breadcrumbs::for('job-type.create', function ($breadcrumbs) {
    $breadcrumbs->parent('job-type.index');
    $breadcrumbs->push('Add', route('job-type.create'));
});

// Pages > Show
Breadcrumbs::for('job-type.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('job-type.index');
    $breadcrumbs->push('Show', route('job-type.show', $data->id));
});

// Pages > Edit
Breadcrumbs::for('job-type.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('job-type.index', $data);
    $breadcrumbs->push('Edit', route('job-type.edit', $data->id));
});


/*
|--------------------------------------------------------------------------
| Joey Mails
|--------------------------------------------------------------------------
*/

Breadcrumbs::for('notification.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Joey BroadCasting Notification', route('notification.index'));
});


/*
|--------------------------------------------------------------------------
| Order Category
|--------------------------------------------------------------------------
*/


Breadcrumbs::for('order-category.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Order Categories List', route('order-category.index'));
});

// Pages > New
Breadcrumbs::for('order-category.create', function ($breadcrumbs) {
    $breadcrumbs->parent('order-category.index');
    $breadcrumbs->push('Add', route('order-category.create'));
});

// Pages > Show
Breadcrumbs::for('order-category.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('order-category.index');
    $breadcrumbs->push('Show', route('order-category.show', $data->id));
});

// Pages > Edit
Breadcrumbs::for('order-category.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('order-category.index', $data);
    $breadcrumbs->push('Edit', route('order-category.edit', $data->id));
});




/*
|--------------------------------------------------------------------------
| Documents
|--------------------------------------------------------------------------
*/


Breadcrumbs::for('documents.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Documents List', route('documents.index'));
});

//  documents> New
Breadcrumbs::for('documents.create', function ($breadcrumbs) {
    $breadcrumbs->parent('documents.index');
    $breadcrumbs->push('Add', route('documents.create'));
});

//  documents> Show
Breadcrumbs::for('documents.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('documents.index');
    $breadcrumbs->push('Show', route('documents.show', $data->id));
});

//  documents> Edit
Breadcrumbs::for('documents.edit', function ($breadcrumbs, $data) {

    $breadcrumbs->parent('documents.index', $data);
    $breadcrumbs->push('Edit', route('documents.edit', $data->id));
});



/*
|--------------------------------------------------------------------------
| Site Settings
|--------------------------------------------------------------------------
*/

// Site Setting
Breadcrumbs::for('site-settings.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Site Settings', route('site-settings.index'));
});

/*
|--------------------------------------------------------------------------
| Change Password
|--------------------------------------------------------------------------
*/

// Change Password
Breadcrumbs::for('users.change-password', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Change Password', route('users.change-password'));
});

/*
|--------------------------------------------------------------------------
| Edit Profile
|--------------------------------------------------------------------------
*/

// Edit Profile
Breadcrumbs::for('users.profile', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Edit Profile', route('users.edit-profile'));
});






/*
|--------------------------------------------------------------------------
| Front
|--------------------------------------------------------------------------
*/

// Home
Breadcrumbs::for('home', function ($breadcrumbs) {
    $breadcrumbs->push('Home', route('index'));
});






/*
|--------------------------------------------------------------------------
| Faqs
|--------------------------------------------------------------------------
*/


Breadcrumbs::for('faqs.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('FAQ List', route('faqs.index'));
});

//  documents> New
Breadcrumbs::for('faqs.create', function ($breadcrumbs) {
    $breadcrumbs->parent('faqs.index');
    $breadcrumbs->push('Add', route('faqs.create'));
});

//  documents> Show
Breadcrumbs::for('faqs.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('faqs.index');
    $breadcrumbs->push('Show', route('faqs.show', $data->id));
});

//  documents> Edit
Breadcrumbs::for('faqs.edit', function ($breadcrumbs, $data) {

    $breadcrumbs->parent('faqs.index', $data);
    $breadcrumbs->push('Edit', route('faqs.edit', $data->id));
});





/*
|--------------------------------------------------------------------------
| Joey attempt quiz
|--------------------------------------------------------------------------
*/


Breadcrumbs::for('joey-attempt-quiz.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Joey Attempt Quiz List', route('joey-attempt-quiz.index'));
});

//  documents> Show
Breadcrumbs::for('joey-attempt-quiz.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('joey-attempt-quiz.index');
    $breadcrumbs->push('Show', route('joey-attempt-quiz.show', $data->id));
});

////  documents> New
//Breadcrumbs::for('faqs.create', function ($breadcrumbs) {
//    $breadcrumbs->parent('faqs.index');
//    $breadcrumbs->push('Add', route('faqs.create'));
//});
//

//
////  documents> Edit
//Breadcrumbs::for('faqs.edit', function ($breadcrumbs, $data) {
//
//    $breadcrumbs->parent('faqs.index', $data);
//    $breadcrumbs->push('Edit', route('faqs.edit', $data->id));
//});

/*
|--------------------------------------------------------------------------
|Customer send messages
|--------------------------------------------------------------------------
*/


Breadcrumbs::for('customer-send-messages.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Customer Send Messages List', route('customer-send-messages.index'));
});

//  Customer send messages> New
Breadcrumbs::for('customer-send-messages.create', function ($breadcrumbs) {
    $breadcrumbs->parent('customer-send-messages.index');
    $breadcrumbs->push('Add', route('customer-send-messages.create'));
});

//  Customer send messages> Show
Breadcrumbs::for('customer-send-messages.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('customer-send-messages.index');
    $breadcrumbs->push('Show', route('customer-send-messages.show', $data->id));
});

//  Customer send messages> Edit
Breadcrumbs::for('customer-send-messages.edit', function ($breadcrumbs, $data) {

    $breadcrumbs->parent('customer-send-messages.index', $data);
    $breadcrumbs->push('Edit', route('customer-send-messages.edit', $data->id));
});

/*
|--------------------------------------------------------------------------
| Customer Services
|--------------------------------------------------------------------------
*/


Breadcrumbs::for('customer-service.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Flag List', route('customer-service.index'));
});

//  Flag> Create
Breadcrumbs::for('customer-service.create', function ($breadcrumbs) {
    $breadcrumbs->parent('customer-service.index');
    $breadcrumbs->push('Add Flag', route('customer-service.create'));
});
//  Flag> Edit
Breadcrumbs::for('customer-service.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('customer-service.index', $data);
    $breadcrumbs->push('Edit', route('customer-service.edit', $data->id));
});
//  Flag> Detail
Breadcrumbs::for('customer-service.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('customer-service.index', $data);
    $breadcrumbs->push('Show', route('customer-service.show', $data->id));
});
//Flag Incident
Breadcrumbs::for('flag-incident.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Flag Suspension Policy List', route('flag-incident.index'));
});
//Flag Incident Create
Breadcrumbs::for('flag-incident.create', function ($breadcrumbs) {
    $breadcrumbs->parent('flag-incident.index');
    $breadcrumbs->push('Add', route('flag-incident.create'));
});
Breadcrumbs::for('flag-incident.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('flag-incident.index', $data);
    $breadcrumbs->push('Edit', route('flag-incident.edit', $data));
});




/*
|--------------------------------------------------------------------------
|Joeys Complaints
|--------------------------------------------------------------------------
*/
Breadcrumbs::for('joeys-complaints.index', function ($breadcrumbs) {
    $breadcrumbs->parent('joeys.statistics');
    $breadcrumbs->push('Joey Complaints List', route('joeys-complaints.index'));
});

//Micro Hub Breadcrums
// Statistics
Breadcrumbs::for('micro-hub.joeys.statistics', function ($breadcrumbs) {
    $breadcrumbs->push('Statistics', route('micro-hub.joeys.statistics'));
});

/*
|--------------------------------------------------------------------------
| Role & Permissions
|--------------------------------------------------------------------------
*/
Breadcrumbs::for('micro-hub.role.index', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('Role List', route('micro-hub.role.index'));
});
// Pages > New
Breadcrumbs::for('micro-hub.role.create', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.role.index');
    $breadcrumbs->push('Add', route('micro-hub.role.create'));
});
// Pages > Edit
Breadcrumbs::for('micro-hub.role.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('micro-hub.role.index', $data);
    $breadcrumbs->push('Edit', route('micro-hub.role.edit', $data->id));
});
// Pages > Set Permissions
Breadcrumbs::for('micro-hub.role.set-permissions', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('micro-hub.role.index', $data);
    $breadcrumbs->push('Set Role Permissions', route('micro-hub.role.set-permissions', $data->id));
});
// Pages > Show
Breadcrumbs::for('micro-hub.role.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('micro-hub.role.index');
    $breadcrumbs->push('Show', route('micro-hub.role.show', $data->id));
});

/*
|--------------------------------------------------------------------------
| Sub Admin
|--------------------------------------------------------------------------
*/
Breadcrumbs::for('micro-hub.sub-admin.index', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('Sub Admin List', route('micro-hub.sub-admin.index'));
});
// Pages > New
Breadcrumbs::for('micro-hub.sub-admin.create', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.sub-admin.index');
    $breadcrumbs->push('Add', route('micro-hub.sub-admin.create'));
});
// Pages > Edit
Breadcrumbs::for('micro-hub.sub-admin.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('micro-hub.sub-admin.index', $data);
    $breadcrumbs->push('Edit', route('micro-hub.sub-admin.edit', $data->id));
});
// Pages > Show
Breadcrumbs::for('micro-hub.sub-admin.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('micro-hub.sub-admin.index');
    $breadcrumbs->push('Show', route('micro-hub.sub-admin.show', $data->id));
});

/*
|--------------------------------------------------------------------------
| Micro Hub Users List
|--------------------------------------------------------------------------
*/
Breadcrumbs::for('micro-hub.users.index', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('MicroHub Users List', route('micro-hub.users.index'));
});
//Hub Profile Status Update
Breadcrumbs::for('micro-hub.profile-status.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('micro-hub.users.index', $data);
    $breadcrumbs->push('Profile Status Update', route('micro-hub.profile-status.edit', $data));
});



//Approved
Breadcrumbs::for('micro-hub.microHubUsers.approved.index', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('Approved User List', route('micro-hub.approved.index'));
});
//Not Approved
Breadcrumbs::for('micro-hub.notApproved.index', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('Not Approved User List', route('micro-hub.notApproved.index'));
});
// Document Approved
Breadcrumbs::for('micro-hub.documentApproved.index', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('Document Approved User List', route('micro-hub.documentApproved.index'));
});
// Document Not Approved
Breadcrumbs::for('micro-hub.documentNotApproved.index', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('Document Not Approved User List', route('micro-hub.documentNotApproved.index'));
});
//Document Not Uploaded
Breadcrumbs::for('micro-hub.documentNotUploaded.index', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('Document Not Uploaded User List', route('micro-hub.documentNotUploaded.index'));
});
//Not Trained
Breadcrumbs::for('micro-hub.notTrained.index', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('Not Trained User List', route('micro-hub.notTrained.index'));
});
//Quiz Pending
Breadcrumbs::for('micro-hub.quizPending.index', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('Quiz Pending User List', route('micro-hub.quizPending.index'));
});
//Quiz Passed
Breadcrumbs::for('micro-hub.quizPassed.index', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('Quiz Passed User List', route('micro-hub.quizPassed.index'));
});

//Document List
Breadcrumbs::for('micro-hub.documentList.index', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('Document List', route('micro-hub.documentList.index'));
});
//  documents> New
Breadcrumbs::for('micro-hub.documentList.create', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.documentList.index');
    $breadcrumbs->push('Add', route('micro-hub.documentList.create'));
});
//  documents> Edit
Breadcrumbs::for('micro-hub.documentList.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('documents.index', $data);
    $breadcrumbs->push('Edit', route('micro-hub.documentList.edit', $data));
});

//Document Verification
Breadcrumbs::for('micro-hub.documentVerificationData.index', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('Document Verification', route('micro-hub.documentVerificationData.index'));
});
// Pages > Show
Breadcrumbs::for('micro-hub.documentVerificationData.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('micro-hub.documentVerificationData.index');
    $breadcrumbs->push('Show', route('micro-hub.documentVerificationData.show', $data));
});
// Pages > Edit
Breadcrumbs::for('micro-hub.documentVerificationData.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('micro-hub.documentVerificationData.index');
    $breadcrumbs->push('Edit', route('micro-hub.documentVerificationData.edit', $data));
});


/**
 * Training
 */
// Training  > Listing
Breadcrumbs::for('training-list.index', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('Training Videos and Documents list', route('training-list.index'));
});
// Training > New
Breadcrumbs::for('training-list.create', function ($breadcrumbs) {
    $breadcrumbs->parent('training-list.index');
    $breadcrumbs->push('Add', route('training-list.create'));
});
// Training > Edit
Breadcrumbs::for('training-list.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('training-list.index',$data);
    $breadcrumbs->push('Edit', route('training-list.edit', $data->id));
});

/**
 *  Order Category
 */
Breadcrumbs::for('order-category-list.index', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('Order Categories List', route('order-category-list.index'));
});
// Pages > New
Breadcrumbs::for('order-category-list.create', function ($breadcrumbs) {
    $breadcrumbs->parent('order-category-list.index');
    $breadcrumbs->push('Add', route('order-category-list.create'));
});
// Pages > Show
Breadcrumbs::for('order-category-list.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('order-category-list.index');
    $breadcrumbs->push('Show', route('order-category-list.show', $data->id));
});
// Pages > Edit
Breadcrumbs::for('order-category-list.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('order-category-list.index', $data);
    $breadcrumbs->push('Edit', route('order-category-list.edit', $data->id));
});

/**
 * Quiz
 *
 */
// quizManagement  > Listing
Breadcrumbs::for('quiz-management-list.index', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('Quiz Management List', route('quiz-management-list.index'));
});
// quizManagement > New
Breadcrumbs::for('quiz-management-list.create', function ($breadcrumbs) {
    $breadcrumbs->parent('quiz-management-list.index');
    $breadcrumbs->push('Add', route('quiz-management-list.create'));
});
// quizManagement > Show
Breadcrumbs::for('quiz-management-list.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('quiz-management-list.index');
    $breadcrumbs->push('Show', route('quiz-management-list.show', $data));
});
// quizManagement > Edit
Breadcrumbs::for('quiz-management-list.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('quiz-management-list.index',$data);
    $breadcrumbs->push('Edit', route('quiz-management-list.edit', $data));
});


/*
|--------------------------------------------------------------------------
| Micro Hub Assign
|--------------------------------------------------------------------------
*/
Breadcrumbs::for('micro-hub-assign.index', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('Micro Hub User List', route('micro-hub-assign.index'));
});
// Micro Hub Assign > Edit
Breadcrumbs::for('micro-hub-assign.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('micro-hub-assign.index',$data);
    $breadcrumbs->push('Hub Assign', route('micro-hub-assign.edit', $data->id));
});

/*
|--------------------------------------------------------------------------
| attempt quiz
|--------------------------------------------------------------------------
*/


Breadcrumbs::for('quiz-attempt.index', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('Attempt Quiz List', route('quiz-attempt.index'));
});

//  documents> Show
Breadcrumbs::for('quiz-attempt.show', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('quiz-attempt.index');
    $breadcrumbs->push('Show', route('quiz-attempt.show', $data));
});

/*
|--------------------------------------------------------------------------
| Edit Profile
|--------------------------------------------------------------------------
*/
// Edit Profile
Breadcrumbs::for('micro-hub.users.edit-profile', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('Edit Profile', route('micro-hub.users.edit-profile'));
});

/*
|--------------------------------------------------------------------------
| Change Password
|--------------------------------------------------------------------------
*/
// Change Password
Breadcrumbs::for('micro-hub.users.change-password', function ($breadcrumbs) {
    $breadcrumbs->parent('micro-hub.joeys.statistics');
    $breadcrumbs->push('Change Password', route('micro-hub.users.change-password'));
});
