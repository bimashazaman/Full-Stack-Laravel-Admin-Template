import Vue from "vue";

export default {
    methods: {
        toastAndReload(message, id = null) {

            this.loading = false;
            this.errors = {};
            this.$toastr.s('', message);
            if (id) this.$hub.$emit(`reload-${id}`)
        },
        toastException(data) {
            this.loading = false;
            this.$toastr.e('', data.message);
        },
        generateModalTitle(subject) {
            return this.fieldTitle(this.selectedUrl ? 'edit' : 'add', subject, true);
        },
        scrollToTop(afterRender = true) {
            const handler = () => window.scroll({
                top: 0,
                left: 0,
                behavior: 'smooth'
            });

            if (afterRender) {
                setTimeout(() => handler())
            } else {
                handler();
            }

        },
        fieldTitle(subject, title = null, titleCapitalize = false, infix = null){
            if (title) {
                let translation = Vue.prototype.$t('field_title', {
                    subject: Vue.prototype.$t(subject),
                    infix: infix ? Vue.prototype.$t(infix).toLowerCase() : '',
                    title: titleCapitalize ? Vue.prototype.$t(title) : Vue.prototype.$t(title).toLowerCase()
                })
                return translation.split(' ').filter(a => a).join(' ');
            }
            return Vue.prototype.$t(subject);
        }

    }
}
