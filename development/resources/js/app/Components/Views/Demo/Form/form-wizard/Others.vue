<template>
    <div>
        <form ref="form" data-url="#">
            <div class="form-group row align-items-center">
                <label for="inputs_status" class="col-sm-2 mb-0">{{ $t('status') }}</label>
                <app-input
                    id="inputs_status"
                    class="col-sm-7"
                    type="select"
                    :required="true"
                    v-model="inputs.status"
                    :list="statusLists"
                />
            </div>
            <div class="form-group row align-items-center">
                <label class="col-sm-2 mb-0">{{ $t('gender') }}</label>
                <app-input
                    id="inputs_gender"
                    class="col-sm-7"
                    type="radio"
                    v-model="inputs.gender"
                    :list="genderLists"/>
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
                            @click.prevent="saveData"
                            class="btn btn-primary width-100 height-34">
                            <span v-if="preloader">
                                <app-pre-loader spinner-class="width-19 height-19"/>
                            </span>
                            <span v-else>
                                {{ $t('submit') }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import {FormMixin} from "../../../../../../core/mixins/form/FormMixin";
import {DATATABLE_DATA} from "../../../../../Config/ApiUrl";

export default {
    mixins: [FormMixin],
    props: {
        dataCarry: {}
    },
    data() {
        return {
            preloader: false,
            inputs: {
                status: '',
                gender: 'male'
            },
            statusLists: [
                {id: '', value: "Choose One",},
                {id: 'active', value: "Active",},
                {id: 'inactive', value: "Inactive",},
                {id: 'invited', value: "Invite",},
            ],
            genderLists: [
                {id: 'male', value: "Male"},
                {id: 'female', value: "Female"},
                {id: 'other', value: "Other"},
            ],
        };
    },
    methods: {
        goBack() {
            this.$emit("back", true);
        },
        saveData() {
            this.fieldStatus = {};
            this.fieldStatus.isSubmit = true;
            if (this.isValidForm()) {
                this.preloader = true;
                let url = DATATABLE_DATA,
                    data = {
                        ...this.dataCarry,
                        ...this.inputs
                    }
                this.axiosPost({url, data})
                    .then(res => {
                        this.$toastr.s(res.data.message);
                        location.reload();
                    })
                    .catch(({response}) => {
                        this.$toastr.e(response.data.message);
                        this.preloader = false;
                    })
            }
        }
    },
};
</script>
