const fs = require('fs');
//const util = require('util');
const { promisify } = require('util');

//const res = fs.readdirSync('./task3');
//console.log(res);

const pReadDir = promisify(fs.readdir);
const pReadFile = promisify(fs.readFile);
const pWriteFile = promisify(fs.writeFile);

pReadDir('./task3')
    .then(files => {
        const readPromises = files.map(file => pReadFile(`./task3/${file}`, 'utf-8'));
        return Promise.all(readPromises);
    })
    .then(contents => {
        console.log(contents);
        return contents.join('\n');
    })
    .then(contents => pWriteFile('./task3.txt', contents))
    .then(() => console.log('Vége'))
    .catch(err => {
        throw err;
    });
