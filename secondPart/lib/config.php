<?php
declare(strict_types=1);
const DB_HOST = 'localhost:3306';
const DB_NAME = 'uchetprosto';
const DB_USER = 'root';
const DB_PASS = 'root';
const DB_CHARSET = 'utf8mb4';
function dsn(): string { return 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET; }
