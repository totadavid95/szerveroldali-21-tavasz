'use strict';
const {
  Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
  class Comment extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
      this.belongsTo(models.User, {
        as: "Author",
        foreignKey: "AuthorId",
      });
      this.belongsTo(models.Post, {
        as: "Post",
        foreignKey: "PostId",
      });
    }
  };
  Comment.init({
    text: DataTypes.STRING,
    PostId: DataTypes.INTEGER,
    AuthorId: DataTypes.INTEGER
  }, {
    sequelize,
    modelName: 'Comment',
  });
  return Comment;
};
