<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class ModelServiceProvider
 *
 * @author Ghulam Mustafa <ghulam.mustafa@vservices.com>
 * @date   29/11/18
 */
class ModelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    /**
     * Bind the interface to an implementation model class
     */
    public function register()
    {
        $this->app->bind('App\Models\Interfaces\UserInterface', 'App\Models\User');
        $this->app->bind('App\Models\Interfaces\SiteSettingInterface', 'App\Models\SiteSetting');
        $this->app->bind('App\Models\Interfaces\OrderCategoryInterface', 'App\Models\OrderCategory');
        $this->app->bind('App\Models\Interfaces\JobTypeInterface', 'App\Models\JobType');
        $this->app->bind('App\Models\Interfaces\ZoneInterface', 'App\Models\Zone');
        $this->app->bind('App\Models\Interfaces\WorkTimeInterface', 'App\Models\WorkTime');
        $this->app->bind('App\Models\Interfaces\WorkTypeInterface', 'App\Models\WorkType');
        $this->app->bind('App\Models\Interfaces\SiteSettingInterface', 'App\Models\SiteSetting');
        $this->app->bind('App\Models\Interfaces\JoeyDocumentVerificationInterface', 'App\Models\JoeyDocumentVerification');
        $this->app->bind('App\Models\Interfaces\TrainingInterface', 'App\Models\Training');
        $this->app->bind('App\Models\Interfaces\CategoresInterface', 'App\Models\Categores');
        $this->app->bind('App\Models\Interfaces\CategoriesInterface', 'App\Models\Categories');
        $this->app->bind('App\Models\Interfaces\QuizQuestionInterface', 'App\Models\QuizQuestion');
        $this->app->bind('App\Models\Interfaces\QuizAnswerInterface', 'App\Models\QuizAnswer');
        $this->app->bind('App\Models\Interfaces\VendorInterface', 'App\Models\Vendor');
        $this->app->bind('App\Models\Interfaces\VendorsInterface', 'App\Models\Vendors');
        $this->app->bind('App\Models\Interfaces\JoeyChecklistInterface', 'App\Models\JoeyChecklist');
        $this->app->bind('App\Models\Interfaces\BasicVendorInterface', 'App\Models\BasicVendor');
        $this->app->bind('App\Models\Interfaces\BasicCategoryInterface', 'App\Models\BasicCategory');
        $this->app->bind('App\Models\Interfaces\RoleInterface', 'App\Models\Roles');
        $this->app->bind('App\Models\Interfaces\PermissionsInterface', 'App\Models\Permissions');
        $this->app->bind('App\Models\Interfaces\JoeyDocumentInterface', 'App\Models\JoeyDocument');
        $this->app->bind('App\Models\Interfaces\DocumentsInterface', 'App\Models\Documents');
        $this->app->bind('App\Models\Interfaces\FaqsInterface', 'App\Models\Faqs');
        $this->app->bind('App\Models\Interfaces\JoeyAttemptQuizInterface', 'App\Models\JoeyAttemptQuiz');
        $this->app->bind('App\Models\Interfaces\JoeyQuizInterface', 'App\Models\JoeyQuiz');
        $this->app->bind('App\Models\Interfaces\CustomerSendMessagesInterface', 'App\Models\CustomerSendMessages');
        $this->app->bind('App\Models\Interfaces\JoeyTrainingSeenInterface', 'App\Models\JoeyTrainingSeen');
        $this->app->bind('App\Models\Interfaces\VehicleInterface', 'App\Models\Vehicle');
        $this->app->bind('App\Models\Interfaces\JoeyPlansInterface', 'App\Models\JoeyPlans');
        $this->app->bind('App\Models\Interfaces\HubsInterface', 'App\Models\Hubs');
        $this->app->bind('App\Models\Interfaces\JoeyDepositInterface', 'App\Models\JoeyDeposit');
		$this->app->bind('App\Models\Interfaces\JoeyComplaintsInterface', 'App\Models\JoeyComplaints');
        $this->app->bind('App\Models\Interfaces\CustomerFlagCategoryValuesInterface', 'App\Models\CustomerFlagCategoryValues');
        $this->app->bind('App\Models\Interfaces\FlagCategoryMetaDataInterface', 'App\Models\FlagCategoryMetaData');
        $this->app->bind('App\Models\Interfaces\CustomerFlagCategoryInterface', 'App\Models\CustomerFlagCategories');
        $this->app->bind('App\Models\Interfaces\FlagOrderTypeInterface', 'App\Models\FlagOrderType');
        $this->app->bind('App\Models\Interfaces\CustomerIncidentsInterface', 'App\Models\CustomerIncidents');

        //For Micro Hub
        $this->app->bind('App\Models\Interfaces\MicroHubRequestInterface', 'App\Models\MicroHubRequest');
        $this->app->bind('App\Models\Interfaces\JoeycoUsersInterface', 'App\Models\JoeycoUsers');
        $this->app->bind('App\Models\Interfaces\CitiesInterface', 'App\Models\Cities');
        $this->app->bind('App\Models\Interfaces\LocationsInterface', 'App\Models\Locations');
        $this->app->bind('App\Models\Interfaces\DeliveryProcessTypeInterface', 'App\Models\DeliveryProcessType');
        $this->app->bind('App\Models\Interfaces\HubProcessInterface', 'App\Models\HubProcess');
        $this->app->bind('App\Models\Interfaces\DashboardUsersInterface', 'App\Models\DashboardUsers');
        $this->app->bind('App\Models\Interfaces\HubPostalCodeInterface', 'App\Models\HubPostalCode');
        $this->app->bind('App\Models\Interfaces\ZoneTypesInterface', 'App\Models\ZoneTypes');
        $this->app->bind('App\Models\Interfaces\ZonesRoutingInterface', 'App\Models\ZonesRouting');
        $this->app->bind('App\Models\Interfaces\SlotPostalCodeInterface', 'App\Models\SlotPostalCode');
        $this->app->bind('App\Models\Interfaces\MicroHubUserTrainingSeenInterface', 'App\Models\MicroHubUserTrainingSeen');
        $this->app->bind('App\Models\Interfaces\MicroHubUserQuizPendingInterface', 'App\Models\MicroHubUserQuizPending');
        $this->app->bind('App\Models\Interfaces\MicroHubUserDocumentInterface', 'App\Models\MicroHubUserDocument');
        $this->app->bind('App\Models\Interfaces\MicroHubQuizInterface', 'App\Models\MicroHubQuiz');
        $this->app->bind('App\Models\Interfaces\MicroHubJoeyAssignInterface', 'App\Models\MicroHubJoeyAssign');
        $this->app->bind('App\Models\Interfaces\MicroHubPermissionInterface', 'App\Models\MicroHubPermission');
        $this->app->bind('App\Models\Interfaces\VehicleDetailInterface', 'App\Models\VehicleDetail');
		$this->app->bind('App\Models\Interfaces\HubsAssignInterface', 'App\Models\HubsAssign');
		
		$this->app->bind('App\Models\Interfaces\MiJobsInterface', 'App\Models\MiJobs');
		$this->app->bind('App\Models\Interfaces\MiJobsAssignInterface', 'App\Models\MiJobsAssign');
        $this->app->bind('App\Models\Interfaces\LocationInterface', 'App\Models\Location');

    }
}
