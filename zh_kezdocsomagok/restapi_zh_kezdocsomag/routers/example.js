const express = require("express");

const router = express.Router();

router
  .get("/", async (req, res) => {
    res.send({ message: 'Example router' });
  })

module.exports = router;
