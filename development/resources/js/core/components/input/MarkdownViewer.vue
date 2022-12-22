<template>
    <viewer
        ref="toastUiViewer"
        :initial-value="value"
        :height="`${height}px`"
    />
</template>

<script>
import '@toast-ui/editor/dist/toastui-editor.css';
import '@toast-ui/editor/dist/theme/toastui-editor-dark.css';
import {Viewer} from '@toast-ui/vue-editor';

export default {
    name: "MarkdownViewer",
    components: {
        viewer: Viewer
    },
    props: {
        value: {},
        height: 300,
    },
    watch: {
        value: {
            handler: function (newValue) {
                this.$refs.toastUiViewer.invoke('setMarkdown', newValue);
            },
        }
    },
    data() {
        return {
            editorOptions: {
                hideModeSwitch: true
            }
        }
    }
}
</script>