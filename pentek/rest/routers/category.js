const express = require('express');
const router = express.Router();

const models = require('../models');
const { Category } = models;

/*
    GET /categories - Minden kategória
    GET /categories/:id - Kategória lekérése
    POST /categories - Új kategória
    PATCH /categories/:id - Meglévő kategória módosítása, frissítése
    DELETE /categories - Kategóriák törlése
    DELETE /categories/:id - Kategória törlése
*/

router
    .get('/',       async (req, res) => {
        const categories = await Category.findAll();
        res.send(categories);
    })
    .get('/:id',    async (req, res) => {
        const { id } = req.params;
        const category = await Category.findByPk(id);
        if (!category) {
            res.sendStatus(404);
        }
        res.send(category);
    })
    .post('/',      async (req, res) => {
        console.log(req.body);
        const category = await Category.create(req.body);
        //console.log(category);
        res.send(category)
    })
    .put('/:id',    (req, res) => res.send("Modify a recipes"))
    .patch('/:id',  (req, res) => res.send("Modify part of a recipes"))
    .delete('/:id', (req, res) => res.send("Delete all recipes"))
    .delete('/:id', (req, res) => res.send("Delete recipe"));

module.exports = router;