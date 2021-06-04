
# Szerveroldali webprogramozás pótzh -- GraphQL + Websocket

_2021. június 04._

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
  - Becsomagoláshoz használható az `npm run zip` parancs.
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
  - Ellenőrzéskor a gyakorlatvezetők az alábbi parancsokat adják ki:
    ```
    # Csomagok telepítése:
    npm install
    # Friss adatbázis létrehozása
    npm run gql-db
    npm run ws-db
    # Fejlesztői verzió futtatása:
    npm run gql
    npm run ws
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
  - [SocketIO Client Tool](https://amritb.github.io/socketio-client-tool/)

## Tartalomjegyzék

- [Szerveroldali webprogramozás pótzh -- GraphQL + Websocket](#szerveroldali-webprogramozás-pótzh----graphql--websocket)
  - [Tudnivalók](#tudnivalók)
  - [Hasznos linkek](#hasznos-linkek)
  - [Tartalomjegyzék](#tartalomjegyzék)
  - [Kezdőcsomag](#kezdőcsomag)
  - [GraphQL feladat - Játékok nyilvántartása (20 pont)](#graphql-feladat---játékok-nyilvántartása-20-pont)
  - [Websocket feladat - Feladatok megoldása és értékelése (10 pont)](#websocket-feladat---feladatok-megoldása-és-értékelése-10-pont)

## Kezdőcsomag

Segítségképpen készítettünk egy kezdőcsomagot a zárthelyi elkészítéséhez. Csak telepíteni kell a csomagokat, és kezdheted is a fejlesztést.

- A kezdőcsomag az Általános csatornán folyó beszélgetésben érhető el.
- **A kezdőcsomag tartalmaz egy nyilatkozatot (statement.txt), amelyben a <NÉV> és a <NEPTUN> részeket helyettesítsd be a saját neveddel és Neptun kódoddal! Ha beadod a feladatot, azzal elfogadod és magadra nézve kötelezőnek tekinted a nyilatkozatot.**
- A kezdőcsomag tartalmaz automata tesztelőket, viszont ezek csak segédprogramok! A zárthelyit enélkül is meg kell tudni oldani a tanult eszközökkel!

## GraphQL feladat - Játékok nyilvántartása (20 pont)

- Adott az alábbi GraphQL schema. Egészítsd ki a schema-t, ahol ez szükséges, majd pedig készítsd el a szükséges resolver-eket a feladatok megoldásához!
- Teszteléshez használd a GraphiQL felületet: http://localhost:4000/graphql.

  ```graphql
  scalar Date

  type Query {
    # ...
  }

  type Developer {
    id: ID!
    name: String!
    website: String
    location: String
  }

  type Genre {
    id: ID!
    name: String
  }

  type Game {
    id: ID!
    title: String!
    writers: String
    description: String
    singleplayer: Boolean
    multiplayer: Boolean
    engine: String
  }

  enum Platform {
    win
    ps2
    ps3
    ps4
    ps5
    xbox360
    xboxone
  }

  type Release {
    id: ID!
    platform: Platform!
    date: Date!
    version: String
  }
  ```

- A modellek:
  - Genre
    - id: integer, not null, auto increment, primary key
    - name: string, unique
    - createdAt: date
    - updatedAt: date
  - Developer
    - id: integer, not null, auto increment, primary key
    - name: string, not null
    - website: string
    - location: string
    - createdAt: date
    - updatedAt: date
  - Game
    - id: integer, not null, auto increment, primary key
    - developerId: integer
    - title: string, not null
    - writers: string
    - description: string
    - singleplayer: boolean
    - multiplayer: boolean
    - engine: string
    - createdAt: date
    - updatedAt: date
  - Release
    - id: integer, not null, auto increment, primary key
    - gameId: integer, not null
    - platform: enum(win, ps2, ps3, ps4, ps5, xbox360, xboxone)
    - date: date
    - version: string
    - createdAt: date
    - updatedAt: date

- A modellek közötti kapcsolatok:
  - Genre N - N Game
  - Developer 1 - N Game
  - Game 1 - N Release

- Hozd létre az adatbázist, töltsd fel néhány adattal. A kezdőcsomagban ez az `npm run gql-db` paranccsal megtehető.
- Oldd meg a következő feladatokat:
  - `games`: összes játék lekérése, a hozzá tartozó fejlesztővel (Developer), műfajokkal (Genre) és kiadásokkal (Release) együtt **(4 pont)**
    - Kérés:
      ```graphql
      query {
        games {
          id, title, writers description, singleplayer, multiplayer, engine,
          developer { name, website, location },
          genres { name },
          releases { platform, date, version },
        }
      }
      ```
    - Válasz: a minta válaszon is látható módon, meg kell jeleníteni a játék alapadatait, és a hozzá kapcsolódó modelleket (Developer, Genre, Release).
      ```json
      {
      "data": {
        "games": [
          {
            "id": "1",
            "title": "GTA Vice City",
            "writers": "Dan Houser, James Worrall",
            "description": "Welcome to the 1980s. From the decade of big hair, excess, and pastel suits comes a story of one man's rise to the top of the criminal pile as Grand Theft Auto returns.",
            "singleplayer": true,
            "multiplayer": true,
            "engine": "RenderWare",
            "developer": {
              "name": "Rockstar Games",
              "website": "https://www.rockstargames.com/",
              "location": "New York"
            },
            "genres": [
              {
                "name": "Action-adventure"
              }
            ],
            "releases": [
              {
                "platform": "ps2",
                "date": "2002-10-26T22:00:00.000Z",
                "version": "v1"
              },
              {
                "platform": "win",
                "date": "2002-10-26T22:00:00.000Z",
                "version": "v1"
              },
              {
                "platform": "ps3",
                "date": "2013-01-29T23:00:00.000Z",
                "version": "v2"
              }
            ]
          },
          ...
        ]
      }
      ```
  - `randomGame`: egy véletlenszerűen kiválasztott játékot ad meg az adatbázisból (ha nincs játék az adatbázisban, akkor null-t), az adatszerkezet ugyanaz, mint az 1. feladatban **(2 pont)**
    - Kérés
      ```graphql
      query {
        randomGame {
          id, title, writers description, singleplayer, multiplayer, engine,
          developer { name, website, location },
          genres { name },
          releases { platform, date, version },
        }
      }
      ```
    - Válasz
      ```json
      {
        "data": {
          "randomGame": {
            "id": "2",
            "title": "GTA San Andreas",
            "writers": "Dan Houser, James Worrall",
            "description": "Five years ago Carl Johnson escaped from the pressures of life in Los Santos, San Andreas — a city tearing itself apart with gang trouble, drugs, and corruption. Where film stars and millionaires do their best to avoid the dealers and gangbangers.",
            "singleplayer": true,
            "multiplayer": true,
            "engine": "RenderWare",
            "developer": {
              "name": "Rockstar Games",
              "website": "https://www.rockstargames.com/",
              "location": "New York"
            },
            "genres": [],
            "releases": [
              {
                "platform": "ps2",
                "date": "2004-10-25T22:00:00.000Z",
                "version": "USA"
              },
              {
                "platform": "win",
                "date": "2005-06-06T22:00:00.000Z",
                "version": "USA"
              },
              {
                "platform": "win",
                "date": "2005-06-09T22:00:00.000Z",
                "version": "EU"
              },
              {
                "platform": "win",
                "date": "2008-01-03T23:00:00.000Z",
                "version": "Steam"
              },
              {
                "platform": "xbox360",
                "date": "2005-06-06T22:00:00.000Z",
                "version": "USA"
              }
            ]
          }
        }
      }
      ```
  - `latestRelease`: egészítsük ki a játékok lekérését egy olyan mezővel, ami a játékhoz tartozó legújabb kiadást adja meg! A platform nem számít, a lényeg, hogy a kiadás a játékhoz tartozzon, és a `date` mező szerint a legfrissebb legyen! **(2 pont)**
    - Kérés:
        ```graphql
        query {
          games {
            id, title,
            latestRelease { id, date, version }
          }
        }
        ```
    - Válasz:
      ```json
      {
        "data": {
          "games": [
            {
              "id": "1",
              "title": "GTA Vice City",
              "latestRelease": {
                "id": "3",
                "date": "2013-01-29T23:00:00.000Z",
                "version": "v2"
              }
            },
            {
              "id": "2",
              "title": "GTA San Andreas",
              "latestRelease": {
                "id": "7",
                "date": "2008-01-03T23:00:00.000Z",
                "version": "Steam"
              }
            },
            ...
          ]
        }
      }
      ```
  - `stats`: Statisztikai adatok megjelenítése. **(5 pont)**
    - Mezők, amiket meg kell adni:
      - Egyszerűbbek *(2 pont)*
        - *genresCount*: hány műfaj van az adatbázisban
        - *gamesCount*: hány játék van az adatbázisban
        - *multiplayerGames*: hány olyan játék van az adatbázisban, amelyik támogatja a multiplayer módot
        - *singleplayerGames*: hány olyan játék van az adatbázisban, amelyik támogatja a singleplayer módot
      - *windowsReleases, playStationReleases, xboxReleases* *(3 pont)*
        - Egy játék csak egyszer számít a statisztikában.
        - Egy játékhoz több release is tartozhat (akár ugyanahhoz a platformhoz is, tehát egy játékhoz akár tartozhat két Windows release is!)
        - **NEM** az a kérdés, hogy számold meg, pl. hány release-nél Windows a platform!
        - Ha pl. egy játékból (gameId) van 3 db Windows release, az csak 1 Windows release-nek számít a statisztikában.
        - A PS-ek és az Xbox-ok össze vannak vonva: ha egy játékból mondjuk van 2 db. PS2 release és 1 db PS3 release, akkor annyi a lényeg, hogy ez +1 PS a statisztikában, mert a játéknak van legalább 1, valamilyen PS kiadása.
        - Egyszerű eszközökben gondolkodj, ne gondold túl! Az nem baj, ha a megoldásod nem optimális, vagy nem annyira "elegáns"!
    - Kérés:
      ```js
      query {
        stats {
          genresCount
          gamesCount
          multiplayerGames
          singleplayerGames
          windowsReleases
          playStationReleases
          xboxReleases
        }
      }
      ```
    - Válasz (ezek a számok csak a default seedelésnél érvényesek):
      ```json
      {
        "data": {
          "stats": {
            "windowsReleases": 2,
            "playStationReleases": 2,
            "xboxReleases": 1,
            "genresCount": 2,
            "gamesCount": 9,
            "multiplayerGames": 2,
            "singleplayerGames": 9
          }
        }
      }
      ```
  - `addDeveloper(developerData: DeveloperInput!)`: hozzáad egy új játékfejlesztőt az adatbázishoz **(2 pont)**
    - Kérés:
        ```graphql
        mutation {
          addDeveloper(
            developerData: {
              name: "Ubisoft",
              website: "https://www.ubisoft.com/",
              location: "France"
            }
          ) {
            id, name, website, location
          }
        }
        ```
    - Válasz:
      ```json
      {
        "data": {
          "addDeveloper": {
            "id": "3",
            "name": "Ubisoft",
            "website": "https://www.ubisoft.com/",
            "location": "France"
          }
        }
      }
      ```
    - Segítség:
      - [https://graphql.org/graphql-js/mutations-and-input-types/](https://graphql.org/graphql-js/mutations-and-input-types/)
  - `addGame(gameData: GameInput!, genresData: [GenreInput])`: hozzáad egy új játékot az adatbázishoz a megadott adatokkal **(5 pont)**
    - Ha a `developerId`-ben megadott fejlesztő nem létezik, akkor dobj egy hibát `Developer not found` üzenettel.
    - A második paraméterben több műfajt is meg lehet adni. Ha a megadott névvel létezik műfaj, akkor csatoljuk hozzá a játékhoz, egyébként pedig hozzuk létre a műfajt is! Figyelj arra, hogy a műfajok neve egyedi (unique constraint)!
    - Kérés:
      ```graphql
        mutation {
          addGame(
            gameData: {
              title: "Assassin's Creed: Valhalla",
              writers: "Darby McDevitt"
              description: "Assassin's Creed Valhalla is a 2020 action role-playing video game developed by Ubisoft Montreal and published by Ubisoft. It is the twelfth major installment and the twenty-second release in the Assassin's Creed series, and a successor to the 2018's Assassin's Creed Odyssey.",
              multiplayer: false,
              singleplayer: true,
              developerId: 3,
            },
            genresData: {
              name: "Action role-playing"
            }
          ) {
            id, title, writers, description, multiplayer, singleplayer, developer { name }, genres { name }
          }
        }
      ```
    - Válasz: A bolt objektum legyen, a hozzá tartozó árucikkekkel.
      ```json
      {
        "data": {
          "addGame": {
            "id": "9",
            "title": "Assassin's Creed: Valhalla",
            "writers": "Darby McDevitt",
            "description": "Assassin's Creed Valhalla is a 2020 action role-playing video game developed by Ubisoft Montreal and published by Ubisoft. It is the twelfth major installment and the twenty-second release in the Assassin's Creed series, and a successor to the 2018's Assassin's Creed Odyssey.",
            "multiplayer": false,
            "singleplayer": true,
            "developer": {
              "name": "Ubisoft"
            },
            "genres": [
              {
                "name": "Action-adventure"
              }
            ]
          }
        }
      }
      ```
    - Segítség:
      - [https://graphql.org/graphql-js/mutations-and-input-types/](https://graphql.org/graphql-js/mutations-and-input-types/)

## Websocket feladat - Feladatok megoldása és értékelése (10 pont)

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

Tesztelés:
- Teszteléshez használhatod a Socket.IO Client Tool felületet: https://amritb.github.io/socketio-client-tool/
- Vagy a Firecamp-et (főoldalon Socket.IO)
- Vagy az automata tesztelőt: `npm run gql-ws`

A következő üzenetek legyenek:

- Kliens -> szerver
  - **Fontos**: A kliens a szerver felé úgy küldi az üzeneteket, hogy az első paraméter egy JSON objektum, ami tartalmaz minden paraméter adatot, a második paraméter pedig az [ack](https://socket.io/docs/v4/emitting-events/#Acknowledgements). Tehát pl. az első feladat meghívása így néz ki kliens oldalról:
    ```js
    socket.emit('create-event', { name: 'Név', tasks: [ ... ] }, (ack) => { ... })
    ```
  - `create-event`: Esemény létrehozása. A létrehozó lesz a tanár. Fel kell küldeni az esemény nevét, és a feladatokat. Az eseményt el kell menteni a létrehozó socketid-jával. A feladatokat létre kell hozni a táblában és az eseményhez kell kapcsolni őket. Az esemény uuid-jával térünk vissza. **(2 pont)**
    - Paraméterek:
      1. *name*: esemény neve
      2. *tasks*: feladatok tömbje, pl. `[{"description": "task1"}, {"description": "task2"}]`
    - Válasz
      - Helyes: `{ status: 'ok', uuid: <uuid> }`
      - Hibás: `{ status: 'error', message: <hibaüzenet> }`
  - `join-event`: Csatlakozás eseményhez. A kapcsolódó megad egy uuid-t, és visszakapja a kapcsolódó esemény és feladatok részleteit. **(2 pont)**
    - Paraméterek:
      1. *uuid*: esemény azonosítója
    - Válasz
      - Helyes: `{ status: 'ok', event: <event>, tasks: [<task>] }`
      - Hibás: `{ status: 'error', message: <hibaüzenet> }`
  - `send-solution`: Megoldás küldése egy feladathoz. Meg kell adni egy feladatazonosítót (`taskId`) és egy megoldást szövegként. A `Solutions` táblába fel kell venni egy új rekordot a megoldással és a megoldó socketid-jával. A megfelelő feladathoz kell kapcsolni. A művelet hatására `solution-sent` üzenetet kell küldeni a megoldáshoz tartozó feladathoz tartozó eseményben tárolt socketid-ra az esemény, feladat és megoldás adataival. **(1 pont)**
    - Paraméterek:
      1. *taskId*: feladat azonosítója
      2. *solution*: megoldás szövege
    - Válasz
      - Helyes: `{ status: 'ok' }`
      - Hibás: `{ status: 'error', message: <hibaüzenet> }`
  - `send-evaluation`: Értékelés küldése egy megoldáshoz. Meg kell adni egy megoldásazonosítót (`solutionId`) és egy értékelést szövegként. A `Solutions` táblában az adott rekordhoz menteni kell az értékelést. A művelet hatására `evaluation-sent` üzenetet kell küldeni a megoldáshoz tartozó socketid-ra a megoldás adataival. **(2 pont)**
    - Paraméterek:
      1. *solutionId*: megoldás azonosítója
      2. *evaluation*: értékelés szövege
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
