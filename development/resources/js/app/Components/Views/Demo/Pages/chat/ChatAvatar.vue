<template>
    <div class="chat-avatar">
        <img v-if="avatar.img"
             :src="avatar.img"
             :alt="avatar.name"
             :class="avatarClass">
        <div v-else
             class="chat-avatar-empty"
             :class="avatarClass">
            {{ avatar.name | titleFilter}}
        </div>
    </div>
</template>

<script>
    import CoreLibrary from '../../../../../../core/helpers/CoreLibrary';

    export default {
        name: 'ChatAvatar',
        extends: CoreLibrary,
        props: {
            avatar: {},
            avatarClass: {
                type: String
            }
        }
    }
</script>