import config from "../config.js";

var str = window.name;
// console.log('index: ', str);

var res = str.split(",");
var session = res[0];
var me = res[1];
var friend = res[2];
var friend_email = res[3];
var image = res[4];

var url = config.serverImage() + '/' + image;

const socket = io.connect(config.serverUrl(), {
    secure: true,
    query: {
        userID: 'miuserID'
    }
})

document.getElementById("friend").innerHTML = friend;
document.getElementById("your-name").value = me;
document.getElementById("session").value = session;
document.getElementById("friend_email").innerHTML = friend_email;

document.getElementById("bg_image").style.backgroundImage = "url('" + url + "')";
document.getElementById("profile_image").src = url;

var reload = 'false';
socket.on("roomAnswer", function (data) {
    if (data && data.data[0] == session) {
        if (data.data[1] == "true") {
            reload = "true";
            (send_load) ? reload = 'true': reload = 'false';
        } else if (data.data[1] == "false") self.close();
        else if (data.data[1] == "closeBrowser" && data.data[2] == "video") self.close();
        else if (data.data[1] == "closeBrowser" && data.data[2] == "index") {
            var sendSocket = socket.emit("videoTime", new Array(data.data[0], new Date().getTime(), 'end', friend_email));
            if (sendSocket) {
                setTimeout(() => {
                    self.close();
                }, 500);
            }
        } else console.log('error...');
    }
});

socket.on("cancelCall", function (data) {
    if (data.data[0] == session && data.data[1] == 'true') {
        var sendSocket = socket.emit("videoTime", new Array(data.data[0], new Date().getTime(), 'end', friend_email));
        sendSocket ? self.close() : '';
    } else console.log('error...');
});

var send_load = window.onunload = (e) => {
    if (reload != 'true') return socket.emit("roomAnswer", new Array(session, "closeBrowser", "index", friend_email));
};

//---------------------block---------------------------------------
const keys = {
    "F1": false,
    "F2": false,
    "F3": false,
    "F4": false,
    "F5": false,
    "F6": false,
    "F7": false,
    "F8": false,
    "F9": false,
    "F10": false,
    "F11": false,
    "F12": false,
    "F13": false,
    "F14": false,
    "F15": false,
    "F16": false,
    "F17": false,
    "F18": false,
    "F19": false,
    "F20": false,
    "F21": false,
    "F22": false,
    "F23": false,
    "F24": false,
    "PrintScreen": false,
    "ScrollLock": false,
    "Pause": false,
    "Tab": false,
    "CapsLock": false,
    "Shift": false,
    "Control": false,
    "Meta": false,
    "Alt": false,
    "ContextMenu": false,
    "ArrowLeft": false,
    "ArrowRight": false,
    "ArrowUp": false,
    "ArrowDown": false,
    "Enter": false,
    "Backspace": false,
    "Clear": false,
    "NumLock": false,
    "Insert": false,
    "Home": false,
    "PageUp": false,
    "PageDown": false,
    "End": false,
    "Delete": false
}
document.addEventListener('keydown', function (event) {
    if (event.defaultPrevented) {
        return
    }
    return checkKey(event)
})

function checkKey(event) { // block keyboard events such as alt-f4 which could close the window
    let key = event.key || event.keyCode;
    let val = parseInt(key, 10);
    if (typeof val == 'number' ||
        keys[key] == false ||
        event.altKey ||
        event.ctrlKey ||
        event.shiftKey ||
        event.metaKey ||
        event.repeat) {
        event.preventDefault()
        return false
    }
}
//---------------------block-end--------------------------------------
