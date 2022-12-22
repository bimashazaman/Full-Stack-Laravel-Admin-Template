<template>
    <div class="content-wrapper">
        <app-breadcrumb :page-title="$t('form_wizard')"/>
        <div class="card p-primary border-0">
            <app-form-wizard
                :tabs="tabs"
            />
        </div>
    </div>
</template>

<script>
export default {
    name: "formWizard",
    data() {
        return {
            tabs: [
                {
                    name: this.$t('basic_info'),
                    component: "form-wizard-basic-info"
                },
                {
                    name: this.$t('contact_info'),
                    component: "form-wizard-contact-info"
                },
                {
                    name: this.$t('others'),
                    component: "form-wizard-others"
                },
            ],
        };
    }
};
</script>
