<?php

namespace App\Providers;


use App\Events\Api\ResendCodeEvent;
use App\Events\Api\UserCreateEvent;
use App\Events\Api\UserForgotPassword;
use App\Events\Backend\DocumentNotUploadEvent;
use App\Events\Backend\NotificationEvent;
use App\Events\Backend\NotTrainedEvent;
use App\Events\Backend\PushNotificationEvent;
use App\Events\Backend\QuizPendingEvent;
use App\Listeners\Api\ForgotPassword;

use App\Listeners\Api\ResendCodeListener;
use App\Listeners\Api\UserCreateListener;
use App\Listeners\Backend\DocumentNotUploadListener;
use App\Listeners\Backend\NotificationListener;
use App\Listeners\Backend\NotTrainedListener;
use App\Listeners\Backend\PushNotificationListener;
use App\Listeners\Backend\QuizPendingListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserForgotPassword::class => [
            ForgotPassword::class,
        ],
        UserCreateEvent::class => [
            UserCreateListener::class,
        ],
        ResendCodeEvent::class => [
            ResendCodeListener::class,
        ],
        NotificationEvent::class => [
            NotificationListener::class,
        ],
        PushNotificationEvent::class => [
            PushNotificationListener::class,
        ],
        DocumentNotUploadEvent::class => [
            DocumentNotUploadListener::class,
        ],
        NotTrainedEvent::class => [
            NotTrainedListener::class,
        ],
        QuizPendingEvent::class => [
            QuizPendingListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
