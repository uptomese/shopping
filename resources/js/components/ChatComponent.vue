<template>
    <div class="container">
        <button id="myBtn" class="chatbox_open">
            <i class="fab fa-rocketchat"></i>
        </button>
        <div class="row">
            <div
                class="chatbox chatbox22 chatbox--tray chatbox--closed col-sm-10 col-md-6 col-lg-4 "
                style="background-color: transparent;position : fixed;"
            >
                <div class="chatbox__title">
                    <h5>
                        <a href="javascript:void()">Private Chat App</a>
                    </h5>
                    <button class="chatbox__title__close">
                        <span>
                            <svg viewBox="0 0 12 12" width="12px" height="12px">
                                <line
                                    stroke="#FFFFFF"
                                    x1="11.75"
                                    y1="0.25"
                                    x2="0.25"
                                    y2="11.75"
                                />
                                <line
                                    stroke="#FFFFFF"
                                    x1="11.75"
                                    y1="11.75"
                                    x2="0.25"
                                    y2="0.25"
                                />
                            </svg>
                        </span>
                    </button>
                </div>
                <friends-component
                    v-on:messagesent="sendMessage"
                    v-on:session="sessionShow"
                    v-on:delete_message="delMessage"
                    :messages="messages"
                    :user="user"
                ></friends-component>
            </div>
        </div>
    </div>
</template>

<script>
import FriendsComponent from "./FriendsComponent";
export default {
    props: ["messages", "user"],
    components: { FriendsComponent },
    mounted() {},
    methods: {
        sendMessage(r) {
            this.$emit("messagesent", {
                id: r.id,
                name: r.name,
                message: r.message,
                session: r.session,
                created_at: r.created_at
            });
        },
        sessionShow(r) {
            this.$emit("session", {
                session: r.session,
                user: r.user
            });
        },
        delMessage(r) {
            this.$emit("delete_message", {
                message: r.message
            });
        }
    }
};
</script>
