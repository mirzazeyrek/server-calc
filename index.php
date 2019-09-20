<?php
/**
 * Coding Kata.
 * @author Mirza ZEYREK
 */
use App\Server;
use App\VirtualMachine;

$autoloadFile = 'vendor/autoload.php';
if(!file_exists($autoloadFile)) {
    die('Please run composer install first.');
}
require $autoloadFile;

try {
    $server = new Server( 2, 16, 100 );
    $virtualMachines[] = new VirtualMachine( 1, 8, 10 );
    $virtualMachines[] = new VirtualMachine( 2, 8, 50 );
    $app = new App\Calculator( $server, $virtualMachines );
    echo $app->calculateNeededServers();
} catch(Exception $exception) {
    //mail('mirza@moebel.de', 'Calculation Error!', $exception->getMessage());
    //notifySlack('ProdFails','Server Error');
    echo $exception->getCode().$exception->getMessage();
}