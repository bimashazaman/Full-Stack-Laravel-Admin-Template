<template>
    <div class="content-wrapper">
        <app-breadcrumb :page-title="$t('chat')"/>

        <div class="chat-wrapper">
            <div class="chat-contacts">
                <div class="search-contact">
                    <div class="search-input-group">
                        <app-icon name="search" class="size-17 search-icon"/>
                        <input type="text"
                               v-model="searchContact"
                               class="form-control search-contact-input"
                               placeholder="Search contact">
                    </div>
                </div>
                <div class="contact-list">
                    <template v-for="(contact, index) in visibleContacts">
                        <a class="contact"
                           :class="{'active': activeIndex === index}"
                           role="tab"
                           data-toggle="pill"
                           :id="`v-pills-tab-${index}`"
                           :href="`#v-pills-${index}`"
                           :aria-controls="`v-pills-${index}`"
                           :aria-selected="activeIndex === index ? 'true' : 'false'"
                           @click="changeActive(index, contact)">
                            <div class="contact-icon">
                                <!--                                <div v-if="contact.type === 'group'"-->
                                <!--                                     class="chat-avatar-group">-->
                                <!--                                    <chat-avatar-->
                                <!--                                        :avatar="avatar"-->
                                <!--                                        v-for="(avatar, index) in findUser(contact.groupMembers)"-->
                                <!--                                        v-if="index < 2"-->
                                <!--                                        :key="`group-avatar-${avatar.id}`"-->
                                <!--                                    />-->
                                <!--                                    <chat-avatar-->
                                <!--                                        v-if="findUser(contact.groupMembers).length > 2"-->
                                <!--                                        :avatar="{name: `+${findUser(contact.groupMembers).length - 2}`}"-->
                                <!--                                    />-->
                                <!--                                </div>-->
                                <div class="avatars-w-40">
                                    <app-avatar :alt-text="contact.full_name"
                                                :img="contact.profile_picture ?
                                                    urlGenerator(contact.profile_picture.path) :
                                                    contact.profile_picture"
                                                :shadow="true"
                                                :title="contact.full_name"/>
                                </div>

                            </div>
                            <div class="contact-info">
                                <p class="mb-0">
                                    {{ contact.full_name }}
                                </p>

                                <template v-if="contact.messages[0]">
                                    <p class="text-size-12 text-muted mb-0" v-if="contact.messages[0].message">
                                    <span v-if="contact.messages[0].message.length < 20"
                                          v-html="contact.messages[0].message">

                                    </span>
                                        <span v-else v-html="contact.messages[0].message.substring(0,20)+'...'"></span>
                                    </p>

                                    <p class="text-muted mb-0" v-else>
                                        Image send
                                    </p>
                                </template>
                            </div>
                        </a>
                    </template>
                </div>
            </div>
            <div class="chat-messages">
                <div class="tab-content">
                    <template v-for="(contact, index) in contactList">
                        <div class="tab-pane fade"
                             :class="{'show active': activeIndex === index}"
                             :id="`#v-pills-${index}`"
                             role="tabpanel"
                             :aria-labelledby="`v-pills-tab-${index}`">
                            <div class="message-header">
                                <div class="contact-title">
                                    <h4 class="mb-0">{{ contact.full_name }}</h4>
                                </div>
                                <div class="contact-user">
                                    <!--                                    <div v-if="contact.type === 'group'"-->
                                    <!--                                         class="chat-avatar-group">-->
                                    <!--                                        <chat-avatar-->
                                    <!--                                            :avatar="avatar"-->
                                    <!--                                            v-for="avatar in findUser(contact.groupMembers)"-->
                                    <!--                                            :key="`group-avatar-${avatar.id}`"-->
                                    <!--                                        />-->
                                    <!--                                    </div>-->
                                    <div class="avatars-w-40">
                                        <app-avatar :alt-text="contact.full_name"
                                                    :img="contact.profile_picture ?
                                                    urlGenerator(contact.profile_picture.path) :
                                                    contact.profile_picture"
                                                    :shadow="true"
                                                    :title="contact.full_name"/>
                                    </div>
                                </div>
                            </div>
                            <div class="message-body" v-chat-scroll>
                                <template v-for="userMessage in userMessageLists">
                                    <div class="message"
                                         :class="{'reply-message': contact.id === userMessage.user.id}">

                                        <div class="avatars-w-40" v-if="userMessage.user">
                                            <app-avatar :alt-text="userMessage.user.full_name"
                                                        :img="userMessage.user.profile_picture ?
                                                    urlGenerator(userMessage.user.profile_picture.path) :
                                                    userMessage.user.profile_picture"
                                                        :shadow="true"
                                                        :title="userMessage.user.full_name"/>
                                        </div>
                                        <template v-if="userMessage.attachments.length">
                                            <img class="chat-message-image img-thumbnail"
                                                 v-for="attachment in userMessage.attachments"
                                                 :src="`${urlGenerator(attachment.path)}`"
                                                 alt="Not found"/>
                                        </template>
                                        <div class="text" v-if="userMessage.message">
                                            <span v-html="userMessage.message"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div class="message-editor">
                                <div class="attached-file-options">
                                    <input
                                        type="file"
                                        class="image-input cursor-pointer"
                                        @change="sendImage($event)"
                                        :id="`chat-image-upload-${contact.id}`"
                                    />
                                    <span class="option p-1 text-primary">
                                        <app-icon name="image" class="size-18"/>
                                    </span>
                                    <!--
                                    <a href="#" class="option">
                                        <app-icon name="video" class="size-15"/>
                                    </a>
                                    <a href="#" class="option">
                                        <app-icon name="paperclip" class="size-15"/>
                                    </a>
                                    -->
                                </div>
                                <div class="message-input">
                                    <div class="message-input-group">
                                        <input type="text"
                                               class="form-control"
                                               v-model="messageText"
                                               data-emojiable="true"
                                               @keyup.enter="sendMessage()"
                                               placeholder="Type something here...">
                                        <div class="message-input-append">
                                            <div class="btn-group dropdown-emoji">
                                                <button type="button"
                                                        class="btn"
                                                        data-toggle="dropdown"
                                                        aria-haspopup="true"
                                                        aria-expanded="false">
                                                    <app-icon name="smile" class="size-15"/>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="#"
                                                       v-for="emoji in emojiList"
                                                       class="emoji"
                                                       @click.prevent="selectEmoji($event, emoji)"
                                                       v-html="emoji.code">
                                                    </a>
                                                </div>
                                            </div>
                                            <a href="#"
                                               :class="{'disabled': !messageText}"
                                               class="btn btn-send"
                                               @click.prevent="sendMessage()">
                                                <app-icon name="send" class="size-15"/>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <a href="#"
                                       class="text-size-20"
                                       @click.prevent="sendActiveEmoji()"
                                       v-html="activeEmoji">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <div class="chat-contact-details">
                <template v-for="(contact, index) in contactList">
                    <div v-if="activeIndex === index" class="d-flex flex-column align-items-center">

                        <!--                        <div v-if="contact.type === 'group'"-->
                        <!--                             class="chat-avatar-group">-->
                        <!--                            <chat-avatar-->
                        <!--                                :avatar="avatar"-->
                        <!--                                avatar-class="width-60 height-60"-->
                        <!--                                v-for="(avatar, index) in findUser(contact.groupMembers)"-->
                        <!--                                v-if="index < 2"-->
                        <!--                                :key="`group-avatar-${avatar.id}`"-->
                        <!--                            />-->
                        <!--                            <chat-avatar-->
                        <!--                                avatar-class="width-60 height-60"-->
                        <!--                                v-if="findUser(contact.groupMembers).length > 2"-->
                        <!--                                :avatar="{name: `+${findUser(contact.groupMembers).length - 2}`}"-->
                        <!--                            />-->
                        <!--                        </div>-->
                        <!--                        -->
                        <div class="avatars-w-40">
                            <app-avatar :alt-text="contact.full_name"
                                        :img="contact.profile_picture ?
                                                    urlGenerator(contact.profile_picture.path) :
                                                    contact.profile_picture"
                                        :shadow="true"
                                        :title="contact.full_name"/>
                        </div>

                        <p class="mt-2 mb-0">
                            {{ contact.full_name }}
                        </p>
                    </div>
                </template>
                <div class="my-4 call-actions">
                    <button type="button" class="btn mr-2">
                        <app-icon name="phone" class="size-17 mr-2"/>
                        Voice Call
                    </button>
                    <button type="button" class="btn">
                        <app-icon name="video" class="size-17 mr-2"/>
                        Video Chat
                    </button>
                </div>
                <div class="chat-conversation-action">
                    <div class="search-conversation">
                        <app-icon name="search" class="size-15 search-icon"/>
                        <input type="text" class="form-control" placeholder="Search in conversation">
                    </div>
                    <div class="btn-group dropdown-emoji">
                        <button type="button"
                                class="btn"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            Change Emoji
                            <span class="text-size-20" v-html="activeEmoji"/>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#"
                               class="emoji"
                               v-for="emoji in emojiList"
                               @click.prevent="setEmoji(emoji.code)" v-html="emoji.code">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {urlGenerator} from "../../../../../Helpers/AxiosHelper";
