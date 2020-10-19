<template>
  <div
    v-if="friend_id"
    class="col"
    style="padding-left: 0%; margin-right: 15px; padding-right: 0px;"
  >
    <div class="card card-default card-box">
      <div class="input-group">
        <input
          v-model="search"
          type="search"
          class="form-control"
          placeholder="Search message"
        />
        <span class="input-group-addon">
          <i
            class="far fa-file-image"
            type="button"
            @click.prevent="getFile()"
          ></i>
        </span>
        <span v-if="friend_id.status == 'online'" class="input-group-addon">
          <div style="text-align: right">
            <i
              class="fa fa-video-camera camera"
              @click.prevent="openVideo(friend_id)"
              type="button"
              style="color: green"
            ></i>
          </div>
        </span>
      </div>

      <div
        v-chat-scroll="{
          always: false,
          smooth: true,
          scrollonremoved: true,
          smoothonremoved: false,
        }"
        class="card-body cardpadding uploader"
        style="overflow: auto"
        @dragenter="OnDragEnter"
        @dragleave="OnDragLeave"
        @dragover.prevent
        @drop="onDrop"
        :class="{ dragging: isDragging }"
        @scroll="handleScroll"
      >
        <div v-for="message in filteredMessage" :key="message.index">
          <div
            :id="'del' + message.message_id"
            v-if="user.id == message.id"
            class="chatbox__body__message chatbox__body__message--right"
          >
            <div class="chatbox_timing">
              <ul>
                <li>
                  <a>
                    <i class="fa fa-calendar"></i>
                    {{ message.created_at | moment("dddd, Do MMMM YYYY") }}
                  </a>
                </li>
                <li>
                  <a>
                    <i class="far fa-clock"></i>
                    {{ message.created_at | moment("H:mm:ss") }}
                  </a>
                </li>
              </ul>
            </div>
            <img
              :src="baseUrl + '/storage/user_images/' + user.image"
              alt="Picture"
            />
            <div class="clearfix"></div>
            <div class="ul_section_full">
              <ul
                v-if="message.status == 2 || message.type == 'image'"
                class="ul_msg"
                style="height: 150px"
              >
                <li>
                  <strong>{{ message.name }}</strong>
                </li>
                <li :id="message.message_id">
                  <img
                    id="myImg"
                    v-img:myimage
                    :src="
                      baseUrl + '/storage/message_images/' + message.message
                    "
                    class="message_image"
                    style="
                      border-radius: 20px;
                      width: 150px;
                      height: 150px;
                      top: 30px;
                      left: 60px;
                    "
                  />
                </li>
              </ul>
              <ul v-else class="ul_msg">
                <li>
                  <strong>{{ message.name }}</strong>
                </li>
                <li
                  v-if="message.status == 4 || message.type == 'video_time'"
                  :id="message.message_id"
                  style="background-color: greenyellow"
                >
                  <p>
                    <i class="fa fa-phone" style="transform: scaleX(-1)"></i>
                    &nbsp;{{ message.message }}
                  </p>
                </li>
                <li v-else :id="message.message_id">
                  {{ message.message }}
                </li>
              </ul>
              <div class="clearfix"></div>
              <ul
                v-if="
                  message.status != 3 &&
                  message.status != 4 &&
                  message.type != 'video_time'
                "
                class="ul_msg2"
              >
                <li>
                  <a
                    v-if="message.status != 2 && message.type != 'image'"
                    :href="'#' + message.message_id"
                    @click.prevent="updateMessage(message)"
                  >
                    <i class="fas fa-pencil-alt"></i>
                  </a>
                </li>
                <li>
                  <a
                    :href="'#' + message.message_id"
                    @click.prevent="deleteMessage(message)"
                  >
                    <i class="fa fa-trash chat-trash"></i>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div
            v-else
            class="chatbox__body__message chatbox__body__message--left"
          >
            <div class="chatbox_timing">
              <ul>
                <li>
                  <a>
                    <i class="fa fa-calendar"></i>
                    {{ message.created_at | moment("dddd, Do MMMM YYYY") }}
                  </a>
                </li>
                <li>
                  <a>
                    <i class="far fa-clock"></i>
                    {{ message.created_at | moment("H:mm:ss") }}
                  </a>
                </li>
              </ul>
            </div>
            <img
              :src="baseUrl + '/storage/user_images/' + friend_id.image"
              alt="Picture"
            />
            <div class="clearfix"></div>
            <div class="ul_section_full">
              <ul
                v-if="message.status == 2 || message.type == 'image'"
                class="ul_msg"
                style="height: 150px"
              >
                <li>
                  <strong>{{ message.name }}</strong>
                </li>
                <li :id="message.message_id">
                  <img
                    id="myImg"
                    v-img:image
                    :src="
                      baseUrl + '/storage/message_images/' + message.message
                    "
                    class="message_image"
                    style="
                      border-radius: 20px;
                      width: 150px;
                      height: 150px;
                      top: 30px;
                      right: 60px;
                    "
                  />
                </li>
              </ul>
              <ul v-else class="ul_msg">
                <li>
                  <strong>{{ message.name }}</strong>
                </li>
                <li
                  v-if="message.status == 4 || message.type == 'video_time'"
                  :id="message.message_id"
                  style="background-color: greenyellow"
                >
                  <p>
                    {{ message.message }}&nbsp;
                    <i class="fa fa-phone"></i>
                  </p>
                </li>
                <li v-else :id="message.message_id">
                  {{ message.message }}
                </li>
              </ul>
              <div class="clearfix"></div>
              <div v-if="message.status != 4 && message.type != 'video_time'">
                <br />
              </div>
            </div>
          </div>
        </div>
        <div
          v-if="this.friend_id.session == this.check_session"
          id="typing"
          class="chatbox__body__message chatbox__body__message--left"
          style="display: none"
        >
          <img
            :src="baseUrl + '/storage/user_images/' + this.friend_id.image"
            alt="Picture"
          />
          <div class="clearfix"></div>
          <div class="ul_section_full" style="height: 80px; width: 150px">
            <ul class="ul_msg">
              <li>
                <strong>{{ this.friend_id.name }}</strong>
                <div class="wavy" style="font-weight: bold">
                  <span style="--i: 1">T</span>
                  <span style="--i: 2">y</span>
                  <span style="--i: 3">p</span>
                  <span style="--i: 4">i</span>
                  <span style="--i: 5">n</span>
                  <span style="--i: 6">g</span>&nbsp;
                  <span style="--i: 7">.</span>&nbsp;
                  <span style="--i: 8">.</span>&nbsp;
                  <span style="--i: 9">.</span>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="panel-footer" style="padding: 0">
        <div v-if="this.update == true" class="input-group-prepend">
          <span class="input-group-text" id="basic-addon1">{{
            this.messageDisable
          }}</span>
          <input
            id="btn-input"
            type="text"
            class="form-control"
            placeholder="Type your message here..."
            tabindex="0"
            dir="ltr"
            spellcheck="false"
            autocomplete="off"
            autocorrect="off"
            autocapitalize="off"
            contenteditable="true"
            name="message"
            v-model="newMessage"
            @keyup.enter="sendMessage"
          />
          <i
            class="fas fa-location-arrow icon"
            @click.prevent="sendMessage"
          ></i>
        </div>

        <div v-else>
          <div class="input-group-prepend">
            <input
              id="btn-input"
              type="text"
              class="form-control"
              placeholder="Type your message here..."
              tabindex="0"
              dir="ltr"
              spellcheck="false"
              autocomplete="off"
              autocorrect="off"
              autocapitalize="off"
              contenteditable="true"
              name="message"
              v-model="newMessage"
              @keyup.enter="sendMessage"
            />
            <i
              class="fas fa-location-arrow icon"
              @click.prevent="sendMessage"
            ></i>
          </div>

          <div class="input-group-prepend" hidden style="display: none">
            <input
              id="image_file"
              type="file"
              v-on:change="onImageChange"
              class="form-control"
              onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
              accept=".jpg, .jpeg, .png"
            />
            <img id="blah" height="40" width="40" />
            <button
              class="btn btn-success"
              @click="uploadImage(friend_id.session)"
            >
              Upload
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import config from "../../../src/assets/js/config.js";

