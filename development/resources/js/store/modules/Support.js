import {axiosGet} from "../../app/Helpers/AxiosHelper";


const state = {
    customFieldTypeList: [],
    customFiledList: [],

};
const getters = {
    customFieldTypeList: state => state.customFieldTypeList,
    getCustomFiled: state => state.customFiledList,

};

const mutations = {

    SET_CUSTOM_FIELD_INFO(state, data) {
        state.customFieldTypeList = data
    },
    CUSTOM_FILED_LIST(state, data){
        state.customFiledList = data
    },

};
const actions = {
    getCustomFieldType({commit}) {
        axiosGet('admin/app/custom-field-types').then(({data}) => {
            commit('SET_CUSTOM_FIELD_INFO', data)
        }).catch((error) => console.log(error));
    },
    getCustomField({commit},  type) {
        axiosGet(`admin/app/custom-fields?type=${type}`).then((response) => {
           commit('CUSTOM_FILED_LIST', response.data)
        })
    },
};


export default {
    state,
    getters,
    mutations,
    actions
}
