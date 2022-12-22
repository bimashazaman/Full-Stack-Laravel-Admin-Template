export default class StringMethod {
    static objectToQueryString(obj) {
        return  Object.keys(obj).reduce((carry, key) => {
            if (obj[key] || obj[key] === 0) {
                return carry + `${key}=${(typeof obj[key] == 'object')
                    ? Object.keys(obj[key]).length > 0
                        ? JSON.stringify(obj[key])
                        : '' : obj[key]}&`
            }
            return carry;
        }, '').replace(/&+$/, '');
    }

    static queryStringToObject(queryString = '') {
        const urlParams = new URLSearchParams(queryString === '' ? window.location.search: queryString);
        const params = Object.fromEntries(urlParams.entries());
        let queryObj = {}
        for (const [key, value] of Object.entries(params)) {
            if(value[0] === '{') {
                queryObj[key] = JSON.parse(value)
            } else queryObj[key] = value
        }
        return queryObj
    }

}
