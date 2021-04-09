const fs = require('fs');
const { promisify } = require('util');
const path = require('path');
const jimp = require('jimp');
const date = require('date-and-time');
const sqlite3 = require('sqlite3').verbose();

const pReadDir = promisify(fs.readdir);

;(async() => {
    const db = new sqlite3.Database('task5.sqlite');

    db.run(
        `CREATE TABLE IF NOT EXISTS images (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            original_name VARCHAR(255),
            original_width INTEGER,
            original_height INTEGER,
            new_name VARCHAR(255),
            new_width INTEGER,
            new_height INTEGER,
            processed_on DATETIME
        )`
    );

    const files = await pReadDir('./task5');
    console.log(files);
    for (const file of files) {
        let image = await jimp.read(`./task5/${file}`);
        //console.log(image);
        let fileProps = path.parse(`./task5/${file}`);
        console.log(fileProps);

        const { width, height } = image.bitmap;
        console.log(width, height);

        image.resize(100, jimp.AUTO);

        const newWidth = image.bitmap.width;
        const newHeight = image.bitmap.height;
        console.log(newWidth, newHeight);

        const now = new Date();
        const nowFormatted = date.format(now, 'YYYY_MM_DD_HH_mm_ss'); 

        const newName = `${fileProps.name}_${nowFormatted}${fileProps.ext}`;
        image.write(`./task5_out/${newName}`);

        db.run(
            `INSERT INTO images (
                original_name, 
                original_width, 
                original_height,
                new_name, 
                new_width, 
                new_height,
                processed_on
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)`,
            [file, width, height, newName, newWidth, newHeight, now]
        );
    }
})();