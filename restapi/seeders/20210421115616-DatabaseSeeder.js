"use strict";

const faker = require("faker");

const models = require("../models");
const { User, Category, Post } = models;

module.exports = {
  up: async (queryInterface, Sequelize) => {
    let users = [];
    for (let i = 1; i <= 5; i++) {
      users.push( // bele lehet pusholni, mivel a create visszaadja a létrejött entitást
        await User.create({
          name: faker.name.findName(),
          email: `user${i}@szerveroldali.hu`,
          password: "password",
        })
      );
    }

    // "Tesztelés"
    /*console.log(users[0].matchPassword('password'));
    console.log(users[0].matchPassword('passw0rd'));
    console.log(users[0].matchPassword('Password'));*/

    let categories = [];
    for (let i = 1; i <= 7; i++) {
      categories.push(
        await Category.create({
          name: faker.lorem.word(),
          color: faker.commerce.color(),
        })
      );
    }

    let posts = [];
    for (let i = 1; i <= 14; i++) {
      posts.push(
        await Post.create({
          title: faker.lorem.words(faker.datatype.number({ min: 1, max: 5 })),
          text: faker.lorem.paragraph(
            faker.datatype.number({ min: 1, max: 5 })
          ),
        })
      );
    }

    // Relációk megteremtése az előbb létrehozott entitások között
    for (let post of posts) {
      await post.addCategory(
        faker.random.arrayElements(
          categories,
          faker.datatype.number({ min: 1, max: categories.length })
        )
      );
      await post.setAuthor(faker.random.arrayElement(users));
    }
  },

  down: async (queryInterface, Sequelize) => {
    /**
     * Add commands to revert seed here.
     *
     * Example:
     * await queryInterface.bulkDelete('People', null, {});
     */
  },
};
