# REST API - Blog
Egyszerű kis REST backend egy bloghoz.

## Parancsok
A projekt felépítéséhez az alábbi parancsok lettek kiadva:
```shell
# Projekt inicializálása
npm init --yes
# Futáshoz és fejlesztéshez szükséges függőségek telepítése
npm install --save-dev nodemon sequelize-cli
npm install --save dotenv http-status-codes express express-async-errors express-jwt jsonwebtoken sequelize sqlite3 crypto-js faker
# Modellek, migrationok kigenerálása
npx sequelize model:generate --name User --attributes name:string,email:string,password:string
npx sequelize model:generate --name Category --attributes name:string,color:string
npx sequelize model:generate --name Post --attributes title:string,text:string
npx sequelize migration:generate --name associate-category-post
# Seeder kigenerálása
npx sequelize seed:generate --name DatabaseSeeder
```

A GitHub-ról való letöltés után ezekkel a parancsokkal lehet elindítani:
```shell
# Függőségek telepítése:
npm install
# Adatbázis létrehozása, seedelése:
npm run db
# Szerver futtatása Nodemon-nal:
npm run dev
```

## Linkek
- Sequelize 6 doksi: https://sequelize.org/master/
- Express 4 api: https://expressjs.com/en/4x/api.html
- Faker api: http://marak.github.io/faker.js/
- [jwt.io](https://jwt.io/)
- [https://firecamp.io/](https://firecamp.io/)
