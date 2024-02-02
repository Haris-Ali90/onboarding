<?php

namespace App\Providers;

use App\Repositories\Interfaces\PropertyRepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 *
 * @author Ghulam Mustafa <ghulam.mustafa@vservices.com>
 * @date   29/11/18
 */
class RepositoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    /**
     * Bind the interface to an implementation repository class
     */
    public function register()
    {
        $this->app->bind('App\Repositories\Interfaces\AdminRepositoryInterface', 'App\Repositories\AdminRepository');
        $this->app->bind('App\Repositories\Interfaces\SiteSettingRepositoryInterface', 'App\Repositories\SiteSettingRepository');
        $this->app->bind('App\Repositories\Interfaces\UserRepositoryInterface', 'App\Repositories\UserRepository');
        $this->app->bind('App\Repositories\Interfaces\JobTypeRepositoryInterface', 'App\Repositories\JobTypeRepository');
        $this->app->bind('App\Repositories\Interfaces\OrderCategoryRepositoryInterface', 'App\Repositories\OrderCategoryRepository');
        $this->app->bind('App\Repositories\Interfaces\ZoneRepositoryInterface', 'App\Repositories\ZoneRepository');
        $this->app->bind('App\Repositories\Interfaces\WorkTimeRepositoryInterface', 'App\Repositories\WorkTimeRepository');
        $this->app->bind('App\Repositories\Interfaces\WorkTypeRepositoryInterface', 'App\Repositories\WorkTypeRepository');
        $this->app->bind('App\Repositories\Interfaces\JoeyDocumentVerificationRepositoryInterface', 'App\Repositories\JoeyDocumentVerificationRepository');
        $this->app->bind('App\Repositories\Interfaces\TrainingRepositoryInterface', 'App\Repositories\TrainingRepository');
        $this->app->bind('App\Repositories\Interfaces\CategoresRepositoryInterface', 'App\Repositories\CategoresRepository');
        $this->app->bind('App\Repositories\Interfaces\QuizQuestionRepositoryInterface', 'App\Repositories\QuizQuestionRepository');
        $this->app->bind('App\Repositories\Interfaces\QuizAnswerRepositoryInterface', 'App\Repositories\QuizAnswerRepository');
        $this->app->bind('App\Repositories\Interfaces\VendorsRepositoryInterface', 'App\Repositories\VendorsRepository');
        $this->app->bind('App\Repositories\Interfaces\JoeyChecklistRepositoryInterface', 'App\Repositories\JoeyChecklistRepository');
        $this->app->bind('App\Repositories\Interfaces\BasicVendorRepositoryInterface', 'App\Repositories\BasicVendorRepository');
        $this->app->bind('App\Repositories\Interfaces\BasicCategoryRepositoryInterface', 'App\Repositories\BasicCategoryRepository');
        $this->app->bind('App\Repositories\Interfaces\VendorRepositoryInterface', 'App\Repositories\VendorRepository');
        $this->app->bind('App\Repositories\Interfaces\CategoriesRepositoryInterface', 'App\Repositories\CategoriesRepository');
        $this->app->bind('App\Repositories\Interfaces\RoleRepositoryInterface','App\Repositories\RolesRepository');
        $this->app->bind('App\Repositories\Interfaces\DocumentsRepositoryInterface','App\Repositories\DocumentsRepository');
        $this->app->bind('App\Repositories\Interfaces\FaqsRepositoryInterface','App\Repositories\FaqsRepository');
        $this->app->bind('App\Repositories\Interfaces\CustomerSendMessagesRepositoryInterface','App\Repositories\CustomerSendMessagesRepository');
        $this->app->bind('App\Repositories\Interfaces\CustomerIncidentRepositoryInterface','App\Repositories\CustomerIncidentRepository');
        $this->app->bind('App\Repositories\Interfaces\CustomerFlagCategoryRepositoryInterface','App\Repositories\CustomerFlagCategoryRepository');
        $this->app->bind('App\Repositories\Interfaces\FlagCategoryMetaDataRepositoryInterface','App\Repositories\FlagCategoryMetaDataRepository');
        $this->app->bind('App\Repositories\Interfaces\CustomerFlagCategoryValuesRepositoryInterface','App\Repositories\CustomerFlagCategoryValuesRepository');
        $this->app->bind('App\Repositories\Interfaces\FlagOrderTypeRepositoryInterface','App\Repositories\FlagOrderTypeRepository');

        //For Micro Hub
        $this->app->bind('App\Repositories\Interfaces\MicroHubPermissionRepositoryInterface','App\Repositories\MicroHubPermissionRepository');
        $this->app->bind('App\Repositories\Interfaces\MicroHubRequestRepositoryInterface','App\Repositories\MicroHubRequestRepository');
        $this->app->bind('App\Repositories\Interfaces\JoeycoUsersRepositoryInterface','App\Repositories\JoeycoUsersRepository');
        $this->app->bind('App\Repositories\Interfaces\HubRepositoryInterface','App\Repositories\HubRepository');
        $this->app->bind('App\Repositories\Interfaces\HubProcessRepositoryInterface','App\Repositories\HubProcessRepository');
        $this->app->bind('App\Repositories\Interfaces\DashboardUsersRepositoryInterface','App\Repositories\DashboardUsersRepository');
        $this->app->bind('App\Repositories\Interfaces\HubPostalCodeRepositoryInterface','App\Repositories\HubPostalCodeRepository');
        $this->app->bind('App\Repositories\Interfaces\ZonesRoutingRepositoryInterface','App\Repositories\ZonesRoutingRepository');
        $this->app->bind('App\Repositories\Interfaces\SlotPostalCodeRepositoryInterface','App\Repositories\SlotPostalCodeRepository');
        $this->app->bind('App\Repositories\Interfaces\MicroHubJoeyAssignRepositoryInterface','App\Repositories\MicroHubJoeyAssignRepository');
        $this->app->bind('App\Repositories\Interfaces\VehicleDetailRepositoryInterface','App\Repositories\VehicleDetailRepository');
		$this->app->bind('App\Repositories\Interfaces\HubAssignRepositoryInterface','App\Repositories\HubAssignRepository');
		
		$this->app->bind('App\Repositories\Interfaces\MiAssignJobsRepositoryInterface','App\Repositories\MiAssignJobsRepository');
        $this->app->bind('App\Repositories\Interfaces\LocationRepositoryInterface','App\Repositories\LocationRepository');
        $this->app->bind('App\Repositories\Interfaces\LocationsRepositoryInterface','App\Repositories\LocationsEncRepository');



    }
}
