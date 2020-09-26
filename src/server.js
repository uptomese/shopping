// key: fs.readFileSync(__dirname + "/key3/server.key"),
// cert: fs.readFileSync(__dirname + "/key3/server.crt"),
// ca: [
//     fs.readFileSync(__dirname + "/key2/cabundle.ca-bundle"),
//     fs.readFileSync(__dirname + "/key2/entrust_g2_ca.cer")
// ],

const {
    timeStamp
} = require("console");

const bodyParser = require("body-parser"),
    express = require("express"),
    app = express(),
    fs = require("fs"),
    https = require("https"),
    path = require("path"),
    stream = require("./ws/stream"),
    favicon = require("serve-favicon"),
    port = process.env.PORT || 6999,
    server = https.createServer({
            key: fs.readFileSync(__dirname + "/key2/key.pem"),
            cert: fs.readFileSync(__dirname + "/key2/key-cert.pem"),
            requestCert: true,
            rejectUnauthorized: false
        },
        app
    ),
    io = require("socket.io").listen(server);

var users = {};

app.use(favicon(path.join(__dirname, "pngguru.com.ico")));
app.use("/assets", express.static(path.join(__dirname, "assets")));
app.use(express.static(__dirname + "/"));

app.use(
    bodyParser.urlencoded({
        extended: false
    })
);

app.engine("html", require("ejs").renderFile);
app.set("view engine", "html");
app.set("views", __dirname);

app.get("/video/:session", (req, res) => {
    var session = req.params.session;
    res.render("video", {
        session: session
    });
});

io.of("/stream").on("connection", stream);
io.sockets.on("connection", function (socket) {
    // connected io success
    // console.log("a user connected ", socket.id);

    // -------------------------------------------------Chat
    socket.on("chatMessage", data => {
        if (typeof data[1] === 'string') {
            console.log('CHAT: image => user[' + data[0]["name"] + "] session[" + data[2] + "] file[" + data[1] + "] id[" + data[4] + "]");
            io.emit("chatMessage_image", {
                user: data[0],
                message: data[1],
                session: data[2],
                unread: data[3],
                message_id: data[4]
            });
        } else if (data[1] && typeof data[1] === 'object') {
            if (data[1][1] == 'time') {
                console.log('CHAT: video time => user[' + data[0]["name"] + "] session[" + data[2] + "] time[" + data[1][0] + "]");
                io.emit("chatMessage_time", {
                    user: data[0],
                    message: data[1][0],
                    session: data[2],
                    type: 'video_time'
                    // unread: data[3],
                    // message_id: data[4]
                });
            } else {
                console.log('CHAT: message => user[' + data[0]["name"] + "] session[" + data[2] + "] text[" + data[1]['message'] + "] id[" + data[4] + "]");
                io.emit("chatMessage", {
                    user: data[0],
                    message: data[1],
                    session: data[2],
                    unread: data[3],
                    message_id: data[4]
                });
            }
        }
    });

    // -------------------------------------------------userOnline
    socket.on("login", data => {
        if (data.userId) {
            console.log("a user " + data.userId + " connected");
            io.emit("userOnline", {
                userId: data.userId
            });
            users[socket.id] = data.userId;
        }
    });
    socket.on("disconnect", () => {
        if (users[socket.id]) {
            console.log("user " + users[socket.id] + " disconnected");
            io.emit("userOffline", {
                userId: users[socket.id]
            });
            delete users[socket.id];
        }
    });

    // -------------------------------------------------toChat
    socket.on("toChat", data => {
        io.emit("toChat", {
            session: data[0],
            user_id: data[1]
        });
    });

    // -------------------------------------------------roomVideo
    socket.on("roomVideo", data => {
        console.log("roomVideo: ", data);
        io.emit("roomVideo", {
            data: data
        });
    });

    // -------------------------------------------------roomLink
    socket.on("nameRoom", data => {
        console.log("Room created: ", data);
        io.emit("nameRoom", {
            data: data
        });
    });

    // -------------------------------------------------roomAnswer
    socket.on("roomAnswer", data => {
        console.log("roomAnswer: ", data);
        io.emit("roomAnswer", {
            data: data
        });
    });

    // -------------------------------------------------cancelCall
    socket.on("cancelCall", data => {
        console.log('cancelCall: ', data);
        io.emit("cancelCall", {
            data: data
        });
    });

    // -------------------------------------------------typing
    socket.on("typing", data => {
        // console.log('typing: ', data);
        io.emit("typing", {
            session: data[0],
            user_id: data[1]
        });
    });

    // -------------------------------------------------stopTyping
    socket.on("stopTyping", (data) => {
        // console.log('stopTyping: ', data);
        io.emit("stopTyping", {
            session: data[0],
            user_id: data[1]
        });
    });

    // -------------------------------------------------videoTime
    socket.on("videoTime", (data) => {
        console.log('videoTime: ', data);
        io.emit("videoTime", {
            session: data[0],
            time: data[1],
            status: data[2],
            user_id: data[3]
        });
    });

});

server.listen(port, () => {
    console.log("Run Port // :", port);
});
