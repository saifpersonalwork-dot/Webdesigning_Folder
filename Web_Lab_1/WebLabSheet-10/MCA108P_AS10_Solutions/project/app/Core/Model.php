<?php
namespace App\Core;

use PDO;

abstract class Model
{
    public function __construct(protected PDO $db) {}
}
