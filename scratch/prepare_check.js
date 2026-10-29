const fs = require('fs');
let content = fs.readFileSync('resources/views/web/events/partials/step2_interactive_zone_js.blade.php', 'utf8');

// Strip <script> and </script>
content = content.replace('<script>', '// script').replace('</script>', '// endscript');

// Replace blade directives with valid JS dummy values
content = content.replace(/@json\([\s\S]*?\n/g, '[];\n');
content = content.replace(/{{[\s\S]*?}}/g, '"dummy"');

fs.writeFileSync('scratch/check_step2_full.js', content);
console.log('Wrote scratch/check_step2_full.js');

