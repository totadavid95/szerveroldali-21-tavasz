require("dotenv").config();
const express = require("express");
require("express-async-errors");
const {
  StatusCodes,
  ReasonPhrases,
} = require("http-status-codes");

// Express app létrehozása
const app = express();

// Routerek behúzása
const exampleRouter = require("./routers/example");

// A json parsolás működjön a request body-ból
app.use(express.json());

// Routerek bind-olása adott végpontokhoz
app.use("/example", exampleRouter);
// ...

// A default error handler felülírása, hogy ne HTML kimenetet adjon,
// hanem rendesen jsonben adja vissza a hibákat
app.use((err, req, res, next) => {
  if (res.headersSent) {
    return next(err);
  }
  return res.status(StatusCodes.INTERNAL_SERVER_ERROR).send({
    httpStatus: ReasonPhrases.INTERNAL_SERVER_ERROR,
    errorDetails: {
      name: err.name,
      message: err.message,
      stack: [...err.stack.split("\n")],
    },
  });
});

// App indítása a megadott porton
;(async () => {
  const port = process.env.PORT || 3000;
  app.listen(port, () => {
    console.log(`Az Express app fut, ezen a porton: ${port}`);
  });
})();
