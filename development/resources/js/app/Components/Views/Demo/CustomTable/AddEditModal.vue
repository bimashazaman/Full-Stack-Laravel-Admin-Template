<template>
    <app-modal modal-alignment="top" :modal-id="`dynamic-tab-add-edit-modal-${tableData.id}`"  modal-size="large" @close-modal="closeModal">
        <template slot="header">
            <h5 v-if="selectedUrl" class="modal-title">{{ $t("edit") + " " + $t(tableData.name) }}</h5>
            <h5 v-else class="modal-title">{{ $t("add") + ' ' + $t(tableData.name) }}</h5>
            <button aria-label="Close" class="close outline-none" @click="closeModal" type="button">
                <span><app-icon :name="'x'"></app-icon></span>
            </button>
        </template>
        <template slot="body">
            <app-overlay-loader v-if="preloader"/>
            <form v-if="formData.fields" :class="{'loading-opacity': preloader}" ref="form" :data-url="selectedUrl ? selectedUrl : 'admin/app/custom-table/values'">
                <div class="alert alert-warning text-dark" v-if="validationError">
                    <p class="m-0">{{ validationError }}</p>
                </div>
                <div v-for="(field, index) in tableData.fields" :key="`custom-form-item-${index}`" class="form-group row">
                    <label class="mb-0 col-sm-3 d-flex align-items-center">
                        {{ field.name }} <span class="text-danger" v-if="Number(field.required)">*</span>
                    </label>
                    <div class="col-sm-9">
                    <app-input
                        :field-name="field.name"
                        :radioCheckboxName="field.name"
                        v-model="formData.fields[index].value"
                        :placeholder="'Enter ' + field.name"
                        :list="genList(field)"
                        :type="field.custom_field_type.name"
                        :required="Number(field.required) ? true : false"/>
                    </div>
                </div>
            </form>
        </template>
        <template slot="footer">
            <button class="btn btn-secondary mr-2" data-dismiss="modal" type="button" @click.prevent="closeModal">
                {{ $t("cancel") }}
            </button>
            <button class="btn btn-primary" type="button" @click.prevent="submit">
                <span v-if="loading" class="w-100">
                    <span class="spinner-bounce">
                        <span class="bounce1 mr-1"/>
                        <span class="bounce2 mr-1"/>
                        <span class="bounce3 mr-1"/>
                    </span>
                </span>
                <template v-else>{{ $t("save") }}</template>
            </button>
        </template>
    </app-modal>
</template>

<script>
import {FormMixin} from "../../../../../core/mixins/form/FormMixin";
import {convertServerDateFormat} from "../../../../Helpers/DateTimeHelper";

export default {
    mixins: [FormMixin],
    props: ['tableData', 'tableId'],
    data() {
        return {
            loading: false,
            preloader: false,
            formData: {
                table_id: null,
                fields: []
            },
            validationError: '',
        }
    },
    created() {
        this.formData.table_id = this.tableData.id;
        this.formData.fields = this.tableData.fields.map(field => {
            return {custom_field_id: field.id, name: field.name, required: field.required, value: ''}
        })
    },
    methods: {
        genList(field) {
            return field.meta?.split(',').map((item, index) => {
                return {id: item, value: item}
            })
        },
        beforeGetEditData() {
            this.preloader = true;
        },
        beforeSubmit() {
            this.loading = true;
            this.tableData.fields.forEach((field, index) => {
                if (field.custom_field_type.name == 'date' && this.formData.fields[index].value){
                    this.formData.fields[index].value = convertServerDateFormat(this.formData.fields[index].value)
                }
            })
        },
        submit() {
            this.save(this.formData);
        },
        afterError(response) {
            this.loading = false;
            if (response.data.errors && response.status == 422) {
                this.validationError = this.$t('validation_error_message');
            } else {
                this.$toastr.e(response.data.message);
            }
        },
        afterSuccess(res) {
            this.$toastr.s(res.data.message);
            this.$hub.$emit('reload-' + this.tableId);
            this.validationError = '';
            this.closeModal();
        },
        afterFinalResponse() {
            this.loading = false;
        },
        afterSuccessFromGetEditData(response) {
            this.formData.fields = this.tableData.fields.map(field => {
                let fieldValue = response.data.field_values.find((value) => value.custom_field_id == field.id)?.value
                return {custom_field_id: field.id, name: field.name, required: field.required, value: fieldValue}
            })
            this.preloader = false;
        },
        closeModal(value) {
            this.$emit("close-modal", value);
        }
    }

}
</script>

<style>

</style>