const express = require("express");
const { Category, Post, User } = require("../models");
const { StatusCodes } = require("http-status-codes");
const jwtMiddleware = require("../middlewares/jwt");

const router = express.Router();

const queryOptions = {
  include: [
    {
      model: Category,
      as: "Categories",
      attributes: ["id", "name"],
      through: { attributes: [] },
    },
  ],
};

router
  .get("/", async (req, res) => {
    const posts = await Post.findAll(queryOptions);
    res.send(posts);
  })
  .get("/:id", async (req, res) => {
    const { id } = req.params;
    const post = await Post.findByPk(id, queryOptions);
    if (!post) return res.sendStatus(StatusCodes.NOT_FOUND);
    res.send(post);
  })
  .post("/", jwtMiddleware, async (req, res) => {
    const user = await User.findByPk(req.user.id);
    if (!user) return res.sendStatus(StatusCodes.NOT_FOUND);
    const post = await Post.create(req.body);
    await post.setAuthor(user);
    res.status(StatusCodes.CREATED).send(post);
  })
  .patch("/:id", jwtMiddleware, async (req, res) => {
    const user = await User.findByPk(req.user.id);
    if (!user) return res.sendStatus(StatusCodes.NOT_FOUND);
    const { id } = req.params;
    const post = await Post.findByPk(id);
    if (!post) return res.sendStatus(StatusCodes.NOT_FOUND);
    if (post.AuthorId != user.id)
      return res.sendStatus(StatusCodes.UNAUTHORIZED);
    await post.update(req.body);
    res.send(post);
  })
  .delete("/:id", async (req, res) => {
    const { id } = req.params;
    const post = await Post.findByPk(id);
    if (!post) return res.sendStatus(StatusCodes.NOT_FOUND);
    await post.destroy();
    res.sendStatus(StatusCodes.OK);
  })
  .patch("/:id/set-categories", jwtMiddleware, async (req, res) => {
    // Ha nem jó a request, nincs is értelme tovább nézegetni
    const { categories } = req.body;
    if (!categories) return res.sendStatus(StatusCodes.BAD_REQUEST);
    // User és post ellenőrzése
    const user = await User.findByPk(req.user.id);
    if (!user) return res.sendStatus(StatusCodes.NOT_FOUND);
    const { id } = req.params;
    const post = await Post.findByPk(id);
    if (!post) return res.sendStatus(StatusCodes.NOT_FOUND);
    // Csak a post szerzője módosíthatja
    if (post.AuthorId != user.id)
      return res.sendStatus(StatusCodes.UNAUTHORIZED);
    // Válogassuk szét a létező és a nem létező kategóriákat, aztán a létezőket adjuk hozzá a posthoz
    let categoriesNotExists = [],
      categoriesExists = [];
    for (let categoryId of categories) {
      const category = await Category.findByPk(categoryId);
      (category ? categoriesExists : categoriesNotExists).push(categoryId);
    }
    await post.setCategories(categoriesExists);
    res.send({ categoriesNotExists, categoriesExists });
  });

module.exports = router;
