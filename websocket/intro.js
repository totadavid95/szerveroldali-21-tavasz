// Csak példák a bevezetőhöz...

const httpServer = require("http").createServer();
const io = require("socket.io")(httpServer, {
  cors: ["https://amritb.github.io/socketio-client-tool"],
});

io.on("connection", (socket) => {
  console.log("Client connected", socket);

  socket.on("test", (data, ack) => {
    console.log(data, ack);
    socket.emit("test-listen", "valami");
    ack({
      alma: true,
    });
  });
});

httpServer.listen(3000);

setInterval(() => {
  io.emit("test-listen2", "valami2");
  console.log("test-listen2");
}, 2000);
