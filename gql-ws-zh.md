# Szerveroldali webprogramozás zh -- GRAPHQL + Websocket

_2021. május 21._

## Tudnivalók

- Kommunikáció
  - **A Teams csoport Általános csatornáján lehetőleg lépj be a meetingbe, ami a ZH egész ideje alatt tart! Elsősorban ebben a meetingben válaszolunk a felmerülő kérdésekre, valamint az esetleges időközbeni javításokat is itt osztjuk meg!**
  - Ha a zárthelyi közben valamilyen problémád, kérdésed adódik, akkor keresd az oktatókat Teams chaten vagy a meetingben.
- Időkeret
  - **A zárthelyi megoldására 2 óra áll rendelkezésre: _16:00-18:00_**
  - Oszd be az idődet! Ha egy feladat nagyon nem megy, akkor inkább ugord át (legfeljebb később visszatérsz rá), és foglalkozz a többivel, hogy ne veszíts pontot olyan feladatból, amit meg tudnál csinálni!
- Beadás
  - **A beadásra további _15_ perc áll rendelkezésre: _18:00-18:15_. Ez a +15 perc _ténylegesen_ a beadásra van! _18:15_ után a Canvas lezár, és további beadásra nincs lehetőség!**
  - Ha előbb végzel, természetesen 16:00-tól 18:15-ig bármikor beadhatod a feladatot.
  - A feladatokat `node_modules` mappák nélkül kell becsomagolni egy .zip fájlba, amit a Canvas rendszerbe kell feltölteni!
- Értékelés:
  - A legutoljára beadott megoldás lesz értékelve.
  - **A zárthelyin legalább a pontok 40%-át, vagyis legalább 12 pontot kell elérni**, ez alatt a zárthelyi sikertelen.
  - Vannak részpontok.
  - A pótzárthelyin nem lehet rontani a zárthelyi eredményéhez képest, csak javítani.
  - **Érvényes nyilatkozat (megfelelően kitöltött statement.txt) hiányában a kapott értékelés érvénytelen, vagyis 0 pont.**
  - Az elrontott, elfelejtett nyilatkozat utólag pótolható: Canvasen kommentben kell odaírni a feladathoz.
- Egyéb:
  - A feladatokat JavaScript nyelven, Node.js környezetben kell megoldani!
  - **Minden feladat külön mappába kerüljön!**
  - Ha kell, akkor további csomagok telepíthetőek, de ezeket a `package.json` fájlban fel kell tüntetni!
  - Ellenőrzéskor a gyakorlatvezetők az alábbi parancsokat adják ki a feladatonkénti mappákban:
    ```
    # Csomagok telepítése:
    npm install
    # Friss adatbázis létrehozása:
    npm run freshdb
    # Fejlesztői verzió futtatása:
    npm run dev
    ```
  - Ellenőrzéshez és teszteléshez a Node.js _14.x_, az npm _7.x_, és a Firecamp _1.4.x_ verzióját fogjuk használni.

## Hasznos linkek

