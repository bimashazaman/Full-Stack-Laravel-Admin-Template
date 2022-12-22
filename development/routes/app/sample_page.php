<?php

use App\Http\Controllers\App\PaymentMethod\PaypalController;
use App\Http\Controllers\App\PaymentMethod\StripeController;
use App\Http\Controllers\App\SamplePage\ReportController;
use App\Http\Controllers\App\SamplePage\CalendarController;
use App\Http\Controllers\App\SamplePage\KanbanView\TaskController;
use App\Http\Controllers\App\SamplePage\KanbanView\StageController;

Route::view('chat', 'sample-pages.chat');
Route::view('calendar-view', 'sample-pages.calendar-view');
Route::view('report-view', 'sample-pages.report');
Route::view('kanban-view', 'sample-pages.kanban-view');

// Calendar Events Handling
Route::resource('calendars', CalendarController::class);

// Report
Route::get('reports', [ReportController::class, 'index'])->name('report.index');

// Kanban-view task management
Route::get('stages', [StageController::class, 'index'])->name('stages.index');
Route::resource('tasks', TaskController::class);

Route::get('stripe-status', [StripeController::class, 'stripeStatus'])
    ->name('payment_method.stripe-status');

Route::get('paypal-status', [PaypalController::class, 'paypalStatus'])
    ->name('payment_method.paypal-status');

Route::get('create-payment', [PaypalController::class, 'create'])
    ->name('create-payment');

Route::get('execute-payment', [PaypalController::class, 'execute'])
    ->name('execute-payment');

Route::get('cancel-payment', [PaypalController::class, 'cancel'])
    ->name('cancel-payment');