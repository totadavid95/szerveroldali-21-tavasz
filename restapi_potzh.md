# Szerveroldali webprogramozás zh -- REST API pótzh

_2021. június 03._

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
    npm run db
    # Fejlesztői verzió futtatása:
    npm run dev
    ```
  - Ellenőrzéshez és teszteléshez a Node.js _14.x_, az npm _7.x_, és a Firecamp _1.4.x_ verzióját fogjuk használni.

## Hasznos linkek

- Dokumentációk
  - [Socket.IO dokumentáció](https://socket.io/docs/v4/server-api/)
  - [ExpressJS dokumentáció](https://expressjs.com/en/4x/api.html)
  - [Sequelize dokumentáció](https://sequelize.org/master/)
  - [Firecamp Chrome kiegészítő](https://chrome.google.com/webstore/detail/firecamp-a-campsite-for-d/eajaahbjpnhghjcdaclbkeamlkepinbl)
  - [DB Browser for SQLite](https://sqlitebrowser.org/)

## Tartalomjegyzék

- [Szerveroldali webprogramozás zh -- REST API pótzh](#szerveroldali-webprogramozás-zh----rest-api-pótzh)
  - [Tudnivalók](#tudnivalók)
  - [Hasznos linkek](#hasznos-linkek)
  - [Tartalomjegyzék](#tartalomjegyzék)
  - [Kezdőcsomag](#kezdőcsomag)
  - [Feladatsor](#feladatsor)

## Kezdőcsomag
Segítségképpen készítettünk egy kezdőcsomagot a zárthelyi elkészítéséhez. Csak telepíteni kell a csomagokat, és kezdheted is a fejlesztést.
- A kezdőcsomag a Teams csoport Általános csatornáján érhető el.
- **A kezdőcsomag tartalmaz egy nyilatkozatot (statement.txt), amelyben a <NÉV> és a <NEPTUN> részeket helyettesítsd be a saját neveddel és Neptun kódoddal! Ha beadod a feladatot, azzal elfogadod és magadra nézve kötelezőnek tekinted a nyilatkozatot.**

## Feladatsor
Készítsd el az alábbi REST API feladatot Node.js, ExpressJS és SQLite3 segítségével! A szerver a 4000-es porton fusson!
- Az alábbi modellek és kapcsolatok alapján hozd létre a megfelelő migrációkat, modelleket és a seedert, amelyek alapján az adatbázis előállítható és feltölthető adatokkal! **(6 pont)**
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
      - platform: [enum](https://sequelize.org/master/class/lib/data-types.js~ENUM.html)(win, ps2, ps3, ps4, ps5, xbox360, xboxone), not null
      - date: date, not null
      - version: string
      - createdAt: date
      - updatedAt: date

  - A modellek közötti kapcsolatok:
    - Genre N - N Game
    - Developer 1 - N Game
    - Game 1 - N Release

  - Tippek:
    - *[Sequelize CLI használata](https://github.com/sequelize/cli/blob/master/README.md#usage)*
    - *Ha az enumot nem tudod CLI-vel megcsinálni, akkor hozd létre stringként, és utólag írd át enum-ra!*
    - *Ne felejtsd el, hogy az N-N kapcsolatokhoz kapcsolótábla szükséges!*
    - *Ügyelj az elnevezésekre, és következetesen használd őket, különben hibába fogsz futni!*

- `GET /games/titles`: Az adatbázisban lévő játékok címeinek lekérése egy tömbben. **(1 pont)**
  - Kérés: [http://localhost:4000/games/titles](http://localhost:4000/games/titles)
  - Válasz:
    - 200 OK:
      ```json
      [
        "GTA Vice City",
        "GTA San Andreas"
      ]
      ```
    - Ha nincsenek játékok, üres tömb jöjjön vissza
- `GET /genres`: Az adatbázisban lévő műfajok lekérése, kiegészítve a hozzájuk tartozó játékokkal (games mező), ami tartalmazza a követketőket: játék id-je, címe. **(2 pont)**
  - Kérés: [http://localhost:4000/genres](http://localhost:4000/genres)
  - Válasz:
    - 200 OK:
      ```json
      [
        {
          "id": 1,
          "name": "Action-adventure",
          "createdAt": "2021-06-01T15:12:30.313Z",
          "updatedAt": "2021-06-01T15:12:30.313Z",
          "Games": [
            {
              "id": 1,
              "title": "GTA Vice City"
            }
          ]
        }
      ]
      ```
- `GET /games/:id`: Egy adott játékot ad vissza annak minden mezőjével, továbbá kiegészíti azt két további mezővel: `genres` és `releases`, amelyek a játékhoz tartozó műfajok és kiadások összes adatát tartalmazzák. **(4 pont)**
  - Válasz:
    - 200 OK:
      ```json
      {
        "id": 1,
        "title": "GTA Vice City",
        "writers": "Dan Houser, James Worrall",
        "description": "Welcome to the 1980s. From the decade of big hair, excess, and pastel suits comes a story of one man's rise to the top of the criminal pile as Grand Theft Auto returns.",
        "singleplayer": true,
        "multiplayer": true,
        "engine": "RenderWare",
        "createdAt": "2021-06-01T10:44:35.443Z",
        "updatedAt": "2021-06-01T10:44:35.443Z",
        "developerId": 1,
        "Genres": [
          {
            "id": 1,
            "name": "Action-adventure",
            "createdAt": "2021-06-01T10:44:35.472Z",
            "updatedAt": "2021-06-01T10:44:35.472Z"
          }
        ],
        "Releases": [
          {
            "id": 1,
            "platform": "ps2",
            "date": "2002-10-26T22:00:00.000Z",
            "version": "v1",
            "createdAt": "2021-06-01T10:44:35.450Z",
            "updatedAt": "2021-06-01T10:44:35.450Z",
            "gameId": 1
          },
          {
            "id": 2,
            "platform": "win",
            "date": "2002-10-26T22:00:00.000Z",
            "version": "v1",
            "createdAt": "2021-06-01T10:44:35.457Z",
            "updatedAt": "2021-06-01T10:44:35.457Z",
            "gameId": 1
          },
          {
            "id": 3,
            "platform": "ps3",
            "date": "2013-01-29T23:00:00.000Z",
            "version": "v2",
            "createdAt": "2021-06-01T10:44:35.465Z",
            "updatedAt": "2021-06-01T10:44:35.465Z",
            "gameId": 1
          }
        ]
      }
      ```
- `POST /games`: Játék létrehozása. Megadjuk legalább a játék kötelező mezőit (pl. a title non-nullable, tehát azt mindenképp meg kell adni, viszont a writers-t nem muszáj megadni, az lehet null). A feladat további része, hogy a végpont tudjon fogadni egy `genres` nevű mezőt is, ahol egy tömbben, szöveggként felsoroljuk az egyes műfajok neveit, amik a játékhoz tartoznak. Mivel a műfaj neve egyedi, ezért kétszer ugyanazzal a névvel nem hozható létre műfaj, viszont elképzelhető, hogy amit itt megadunk, azzal a névvel már létezik műfaj. Ezt le kell tudni kezelni hiba nélkül úgy, hogy végeredményében a kérésben megadott műfajok legyenek a játékhoz rendelve! Tehát amelyik még nem létezik, az létrejöjjön és a játékhoz rendelődjön, amelyik pedig már létezik, az csak rendelődjön a játékhoz! **(7 pont)**
  - Kérés:
    ```json
    {
      "game": {
        "title": "CS:GO",
        "description": "Counter-Strike: Global Offensive (CS: GO) expands upon the team-based action gameplay that it pioneered when it was launched 19 years ago. CS: GO features new maps, characters, weapons, and game modes, and delivers updated versions of the classic CS content (de_dust2, etc.).",
        "singleplayer": true,
        "multiplayer": true,
        "engine": "Source"
      },
      "genres": ["fps"]
    }
    ```
  - Válasz:
    - 400 Bad Request: rossz kérés esetén, vagyis ha pl. nem lett megadva kötelező mező (pl. title)
    - 201 Created: vissza kell adni a létrejött játékot, és a hozzá tartozó műfajokat:
      ```json
      {
        "game": {
          "id": 3,
          "title": "CS:GO",
          "description": "Counter-Strike: Global Offensive (CS: GO) expands upon the team-based action gameplay that it pioneered when it was launched 19 years ago. CS: GO features new maps, characters, weapons, and game modes, and delivers updated versions of the classic CS content (de_dust2, etc.).",
          "singleplayer": true,
          "multiplayer": true,
          "engine": "Source",
          "updatedAt": "2021-06-01T10:51:43.556Z",
          "createdAt": "2021-06-01T10:51:43.556Z"
        },
        "genres": {
          "exists": [],
          "created": [
            {
              "id": 2,
              "name": "fps",
              "updatedAt": "2021-06-01T14:33:13.787Z",
              "createdAt": "2021-06-01T14:33:13.787Z"
            }
          ]
        }
      }
      ```
    - A válaszban a műfajok legyenek szétválogatva a példán látható módon. Az `exists` azon műfajokat jelöli, amelyeket nem kellett létrehozni, csak hozzácsatolni a játékhoz, a `created` pedig értelemszerűen azokat, amelyek a kérés előtt nem léteztek és létre kellett őket hozni, majd utána csatolni a játékhoz.
  - Segítség:
    - *[Shorthand syntax for Op.in](https://sequelize.org/master/manual/model-querying-basics.html#shorthand-syntax-for--code-op-in--code-)*
    - *ValidationError használata (importálni is kell!):*
      ```js
      try {
        // ...
      } catch (e) {
        if (e instanceof ValidationError) return res.status(400).send(e);
        // Ha nem egyezett a hiba, simán "tovább dobjuk"
        throw e;
      }
      ```
- `PATCH /games/:id/genres`: Játékhoz tartozó műfajok módosítása. A kívánt műfajok listáját az előző feladathoz hasonlóan egy tömbben kell felküldeni. A lényeg, hogy utána csak a megadott műfajok legyenek a játékhoz rendelve. A régi műfajokat nem kell törölni, csak leválasztani (megszüntetni a kapcsolatot a műfaj és a játék között). Ha üres tömböt küldünk fel, az azt jelenti, hogy az összes műfajt le kell választani a játékról!  **(5 pont)**
  - Kérés:
    ```json
    {
      "genres": ["fps", "action"]
    }
    ```
  - Válasz:
    - 404 Not Found: nem létező játék esetén
    - 200 OK: vissza kell adni a játékot, és a hozzá tartozó műfajokat:
      ```json
      {
        "game": {
          "id": 3,
          "title": "CS:GO",
          "writers": null,
          "description": "Counter-Strike: Global Offensive (CS: GO) expands upon the team-based action gameplay that it pioneered when it was launched 19 years ago. CS: GO features new maps, characters, weapons, and game modes, and delivers updated versions of the classic CS content (de_dust2, etc.).",
          "singleplayer": true,
          "multiplayer": true,
          "engine": "Source",
          "createdAt": "2021-06-01T14:33:13.774Z",
          "updatedAt": "2021-06-01T14:33:13.774Z",
          "developerId": null
        },
        "genres": {
          "exists": [
            {
              "id": 2,
              "name": "fps",
              "createdAt": "2021-06-01T10:51:29.266Z",
              "updatedAt": "2021-06-01T10:51:29.266Z"
            }
          ],
          "created": [
            {
              "id": 3,
              "name": "action",
              "updatedAt": "2021-06-01T14:41:10.437Z",
              "createdAt": "2021-06-01T14:41:10.437Z"
            }
          ]
        }
      }
      ```
    - Itt ugye az fps már hozzá volt adva (lásd előző példa), ezért az az `exists`-be kerül, az action viszont új, így az a `created`-be.
- `POST /games/:id/releases`: Új kiadás felvétele egy adott játékhoz. **(3 pont)**
  - Kérés:
    ```json
    {
      "platform": "win",
      "date": "2012-08-21",
      "version": "initial"
    }
    ```
  - Válasz:
    - 404 Not Found: nem létező játék esetén
    - 400 Bad Request: érvénytelen platform esetén
    - 200 OK: vissza kell adni a kiadást:
      ```json
      {
        "id": 9,
        "platform": "win",
        "date": "2012-08-21T00:00:00.000Z",
        "version": "initial",
        "gameId": 1,
        "updatedAt": "2021-06-01T14:58:33.204Z",
        "createdAt": "2021-06-01T14:58:33.204Z"
      }
      ```
  - Segítség:
    - Mivel az SQLite nem ismer natívan enum-ot, ezért a validátornak meg kell mondani, hogy csak a lehetséges értékeket veheti fel a platform mező (`models` mappán belül a `release.js`-ben). Ha ez nem teljesül, akkor ValidationError fog keletkezni:
      ```js
      platform: {
        type: DataTypes.ENUM('win', 'ps2', 'ps3', 'ps4', 'ps5', 'xbox360', 'xboxone'),
        validate: {
          isIn: {
            args: [['win', 'ps2', 'ps3', 'ps4', 'ps5', 'xbox360', 'xboxone']],
            msg: "Invalid platform"
          },
        },
      },
      ```
- `DELETE /games`: Játék törlése. A játék id-jét a request body-ban küldjük fel, *NEM* URL paraméterben! **(2 pont)**
  - Kérés:
    ```json
    {
      "id": 2
    }
    ```
  - Válasz:
    - 400 Bad Request: Ha nincs megadva a játék ID-je
    - 404 Not Found: nem létező játék esetén
    - 200 OK: vissza kell adni a törölt játék adatait:
      ```json
      {
        "id": 2,
        "title": "GTA San Andreas",
        "writers": "Dan Houser, James Worrall",
        "description": "Five years ago Carl Johnson escaped from the pressures of life in Los Santos, San Andreas — a city tearing itself apart with gang trouble, drugs, and corruption. Where film stars and millionaires do their best to avoid the dealers and gangbangers.",
        "singleplayer": true,
        "multiplayer": true,
        "engine": "RenderWare",
        "createdAt": "2021-06-01T14:51:38.391Z",
        "updatedAt": "2021-06-01T14:51:38.391Z",
        "developerId": 1
      }
      ```
