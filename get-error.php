<?php
$lines = file('storage/logs/laravel.log');
$errors = [];
$currentError = [];
foreach ($lines as $line) {
    if (preg_match('/^\[\d{4}-\d{2}-\d{2}/', $line)) {
        if (!empty($currentError)) {
            $errors[] = implode("", $currentError);
        }
        $currentError = [$line];
    } elseif (!empty($currentError)) {
        if (count($currentError) < 15) { // get first few lines of trace
            $currentError[] = $line;
        }
    }
}
if (!empty($currentError)) $errors[] = implode("", $currentError);
echo end($errors);
