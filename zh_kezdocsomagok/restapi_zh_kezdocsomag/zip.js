const fs = require("fs");
const { promisify } = require("util");
const { pipeline } = require("stream");
const fetch = require("node-fetch");
const AdmZip = require("adm-zip");
const date = require("date-and-time");
const colors = require("colors");

const pPipeline = promisify(pipeline);
const pExists = promisify(fs.exists);
const pMkDir = promisify(fs.mkdir);
const pReadDir = promisify(fs.readdir);
const pReadFile = promisify(fs.readFile);

const ignoredElements = [
  "node_modules",
  "package-lock.json",
  "zip.js",
  "zipfiles",
];

checkStatement = async () => {
  console.log(" 1.) Nyilatkozat ellenőrzése");
  if (!(await pExists("statement.txt"))) {
    console.log(
      colors.yellow(
        "   * Hoppá, úgy néz ki, hogy nem található a statement.txt fájl! Nem baj, letöltjük..."
      )
    );
    const remoteURL = 'https://raw.githubusercontent.com/totadavid95/szerveroldali-21-tavasz/main/zh_kezdocsomagok/restapi_zh_kezdocsomag/statement.txt';
    const response = await fetch(remoteURL);
    await pPipeline(response.body, fs.createWriteStream("./statement.txt"));
    console.log(
      "   * A statement.txt fájl sikeresen le lett töltve! Kérjük, töltsd ki a folytatáshoz!"
    );
    return false;
  }
  const statement = await pReadFile("statement.txt");
  if (new RegExp("<NÉV>|<NEPTUN>").test(statement)) {
    console.log(
      colors.red(
        "   * A nyilatkozatban (statement.txt) nem töltötted ki a Nevet/Neptun kódot. Kérjük, töltsd ki a folytatáshoz!"
      )
    );
    return false;
  }
  const data = new RegExp(
    "Én, ([^,]*), Neptun kód: ([a-zA-Z0-9]{6}) kijelentem"
  ).exec(statement);
  if (!data) {
    console.log(
      colors.red(
        "   * A nyilatkozatban (statement.txt) valószínűleg rosszul töltötted ki a Nevet/Neptun kódot."
      )
    );
    console.log(
      colors.red(
        "   * Kérjük, ellenőrizd, és töltsd ki helyesen ki a folytatáshoz!"
      )
    );
    return false;
  }
  const name = data[1];
  const neptun = data[2].toUpperCase();
  console.log(
    colors.green(
      `   * Az előzetes ellenőrzés szerint a nyilatkozatod rendben van!`
    )
  );
  console.log(
    colors.green(`   * A detekció szerint ezeket az adatokat adtad meg:`)
  );
  console.log(colors.green(`     * Név: `) + colors.yellow(name));
  console.log(colors.green(`     * Neptun kód: `) + colors.yellow(neptun));
  console.log("\n");
  return neptun;
};

const collectFiles = async (ignore, dir = ".") => {
  let collectedFiles = [];
  const entries = await pReadDir(dir, { withFileTypes: true });
  for (entry of entries) {
    if (ignore.includes(entry.name)) continue;
    if (entry.isDirectory()) {
      collectedFiles.push(
        ...(await collectFiles(ignore, `${dir}/${entry.name}`))
      );
    } else {
      collectedFiles.push({
        fileName: entry.name,
        relativePath: dir,
      });
    }
  }
  return collectedFiles;
};

const makeZip = async (ignore, neptun) => {
  console.log(" 2.) Fájlok becsomagolása");
  if (!(await pExists("zipfiles"))) await pMkDir("zipfiles");
  console.log("   * Fájlok összegyűjtése...");
  const collectedFiles = await collectFiles(ignore);
  console.log("   * Fájlok hozzáadása az archívumhoz...");
  const zip = new AdmZip();
  for (file of collectedFiles) {
    if (file.relativePath === ".") {
      console.log("      - hozzáadás: " + colors.grey(file.fileName));
      zip.addLocalFile(file.fileName);
    } else {
      console.log(
        "      - hozzáadás: " +
          colors.grey(`${file.relativePath}/${file.fileName}`)
      );
      zip.addLocalFile(
        `${file.relativePath}/${file.fileName}`,
        file.relativePath
      );
    }
  }
  const formattedDate = date.format(new Date(), "YYYYMMDD_HHmmss");
  const zipName = `restapi_zh_${neptun}_${formattedDate}.zip`;
  const zipPath = `zipfiles/${zipName}`;
  console.log("   * Archívum mentése ide: " + colors.yellow(zipPath) + "...");
  zip.writeZip(zipPath);
  console.log(
    colors.green("   * A fenti archívum mentése sikeresen megtörtént.")
  );

  console.log("\n");
  console.log(colors.yellow(" * FIGYELEM!"));
  console.log(
    colors.yellow(
      "   * Beadás előtt ellenőrizd, hogy minden fájl megfelelően be lett-e csomagolva!"
    )
  );
  console.log(
    colors.yellow(
      "   * Az esetleges becsomagolás közben fellépő hibákért felelősséget nem vállalunk!"
    )
  );
  console.log(
    colors.yellow(
      "   * A zárthelyi beadásával elfogadod a nyilatkozatot (statement.txt)."
    )
  );
  console.log(
    colors.yellow(
      "   * Az értékelés csak megfelelően kitöltött nyilatkozattal érvényes!"
    )
  );
};

(async () => {
  const neptun = await checkStatement();
  if (neptun === false) return;
  await makeZip(ignoredElements, neptun);
})();
