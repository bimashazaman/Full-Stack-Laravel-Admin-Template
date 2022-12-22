import coreLibrary from "../../../helpers/CoreLibrary";

export const TabMixin = {
    extends: coreLibrary,
    props: {
        tabs: {
            type: Array,
            require: true
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
        queryStringKey: {
            type: String,
            default: 'tab'
        }
    },
    data() {
        return {
            // queryStringKey: 'tab',
            queryStringValue: '',
            currentIndex: 0,
            componentName: '',
            componentClass: '',
            componentId: '',
            componentProps: '',
            componentTitle: '',
            componentButton: {}
        }
    },
    computed: {
        filteredTab() {
            return this.tabs.filter(tab => (this.isUndefined(tab.permission) || tab.permission !== false))
        }
    },
    mounted() {
        this.queryStringValue = this.getQueryStringValue(this.queryStringKey);
        this.setTabByName();
    },
    methods: {
        setQueryString(name) {
            const pageTitle = window.document.title;
            window.history.pushState("", pageTitle, `?${this.getParams(name)}`);
            // console.log(this.getParams(name));
        },

        getParams(name){
            let params = new URLSearchParams(window.location.search)
            params.set(this.queryStringKey, name);
            return params.toString();
        },

        setTabByName() {
            let currentTab = {},
                result = {};
            result = this.filteredTab.find((item, index) => {
                if (item.name == this.queryStringValue) {
                    this.currentIndex = index;
                    return item;
                }
            });

            currentTab = result ? result : this.filteredTab[this.currentIndex];

            this.loadComponent(currentTab, this.currentIndex);
        },

        loadComponent(tab, index) {
            this.currentIndex = index;
            this.componentTitle = tab.title;
            this.componentId = tab.name + '-' + index;
            this.componentName = tab.component;
            this.componentClass = tab.class
            this.componentProps = tab.props;
            if (!this.isUndefined(tab.headerButton)) {
                this.componentButton = tab.headerButton;
            } else this.componentButton = {};

            if (this.queryString) this.setQueryString(tab.name);
        }
    }
};
