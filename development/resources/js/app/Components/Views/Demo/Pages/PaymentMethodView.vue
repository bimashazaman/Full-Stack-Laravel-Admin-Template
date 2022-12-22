<template>
    <div class="content-wrapper">

        <app-breadcrumb :page-title="$t('payment_method')" :directory="$t('sample_pages')" :icon="'copy'"/>

        <div class="card card-with-shadow border-0">

            <div class="card-header p-primary bg-transparent">
                <h5 class="mb-0"> Payment methods</h5>
            </div>

            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <p class="mb-0">Stripe</p>
                    <a v-if="isSetStripe" href="/stripe" class="btn btn-primary">Pay</a>
                    <app-note v-else content-type="html"
                              :show-title="false"
                              notes="Configure your stripe setting first!"/>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <p class="mb-0">Paypal</p>
                    <a href="/create-payment" v-if="isSetPaypal" class="btn btn-primary">Pay</a>
                    <app-note v-else content-type="html"
                              :show-title="false"
                              notes="Configure your paypal setting first!"/>
                </div>

            </div>

        </div>
    </div>
</template>

<script>
import {FormMixin} from "../../../../../core/mixins/form/FormMixin";
import {axiosGet} from "../../../../Helpers/AxiosHelper";
import {GET_STRIPE_STATUS, GET_PAYPAL_STATUS} from "../../../../Config/ApiUrl";

export default {
    name: "PaymentMethodView",
    mixins: [FormMixin],
    props: {
        message: {
            type: String,
            default: '',
        }
    },
    data() {
        return {
            isSetStripe: false,
            isSetPaypal: false,
        }
    },
    created() {
        this.getStripeStatus();
        this.getPaypalStatus();
        this.successMessage();
    },
    methods: {
        getStripeStatus() {
            axiosGet(GET_STRIPE_STATUS).then(({data}) => {
                this.isSetStripe = data.isSetStripe
            });
        },
        getPaypalStatus() {
            axiosGet(GET_PAYPAL_STATUS).then(({data}) => {
                this.isSetPaypal = data.isSetPaypal
            });
        },
        successMessage() {
            this.message ? this.$toastr.s(this.message) : '';
        }

    }
}
</script>