export default {
  props: ["messages", "user", "friend_id"],
  data() {
    return {
      newMessage: "",
      update: false,
      message_id: null,
      messageDisable: "",
      search: "",
      isDragging: false,
      dragCount: 0,
      check_session: "",
      load: 2,
      hasScrolledToBottom: false,
      baseUrl: "",
    };
  },
  components: {},
  created() {
    this.Typing();
  },
  watch: {
    newMessage(value) {
      value
        ? this.$root.socket.emit(
            "typing",
            new Array(this.friend_id.session, this.user.id)
          )
        : this.$root.socket.emit(
            "stopTyping",
            new Array(this.friend_id.session, this.user.id)
          );
    },
  },
  computed: {
    filteredMessage: function () {
      return this.messages.filter((message) => {
        return message.message.match(this.search);
      });
    },
  },
  mounted() {},
  methods: {
    getUrl() {
      this.baseUrl = window.location.origin;
    },
    //-------------------------------------------------------- load more messages
    handleScroll: function (el) {
      if (el.srcElement.scrollTop == 0) {
        this.hasScrolledToBottom = true;
        this.messages.length >= config.messagesDisplayed()
          ? this.loadMoreMessages(this.friend_id)
          : "";
      }
    },
    loadMoreMessages(friend) {
      if (friend && this.hasScrolledToBottom == true) {
        axios
          .post("/get_messages", {
            session: friend.session,
            load: this.load++,
          })
          .then((r) => {
            if (r.data != 0) {
              r.data[0].reverse().forEach((element) => {
                this.messages.unshift({
                  id: element.id,
                  name: element.name,
                  message_id: element.message_id,
                  message: element.message,
                  status: element.status,
                  created_at: element.created_at,
                });
              });
            } else {
              // this.load = 2;
              console.log("No messages");
            }
          });
      }
    },
    //-------------------------------------------------------- Typing
    Typing() {
      let vm = this;
      this.$root.socket.on("typing", (data) => {
        if (vm.friend_id) {
          if (
            vm.friend_id.session == data["session"] &&
            vm.user.id != data["user_id"]
          ) {
            vm.check_session = data["session"];
            let clock = setInterval(() => {
              clearInterval(clock);
              clock = null;
              document.getElementById("typing").style.display = "block";
            });
          }
        }
      });
      this.$root.socket.on("stopTyping", (data) => {
        if (vm.friend_id) {
          if (
            vm.friend_id.session == data["session"] &&
            vm.user.id != data["user_id"]
          ) {
            document.getElementById("typing").style.display = "none";
          }
        }
      });
    },
    //--------------------------------------------------------drop file
    OnDragEnter(e) {
      e.preventDefault();
      this.dragCount++;
      this.isDragging = true;
      return false;
    },
    OnDragLeave(e) {
      e.preventDefault();
      this.dragCount--;
      if (this.dragCount <= 0) this.isDragging = false;
    },
    onDrop(e) {
      e.preventDefault();
      e.stopPropagation();
      this.isDragging = false;
      const files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.createImage(files[0]);
    },
    //--------------------------------------------------------upload file
    getFile() {
      document.getElementById("image_file").click();
    },
    onImageChange(e) {
      const files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.createImage(files[0]);
    },
    createImage(file) {
      let reader = new FileReader();
      let vm = this;
      reader.onload = (e) => {
        vm.image = e.target.result;
        this.uploadImage(this.friend_id.session);
      };
      reader.readAsDataURL(file);
    },
    uploadImage(session) {
      axios
        .post("/upload", { image: this.image, session: session })
        .then((response) => {
          document.getElementById("blah").src = "";
          this.$root.socket.emit("chatMessage", response.data);
        })
        .catch((error) => console.log(error));
    },
    //--------------------------------------------------------video
    openVideo(friend_id) {
      if (friend_id) {
        let vm = this;
        this.$root.socket.emit(
          "roomVideo",
          new Array(friend_id.session, vm.user.id)
        );
        var image = friend_id.image;
        var url = config.serverUrl() + "/video/";

        window.open(
          url + friend_id.session,
          new Array(
            friend_id.session,
            vm.user.name,
            vm.user.email,
            friend_id.id,
            friend_id.name,
            friend_id.email,
            image,
            vm.user.id,
            vm.user.image
          ),
          "location=1,status=1,scrollbars=1,width=900, height=560"
        );
      }
    },

    sendMessage() {
      if (this.newMessage != "") {
        if (this.update == true) {
          var id = this.message_id;
          var message = this.newMessage;
          $(document).ready(function () {
            $("#" + id).text(message);
          });
          //--------------------
          this.$emit("update_message_unread", {
            message: message,
            user_id: this.friend_id.id,
          });
          //--------------------
          axios
            .post("update_message", {
              data: {
                id: this.message_id,
                message: this.newMessage,
              },
            })
            .then((r) => {
              this.update = false;
              this.message_id = null;
              this.newMessage = "";
              this.messageDisable = "";
            });
          this.newMessage = "";
        } else {
          this.$emit("messagesent", {
            id: this.user.id,
            name: this.user.name,
            message: this.newMessage,
            session: this.friend_id.session,
            created_at: new Date(),
          });
          this.newMessage = "";
        }
      } else {
        alert("Text required.");
        this.update = false;
      }
    },
    deleteMessage(message) {
      if (message) {
        var result = confirm(
          "You are sure to delete this message. '[ " + message.message + " ]'"
        );
        if (result) {
          this.$emit("delete_message", {
            message: message,
            user_id: this.friend_id.id,
          });
          $(document).ready(function () {
            $("#del" + message.message_id).remove();
          });
        }
      }
    },
    updateMessage(message) {
      if (message) {
        this.newMessage = message.message;
        this.messageDisable = message.message;
        this.message_id = message.message_id;
        this.update = true;
      }
      // this.sendMessage(message);
    },
  },
};
</script>

