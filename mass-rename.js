//node script for mass renaming files
//get the directory and the new name
//the names must be consecutive
//the extension must be the same

let dir = process.argv[2];
let newName = process.argv[3];
function rename(dir, newName_param) {
    let fs = require('fs');
    let path = require('path');
    let files = fs.readdirSync(dir);
    let ext = path.extname(files[0]);
    for (let i = 0; i < files.length; i++) {
        let oldName = path.join(dir, files[i]);
        let newName = path.join(dir, newName_param + i + ext);
        fs.renameSync(oldName, newName);
    }
}
rename(dir, newName);