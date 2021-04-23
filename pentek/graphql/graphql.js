const { graphqlHTTP } = require('express-graphql');
const { makeExecutableSchema } = require('@graphql-tools/schema');
const { User, Category, Post } = require('./models');

let number = 5;

const typeDefs = `
  type Query {
    hello(name: String): String
    getNumber: Int
    users: [User]
    user(id: Int): User
    categories: [Category]
    category(id: Int): Category
    posts: [Post]
    post(id: Int): Post
  }

  type Mutation {
    setNumber(n: Int): Int
  }

  type User {
    id: Int
    name: String
    email: String
    posts: [Post]
  }

  type Post {
    id: Int
    title: String
    text: String
    categories: [Category]
    author: User
  }

  type Category {
    id: Int
    name: String
    color: String
    posts: [Post]
  }
`;

const resolvers = {
  Query: {
    /*hello: (_, params) => {
      console.log(params);
      const { name } = params;
      return `Hello ${name}!`
    },*/
    hello: (_, { name }) => `Hello ${name}!`,
    getNumber: () => number,
    //
    users: () => User.findAll(),
    user: (_, { id }) => User.findByPk(id),

    categories: () => Category.findAll(),
    category: (_, { id }) => Category.findByPk(id),

    posts: () => Post.findAll(),
    post: (_, { id }) => Post.findByPk(id),
  },
  Mutation: {
    setNumber: (_, { n }) => {
      number = n;
      return number;
    }
  },
  User: {
    posts: (user) => user.getPosts(),
  },
  Post: {
    categories: (post) => post.getCategories(),
    author: (post) => post.getAuthor(),
  },
  Category: {
    posts: (category) => category.getPosts(),
  }
};

const executableSchema = makeExecutableSchema({
  typeDefs,
  resolvers,
});

module.exports = graphqlHTTP({
  schema: executableSchema,
  graphiql: true,
});
