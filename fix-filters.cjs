const fs = require('fs');
const path = require('path');

function walkDir(dir, callback) {
    fs.readdirSync(dir).forEach(f => {
        let dirPath = path.join(dir, f);
        let isDirectory = fs.statSync(dirPath).isDirectory();
        isDirectory ? walkDir(dirPath, callback) : callback(path.join(dir, f));
    });
}

walkDir('C:/laragon/www/asatidz/resources/js/Pages', function(filePath) {
    if (filePath.endsWith('.vue')) {
        let content = fs.readFileSync(filePath, 'utf8');
        let modified = false;

        if (content.includes('class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4"')) {
            content = content.replace(/class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4"/g, 'class="flex flex-col lg:flex-row lg:flex-wrap lg:justify-between lg:items-center gap-4"');
            modified = true;
        }

        if (content.includes('class="flex items-center gap-2"')) {
            // Only replace inside the Filter Controls section if possible, but globally is fine since it's just a flex container tweak
            content = content.replace(/class="flex items-center gap-2"/g, 'class="flex items-center gap-2 shrink-0"');
            modified = true;
        }

        if (modified) {
            fs.writeFileSync(filePath, content, 'utf8');
            console.log(`Updated ${filePath}`);
        }
    }
});
