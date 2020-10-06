/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

import io from "socket.io-client";

window.Vue = require("vue");

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import ChatComponent from "./components/ChatComponent.vue";

import config from "../../src/assets/js/config.js";

// Vue.component('chat-component', require('./components/ChatComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",
    components: {
        "chat-component": ChatComponent
    },
    data() {
        return {
            messages: [],
            session: "",
            friend_online: "",
            user: "",
            // socket: io(':6999')
            socket: io.connect(config.serverUrl(), {
                secure: true,
                query: {
                    userID: "miuserID"
                }
            })
        };
    },
    computed: {},
    async created() {
        await this.userOnline();
        await this.videoTime();
    },
    async mounted() {},
    methods: {
        videoTime() {
            let vm = this;
            this.$root.socket.on("videoTime", function(data) {
                if (
                    vm.session == data["session"] &&
                    data["status"] == "start" &&
                    vm.user.id == data["user_id"]
                ) {
                    axios
                        .post("/video_time", {
                            data: {
                                session: data["session"],
                                time: data["time"],
                                status: data["status"],
                                user_id: data["user_id"]
                            }
                        })
                        .then(r => {
                            // console.log(r.data);
                        });
                    data["session"] = "";
                } else if (
                    vm.session == data["session"] &&
                    data["status"] != "start"
                ) {
                    axios
                        .post("/video_time_end", {
                            data: {
                                session: data["session"],
                                time: data["time"],
                                status: data["status"],
                                user_id: data["user_id"]
                            }
                        })
                        .then(res => {
                            vm.sendTime(res.data);
                        });
                    data["session"] = "";
                }
            });
        },
        sendTime(res) {
            this.$root.socket.emit("chatMessage", res);
        },
        userOnline() {
            axios.get("/user").then(r => {
                this.user = r.data;
                this.$root.socket.emit("login", {
                    userId: r.data.id
                });
            });
        },
        ownReading(id) {
            axios.post("/re_reading", {
                data: {
                    session: this.session,
                    user_id: id
                }
            });
        },
        reading() {
            let vm = this;
            this.$root.socket.on("toChat", function(data) {
                if (
                    vm.session == data.session["id"] &&
                    vm.user.id != data.user_id.id
                ) {
                    axios.post("/reading", {
                        data: {
                            session_reading: vm.session,
                            session: data.session,
                            user_id: data.user_id.id
                        }
                    });
                }
                data.session["id"] = "";
            });
        },

        chatMessagesImage() {
            let vm = this;
            this.$root.socket.on("chatMessage_image", function(data) {
                if (vm.session == data["session"]) {
                    vm.messages.push({
                        id: data["user"]["id"],
                        name: data["user"]["name"],
                        message_id: data["message_id"],
                        message: data["message"],
                        session: data["session"],
                        type: "image",
                        created_at: new Date()
                    });
                    data["session"] = "";
                }
            });
        },

        chatMessages() {
            let vm = this;
            this.$root.socket.on("chatMessage", function(data) {
                if (vm.session == data["session"]) {
                    vm.messages.push({
                        id: data["user"]["id"],
                        name: data["user"]["name"],
                        message_id: data["message_id"],
                        message: data["message"]["message"],
                        session: data["message"]["session"],
                        created_at: data["message"]["created_at"]
                    });
                    data["session"] = "";
                }
            });
        },

        chatMessageVedeoTime() {
            let vm = this;
            this.$root.socket.on("chatMessage_time", function(data) {
                if (vm.session == data["session"]) {
                    vm.messages.push({
                        id: data["user"]["id"],
                        name: data["user"]["name"],
                        message: data["message"],
                        session: data["session"],
                        type: "video_time",
                        created_at: new Date()
                    });
                    data["session"] = "";
                }
            });
        },

        addMessage(message) {
            // this.messages.push(message);
            //-------------------------------
            axios
                .post("/messages", {
                    data: {
                        message: message
                    }
                })
                .then(response => {
                    this.$root.socket.emit("chatMessage", response.data); //ส่ง data ไป server socket
                    this.messages.message = "";
                });
        },

        async addSession(session) {
            // เอาsession ไปget message
            this.session = session.session;
            axios.post("/get_messages", session).then(r => {
                this.messages = r.data[0];
                this.$root.socket.emit(
                    "toChat",
                    new Array(r.data[1], r.data[2])
                );
            });
            await this.chatMessages();
            await this.chatMessagesImage();
            await this.chatMessageVedeoTime();
            await this.reading();
            await this.ownReading(session.user.id);
        },

        delMessage(message) {
            axios
                .delete("message", {
                    params: {
                        id: message.message.message_id
                    }
                })
                .then(r => {
                    // console.log(r.data);
                });
        }
    }
});

//1. ทำ fetch data ตอน crud --------------------**รอทำorter
//2. ทำ loading เมื่อ crud
//3. user online ดูว่าใครกำลังออนไลน์---------------**ไปเขียนเพิ่มใน AuthenticatesUsers ตอนlogout save status offline
//4. ลบข้อความ เมื่อข้อความนานเกิน2เดือน
//5. limit ข้อความ 100 ข้อความต่อคน --------------
//6. ทำ delete message -------------------------
//7. ทำ update message -------------------------
//8. จำนวนข้อความที่ยังไม่ได้อ่าน --------------------
//9. โชว์ข้อความล่าสุดที่ยังไม่ได้อ่าน ------------------
//10. ลบmessage ไม่ให้ลบออกจากdb เปลี่ยนstate -----
//11. ตอนกำลังพิม คนที่แชทด้วยเห็นว่ากำลังพิม
//12. เสียงแจ้งเตือนตอนข้อความใหม่ ------------------
//13. sql => mongodb ---------------------------
//14. pusher => socket -------------------------
