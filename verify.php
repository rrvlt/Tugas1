<?php
if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }
chdir(__DIR__);
if (!is_dir('docs/evidence')) mkdir('docs/evidence', 0775, true);
$commands = [
    'route-list.txt' => 'artisan route:list --except-vendor',
    'route-admin.txt' => 'artisan route:list --path=admin',
    'tests.txt' => 'artisan test --compact',
];
$status = [];
foreach ($commands as $file => $arguments) {
    echo "\n> php $arguments\n";
    $lines = []; $exit = 0;
    exec(escapeshellarg(PHP_BINARY).' '.$arguments.' 2>&1', $lines, $exit);
    $output = implode(PHP_EOL, $lines).PHP_EOL;
    echo $output;
    file_put_contents('docs/evidence/'.$file, 'Recorded: '.date(DATE_ATOM).PHP_EOL.'Command: php '.$arguments.PHP_EOL.'Exit code: '.$exit.PHP_EOL.PHP_EOL.$output);
    $status[$file] = $exit;
}
file_put_contents('docs/evidence/status.json', json_encode(['recorded_at'=>date(DATE_ATOM),'php'=>PHP_VERSION,'commands'=>$status], JSON_PRETTY_PRINT));
if (array_filter($status, fn($code) => $code !== 0)) {
    fwrite(STDERR, "Verifikasi belum lulus. Baca docs/evidence dan perbaiki error sebelum pengumpulan.\n"); exit(1);
}
echo "\nVerifikasi lulus. Hasil aktual tersimpan di docs/evidence.\n";
