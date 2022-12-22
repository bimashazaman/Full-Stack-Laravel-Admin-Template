<template>
    <div>
        <form ref="form" data-url="#">
            <div class="form-group row align-items-center">
                <label for="inputs_email" class="col-sm-2 mb-0">{{ $t('email') }}</label>
                <app-input id="inputs_email"
                           class="col-sm-7"
                           type="email"
                           v-model="inputs.email"
                           :placeholder="$t('email_type_input')"
                           :required="true"
                />
            </div>
            <div class="form-group row align-items-center">
                <label for="inputs_phone" class="col-sm-2 mb-0">{{ $t('contact_number') }}</label>
                <app-input
                    id="inputs_phone"
                    class="col-sm-7"
                    type="tel-input"
                    v-model="inputs.phone"
                    :placeholder="$t('type_contact_number')"
                />
            </div>
            <br>
            <div class="form-group mt-3">
                <div class="row">
                    <div class="col-12">
                        <button
                            type="button"
                            @click.prevent="goBack"
                            class="btn btn-secondary mr-2">
                            {{ $t('back') }}
                        </button>
                        <button
                            type="button"
                            @click.prevent="goNext"
                            class="btn btn-primary">
                            {{ $t('next') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import {FormMixin} from "../../../../../../core/mixins/form/FormMixin";

export default {
    mixins: [FormMixin],
    props: {
        dataCarry: {}
    },
    data() {
        return {
            inputs: {
                email: '',
                phone: ''
            }
        }
    },
    methods: {
        goBack() {
            this.$emit('back', true);
        },
        goNext() {
            this.fieldStatus = {};
            this.fieldStatus.isSubmit = true;
            if (this.isValidForm()) {
                this.$emit('next', true, this.inputs);
            }
        }
    }
}
</script>
ß
