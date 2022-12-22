<template>
    <app-tab :tabs="tabs" type="horizontal" :elevated="false" tabClass="px-4 py-3" />
</template>

<script>
export default {
    name: "InnerComponent",
    data() {
        return {
            tabs: [
                {
                    "name": "Inner Tab 1",
                    "component": "inner-tab",
                    "permission": ""
                },
                {
                    "name": "Inner Tab 2",
                    "component": "inner-tab",
                    "permission": ""
                },
                {
                    "name": "Inner Tab 3",
                    "component": "inner-tab",
                    "permission": ""
                },
                {
                    "name": "Inner Tab 4",
                    "component": "inner-tab",
                    "permission": ""
                },
                {
                    "name": "Inner Tab 5",
                    "component": "inner-tab",
                    "permission": ""
                },
            ]
        }
    }
}
</script>

<style scoped>

</style>