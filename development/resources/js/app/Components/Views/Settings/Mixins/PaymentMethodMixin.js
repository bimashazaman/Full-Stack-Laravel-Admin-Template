import {PAYMENT_METHOD} from "../../../../Config/ApiUrl";

export default {
    data() {
        return {
            options: {
                url: PAYMENT_METHOD,
                showHeader: true,
                tableShadow: false,
                tablePaddingClass: 'p-0',
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'text',
                        key: 'name',
                    },
                    {
                        title: this.$t('type'),
                        type: 'custom-html',
                        key: 'type',
                        modifier: (value) => {
                            return this.$t(value)
                        }
                    },
                    {
                        title: this.$t('status'),
                        type: 'custom-html',
                        key: 'status',
                        modifier: (value) => {
                            return `<span class="badge badge-pill badge-${value.class}">${value.translated_name}</span>`;
                        }
                    },
                    {
                        title: this.$t('default'),
                        type: 'custom-html',
                        key: 'is_default',
                        modifier: (value) => {
                            return parseInt(value) === 1 ? this.$t("yes") : this.$t("no");
                        }
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'actions',
                    },
                ],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'desc',
                actionType: "default",
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'edit',
                        component: 'tax-create-edit-modal',
                        modalId: 'tax-create-edit-modal',
                        /*modifier: () => {
                            return this.$can('update_payment_method')
                        },*/
                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash-2',
                        type: 'modal',
                        component: 'app-confirmation-modal',
                        modalId: 'app-confirmation-modal',
                        url: PAYMENT_METHOD,
                        name: 'delete',
                        /*modifier: () => {
                            return this.$can('delete_payment_method')
                        },*/
                    }
                ],
            },
        }
    }
}
