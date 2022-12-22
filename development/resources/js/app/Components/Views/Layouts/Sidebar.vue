<template>
    <sidebar :data="data"
             :logo-url="urlGenerator('/')"
             :logo-src="urlGenerator(logo)"
             :logo-icon-src="urlGenerator(logoIcon)"
    />
</template>

<script>
import {urlGenerator} from "../../../Helpers/AxiosHelper";

export default {
    name: 'DashboardSidebar',
    props: {
        data: {
            required: true
        },
        logo: {
            type: String,
        },
        logoIcon: {
            type: String,
        }
    },
    data() {
        return {
            urlGenerator,
        }
    }
}
</script>
