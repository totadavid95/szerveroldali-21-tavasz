const models = require('./models');
const { Category } = models;

;(async () => {
    await models.sequelize.sync({ force: true });

    await Category.create({
        name: 'Kategória 1',
        color: 'white',
    });

    
})();