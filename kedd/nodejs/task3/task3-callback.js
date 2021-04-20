const fs = require('fs');

fs.readdir('./inputs', (err, files) => {
    if (err) throw err;
    console.log(files);
    let contents = [];
    files.forEach(file => {
        fs.readFile(`./inputs/${file}`, 'utf-8', (err, content) => {
            if (err) throw err;
            console.log(content);
            contents.push(content);
            if (contents.length === files.length) {
                //console.log(contents.join('\n'));
                fs.writeFile('./output.txt', contents.join('\n'), (err) => {
                    if (err) throw err;
                    console.log('Vége');
                });
            }
        });
    });
});
