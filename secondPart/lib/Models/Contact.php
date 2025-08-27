<?php
declare(strict_types=1);
final class Contact{
  public function __construct(public ?int $id, public string $first_name, public ?string $last_name){}
}
