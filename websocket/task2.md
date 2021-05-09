## 3. feladat: Websocket - Tőzsde (10 pont)
Socket.io segítségével készíts Websocket szervert, amely egy képzeletbeli tőzsde értékpapírjairól közöl valósidejű adatokat. A tőzsde pár előre megadott, beégetett értékpapírt tart nyilván. Mindegyiknek van egy kezdő értéke, és időről időre ezek közül véletlenszerűen valamelyik megváltozik ugyancsak egy véletlen értékkel. A kliensek le tudják kérni a szervertől, hogy milyen értékpapírokat tart nyilván, majd fel tudnak íratkozni értékpapírcsatornákra. Ilyenkor minden változásról automatikusan értesülnek. A feliratkozást meg is lehet szüntetni. A kliensek historikus adatokat is le tudnak kérni egy-egy értékpapírról. Egy értékpapír csatornájában egy kliens ajánlatot küldhet a többi csatornabeli kliensnek. Ekkor a szerver egy azonosítóval látja el az ajánlatot, majd elküldi a többieknek. Ha valamelyik kliens él az ajánlattal, akkor az ajánlatazonosítóval elfogadhatja, és erről az ajánlattevő és -fogadó is értesül.

Szerveren használjuk a `setTimeout`-ot az értékpapír árfolyamának mozgatásához, pl. 1s-onként valamelyik értékpapír ára változzon! A klienseket a socket azonosítójukkal azonosítjuk. Az adatokat a szerver memóriájában tároljuk, nem kell adatbázis! Például az alábbi adatszerkezet használható az értékpapírok tárolására, de más is, ha van kényelmesebb:

```js
const securities = {
  'ELTE': {
    prices: [
      {timestamp: 1610110943147, price: 1234},
      {timestamp: 1610110969348, price: 1255},
      {timestamp: 1610110976805, price: 1340},
    ],
    offers: [
      {clientId: 'client1', quantity: 0.65, intent: 'sell', active: true},
      {clientId: 'client3', quantity: 2.0, intent: 'buy', active: false},
    ]
  }
  'TESLA': {
    // ...
  },
  'RICHTER': {
    // ...
  },
  // ...
}
```

A következő üzenetek legyenek:

- **Kliens -> szerver**
  - `list-securities`: Ezzel kérdezhetjük le a szerverről, hogy milyen értékpapírokat tart nyilván. Hibát nem kell kezelni. (1 pont)
    - Paraméterek: nincs
    - Válasz (acknowledgement)
      ```
      { status: 'ok', securities: ['ELTE', 'TESLA', ...] }
      ```
  - `get-historic-data`: Egy adott értékpapírhoz tartozó előzményadatok lekérdezése. Paraméterként lehet megadni az értékpapír nevét, valamint az előzményadatok számát. Ha nincs ilyen értékpapír, vagy a szám nem szám vagy kisebb mint 0, akkor hibaüzenetet kapunk. (1 pont)
    - Paraméterek: 
      - értékpapír azonosítója/neve, pl. `ELTE`
      - előzményadatok száma, pl. 7
    - Válasz (acknowledgement)
      - Helyes: 
        ```js
        { status: 'ok', prices: [
          {timestamp: 1610110943147, price: 1234},
          {timestamp: 1610110969348, price: 1255},
          {timestamp: 1610110976805, price: 1340},
        ]}
        ```
      - Hibás: `{ status: 'error', message: <hibaüzenet>}`
  - `join-security`: Ezzel iratkozhatunk fel egy értékpapír csoportjába. Ha már feliratkoztunk, akkor az hibaüzenetként jelenik meg. Hiba az is, ha nem létező értékpapírnevet adunk meg. (1 pont)
    - Paraméterek: értékpapír azonosítója/neve, pl. `ELTE`.
    - Válasz (acknowledgement)
      - Helyes: `{ status: 'ok'}`
      - Hibás: `{ status: 'error', message: 'No such security in our system.'}`
      - Hibás: `{ status: 'error', message: 'You are already subscribed to this security.'}`
  - `leave-security`: Ezzel iratkozhatunk le egy értékpapír csoportjáról. Ha eleve nem figyeljük, akkor az hibaüzenetként jelenik meg. Hiba az is, ha nem létező értékpapírnevet adunk meg. (1 pont)
    - Paraméterek: értékpapír azonosítója/neve, pl. `ELTE`.
    - Válasz (acknowledgement)
      - Helyes: `{ status: 'ok'}`
      - Hibás: `{ status: 'error', message: 'No such security in our system.'}`
      - Hibás: `{ status: 'error', message: 'You do not follow this security.'}`
  - `send-offer`: Ajánlat küldése egy feliratkozott értékpapírhoz. Hiba, ha rossz értékpapírnevet adtunk meg, vagy nem vagyunk feliratkozva az értékpapírra. A szerver rendel hozzá egy azonosítót (ez lehet a tömbindex is), elmenti, majd a csoportba tartozó többi kliensnek `offer-sent` üzenetet kell küldeni az ajánlat adataival. (1 pont)
    - Paraméterek: 
      - értékpapír azonosítója/neve, pl. `ELTE`
      - mennyiség, pl. 0.65
      - szándék: `'buy'`/`'sell'`
    - Válasz (acknowledgement)
      - Helyes: `{ status: 'ok'}`
      - Hibás: `{ status: 'error', message: <hibaüzenet>}`
  - `accept-offer`: Az ajánlat elfogadása. Meg kell adni az ajánlat azonosítóját, a szerver az ajánlatot inaktívvá teszi (`active` flag beállításával), majd mindkét érintett kliensnek `offer-accepted` üzenetet küld. Hiba akkor van, ha nincs olyan értékpapír, illetve azon belül nincs olyan ajánlatazonosító. (1 pont)
    - Paraméterek: 
      - értékpapír azonosítója/neve, pl. `ELTE`
      - ajánlatazonosító, pl. 2
    - Válasz (acknowledgement)
      - Helyes: `{ status: 'ok'}`
      - Hibás: `{ status: 'error', message: <hibaüzenet>}`
- **Szerver -> kliens**
  - `price-changed`: Akkor kapunk ilyen hibaüzenetet, ha egy figyelt értékpapír ára változik. Mindenki megkapja az értékpapír csoportjában. (2 pont)
    ```js
    {
      security: 'ELTE',
      price: 1635
    }
    ```
  - `offer-sent`: ha valaki egy értékpapírcsoportban ajánlatot tesz, akkor a többi csoporttag ilyen üzenetet kap. (1 pont)
    ```js
    {
      id: 2,
      security: 'ELTE',
      quantity: 0.65, 
      intent: 'sell'
    }
    ```
  - `offer-accepted`: Ha egy ajánlat elfogadásra kerül, akkor mind az ajánlattevő, mind a -fogadó ilyen üzenetet kap. (1 pont)
    ```js
    {
      id: 2,
      security: 'ELTE',
      quantity: 0.65, 
      intent: 'sell'
    }
    ```