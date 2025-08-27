<?php
declare(strict_types=1);
require_once __DIR__ . '/../lib/Repositories/DealRepository.php';
require_once __DIR__ . '/../lib/Repositories/ContactRepository.php';
header('Content-Type: application/json; charset=utf-8');
$type=$_GET['type']??'deal'; $action=$_GET['action']??'list';
$dealRepo=new DealRepository(); $contactRepo=new ContactRepository();
try{
  switch("$type:$action"){
    case 'deal:list': echo json_encode(['ok'=>true,'items'=>$dealRepo->all()],JSON_UNESCAPED_UNICODE); break;
    case 'contact:list': echo json_encode(['ok'=>true,'items'=>$contactRepo->all()],JSON_UNESCAPED_UNICODE); break;
    case 'deal:get': $id=(int)($_GET['id']??0); echo json_encode(['ok'=>true,'item'=>$dealRepo->find($id)],JSON_UNESCAPED_UNICODE); break;
    case 'contact:get': $id=(int)($_GET['id']??0); echo json_encode(['ok'=>true,'item'=>$contactRepo->find($id)],JSON_UNESCAPED_UNICODE); break;
    case 'deal:save':
      $in=json_decode(file_get_contents('php://input'),true)??$_POST; $name=trim((string)($in['name']??''));
      if($name===''){ http_response_code(422); echo json_encode(['ok'=>false,'error'=>'Наименование обязательно']); break; }
      $amount=(float)($in['amount']??0); $contacts=array_map('intval',$in['contact_ids']??[]); $id=isset($in['id'])?(int)$in['id']:0;
      if($id>0){ $dealRepo->update($id,['name'=>$name,'amount'=>$amount],$contacts);} else { $id=$dealRepo->create(['name'=>$name,'amount'=>$amount],$contacts); }
      echo json_encode(['ok'=>true,'id'=>$id],JSON_UNESCAPED_UNICODE); break;
    case 'contact:save':
      $in=json_decode(file_get_contents('php://input'),true)??$_POST; $first=trim((string)($in['first_name']??''));
      if($first===''){ http_response_code(422); echo json_encode(['ok'=>false,'error'=>'Имя обязательно']); break; }
      $last=trim((string)($in['last_name']??'')); $dealIds=array_map('intval',$in['deal_ids']??[]); $id=isset($in['id'])?(int)$in['id']:0;
      if($id>0){ $contactRepo->update($id,['first_name'=>$first,'last_name'=>$last],$dealIds);} else { $id=$contactRepo->create(['first_name'=>$first,'last_name'=>$last],$dealIds); }
      echo json_encode(['ok'=>true,'id'=>$id],JSON_UNESCAPED_UNICODE); break;
    case 'deal:delete': $id=(int)($_POST['id']??($_GET['id']??0)); $dealRepo->delete($id); echo json_encode(['ok'=>true]); break;
    case 'contact:delete': $id=(int)($_POST['id']??($_GET['id']??0)); $contactRepo->delete($id); echo json_encode(['ok'=>true]); break;
    case 'options:contacts': echo json_encode(['ok'=>true,'items'=>$contactRepo->all()],JSON_UNESCAPED_UNICODE); break;
    case 'options:deals': echo json_encode(['ok'=>true,'items'=>$dealRepo->all()],JSON_UNESCAPED_UNICODE); break;
    default: http_response_code(400); echo json_encode(['ok'=>false,'error'=>'Unknown action']);
  }
}catch(Throwable $e){ http_response_code(500); echo json_encode(['ok'=>false,'error'=>'Server error']); }
