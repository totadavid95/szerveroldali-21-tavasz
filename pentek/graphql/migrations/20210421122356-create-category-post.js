'use strict';

module.exports = {
  up: async (queryInterface, Sequelize) => {
    await queryInterface.createTable('CategoryPost', {
      id: {
        allowNull: false,
        autoIncrement: true,
        primaryKey: true,
        type: Sequelize.INTEGER
      },
      CategoryId: {
        type: Sequelize.INTEGER,
        allowNull: false,
      },
      PostId: {
        type: Sequelize.INTEGER,
        allowNull: false,
      },
      createdAt: {
        allowNull: false,
        type: Sequelize.DATE
      },
      updatedAt: {
        allowNull: false,
        type: Sequelize.DATE
      }
    });
    await queryInterface.addConstraint('CategoryPost', {
      fields: ['CategoryId', 'PostId'],
      type: 'unique',
    });
  },

  down: async (queryInterface, Sequelize) => {
    await queryInterface.dropTable('CategoryPost');
  }
};
