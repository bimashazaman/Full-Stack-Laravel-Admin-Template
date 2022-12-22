<template>
    <smart-select
        key="smart-select-filter"
        :data="{...$props, list, loader: settings.loader}"
        :isLoading="isLoading"
        :loader='loader'
        searchAndSelect
        v-model="searchAndSelect"
        @input="changed"
        @search="searchAndSet"
        @add-more="addMore"
        :multiple="settings.multiple"
    />
</template>

<script>
import {FilterMixin} from './mixins/FilterMixin';
import SmartSelect from "../input/SmartSelect";
import coreLibrary from '../../helpers/CoreLibrary'
export default {
    name: "SearchAndSelectFilter",
    mixins: [FilterMixin, coreLibrary],
    components: {SmartSelect},
    props: {
        id: {
            type: String,
            default: 'drop-down-filter'
        },
        label: {
            type: String,
            default: ""
        },
        listValueField: {
            type: String,
            default: 'value'
        },
        settings: {
            type: Object,
            default: {}
        },
        loader: {
            type: String,
            default: 'app-overlay-loader'
        }
    },
    data() {
        return {
            searchAndSelect: null,
            initialActiveId: null,
            list: [],
            isLoading: false,
            pageNumber: 1,
            noMoreData: false,
            queryName: this.settings.queryName || 'search',
            per_page: this.settings.per_page || 10,
            value: this.settings.multiple ? [] : ''
        }
    },
    mounted() {
        this.searchAndSet('');
        this.$hub.$on('clearAllFilter-' + this.tableId, () => {
            this.searchAndSelect = /*this.initialActiveId ? this.initialActiveId :*/ null;
        });
    },
    methods: {
        changed(value) {
            this.returnValue(value.toString());
        },
        addToList(searchText){
            let url = `${this.settings.url}?${this.queryName}=${searchText}&` + new URLSearchParams({ page: this.pageNumber, per_page: this.per_page, ...this.settings.params});
            this.isLoading = true;
            fetch(url).then(res => {
                if(res.ok){
                    return res.json()
                }
            }).then(res => {
                if (this.settings.modifire) this.list = [...this.list , ...res.data.map(item => this.settings.modifire(item))]
                else this.list = [...this.list, ...res.data]
                this.isLoading = false;
                this.noMoreData = res.data.length < this.settings.per_page
            })
        },
        searchAndSet(searchText){
            this.noMoreData = false;
            this.pageNumber = 1;
            this.list = [];
            this.addToList(searchText);
        },
        addMore(searchText){
            if(!this.noMoreData){
                this.pageNumber = this.pageNumber+1;
                this.addToList(searchText);
            }
        }
    },
    watch: {
        active: {
            handler: function (active) {
                this.searchAndSelect = isNaN(Number(active)) ? active : Number(active);
            },
            immediate: true
        }
    }
}
</script>