- Dokumentációk
  - [GraphQL dokumentáció](https://graphql.org/learn/)
  - [Socket.IO dokumentáció](https://socket.io/docs/v4/server-api/)
  - [ExpressJS dokumentáció](https://expressjs.com/en/4x/api.html)
  - [Sequelize dokumentáció](https://sequelize.org/master/)
  - [Firecamp Chrome kiegészítő](https://chrome.google.com/webstore/detail/firecamp-a-campsite-for-d/eajaahbjpnhghjcdaclbkeamlkepinbl)
  - [DB Browser for SQLite](https://sqlitebrowser.org/)

## Tartalomjegyzék

- [Szerveroldali webprogramozás zh -- GRAPHQL + Websocket](#szerveroldali-webprogramozás-zh----graphql + websocket)
  - [Tudnivalók](#tudnivalók)
  - [Hasznos linkek](#hasznos-linkek)
  - [Tartalomjegyzék](#tartalomjegyzék)
  - [Kezdőcsomag](#kezdőcsomag)
  - [Feladatsor](#feladatsor)

## Kezdőcsomag

Segítségképpen készítettünk egy kezdőcsomagot a zárthelyi elkészítéséhez. Csak telepíteni kell a csomagokat, és kezdheted is a fejlesztést.

- A kezdőcsomag az Általános csatornán folyó beszélgetésben érhető el.
- **A kezdőcsomag tartalmaz egy nyilatkozatot (statement.txt), amelyben a <NÉV> és a <NEPTUN> részeket helyettesítsd be a saját neveddel és Neptun kódoddal! Ha beadod a feladatot, azzal elfogadod és magadra nézve kötelezőnek tekinted a nyilatkozatot.**

## GraphQL - Feladatok megoldása és értékelése

- A szerver a 4000-es porton fusson!
- Adott az alábbi GraphQL séma. Készíts GraphQL szervert Node.js-ben, amelyben implementálod a szükséges műveleteket. A boltokat (Shop), árucikkeket (Item), raktárakat (Warehouse) és beszállítókat (Carrier) lokális SQLite adatbázisban kell tárolni. Legyen elérhető a GraphiQL felület, amelyen keresztül tesztelhető a séma implementációja, pl. Apollo szerver esetén http://localhost:4000, express-graphql esetén http://localhost:4000/graphql.

```js

scalar Date

type Shop {
  id: ID!
  name: String!
  city: String!
  createdAt: Date!
  updatedAt: Date!
}

type Item {
  id: ID!
  name: String!
  price: Int!
  createdAt: Date!
  updatedAt: Date!
}

type Warehouse {
  id: ID!
  name: String!
  city: String!
  capacity: Int!
  carriers: [Carrier]
  createdAt: Date!
  updatedAt: Date!
}

type Carrier {
  id: ID!
  name: String!
  numberOfCars: Int!
  carCapacity: Int!
  createdAt: Date!
  updatedAt: Date!
}
```

- Hozd létre az adatbázist, töltsd fel néhány adattal.
- Oldd meg a következő feladatokat:
  - `warehouses`: összes raktár lekérése, a hozzá tartozó szállítókkal együtt **(2 pont)**
    - Kérés:
    ```js
    query {
      warehouses {
        id
        name
        city
        capacity
        createdAt
        updatedAt
        carriers {
          id
          name
          numberOfCars
          carCapacity
          createdAt
          updatedAt
        }
      }
    }
    ```
    - Válasz: a raktárakhoz kapcsolt szállítókat listázni kell a példán látható módon
      ```json
      {
        "data": {
          "warehouses": [
            {
              "id": 1,
              "name": "AmazInG",
              "city": "Lafabel",
              "capacity": 280,
              "createdAt": "2021-01-11...",
              "updatedAt": "2021-01-11...",
              "carriers": [
                {
                  "id": 1,
                  "name": "Sétálár",
                  "numberOfCars": 2,
                  "carCapacity": 15,
                  "createdAt": "2021-01-11...",
                  "updatedAt": "2021-01-11..."
                },
                {
                  "id": 2,
                  "name": "Rohanár",
                  "numberOfCars": 5,
                  "carCapacity": 30,
                  "createdAt": "2021-01-11...",
                  "updatedAt": "2021-01-11..."
                }
              ]
            },
            {
              "id": 1,
              "name": "Ezbaj",
              "city": "Ibst",
              "capacity": 150,
              "createdAt": "2021-01-11...",
              "updatedAt": "2021-01-11...",
              "Carriers": [
                {
                  "id": 2,
                  "name": "Rohanár",
                  "numberOfCars": 5,
                  "carCapacity": 30,
                  "createdAt": "2021-01-11...",
                  "updatedAt": "2021-01-11..."
                }
              ]
            }
          ]
        }
      }
      ```
  - `updateWarehouse(data)`: egy raktár módosítása. Itt a data azokra a kulcs-érték párosokra utal, amit módosítani szeretnénk. Ennek kötelezően tartalmaznia kell az id-t is **(2 pont)**
    - Kérés
      ```js
      mutation {
        updateWarehouse(data: { id: 9, city: "Pheuhpolia", capacity: 50 }) {
          id
          name
          city
          capacity
          updatedAt
          createdAt
        }
      }
      ```
    - Válasz
      ```json
      "data": {
        "updateWarehouse": {
          "id": 9,
          "name": "Holház",
          "city": "Pheuhpolia",
          "capacity": 50,
          "updatedAt": "2021-01-11...",
          "createdAt": "2021-01-11..."
        }
      }
      ```
  - `shop(id)`: Egy bolt lekérdezése (erre még nem jár pont). Egészítsd ki egy olyan mezővel, ami visszaadja a hozzá tartozó olyan raktárakat, amik neve magánhangzóval kezdődik, és az azokhoz tartozó szállítókkal. **(2 pont)**
    - Kérés:
    ```js
    query {
      shop(id: 1) {
        warehousesStartingWithVowel {
          id
          name
          city
          capacity
          createdAt
          updatedAt
          carriers {
            id
            name
            numberOfCars
            carCapacity
            createdAt
            updatedAt
          }
        }
      }
    }
    ```
    - Válasz:
    ```json
      {
        "data": {
          "shop": {
            "warehousesStartingWithVowel": [
              {
                "id": "8",
                "name": "aWaters Group",
                "city": "New Yoshiko",
                "capacity": 73669,
                "createdAt": "2021-05-16T17:51:45.071Z",
                "updatedAt": "2021-05-16T17:51:45.071Z",
                "carriers": [
                  {
                    "id": "1",
                    "name": "Turcotte - Bartoletti",
                    "numberOfCars": 51442,
                    "carCapacity": 14173,
                    "createdAt": "2021-05-16T17:51:43.472Z",
                    "updatedAt": "2021-05-16T17:51:47.630Z"
                  }
                ]
              }
            ]
          }
        }
      }
    ```
    - Segítség: a magánhangzók tömbje: `['a', 'e', 'i', 'o', 'u']`
  - `cheapestItem`: visszaadja az adatbázisban tárolt legolcsóbb terméket és az összes olyan raktárat, ahol lehetséges hogy megtalálható. **(2 pont)**
    - Kérés:
        ```js
          query {
            cheapestItem {
              id
              name
              price
              createdAt
              updatedAt
              shop {
                warehouses {
                  id
                  name
                  city
                  capacity
                  createdAt
                  updatedAt
                }
              }
            }
          }
        ```
    - Válasz:
    ```json
      {
        "data": {
          "cheapestItem": {
            "id": "10",
            "name": "Boehm, Greenholt and Spinka",
            "price": 5119,
            "createdAt": "2021-05-16T17:51:43.104Z",
            "updatedAt": "2021-05-16T17:51:45.981Z",
            "shop": null
          }
        }
      }
    ```

  - `refillShelves(id, items)`: egy megadott bolthoz rendel hozzá árucikkeket. Fontos, hogy az új árucikkek nem adódnak hozzá a már meglévőekhez, hanem a lekérés után csak ezek fognak a bolthoz tartozni. **(3 pont)**
    - Kérés:
    ```js
      mutation {
        refillShelves(id: 1, items: [
          { name: "Víz", price: 100 }
        ]) {
          name
          city
          createdAt
          updatedAt
          items {
            id
            name
            price
            createdAt
            updatedAt
          }
        }
      }
    ```
    - Válasz: A bolt objektum legyen, a hozzá tartozó árucikkekkel.
      ```json
      {
        "data": {
          "refillShelves": {
            "name": "Casper - Koss",
            "city": "Amarillo",
            "createdAt": "2021-05-16T13:17:56.013Z",
            "updatedAt": "2021-05-16T13:17:56.013Z",
            "items": [
              {
                "id": "13",
                "name": "Víz",
                "price": 100,
                "createdAt": "2021-05-16T15:25:38.600Z",
                "updatedAt": "2021-05-16T15:25:38.787Z"
              }
            ]
          }
        }
      }
      ```
  - `fireCarriers(warehouseId)`: Meghívása után minden olyan beszállító, ahol az autók száma _ autók kapacitása nem éri el a raktár kapacitásának felét, törölni kell a raktárból.
    Vagyis, ha `Carrier.numberOfCars _ Carrier.carCapacity < Warehouse.capacity / 2`, akkor a Carrier-ből törölni kell a hozzá tartozó warehouseId-t. **(5 pont)**

    - Kérés

    ```js
      mutation {
        fireCarriers(warehouseId: 1) {
          fired {
            id
            name
            numberOfCars
            carCapacity
            createdAt
            updatedAt
          }
          remainders {
            id
            name
            numberOfCars
            carCapacity
            createdAt
            updatedAt
          }
        }
      }
    ```

    - Válasz
      ```json
      {
        "data": {
          "fireCarriers": {
            "fired": [
              {
                "id": 1,
                "name": "Sétálár",
                "numberOfCars": 2,
                "carCapacity": 15,
                "createdAt": "2021-01-11...",
                "updatedAt": "2021-01-13..."
              }
            ],
            "remainders": [
              {
                "id": 2,
                "name": "Rohanár",
                "numberOfCars": 5,
                "carCapacity": 30,
                "createdAt": "2021-01-11...",
                "updatedAt": "2021-01-13..."
              }
            ]
          }
        }
      }
      ```

  - `statistics(shopId)`: egy boltban lévő árucikkekből készít statisztikát. **(4 pont)**
    - Kérés:
    ```js
      query {
        statistics(shopId: 1) {
          count
          max
          min
          average
          oddSumTenPercent
        }
      }
    ```
    - Válasz: le kell kérni a bolthoz aktuálisan tartozó statisztikákat, A statisztika a következő mezőkből áll: árucikkek darabszáma (mennyi item tartozik a shop-hoz), legmagasabb árú termék ára, legalacsonyabb árú termék ára, a boltban lévő termékek átlagára, minden páratlan árú termék összegének 10%-a. Az alább példa szerint kell visszaadni a statisztikát:
      ```json
      {
        "data": {
          "statistics": {
            "count": 13,
            "max": 200,
            "min": 15,
            "average": 123.64,
            "oddSumTenPercent": 2.4
          }
        }
      }
      ```
## Websocket - Feladatok megoldása és értékelése (10 pont)

Az alábbi egyszerű folyamat során a tanár feladatokat hirdet meg egy eseményhez, majd a diák megoldásokat küld be a feladatokhoz. A tanár értesülni szeretne, ha egy megoldás érkezik, a diák pedig arról kaphat értesítést, ha a megoldása ki lett értékelve.

A megvalósítás során a felhasználókat a socket azonosítójukkal azonosítjuk (ami azt is jelenti, hogy ha újrakapcsolódnak, akkor az másik felhasználót jelent). A tanár és diák úgy van megkülönböztetve, hogy az eseményeknél elmentjük a létrehozó socketid-ját az eseményhez, a diák socketid-ját pedig a megoldáshoz mentjük el.

Az adatokat lokális SQLite adatbázisban kell tárolni. A szükséges modelleket a keretrendszer tartalmazza.  A feladatban három modell van: egy eseményhez több feladat tartozik, egy feladathoz több megoldás (Event 1-N Feladat 1-N Solution).
- Event
  - id
  - name
  - uuid
  - socketid
  - createdAt
  - updatedAt
- Task
  - id
  - description
  - eventId
  - createdAt
  - updatedAt
- Solution
  - id
  - solution
  - evaluation
  - socketid
  - taskId
  - createdAt
  - updatedAt

Egy tipikus folyamat a következő:
1. A tanár létrehozza az eseményt a feladatokkal (`create-event`), megkapja az esemény uuid-ját.
2. A diák valahogyan értesül a uuid-ról, és kapcsolódik az eseményhez (`join-event`) a uuid-val, és megkapja a feladatokat.
3. A diák megold egy feladatot, beküldi azt azonosítóval és megoldással (`send-solution`).
4. A tanár értesül a megoldásról (`solution-sent`),  megkapja a megoldást, a feladatot és az eseményt
5. A tanár beküldi a megoldáshoz tartozó értékelést (`send-evaluation`)
6. A diák értesül az értékelésről (`evaluation-sent`), megkapja a megoldás objektumot.

A következő üzenetek legyenek:

- Kliens -> szerver
  - `create-event`: Esemény létrehozása. A létrehozó lesz a tanár. Fel kell küldeni az esemény nevét, és a feladatokat. Az eseményt el kell menteni a létrehozó socketid-jával. A feladatokat létre kell hozni a táblában és az eseményhez kell kapcsolni őket. Az esemény uuid-jával térünk vissza. **(2 pont)**
    - Paraméterek:
      1. esemény neve
      2. feladatok tömbje, pl. `[{"description": "task1"}, {"description": "task2"}]`
    - Válasz
      - Helyes: `{ status: 'ok', uuid: <uuid> }`
      - Hibás: `{ status: 'error', message: <hibaüzenet> }`
  - `join-event`: Csatlakozás eseményhez. A kapcsolódó megad egy uuid-t, és visszakapja a kapcsolódó esemény és feladatok részleteit. **(2 pont)**
    - Paraméterek:
      1. uuid
    - Válasz
      - Helyes: `{ status: 'ok', event: <event>, tasks: [<task>] }`
      - Hibás: `{ status: 'error', message: <hibaüzenet> }`
  - `send-solution`: Megoldás küldése egy feladathoz. Meg kell adni egy feladatazonosítót (`taskId`) és egy megoldást szövegként. A `Solutions` táblába fel kell venni egy új rekordot a megoldással és a megoldó socketid-jával. A megfelelő feladathoz kell kapcsolni. A művelet hatására `solution-sent` üzenetet kell küldeni a megoldáshoz tartozó feladathoz tartozó eseményben tárolt socketid-ra az esemény, feladat és megoldás adataival. **(1 pont)**
    - Paraméterek:
      1. taskId
      2. megoldás szövege
    - Válasz
      - Helyes: `{ status: 'ok' }`
      - Hibás: `{ status: 'error', message: <hibaüzenet> }`
  - `send-evaluation`: Értékelés küldése egy megoldáshoz. Meg kell adni egy megoldásazonosítót (`solutionId`) és egy értékelést szövegként. A `Solutions` táblában az adott rekordhoz menteni kell az értékelést. A művelet hatására `evaluation-sent` üzenetet kell küldeni a megoldáshoz tartozó socketid-ra a megoldás adataival. **(2 pont)**
    - Paraméterek:
      1. solutionId
      2. értékelés szövege
    - Válasz
      - Helyes: `{ status: 'ok' }`
      - Hibás: `{ status: 'error', message: <hibaüzenet> }`
- Szerver -> kliens
  - `solution-sent`: A diák megoldást adott be. A tanár értesül a megoldásról, a feladatról és az eseményről. **(2 pont)**
    ```js
    {
      event: {...},
      task: {...},
      solution: {...}
    }
    ```
  - `evaluation-sent`: A tanár értékelést adott be. A diák értesül a megoldásról. **(1 pont)**
    ```js
    {
      solution: {...}
    }
    ```
