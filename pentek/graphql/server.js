require("dotenv").config();
const express = require("express");
require("express-async-errors");
const {
  StatusCodes,
  ReasonPhrases,
} = require("http-status-codes");
const { UniqueConstraintError } = require("sequelize");
const jwtMiddleware = require('./middlewares/jwt');

const GraphQL = require('./graphql');

// Express app létrehozása
const app = express();

// Routerek behúzása
const userRouter = require("./routers/user");
const authRouter = require("./routers/auth");
const categoryRouter = require("./routers/category");
const postRouter = require("./routers/post");

// Json parsolás működjön a request body-ból
app.use(express.json());

const debugRequest = (req, res, next) => {
  console.log(req.unauthorized);
  console.log(req.user);
  next();
};

const optionalJwtMiddleware = (req, res, next) => {
  return jwtMiddleware(req, res, (err) => {
    if (err) {
      if (err.name !== 'UnauthorizedError') return next(err);
      if (err.message === 'invalid_token') req.invalidToken = true;
      req.unauthorized = true;
    }
    return next();
  })
}

app.use('/graphql', optionalJwtMiddleware, debugRequest, GraphQL);

// Routerek bind-olása a végpontokhoz
app.use("/users", userRouter);
app.use("/auth", authRouter);
app.use("/categories", categoryRouter);
app.use("/posts", postRouter);

/*
  Végpontok terve:

    GET /users - Összes felhasználó lekérése
    GET /users/:id - Adott felhasználó lekérése
    POST /users - Új felhasználó
    PATCH /users/:id - Felhasználó módosítása
    DELETE /users/:id - Felhasználó törlése

    POST /auth - Hitelesítés

    GET /categories - Összes kategória lekérése
    GET /categories/:id - Adott kategória lekérése
    POST /categories - Új kategória
    PATCH /categories/:id - Kategória módosítása
    DELETE /categories/:id - Kategória törlése

    GET /posts - Összes bejegyzés lekérése
    GET /posts/:id - Adott bejegyzés lekérése
    POST /posts - Új bejegyzés
    PATCH /posts/:id - Bejegyzés módosítása
    DELETE /posts/:id - Bejegyzés törlése
    POST /posts/:id/set-categories - A bejegyzéshez tartozó kategóriák módosítása
*/

// Default error handler felülírása, hogy ne HTML kimenetet adjon,
// hanem rendesen jsonben adja vissza a hibákat
app.use((err, req, res, next) => {
  if (res.headersSent) {
    return next(err);
  }
  if (err instanceof UniqueConstraintError) {
    //console.log(err.errors[0]);
    const validationErr = err.errors[0];
    return res.status(StatusCodes.CONFLICT).send({
      httpStatus: ReasonPhrases.CONFLICT,
      errorDetails: {
        message: validationErr.message,
        value: validationErr.value,
      },
    });
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
app.listen(process.env.PORT || 3000);
