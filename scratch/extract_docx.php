<?php

function readDocx($filename) {
    $zip = new ZipArchive();
    if ($zip->open($filename) === TRUE) {
        $xml = $zip->getFromName('word/document.xml');
        $zip->close();
        
        // Parse XML with DOMDocument to get better structure
        $dom = new DOMDocument();
        @$dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        
        $paragraphs = $xpath->query('//w:p');
        $lines = [];
        foreach ($paragraphs as $p) {
            $texts = $xpath->query('.//w:t', $p);
            $line = '';
            foreach ($texts as $t) {
                $line .= $t->nodeValue;
            }
            if (trim($line) !== '') {
                $lines[] = $line;
            }
        }
        return implode("\n", $lines);
    }
    return '';
}

$files = [
    'POLITICAS DE PRIVACIDAD.docx',
    'Política de Cookies.docx',
    'TERMINOS Y CONDICIONES.docx'
];

foreach ($files as $file) {
    $path = __DIR__ . '/../' . $file;
    if (file_exists($path)) {
        $out = readDocx($path);
        file_put_contents(__DIR__ . '/' . pathinfo($file, PATHINFO_FILENAME) . '.txt', $out);
        echo "Extracted: $file (" . strlen($out) . " chars)\n";
    } else {
        echo "Not found: $path\n";
    }
}
