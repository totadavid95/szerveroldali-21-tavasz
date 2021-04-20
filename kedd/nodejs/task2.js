const faker = require('faker');

const addr = `${faker.address.zipCode()} ${faker.address.cityName()} ${faker.address.streetName()} ${faker.address.streetAddress()}`
console.log(faker.name.findName(), ', ', addr);
