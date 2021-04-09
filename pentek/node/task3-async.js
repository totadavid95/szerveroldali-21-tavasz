const fs = require('fs');
//const util = require('util');
const { promisify } = require('util');

//const res = fs.readdirSync('./task3');
//console.log(res);

const pReadDir = promisify(fs.readdir);
const pReadFile = promisify(fs.readFile);
const pWriteFile = promisify(fs.writeFile);

;(async() => {
    const files = await pReadDir('./task3');
    console.log(files);
    let contents = [];
    for (const file of files) {
        const content = await pReadFile(`./task3/${file}`, 'utf-8');
        contents.push(content);
    }
    await pWriteFile('./task3.txt', contents.join('\n'));
    console.log('Vége');
})();

