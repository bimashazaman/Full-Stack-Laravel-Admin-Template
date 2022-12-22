import StringMethod from "../../../helpers/string/StringMethod";
export default {
    created() {
        let queryObj = StringMethod.queryStringToObject()
        if(typeof this.afterGetQueryObj === 'function') this.afterGetQueryObj(queryObj);
    },
    methods: {
        afterGetQueryObj(queries) {
            let condition = (this.options.datatableWrapper || this.isUndefined(this.options.datatableWrapper)) && this.options?.filters;
            if(condition) {
                for(const [key, value] of Object.entries(queries)) {
                    if(key === 'per_page') this.options.rowLimit = Number(value);
                    else if(key === 'page') this.options.initialActivePage = Number(value);
                    else {
                        let filter = this.options.filters.find(item => key === _.snakeCase(_.lowerCase(item.key)))
                        if(filter) filter.initValue = value
                    }
                }
            }
        }
    }
}
