<template>
    <div class="single-filter search-filter-dropdown">
        <div class="dropdown dropdown-with-animation"
             :class="[{'disabled':data.disabled}, `dropdown-${inputFieldId}`]">
            <div :id="inputFieldId"
                 data-toggle="dropdown"
                 aria-haspopup="true"
                 aria-expanded="false">
                <input
                    type="text"
                    class="form-control cursor-pointer"
                    :placeholder="data.placeholder"
                    :disabled="data.disabled"
                    @click="startNavigation"
                    :value="selectedValue ? selectedValue[data.listValueField] : ''">
            </div>

            <div class="dropdown-menu py-0 my-1"
                 :class="data.listClass"
                 :aria-labelledby="inputFieldId">
                <div class="form-group form-group-with-search">
                    <span class="form-control-feedback">
                        <app-icon name="search"/>
                    </span>
                    <input
                        type="text"
                        ref="searchInput"
                        :class="'form-control '+data.listItemInputClass"
                        :placeholder="$t('search')"
                        v-model="searchValue"
                        @input="getSearchValue($event)"
                        @keydown.down="navigateDown"
                        @keydown.up="navigateUp"
                        @keydown.enter.prevent="enterSelectedValue"
                        :autofocus="startNavigation">
                </div>
                <div class="dropdown-divider my-0"/>
                <div class="dropdown-search-result-wrapper custom-scrollbar min-height-200"
                     ref="optionList" :class="{'loading-opacity': moreTagLoading}">
                    <app-overlay-loader v-if="moreTagLoading"/>
                    <a class="dropdown-item"
                       href="#"
                       v-for="(item,index) in options"
                       :class="{'active': index === activeIndex, [data.listItemClass]:
                       !isUndefined(data.listItemClass), 'selected': item.id === value.id, 'disabled': item.disabled}"
                       @click.prevent="changeSelectedValue(item)"
                       :key="`${inputFieldId}-${index}`">
                        {{ item[data.listValueField] }}
                        <span class="check-sign float-right">
                            <app-icon name="check" class="menu-icon"/>
                        </span>
                    </a>
                    <div v-if="!options.length && !moreTagLoading"
                         class="text-center text-muted text-size-13 py-primary">
                        {{ $t('did_not_match_anything') }}
                    </div>
                </div>
                <button
                    v-if="options.length < totalRow"
                    class="btn btn-light btn-sm btn-block"
                    @click.prevent="loadMoreTags($event)">
                        <span v-if="moreTagLoading">
                            <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>
                            {{ `${$t('loading')}...` }}
                        </span>
                    <span v-else>{{ $t('load_more') }}</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import {InputMixin} from './mixin/InputMixin.js';
import {NavigationMixin} from "./mixin/NavigationMixin";
import CoreLibrary from "../../helpers/CoreLibrary";

export default {
    name: "AdvanceSearchSelect",
    mixins: [InputMixin, NavigationMixin],
    extends: CoreLibrary,
    data() {
        return {
            selectedValue: '',
            showTitle: '',
            searchValue: '',
            perPage: this.data.loadedPerTime,
            activePage: 1,
            moreTagLoading: false,
            totalRow: 0,
            options: [],
            getSearchValue: _.debounce(()=> {
                this.afterSearch()
            }, 500)
        }
    },
    mounted() {
        $(`.dropdown-${this.inputFieldId}`).on('hide.bs.dropdown', () => {
            this.resetState();
        });
        $(`.dropdown-${this.inputFieldId}`).on('show.bs.dropdown', () => {
            this.searchValue = ''
            this.getOptions();
        });
        this.activeIndex = -1;
    },
    watch: {
        value: {
            handler: function (data) {
                this.selectedValue = data
            },
            immediate: true
        }
    },
    methods: {
        resetState() {
            this.perPage = this.data.loadedPerTime;
            this.activePage = 1;
            this.totalRow = 0;
            this.options = [];
        },
        afterSearch() {
            this.resetState();
            this.getOptions();
        },
        getOptions() {
            if(!this.data.fetchUrl) {
                console.warn('Url(fetchUrl:String) required for advance search select!');
                return false;
            }
            let queryObj = {
                params: {
                    search: this.searchValue,
                    page: this.activePage,
                    per_page: this.perPage
                }
            }
            this.moreTagLoading = true;
            this.axiosGet(this.data.fetchUrl, queryObj)
                .then(res => {
                    this.options = [...this.options, ...res.data.data];
                    this.totalRow = Number(res.data.total);
                    this.activePage = Number(res.data.current_page);
                    this.moreTagLoading = false;
                })
        },
        changed() {
            this.$emit('input', this.selectedValue);
        },
        changeSelectedValue(value) {
            this.selectedValue = value;
            this.searchValue = '';
            this.navigationStart = false;
            this.changed();
        },
        enterSelectedValue() {
            this.options.filter((item, index) => {
                if (index == this.activeIndex) {
                    this.changeSelectedValue(item)
                }
            });
            this.endNavigation();
        },
        loadMoreTags(e) {
            e.stopPropagation();
            this.activePage++;
            this.getOptions();
        },
    }
}
</script>
