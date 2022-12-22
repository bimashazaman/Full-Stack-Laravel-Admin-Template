<?php

use App\Http\Controllers\{App\CustomTable\DataTableCustomTableController,
    Core\Auth\Role\PermissionController,
    Core\Auth\User\AuthenticateUserController,
    Core\Auth\User\LoginController,
    Core\Auth\User\UserPasswordController as BaseUserPasswordControllerAlias,
    Core\Auth\User\UserUpdateController,
    Core\Auth\User\UserThumbnailController,
    Core\Builder\Form\CustomFieldTypeController,
    Core\Builder\Table\CustomTableController,
    Core\Frontend\TableFilterController,
    Core\LanguageController,
    Core\Log\ActivityLogController,
    Core\Notification\NotificationChannelController,
    Core\Notification\NotificationController,
    Core\Notification\NotificationEventController,
    Core\Setting\StatusController,
    Core\Setting\TypeController};

Route::group(['prefix' => 'app'], function () {
    Route::get('types', [TypeController::class, 'index'])
        ->name('types.index');

    Route::get('statuses', [StatusController::class, 'index'])
        ->name('statuses.index');

    Route::get('notification-events', [NotificationEventController::class, 'index'])
        ->name('notification-events.index');

    Route::get('notification-events/{notification_event}', [NotificationEventController::class, 'show'])
        ->name('notification-events.show');

    Route::get('notification-channels', [NotificationChannelController::class, 'index'])
        ->name('notification-channels.index');

    Route::get('custom-field-types', [CustomFieldTypeController::class, 'index']);

    Route::resource('table-filter', TableFilterController::class)->except('create','edit', 'show');

    Route::get('custom-table/values/{custom_table}', [DataTableCustomTableController::class, 'getTableValues'])
        ->name('custom-table.field-values');

    Route::post('custom-table/values', [DataTableCustomTableController::class, 'storeTableValues'])
        ->name('custom-table.store-values');

    Route::get('custom-table/row/{custom_table_row}', [DataTableCustomTableController::class, 'getTableRow'])
        ->name('custom-table.row');

    Route::patch('custom-table/row/{custom_table_row}', [DataTableCustomTableController::class, 'updateTableRow'])
        ->name('custom-table.update-row');

    Route::delete('custom-table/row/{custom_table_row}', [DataTableCustomTableController::class, 'deleteTableRow'])
        ->name('custom-table.delete-row');

});

Route::get('auth/permissions', [PermissionController::class, 'index'])
    ->name('permissions.index');

Route::get('log-out', [LoginController::class, 'logOut'])->name('auth.log_out');

Route::get('user/notifications', [NotificationController::class, 'index'])
    ->name('user-notifications.index');

Route::post('user/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])
    ->name('user-notifications.mark-as-read');

Route::post('user/notifications/mark-all-as-read', [NotificationController::class, 'markAsReadAll'])
    ->name('user-notifications.mark-all-as-read');

Route::post('user/notifications/mark-as-unread/{id}', [NotificationController::class, 'markAsUnread'])
    ->name('user-notifications.mark-as-unread');

Route::get('user/activity-log', [ActivityLogController::class, 'show'])
    ->name('activity-log.show');

Route::get('user/activities/{user}', [ActivityLogController::class, 'activities'])
    ->name('user.activities')
    ->middleware('can:view_activities');

Route::get('authenticate-user', [AuthenticateUserController::class, 'show'])
    ->name('user.authenticate-user');

Route::post('auth/users/change-settings', [UserUpdateController::class, 'update'])
    ->name('users.change-settings');

Route::post('auth/users/profile-picture', [UserThumbnailController::class, 'store'])
    ->name('users.change-profile-picture');

Route::group(['prefix' => 'auth/users/{user}'], function () {
    Route::post('password/change', [BaseUserPasswordControllerAlias::class, 'update'])
        ->name('users.change-password');
});

Route::get('languages', [LanguageController::class, 'index'])->name('languages.index');

Route::get('custom-table/view/{custom_table}', [CustomTableController::class, 'viewTable'])
    ->name('custom-table-data.view')
    ->middleware('can:view_custom_tables');