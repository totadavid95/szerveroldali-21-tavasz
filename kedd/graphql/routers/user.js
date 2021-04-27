const express = require("express");
const { User } = require("../models");
const { StatusCodes } = require("http-status-codes");

const router = express.Router();

router
  .get("/", async (req, res) => {
    const users = await User.findAll();
    res.send(users);
  })
  .get("/:id", async (req, res) => {
    const { id } = req.params;
    const user = await User.findByPk(id);
    if (!user) return res.sendStatus(StatusCodes.NOT_FOUND);
    res.send(user);
  })
  .post("/", async (req, res) => {
    const user = await User.create(req.body);
    res.status(StatusCodes.CREATED).send(user);
  })
  .patch("/:id", async (req, res) => {
    const { id } = req.params;
    const user = await User.findByPk(id);
    if (!user) return res.sendStatus(StatusCodes.NOT_FOUND);
    await user.update(req.body);
    res.send(user);
  })
  .delete("/:id", async (req, res) => {
    const { id } = req.params;
    const user = await User.findByPk(id);
    if (!user) return res.sendStatus(StatusCodes.NOT_FOUND);
    await user.destroy();
    res.sendStatus(StatusCodes.OK);
  });

module.exports = router;
