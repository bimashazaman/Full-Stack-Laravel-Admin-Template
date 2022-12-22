<?php

namespace App\Http\Composer;

use App\Models\Core\Builder\Table\CustomTable;
use Illuminate\View\View;

class SidebarComposer
{
    public function compose(View $view)
    {
        $table = CustomTable::all();

        $menu = [
            [
                'id' => 'dashboard-samples',
                'icon' => 'pie-chart',
                'name' => __t('dashboard'),
                'permission' => authorize_any(['view_default', 'view_academy', 'view_ecmommerce', 'view_hospital', 'view_hrm']),
                'subMenu' => [
                    [
                        'name' => trans('custom.default'),
                        'url' => request()->root() . '/admin/dashboard',
                        'permission' => auth()->user()->can('view_default'),
                    ],
                    [
                        'name' => trans('custom.academy'),
                        'url' => request()->root() . '/dashboard/academy',
                        'permission' => auth()->user()->can('view_academy'),
                    ],
                    [
                        'name' => trans('custom.e_commerce'),
                        'url' => request()->root() . '/dashboard/ecommerce',
                        'permission' => auth()->user()->can('view_ecmommerce'),
                    ],
                    [
                        'name' => trans('custom.hospital'),
                        'url' => request()->root() . '/dashboard/hospital',
                        'permission' => auth()->user()->can('view_hospital'),
                    ],
                    [
                        'name' => trans('custom.hrm'),
                        'url' => request()->root() . '/dashboard/hrm',
                        'permission' => auth()->user()->can('view_hrm'),
                    ],
                ],
            ],
            [
                'id' => 'auth-pages',
                'icon' => 'power',
                'name' => __t('authentication'),
                'permission' => authorize_any(['view_registration', 'view_forget_password', 'view_reset_password']),
                'subMenu' => [
                    [
                        'name' => trans('custom.registration'),
                        'url' => request()->root() . '/user/registration',
                        'permission' => auth()->user()->can('view_registration'),
                    ],
                    [
                        'name' => trans('custom.forget_password'),
                        'url' => request()->root() . '/forget-password',
                        'permission' => auth()->user()->can('view_forget_password'),
                    ],
                    [
                        'name' => trans('custom.reset_password'),
                        'url' => request()->root() . '/reset/password',
                        'permission' => auth()->user()->can('view_reset_password'),
                    ],
                ],
            ],
            [
                'id' => 'tables',
                'icon' => 'grid',
                'name' => trans('custom.datatables'),
                'permission' => authorize_any([
                    'view_basic_datatable', 'manage_functional_datatable', 'manage_advance_datatable',
                    'view_responsive_datatable', 'manage_filter_type_datatable', 'manage_paginated_datatable',
                    'manage_gird_view_datatable',
                ]),
                'subMenu' => array_merge([
                    [
                        'name' => trans('custom.basic'),
                        'url' => request()->root() . '/tables/basic-datatable',
                        'permission' => auth()->user()->can('view_basic_datatable'),
                    ],
                    [
                        'name' => trans('custom.functional'),
                        'url' => request()->root() . '/tables/functional',
                        'permission' => auth()->user()->can('manage_functional_datatable'),
                    ],
                    [
                        'name' => trans('custom.advance'),
                        'url' => request()->root() . '/tables/advance',
                        'permission' => auth()->user()->can('manage_advance_datatable'),
                    ],
                    [
                        'name' => trans('custom.responsive'),
                        'url' => request()->root() . '/tables/responsive',
                        'permission' => auth()->user()->can('view_responsive_datatable'),
                    ],
                    [
                        'name' => trans('custom.filter_type'),
                        'url' => request()->root() . '/tables/filter',
                        'permission' => auth()->user()->can('manage_filter_type_datatable'),
                    ],
                    [
                        'name' => trans('custom.grid_view'),
                        'url' => request()->root() . '/tables/grid-view',
                        'permission' => auth()->user()->can('manage_gird_view_datatable'),
                    ],
                    [
                        'name' => trans('custom.paginated'),
                        'url' => request()->root() . '/tables/pagination',
                        'permission' => auth()->user()->can('manage_paginated_datatable'),
                    ],
                ], $table->map(function ($table) {
                    if ($table->context == 'datatable') {
                        return [
                            'name' => $table->name,
                            'url' => request()->root() . '/admin/custom-table/view/'. $table->id,
                            'permission' => true,
                        ];
                    }
                    return [];
                })->toArray()),
            ],
            [
                'id' => 'forms',
                'icon' => 'sidebar',
                'name' => trans('custom.forms_and_fields'),
                'permission' => authorize_any(['view_form_layouts', 'view_form_elements', 'view_form_validation', 'view_form_text_editor']),
                'subMenu' => [
                    [
                        'name' => trans('custom.form_layouts'),
                        'url' => request()->root() . '/form/layouts',
                        'permission' => auth()->user()->can('view_form_layouts'),
                    ],
                    [
                        'name' => trans('custom.form_elements'),
                        'url' => request()->root() . '/form/elements',
                        'permission' => auth()->user()->can('view_form_elements'),
                    ],
                    [
                        'name' => trans('custom.form_validations'),
                        'url' => request()->root() . '/form/validation',
                        'permission' => auth()->user()->can('view_form_validation'),
                    ],
                    [
                        'name' => trans('custom.form_wizard'),
                        'url' => request()->root() . '/form-wizard',
                        'permission' => auth()->user()->can('view_form_wizard'),
                    ],
                    [
                        'name' => trans('custom.text_editor'),
                        'url' => request()->root() . '/form/text',
                        'permission' => auth()->user()->can('view_form_text_editor'),
                    ],
                ],
            ],
            [
                'id' => 'ui',
                'icon' => 'trello',
                'name' => trans('custom.ui_elements'),
                'permission' => authorize_any([
                    'view_ui_avatar', 'view_ui_badges_pill', 'view_buttons', 'view_cards',
                    'view_checkboxes_radios', 'view_error_notes', 'view_icons', 'view_modals', 'view_nothing_to_show', 'view_tabs',
                ]),
                'subMenu' => [
                    [
                        'name' => trans('custom.avatars'),
                        'url' => request()->root() . '/avatars',
                        'permission' => auth()->user()->can('view_ui_avatar'),
                    ],
                    [
                        'name' => trans('custom.badges_and_pills'),
                        'url' => request()->root() . '/badges',
                        'permission' => auth()->user()->can('view_ui_badges_pill'),
                    ],
                    [
                        'name' => trans('custom.buttons'),
                        'url' => request()->root() . '/buttons',
                        'permission' => auth()->user()->can('view_buttons'),
                    ],
                    [
                        'name' => trans('custom.cards'),
                        'url' => request()->root() . '/cards',
                        'permission' => auth()->user()->can('view_cards'),
                    ],
                    [
                        'name' => trans('custom.checkboxes_and_radios'),
                        'url' => request()->root() . '/checkboxes-radios',
                        'permission' => auth()->user()->can('view_checkboxes_radios'),
                    ],
                    [
                        'name' => trans('custom.notes'),
                        'url' => request()->root() . '/error-notes',
                        'permission' => auth()->user()->can('view_error_notes'),
                    ],
                    [
                        'name' => trans('custom.icons'),
                        'url' => request()->root() . '/icons',
                        'permission' => auth()->user()->can('view_icons'),
                    ],
                    [
                        'name' => trans('custom.modals'),
                        'url' => request()->root() . '/modal',
                        'permission' => auth()->user()->can('view_modals'),
                    ],
                    [
                        'name' => trans('custom.nothing_to_show'),
                        'url' => request()->root() . '/nothing-to-show',
                        'permission' => auth()->user()->can('view_nothing_to_show'),
                    ],
                    [
                        'name' => trans('custom.tabs'),
                        'url' => request()->root() . '/tabs',
                        'permission' => auth()->user()->can('view_tabs'),
                    ],
                ],
            ],
            [
                'id' => 'pages',
                'icon' => 'copy',
                'name' => trans('default.sample_pages'),
                'permission' => authorize_any(['view_user_profile', 'view_blank_page']),
                'subMenu' => [
                    [
                        'name' => trans('custom.user_profile'),
                        'url' => request()->root() . '/my-profile',
                        'permission' => auth()->user()->can('view_user_profile'),
                    ],
                    [
                        'name' => trans('custom.report'),
                        'url' => request()->root() . '/report-view',
                        'permission' => auth()->user()->can('manage_report_view'),
                    ],
                    [
                        'name' => trans('custom.blank_page'),
                        'url' => request()->root() . '/blank-page',
                        'permission' => auth()->user()->can('view_blank_page'),
                    ],
                ],
            ],
            [
                'icon' => 'message-square',
                'name' => trans('default.chat'),
                'url' => request()->root() . '/chat',
                'permission' => auth()->user()->can('view_chat'),
            ],
            [
                'icon' => 'calendar',
                'name' => trans('custom.calendar_view'),
                'url' => request()->root() . '/calendar-view',
                'permission' => auth()->user()->can('manage_calendar_view'),
            ],
            [
                'icon' => 'columns',
                'name' => trans('custom.kanban_view'),
                'url' => request()->root() . '/kanban-view',
                'permission' => auth()->user()->can('manage_kanban_view'),
            ],
            [
                'icon' => 'credit-card',
                'name' => trans('custom.payment_method'),
                'url' => request()->root() . '/payment-view',
                'permission' => auth()->user()->can('view_payment_method'),
            ],
            [
                'id' => 'error-pages',
                'icon' => 'alert-triangle',
                'name' => trans('custom.error_pages'),
                'permission' => authorize_any([
                    'view_error_400', 'view_error_401', 'view_error_403', 'view_error_404',
                    'view_error_500', 'view_error_503',
                ]),
                'subMenu' => [
                    [
                        'name' => trans('custom.error_400'),
                        'url' => request()->root() . '/error-400',
                        'permission' => auth()->user()->can('view_error_400'),
                    ],
                    [
                        'name' => trans('custom.error_401'),
                        'url' => request()->root() . '/error-401',
                        'permission' => auth()->user()->can('view_error_401'),
                    ],
                    [
                        'name' => trans('custom.error_403'),
                        'url' => request()->root() . '/error-403',
                        'permission' => auth()->user()->can('view_error_403'),
                    ],
                    [
                        'name' => trans('custom.error_404'),
                        'url' => request()->root() . '/error-404',
                        'permission' => auth()->user()->can('view_error_404'),
                    ],
                    [
                        'name' => trans('custom.error_500'),
                        'url' => request()->root() . '/error-500',
                        'permission' => auth()->user()->can('view_error_500'),
                    ],
                    [
                        'name' => trans('custom.error_503'),
                        'url' => request()->root() . '/error-503',
                        'permission' => auth()->user()->can('view_error_503'),
                    ],
                ],
            ],
            [
                'icon' => 'user-check',
                'name' => trans('custom.user_and_roles'),
                'url' => request()->root() . '/users-and-roles',
                'permission' => authorize_any(['view_users', 'view_roles', 'invite_user', 'create_roles']),
            ],
            [
                'icon' => 'settings',
                'name' => trans('custom.settings'),
                'url' => request()->root() . '/app-setting',
                'permission' => authorize_any(
                    [
                        'view_settings', 'update_settings', 'view_delivery_settings',
                        'update_delivery_settings',
                        'view_sms_settings', 'update_sms_settings',
                        'view_recaptcha_settings',
                        'view_payment_method',
                        'update_payment_method',
                        'delete_payment_method',
                        'view_notification_settings', 'update_notification_settings', 'update_notification_templates',
                        'view_notification_templates',
                    ]
                ),
            ],
        ];


        $view->with(['data' => $menu]);
    }
}
