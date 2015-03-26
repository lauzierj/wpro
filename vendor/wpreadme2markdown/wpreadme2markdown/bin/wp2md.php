<?php
/**
 * @author Christian Archer <chrstnarchr@aol.com>
 * @copyright © 2014, Christian Archer
 * @license MIT
 */

if (is_file(__DIR__ . '/../vendor/autoload.php')) {
    require(__DIR__ . '/../vendor/autoload.php');
} elseif (is_file(__DIR__ . '/../../../autoload.php')) {
    require(__DIR__ . '/../../../autoload.php');
} else {
    die(
        'You must set up the wp2md dependencies, run the following commands:' . PHP_EOL .
        'curl -s http://getcomposer.org/installer | php' . PHP_EOL .
        'php composer.phar install' . PHP_EOL
    );
}

$application = new \Symfony\Component\Console\Application('WPReadme2Markdown', '@package_version@');
$application->add(new \WPReadme2Markdown\cli\Convert());
$application->run();

__HALT_COMPILER(); // allow project to be used in PHAR
