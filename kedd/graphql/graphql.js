const { graphqlHTTP } = require('express-graphql');
const { makeExecutableSchema } = require('@graphql-tools/schema');
const models = require("./models");
const { User, Category, Post } = models;

let boss = "Valaki";

// Séma
const typeDefs = `
  type Query {
    hello: String
    greeting(name: String): String
    getBoss: String
    users: [User]
    user(id: Int!): User
  }

  type Mutation {
    setBoss(name: String!): String
  }

  type User {
    id: Int!
    name: String
    email: String!
    posts: [Post]
  }

  type Post {
    id: Int!
    title: String
    text: String
  }
`;

// Resolverek
const resolvers = {
  Query: {
    hello: () => {
      return 'Helló';
    },
    /*greeting: (parent, params, context, info) => {
      console.log(params);
      const { name } = params;
      return `Helló ${name}`;
    },*/
    greeting: (parent, { name }, context, info) => `Helló ${name}`,
    getBoss: () => boss,
    users: () => User.findAll(),
    user: (_, { id }) => User.findByPk(id),
  },
  Mutation: {
    setBoss: (_, { name }) => {
      boss = name;
      return boss;
    }
  },
  User: {
    posts: (user) => user.getPosts(),
  },
};

const executableSchema = makeExecutableSchema({
  typeDefs,
  resolvers,
});

module.exports = graphqlHTTP({
  schema: executableSchema,
  graphiql: true,
});
