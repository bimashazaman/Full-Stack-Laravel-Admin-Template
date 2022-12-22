<template>
    <editor
        ref="toastUiEditor"
        :initial-value="initialValue"
        :options="editorOptions"
        :height="`${data.height}px`"
        initialEditType="markdown"
        @change="onEditorChange"
    />
</template>

<script>
import '@toast-ui/editor/dist/toastui-editor.css';
import '@toast-ui/editor/dist/theme/toastui-editor-dark.css';
import {Editor} from '@toast-ui/vue-editor';
import {InputMixin} from "./mixin/InputMixin";

export default {
    name: "Markdown",
    mixins: [InputMixin],
    components: {
        editor: Editor
    },
    data() {
        return {
            editorOptions: {
                hideModeSwitch: true
            },
            initialValue: this.value,
        }
    },
    methods: {
        onEditorChange() {
            let markdown = this.$refs.toastUiEditor.invoke('getMarkdown');
            this.$emit('input', markdown);
        }
    }
}
</script>

<style lang="scss">
.toastui-editor-dark .toastui-editor-md-container,
.toastui-editor-dark .toastui-editor-ww-container {
    background-color: var(--form-control-bg) !important;
}

.toastui-editor-main-container {
    color: var(--default-font-color) !important;
}

.toastui-editor-dark .ProseMirror,
.toastui-editor-dark .toastui-editor-contents p,
.toastui-editor-dark .toastui-editor-contents h1,
.toastui-editor-dark .toastui-editor-contents h2,
.toastui-editor-dark .toastui-editor-contents h3,
.toastui-editor-dark .toastui-editor-contents h4,
.toastui-editor-dark .toastui-editor-contents h5,
.toastui-editor-dark .toastui-editor-contents h6 {
    color: var(--default-font-color) !important;
}
</style>
