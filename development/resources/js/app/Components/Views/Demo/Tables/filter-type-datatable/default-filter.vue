<template>
    <div class="mb-primary col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <h5 class="mb-3">{{ $t('default_filter') }}</h5>
        <app-table :id="'default-filter-table'" :options="options"/>
    </div>
</template>

<script>

    import * as actions from "../../../../../Config/ApiUrl";
    import {TableHelpers} from "../mixins/TableHelpers";
    import CoreLibrary from "../../../../../../core/helpers/CoreLibrary";

    export default {
        name: "default-filter",
        mixins: [TableHelpers],
        extends: CoreLibrary,
        data() {
            return {
                options: {
                    name: this.$t('default_filter'),
                    url: actions.DATATABLE_DATA,
                    showHeader: true,
                    showCount: true,
                    showClearFilter: true,
                    columns: [],
                    filters: [
                        {
                            "title": this.$t('date'),
                            "type": "range-picker",
                            "key": "date",
                            "option": ["today", "thisMonth", "last7Days", "nextYear"]
                        },
                        {
                            "title": this.$t('age'),
                            "type": "range-filter",
                            "key": "age-range",
                            "maxTitle": this.$t('max_age'),
                            "minTitle": this.$t('min_age'),
                            "sign": ""
                        },
                        {
                            "title": this.$t('status'),
                            "type": "checkbox",
                            "key": "status",
                            "option": [
                                {id: 'active', value: 'Active'},
                                {id: 'inactive', value: 'Inactive'},
                                {id: 'invited', value: 'Invited'},
                            ],
                        },
                        {
                            title: "Serverside Search and Select",
                            type: "search-and-select-filter",
                            key: "serverside_select",
                            settings: {
                                url: actions.DATATABLE_SERVER_SEARCH_SELECT, // this url will hit every search action
                                modifire: (v) => {
                                    return { id: v.id, name: v.name }
                                },
                                per_page: 10, // default 10, you can change it any number. min 10 encourage to use
                                queryName: 'search_selectable', // default 'search', this key will use for query build link '../endpoind?last_name=shi&moreparam...'
                                loader: 'app-pre-loader', // default app-overlay-loder
                                multiple: true, // default false, if you need to select multiple item so make it true
                                params: {
                                    'type': 'type1',
                                    'isWanted': true
                                } // params object will be appended with your url after search param like '../endpoint?last_name=shishir&type=type1&isWanted=true'
                            },
                            listValueField: 'name'
                        },
                        {
                            "title": this.$t('gender'),
                            "type": "radio",
                            "key": "filter-with-gender",
                            "option": [
                                {id: 'male', value: 'Male'},
                                {id: 'female', value: 'Female'},
                                {id: 'other', value: 'Other'},
                            ],

                            "header": {
                                "title": this.$t('want_to_filter_your_list'),
                                "description": this.$t('you_can_filter_your_data_table_which_are_created_based_on_gender'),
                            },
                        }
                    ],
                    paginationType: "pagination",
                    responsive: true,
                    rowLimit: 10,
                    orderBy: 'desc',
                    showAction: false,
                    actions: [],
                },
            }
        },
        created() {
            this.options.columns = [...this.tableColumns];
            this.searchAndSelectFilterOptions();
        },
        methods: {
            searchAndSelectFilterOptions() {
                this.axiosGet(actions.DATATABLE_SEARCH_SELECT).then(response => {
                    this.options.filters.push({
                        "title": this.$t('search_and_select'),
                        "type": "drop-down-filter",
                        "key": "search select",
                        "option": [] = response.data.map(name => {
                            return {
                                id: name.name,
                                value: name.name
                            }
                        })
                    })
                });
            }
        }
    }
</script>
