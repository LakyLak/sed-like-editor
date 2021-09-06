<?php
 
$substitution = false;
 
if (count($argv) == 1) {
    printHelp();
    return;
}
 
for ($i = 1; $i < count($argv); $i++) {
    if (substr($argv[$i], 0, 1) === "-") {
        $arguments['options'][] = $argv[$i];
        continue;
    }
    
    if (substr($argv[$i], 0, 2) === "s/") {
        $substitution = true;
        $arguments['replace'] = getReplaceArgumets($argv[$i]);
        continue;
    }
    
    if ($substitution && !isset($arguments['inFile'])) {
        $arguments['inFile'] = $argv[$i];
        continue;
    }
    
    if (isset($arguments['inFile'])) {
        $arguments['toFile'] = $argv[$i];
    }
}

replace($arguments);
 
function printHelp()
{
    echo 'SedLike Stream Editor' . PHP_EOL . PHP_EOL;
    echo 'Run with command: "php sedlike.php [OPTIONS:optional] [SCRIPT] [FILE] [OUTPUT FILE:optional]"' . PHP_EOL;
    echo 'Options:' . PHP_EOL;
    echo '  -i  : in-file change (without output)' . PHP_EOL . PHP_EOL;
    echo 'Script:' . PHP_EOL;
    echo '  s/[SEARCH]/[REPLACEMENT]/  : script replaces the text' . PHP_EOL . PHP_EOL;
    echo 'Example: php sedlike.php -i s/human/robot/ file1.txt file2.txt' . PHP_EOL . PHP_EOL;
    echo 'To run tests run "./vendor/bin/phpunit tests --color"' . PHP_EOL . PHP_EOL;
}
 
function getReplaceArgumets($args)
{
    $fields = explode('/', $args);
    
    $arguments['from'] = $fields[1];
    $arguments['to']   = $fields[2];
    return $arguments;
}
 
function replace(array $arguments)
{
    $inPlace = false;
    
    if (!empty($arguments['options']) && in_array('-i', $arguments['options'])) {
        $inPlace = true;
    }
    
    $fileFrom    = getFileFrom($arguments);
    $fileTo      = getFileTo($arguments);
    $content     = file_get_contents("$fileFrom");
    $replaceFrom = $arguments['replace']['from'];
    $replaceTo   = $arguments['replace']['to'];
    
    $output = str_replace($replaceFrom, $replaceTo, $content);

    file_put_contents("$fileTo", $output);
    
    if (!$inPlace) {
        echo $output;
    }
}
 
function getFileFrom($arguments)
{
    if (file_exists($arguments['inFile'])) {
        return $arguments['inFile'];
    }
}
 
function getFileTo(array $arguments)
{
    if (isset($arguments['toFile'])) {
        return $arguments['toFile'];
    } 
    
    return $arguments['inFile'];
}