<style lang="scss" scoped>

.col {
    max-width: 100%;
    flex-basis: 0;
    flex-grow: 1;
}

.form-control {
  height: 49px;
}

.input-group .form-control,
.input-group-addon,
.input-group-btn {
  display: table-cell;
}

.input-group-addon {
  padding: 12px 12px;
  font-size: 17px;
  font-weight: 400;
  line-height: 1;
  color: #555;
  text-align: center;
  background-color: #eee;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.camera {
  padding: 0px;
}

// .fa {
//   padding: 0px;
//   width: 15px;
// }

// .has-search .form-control {
//   padding-left: 2.375rem;
// }

// .has-search .form-control-feedback {
//   position: absolute;
//   z-index: 2;
//   display: block;
//   width: 2.375rem;
//   height: 2.375rem;
//   line-height: 2.375rem;
//   text-align: center;
//   pointer-events: none;
//   color: #aaa;
// }

.message_image {
  margin: auto;
  left: 0;
  bottom: 0;
}

#myImg:hover {
  opacity: 0.7;
}

.uploader {
  width: 100%;
  background-color: #d0d0d0;
  &.dragging {
    background-color: #f2f2f2;
    color: #2196f3;
    border: 2px dashed #2196f3;
    .file-input label {
      background: #2196f3;
      color: #fff;
    }
  }
}

.chat-bubble {
  left: 0;
  margin-left: 15px;
  padding-left: 15px;
  text-align: left;
  padding-top: 15px;
  padding-bottom: 5px;
  margin-bottom: 5px;
  border-radius: 30px 30px 30px 0px;
  background-color: #ffffff;
  padding: 16px 28px;
  -webkit-border-radius: 20px;
  -webkit-border-bottom-left-radius: 2px;
  -moz-border-radius: 20px;
  -moz-border-radius-bottomleft: 2px;
  // border-radius: 20px;
  border-bottom-left-radius: 2px;
  display: inline-block;
}

.wavy {
  position: relative;
}
.wavy span {
  position: relative;
  display: inline-block;
  color: #000;
  animation: animate 1.5s infinite ease-in-out;
  animation-delay: calc(0.1s * var(--i));
}
@keyframes animate {
  0% {
    transform: translateY(0px);
    color: #999999;
  }
  28% {
    transform: translateY(-7px);
    color: #b3b3b3;
  }
  44% {
    transform: translateY(0px);
    color: #cccccc;
  }
}
</style>
