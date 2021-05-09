const faker = require("faker");
const httpServer = require("http").createServer();
const io = require("socket.io")(httpServer, {
  cors: ["https://amritb.github.io/socketio-client-tool"],
});

games = [];

io.on("connection", (socket) => {
  socket.on("tip", (data, ack) => {
    try {
      const { number } = data;
      if (number === undefined) {
        throw new Error("No number specified");
      }
      if (isNaN(number)) {
        throw new Error("Not a number");
      }
      if (
        /*games.length &&*/ games[games.length - 1].tips.find(
          (tip) => tip.client === socket.id
        )
      ) {
        throw new Error("Already tipped");
      }
      games[games.length - 1].tips.push({
        client: socket.id,
        number,
      });
      ack({ status: "ok" });
      console.log(games);
    } catch (err) {
      ack({ status: "error", message: err.message });
    }
  });
});

httpServer.listen(3000);

gameOver = () => {
  const sorted = games[games.length - 1].tips.sort(
    (a, b) => a.number - b.number
  );
  const winner = sorted.find(
    (tip) => sorted.filter((other) => other.number === tip.number).length == 1
  );
  games[games.length - 1].tips.forEach((tip) =>
    io.to(tip.client).emit("game-over", {
      won: tip === winner,
      tipped: tip.number,
      winner: winner?.number,
    })
  );
};

startNewGame = () => {
  if (games.length) gameOver();
  games.push({
    startTime: Date.now(),
    tips: [
      { client: "socketid1", number: 12 },
      { client: "socketid2", number: 1 },
      { client: "socketid3", number: 1 },
      { client: "socketid4", number: 2 },
      { client: "socketid4444", number: 2 },
      { client: "socketid444", number: 2 },
      { client: "socketid5", number: 12 },
    ],
  });
  io.emit("new-game-started");
  console.log("new game started");
  setTimeout(startNewGame, faker.datatype.number({ min: 10000, max: 15000 }));
};

startNewGame();
