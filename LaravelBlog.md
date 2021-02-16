
# Szerveroldali webprogramozás
## Laravel projekt - Blog

A gyakorlatok során egy konkrét projektet fogunk Laravelben fejleszteni, amelynek a részleteit ez a feladatsor ismerteti.

### A feladat rövid leírása
A projekt egy alapvető blogot valósít meg, amelybe a felhasználók regisztrálhatnak, majd kategóriákat, később a kategóriákhoz bejegyzéseket hozhatnak létre. A bejegyzések megtekinthetők, kategória szerint rendezhetők, kereshetők. A bejegyzésekhez lehetőség van hozzá is szólni, amennyiben ezt a bejegyzés beállításai lehetővé teszik.

### Adatmodellek és tulajdonságaik
User
  - id
  - name (string)
  - email (string)
  - email_verified_at (timestamp)
  - password (string)
  - is_admin (boolean)
  - remember_token
  - timestamps

Category
  - id
  - name (string)
  - style (enum: primary, secondary, success, danger, warning, info, light, dark)
  - timestamps

Post
  - id
  - user_id (először elég egy string)
  - title (string)
  - text (text)
  - attachment_hash_name (string)
  - attachment_original_name (string)
  - comments_enabled (boolean, default true)
  - published (boolean, default true)
  - timestamps

Comment
  - id
  - user_id
  - post_id
  - text
  - timestamps

### Adatmodellek közti relációk
- User 1-N Post
- Post N-N Category
- Post 1-N Comment
- User 1-N Comment

### Sablon
Az oldalak vázát előre kidolgoztuk a Bootstrap keretrendszer segítségével, így ezeket a dizájnszerű dolgokat a gyakorlatokon csak be fogjuk másolni a projektbe, ezt követően fogjuk őket fokozatosan "Laravelesíteni". Ennek előnyei:
- a gyakorlat ideje nem pazarolódik HTML/CSS kódok írására,
- végeredményében szebb lesz a projekt,
- a hallgatók is jobban el tudják képzelni a távlati projektet, ha már az elején látják az oldalak vázát.

A sablon letölthető innen: [http://totadavid.hu/files/blog_template.zip](http://totadavid.hu/files/blog_template.zip "http://totadavid.hu/files/blog_template.zip")

### 1. fázis: Kimenetek elkészítése
- Nézzük meg a letöltött sablont, hogy láthassuk magunk előtt a projektet.
- Gondoljuk ki, hogy miként lehet ezt berakni a Laravelbe, nagyjából milyen routingra lesz szükség.
- Telepítsük a Font Awesome csomagot (`npm install --save @fortawesome/fontawesome-free`), és állítsuk be a Laravel Mix-ben.
- Beszéljük át a Laravel Mix-et, hogy mi ez, futtassuk le, és nézzük meg, hogy a public mappába kerülnek az assetek, a Font Awesome is.
- A megálmodott route-ok alapján készítsük el a nézethez szükséges *.blade.php* sablonjainkat, majd másoljuk bele a letöltött sablonból a megfelelő kódrészletet (fontos: gondolkodjunk layout-okban).
- Nézzünk példát a nevesített útvonalakra, ahol tudjuk, ezt írjuk át (pl home linkek, stb.)

### 2. fázis: Űrlapok validációja
- Hozzunk létre controllereket a category és a post számára.
- Alakítsuk át a web.php útvonalleírót oly módon, hogy a vezérlőket használja.
- Írjuk át az űrlapok tulajdonságait, hogy nevesített útvonallal a megfelelő feldolgozórétegnek küldjék el az űrlap adatait, POST metódussal.
- Küldjük el az űrlapot, 419 Expired hibát kapunk, miért? CSRF.
- Helyezzük be a @csrf direktívát is, és nézzük meg, hogy miben változott az oldal forráskódja (bekerült egy hidden input field).
- Nézzük meg a Laravel dokumentáció validációra vonatkozó fejezetét, és állítsuk össze a szabályokat.
- Állítsuk be a hibakezelést a Blade sablon oldalán is (@error direktíva, és $message változó), próbáljuk ki.
- Finomítsuk a validációs szabályokat.
- Nézzük meg, hogy működik a nyelvesítés (resources/lang), adjunk meg egyedi hibaüzeneteket a validációnál.
- Ugyanezeket a feladatokat végezzük el a post létrehozásánál is.
- A post esetében a validáció után nézzük meg, hogy a request tartalmaz-e fájlt, ha igen, egyelőre mentsük le a public disk-re. Kitekintés: ezt majd le lehet tölteni a megfelelő végpontról, viszont adatbázis is kell majd hozzá.
