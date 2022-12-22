<template>
    <div class="dropdown-search-select tag-manager">
        <div class="search-filter-dropdown" :class="{'disabled': disabled}">
            <div class="dropdown dropdown-with-animation keep-inside-clicks-open">
                <div id="dropdownMenuLink"
                     class="p-2 chips-container custom-scrollbar"
                     data-toggle="dropdown"
                     @click="startNavigation">
                    <app-overlay-loader v-if="tagPreloader"/>
                    <template v-else>
                        <span class="chips d-inline-flex align-items-center justify-content-center mr-2 mb-2"
                              v-for="(chip, index) in tags"
                              :style="'background-color: '+chip[colorValueField]"
                              :key="index">
                            <span class="chips-label">{{ chip[listValueField] }}</span>
                            <span class="delete-chips d-inline-flex align-items-center justify-content-center"
                                  @click.prevent="deleteChips($event, chip)">
                                <app-icon name="x" class="size-14"/>
                            </span>
                        </span>
                        <span class="d-inline-block add">+ {{ placeholder ? placeholder : $t('add') }}</span>
                    </template>
                </div>
                <div class="dropdown-menu chips-dropdown-menu radius-15 py-0" aria-labelledby="dropdownMenuLink">
                    <div class="form-group form-group-with-search">
                        <span class="form-control-feedback">
                            <app-icon name="search" class="size-20"/>
                        </span>
                        <input
                            type="text"
                            ref="searchInput"
                            class="form-control"
                            v-model="searchValue"
                            :autofocus="startNavigation"
                            @keydown.up="navigateUp"
                            @keydown.down="navigateDown"
                            @keydown.enter.prevent="enterSelectedValue"
                        />
                        <div v-if="!options.length && searchValue !== ''"
                             class="color-picker-wrapper"
                             :title="$t('pick_a_color')">
                            <div class="input-color-container">
                                <input
                                    id="input-color"
                                    type="color"
                                    v-model="color"
                                    class="input-color"
                                />
                                <label class="input-color-label" for="input-color">
                                    <app-icon name="palette" :style="'color: '+color"/>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div v-if="!options.length && searchValue !== ''"
                         class="animate__animated animate__fadeIn d-flex flex-wrap align-items-center justify-content-between px-primary mb-3">
                        <span class="text-truncate chip" :style="'background-color: '+color">
                            <span class="chips-label">{{ searchValue }}</span>
                        </span>
                        <a href="#"
                           class="default-base-color rounded-circle width-27 height-27 d-flex align-items-center justify-content-center"
                           @click.prevent="randomColor">
                            <app-icon name="refresh-ccw" class="text-primary size-15"/>
                        </a>
                    </div>
                    <div class="dropdown-divider my-0"/>
                    <div class="dropdown-search-result-wrapper custom-scrollbar" ref="optionList">
                        <a href="#"
                           class="dropdown-item d-flex align-items-center justify-content-between"
                           :class="{'active': index === activeIndex, 'selected disabled': isChipSelected(option.id), 'disabled': option.disabled}"
                           v-for="(option, index) in options"
                           :key="index"
                           @click.prevent="addChips(option)">
                            <span class="d-flex align-items-center">
                                <span class="width-20 height-20 d-inline-block rounded-circle mr-2"
                                      :style="'background-color: '+option[colorValueField]"/>
                                {{ option[listValueField] }}
                            </span>
                            <span class="check-sign">
                                <app-icon name="check" class="menu-icon"/>
                            </span>
                        </a>
                        <div v-if="!options.length" class="text-center text-muted text-size-13 py-primary">
                            <span v-html="hintText"/>
                        </div>
                    </div>
                    <button v-if="searchValue === '' && (list.length > loadedIndex)"
                            class="btn btn-light btn-sm btn-block"
                            @click.prevent="loadMoreTags($event)">
                        <span v-if="moreTagLoading">
                            <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>
                            {{`${$t('loading')}...`}}
                        </span>
                        <span v-else>{{$t('load_more')}}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CoreLibrary from "../../helpers/CoreLibrary";
import {NavigationMixin} from "../input/mixin/NavigationMixin";
import {FilterCloseMixin} from "../filter/mixins/FilterCloseMixin";

export default {
    name: "TagManager",
    extends: CoreLibrary,
    mixins: [NavigationMixin, FilterCloseMixin],
    props: {
        tags: {
            type: Array,
            default: function () {
                return []
            }
        },
        list: {
            type: Array,
            default: function () {
                return [];
            }
        },
        colorValueField: {
            type: String,
            default: 'color'
        },
        listValueField: {
            type: String,
            default: 'value'
        },
        tagPreloader: {
            type: Boolean,
            default: false
        },
        placeholder: {
            type: String,
            default: ''
        },
        disabled: {
            type: Boolean,
            default: false
        },
        loadedPerTime: {
            type: Number,
            default: 50
        }
    },
    data() {
        return {
            searchValue: '',
            color: '',
            loadedIndex: this.loadedPerTime,
            moreTagLoading: false
        }
    },
    computed: {
        options() {
            this.activeIndex = -1;
            if (this.searchValue) {
                return this.list.filter(option => {
                    return option[this.listValueField].toLowerCase().includes(this.searchValue.toLowerCase());
                });
            } else return this.list.slice(0, this.loadedIndex);
        },
        /*selectedOptions() {
            let chipsList = [];
            this.tags.forEach(item => {
                chipsList.push(this.list.find(chip => chip.id === item))
            })
            return chipsList;
            // return this.list.filter(item => this.tags.includes(item.id));
        },*/
        hintText() {
            let validText = !this.list.length ? this.$t('no_options_found') : (!this.options.length ? this.$t('did_not_match_anything') : '');
            return `${validText} <br/><span class="text-size-13 default-font-color">${this.$t('enter_to_add_new')}</span>`
        }
    },
    methods: {
        randomColor() {
            let letters = '0123456789ABCDEF', generatedColor = '#';
            for (let i = 0; i < 6; i++) {
                generatedColor += letters[Math.floor(Math.random() * 16)];
            }
            this.color = generatedColor;
        },
        enterSelectedValue() {
            if (this.searchValue !== '' && this.activeIndex < 0) {
                let tag = {};
                tag[this.listValueField] = this.searchValue;
                tag[this.colorValueField] = this.color;
                this.$emit('storeTag', tag);
                this.searchValue = '';
            }

            this.options.filter((option, index) => {
                if (index === this.activeIndex && (!option.disabled || this.isUndefined(option.disabled))) {
                    this.addChips(option)
                }
            });

            this.endNavigation();
            this.randomColor();
        },
        isChipSelected(value) {
            return this.tags.map(i => i.id).includes(value);
        },
        addChips(chip) {
            this.closeDropDown();
            if (this.tags.includes(chip.id)) this.$emit('detachTag', chip);
            else this.$emit('attachTag', chip);
        },
        deleteChips(event, chip) {
            event.stopPropagation();
            this.closeDropDown();
            this.$emit('detachTag', chip);
        },
        loadMoreTags(e) {
            e.stopPropagation();
            this.moreTagLoading = true;
            this.loadedIndex += this.loadedPerTime;
            setTimeout(()=> {
                this.moreTagLoading = false;
            }, 500)
        },
    },
    mounted() {
        this.randomColor();
        $('.dropdown').on('hide.bs.dropdown', () => {
            this.searchValue = ''
            this.loadedIndex = this.loadedPerTime;
        });
    }
}
</script>
