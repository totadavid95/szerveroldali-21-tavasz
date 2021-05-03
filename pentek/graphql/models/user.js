"use strict";
const { Model } = require("sequelize");
const SHA256 = require("crypto-js/sha256");

const hashPassword = (user) =>
  (user.password = SHA256(user.password).toString());

module.exports = (sequelize, DataTypes) => {
  class User extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
      this.hasMany(models.Post, {
        as: "Posts",
        foreignKey: "AuthorId",
      });
      this.hasMany(models.Comment, {
        as: "Comments",
        foreignKey: "AuthorId",
      });
    }

    matchPassword = (password) => SHA256(password).toString() === this.password;

    toJSON = () => {
      let data = this.get();
      if (data.hasOwnProperty("password")) delete data.password;
      //console.log(data);
      return data;
    };
  }
  User.init(
    {
      name: DataTypes.STRING,
      email: DataTypes.STRING,
      password: DataTypes.STRING,
    },
    {
      sequelize,
      modelName: "User",
      hooks: {
        beforeCreate: hashPassword,
        beforeUpdate: hashPassword,
      },
    }
  );
  return User;
};