import {FormMixin} from "../../../../../../core/mixins/form/FormMixin";

export default {
    name: 'Index',
    mixins: [FormMixin],
    data() {
        return {
            activeIndex: 0,
            searchContact: '',
            messageText: '',
            activeEmoji: '&#128077;',
            contactList: [],
            userMessageLists: [],
            userInfo: {},
            file_upload: '',
            fileUploadUrl: '',
            typingMessage: '',
            typing: '',
            urlGenerator,
            emojiList: [
                {id: 1, code: '&#9994;'},
                {id: 2, code: '&#9995;'},
                {id: 3, code: '&#9996;'},
                {id: 4, code: '&#128074;'},
                {id: 5, code: '&#128076;'},
                {id: 6, code: '&#128077;'},
                {id: 7, code: '&#128078;'},
                {id: 8, code: '&#128079;'},
                {id: 9, code: '&#128148;'},
                {id: 10, code: '&#128149;'},
                {id: 11, code: '&#128150;'},
                {id: 12, code: '&#128153;'},
                {id: 13, code: '&#128154;'},
                {id: 14, code: '&#128155;'},
                {id: 15, code: '&#128156;'},
                {id: 16, code: '&#128157;'},
                {id: 17, code: '&#128512;'},
                {id: 18, code: '&#128513;'},
                {id: 19, code: '&#128514;'},
                {id: 20, code: '&#128515;'},
                {id: 21, code: '&#128516;'},
                {id: 22, code: '&#128517;'},
                {id: 23, code: '&#128518;'},
                {id: 24, code: '&#128521;'},
                {id: 25, code: '&#128521;'},
                {id: 26, code: '&#128522;'},
                {id: 27, code: '&#128523;'},
                {id: 28, code: '&#128524;'},
                {id: 29, code: '&#128525;'},
                {id: 30, code: '&#128525;'},
                {id: 32, code: '&#128577;'},
                {id: 33, code: '&#128578;'},
                {id: 34, code: '&#128591;'},
            ],
        }
    },
    computed: {
        visibleContacts() {
            return this.contactList.filter(item => item.full_name.toLowerCase().includes(this.searchContact.toLowerCase()));
        }
    },
    watch: {
        'contactList.length': {
            handler: function (length) {
                this.userInfo = this.contactList[0];
                if (this.userInfo) {
                    this.changeActive(0, this.userInfo);
                }
            },
            immediate: true
        }
    },

    methods: {
        // findUser(users) {
        //     if (typeof users === 'object') {
        //         return this.chatUsers.filter(item => users.includes(item.id))
        //     } else {
        //         //return this.chatUsers.find(item => item.id === users)
        //     }
        // },
        changeActive(index, user) {
            this.activeIndex = index;
            this.userInfo = user;
            this.getUserMessages(user.id);
        },
        setEmoji(code) {
            this.activeEmoji = code;
        },
        sendMessage() {
            if (this.messageText.length) {
                let formData = {};
                formData.message = this.messageText
                formData.receiver_id = this.userInfo.id
                this.submitFromFixin('post', `messages`, formData)
            }
        },
        sendActiveEmoji() {
            let formData = {};
            formData.message = this.activeEmoji
            formData.receiver_id = this.userInfo.id
            this.submitFromFixin('post', `messages`, formData)
        },
        sendImage(event) {
            const file = event.target.files[0]
            this.file_upload = file
            this.fileUploadUrl = URL.createObjectURL(file);
            let formData = new FormData();
            formData.append('receiver_id', this.userInfo.id);
            formData.append('file_upload', this.file_upload);
            this.submitFromFixin('post', `messages`, formData)
        },
        afterSuccess(response) {
            this.messageText = '';
            this.file_upload = '';
            this.getUserMessages(response.data.message.receiver_id);
            this.getAllUser();
        },
        selectEmoji(event) {
            this.messageText += event.target.innerText
        },
        getAllUser() {
            this.axiosGet(`chat/users`).then(({data}) => {
                this.contactList = data;
            })
        },
        getUserMessages(id) {
            this.axiosGet(`user-messages/${id}`).then((response) => {
                this.userMessageLists = response.data;
            })
        }
    },
    mounted() {
        Echo.private(`chat.${user.id}`)
            .listen('ChatEvent', (e) => {
                this.getUserMessages(e.message.sender_id);
            });
    },
    created() {
        this.getAllUser();
    }
}
</script>

<style lang="scss">
.attached-file-options {
    display: flex;
    align-items: center;

    .image-input {
        opacity: 0;
        position: absolute;
        width: 25px;
    }
}

.chat-message-image {
    max-height: 200px;
    max-width: 150px;
    margin-right: 10px;
}
</style>
