<template>
    <div class="saved-filter-wrapper mr-2">
        <div class="dropdown keep-inside-clicks-open">
            <button
                class="btn btn-filter"
                type="button"
                id="dropdownMenuButton"
                data-toggle="dropdown"
                :title="$t('saved_filter')"
                aria-haspopup="true"
                aria-expanded="false">
                <app-icon class="size-18" :name="'archive'"/>
            </button>
            <div class="dropdown-menu saved-filter-dropdown-menu">
                <div class="px-primary pt-primary">
                    <h6>{{ $t('select_form_previous_state') }}</h6>
                    <p class="font-size-90 text-muted text-justify">
                        {{ $t('filter_saved_instruction') }}
                    </p>
                </div>
                <div class="dropdown-search-result-wrapper custom-scrollbar">
                    <a v-for="(item, index) in tableFilterData"
                       class="dropdown-item saved-filter-item d-flex justify-content-between font-size-90"
                       href="#"
                       @click.prevent="selectFilter(item)">
                        <span>{{ item.filter_name }}</span>
                        <span @click.prevent="deleteFilter($event, item)">
                            <app-icon name="trash-2" class="size-12"/>
                        </span>
                    </a>
                </div>
                <a class="dropdown-item saved-filter-item font-size-90" href="#"
                   @click.prevent="openSaveFilter">
                    <app-icon class="size-14" name="plus"/>
                    {{ $t('save_current_filter_state') }}
                </a>
                <div class="p-primary border-top" :class="{'d-none': !isSaveFormActive}">
                    <label for="filterName">{{$t('filter_name')}}:</label>
                    <input
                        type="text"
                        id="filterName"
                        v-model="filterName"
                        autocomplete="off"
                        class="form-control"
                        @keydown.enter.prevent="filterSaved"
                    />
                    <small v-if="errorFrontend"
                           class="text-danger validation-error">
                        {{ errorFrontend }}
                    </small>
                    <div class="d-flex justify-content-end mt-2">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            @click.prevent="isSaveFormActive = false">
                            {{ $t('cancel') }}
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary ml-2"
                            @click.prevent="filterSaved">
                            {{ $t('save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import CoreLibrary from "../../../helpers/CoreLibrary";

export default {
    name: "SavedFilterList",
    extends: CoreLibrary,
    props: {
        tableId: String,
        filterKeys: Array,
    },
    data() {
        return {
            isSaveFormActive: false,
            filterName: '',
            tableFilterData: [],
            errorFrontend: '',
        }
    },
    created() {
        this.getTableFilterData();
    },
    methods: {
        getTableFilterData() {
            let url = 'admin/app/table-filter',
                query = {};
            query.params = {table_id: this.tableId};
            this.axiosGet(url, query).then(res => {
                this.tableFilterData = res.data;
            })
        },
        filterSaved() {
            this.errorFrontend = '';
            let queryObj = this.queryStringToObject(),
                filterObj = {};
            for (const [key, value] of Object.entries(queryObj)) {
                if (this.filterKeys.includes(key)) filterObj[key] = value;
            }
            let queryString = this.objectToQueryString(filterObj);

            if(!this.filterName) {
                this.errorFrontend = this.$t('this_field_is_required');
                return;
            }

            if(!queryString) {
                this.errorFrontend = this.$t('at_least_one_filter_should_have_selected');
                return;
            }

            let url = 'admin/app/table-filter',
                formData = {
                    filter_name: this.filterName,
                    filter_value: queryString,
                    table_id: this.tableId
                };

            this.axiosPost({url, data: formData})
                .then(res => {
                    this.$toastr.s(res.data.message);
                    this.getTableFilterData();
                    this.filterName = '';
                    this.isSaveFormActive = false
                })
                .catch(({response})=> {
                    let errorObj = response.data.errors,
                        key = Object.keys(errorObj)[0];
                    this.errorFrontend = errorObj[key][0];
                    this.$toastr.e(response.data.message);
                })
        },
        deleteFilter(e, item) {
            e.stopPropagation();
            this.axiosDelete(`admin/app/table-filter/${item.id}`)
                .then(res => {
                    this.$toastr.s(res.data.message);
                    this.getTableFilterData();
                })
        },
        selectFilter(item) {
            let pageTitle = document.title;
            window.history.pushState("", pageTitle, `?${item['filter_value']}`);
            location.reload();
        },
        openSaveFilter() {
            this.isSaveFormActive = true;
            setTimeout(() => {
                $(`#filterName`).focus();
            });
        }
    }
}
</script>

<style lang="scss">
.saved-filter-wrapper {
    .btn-filter {
        padding-left: .5rem;
        padding-right: .5rem;
    }

    .saved-filter-item {
        padding: 1rem 2rem 1rem 2rem !important;

        &:hover {
            color: #4466F2;
        }

        &:focus {
            background: transparent;
        }

        &.active {
            color: #4466F2;
        }
    }

    .saved-filter-dropdown-menu {
        min-width: 378px;
        margin-top: 5px;

        .dropdown-search-result-wrapper {
            max-height: 190px;
            overflow-y: scroll;
        }
    }
}

</style>
