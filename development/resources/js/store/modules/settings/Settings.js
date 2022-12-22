import {getDateFormatForFrontend, getTimeFormatForFrontend} from '../../../app/Helpers/DateTimeHelper';
import {BASIC_SETTINGS, LANGUAGE} from "../../../app/Config/ApiUrl";
import {axiosGet, urlGenerator} from "../../../app/Helpers/AxiosHelper";

export default {

	state: {
		dateFormat: window.settings?.date_format ? getDateFormatForFrontend(window.settings.date_format) : "DD-MM-YYYY",
		timeFormat: window.settings?.time_format ? getTimeFormatForFrontend(window.settings.time_format) : 12,
		appSettings: window.settings ? window.settings : {},
		selectedLanguage : {},
		languages: [],
	},

	getters: {
		appSettings : state => state.appSettings,
	},

	mutations: {

    	SET_SETTINGS(state, setting){
			state.appSettings = setting;
			state.appSettings.logo = setting?.company_logo;
			state.appSettings.icon = setting?.company_icon;

			state.appSettings.company_icon = urlGenerator(setting?.company_icon);
			state.appSettings.company_logo = urlGenerator(setting?.company_logo);
			state.appSettings.company_banner = urlGenerator(setting?.company_banner);

			state.dateFormat = getDateFormatForFrontend(setting?.date_format);
			state.timeFormat = getTimeFormatForFrontend(setting?.time_format);
		},

		SET_LANGUAGES(state, data) {
			state.languages = data.map((lang) => {
				return {
					...lang,
					id: lang.key,
					value: lang.title
				}
			});
		},

		SET_SELECTED_LANGUAGE(state, rootState){
    	    const {locale, translations} = rootState.i18n;
    	    state.selectedLanguage = translations[locale];
        }
	},

	actions: {
		getSettings({commit}){
			axiosGet(BASIC_SETTINGS).then(({data}) => {
				commit('SET_SETTINGS', data);
			});
		},

		getLanguages({commit, state}) {
			if (!state.languages.length) {
				axiosGet(LANGUAGE).then(({data}) => {
					commit('SET_LANGUAGES', data)
				});
			}
		},

		setSelectedLanguage({commit, rootState}){
		    commit('SET_SELECTED_LANGUAGE', rootState)
        }
	}
}
