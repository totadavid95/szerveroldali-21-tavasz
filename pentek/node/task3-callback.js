const fs = require('fs');

//const res = fs.readdirSync('./task3');
//console.log(res);

fs.readdir('./task3', (err, files) => {
    if (err) throw err;
    console.log(files);
    let contents = [];
    files.forEach(file => {
        fs.readFile(`./task3/${file}`, 'utf-8', (err, data) => {
            if (err) throw err;
            console.log(data);
            contents.push(data);
            if (contents.length === files.length) {
                console.log(contents.join('\n'));
                fs.writeFile('./task3.txt', contents.join('\n'), (err) => {
                    if (err) throw err;
                    console.log('Vége');
                })
            }
        });
    });
});

