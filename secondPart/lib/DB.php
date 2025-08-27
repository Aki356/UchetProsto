<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
final class DB{
  private static ?PDO $pdo=null;
  public static function pdo(): PDO{
    if(!self::$pdo){
      self::$pdo = new PDO(dsn(), DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES=>false,
      ]);
    }
    return self::$pdo;
  }
}
