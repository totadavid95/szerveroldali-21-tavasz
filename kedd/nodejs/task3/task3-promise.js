const { promisify } = require('util');
const fs = require('fs');

const pReadDir = promisify(fs.readdir);
const pReadFile = promisify(fs.readFile);
const pWriteFile = promisify(fs.writeFile);

pReadDir('./inputs')
    .then(files => {
        console.log(files);
        const reads = files.map(file => pReadFile(`./inputs/${file}`, 'utf-8'));
        console.log(reads);
        return Promise.all(reads);
    })
    .then(contents => {
        console.log(contents);
        return contents.join('\n');
    })
    .then(output => pWriteFile('./output.txt', output))
    .then(() => console.log('Vége'))
    .catch(err => {
        throw err;
    });