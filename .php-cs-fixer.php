<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__)
    ->exclude('vendor') 
    ->exclude('tests')  
    ->name('*.php');   

return (new Config())
    ->setRules([
        '@PSR12' => true, 
        'array_syntax' => ['syntax' => 'short'], 
        'no_unused_imports' => true, 
        'ordered_imports' => ['sort_algorithm' => 'alpha'], 
    ])
    ->setFinder($finder);
