const express = require('express');
const categoryRouter = require('./routers/category');

const app = express();

app.use(express.json());

app.use('/categories', categoryRouter);

/*
    GET /users - Minden felhasználó
    GET /users/:id - Felhasználó lekérése
    POST /users - Új felhasználó
    PATCH /users/:id - Meglévő felhasználó módosítása, frissítése
    DELETE /users - Felhasználók törlése
    DELETE /users/:id - Felhasználó törlése

    POST /auth - Hitelesítés

    GET /categories - Minden kategória
    GET /categories/:id - Kategória lekérése
    POST /categories - Új kategória
    PATCH /categories/:id - Meglévő kategória módosítása, frissítése
    DELETE /categories - Kategóriák törlése
    DELETE /categories/:id - Kategória törlése

    GET /posts - Minden bejegyzés
    GET /posts/:id - Bejegyzés lekérése
    POST /posts - Új bejegyzés
    PATCH /posts/:id - Meglévő bejegyzés módosítása, frissítése
    DELETE /posts - Bejegyzés törlése
    DELETE /posts/:id - Bejegyzés törlése
*/

app.use((req, res, next) => {
    const date = new Date();
    console.log(date.toString(), req.ip);
    next();
});

app.use((req, res, next) => {
    const { name } = req.query;
    req.name = name;
    next();
});

app.get('/', (req, res) => {
    res.json({ message: `Helló ${req.name}!` });
});

app.listen(4000);