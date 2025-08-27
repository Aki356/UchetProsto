<?php
declare(strict_types=1);
require_once __DIR__ . '/../Models/Deal.php';
require_once __DIR__ . '/../Models/Contact.php';
require_once __DIR__ . '/../DB.php';
final class DealRepository{
  public function all(): array{
    return DB::pdo()->query('SELECT id,name,amount FROM deals ORDER BY id DESC')->fetchAll();
  }
  public function find(int $id): ?array{
    $st=DB::pdo()->prepare('SELECT id,name,amount FROM deals WHERE id=?');$st->execute([$id]);$d=$st->fetch();
    if(!$d) return null; $d['contacts']=$this->contacts($id); return $d;
  }
  public function create(array $data, array $contactIds=[]): int{
    $st=DB::pdo()->prepare('INSERT INTO deals (name,amount) VALUES (?,?)');$st->execute([$data['name'],$data['amount']??0]);
    $id=(int)DB::pdo()->lastInsertId(); $this->setContacts($id,$contactIds); return $id;
  }
  public function update(int $id,array $data,array $contactIds=[]): void{
    $st=DB::pdo()->prepare('UPDATE deals SET name=?,amount=? WHERE id=?');$st->execute([$data['name'],$data['amount']??0,$id]);
    $this->setContacts($id,$contactIds);
  }
  public function delete(int $id): void{
    DB::pdo()->prepare('DELETE FROM deals WHERE id=?')->execute([$id]);
  }
  public function contacts(int $dealId): array{
    $sql='SELECT c.id,c.first_name,c.last_name FROM contacts c JOIN deal_contact dc ON dc.contact_id=c.id WHERE dc.deal_id=? ORDER BY c.id DESC';
    $st=DB::pdo()->prepare($sql);$st->execute([$dealId]);return $st->fetchAll();
  }
  public function setContacts(int $dealId,array $ids): void{
    DB::pdo()->prepare('DELETE FROM deal_contact WHERE deal_id=?')->execute([$dealId]);
    if(!$ids) return; $ins=DB::pdo()->prepare('INSERT INTO deal_contact (deal_id,contact_id) VALUES (?,?)');
    for($i=0,$n=count($ids);$i<$n;$i++){ $cid=(int)$ids[$i]; $ins->execute([$dealId,$cid]); }
  }
}
