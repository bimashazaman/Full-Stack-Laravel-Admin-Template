<template>
    <vertical-tab
        v-if="type === 'vertical'"
        :tabs="tabs" :icon="icon"
        :query-string="queryString"
        :elevated="elevated"
    />
    <horizontal-tab
        v-else
        :tabs="tabs"
        :query-string="queryString"
        :bodyClass="bodyClass"
        :tabClass="tabClass"
        :queryStringKey="tabName"
        :elevated="elevated"
    />
</template>
<script>
import VerticalTab from "./VerticalTab";
import HorizontalTab from "./HorizontalTab";

export default {
    name: "AppTab",
    components: {HorizontalTab, VerticalTab},
    props: {
        tabs: {
            type: Array,
            require: true
        },
        type: {
            type: String,
            default: 'vertical'
        },
        icon: {
            type: String
        },
        queryString: {
            type: Boolean,
            default: true
        },
        tabClass: {
            type: String,
            default: 'p-primary'
        },
        elevated: {
            type: Boolean,
            default: true
        },
        bodyClass: {
            type: String,
            default: 'p-primary'
        },
        tabName: {
            type: String,
            default: 'tab'
        }
    }
}
</script>

