<?php
declare(strict_types=1);
require_once __DIR__ . '/../Models/Contact.php';
require_once __DIR__ . '/../Models/Deal.php';
require_once __DIR__ . '/../DB.php';
final class ContactRepository{
  public function all(): array{
    return DB::pdo()->query('SELECT id,first_name,last_name FROM contacts ORDER BY id DESC')->fetchAll();
  }
  public function find(int $id): ?array{
    $st=DB::pdo()->prepare('SELECT id,first_name,last_name FROM contacts WHERE id=?');$st->execute([$id]);$c=$st->fetch();
    if(!$c) return null; $c['deals']=$this->deals($id); return $c;
  }
  public function create(array $data,array $dealIds=[]): int{
    $st=DB::pdo()->prepare('INSERT INTO contacts (first_name,last_name) VALUES (?,?)');$st->execute([$data['first_name'],$data['last_name']??null]);
    $id=(int)DB::pdo()->lastInsertId(); $this->setDeals($id,$dealIds); return $id;
  }
  public function update(int $id,array $data,array $dealIds=[]): void{
    $st=DB::pdo()->prepare('UPDATE contacts SET first_name=?,last_name=? WHERE id=?');$st->execute([$data['first_name'],$data['last_name']??null,$id]);
    $this->setDeals($id,$dealIds);
  }
  public function delete(int $id): void{
    DB::pdo()->prepare('DELETE FROM contacts WHERE id=?')->execute([$id]);
  }
  public function deals(int $contactId): array{
    $sql='SELECT d.id,d.name,d.amount FROM deals d JOIN deal_contact dc ON dc.deal_id=d.id WHERE dc.contact_id=? ORDER BY d.id DESC';
    $st=DB::pdo()->prepare($sql);$st->execute([$contactId]);return $st->fetchAll();
  }
  public function setDeals(int $contactId,array $ids): void{
    DB::pdo()->prepare('DELETE FROM deal_contact WHERE contact_id=?')->execute([$contactId]);
    if(!$ids) return; $ins=DB::pdo()->prepare('INSERT INTO deal_contact (deal_id,contact_id) VALUES (?,?)');
    for($i=0,$n=count($ids);$i<$n;$i++){ $did=(int)$ids[$i]; $ins->execute([$did,$contactId]); }
  }
}
