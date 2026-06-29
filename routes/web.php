<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\Appointment\ResultController;
use App\Http\Controllers\Appointment\ScheduleController;
use App\Http\Controllers\Appointment\SearchController;
use App\Http\Controllers\Exam\ExamController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BiomedicalController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\Exam\FilterController;
use App\Http\Controllers\Exam\ModelController;
use App\Http\Controllers\Exam\ParameterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Routine\MapController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\Web\PatientController as WebPatientController;
use App\Http\Controllers\Pdf\PdfController;
use App\Http\Controllers\Routine\TagController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ReceptionistController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Routine\AppointmentByDayController;
use App\Http\Controllers\Routine\OccurrenceController;
use App\Http\Controllers\Routine\ProductionBiomedicalController;
use App\Http\Controllers\Routine\ProductionUnityController;
use App\Http\Controllers\Routine\TraceabilityController;
use App\Http\Controllers\UserController;

// AUTHENTICATION
Route::controller(AuthController::class)->group(function () {
    Route::get('patient-login', 'showPatientLoginForm');
    Route::post('patient-login', 'patientLogin');
    Route::get('login', 'showLoginForm');
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::get('forgot-password', 'showForgotPasswordForm');
    Route::post('forgot-password', 'forgotPassword');
    Route::get('reset-password/{user_id}/{token}', 'showResetPasswordForm');
    Route::post('reset-password', 'resetPassword');
    Route::get('change-password', 'showChangePasswordForm');
    Route::post('change-password', 'changePassword');
});

