<template>
    <nav>
        <ul class="pagination justify-content-center justify-content-md-end mb-0">
            <li class="d-flex align-items-center mr-3">
                <p class="text-muted mb-0 mr-2">{{ $t('go_to_page') }}</p>
                <input
                    :value="activePage"
                    type="text"
                    class="form-control width-50"
                    @keydown.enter.prevent="directGoToPage($event)">
            </li>
            <li :class="{'disabled': activePage <= 1}" class="page-item">
                <a class="page-link border-0"
                   href="#"
                   @click.prevent="prevArrow"
                   aria-label="Previous">
                    <span :key="'arrow-left'">
                        <app-icon
                            :name="'arrow-left'"
                            class="menu-arrow"
                        />
                    </span>
                </a>
            </li>

            <template v-if="isExtendedPagination">
                <template v-if="activePage <= 4">
                    <li v-for="(page, index) in 4"
                        :key="index"
                        class="page-item">
                        <a class="page-link border-0"
                           :class="{'active disabled': activePage === page}"
                           href="#"
                           @click.prevent="activated(page)">{{ page }}</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link border-0 jumping-step"
                           href="#"
                           :title="$t('next_five_pages')"
                           @click.prevent="nextFiveStep">
                            <span :key="'more-horizontal-start'">
                                <app-icon name="more-horizontal"/>
                            </span>
                            <span :key="'chevrons-right-start'">
                                <app-icon name="chevrons-right" class="jump-icon"/>
                            </span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link border-0"
                           href="#"
                           :class="{'active disabled': activePage === totalPage}"
                           @click.prevent="activated(totalPage)">
                            {{ totalPage }}
                        </a>
                    </li>
                </template>


                <template v-else-if="activePage > (totalPage - 4)">
                    <li class="page-item">
                        <a class="page-link border-0"
                           href="#"
                           :class="{'active disabled': activePage === 1}"
                           @click.prevent="activated(1)">
                            {{ 1 }}
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link border-0 jumping-step"
                           href="#"
                           :title="$t('previous_five_pages')"
                           @click.prevent="prevFiveStep">
                            <span :key="'more-horizontal-end'">
                                <app-icon name="more-horizontal"/>
                            </span>
                            <span :key="'chevrons-left-end'">
                                <app-icon name="chevrons-left" class="jump-icon"/>
                            </span>
                        </a>
                    </li>
                    <li v-for="(page, index) in 4"
                        :key="index"
                        class="page-item">
                        <a class="page-link border-0"
                           :class="{'active disabled': activePage === (page+endOffset)}"
                           href="#"
                           @click.prevent="activated(page+endOffset)">{{ page + endOffset }}</a>
                    </li>
                </template>


                <template v-else>
                    <li class="page-item">
                        <a class="page-link border-0"
                           href="#"
                           :class="{'active disabled': activePage === 1}"
                           @click.prevent="activated(1)">
                            {{ 1 }}
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link border-0 jumping-step"
                           href="#"
                           :title="$t('previous_five_pages')"
                           @click.prevent="prevFiveStep">
                            <span :key="'more-horizontal-middle-start'">
                                <app-icon name="more-horizontal"/>
                            </span>
                            <span :key="'chevrons-left-middle'">
                                <app-icon name="chevrons-left" class="jump-icon"/>
                            </span>
                        </a>
                    </li>

                    <li v-for="(page, index) in 5"
                        :key="index"
                        class="page-item">
                        <a class="page-link border-0"
                           :class="{'active disabled': activePage === (page+middleOffset)}"
                           href="#"
                           @click.prevent="activated(page+middleOffset)">{{ page + middleOffset }}</a>
                    </li>

                    <li class="page-item">
                        <a class="page-link border-0 jumping-step"
                           href="#"
                           :title="$t('next_five_pages')"
                           @click.prevent="nextFiveStep">
                            <span :key="'more-horizontal-middle-end'">
                                <app-icon name="more-horizontal"/>
                            </span>
                            <span :key="'chevrons-right-middle'">
                                <app-icon name="chevrons-right" class="jump-icon"/>
                            </span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link border-0"
                           href="#"
                           :class="{'active disabled': activePage === totalPage}"
                           @click.prevent="activated(totalPage)">
                            {{ totalPage }}
                        </a>
                    </li>
                </template>
            </template>

            <template v-else>
                <li v-for="(page, index) in paginationPages"
                    :key="index"
                    class="page-item">
                    <a class="page-link border-0"
                       :class="{'active disabled': activePage === page}"
                       href="#"
                       @click.prevent="activated(page)">{{ page }}</a>
                </li>
            </template>

            <li :class="{'disabled': activePage >= totalPage}" class="page-item">
                <a class="page-link border-0 align-content-center"
                   href="#"
                   aria-label="Next"
                   @click.prevent="nextArrow">
                    <app-icon
                        :name="'arrow-right'"
                        class="menu-arrow"
                    />
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    name: "AppPagination",
    props: {
        totalRow: {
            type: Number,
            require: true
        },
        rowLimit: {
            type: Number,
            require: true
        },
    },
    mounted() {
        this.$hub.$on('resetPaginationState', (value) => {
            setTimeout(()=> {
                this.activePage = value;
            });
        });
    },
    data() {
        return {
            activePage: 1,
            maxPage: 6,
        }
    },
    computed: {
        middleOffset() {
            return this.activePage - 3
        },
        endOffset() {
            return this.totalPage - 4
        },
        isExtendedPagination() {
            return this.totalPage > this.maxPage
        },
        totalPage: function () {
            return Math.ceil(this.totalRow / this.rowLimit);
        },
        paginationPages: function () {
            if (this.maxPage >= this.totalPage) {
                return this.totalPage
            } else {
                return this.maxPage;
            }
        }
    },
    methods: {
        activated(page) {
            this.activePage = page;
            this.sendSelectedPage();
        },
        nextArrow() {
            this.activePage++;
            this.sendSelectedPage();
        },
        prevArrow() {
            this.activePage--;
            this.sendSelectedPage();
        },
        nextFiveStep() {
            this.activePage = (this.activePage + 5) > this.totalPage ? this.totalPage : (this.activePage + 5)
            this.sendSelectedPage();
        },
        prevFiveStep() {
            this.activePage = (this.activePage - 5) < 1 ? 1 : (this.activePage - 5)
            this.sendSelectedPage();
        },
        sendSelectedPage() {
            this.$emit('submit', this.activePage);
        },
        directGoToPage(e) {
            let inputValue = Number(e.target.value) < 1 ? 1 : Number(e.target.value);
            this.activePage = inputValue > this.totalPage ? this.totalPage : inputValue;
            this.sendSelectedPage();
        },
    }
}
</script>

<style scoped lang="scss">
.form-control {
    padding: 5px 7px !important;
}
.jumping-step {
    .feather {
        &.jump-icon {
            display: none;
        }
    }
}
.jumping-step {
    &:hover {
        .feather{
            display: none;
        }
        .feather{
            &.jump-icon{
                display: initial;
            }
        }
    }
}
</style>
