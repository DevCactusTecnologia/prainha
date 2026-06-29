<?php

namespace App\Providers;

use App\View\Composers\RoleComposer;
use App\View\Composers\UserComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer([
            'categories.index',
            'categories.create',
            'categories.edit',

            'exams.index',
            'exams.create',
            'exams.edit',
            'exams.models.create',
            'exams.models.edit',

            'routine.map.patient.index',
            'routine.map.biomedical.index',
            'routine.map.biomedical.print',
            'routine.appointment-by-day.index',
            'routine.production-by-biomedical.index',
            'routine.production-by-unity.index',
            'routine.tag.index',
            'routine.traceability.index',
            'routine.occurrence.index',
            'routine.occurrence.show',

            'doctors.index',
            'doctors.create',
            'doctors.edit',
            'doctors.show',

            'biomedicals.index',
            'biomedicals.create',
            'biomedicals.show',
            'biomedicals.edit',

            'receptionists.create',
            'receptionists.show',
            'receptionists.edit',

            'patients.index',
            'patients.create',
            'patients.show',
            'patients.edit',

            'appointments.index',
            'appointments.result.create',
            'appointments.result.show',
            'appointments.schedule',
            'appointments.create',
            'appointments.edit',

            'index',
        ], UserComposer::class);

        View::composer([
            'categories.index',
            'categories.create',
            'categories.edit',

            'exams.index',
            'exams.create',
            'exams.edit',
            'exams.models.create',
            'exams.models.edit',

            'routine.map.patient.index',
            'routine.map.biomedical.index',
            'routine.appointment-by-day.index',
            'routine.production-by-biomedical.index',
            'routine.production-by-unity.index',
            'routine.tag.index',
            'routine.traceability.index',
            'routine.occurrence.index',
            'routine.occurrence.show',

            'doctors.index',
            'doctors.create',
            'doctors.edit',
            'doctors.show',

            'biomedicals.index',
            'biomedicals.create',
            'biomedicals.show',
            'biomedicals.edit',

            'receptionists.create',
            'receptionists.show',
            'receptionists.edit',

            'patients.index',
            'patients.create',
            'patients.show',
            'patients.edit',

            'appointments.index',
            'appointments.result.create',
            'appointments.result.show',
            'appointments.schedule',
            'appointments.create',
            'appointments.edit',

            'index',
        ], RoleComposer::class);
    }

}
