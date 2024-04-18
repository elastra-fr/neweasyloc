<?php
require_once 'config/globals.php';
require_once 'model/Customer.php';

echo "Test terminal\n";

$customers = Customer::getAllCustomers();

print($customers);