require("dotenv").config();
const expressJwt = require("express-jwt");

// Az express-jwt teszi lehetővé, hogy a jsonwebtoken-t egyszerűen
// használhassuk Express-ben, ez a fájl kiolvassa a beállításokat
// a .env fájlból és megadja a szükséges middleware-t
module.exports = expressJwt({
  secret: process.env.JWT_SECRET || "secret",
  algorithms: [process.env.JWT_ALGO || "HS256"],
});
