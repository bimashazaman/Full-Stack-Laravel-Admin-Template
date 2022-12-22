<template>
    <div>
        <form ref="form" data-url="#">
            <div class="form-group row align-items-center">
                <label for="inputs_name" class="col-sm-2 mb-0">{{ $t('name') }}</label>
                <app-input
                    id="inputs_name"
                    class="col-sm-7"
                    type="text"
                    v-model="inputs.name"
                    :placeholder="$t('text_type_input')"
                    :required="true"
                />
            </div>
            <div class="form-group row align-items-center">
                <label class="col-sm-2 mb-0">{{ $t('age') }}</label>
                <app-input
                    id="inputs_age"
                    class="col-sm-7"
                    type="number"
                    :max-number="100"
                    v-model="inputs.age"
                    :placeholder="$t('type_age')"
                />
            </div>
            <br>
            <div class="form-group mt-3">
                <div class="row">
                    <div class="col-12">
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
                name: "",
                age: "",
            },
        };
    },
    methods: {
        goNext() {
            this.fieldStatus = {};
            this.fieldStatus.isSubmit = true;
            if (this.isValidForm()) {
                this.$emit('next', true, this.inputs);
            }
        },
    },
};
</script>
