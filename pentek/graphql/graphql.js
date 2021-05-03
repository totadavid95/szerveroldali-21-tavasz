const { graphqlHTTP } = require('express-graphql');
const { makeExecutableSchema } = require('@graphql-tools/schema');
const { User, Category, Post, Comment } = require('./models');

let number = 5;

const typeDefs = `
  type Query {
    hello(name: String): String
    test: String
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
    comment(PostId: Int!, text: String!): Int
    editComment(id: Int!, text: String!): String
  }

  type User {
    id: Int!
    name: String
    email: String
    posts: [Post]
  }

  type Post {
    id: Int!
    title: String
    text: String
    categories: [Category]
    author: User
    comments: [Comment]
  }

  type Comment {
    id: Int!
    text: String!
    post: Post!
    author: User!
  }

  type Category {
    id: Int!
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
    test: (parent, args, context, info) => {
      const { user } = context;
      console.log(user);
      return `Hello`;
    },
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
    },
    comment: async (_, { PostId, text }, context) => {
      if (context.unauthorized === true) {
        throw new Error('UnauthorizedError');
      }
      const post = await Post.findByPk(PostId);
      if (!post) {
        throw new Error('PostNotFound');
      }
      const comment = await Comment.create({
        text,
        PostId,
        AuthorId: context.user.id,
      });
      return comment.id;
    },
    editComment: async (_, { id, text }, context) => {
      if (context.unauthorized === true) {
        throw new Error('UnauthorizedError');
      }
      const comment = await Comment.findByPk(id);
      if (!comment) {
        throw new Error('CommentNotFound');
      }
      if (comment.AuthorId !== context.user.id) {
        throw new Error('NotYourComment');
      }
      await comment.update({ text });
      return comment.text;
    },
  },
  User: {
    posts: (user) => user.getPosts(),
  },
  Post: {
    categories: (post) => post.getCategories(),
    author: (post) => post.getAuthor(),
    comments: (post) => post.getComments(),
  },
  Category: {
    posts: (category) => category.getPosts(),
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
