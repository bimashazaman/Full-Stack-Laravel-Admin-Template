import {mapState} from "vuex";

export default {

    computed: {
        ...mapState({
            statuses: state => state.support.statuses
        }),
    },
}
