<template>
    <div class="single-filter search-filter-dropdown">
        <div class="dropdown" :class="{'disabled':data.disabled}">
            <a href="#"
               class="btn btn-filter px-3"
               :class="{'applied': value}"
               :id="inputFieldId"
               @click="startNavigation"
               data-toggle="dropdown"
               aria-haspopup="true"
               aria-expanded="false">
                {{ data.label }}
                <span v-if="data.visibleValue">{{ selectedValue }}</span>
                <span class="clear-icon" v-if="value" @click.prevent="clear">
                    <app-icon :name="'x'"/>
                </span>
            </a>

            <div class="dropdown-menu py-0" :class="data.listClass" :aria-labelledby="inputFieldId">
                <div class="btn-dropdown-close d-sm-none">
                    <span class="title">
                        {{ data.label }}
                    </span>
                    <span class="back float-right" @click.prevent="closeDropDown">
                        <app-icon name="x"/>
                    </span>
                </div>
                <div class="form-group form-group-with-search">
                    <span class="form-control-feedback">
                        <app-icon name="search"/>
                    </span>
                    <input type="text"
                           ref="searchInput"
                           :class="'form-control '+data.listItemInputClass"
                           :placeholder="$t('search')"
                           v-bind:value="searchValue"
                           @input="getSearchValue($event)"
                           @keydown.down="navigateDown"
                           @keydown.up="navigateUp"
                           @keyup="search"
                           @keydown.enter.prevent="enterSelectedValue"
                           :autofocus="startNavigation">
                </div>
                <div class="dropdown-divider my-0"/>
                <div class="dropdown-search-result-wrapper custom-scrollbar position-relative" ref="optionList" @scroll="loadMore">
                    <template>
                        <a class="dropdown-item"
                           href="#"
                           v-for="(item,index) in options"
                           :class="{'active':index==activeIndex, [data.listItemClass]: !isUndefined(data.listItemClass),'selected': multiple ? (value||[]).includes(item.id) : item.id === value, 'disabled':item.disabled}"
                           @click.prevent="changeSelectedValue(item.id)"
                           :key="`${inputFieldId}-${index}`">
                            {{ item[data.listValueField] }}
                            <span class="check-sign float-right">
                 <app-icon name="check" class="menu-icon"/>
              </span>
                        </a>
                    </template>
                    <div v-if="searchAndSelect" style="min-height: 30px;">
                        <div v-if="isLoading" class="dropdown-item position-relative">
                            <app-pre-loader v-if="data.loader === 'app-pre-loader'" />
                            <app-overlay-loader v-else />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {InputMixin} from './mixin/InputMixin.js';
import CoreLibrary from "../../helpers/CoreLibrary";
import {FilterCloseMixin} from "../filter/mixins/FilterCloseMixin";
import {NavigationMixin} from "./mixin/NavigationMixin";
export default {
    name: "SmartSelect",
    mixins: [InputMixin, FilterCloseMixin, NavigationMixin],
    extends: CoreLibrary,
    data() {
        return {
            selectedValue: this.multiple ? [] : '',
            searchValue: ""
        }
    },
    props: {
        isLoading: {
            type: Boolean,
            default: false
        },
        searchAndSelect: {
            type: Boolean,
            default: false
        },
        multiple: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        options() {
            this.activeIndex = -1;
            if (!_.isEmpty(this.searchValue) && !this.searchAndSelect) {
                return this.data.list.filter(item => {
                    return item[this.data.listValueField].toLowerCase().includes(this.searchValue.toLowerCase());
                });
            } else return this.data.list;
        }
    },
    methods: {
        getSearchValue($event) {
            this.searchValue = $event.target.value;
        },
        changed() {
            this.$emit('input', this.selectedValue);
        },
        changeSelectedValue(value) {
            if (this.multiple) {
                this.selectedValue = this.selectedValue.includes(value) ? this.selectedValue.filter(i => i !== value) : [...this.selectedValue, value];
                this.searchValue = "";
                this.navigationStart = false;
                this.changed();
            } else {
                this.selectedValue = value;
                this.searchValue = "";
                this.navigationStart = false;
                this.changed();
            }
        },
        enterSelectedValue() {
            this.options.filter((item, index) => {
                if (index == this.activeIndex) {
                    this.changeSelectedValue(item.id)
                }
            });
            this.endNavigation();
        },
        clear(event) {
            event.stopPropagation();
            this.selectedValue = '';
            this.changed();
            this.searchValue = '';
            this.$emit('search', '');
        },
        search: _.debounce(function (key) {
            let isAcceptable = key.which > 46 && key.which < 90;
            !isAcceptable ? isAcceptable = [32, 8, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105].find(k => k === key.which) : isAcceptable;
            isAcceptable && this.$emit('search', key.target.value);
        }, 500),
        loadMore: _.debounce(function (e) {
            if (this.searchAndSelect && !this.isLoading) {
                let scrollBar = e.target;
                let isReachBottom = (scrollBar.scrollTop + scrollBar.clientHeight >= scrollBar.scrollHeight - 20);
                isReachBottom && this.$emit('add-more', this.searchValue);
            }
        }, 500),
    }
}
</script>