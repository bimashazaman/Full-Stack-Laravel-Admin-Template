<template>
    <div class="dropdown-menu-filter mr-2">
        <div class="dropdown keep-inside-clicks-open">
            <button class="btn menu-filter-btn"
                    :class="!isUndefined(activeOption) ? 'text-primary': ''"
                    type="button"
                    :id="'dropdownMenuButton-segments-'+filterId"
                    data-toggle="dropdown">
                {{ !isUndefined(activeOption) ? activeOption[listValueField] : label }}
                <img :src="getAppUrl('images/chevron-down.svg')" alt="">
            </button>
            <div :class="'dropdown-menu '+filterId"
                 :aria-labelledby="'dropdownMenuButton-segments-'+filterId">
                <div class="btn-dropdown-close d-sm-none">
                    <span class="title">
                        {{ !isUndefined(activeOption) ? activeOption[listValueField] : label }}
                    </span>
                    <span class="back float-right"
                          @click.prevent="closeDropDown">
                        <app-icon :name="'x'"/>
                    </span>
                </div>
                <div class="custom-scrollbar" ref="optionList">
                    <a v-for="(item, index) in list"
                       :key="index+(item.icon ? item.icon : '')"
                       href="#"
                       class="dropdown-item"
                       :class="{'active disabled': item.id == activeId}"
                       @click.prevent="selectedItem(item.id, $event)">
                        <app-icon
                            v-if="item.icon"
                            :name="item.icon"
                            class="mr-2 pb-1 size-20"
                        />
                        {{ item[listValueField] }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {FilterMixin} from './mixins/FilterMixin';
import CoreLibrary from "../../helpers/CoreLibrary";

export default {
    name: "DropdownMenuFilter",
    mixins: [FilterMixin],
    extends: CoreLibrary,
    props: {
        list: {
            type: Array,
            require: true
        },
        label: {
            type: String,
            default: function () {
                return this.$t('select_an_option');
            }
        },
        listValueField: {
            type: String,
            default: 'value'
        }
    },
    data() {
        return {
            activeId: null,
            defaultActiveId: this.default ? this.default : null
        }
    },
    computed: {
        activeOption() {
            return this.list.find((item) => item.id == this.activeId);
        }
    },
    watch: {
        active: {
            handler: function (value) {
                if (value) {
                    this.activeId = value;
                }
            },
            immediate: true
        }
    },
    mounted() {
        this.$hub.$on('clearAllFilter-' + this.tableId, () => {
            this.clearAndInitiate();
        });
    },
    methods: {
        selectedItem(id, e) {
            this.activeId = id;
            this.returnValue(id);
            if (e) {
                $('.dropdown-menu-filter .' + this.filterId).removeClass('show');
            }
        },
        clearAndInitiate() {
            /*if (this.defaultActiveId) this.activeId = this.defaultActiveId;
            else*/ this.activeId = null
        }
    }
}
</script>
