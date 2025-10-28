<?php
declare(strict_types=1);
const DB_HOST = '127.0.0.1:3308';
const DB_NAME = 'lyubagloto';
const DB_USER = 'lyubagloto';
const DB_PASS = 'KYZFSTWeNBEXG9*E';
const DB_CHARSET = 'utf8mb4';
function dsn(): string { return 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET; }