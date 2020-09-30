<template>
  <div class="chatbox__body" style="padding: 0%">
    <div class="row">
      <div class="col-lg-4" style="padding-right: 0%">
        <div class="card card-default">
          <div class="has-search input-group">
            <!-- <span class="fa fa-search form-control-feedback"></span> -->
            <input
              v-model="search"
              type="search"
              class="form-control"
              placeholder="Search name"
            />
          </div>
          <!-- <div class="card-header headlist"></div> -->
          <div class="card-body friends" style="overflow: auto; padding: 0%">
            <ul
              class="list-group shotname"
              v-for="friend in filteredFriend"
              :key="friend.id"
            >
              <li
                v-if="friend.id == select"
                class="list-group-item list-group-item-action active"
                style="text-decoration: none"
              >
                <img
                  :src="baseUrl + '/storage/user_images/' + friend.image"
                  class="profileimg"
                />
                <span :id="'unread_count' + friend.id" class="badge"></span>
                <div class="chat message">
                  <a class="username" style="color: white">
                    {{ friend.name }}
                    <span
                      :id="'status_online' + friend.id"
                      v-if="friend.status == 'online'"
                      style="
                        background: rgb(66, 183, 42);
                        border-radius: 50%;
                        display: inline-block;
                        height: 6px;
                        margin-left: 4px;
                        width: 6px;
                      "
                    ></span>
                  </a>
                  <a
                    :id="'unread_message' + friend.id"
                    class="letter"
                    style="color: white"
                    >{{ friend.unread_message }}</a
                  >
                </div>
              </li>
              <li
                type="button"
                v-else
                @click.prevent="sendFriendId(friend)"
                class="list-group-item list-group-item-action"
                style="text-decoration: none"
              >
                <img
                  :src="baseUrl + '/storage/user_images/' + friend.image"
                  class="profileimg"
                />
                <span
                  v-if="friend.resulut_unread > 0"
                  :id="'unread_count' + friend.id"
                  class="badge"
                  >{{ friend.resulut_unread }}</span
                >
                <div class="chat message">
                  <a
                    v-if="friend.status == 'online'"
                    class="username"
                    style="color: green"
                  >
                    {{ friend.name }}
                    <span
                      :id="'status_online' + friend.id"
                      v-if="friend.status == 'online'"
                      style="
                        background: rgb(66, 183, 42);
                        border-radius: 50%;
                        display: inline-block;
                        height: 6px;
                        margin-left: 4px;
                        width: 6px;
                      "
                    ></span>
                  </a>
                  <a v-else class="username">
                    {{ friend.name }}
                    <span
                      :id="'status_online' + friend.id"
                      v-if="friend.status == 'online'"
                      style="
                        background: rgb(66, 183, 42);
                        border-radius: 50%;
                        display: inline-block;
                        height: 6px;
                        margin-left: 4px;
                        width: 6px;
                      "
                    ></span>
                  </a>
                  <a class="letter">{{ friend.unread_message }}</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <message-component
        v-on:update_message_unread="updateMessageUnread"
        v-on:messagesent="sendMessage"
        v-on:delete_message="delMessage"
        :messages="messages"
        :user="user"
        :friend_id="friend_id"
      ></message-component>
    </div>
  </div>
</template>

