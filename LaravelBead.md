# Szerveroldali webprogramozás beadandó
## Beadandó két felvonásban

A feladatod az alábbi beadandó feladat megvalósítása. Az elkészült beadandókat a [Canvas rendszeren](https://canvas.elte.hu/courses/17499) keresztül kell majd beadni.

## Tartalomjegyzék
- [Szerveroldali webprogramozás beadandó](#szerveroldali-webprogramozás-beadandó)
  - [Beadandó két felvonásban](#beadandó-két-felvonásban)
  - [Tartalomjegyzék](#tartalomjegyzék)
  - [Modellek](#modellek)
  - [Adatmodellek közötti relációk](#adatmodellek-közötti-relációk)
  - [Metódus ötletek](#metódus-ötletek)
  - [Szerepkörök](#szerepkörök)
  - [1. felvonás](#1-felvonás)
    - [1. felvonás pontjai:](#1-felvonás-pontjai)
  - [2. felvonás (30 pont)](#2-felvonás-30-pont)
    - [2. felvonás pontjai:](#2-felvonás-pontjai)
  - [Követelmények](#követelmények)
  - [Alkotói szabadság](#alkotói-szabadság)
  - [Segítségkérés, konzultációs alkalmak](#segítségkérés-konzultációs-alkalmak)
  - [Határidők, késés](#határidők-késés)
  - [A munka tisztasága](#a-munka-tisztasága)
  - [Hasznos hivatkozások](#hasznos-hivatkozások)
  - [Változásnapló](#változásnapló)
      - [2021. május 19.](#2021-május-19)
      - [2021. április 11.](#2021-április-11)
      - [2021. április 10.](#2021-április-10)
      - [2021. április 06.](#2021-április-06)
      - [2021. március 19.](#2021-március-19)

## Modellek
- Az alábbi modelleket kell létrehozni a megadott mezőkkel és tulajdonságokkal.
  - User (Felhasználó) 
      - *Tipp: Ez a tábla már alapból létre van hozva, csak ki kell egészíteni*
    - id
    - name (string)
    - email (string, unique)
    - email_verified_at (timestamp, nullable)
    - password (string)
    - is_librarian (boolean, default:false)
    - remember_token
    - timestamps (created_at, updated_at)
  
  - Book (Könyv)
    - id
    - title (string, 255)
    - authors (string, 255)
    - description (text, nullable)
    - released_at (date)
    - cover_image (string, 255, nullable)
    - pages (integer)
    - language_code (string, 3, default:hu)
    - isbn (string, 13, unique)
    - in_stock (integer)
    - timestamps (created_at, updated_at)
  
  - Genre (Műfaj)
    - id
    - name (string, 255)
    - style (enum: primary, secondary, success, danger, warning, info, light, dark)
    - timestamps (created_at, updated_at)
  
  - Borrow (Kölcsönzési igény / kölcsönzés)
    - id
    - reader_id (unsignedBigInteger, foreign, references:id, on:users)
        - *Melyik olvasóhoz kapcsolódik a kölcsönzés*
    - book_id (unsignedBigInteger, foreign, references:id, on:books)
        - *Melyik könyvhöz kapcsolódik a kölcsönzés*
    - reader_message (string, 255, nullable)
      - *Az olvasó megjegyzést tud fűzni a kölcsönzési igényéhez, amelyet a könyvtáros majd látni fog, amikor feldolgozza azt*
    - status (enum: PENDING, ACCEPTED, REJECTED, RETURNED)
        - Mi a kölcsönzés jelenlegi állapota:
            - *PENDING: függőben van (tehát az olvasó kérvényezte, hogy kikölcsönözhesse az adott könyvet)*
            - *REJECTED: a könyvtáros elutasította az olvasó kölcsönzési igényét*
            - *ACCEPTED: a könyvtáros elfogadta az olvasó kölcsönzési igényét, tehát a kölcsönzés úgymond aktív, tehát éppen az olvasónál van a könyv*
            - *RETURNED: az olvasó visszajuttatta a könyvet a könyvtárnak, a státusz akkor változik erre, ha a könyvtáros ezt lekönyveli*
    - request_processed_at (datetime, nullable)
        - *Azt mondja meg, hogy a kölcsönzési IGÉNY mikor lett feldolgozva (tehát PENDING-ről REJECTED-re vagy ACCEPTED-re állítva)*
    - request_managed_by (unsignedBigInteger, nullable, foreign, references:id, on:users)
        - *Azt mondja meg, hogy melyik könyvtáros kezelte le a fentebb is említett kölcsönzési igényt*
    - request_processed_message (string, 255, nullable)
        - *A kölcsönzési igény feldolgozásakor a könyvtáros megadhat egy üzenetet ezen keresztül az olvasó felé*
    - deadline (datetime, nullable)
        - *Ha a könyvtáros elfogadja a kölcsönzési igényt, akkor megadhat egy határidőt, ameddig a könyvet az olvasónak vissza kell juttatnia*
    - returned_at (datetime, nullable)
        - *Azt mondja meg, hogy a könyv visszajuttatása mikor lett adminisztrálva a könyvtáros részéről*
    - return_managed_by (unsignedBigInteger, nullable, foreign, references:id, on:users)
        - *Azt mondja meg, hogy melyik könyvtáros adminisztrálta a könyv visszajuttatását*
    - timestamps (created_at, updated_at)
    
## Adatmodellek közötti relációk
- Egy könyvhöz (Book) akármennyi műfaj (Genre) tartozhat és egy műfajhoz (Genre) is akármennyi könyv (Book) tartozhat, vagyis:
  - **Book N-N Genre**
- A kölcsönzéshez (Borrow) az alábbi kapcsolódási lehetőségek vannak:
  - Az olvasó (User), aki a kölcsönzést kérvényezte:
    - **User 1-N Borrow (reader)**
  - A könyv (Book), amit az olvasó ki akar kölcsönözni:
    - **Book 1-N Borrow (book)**
  - A könyvtáros (User), aki feldolgozza a kölcsönzési kérvényt:
    - **User 1-N Borrow (request_manager)**
  - A könyvtáros (User), aki adminisztrálja a könyv visszajuttatását:
    - **User 1-N Borrow (return_manager)**
- Fontos, hogy mivel a kölcsönzés (Borrow) több módon is kapcsolódik a felhasználók (User) táblához, ezért ezeket szét kell választani oly módon, hogy a rendszer egyértelműen különbséget tudjon köztük tenni, és ne keveredjenek össze!

## Metódus ötletek
- A modellekhez lehetőséged van egyéni metódusokat létrehozni azokon túl, amiket gyakorlatokon néztük.
- Az alábbiakban adunk pár ötletet, hogy milyen funkciókat ellátó metódusokat lehet érdemes létrehozni a modellekben.
- Könyv modell (Book):
  - Kölcsönzések lekérése
  - Műfajok lekérése
  - AKTÍV kölcsönzések lekérése (vagyis ami el lett fogadva, de még nem vitték vissza)
  - Rendelkezésre álló mennyiség lekérése (ez kiszámolható az in_stock és a fentiek alapján)
  - Elérhető-e a könyv (ez megadható a fentebbi pontból)
  - Borítókép URL-jének lekérése, ha nincs, akkor pl. default placeholder képre mutató URL visszaadása
- Kölcsönzés (Borrow):
  - Olvasó lekérése
  - Könyv lekérése
  - Kezelő (kérvényt elutasító/elfogadó és a visszajuttatást lekezelő) könyvtárosok lekérése
  - Kölcsönzés kezelése (elfogadás, elutasítás, visszajuttatás)
    - Hintek, amiket nem árt végiggondolni:
      - pl. egy elutasított (REJECTED) kölcsönzést értelmetlen lenne visszavinni, így ezeket a lehetőségeket szűrni kell!
      - egy RETURNED állapotú kölcsönzést ne lehessen elfogadni, stb.
- Műfaj (Genre):
  - Könyvek lekérése
- Felhasználó (User):
  - Könyvtáros-e?
  - Mint könyvtáros, melyik kölcsönzéseket kezelte le?
  - Mint olvasó, milyen kölcsönzései vannak?

## Szerepkörök
- Az alkalmazás felhasználói az alábbi szerepköröket vehetik fel:
  - Vendég
    - Nem bejelentkezett felhasználó, aki egyszerű vendégként böngészi az alkalmazást
  - Olvasó
    - Bejelentkezett felhasználó, de nem könyvtáros
  - Könyvtáros
    - Bejelentkezett felhasználó és könyvtáros ranggal bír

## 1. felvonás
- Időpontok:
  - **Határidő: 2021. április 18. (vasárnap) 23:59:59**
  - Konzultációk: 
    - 2021\. április 03. (szombat) 14:00, Teams Általános csatorna
    - 2021\. április 16. (péntek) 16:00, Teams Általános csatorna
- Feladatok:
  - Laravel alkalmazás elkészítése
    - Hozz létre egy új Laravel alkalmazást, amelyben a beadandót fejleszteni fogod. Az alkalmazás verziója *legalább* Laravel 7 kell, hogy legyen, de érdemes a jelenleg elérhető legújabb verziót, a 8-ast használni!
  - Felhasználói felület elkészítése
    - Telepítsd fel a [Laravel UI](https://github.com/laravel/ui) csomagot, majd ezzel a paranccsal hozd létre a felhasználói felületet:
    `php artisan ui bootstrap --auth`
    - *Tipp: Az --auth kapcsoló miatt rögtön kigenerálja az alap layoutot (layouts/app.blade.php), illetve egy teljesen működőképes autentikációs rendszert is, regisztrációs, bejelentkező, stb. oldalakkal együtt.*
  - Adatbázis beállítása
    - Az alkalmazást úgy állítsd be, hogy SQLite3 adatbázist használjon, amelyet ez a fájl biztosítson: database/database.sqlite
  - Időzóna beállítása
    - Az alkalmazás időzónája az alap UTC helyett legyen magyarországi időre beállítva.
  - Adatbázis létrehozása:
    - *Tipp: Ezt a feladatot érdemes végig nyomon követni parancssori eszközökkel, Tinkerben rendszeresen ellenőrizni, stb.*
    - Hozd létre a feladat elején részletezett adatmodelleket, és a hozzájuk kapcsolódó egyéb dolgokat (migration, factory, seeder)!
    - A mezők nevét és tulajdonságait pontosan kövesd, ahogy azt a feladat leírja!
    - A factory-nál érdemes a [Faker](https://fakerphp.github.io/)-t használni. A kölcsönzéshez (Borrow) nem muszáj Factory-t írni, elég, ha a seederben megfelelően hozod létre.
    - A seeder *értelmesen* töltse fel az alkalmazást adatokkal, vagyis:
      - hozzon létre minden modellből néhányat (nem kell sokat, pl. felesleges 100 könyvet létrehozni);
      - a létrehozott modellek között teremtsen kapcsolatot is, tehát pl. a könyvek és a műfajok között, valamint legyenek példa kölcsönzések is különböző véletlenszerű státuszokkal.
    - **Fontos, hogy a könnyű tesztelés érdekében a seeder a felhasználókat az alábbi minta szerint hozza létre! Légyszíves ne találj ki mindenféle felhasználónevet!**
      - *Név: olvaso1, E-mail: olvaso1@szerveroldali.hu, Jelszó: password*
      - *Név: olvaso2, E-mail: olvaso2@szerveroldali.hu, Jelszó: password*
      - ....
      - *Név: konyvtaros1, E-mail: konyvtaros1@szerveroldali.hu, Jelszó: password*
      - *Név: konyvtaros2, E-mail: konyvtaros2@szerveroldali.hu, Jelszó: password*
      - ....
    - A seeder esetében az is jó megoldás, ha mindent a DatabaseSeeder.php-ba írsz, ha úgy egyszerűbb (hiszen pl. a create visszaadja a létrehozott modelleket, stb.)
    - Miután elkészültél a modellekkel, és rendelkeznek a kért mezőkkel, valamint a kapcsolatokat is létrehoztad, utána érdemes az adatmodellekhez tartozó metódusokat is megcsinálni, és azokat is tesztelni. Így a felhasználói felület fejlesztésének úgy állhatsz neki, hogy az alkalmazásodnak már van egy stabil adatbázisa.
  - Felhasználói felület elkészítése:
    - *Tipp: hozz létre [Resource controller-eket](https://laravel.com/docs/8.x/controllers#resource-controllers) a műfajhoz és a könyvekhez!*
    - A következő route-ok: **GET /** és **GET /home**, irányítsanak át erre az útvonalra: **books.index**, ez lesz a főoldal
    - Főoldal (**GET /books**)
      - *Könyvek megjelenítése*
        - A könyvek egy olyan szülő div-ben legyenek, aminek az id mezője **#books**
        - Az a div, ami a könyvet tartalmazza, legyen ellátva egy **.book** stílusosztállyal.
        - A könyv adatai (pl cím, szerző, stb.) ezekkel a stílusosztályokkal legyenek ellátva (a tag mindegy):
          - **.book-title, .book-author, .book-date, .book-description**
          - A könyv kiadása itt ilyen formátumban jelenjen meg: *ÉÉÉÉ. HH. NN.*
        - A könyvhöz legyen hozzárendelve egy gomb, ami az adott könyv adatlapját nyitja meg, ehhez egy olyan gombot/hivatkozást rendelj hozzá, ami a **.book-details** stílusosztállyal rendelkezik.
        - A könyvek oldalanként jelenjenek meg úgy, hogy egy oldalon 9 könyv legyen, ehhez lásd: [Pagination](https://laravel.com/docs/8.x/pagination)
          - Az oldalak közti lapozás így jelenik meg az URL-ben:
            - **GET /books?page={oldalszám}**
      - *Műveletek megjelenítése*
        - Jeleníts meg két gombot: *Új műfaj* és *Új könyvek*
          - A gombok azonosítója a következő legyen: **#create-genre-btn**, **#create-book-btn**
          - Ezek a gombok (lehet **button** vagy **a** is) szolgáljanak hivatkozásként a megfelelő formokhoz!
      - *Statisztika megjelenítése*
        - Jelenítsd meg az adatbázis statisztikákat valahol a főoldalon. Meg kell jelenítened, hogy mennyi felhasználó, műfaj és könyv van az adatbázisban. A számérték egy **span** tag-ben legyen, ami az alábbi azonosítókkal rendelkezik:
          - Felhasználók: **#stats-users**
          - Műfajok: **#stats-genres**
          - Könyvek: **#stats-books**
          - Pl.: `<span id="stats-users">7</span>`
      - *Műfajok megjelenítése*
        - Jelenítsd meg az elérhető műfajokat hivatkozható forumában a következő módon:
          - Az a szülő div, amelyben a műfajok vannak, ezzel a stílusosztállyal rendelkezzen: **.genres-list**
          - Ezen a div-en belül pedig legyenek különböző **a** tag-ek (Bootstrap esetén lehet pl. *badge*), amelyek az adott műfajra mutatnak.
    - Könyv adatlapja (**GET /books/{book}**)
      - Ez az oldal egy könyv adatlapját mutatja meg, ami megjeleníti a könnyvel kapcsolatos főbb adatokat.
      - Az oldal így épüljön fel:
        - *Hivatkozás a könyvekhez*
          - Legyen egy hivatkozás, ami visszavisz a könyvek listájához
            - **a#all-books-ref**
        - *Könyv adatai*
          - Könyv címe: **#book-title** azonosító
          - Könyv műfajai: **#book-genres** azonosító
            - Ezen a div-en belül pedig legyenek különböző **a** tag-ek (Bootstrap esetén lehet pl. *badge*), amelyek az adott műfajra mutatnak.
          - Szerzők: **#book-authors**
          - Kiadás dátuma: **#book-date**
          - Oldalak száma: **#book-pages**
          - Nyelv: **#book-lang**
          - ISBN: **#book-isbn**
          - Készletszám: **#book-in-stock**
          - Jelenleg kikölcsönözve: **#book-borrowed**
          - Könyv leírása: **#book-description**
        - *Könyvvel kapcsolatos műveletek*
          - Legyen egy szülő div-je, aminek az id-je **#book-actions**
            - Ebben legyen két gomb: *Módosítás* és *Törlés*
            - A gombok azonosítója a következő legyen: 
              - **#edit-book-btn**: ez egy *a* tag legyen, ami linkként szolgál a szerkesztő formra (**GET /books/{book}/edit**)
              - **#delete-book-btn**: ez egy form része legyen, ami rendelkezzen *csrf* mezővel, és az *action*-je a **DELETE /books/{book}** legyen.
    - Könyv törlése (**DELETE /books/{book}**)
      - Törölje a paraméterben kapott könyvet.
      - A törlést követően irányítson át a könyvek oldalra (**GET /books**), ahol jelenítsen meg egy üzenetet, hogy sikerült a törlés. 
        - Az üzenet div-jének az id-je ez legyen: **#book-deleted**
        - Az üzenetben jelenjen meg a könyv neve is, pl. egy span-ben, a lényeg, hogy az id-je ez legyen: **#book-name**
    - Új műfaj létrehozása (**GET /genres/create**)
      - Ezen az oldalon lehet új műfajt létrehozni (űrlap megjelenítés)
      - Legyen egy hivatkozás, ami visszavisz a könyvek listájához
        - **a#all-books-ref**
      - Az űrlap POST metódussal legyen elküldve ide: **POST /genres/store**
      - Az űrlap mezői:
        - Műfaj neve:
          - input mező neve: **name**
          - validációs szabályok:
            - muszáj megadni
            - legalább 3 hosszú
            - maximum 255 hosszú
        - Műfaj stílusa:
          - input mező neve: **style**
          - validációs szabályok:
            - muszáj megadni
            - ezeket az értékeket veheti fel: *primary, secondary, success, danger, warning, info, light, dark*
        - A form legyen állapottartó, tehát ha elküldjük, és a validatoron nem megy át, akkor az előző értékeket jegyezze meg!
    - Új műfaj eltárolása (**POST /genres/store**)
      - Erre a végpontra küldjük el a műfajt létrehozó formot
      - Végezze el a műfajra megadott validációs szabályokat
      - A kapott adatok alapján tárolja el a műfajt az adatbázisban
      - Ha sikerült a műfaj létrehozása, akkor irányítson vissza a műfajt létrehozó formra, és jelenítsen meg egy üzenetet, hogy a műfajt sikerült létrehozni
        - Az üzenet div-jének id-je ez legyen: **#genre-created**
        - Az üzenetben jelenjen meg a műfaj neve is, pl. egy span-ben, a lényeg, hogy az id-je ez legyen: **#genre-name**
    - Műfaj módosítása (**GET /genres/{genre}/edit**)
      - Ezen az oldalon lehet egy műfajt szerkeszteni (szerkesztő űrlap megjelenítés)
      - Legyen egy hivatkozás, ami visszavisz a könyvek listájához
        - **a#all-books-ref**
      - Az űrlap PATCH metódussal legyen elküldve ide: **PATCH /genres/update**
      - Az űrlap elemei megegyeznek a létrehozással
      - A form jelenítse meg az aktuális műfaj adatait, ezen felül legyen állapottartó, tehát ha elküldjük, és a validatoron nem megy át, akkor az előző értékeket jegyezze meg, ahol pedig ilyen nem volt, oda a default értékeket írja!
    - Műfaj módosításainak eltárolása (**PATCH /genres/update**)
      - Erre a végpontra küldjük el a műfajt frissítő formot
      - Végezze el a műfajra megadott validációs szabályokat
      - A kapott adatok alapján módosítsa a műfajt az adatbázisban
      - Ha sikerült a műfaj módosítása, akkor irányítson vissza a műfajt szerkesztő formra, és jelenítsen meg egy üzenetet, hogy a műfajt sikerült módosítani
        - Az üzenet div-jének id-je ez legyen: **#genre-updated**
        - Az üzenetben jelenjen meg a műfaj neve is, pl. egy span-ben, a lényeg, hogy az id-je ez legyen: **#genre-name**
    - Új könyv létrehozása (**GET /books/create**)
      - Ezen az oldalon lehet új műfajt létrehozni (űrlap megjelenítés)
      - Legyen egy hivatkozás, ami visszavisz a könyvek listájához
        - **a#all-books-ref**
      - Az űrlap POST metódussal legyen elküldve ide: **POST /books/store**
      - Az űrlap mezői:
        - Könyv címe:
          - input mező neve: **title**
          - input mező típusa: text
          - validációs szabályok:
            - muszáj megadni
            - legalább 3 hosszú
            - maximum 255 hosszú
        - Könyv szerzői:
          - input mező neve: **authors**
          - input mező típusa: text
          - validációs szabályok:
            - muszáj megadni
            - legalább 3 hosszú
            - maximum 255 hosszú
        - Kiadás dátuma:
          - input mező neve: **released_at**
          - input mező típusa: date
          - validációs szabályok:
            - muszáj megadni
            - dátum
            - az aktuális dátum előtt legyen
        - Oldalak száma:
          - input mező neve: **pages**
          - input mező típusa: number
          - validációs szabályok:
            - muszáj megadni
            - legalább 1
        - ISBN:
          - input mező neve: **isbn**
          - input mező típusa: text
          - validációs szabályok:
            - muszáj megadni
            - regex minta: `/^(?=(?:\D*\d){10}(?:(?:\D*\d){3})?$)[\d-]+$/i`
        - Leírás:
          - input mező neve: **description**
          - input mező típusa: textarea
          - validációs szabályok:
            - lehet null érték
        - Műfajok:
          - input mező neve: **genres**
          - input mező típusa: checkbox
          - validációs szabályok:
            - nem muszáj megadni
          - validációs szabályok, ha legalább egy meg van adva (**genres.***):
            - egész szám (integer)
            - egyedi érték
            - létezik, mint id, a genres táblában
        - Borítókép:
          - input mező neve: **attachment**
          - input mező típusa: file
          - validációs szabályok:
            - lehet null érték
          - validációs szabályok, ha meg van adva:
            - fájl, azon belül is kép
            - legeljebb 1MB
        - Készleten:
          - input mező neve: **in_stock**
          - input mező típusa: number
          - validációs szabályok: 
            - muszáj megadni
            - egész szám (integer)
            - legalább 0
            - legfeljebb 3000
        - A form legyen állapottartó, tehát ha elküldjük, és a validatoron nem megy át, akkor az előző értékeket jegyezze meg (a fájlnál ezt nem kell megtenni)!
    - Új könyv eltárolása (**POST /books/store**)
      - Erre a végpontra küldjük el a könyvet létrehozó formot
      - A fentebb megadott validációs szabályokat végezze el
      - Ha volt megadva borítókép, akkor azt tároljuk el a **public** mappába, és mentsük el a *book cover_image* mezőjébe azt a fájlnevet, amivel elmentettük
        - Ehhez segítség:
          - A `config/filesystems.php` fájlba írd be az `s3` kulcs után a következőket:
            ```php
            'book_covers' => [
                'driver' => 'local',
                'root'   => public_path() . '/images/book_covers',
            ],
            ```
          - Ez lehetővé teszi, hogy a *Storage*-t a következőképp használd:
            ```php
            Storage::disk('book_covers')->....
            ```
            - Vagyis a fájlokat a *public* mappán belül az *images/book_covers* mappába fogja menteni. Alapvető különbség az órai anyaghoz képest az, hogy míg az órai anyag egy "rejtett" tárhely volt, a public mappa hozzáférhető közvetlenül a http szerveren keresztül, tehát a kép betölthető, mint egy css fájl.
          - Érdemes figyelni a névütközésekre, pl. ha két könyvhöz is a *borito.jpg*-t töltik fel, tehát célszerű a fájlt olyan néven menteni, mint pl. *cover_{könyv id}.{kiterjesztés}*
      - A kapott adatok alapján tárolja el a könyvet az adatbázisban
      - A megadott műfajokat adja hozzá a könyvhöz
      - Ha sikerült a könyv létrehozása, akkor irányítson vissza a könyvet létrehozó formra, és jelenítsen meg egy üzenetet, hogy a könyvet sikerült létrehozni
        - Az üzenet div-jének id-je ez legyen: **#book-created**
        - Az üzenetben jelenjen meg a könyv címe is, pl. egy span-ben, a lényeg, hogy az id-je ez legyen: **#book-title**
    - Könyv módosítása (**GET /books/{book}/edit**)
      - Ezen az oldalon lehet egy könyvet szerkeszteni (szerkesztő űrlap megjelenítés)
      - Legyen egy hivatkozás, ami visszavisz a könyvek listájához
        - **a#all-books-ref**
      - Az űrlap PATCH metódussal legyen elküldve ide: **PATCH /books/update**
      - Az űrlap elemei megegyeznek a létrehozással, leszámítva az alábbiakat:
        - A borítókép alatt jelenítsük meg a jelenlegi borítóképet, az alábbi id-vel:
          - **img#book-cover-preview**
        - Az előnézet után legyen egy *"Borítókép eltávolítása"* checkbox, ami arra jó, hogyha a felhasználó bepipálja, akkor a borítókép el lesz távolítva a könyvről, így az alapértelmezett kép lesz használva a módosítás elküldése után.
          - input mező neve: **remove_cover**
          - input mező típusa: checkbox
          - validációs szabályok:
            - lehet null érték
            - logikai érték
          - *Tipp:*
            - A disk-ről a következő módon lehet kitörölni egy fájlt: `Storage::disk('disk neve')->delete('útvonal a fájlhoz')`
            - A `delete` le tudja kezelni azt is, ha nem létező fájlt adsz át neki
        - Ha a könyvhöz már be van állítva egyedi borítókép, de a felhasználó feltölt egy újat, akkor az új borítókép sikeres beállítását követően a régi fájl feleslegessé válik, így azt törölni kell a disk-ről!
        - Annak az esetnek nincs értelme, ha a felhasználó feltölt egy borítóképet, de közben bepipálja a *"Borítókép eltávolítása"* lehetőséget is, hiszen ezek ellentétes utasítások. Éppen ezért úgy kell megvalósítani a módosítást, hogyha a felhasználó ezt a két lehetőséget egyszerre használja, az hibát eredményezzen, méghozzá az **attachment** mező esetében!
          - *Tipp:* Használhatod a [prohibited-unless](https://laravel.com/docs/8.x/validation#rule-prohibited-unless) validációs szabályt.
      - A form jelenítse meg az aktuális könyv adatait, ezen felül legyen állapottartó, tehát ha elküldjük, és a validatoron nem megy át, akkor az előző értékeket jegyezze meg, ahol pedig ilyen nem volt, oda a default értékeket írja! A fájlfeltöltés esetében nem kell megjegyezni az előző értéket.
    - Könyv módosításainak eltárolása (**PATCH /books/update**)
      - Erre a végpontra küldjük el a könyvet frissítő formot
      - Végezze el a módosításra megadott validációs szabályokat
      - A kapott adatok alapján módosítsa a könyvet az adatbázisban
      - Ha sikerült a könyv módosítása, akkor irányítson vissza a könyvet szerkesztő formra, és jelenítsen meg egy üzenetet, hogy a könyvet sikerült módosítani
        - Az üzenet div-jének id-je ez legyen: **#book-updated**
        - Az üzenetben jelenjen meg a könyv neve is, pl. egy span-ben, a lényeg, hogy az id-je ez legyen: **#book-title**
    - Műfaj megjelenítése (**GET /genres/{genre}**)
      - Ez a végpont szolgál arra, hogy megjelenítse a műfajhoz tartozó könyveket
      - Szinte ugyanaz, mint a **GET /books** oldal, annyi különbséggel, hogy csak a megadott műfajhoz listázza a könyveket. A különbségek:
        - Az oldal tetején jelenjen meg a műfaj neve egy **span** tag-ben, amelynek az id-je a következő: **#genre**
        - Jelenjenek meg a műfajjal kapcsolatos műveletek:
          - Legyen egy szülő div-je, aminek az id-je **#genre-actions**
            - Ebben legyen két gomb: *Módosítás* és *Törlés*
            - A gombok azonosítója a következő legyen: 
              - **#edit-genre-btn**: ez egy *a* tag legyen, ami linkként szolgál a szerkesztő formra (**GET /genres/{genre}/edit**)
              - **#delete-genre-btn**: ez egy form része legyen, ami rendelkezzen *csrf* mezővel, és az *action*-je a **DELETE /genres/{genre}** legyen.
    - Műfaj törlése (**DELETE genres/{genre}**)
      - Törölje a paraméterben kapott műfajt.
      - A törlést követően irányítson át a könyvek oldalra (**GET /books**), ahol jelenítsen meg egy üzenetet, hogy sikerült a törlés. 
        - Az üzenet div-jének az id-je ez legyen: **#genre-deleted**
        - Az üzenetben jelenjen meg a műfaj neve is, pl. egy span-ben, a lényeg, hogy az id-je ez legyen: **#genre-name**

### 1. felvonás pontjai:
- Adatbázis & Seeder: 5 pont
- Felület igényessége: 4 pont
- Könyv CRUD: 14 pont
- Műfaj CRUD: 7 pont
- *CRUD:* **C**reate, **R**ead, **U**pdate, **D**elete

## 2. felvonás (30 pont)
- Időpontok:
  - **Határidő: 2021. május 30. (vasárnap) 23:59:59**
  - Konzultációk: 
    - 2021\. május 15. (szombat) 14:00, Teams Általános csatorna
    - 2021\. május 22. (szombat) 14:00, Teams Általános csatorna
- Feladatok:
  - Kereső **(3 pont)**
    - A főoldalon legyen egy kereső.
    - A találati oldal lehet olyan, mint a sima könyvlistázó oldal, de csak azok a könyvek jelenjenek meg, amiknek a címében megtalálható a keresett kifejezés. Nincs kis és nagybetű érzékenység, tehát ha egy könyv neve STAR WARS, de stAr-ra keresünk, ugyanúgy be kell adnia találatnak.
  - Regisztráció és Bejelentkezés oldal **(1 pont)**
    - A tanultaknak megfelelően legyen implementálva a *Laravel UI* használatával az autentikációs rendszer.
  - Az aktív menüpontok legyenek ellátva egy *active* vagy valami olyan class-al, ami megkülönbözeti őket a többitől. Tehát ha pl. a Könyvek oldalon vagyunk, akkor a menüben ez legyen félkövér, stb. **(1 pont)**
  - *Egyértelműen* jelölni kell, hogy Vendég/Olvasói/Könyvtáros felületen vagyunk-e! Pl. változik a menüsor színe, stb. **(1 pont)**
  - Könyvtáros funkciók: **(14 pont)**
    - Az alábbi funkciók csak könyvtárosként bejelentkezve legyenek elérhetők.
    - Első felvonásban készített végpontok levédése:
      - Csak a könyvtáros tudjon létrehozni, szerkeszteni, törölni műfajokat és könyveket. Ennek érdekében védd le ezeket a végpontokat a megfelelő jogosultságkezeléssel!
      - A felhasználói felületen illetékteleneknek (olvasóknak, vendégeknek) ne jelenjenek meg a gombok (pl. Új műfaj, Új könyv, stb.), illetve a végpontokhoz se lehessen hozzáférni a felhasználói felület megkerülésével. Ha illetéktelen hozzáférés történik egy végponthoz (pl. valaki megnyitja a /genres/create form-ot, vagy az action url-jére akar postolni), akkor 403-as hibát kell adni.
      - Könyvtárosok között nem teszünk különbséget, tehát olyan megkötés nincs, hogyha A könyvtáros hozott létre egy műfajt/könyvet, akkor azt csak az A módosíthatja, nyugodtan módosíthatja B könyvtáros is.
      - Tipp: használj [policy](https://laravel.com/docs/8.x/authorization#writing-policies)-kat, a controller osztályok konstruktorában pedig az [authorizeResource](https://laravel.com/api/8.x/Illuminate/Foundation/Auth/Access/AuthorizesRequests.html#method_authorizeResource) segítségével tudod megmondani, hogy a policy melyik metódus(ok)ra legyen kihatással.
    - Kölcsönzések kezelése:
      - Ez az oldal váljon szét a következő kategóriákra:
        - Függőben lévő kölcsönzések
        - Elfogadott kölcsönzések
        - Elutasított kölcsönzések
        - Visszahozott kölcsönzések
        - Késésben lévő kölcsönzések
          - Ezek olyan elfogadott kölcsönzések, amelyeknek már lejárt a határideje, ezek jelenjenek meg az elfogadott rész és ez alatt a rész alatt is.
        - Egy oldalon max. 15 kölcsönzés jelenhet meg, ezt [Pagination](https://laravel.com/docs/8.x/pagination) használatával kell megoldani!
        - A kölcsönzések *created_at* szerint növekvő sorrendben jelenjenek meg, tehát a legrégebbi kölcsönzés legyen legfelül, és a legújabb pedig a lista legvégén, a legutolsó oldalon. Tipp: használj *whereDate*-t.
      - Egy kölcsönzésre kattintva annak adatlapjára jutunk
      - A kölcsönzés adatlapja így épül fel:
        - Könyv
          - A könyv, amit a kölcsönzés érint, innen el kell tudni jutni a könyv adatlapjára
        - Kölcsönzés adatai:
          - Olvasó neve, 
          - Könyv címe, 
          - Kölcsönzés kérésének ideje, 
          - Olvasó megjegyzése (ha van), 
          - Kölcsönzés állapotát szövegként (Függőben, Elutasítva, Elfogadva, Visszahozva)
          - Ha a kölcsönzés fel lett már dolgozva (tehát nem Függőben van), akkor a feldolgozás adatait *is* meg kell jeleníteni:
            - Feldolgozás ideje
            - Feldolgozást végző könyvtáros
            - Könyvtáros megjegyzése (ha van),
            - Határidő (ha van)
          - Ha a könyv már vissza lett juttatva (Visszahozva állapotú), akkor a visszajuttatás adatait *is* meg kell jeleníteni: 
            - Mikor történt a visszajuttatás
            - Melyik könyvtáros regisztrálta a visszajuttatást
        - Opcionális figyelmeztetés
          - Az adatok alatt figyelmeztessük a könyvtárost, ha a kölcsönzést kérő olvasóhoz tartoznak megkésett kölcsönzések!
          - Ezt a figyelmeztetést feltételesen kell megjeleníteni, vagyis csak akkor, ha az alábbi feltételek valamelyike teljesül!
          - A két feltétel, amit kezelni kell:
            1. Megkésett aktuális kölcsönzések
              - Olyan Elfogadott állapotú kölcsönzések, amik még a felhasználónál vannak, azonban a határidejük már lejárt.
            2. Régebbi, késve visszahozott kölcsönzések
              - Olyan Visszahozott állapotú kölcsönzések, amelyeket már a felhasználó visszajuttatott a könyvtár részére, azonban ezt akkor késve tette meg.
          - Ezeket a megkésett kölcsönzéseket tegyük hivatkozhatóvá, hogy az adatlapjukra lehessen ugrani egy kattintással, pl. *#{kölcsönzés id-je}* szöveggel
        - Kezelő űrlap:
          - Ennek az űrlapnak a segítségével adminisztrálható a kölcsönzés.
          - Legyen lehetőség az alábbi három állapot közül választani:
            - Elutasítva 
            - Elfogadva
            - Visszahozva
          - Legyen lehetőség határidőt választani
            - Bármilyen dátumot lehet választani
            - Segítség: használhatsz `datetime-local`-t, ilyenkor a date-t így add értékül, hogy elfogadja: `date('Y-m-d\TH:i', strtotime($borrow->deadline))`
          - Legyen lehetőség egy opcionális megjegyzést írni
            - Ezzel csak az ACCEPTED/REJECTED esetén kell foglalkozni,
            - A megjegyzés max. 255 karakter hosszú lehet
          - Legyen egy mentés gomb, amivel a módosításokat alkalmazni lehet.
          - A módosítások alkalmazása után a rendszer irányítson vissza a kölcsönzés adatlapjára, és ott jelenítsen meg egy üzenetet, hogy sikerült a módosítás.
  - Olvasói funkciók **(7 pont)**:
    - Az olvasói funkció felhasználó felületi elemei, végpontjai a könyvtároshoz hasonlóan legyenek levédve, hogy Vendégek ne tudják használni őket.
    - Könyv kikölcsönzése:
      - A könyv adatlapján legyen egy Kölcsönzés feliratú gomb vagy hivatkozás, ami egy Kölcsönzési igényt összeállító űrlapra irányít
      - Az űrlap oldala így épüljön fel:
        - Legyen egy hivatkozás, amivel vissza lehet jutni a könyv adatlapjára
        - Egyértelműen jelezze, melyik könyvre vonatkozik
        - A könyvhöz jelenjen meg egy készletinformáció, hogy jelenleg elérhető-e a könyv a könyvtárban! 
          - Ez úgy működik, hogy a könyvnek van egy in_stock mezője, és ebből lejön, hogy éppen hány embernek van kiadva (ACCEPTED kölcsönzés)
          - Ha a könyv a készletinformáció szerint nem elérhető, attól a kölcsönzési igényt le lehet adni, majd a könyvtárosokon múlik, hogy kezelik le.
        - Legyen egy megjegyzés mező, ahová opcionálisan lehet a kölcsönzést elbíráló könyvtáros felé üzenni
        - Ha a felhasználó beküldi a formot, jöjjön létre egy PENDING állapotú kölcsönzés, aminek a reader-je az aktuálisan bejelentkezett felhasználó, legyen hozzárendelve a könyv, megjegyzés (ha van), stb. 
        - Ezt követően a rendszer irányítsa vissza a felhasználót a könyv adatlapjára, és jelenítsen meg egy üzenetet, hogy sikerült leadni a kölcsönzést.
        - **Egy felhasználónak egy könyvre egyszerre csak egy kölcsönzése lehet!**
          - A felhasználó nem adhat le kölcsönzési igényt egy könyvre, ha:
            - már le van adva neki arra a könyvre egy kölcsönzési igénye, de az nincs elbírálva
            - vagy van olyan elfogadott kölcsönzése, ami azt a könyvet érinti
    - Kölcsönzések oldal:
      - Nagyon hasonlóan a könyvtáros felületéhez, a felhasználónak is legyen egy *Függőben, Elutasított, Elfogadott, Visszahozva, Késés* kategóriákra bontott kölcsönzési oldala, itt azonban csak a saját kölcsönzéseit láthassa!
      - A kölcsönzések sorrendje itt *created_at* szerint *csökkenő* sorrendben legyen, vagyis a legújabb kölcsönzés legyen legelől mindig!
      - Ugyanúgy legyen a kölcsönzésnek egy adatlapja, ahol látja a könyvet, és a kölcsönzés adatait.
      - Ha a felhasználónak van olyan aktuálisan nála levő (Elfogadott) kölcsönzése, amivel késésben van, akkor jelenítsünk meg neki egy figyelmeztetést a kölcsönzések oldal tetején!
  - Profil oldal **(3 pont)**:
    - Legyen egy Profil oldal, ami elérhető a menüből, és az alábbi adatokat jelenítse meg:
      - A felhasználó
        - szerepkörét (olvasó/könyvtáros)
        - nevét
        - email címét
      - Ha olvasó, akkor jelenítse meg az
        - összes hozzá köthető kölcsönzés számát,
        - az összes hozzá köthető aktív kölcsönzés számát (az aktív kölcsönzés az ACCEPTED, ahol nála van épp a könyv)
        - az aktuális késések számát (vagyis az olyan aktív kölcsönzéseket, aminek a határideje lejárt)
      - Ha könyvtáros, akkor jelenítse meg az
        - általa elfogadott kölcsönzések számát
        - általa elutasított kölcsönzések számát
        - általa visszahozottnak minősített kölcsönzések számát

### 2. felvonás pontjai:
- Kereső: 3 pont
- Regisztráció, bejelentkezés: 1 pont
- Aktív menüpontok: 1 pont
- Vendég/Olvasó/Könyvtáros felületek elválnak: 1 pont
- Könyvtáros funkciók: 14 pont
- Olvasói funkciók: 7 pont
- Profil oldal: 3 pont

## Követelmények
- Az alkalmazást Laravel 7/8 keretrendszerben kell megvalósítani.
- Az alkalmazást úgy kell elkészíteni, hogy a zip-ből kicsomagolva az alábbi parancsok kiadásával elinduljon:
  ```
  composer install
  npm install
  npm run prod
  php artisan migrate:fresh
  php artisan db:seed
  php artisan serve
  ```
- A felhasználó felület igényes legyen, ehhez használhatsz valamilyen CSS framework-öt, pl Twitter Bootstrap-et, amivel az órákon is néztünk példákat.
- A felhasználói felület fedje le az alkalmazás kért funkcióit, ne legyenek olyan funkciók, amelyekhez létezik ugyan végpont, de a felhasználói felületről nem érhetők el!
- Az időzóna legyen magyarra állítva az alkalmazás konfigurációjában!
- **Az űrlapokon keresztül küldött adatokat minden esetben validálni kell szerveroldalon! HTML szintű validáció (pl. required attribútum) ne is legyen a kódban!** Nem a HTML tagek ismeretét szeretnénk számonkérni egy szerveroldali tárgyon, hanem a [Laravel validációjának](https://laravel.com/docs/8.x/validation) megfelelő alkalmazását!
- Az alkalmazást úgy kell elkészíteni, hogy SQLite adatbázissal működjön. Az SQLite adatbázis ebben a fájlban legyen: database/database.sqlite!
- A seeder meghívásával az alkalmazást legyen lehetőség értelmesen feltölteni adatokkal. Értelmesen, az azt jelenti, hogy lehetőleg legyen példaadat mindenre.
- **Kötelező** mellékelni a munka tisztaságáról szóló STATEMENT.md nevű nyilatkozatot, a részleteket lásd lentebb.
- **Tilos** mellékelni a vendor és a node_modules mappákat! Ne terheld a rendszert azzal, hogy feleslegesen 30-40 MB méretű beadandót adsz be! Mikor kijelölöd a becsomagolni kívánt állományokat, hagyd ki ezeket a mappákat (nem kell törölni őket a gépedről, csak ne csomagold be)!
- **Amíg a tisztaságról szóló nyilatkozat nincs mellékelve (ez utólag pótolható Canvas kommentben, ill. a munka újrafeltöltésével), vagy van a projektben vendor/node_modules mappa, addig az értékelés 0 pontot ér, függetlenül a beadott munkától és annak minőségétől! Ha valaki ezeket nem javítja félév végéig, akkor elbukja a tárgyat a beadandón! A pótlásról minden esetben értesíteni kell a gyakorlatvezetőt!**
- Canvas-ben az 1. és a 2. fázishoz is fel kell tölteni a beadandót, mivel ott lesz értékelve!

## Alkotói szabadság
- Szeretnénk, ha nem egy kötött dologként gondolnátok erre a feladatra, hanem kellő alkotói szabadsággal állnátok neki. 
- Tulajdonképpen alkalmazott tudást várunk el, hogy a tanultakat mélyebben megértsétek és akár tovább is tudjátok gondolni; vagyis nem kell mereven csak a tanult dolgokhoz ragaszkodni, kezdjétek el "élesben" használni a Laravelt, és ahogy foglalkoztok vele, úgy értitek majd meg egyre jobban és jobban a koncepcióját.
- Egy jó tanács: mérlegeljétek az előttetek álló feladatokat, ne essetek olyan hibába, hogy sokkal bonyolultabb megoldást csináltok, mint amire szükség van. Pl.
  - ha a felhasználóhoz csak +1 mezőt kell hozzáadni, és azt vizsgálni, akkor ne akarjatok egy komplett role kezelőt beintegrálni;
  - pár soros modellműveletek helyett ne írjatok több száz soros SQL kéréseket, mert valakitől ezt láttátok Stackoverflow-n, stb.

## Segítségkérés, konzultációs alkalmak
- Ha bárkinek bármilyen kérdése van a beadandóval kapcsolatban, pl. elakadt, támpontra van szüksége, kíváncsi, hogy jó-e egy ötlete, nem ért valamit a követelményekből, nem érti a feladat valamelyik pontját, akkor BÁTRAN keresse az oktatókat, de NE tologassa maga előtt a beadandót, hogy "majd lesz valahogy", mivel hamar elszáll az idő! Az elérhetőségek megtalálhatóak a Canvasben, az "Elérhetőség" oldalon.
- A beadandóhoz az alábbi konzultációs lehetőségeket is biztosítjuk:
  - 1\. felvonás:
    - 2021\. április 03. (szombat) 14:00, helyszín: Teams csoport, Általános csatorna
    - 2021\. április 16. (péntek) 16:00, helyszín: Teams Általános csatorna
  - 2\. felvonás:
    - 2021\. ?? (szombat) 14:00, helyszín: Teams csoport, Általános csatorna
- Ezek általános konzultációs alkalmak. Mondunk mi is pár általános dolgot, de örülnénk, ha minél többen jönnétek, és Nektek lennének kérdéseitek, amikből a társaitok is okulhatnak!

## Határidők, késés
- A beadandót igyekeztünk úgy kialakítani és olyan beadási határidőkkel ellátni, hogy azt mindenkinek legyen elegendő ideje rendesen, nyugodt körülmények között kidolgozni.
- Oszd be az idődet, ne az utolsó délutánra hagyd a feladatot, mivel akkor tényleg sok lesz!
- A határidő lejárta után van lehetőség késésre:
  - **Minden megkezdett nap (0:00-tól) 1.5 (másfél) pont levonását jelenti.**
    - A határidők 23:59-kor járnak le, ezért aki 0:00-kor, 0:01-kor adja be, az is ide számít. 
  - Mivel 40%-ot, vagyis 12-12 pontot legalább el kell érni a beadandóból, így ez matematikailag 12 nap késést tesz lehetővé (ami -18 pont levonás a 30-ból), ha feltételezzük, hogy a beadott munka ekkor hibátlan. 
  - A beadandót nem lehet utólag javítani (csak a zh-t), ezért a késésre fokozottan ügyeljetek, mivel **könnyen elbukható ezen a tárgy!**
- Ha valaki INDOKOLT eset miatt nem tudja tartani a határidőt, akkor haladéktalanul keresse fel a gyakorlatvezetőjét, akivel ismertesse a problémáját, és IGAZOLJA is azt, hogy mi jött neki közbe! Ilyen egyedi esetekben lehetőség van a határidőtől a probléma súlyától függően eltérni az adott hallgatónál. **Ezzel a lehetőséggel ne éljetek vissza, igazolatlan eseteket, kamu indokot pedig nem fogadunk el, azzal ne is keressetek minket!**

## A munka tisztasága
- Kérjük, hogy a saját érdekedben csak olyan munkát adj be, amit Te készítettél!
- A hasonló megoldások eredményes kiszűrése érdekében a beadott munkák szigorú, több lépcsőből álló plágiumellenőrzési folyamaton mennek keresztül.
- Gyanús esetekben (ami nem csak 2 beadandó hasonlósága lehet) az érintett hallgatók felkérhetők szóbeli védésre, ahol teljesen véletlenszerűen belekérdezünk az alkalmazásba, annak logikájába és működésébe. Ezzel tökéletesen fel tudjuk mérni, hogy az illető tisztában van-e azzal, hogy mit adott le.
- Ha valaki becsületesen készíti el a beadandót, akkor az itt leírtak miatt egyáltalán nem kell aggódnia.
- A beadott feladatnak tartalmaznia kell egy STATEMENT.md nevű fájlt, a következő tartalommal (értelemszerűen az adataidat írd bele):
```
# Nyilatkozat

Én, <NÉV> (Neptun kód: <NEPTUN KÓD>) kijelentem, hogy ezt a megoldást én küldtem be a Szerveroldali webprogramozás Laravel beadandó feladatához. 
A feladat beadásával elismerem, hogy tudomásul vettem a nyilatkozatban foglaltakat.

- Kijelentem, hogy ez a megoldás a saját munkám.
- Kijelentem, hogy nem másoltam vagy használtam harmadik féltől származó megoldásokat. 
- Kijelentem, hogy nem továbbítottam megoldást hallgatótársaimnak, és nem is tettem azt közzé. 
- Tudomásul vettem, hogy az Eötvös Loránd Tudományegyetem Hallgatói Követelményrendszere (ELTE szervezeti és működési szabályzata, II. Kötet, 74/C. §) kimondja, hogy mindaddig, amíg egy hallgató egy másik hallgató munkáját - vagy legalábbis annak jelentős részét - saját munkájaként mutatja be, az fegyelmi vétségnek számít.
- Tudomásul vettem, hogy a fegyelmi vétség legsúlyosabb következménye a hallgató elbocsátása az egyetemről.
```
- Ha a STATEMENT.md elmarad, akkor pótolni kell egy újabb feltöltéssel, vagy a Canvasen komment formájában.
- Amíg a hallgató nem nyilatkozott a munka tisztaságáról, a feladatra kapott értékelés (ha addig megtörtént az értékelés) nem érvényes, egészen addig, amíg a nyilatkozat pótlásra nem kerül! A nyilatkozat pótlásáról értesíteni kell az oktatót is, aki az értékelést végzi (a saját gyakorlatvezetőt).

## Hasznos hivatkozások
Az alábbiakban adunk néhány hasznos hivatkozást, amiket érdemes szemügyre venni a beadandó elkészítésekor.

- [Órai alkalmazás](https://github.com/totadavid95/szerveroldali-21-tavasz/tree/main/pentek/laravel/pentek)
- [Laravel nyelvi csomag - magyarosításhoz](https://github.com/Laravel-Lang/lang)
- Tantárgyi Laravel jegyzetek
  - [Kimenet generálása](http://webprogramozas.inf.elte.hu/#!/subjects/webprog-server/handouts/laravel-01-kimenet)
  - [Bemeneti adatok, űrlapfeldolgozás](http://webprogramozas.inf.elte.hu/#!/subjects/webprog-server/handouts/laravel-02-bemenet)
  - [Adattárolás, egyszerű modellek](http://webprogramozas.inf.elte.hu/#!/subjects/webprog-server/handouts/laravel-03-adatt%C3%A1rol%C3%A1s)
  - [Relációk a modellek között](http://webprogramozas.inf.elte.hu/#!/subjects/webprog-server/handouts/laravel-04-rel%C3%A1ci%C3%B3k)
  - [Hitelesítés és jogosultságkezelés](http://webprogramozas.inf.elte.hu/#!/subjects/webprog-server/handouts/laravel-05-hiteles%C3%ADt%C3%A9s)
  - [GitHub-os Laravel jegyzet](https://github.com/totadavid95/szerveroldali-21-tavasz/blob/main/Laravel.md)
- Dokumentációk
  - [Laravel dokumentáció](https://laravel.com/docs)
    - [Blade direktívák](https://laravel.com/docs/8.x/blade)
    - [Resource Controllers](https://laravel.com/docs/8.x/controllers#resource-controllers)
    - [Validációs szabályok](https://laravel.com/docs/8.x/validation#available-validation-rules)
    - [Migrációknál elérhető mezőtípusok](https://laravel.com/docs/8.x/migrations#available-column-types)
  - [Laravel API dokumentáció](https://laravel.com/api/master/index.html)
  - [PHP dokumentáció](https://www.php.net/manual/en/)
  - [Bootstrap 4 dokumentáció](https://getbootstrap.com/docs/4.6/getting-started/introduction/)
- Programok, fejlesztői eszközök
  - [Automatikus PHP és Composer telepítő](https://github.com/totadavid95/PhpComposerInstaller)
  - [Visual Studio Code](https://code.visualstudio.com/)
    - [Live Share](https://marketplace.visualstudio.com/items?itemName=MS-vsliveshare.vsliveshare)
    - [Laravel Extension Pack](https://marketplace.visualstudio.com/items?itemName=onecentlin.laravel-extension-pack)
  - [DB Browser for SQLite](https://sqlitebrowser.org/)
- További CSS framework tippek
  - [Fontawesome ikonkészlet](https://fontawesome.com/) 
  - [Tailwind CSS](https://tailwindcss.com/)
  - [Material Bootstrap](https://mdbootstrap.com/)
  - [Material UI, React-hez](https://material-ui.com/)
  - [Bulma](https://bulma.io/)

## Változásnapló
Ha a beadandó feladatsorában bármilyen változás történik, azokat ebben a pontban egyértelműen, tételesen jelezzük. Minden egyes változás külön commit-ként kerül fel a repo-ba, a beadandó feladatsorára vonatkozó commit history pedig [ide kattintva érhető el](https://github.com/totadavid95/szerveroldali-21-tavasz/commits/main/LaravelBead.md).

#### 2021. május 19.
  - reader_message mező hozzáadása a Borrow-hoz

#### 2021. április 11.
  - A feladat mostmár konkrétabban határozza meg a borítókép módosítását/eltávolítását.
  - A könyv, ill. a műfaj törlését követően üzenet megjelenítése.
  - Útvonalak részletesebb kifejtése (ez a feladatot nem befolyásolja).
  - Határidő meghosszabbítása.

#### 2021. április 10.
  - A könyv létrehozásánál, szerkesztésénél az ISBN input mező neve nem pages, hanem isbn.
  - Könyv frissítésénél (book-update) ne #book-name, hanem #book-title jelenjen meg
  - A könyv adatlapján a könyv műfajainak a szülőeleme nem #book-categories, hanem #book-genres
  - edit útvonalak javítása
  - genre törlésénél nem book, hanem genre kell

#### 2021. április 06.
  - A műfaj stílusánál lemaradt a *dark*
  - A *book_cover*-re vonatkozó leírás frissítve lett 

#### 2021. március 19.
  - Első kiadás
