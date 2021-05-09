const faker = require("faker");
const { v4: uuidv4 } = require("uuid");
const httpServer = require("http").createServer();
const io = require("socket.io")(httpServer, {
  cors: ["https://amritb.github.io/socketio-client-tool"],
});

const securities = {
  ELTE: {
    prices: [
      { timestamp: 1610110943147, price: 1234 },
      { timestamp: 1610110969348, price: 1255 },
      { timestamp: 1610110976805, price: 1340 },
    ],
    offers: [
      { clientId: "client1", quantity: 0.65, intent: "sell", active: true },
      { clientId: "client3", quantity: 2.0, intent: "buy", active: false },
    ],
  },
};

io.on("connection", (socket) => {
  socket.on("list-securities", (data, ack) => {
    ack({ status: "ok", securities: Object.keys(securities) });
  });

  socket.on("get-historic-data", (data, ack) => {
    try {
      const props = ["security", "count"];
      for (let prop of props)
        if (!data.hasOwnProperty(prop)) throw new Error(`Missing: ${prop}`);

      if (!securities.hasOwnProperty(data.security)) {
        throw new Error("No such security in our system.");
      }
      if (isNaN(data.count) || data.count < 0) {
        throw new Error(`Wrong number`);
      }
      const count = Math.min(
        securities[data.security].prices.length,
        data.count
      );
      ack({
        status: "ok",
        prices: securities[data.security].prices.slice(0, count),
      });
    } catch (err) {
      ack({ status: "error", message: err.message });
    }
  });

  socket.on("join-security", (data, ack) => {
    try {
      const props = ["security"];
      for (let prop of props)
        if (!data.hasOwnProperty(prop)) throw new Error(`Missing: ${prop}`);
      if (!securities.hasOwnProperty(data.security)) {
        throw new Error("No such security in our system.");
      }
      if (socket.rooms.has(data.security)) {
        throw new Error("You are already subscribed to this security.");
      }
      socket.join(data.security);
      ack({ status: "ok" });
    } catch (err) {
      ack({ status: "error", message: err.message });
    }
  });

  socket.on("leave-security", (data, ack) => {
    try {
      const props = ["security"];
      for (let prop of props)
        if (!data.hasOwnProperty(prop)) throw new Error(`Missing: ${prop}`);
      if (!securities.hasOwnProperty(data.security)) {
        throw new Error("No such security in our system.");
      }
      if (!socket.rooms.has(data.security)) {
        throw new Error("You do not follow this security.");
      }
      socket.leave(data.security);
      ack({ status: "ok" });
    } catch (err) {
      ack({ status: "error", message: err.message });
    }
  });

  socket.on("send-offer", (data, ack) => {
    try {
      const props = ["security", "quantity", "intent"];
      for (let prop of props)
        if (!data.hasOwnProperty(prop)) throw new Error(`Missing: ${prop}`);
      if (!securities.hasOwnProperty(data.security)) {
        throw new Error("No such security in our system.");
      }
      if (!socket.rooms.has(data.security)) {
        throw new Error("You do not follow this security.");
      }
      const id = uuidv4();
      securities[data.security].offers.push({
        id,
        clientId: socket.id,
        quantity: data.quantity,
        intent: data.intent,
        active: true,
      });
      io.in(data.security).emit("offer-sent", {
        id,
        security: data.security,
        quantity: data.quantity,
        intent: data.intent,
      });
      ack({ status: "ok" });
    } catch (err) {
      ack({ status: "error", message: err.message });
    }
  });

  socket.on("accept-offer", (data, ack) => {
    try {
      const props = ["security", "offerId"];
      for (let prop of props)
        if (!data.hasOwnProperty(prop)) throw new Error(`Missing: ${prop}`);
      if (!securities.hasOwnProperty(data.security)) {
        throw new Error("No such security in our system.");
      }
      if (!socket.rooms.has(data.security)) {
        throw new Error("You do not follow this security.");
      }
      const offerIndex = securities[data.security].offers.findIndex(
        (offer) => offer.id === data.offerId
      );
      if (offerIndex === -1) {
        throw new Error("No such offer in our system.");
      }
      securities[data.security].offers[offerIndex].active = false;
      const { id, clientId, quantity, intent } = securities[
        data.security
      ].offers[offerIndex];
      io.to([clientId, socket.id]).emit("offer-accepted", {
        id,
        security: data.security,
        quantity,
        intent,
      });
      ack({ status: "ok" });
    } catch (err) {
      ack({ status: "error", message: err.message });
    }
  });
});

// Ez azt csinálja, hogy egy random értékpapír értékét változtatja át random értékekre
changePrices = () => {
  // Egy random értékpapír választása a securities kulcsai közül
  const security = faker.random.arrayElement(Object.keys(securities));
  // 750 és 1500 között generálunk valami random számot, az lesz a price
  const price = faker.datatype.number({ min: 750, max: 1500 });
  // Az új árat be push-oljuk az adott értékpapír prices property-jébe
  securities[security].prices.push({
      timestamp: Date.now(), // aktuális időbélyeg
      price,
  });
  // Aki az érintett értékpapír csatornájára fel van iratkozva, megkapja 
  // ezt a változtatást a price-changed-ben
  io.to(security).emit('price-changed', { security, price });
  console.log(securities[security])
}

// 1 másodpercenként fusson le a changePrices és változtassa meg egy random értékpapír értékét...
setInterval(changePrices, 1000);

httpServer.listen(3000);
