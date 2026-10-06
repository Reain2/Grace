<?php

use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\AdminMemberController;
use App\Http\Controllers\AdminQuoteController;
use App\Http\Controllers\AdminReadingPlanController;
use App\Http\Controllers\AdminStatisticsController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HolyDayController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ReadingPlanController;
use App\Http\Controllers\ReadingProgressController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\SuperadminAdminController;
use App\Http\Controllers\SuperadminTraditionController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\VerificationStatusController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/verification/status', VerificationStatusController::class)->middleware('role:user')->name('verification.status');
    Route::post('/verification/resubmit', [VerificationStatusController::class, 'resubmit'])->middleware('role:user')->name('verification.resubmit');

    Route::middleware('role:user')->group(function () {
        Route::get('/app', [DashboardController::class, 'user'])->name('user.dashboard');

        Route::middleware('faith.verified')->group(function () {
            Route::get('/app/interactions', fn () => 'User interactions')->name('user.interactions');
            Route::get('/app/quotes', [QuoteController::class, 'index'])->name('user.quotes.index');
            Route::get('/app/quotes/bookmarks', [QuoteController::class, 'bookmarks'])->name('user.quotes.bookmarks');
            Route::get('/app/reading-plans', [ReadingPlanController::class, 'index'])->name('user.reading-plans.index');
            Route::get('/app/notifications', [NotificationController::class, 'index'])->name('user.notifications.index');
            Route::post('/app/notifications/{notification}/read', [NotificationController::class, 'read'])->name('user.notifications.read');
            Route::get('/app/holy-days', [HolyDayController::class, 'index'])->name('user.holy-days.index');
            Route::get('/app/journal', [JournalController::class, 'index'])->name('user.journal.index');
            Route::post('/app/journal', [JournalController::class, 'store'])->name('user.journal.store');
            Route::patch('/app/journal/{entry}', [JournalController::class, 'update'])->name('user.journal.update');
            Route::delete('/app/journal/{entry}', [JournalController::class, 'destroy'])->name('user.journal.destroy');
            Route::get('/app/events', [EventController::class, 'index'])->name('user.events.index');
            Route::post('/app/events/{event}/rsvp', [EventController::class, 'rsvp'])->name('user.events.rsvp');
            Route::delete('/app/event-rsvps/{rsvp}', [EventController::class, 'cancel'])->name('user.events.cancel');
            Route::get('/app/reminders', [ReminderController::class, 'index'])->name('user.reminders.index');
            Route::post('/app/reminders', [ReminderController::class, 'store'])->name('user.reminders.store');
            Route::post('/app/reminders/{reminder}/toggle', [ReminderController::class, 'toggle'])->name('user.reminders.toggle');
            Route::delete('/app/reminders/{reminder}', [ReminderController::class, 'destroy'])->name('user.reminders.destroy');
            Route::post('/app/reading-plans/{plan}/start', [ReadingProgressController::class, 'start'])->name('user.reading-plans.start');
            Route::get('/app/reading-progress/{enrollment}', [ReadingProgressController::class, 'show'])->name('user.reading-plans.progress');
            Route::post('/app/reading-progress/{enrollment}/items/{item}', [ReadingProgressController::class, 'check'])->name('user.reading-plans.check');
            Route::post('/app/quotes/{quote}/bookmark', [QuoteController::class, 'bookmark'])->name('user.quotes.bookmark');
        });
    });

    Route::middleware('role:religion_admin')->group(function () {
        Route::get('/admin', [DashboardController::class, 'admin'])->name('admin.dashboard');
        Route::get('/admin/statistics', [AdminStatisticsController::class, 'index'])->name('admin.statistics.index');
        Route::get('/admin/events', [AdminEventController::class, 'index'])->name('admin.events.index');
        Route::post('/admin/events', [AdminEventController::class, 'store'])->name('admin.events.store');
        Route::get('/admin/members', [AdminMemberController::class, 'index'])->name('admin.members.index');
        Route::post('/admin/members/{user}/toggle', [AdminMemberController::class, 'toggle'])->name('admin.members.toggle');
        Route::get('/admin/verifications', [VerificationController::class, 'index'])->name('admin.verifications.index');
        Route::get('/admin/quotes', [AdminQuoteController::class, 'index'])->name('admin.quotes.index');
        Route::get('/admin/reading-plans', [AdminReadingPlanController::class, 'index'])->name('admin.reading-plans.index');
        Route::post('/admin/reading-plans', [AdminReadingPlanController::class, 'store'])->name('admin.reading-plans.store');
        Route::patch('/admin/reading-plans/{plan}', [AdminReadingPlanController::class, 'update'])->name('admin.reading-plans.update');
        Route::delete('/admin/reading-plans/{plan}', [AdminReadingPlanController::class, 'destroy'])->name('admin.reading-plans.destroy');
        Route::post('/admin/quotes', [AdminQuoteController::class, 'store'])->name('admin.quotes.store');
        Route::patch('/admin/quotes/{quote}', [AdminQuoteController::class, 'update'])->name('admin.quotes.update');
        Route::post('/admin/quotes/{quote}/publish', [AdminQuoteController::class, 'togglePublish'])->name('admin.quotes.publish');
        Route::delete('/admin/quotes/{quote}', [AdminQuoteController::class, 'destroy'])->name('admin.quotes.destroy');
        Route::get('/admin/verifications/{verification}/proof', [VerificationController::class, 'proof'])->name('admin.verifications.proof');
        Route::post('/admin/verifications/{verification}/claim', [VerificationController::class, 'claim'])->name('admin.verifications.claim');
        Route::post('/admin/verifications/{verification}/approve', [VerificationController::class, 'approve'])->name('admin.verifications.approve');
        Route::post('/admin/verifications/{verification}/reject', [VerificationController::class, 'reject'])->name('admin.verifications.reject');
    });

    Route::middleware('role:superadmin')->group(function () {
        Route::get('/superadmin', [DashboardController::class, 'superadmin'])->name('superadmin.dashboard');
        Route::get('/superadmin/audit-logs', [AuditLogController::class, 'index'])->name('superadmin.audit-logs.index');
        Route::get('/superadmin/traditions', [SuperadminTraditionController::class, 'index'])->name('superadmin.traditions.index');
        Route::post('/superadmin/traditions', [SuperadminTraditionController::class, 'store'])->name('superadmin.traditions.store');
        Route::post('/superadmin/traditions/{tradition}/toggle', [SuperadminTraditionController::class, 'toggle'])->name('superadmin.traditions.toggle');
        Route::get('/superadmin/admins', [SuperadminAdminController::class, 'index'])->name('superadmin.admins.index');
        Route::post('/superadmin/admins', [SuperadminAdminController::class, 'store'])->name('superadmin.admins.store');
        Route::post('/superadmin/admins/{user}/toggle', [SuperadminAdminController::class, 'toggle'])->name('superadmin.admins.toggle');
        Route::get('/superadmin/verifications', [VerificationController::class, 'index'])->name('superadmin.verifications.index');
        Route::get('/superadmin/verifications/{verification}/proof', [VerificationController::class, 'proof'])->name('superadmin.verifications.proof');
        Route::post('/superadmin/verifications/{verification}/claim', [VerificationController::class, 'claim'])->name('superadmin.verifications.claim');
        Route::post('/superadmin/verifications/{verification}/approve', [VerificationController::class, 'approve'])->name('superadmin.verifications.approve');
        Route::post('/superadmin/verifications/{verification}/reject', [VerificationController::class, 'reject'])->name('superadmin.verifications.reject');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/tradition', [ProfileController::class, 'changeTradition'])->name('profile.tradition.change');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
