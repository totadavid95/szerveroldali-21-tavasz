const express = require("express");
const { Category } = require("../models");
const { StatusCodes } = require("http-status-codes");

const router = express.Router();

router
  .get("/", async (req, res) => {
    const categories = await Category.findAll();
    res.send(categories);
  })
  .get("/:id", async (req, res) => {
    const { id } = req.params;
    const category = await Category.findByPk(id);
    if (!category) return res.sendStatus(StatusCodes.NOT_FOUND);
    res.send(category);
  })
  .post("/", async (req, res) => {
    const category = await Category.create(req.body);
    res.status(StatusCodes.CREATED).send(category);
  })
  .patch("/:id", async (req, res) => {
    const { id } = req.params;
    const category = await Category.findByPk(id);
    if (!category) return res.sendStatus(StatusCodes.NOT_FOUND);
    await category.update(req.body);
    res.send(category);
  })
  .delete("/:id", async (req, res) => {
    const { id } = req.params;
    const category = await Category.findByPk(id);
    if (!category) return res.sendStatus(StatusCodes.NOT_FOUND);
    await category.destroy();
    res.sendStatus(StatusCodes.OK);
  });

module.exports = router;
