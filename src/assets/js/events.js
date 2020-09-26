import helpers from "./helpers.js";
import config from "./config.js";

const socket = io.connect(config.serverUrl(), {
    secure: true,
    query: {
        userID: 'miuserID'
    }
});

window.addEventListener("load", () => {
    // When the chat icon is clicked
    document.querySelector("#toggle-chat-pane").addEventListener("click", e => {
        let chatElem = document.querySelector("#chat-pane");
        let mainSecElem = document.querySelector("#main-section");

        if (chatElem.classList.contains("chat-opened")) {
            chatElem.setAttribute("hidden", true);
            mainSecElem.classList.remove("col-md-9");
            mainSecElem.classList.add("col-md-12");
            chatElem.classList.remove("chat-opened");
        } else {
            chatElem.attributes.removeNamedItem("hidden");
            mainSecElem.classList.remove("col-md-12");
            mainSecElem.classList.add("col-md-9");
            chatElem.classList.add("chat-opened");
        }

        //remove the 'New' badge on chat icon (if any) once chat is opened.
        setTimeout(() => {
            if (
                document
                .querySelector("#chat-pane")
                .classList.contains("chat-opened")
            ) {
                helpers.toggleChatNotificationBadge();
            }
        }, 300);
    });

    //When the video frame is clicked. This will enable picture-in-picture
    document.getElementById("local").addEventListener("click", () => {
        if (!document.pictureInPictureElement) {
            document
                .getElementById("local")
                .requestPictureInPicture()
                .catch(error => {
                    // Video failed to enter Picture-in-Picture mode.
                    console.error(error);
                });
        } else {
            document.exitPictureInPicture().catch(error => {
                // Video failed to leave Picture-in-Picture mode.
                console.error(error);
            });
        }
    });

    document.getElementById('room_leave').addEventListener('click', () => {
        let leave_session = document.querySelector("#session").value;
        var leave = socket.emit("cancelCall", new Array(leave_session, "true"));
        if (leave) self.close();
    });

    document.getElementById("cancel-room").addEventListener("click", e => {
        let cancel_session = document.querySelector("#session").value;
        var cancel = socket.emit("roomAnswer", new Array(cancel_session, "false"));
        if (cancel) self.close();
    });

    //When the 'Enter room' button is clicked.
    document.getElementById("enter-room").addEventListener("click", e => {
        // console.log('test: ', helpers.getIceServer());

        e.preventDefault();

        let name = document.querySelector("#your-name").value;
        let session = document.querySelector("#session").value;

        if (name) {

            socket.emit("roomAnswer", new Array(session, "true"));

            //remove error message, if any
            document.querySelector("#err-msg-username").innerHTML = "";

            if (name != 'undefined') {

                //save the user's name in sessionStorage
                sessionStorage.setItem("username", name);

                //reload room
                location.reload();

            } else {
                console.log('error...');
            }

        } else {
            document.querySelector("#err-msg-username").innerHTML =
                "Please input your name";
        }
    });

    document.addEventListener("click", e => {
        if (e.target && e.target.classList.contains("expand-remote-video")) {
            helpers.maximiseStream(e);
        } else if (e.target && e.target.classList.contains("mute-remote-mic")) {
            helpers.singleStreamToggleMute(e);
        }
    });

    document.getElementById("closeModal").addEventListener("click", () => {
        helpers.toggleModal("recording-options-modal", false);
    });
});
