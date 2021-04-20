const { promisify } = require('util');
const fs = require('fs');

const pReadDir = promisify(fs.readdir);
const pReadFile = promisify(fs.readFile);
const pWriteFile = promisify(fs.writeFile);

;(async () => {
    const files = await pReadDir('./inputs');
    console.log(files);
    let contents = [];
    for (file of files) {
        const content = await pReadFile(`./inputs/${file}`, 'utf-8');
        contents.push(content);
    }
    await pWriteFile('./output.txt', contents.join('\n'));
})();
