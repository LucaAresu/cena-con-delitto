<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ .'/src/CenaConDelitto/')
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
    ])
    ->setFinder($finder)
;
