require("dotenv").config();
const express = require("express");
const { User } = require("../models");
const { StatusCodes } = require("http-status-codes");
const jwt = require("jsonwebtoken");
const jwtMiddleware = require("../middlewares/jwt");

const router = express.Router();

router
  .post("/", async (req, res) => {
    // Ha a kliens nem küldött mailt vagy jelszót, rossz a request
    const { email, password } = req.body;
    if (!email || !password) return res.sendStatus(StatusCodes.BAD_REQUEST);
    // A kapott mail alapján megpróbáljuk kikeresni a usert
    const user = await User.findOne({ where: { email } });
    if (!user) return res.sendStatus(StatusCodes.NOT_FOUND);
    if (user.matchPassword(password)) { // a requestben kapott jelszó helyes?
      // Ha sikeres volt a jelszó ellenőrzése, akkor csinálnuk egy tokent
      // a megadott beállításokkal és a payloadba belerakjuk a usert, majd
      // visszaküldjük
      const token = jwt.sign({ ...user }, process.env.JWT_SECRET || "secret");
      return res.send({ token });
    }
    return res.sendStatus(StatusCodes.UNAUTHORIZED);
  })
  // Ez csak debug célokra van, hogyha elküldöd rá a tokent, akkor megmondja,
  // hogy melyik userhez tartozik.
  // A requestet úgy kell elküldeni, hogy legyen neki egy Authorization nevű
  // fejléceleme, aminek az értéke Bearer <token>
  // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Authorization
  .get("/who", jwtMiddleware, async (req, res) => {
    //console.log(req.user);
    return res.send(req.user);
  });

module.exports = router;