<script>
import MessageComponent from "./MessageComponent";
export default {
  data() {
    return {
      friend_list: [],
      friend_id: null,
      select: null,
      index: 0,
      session: null,
      search: "",
      baseUrl: "",
    };
  },
  props: ["messages", "user"],
  components: { MessageComponent },
  computed: {
    filteredFriend: function () {
      return _.orderBy(
        this.friend_list.filter((friend) => {
          return friend.name.match(this.search);
        }),
        "status",
        "desc"
      );
    },
  },
  async mounted() {
    await this.listen();
    await this.getFriends(this.user);
    await this.unRead();
    await this.unreadCount();
    await this.roomVideo();
    await this.getUrl();
  },
  async created() {},
  methods: {
    getUrl() {
      this.baseUrl = window.location.origin;
    },
    createRoom(session) {
      let vm = this;
      this.$root.socket.on("nameRoom", function (data) {
        if (data.data) {
          if (data.data[1] == vm.user.id) {
            var image = "pf4.jpg";
            window.open(
              data.data[0],
              new Array(
                session,
                data.data[3],
                data.data[2],
                data.data[4],
                image
              ),
              "location=1,status=1,scrollbars=1,width=900, height=560"
            );
          }
        }
        data.data = "";
      });
    },
    roomVideo() {
      let vm = this;
      this.$root.socket.on("roomVideo", function (data) {
        vm.friend_list.forEach((element) => {
          if (element.session == data.data[0] && vm.user.id != data.data[1]) {
            vm.createRoom(element.session);
          }
        });
      });
    },

    updateMessageUnread(r) {
      if (r) {
        $(document).ready(function () {
          $("#unread_message" + r.user_id).text("คุณ: " + r.message);
        });
      }
    },

    unreadCount() {
      var i = 0;
      this.friend_list.forEach((element) => {
        var index = element.index_unread;
        var str = element.unread;
        var resulut = str.split(",")[index];
        this.friend_list[i].resulut_unread = resulut;
        this.friend_list.push();
        i++;
      });
    },

    sendMessage(r) {
      this.$emit("messagesent", {
        id: r.id,
        name: r.name,
        message: r.message,
        session: r.session,
        created_at: r.created_at,
      });
    },

    delMessage(r) {
      if (r) {
        this.$emit("delete_message", {
          message: r.message,
        });
        $(document).ready(function () {
          $("#unread_message" + r.user_id).remove();
        });
      }
    },

    async getFriends(id) {
      await axios
        .post("/friends_list", id)
        .then((r) => {
          this.friend_list = r.data;
        })
        .catch((e) => {
          console.log(e);
        });
    },

    sendFriendId(friend) {
      if (friend) {
        var index_friend;
        this.friend_list.forEach(myFunction);
        function myFunction(item, index) {
          item.id == friend.id ? (index_friend = index) : "";
        }
        this.index = index_friend;
        // ----------------------------------
        this.session = friend.session;
        this.select = friend.id;
        this.friend_id = friend;
        // ----------------------------------
        this.$emit("session", {
          session: friend.session,
          user: this.user,
        });
        // ----------------------------------
        axios.post("/recount_unread", { session: friend.session });
        // ----------------------------------
        $(document).ready(function () {
          $("#unread_count" + friend.id).hide();
        });
        // ----------------------------------
      }
    },

    listen() {
      let vm = this;
      this.$root.socket.on("userOnline", function (data) {
        if (data.userId) {
          let i = 0;
          vm.friend_list.forEach((element) => {
            if (element.id == data.userId) {
              vm.friend_list[i].status = "online";
              vm.friend_list.push();
            }
            i++;
          });
          axios.post("/api/user/online/" + data.userId, {
            id: data.userId,
          });
        }
      });
      this.$root.socket.on("userOffline", function (data) {
        if (data.userId) {
          let j = 0;
          vm.friend_list.forEach((element) => {
            if (element.id == data.userId) {
              vm.friend_list[j].status = "offline";
              vm.friend_list.push();
            }
            j++;
          });
          axios.post("/api/user/offline/" + data.userId, {
            id: data.userId,
          });
        }
      });
    },

    unRead() {
      this.lastMessage("chatMessage_image");
      this.lastMessage("chatMessage");
    },

    lastMessage(socket) {
      let vm = this;
      this.$root.socket.on(socket, function (data) {
        if (vm.user.id == data.user.id) {
          typeof data.message === "string"
            ? (vm.friend_list[vm.index].unread_message = "คุณ: รูปภาพ")
            : (vm.friend_list[vm.index].unread_message =
                "คุณ: " + data.message.message);
          vm.friend_list.push();
        } else {
          var i = 0;
          vm.friend_list.forEach((element) => {
            if (element.id == data.user.id && element.session == data.session) {
              typeof data.message === "string"
                ? (vm.friend_list[i].unread_message = "รูปภาพ")
                : (vm.friend_list[i].unread_message = data.message.message);
              vm.friend_list.push();
              if (vm.index != i) {
                $(document).ready(function () {
                  $("#unread_count" + data.user.id).show();
                });
              }
              if (vm.session == null) {
                vm.sound();
                vm.friend_list[i].resulut_unread = data.unread;
                vm.friend_list.push();
              } else if (vm.session == data.message.session) {
              } else if (vm.session != data.message.session) {
                vm.sound();
                vm.friend_list[i].resulut_unread = data.unread;
                vm.friend_list.push();
              }
            }
            i++;
          });
        }
      });
    },

    sound() {
      var audio = new Audio("/sound/message.mp3");
      audio.play();
    },
  },
};
</script>

<style>
.form-control {
  height: 49px;
}

.chat img {
  width: 50px;
  height: 50px;
  margin-right: 10px;
  border: 1px solid #222222;
}

.chat .username {
  margin-top: -10px;
  font-size: 16px;
}

.chat .letter {
  margin-top: -5px;
  padding-top: 0;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.letter:hover {
  overflow: visible;
}

.message {
  display: flex;
  flex-direction: column;
  height: 30px;
}

/* .has-search .form-control {
  padding-left: 2.375rem;
} */

/* .has-search .form-control-feedback {
  position: absolute;
  z-index: 2;
  display: block;
  width: 2.375rem;
  height: 2.375rem;
  line-height: 2.375rem;
  text-align: center;
  pointer-events: none;
  color: #aaa;
} */
</style>
