<template>
    <div>
        <app-table :id="customFieldTableId" :options="customField" @action="getAction"/>

        <app-custom-table-modal v-if="isModalActive"
                                :tableId="customFieldTableId"
                                :selected-url="customFiledSelectedUrl"
                                @close-modal="closeModal"/>

        <app-confirmation-modal
            v-if="confirmationModalActive"
            modal-id="custom-field-delete-modal"
            @confirmed="confirmed"
            @cancelled="cancelled"/>

    </div>
</template>

<script>
import {urlGenerator} from "../../../../Helpers/AxiosHelper";
import {FormMixin} from "../../../../../core/mixins/form/FormMixin";
import {CUSTOM_TABLE} from "../../../../Config/ApiUrl";

export default {
    name: "CustomField",
    mixins: [FormMixin],
    props: ['id'],
    data() {
        return {
            urlGenerator,
            CUSTOM_TABLE,
            isModalActive: false,
            confirmationModalActive: false,
            customFieldTableId: 'custom-table',
            customFiledSelectedUrl: '',
                customField: {
                    tableShadow: false,
                    tablePaddingClass: 'px-0 py-primary',
                    name: 'CustomTable',
                    url: CUSTOM_TABLE,
                    showHeader: true,
                    columns: [
                        {
                            title: this.$t('name'),
                            type: 'text',
                            key: 'name'
                        },
                        {
                            title: this.$t('context'),
                            key: 'context',
                            type: 'custom-html',
                            modifier: (value) => this.$t(value)
                        },

                        {
                            title: 'Action',
                            type: 'action',
                            key: 'invoice',
                        },
                    ],
                    filters: [],
                    showSearch: true,
                    showFilter: true,
                    showManageColumn: false,
                    paginationType: "pagination",
                    responsive: true,
                    rowLimit: 10,
                    showAction: true,
                    orderBy: 'desc',
                    actionType: "default",
                    actions: [
                        {
                            title: 'Edit',
                            icon: 'edit',
                            type: 'edit',
                            component: 'app-custom-field-modal',
                            modalId: 'custom-field-modal',
                            url: '',
                        }, {
                            title: this.$t('delete'),
                            icon: 'trash',
                            type: 'delete',
                            component: 'app-confirmation-modal',
                            modalId: 'custom-field-delete-modal',
                            url: '',
                        },
                    ],
                }
            }
        },

        methods: {
            getAction(row, action, active) {
                this.customFiledSelectedUrl = (`${CUSTOM_TABLE}/${row.id}`)
                if (action.type === 'edit') {
                    this.isModalActive = true;
                }else if (action.type === 'delete') {
                    this.confirmationModalActive = true;
                }
            },
            confirmed() {
                this.axiosDelete(this.customFiledSelectedUrl).then(response => {
                    this.$toastr.s(response.data.message);
                    this.$hub.$emit('reload-' + this.customFieldTableId);
                }).catch(({error}) => {
                    this.$toastr.e(response.data.message);
                });
            },
            cancelled() {
                this.confirmationModalActive = false;
            },
            openModal() {
                this.isModalActive = true;
                $('#custom-field-modal').modal('show');
            },
            closeModal() {
                this.isModalActive = false;
                this.customFiledSelectedUrl = '';
                $("#custom-field-modal").modal('hide');
            },
        },

        mounted(){
            this.$hub.$on('headerButtonClicked-'+this.id, () => {
                this.isModalActive = true
                this.customFiledSelectedUrl = '';
            })
        }
    }
</script>

