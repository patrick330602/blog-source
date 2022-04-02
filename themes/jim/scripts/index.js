// index.js
var fs   = require('fs'),
  path = require('path'),
  md = require('markdown-it')({html: true});


 hexo.extend.helper.register('include_md', function (md_path) {
    var render = hexo.render, sourceDir = hexo.source_dir;

    var filepath = path.join(sourceDir, md_path);
    if (!fs.existsSync(filepath)) return filepath;

    var data = {};
    data.content = fs.readFileSync(filepath, 'utf8');

    return md.render(data.content)
});