Route::middleware('sentinel.auth')->group(function () {

    // HOME
    Route::get('/', [HomeController::class, 'index']);

    // PROFILE
    Route::get('profile-edit', [UserController::class, 'edit']);
    Route::post('profile-update', [UserController::class, 'update']);
    Route::get('profile-view', [UserController::class, 'profile_view']);
    Route::resource('user', UserController::class);

    // DOCTOR
    Route::post('doctors/search', [DoctorController::class, 'search'])->name('doctors.search');
    Route::post('doctors/appointment/store', [DoctorController::class, 'storeAppointment'])->name('doctors.appointment.store');
    Route::resource('doctors', DoctorController::class);

    // PATIENT
    Route::post('patients/appointment/search', [PatientController::class, 'searchAppointmentPatient'])->name('appointment.patient.search');
    Route::post('patients/search', [PatientController::class, 'search'])->name('patients.search');
    Route::post('patients/appointment/store', [PatientController::class, 'storeAppointment'])->name('patients.appointment.store');
    Route::resource('patients', PatientController::class);

    // RECEPTIONIST
    Route::resource('receptionists', ReceptionistController::class);

    // PRESCRIPTION
    Route::get('prescription-list', [PrescriptionController::class, 'prescription_list']);
    Route::get('prescription-view/{id}', [PrescriptionController::class, 'prescription_view']);
    Route::resource('prescription', 'PrescriptionController');

    // INVOICE
    Route::post('patient-by-appointment', [InvoiceController::class, 'patient_by_appointment'])->name('patient_by_appointment');
    Route::post('appointment-by-doctor', [InvoiceController::class, 'appointment_by_doctor'])->name('appointment_by_doctor');
    Route::get('invoice-list', [InvoiceController::class, 'invoice_list']);
    Route::get('invoice-view/{id}', [InvoiceController::class, 'invoice_view']);
    Route::get('transaction', [InvoiceController::class, 'transaction']);
    Route::resource('invoice', InvoiceController::class);
    
    // BIOMEDICAL
    Route::get('biomedicals/search', [BiomedicalController::class, 'search']);
    Route::resource('biomedicals', BiomedicalController::class);
    
    // APPOINTMENT
    Route::prefix('appointments')->name('appointments.')->group(function () {

        Route::controller(AppointmentController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::get('{appointment}/edit', 'edit')->name('edit');
            Route::put('{appointment}', 'update')->name('update');
            Route::post('/', 'store')->name('store');
            Route::get('{appointment}/print', 'print')->name('print');
    
            Route::get('status/{status}', 'status')->name('status');
            Route::put('{appointment}/cancel', 'cancel')->name('cancel');
            Route::get('patient-appointment', 'patient_appointment');
        });

        // RESULT
        Route::controller(ResultController::class)->name('result.')->group(function () {
            Route::get('{appointment}/result/create', 'create')->name('create');
            Route::post('result/save', 'save')->name('save');
            Route::post('{appointment}/result/check', 'check')->name('check');
            Route::post('{appointment}/result/finish', 'finish')->name('finish');
            Route::put('{appointment}/result/update/retest', 'updateRetest')->name('update.retest');
            Route::get('{appointment}/result', 'show')->name('show');
            Route::get('{appointment}/result/print', 'print')->name('print');

            Route::put('{appointment}/exams/{exam}/result/cancel', 'cancel')->name('cancel');
            Route::put('{appointment}/exams/{exam}/result/retest', 'retest')->name('retest');
            Route::put('{appointment}/exams/{exam}/result/restore', 'restore')->name('restore');
        });

        // PDF
        Route::get('{id}/pdf', [PdfController::class, 'generate'])->name('result.pdf');
        Route::get('{appointment}/exams/{exam}/result', [PdfController::class, 'single'])->name('result.single.pdf');

        // SCHEDULE
        Route::controller(ScheduleController::class)->group(function () {
            Route::get('schedules', 'index')->name('schedule.index');
            Route::get('schedules/list', 'list')->name('schedule.list');
            Route::post('doctor-by-day-time', 'doctor_by_day_time')->name('doctor_by_day_time');
            Route::post('appointment-time-by-appointment-slot', 'time_by_slot')->name('timeBySlot');
            Route::get('cal-appointment-show', 'cal_appointment_show');
        });

        // SEARCH
        Route::controller(SearchController::class)->name('search.')->group(function () {
            Route::post('search/by-date', 'byDate')->name('by.date');
            Route::post('search/by-protocol', 'byProtocol')->name('by.protocol');
            Route::post('search/by-patient', 'byPatient')->name('by.patient');
            Route::get('{appointment}/search-patient', 'searchPatient')->name('patient');
            Route::post('search/by-status-completed', 'byStatusCompleted')->name('by.status.completed');
        });

    });

    // REPORT
    Route::get('getMonthlyUsersRevenue', [ReportController::class, 'getMonthlyUsersRevenue']);
    Route::get('getMonthlyEarning', [ReportController::class, 'getMonthlyEarning']);
    Route::get('getMonthlyAppointments', [ReportController::class, 'getMonthlyAppointments']);

    // NOTIFICATION
    Route::get('notification-list', [NotificationController::class, 'index']);
    Route::get('notification/{id}', [NotificationController::class, 'notification']);
    Route::post('top-notification', [NotificationController::class, 'notification_top']);
    Route::get('notification-count', [NotificationController::class, 'notificationCount']);

    // EMAIL
    Route::get('invoice-email/{id}', [EmailController::class, 'invoice_email_send']);
    Route::get('prescription-email/{id}', [EmailController::class, 'prescription_email_send']);

    // EXAMS
    Route::prefix('exams')->name('exams.')->group(function () {

        Route::controller(ExamController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('{exam}/edit', 'edit')->name('edit');
            Route::put('{exam}', 'update')->name('update');
            Route::post('search-all', 'search')->name('search.all');
            Route::post('search-name', 'searchName')->name('search.name');
            Route::post('search-abbreviation', 'searchAbbreviation')->name('search.abbreviation');
            
            // CLIENT PRIVATE
            Route::get('getexambyid', 'getexam')->name('getexambyid');
            Route::get('gealltexam', 'gealltexam')->name('gealltexam');
        });

        Route::prefix('parameters')
            ->name('parameters.')
            ->controller(ParameterController::class)
            ->group(function () {
                Route::post('/', 'store')->name('store');
                Route::get('{parameter}', 'show')->name('show');
            });
            
        Route::prefix('filters')
            ->name('filters.')
            ->controller(FilterController::class)
            ->group(function () {
                Route::post('/', 'store')->name('store');
                Route::get('{filter}', 'show')->name('show');
                Route::delete('{filter}/delete', 'destroy')->name('destroy');
            });

        Route::prefix('models')
            ->name('models.')
            ->controller(ModelController::class)
            ->group(function () {
                Route::get('{exam}/create', 'create')->name('create');
                Route::post('{exam}/store', 'store')->name('store');
                Route::get('exams/{exam}/model/{model}/edit', 'edit')->name('edit')->scopeBindings();
                Route::put('exams/{exam}/model/{model}/update', 'update')->name('update')->scopeBindings();
            });
    });

    // CATEGORY
    Route::resource('categories', CategoryController::class)->except(['show', 'destroy']);
    
    // ROUTINE
    Route::prefix('routine')->name('routine.')->group(function () {

        Route::prefix('map')
            ->name('map.')
            ->controller(MapController::class)
            ->group(function () {
                Route::get('patient/index', 'patientIndex')->name('patient.index');
                Route::post('patient/search', 'patientSearch')->name('patient.search');
                Route::get('patient/{appointment}/print', 'patientPrint')->name('patient.print');
                Route::get('biomedical/index', 'biomedicalIndex')->name('biomedical.index');
                Route::post('biomedical/search', 'biomedicalSearch')->name('biomedical.search');
                Route::get('biomedical/{id}/{date}/print', 'biomedicalPrint')->name('biomedical.print');
            });

        Route::name('appointment.by.day.')
            ->controller(AppointmentByDayController::class)
            ->group(function () {
                Route::get('appointment-by-day', 'index')->name('index');
                Route::post('appointment-by-day-search', 'search')->name('search');
                Route::get('appointments/by/{date}/{unity_id}/print/{status}', 'print')->name('print');
            });

        Route::name('production.by.biomedical.')
            ->controller(ProductionBiomedicalController::class)
            ->group(function () {
                Route::get('production-by-biomedical', 'index')->name('index');
                Route::post('production-by-biomedical-search', 'searchAll')->name('search.all');

                Route::get('production-by-biomedical/{biomedical}/between/{dateStart}/{dateEnd}/view', 'searchByBiomedical')->name('search.by.biomedical');
                Route::get('production-by-biomedical/{biomedical}/between/{dateStart}/{dateEnd}/amount', 'searchByBiomedicalAmount')->name('search.by.biomedical.amount');
            });

        Route::name('production.by.unity.')
            ->controller(ProductionUnityController::class)
            ->group(function () {
                Route::get('production-by-unity', 'index')->name('index');
                Route::post('production-by-unity-search', 'searchAll')->name('search.all');

                Route::get('production-by-unity/{unity}/between/{dateStart}/{dateEnd}/view', 'searchByUnity')->name('search.by.unity');
            });

        Route::prefix('traceability')
            ->name('traceability.')
            ->controller(TraceabilityController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('search', 'search')->name('search');
                Route::post('historic', 'historic')->name('historic');
            });

        Route::prefix('occurrence')
            ->name('occurrence.')
            ->controller(OccurrenceController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('search', 'search')->name('search');
                Route::get('{occurrence}/show', 'show')->name('show');
                Route::put('{occurrence}/update', 'update')->name('update');
            });

        Route::get('tag', [TagController::class, 'index'])->name('tag.index');
        Route::get('{appointment}/tag', [TagController::class, 'print'])->name('tag.print');
        Route::post('tag/search', [TagController::class, 'search'])->name('tag.by.unity.search');
        Route::get('tag/by-unity/{unity}/between/{date_start}/{date_end}/view', [TagController::class, 'printByUnity']);
       
    });

});

Route::get('paciente/{cpf}/{cns}/resultados', [WebPatientController::class, 'index'])->name('patient.result.index');
Route::get('paciente/{token}/pdf', [WebPatientController::class, 'generatePdf'])->name('patient.result.pdf');
Route::get('paciente/consulta', [WebPatientController::class, 'searchIndex'])->name('patient.result.search.index');
Route::post('patient/search-result', [WebPatientController::class, 'search'])->name('patient.result.search');
Route::get('paciente/{id}/resultado', [WebPatientController::class, 'show'])->name('patient.result.show');
