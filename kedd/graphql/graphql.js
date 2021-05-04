const { graphqlHTTP } = require('express-graphql');
const { makeExecutableSchema } = require('@graphql-tools/schema');
const models = require("./models");
const { User, Category, Post, Comment } = models;

let boss = "Valaki";

// Séma
const typeDefs = `
  type Query {
    hello: String
    greeting(name: String): String
    greetingAuth: String
    getBoss: String
    users: [User]
    user(id: Int!): User
    posts: [Post]
    post(id: Int!): Post
    categories: [Category]
    category(id: Int!): Category
  }

  type Mutation {
    setBoss(name: String!): String
    comment(postId: Int, text: String): Int
    editComment(commentId: Int, text: String): String
  }

  type User {
    id: Int!
    name: String
    email: String!
    posts: [Post]
  }

  type Category {
    id: Int!
    name: String
    color: String
    posts: [Post]
  }

  type Post {
    id: Int!
    title: String
    text: String
    categories: [Category]
    comments: [Comment]
  }

  type Comment {
    id: Int!
    text: String
    author: User
    post: Post
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
    greetingAuth: (parent, args, context, info) => {
      if (context.unauthorized === true) {
        throw new Error('UnauthorizedError');
      }
      console.log(context.user);
      return `Helló ${context.user.name}`;
    },
    getBoss: () => boss,
    users: () => User.findAll(),
    user: (_, { id }) => User.findByPk(id),

    posts: () => Post.findAll(),
    post: (_, { id }) => Post.findByPk(id),

    categories: () => Category.findAll(),
    category: (_, { id }) => Category.findByPk(id),
  },
  Mutation: {
    setBoss: (_, { name }) => {
      boss = name;
      return boss;
    },
    comment: async (_, { postId, text }, context) => {
      if (context.unauthorized === true) {
        throw new Error('UnauthorizedError');
      }
      const post = await Post.findByPk(postId);
      if (!post) {
        throw new Error('PostNotFoundError');
      }
      const comment = await post.createComment({
        text,
        AuthorId: context.user.id,
      });
      return comment.id;
    },
    editComment: async (_, { commentId, text }, context) => {
      // Hitelesítés
      if (context.unauthorized === true) {
        throw new Error('UnauthorizedError');
      }
      const comment = await Comment.findByPk(commentId);
      if (!comment) {
        throw new Error('CommentNotFoundError');
      }
      // Jogosultságkezelés
      if (comment.AuthorId !== context.user.id) {
        throw new Error('NotYourCommentError');
      }
      // Komment frissítése
      await comment.update({ text });
      return comment.text;
    }
  },
  User: {
    posts: (user) => user.getPosts(),
  },
  Category: {
    posts: (category) => category.getPosts(),
  },
  Post: {
    categories: (post) => post.getCategories(),
    comments: (post) => post.getComments(),
  },
  Comment: {
    author: (comment) => comment.getAuthor(),
    post: (comment) => comment.getPost(),
  }
};

const executableSchema = makeExecutableSchema({
  typeDefs,
  resolvers,
});

module.exports = graphqlHTTP({
  schema: executableSchema,
  graphiql: {
    headerEditorEnabled: true,
  },
});
