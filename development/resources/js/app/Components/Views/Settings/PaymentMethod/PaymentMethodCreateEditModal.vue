<template>
    <modal :modal-id="modalId"
           :title="generateModalTitle('payment_method')"
           :preloader="preloader"
           @submit="submit"
           @close-modal="closeModal">

        <template slot="body">
            <app-overlay-loader v-if="preloader"/>

            <form ref="form"
                  :data-url="selectedUrl ? selectedUrl : url"
                  class="mb-0"
                  :class="{'loading-opacity': preloader}">
                <div class="form-group">
                    <label>{{ $t('name') }}</label>
                    <app-input type="text"
                               v-model="formData.name"
                               :placeholder="$t('name')"
                               :required="true"
                    />
                </div>
                <div class="form-group">
                    <label>{{ $t('type') }}</label>
                    <app-input type="select"
                               v-model="formData.type"
                               :list="paymentType"
                               :required="true"
                    />
                </div>
                <div v-if="selectedUrl" class="form-group">
                    <label>{{ $t('payment_status') }}</label>
                    <app-input type="radio"
                               :radio-checkbox-name="'for-status'"
                               list-value-field="translated_name"
                               v-model="formData.status_id"
                               :list="statusList"
                    />
                </div>
                <template v-if="selectedType">
                    <div v-if="formData.type === 'stripe'" class="form-group">
                        <label>{{ $t('public_key') }}</label>
                        <app-input type="text"
                                   :placeholder="$t('public_key')"
                                   v-model="formData.public_key"
                                   :required="true"
                        />
                    </div>

                    <div v-else class="form-group">
                        <label>{{ $t('client_id') }}</label>
                        <app-input type="text"
                                   :placeholder="$t('client_id')"
                                   v-model="formData.client_id"
                                   :required="true"
                        />
                    </div>

                    <div class="form-group">
                        <label>{{ $t('secret_key') }}</label>
                        <app-input type="password"
                                   :placeholder="$t('secret_key')"
                                   v-model="formData.secret_key"
                                   :required="true"
                        />
                    </div>
                    <div class="form-group">
                        <app-input type="single-checkbox"
                                   v-model="formData.is_default"
                                   :list-value-field="$t('mark_as_default')"
                        />
                    </div>
                </template>

            </form>

        </template>

    </modal>
</template>

<script>
import {FormMixin} from "../../../../../core/mixins/form/FormMixin";
import {GET_PAYMENT_METHOD_STATUS, PAYMENT_METHOD} from "../../../../Config/ApiUrl";
import HelperMixin from "../../../../Mixins/global/HelperMixin";


export default {
    name: "PaymentMethodCreateEditModal",
    mixins: [FormMixin, HelperMixin],
    props: {
        modalId: {
            type: String,
        }
    },

    data() {
        return {
            url: PAYMENT_METHOD,
            statusList: [],
            paymentType: [
                {id: '', value: 'Choose One', disabled: true},
                {id: 'cash', value: this.$t('cash')},
                {id: 'stripe', value: this.$t('stripe')},
                {id: 'paypal', value: this.$t('paypal')}
            ],
            formData: {
                is_default: false,
                public_key: '',
                secret_key: '',
                client_id: '',
                mode: '',
            },
            preloader: false,
            showError: false,

        }
    },
    computed: {
        selectedType() {
            if (this.formData.type === 'stripe' || this.formData.type === 'paypal') {
                if (this.formData.type === 'stripe') {
                    this.formData.client_id = '';
                    this.formData.mode = '';
                } else {
                    this.formData.public_key = '';
                }
                return true;
            }
            this.formData.secret_key = '';
            this.formData.public_key = '';
            this.formData.client_id = '';
            this.formData.mode = '';
            return false;
        }
    },
    created() {
        this.getStatusList();
    },
    methods: {
        getStatusList() {
            this.axiosGet(GET_PAYMENT_METHOD_STATUS).then(response => {
                this.statusList = response.data
            });
        },
        beforeSubmit() {
            this.preloader = true;
        },
        submit() {
            this.save(this.formData);
        },
        afterFinalResponse() {
            this.preloader = false;
        },
        afterSuccess({data}) {
            this.formData = {};
            $('#payment-method-create-edit-modal').modal('hide');
            this.$emit('payment-method-created');
            this.toastAndReload(data.message, 'all-payment-methods-table');
        },
        afterError(response) {
            this.message = '';
            this.loading = false;
            this.errors = response.data.errors || {};
            if (response.status != 422)
                this.$toastr.e(response.data.message || response.statusText)
        },
        afterSuccessFromGetEditData(response) {
            this.preloader = false;
            this.formData = response.data;

            if (response.data.settings) {
                let paypal = response.data.settings.find((item) => (item.name === 'client_id'))
                if (paypal) {
                    this.formData.client_id = paypal.value;
                    this.formData.type = 'paypal'
                    let mode = response.data.settings.find((item) => (item.name === 'mode'));
                    if (mode)
                        this.formData.mode = mode.value;
                }

                let stripe = response.data.settings.find((item) => (item.name === 'public_key'))

                if (stripe) {
                    this.formData.public_key = stripe.value;
                    this.formData.type = 'stripe'
                }

                let secretKey = response.data.settings.find((item) => (item.name === 'secret_key'))
                if (secretKey)
                    this.formData.secret_key = secretKey.value;
            }
        },
        hideModal() {
            $('#' + this.modalId).modal('hide');
        },
        closeModal() {
            this.hideModal();
            this.$emit('closeModal')
        }
    },
}
</script>
