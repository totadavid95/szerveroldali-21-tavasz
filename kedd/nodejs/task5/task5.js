const { promisify } = require('util');
const fs = require('fs');
const path = require('path');
const Jimp = require('jimp');

const pReadDir = promisify(fs.readdir);
const pReadFile = promisify(fs.readFile);
const pWriteFile = promisify(fs.writeFile);

;(async () => {
    const files = await pReadDir('./inputs');
    for (file of files) {
        const image = await Jimp.read(`./inputs/${file}`);
        const { width, height } = image.bitmap;
        console.log(width, height);
        image.resize(100, Jimp.AUTO);
        console.log(image.bitmap);
        const parsed = path.parse(`./inputs/${file}`);
        console.log(parsed);
        const outfile = `${parsed.name}_${new Date().getTime()}${parsed.ext}`;
        console.log(outfile);
        image.write(`./outputs/${outfile}`)
    }
})();