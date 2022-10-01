<template>
    <div>
        <div class="container col-md-8 col-xs-12 pt-5">
            <div
                class="card card-outline card-navy direct-chat direct-chat-primary"
            >
                <div class="card-header">
                    <h3 class="card-title">Chats</h3>

                    <div class="card-tools">
                        <span
                            data-toggle="tooltip"
                            title="3 New Messages"
                            class="badge bg-primary"
                            >{{ users.length }}</span
                        >
                        <button
                            type="button"
                            class="btn btn-tool"
                            data-card-widget="collapse"
                        >
                            <i class="fas fa-minus"></i>
                        </button>
                        <button
                            type="button"
                            class="btn btn-tool"
                            data-toggle="tooltip"
                            title="Contacts"
                            data-widget="chat-pane-toggle"
                        >
                            <i class="fas fa-comments"></i>
                        </button>
                        <button
                            type="button"
                            class="btn btn-tool"
                            data-card-widget="remove"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- Conversations are loaded here -->
                    <div
                        class="direct-chat-messages"
                        style="height: 500px"
                        v-chat-scroll
                    >
                        <!-- Message. Default to the left -->
                        <div v-for="(message, index) in messages" :key="index">
                            <div
                                class="direct-chat-msg"
                                v-if="message.user.id != userId"
                            >
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-left">{{
                                        message.user.name
                                    }}</span>
                                    <span
                                        class="direct-chat-timestamp float-right"
                                        >{{
                                            message.created_at | myDateTime
                                        }}</span
                                    >
                                </div>
                                <!-- /.direct-chat-infos -->
                                <img
                                    class="direct-chat-img"
                                    :src="`/img/profile.png`"
                                    alt="Message User Image"
                                />
                                <!-- /.direct-chat-img -->
                                <div class="direct-chat-text">
                                    {{ message.message }}
                                </div>
                                <!-- /.direct-chat-text -->
                            </div>
                            <!-- /.direct-chat-msg -->

                            <!-- Message to the right -->
                            <div
                                v-show="message.user.id === userId"
                                class="direct-chat-msg right"
                            >
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-right"
                                        >You</span
                                    >
                                    <span
                                        class="direct-chat-timestamp float-left"
                                        >{{
                                            message.created_at | myDateTime
                                        }}</span
                                    >
                                </div>
                                <!-- /.direct-chat-infos -->
                                <img
                                    class="direct-chat-img"
                                    :src="`/img/profile.png`"
                                    alt="Message User Image"
                                />
                                <!-- /.direct-chat-img -->
                                <div class="direct-chat-text">
                                    {{ message.message }}
                                </div>
                                <!-- /.direct-chat-text -->
                            </div>
                        </div>
                        <!-- /.direct-chat-msg -->
                    </div>
                    <!--/.direct-chat-messages-->

                    <!-- Contacts are loaded here -->
                    <div class="direct-chat-contacts">
                        <ul class="contacts-list">
                            <li v-for="user in users" :key="user.id">
                                <a href="#">
                                    <img
                                        class="contacts-list-img"
                                        :src="`/img/profile.png`"
                                    />

                                    <div class="contacts-list-info">
                                        <span class="contacts-list-name">
                                            {{ user.name }}
                                            <small
                                                class="contacts-list-date float-right"
                                                >{{ now | myDateTime }}</small
                                            >
                                        </span>
                                        <span class="contacts-list-msg">{{
                                            user.type
                                        }}</span>
                                    </div>
                                    <!-- /.contacts-list-info -->
                                </a>
                            </li>
                            <!-- End Contact Item -->
                        </ul>
                        <!-- /.contatcts-list -->
                    </div>
                    <!-- /.direct-chat-pane -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="input-group">
                        <input
                            type="text"
                            name="message"
                            @keydown="sendTypingEvent"
                            @keyup.enter="sendMessage"
                            v-model="newMessage"
                            placeholder="Type Message ..."
                            class="form-control"
                        />
                    </div>
                    <span class="text-muted" v-if="activeUser"
                        >{{ activeUser.name }} is typing...</span
                    >
                </div>
                <!-- /.card-footer-->
            </div>
            <!--/.direct-chat -->
        </div>

        <!--end of message chat box -->
    </div>

    <!-- Level Modal -->
</template>

<script>
import { mapState } from "vuex";
export default {
    computed: {
        level() {
            return this.$store.state.level;
        },
    },
    data() {
        return {
            messages: [],
            newMessage: "",
            userId: window.user.id,
            user: window.user,
            users: [],
            activeUser: false,
            typingTimer: false,
            now: new Date(),
            level_id: window.user.level_id,
        };
    },

    created() {
        this.fetchMessages();

        Echo.join(`chats.${this.level["id"]}`)
            .here((user) => {
                this.users = user;
            })
            .joining((user) => {
                this.users.push(user);
            })
            .leaving((user) => {
                this.users = this.users.filter((u) => u.id != user.id);
            })
            .listen("LevelChats", (event) => {
                console.log(this.newMessage);
                this.messages.push(event.message);
            })
            .listenForWhisper("typing", (user) => {
                this.activeUser = user;

                if (this.typingTimer) {
                    clearTimeout(this.typingTimer);
                }

                this.typingTimer = setTimeout(() => {
                    this.activeUser = false;
                }, 3000);
            });
    },

    methods: {
        fetchMessages() {
            axios.get("/api/level_messages").then((response) => {
                this.messages = response.data;
            });
        },
        getLevel() {
            axios
                .get("/api/get_chat_level")
                .then((res) => (this.level_id = res.data));
        },
        sendMessage() {
            if (this.newMessage.length != 0) {
                this.messages.push({
                    user: this.user,
                    message: this.newMessage,
                });

                axios.post("/api/level_messages", { message: this.newMessage });

                this.newMessage = "";
            }
        },

        sendTypingEvent() {
            Echo.join(`chats.${this.level["id"]}`).whisper("typing", this.user);
        },
    },
};
</script>
