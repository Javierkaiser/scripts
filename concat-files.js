//Node Function to concat all files in a directory
//Files are concatenated in the order they are listed in the directory
//The output file is named after the directory
//The output file is placed in the same directory as the input files
//The file type is xlsx
//The parameter is the directory

let directory = process.argv[2];
let fs = require('fs');
let path = require('path');
function concatFiles(directory) {
    let files = fs.readdirSync(directory);
    let output = [];
    let ext = '';
    //get directory name
    let dirName = path.basename(directory);
    files.forEach(file => {
        //get file extension
        ext = path.extname(file);
        let filePath = path.join(directory, file);
        let fileContents = fs.readFileSync(filePath);
        output.push(fileContents);
    });
    let outputFile = `${directory}/${dirName}${ext}`;
    let buffer = Buffer.concat(output);
    fs.writeFileSync(outputFile, buffer);
}
concatFiles(directory);