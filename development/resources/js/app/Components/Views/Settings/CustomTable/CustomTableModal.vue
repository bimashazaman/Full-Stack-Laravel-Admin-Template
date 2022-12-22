<template>
    <app-modal
        modal-alignment="top"
        modal-id="custom-field-modal"
        modal-size="large"
        @close-modal="closeModal"
    >
        <template slot="header">
            <h5 class="modal-title">{{ selectedUrl ? $t('edit') : $t('add') }}</h5>
            <button aria-label="Close" class="close outline-none" data-dismiss="modal" type="button">
        <span>
          <app-icon :name="'x'"></app-icon>
        </span>
            </button>
        </template>
        <app-overlay-loader v-if="preloader"/>
        <template slot="body">
            <form ref="form" :class="{'loading-opacity': preloader}"
                  :data-url="selectedUrl ? selectedUrl : CUSTOM_TABLE">
                <div class="form-group">
                    <label>{{ $t('name') }}<span class="text-danger">*</span></label>
                    <app-input
                        v-model="formData.name"
                        :placeholder="$t('enter_custom_table_name')"
                        :error-message="$errorMessage(errors, 'name')"/>
                </div>

                <div class="form-group">
                    <label>{{ $t('context') }}<span class="text-danger">*</span></label>
                    <app-input
                        v-model="formData.context"
                        :list="[
                            { id: '', value: $t('chose_context'), disabled: true },
                            { id: 'datatable', value: $t('datatable') },
                        ]"
                        type="select"
                        :error-message="$errorMessage(errors, 'context')"
                    />
                </div>

                <div v-if="customErrorMessage" class="mb-3">
                    <span class="text-danger">{{ customErrorMessage }}</span>
                </div>

                <div class="row">
                    <div class="col-4 form-group">
                        <label>{{ $t('column_name') }}<span class="text-danger">*</span></label>
                        <app-input
                            v-model="customField.name"
                            :placeholder="$t('enter_column_name')"
                        />
                    </div>
                    <div class="col-4 form-group">
                        <label>{{ $t('custom_field_type') }}<span class="text-danger">*</span></label>
                        <app-input
                            v-model="customField.type"
                            :list="customFieldTypes"
                            list-value-field="translated_name"
                            type="select"
                            @input="setCustomFieldType($event)"
                        />
                    </div>
                    <div v-if="!custom_field_type.choose" class="col-4 d-flex align-items-center">
                         <span @click="addToTable">
                        <app-icon class="size-20 mr-2" name="plus"/>
                       </span>
                    </div>
                </div>

                <div v-if="custom_field_type.choose" class="row">
                    <div class="col-8 form-group">
                        <label class="d-block">{{ $t('options') }}<span class="text-danger">*</span></label>
                        <small class="text-muted">
                            {{ $t('use_comma_separated_values_as_options_of_the_field') }}
                        </small>
                        <app-input
                            v-model="customField.meta"
                            :placeholder="$t('custom_field_options')"
                            type="textarea"
                        />
                    </div>
                    <div class="col-4 d-flex align-items-center">
                       <span @click="addToTable">
                        <app-icon class="size-20 mr-2" name="plus"/>
                       </span>
                    </div>
                </div>

                <table class="table" v-if="formData.customFieldName.length">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">{{ $t('column_name') }}</th>
                        <th scope="col">{{ $t('field_type') }}</th>
                        <th scope="col">{{ $t('required') }}</th>
                        <th scope="col">{{ $t('options') }}</th>
                        <th scope="col">{{ $t('action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(field, index) in formData.customFieldName" :key="index">
                        <th scope="row">
                            <app-input
                                :id="`name${index}`"
                                v-model="formData.customFieldName[index]"
                                :placeholder="$t('enter_column_name')"
                                :required="true"
                            />
                        </th>
                        <td>
                            <app-input
                                v-model="formData.customFieldType[index]"
                                :list="customFieldTypes"
                                list-value-field="translated_name"
                                type="select"
                                @input="setTableCustomFieldType($event, index)"
                            />
                        </td>
                        <td class="d-flex align-items-center">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    v-model="formData.customFieldRequired[index]"
                                    type="checkbox"
                                />
                            </div>
                        </td>
                        <td>
                            <app-input
                                v-if="formData.customFieldMetaValue[index]"
                                v-model="formData.customFieldMeta[index]"
                                :placeholder="$t('use_comma_separated_values_as_options_of_the_field')"
                                type="textarea"
                                :required="true"
                            />
                        </td>
                        <td>
                            <a href="#" @click.prevent="removeSelectedField(index)">
                                <app-icon name="trash" class="text-primary mt-2" width="22"/>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </form>
        </template>
        <template slot="footer">
            <button
                class="btn btn-secondary mr-2"
                data-dismiss="modal"
                type="button"
                @click.prevent="closeModal"
            >{{ $t('cancel') }}
            </button>

            <app-submit-button @click="submitData" :loading="loading"></app-submit-button>

        </template>
    </app-modal>
</template>

<script>

import {mapGetters} from "vuex";
import {urlGenerator} from "../../../../Helpers/AxiosHelper";
import {FormMixin} from "../../../../../core/mixins/form/FormMixin";
import {CUSTOM_TABLE} from "../../../../Config/ApiUrl";

export default {
    name: "CustomFieldModal",
    mixins: [FormMixin],
    props: {
        tableId: String,
    },
    data() {
        return {
            urlGenerator,
            CUSTOM_TABLE,
            formData: {
                customFieldName: [],
                customFieldType: [],
                customFieldMeta: [],
                customFieldRequired: [],
                customFieldMetaValue: [],
            },
            custom_field_type: {context: "", meta: ""},
            errors: [],
            loading: false,
            preloader: false,
            customErrorMessage: '',
            customField: {},
        };
    },
    computed: {
        ...mapGetters(["customFieldTypeList"]),
        customFieldTypes() {
            return [{id: '', translated_name: "Select field type", disabled: true}, ...this.customFieldTypeList]
        }
    },
    methods: {
        beforeSubmit() {
            this.loading = true;
        },
        addToTable() {
            if (!this.customField.name || !this.customField.type || (this.custom_field_type.choose && !this.customField.meta)) {
                this.customErrorMessage = this.$t('please_fill_up_all_fields');
                return;
            }

            if (this.customField.name.match(/^(select)/i)) {
                this.customErrorMessage = this.$t("do_not_use_select_keyword");
                return;
            }

            this.customField.meta = this.sanitizeMetaData(this.customField.meta ? this.customField.meta : '');

            let index = this.formData.customFieldName.length ?? this.formData.customFieldName.length + 1;
            this.formData.customFieldName[index] = this.customField.name;
            this.formData.customFieldType[index] = this.customField.type;
            this.formData.customFieldMeta[index] = this.customField.meta;
            this.formData.customFieldMetaValue[index] = this.customField.meta ? 1 : 0;
            this.formData.customFieldRequired[index] = 1;

            this.customField = {};
            this.custom_field_type.choose = false;

        },
        removeSelectedField(index) {
            this.formData.customFieldName.splice(index, 1);
            this.formData.customFieldType.splice(index, 1);
            this.formData.customFieldMeta.splice(index, 1);
            this.formData.customFieldMetaValue.splice(index, 1);
            this.formData.customFieldRequired.splice(index, 1);
        },
        submitData() {
            let formData = {
                name: this.formData.name,
                context: this.formData.context,
                customFields: []
            };
            if (!this.formData.customFieldName.length) {
                this.customErrorMessage = this.$t("table_columns_are_required");
                return;
            }
            this.formData.customFieldName.forEach((name, index) => {
                formData.customFields[index] = {
                    name: name,
                    context: this.formData.context,
                    meta: this.formData.customFieldMeta[index],
                    custom_field_type_id: this.formData.customFieldType[index],
                    custom_field_type_name: this.collection(this.customFieldTypeList).find(
                        this.formData.customFieldType[index]
                    ).name,
                    required: this.formData.customFieldRequired[index] ? 1 : 0,
                    in_list: 1,
                }
            })
            this.save(formData)
        },

        beforeGetEditData() {
            this.$store.dispatch("getCustomFieldType");
            this.preloader = true;
        },
        afterSuccessFromGetEditData({data}) {
            this.formData = {...this.formData, ...data};
            this.formData.customFieldName = data.fields.map((field) => field.name);
            this.formData.customFieldType = data.fields.map((field) => field.custom_field_type_id);
            this.formData.customFieldMeta = data.fields.map((field) => field.meta);
            this.formData.customFieldMetaValue = data.fields.map((field) => field.meta ? 1 : 0);
            this.formData.customFieldRequired = data.fields.map((field) => Number(field.required));
            this.preloader = false;
        },

        afterError(response) {
            this.loading = false;
            if (response.data.errors) {
                this.errors = response.data.errors;
            } else {
                this.$toastr.e(response.data.message);
            }
        },

        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            // this.$hub.$emit("reload-" + this.tableId);
            this.closeModal();
            location.reload();
        },
        afterFinalResponse() {
            this.loading = false;
        },
        sanitizeMetaData(value) {
            let meta = value.split(',').map((el) => {
                return el.trim();
            }).join();
            return meta;
        },
        setCustomFieldType(custom_field_type_id) {
            this.customField.meta = "";
            this.custom_field_type = this.collection(this.customFieldTypeList).find(
                custom_field_type_id
            );
            this.custom_field_type.choose = ["select", "checkbox", "radio"].includes(
                this.custom_field_type.name
            );
        },
        setTableCustomFieldType(custom_field_type_id, index) {
            this.custom_field_type = this.collection(this.customFieldTypeList).find(
                custom_field_type_id
            );
            this.formData.customFieldMetaValue[index] = ["select", "checkbox", "radio"].includes(
                this.custom_field_type.name
            );
        },
        closeModal(value) {
            this.$emit("close-modal", value);
        },
    },

    mounted() {
        this.$store.dispatch("getCustomFieldType");
    },
    watch: {
        customField: {
            handler: function (value) {
                this.customErrorMessage = '';
            },
            deep: true
        }
    }
};
</script>


