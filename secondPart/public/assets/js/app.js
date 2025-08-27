const $ = sel => document.querySelector(sel);
const $$ = sel => Array.from(document.querySelectorAll(sel));

let currentType = 'deal';
let selectedId = null;

function setActiveMenu(type){
  currentType = type;
  $$('#menu .item').forEach(li => li.classList.toggle('active', li.dataset.type===type));
}

function loadList(){
  $('#list').innerHTML = '';
  fetch(`api.php?type=${currentType}&action=list`).then(r=>r.json()).then(({items})=>{
    for(const x of items){
      const li = document.createElement('li');
      li.className = 'item';
      li.dataset.id = x.id;
      li.textContent = (currentType==='deal') ? `${x.name}` : `${x.first_name} ${x.last_name??''}`.trim();
      li.addEventListener('click', ()=>{ selectedId = x.id; selectListItem(li); loadItem(); });
      $('#list').appendChild(li);
    }
    const first = $('#list .item');
    if(first){ first.click(); }
    else { $('#content').innerHTML = '<p>Нет данных. Нажмите «Добавить».</p>'; }
  });
}

function selectListItem(li){ $$('#list .item').forEach(el => el.classList.toggle('active', el===li)); }

function loadItem(){
  if(!selectedId){ $('#content').innerHTML=''; return; }
  fetch(`api.php?type=${currentType}&action=get&id=${selectedId}`).then(r=>r.json()).then(({item})=>{
    if(currentType==='deal'){ renderDeal(item); } else { renderContact(item); }
  });
}

function renderDeal(d){
  const rows = [['id сделки', String(d.id)], ['Наименование', d.name], ['Сумма', new Intl.NumberFormat('ru-RU').format(Number(d.amount||0))]];
  let html = `<h2>Сделка</h2><h4>${d.name}</h4><div class="kv">`;
  for(const [k,v] of rows){ html += `<div class="key">${k}</div><div>${v}</div>`; }
  html += `</div>`;
  html += `<table class="table"><thead><tr><th>id контакта</th><th>Имя Фамилия</th></tr></thead><tbody>`;
  for(const c of d.contacts){ const fio = `${c.first_name} ${c.last_name??''}`.trim(); html += `<tr><td>${c.id}</td><td>${fio||'-'}</td></tr>`; }
  html += `</tbody></table>` + actionsHtml();
  $('#content').innerHTML = html; bindActions();
}

function renderContact(c){
  const fio = `${c.first_name} ${c.last_name??''}`.trim();
  const rows = [['id контакта', String(c.id)], ['Имя', c.first_name], ['Фамилия', c.last_name||'—']];
  let html = `<h2>Контакт</h2><h4>${fio}</h4><div class="kv">`;
  for(const [k,v] of rows){ html += `<div class="key">${k}</div><div>${v}</div>`; }
  html += `</div>`;
  html += `<table class="table"><thead><tr><th>id сделки</th><th>Наименование</th></tr></thead><tbody>`;
  for(const d of c.deals){ html += `<tr><td>${d.id}</td><td>${d.name}</td></tr>`; }
  html += `</tbody></table>` + actionsHtml();
  $('#content').innerHTML = html; bindActions();
}

function actionsHtml(){ return `<div class="actions"><button class="btn" id="editBtn">Редактировать</button><button class="btn btn--danger" id="deleteBtn">Удалить</button></div>`; }
function bindActions(){ $('#editBtn')?.addEventListener('click', openEdit); $('#deleteBtn')?.addEventListener('click', onDelete); }

function openEdit(){
  $('#modalTitle').textContent = currentType==='deal' ? 'Сделка' : 'Контакт';
  if(currentType==='deal'){ renderDealForm(); } else { renderContactForm(); }
  openModal();
}

function renderDealForm(){
  Promise.all([ fetch(`api.php?type=deal&action=get&id=${selectedId}`).then(r=>r.json()), fetch(`api.php?type=options&action=contacts`).then(r=>r.json()) ]).then(([{item},{items:contacts}])=>{
    const selected = new Set(item.contacts.map(c=>String(c.id)));
    const options = contacts.map(c=>{ const val = String(c.id), label = `${c.first_name} ${c.last_name??''}`.trim(); const sel = selected.has(val) ? ' selected' : ''; return `<option value="${val}"${sel}>${label||('ID '+val)}</option>`; }).join('');
    $('#modalBody').innerHTML = `<div class="form-row"><label>Наименование *</label><input type="text" id="f_name" value="${escapeHtml(item.name)}"></div><div class="form-row"><label>Сумма</label><input type="number" id="f_amount" step="0.01" value="${Number(item.amount||0)}"></div><div class="form-row"><label>Контакты (множеств.)</label><select id="f_contacts" multiple>${options}</select></div>`;
    $('#modalSave').onclick = () => { const payload = { id: item.id, name: $('#f_name').value.trim(), amount: Number($('#f_amount').value||0), contact_ids: Array.from($('#f_contacts').selectedOptions).map(o=>Number(o.value)) }; if(!payload.name){ alert('Наименование обязательно'); return; } fetch('api.php?type=deal&action=save',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)}).then(r=>r.json()).then(()=>{ closeModal(); loadList(); }); };
  });
}

function renderContactForm(){
  Promise.all([ fetch(`api.php?type=contact&action=get&id=${selectedId}`).then(r=>r.json()), fetch(`api.php?type=options&action=deals`).then(r=>r.json()) ]).then(([{item},{items:deals}])=>{
    const selected = new Set(item.deals.map(d=>String(d.id)));
    const options = deals.map(d=>{ const val = String(d.id), label = d.name; const sel = selected.has(val) ? ' selected' : ''; return `<option value="${val}"${sel}>${label}</option>`; }).join('');
    $('#modalBody').innerHTML = `<div class="form-row"><label>Имя *</label><input type="text" id="f_first" value="${escapeHtml(item.first_name)}"></div><div class="form-row"><label>Фамилия</label><input type="text" id="f_last" value="${escapeHtml(item.last_name||'')}"></div><div class="form-row"><label>Сделки (множеств.)</label><select id="f_deals" multiple>${options}</select></div>`;
    $('#modalSave').onclick = () => { const payload = { id: item.id, first_name: $('#f_first').value.trim(), last_name: $('#f_last').value.trim(), deal_ids: Array.from($('#f_deals').selectedOptions).map(o=>Number(o.value)) }; if(!payload.first_name){ alert('Имя обязательно'); return; } fetch('api.php?type=contact&action=save',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)}).then(r=>r.json()).then(()=>{ closeModal(); loadList(); }); };
  });
}

function onDelete(){
  if(!confirm('Удалить элемент?')) return;
  fetch(`api.php?type=${currentType}&action=delete`, {method:'POST', body:new URLSearchParams({id:String(selectedId)})}).then(r=>r.json()).then(()=>{ selectedId=null; loadList(); });
}

function addNew(){
  $('#modalTitle').textContent = currentType==='deal' ? 'Новая сделка' : 'Новый контакт';
  if(currentType==='deal'){
    fetch(`api.php?type=options&action=contacts`).then(r=>r.json()).then(({items})=>{
      const options = items.map(c=>`<option value="${c.id}">${(c.first_name+' '+(c.last_name??'')).trim()}</option>`).join('');
      $('#modalBody').innerHTML = `<div class="form-row"><label>Наименование *</label><input type="text" id="f_name"></div><div class="form-row"><label>Сумма</label><input type="number" id="f_amount" step="0.01"></div><div class="form-row"><label>Контакты (множеств.)</label><select id="f_contacts" multiple>${options}</select></div>`;
      $('#modalSave').onclick = () => { const payload = { name: $('#f_name').value.trim(), amount: Number($('#f_amount').value||0), contact_ids: Array.from($('#f_contacts').selectedOptions).map(o=>Number(o.value)) }; if(!payload.name){ alert('Наименование обязательно'); return; } fetch('api.php?type=deal&action=save',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)}).then(r=>r.json()).then(()=>{ closeModal(); loadList(); }); };
      openModal();
    });
  } else {
    fetch(`api.php?type=options&action=deals`).then(r=>r.json()).then(({items})=>{
      const options = items.map(d=>`<option value="${d.id}">${d.name}</option>`).join('');
      $('#modalBody').innerHTML = `<div class="form-row"><label>Имя *</label><input type="text" id="f_first"></div><div class="form-row"><label>Фамилия</label><input type="text" id="f_last"></div><div class="form-row"><label>Сделки (множеств.)</label><select id="f_deals" multiple>${options}</select></div>`;
      $('#modalSave').onclick = () => { const payload = { first_name: $('#f_first').value.trim(), last_name: $('#f_last').value.trim(), deal_ids: Array.from($('#f_deals').selectedOptions).map(o=>Number(o.value)) }; if(!payload.first_name){ alert('Имя обязательно'); return; } fetch('api.php?type=contact&action=save',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)}).then(r=>r.json()).then(()=>{ closeModal(); loadList(); }); };
      openModal();
    });
  }
}

function openModal(){ $('#modal').hidden=false; }
function closeModal(){ $('#modal').hidden=true; }
$('#modalClose').addEventListener('click', closeModal);
$('#modalCancel').addEventListener('click', closeModal);
$('#modal').addEventListener('click', (e)=>{
  if(!e.target.closest('.modal__dialog')) closeModal();
});
document.addEventListener('keydown', (e)=>{
  if(e.key === 'Escape' && !$('#modal').hidden) closeModal();
});

$('#menu').addEventListener('click', e=>{ const li = e.target.closest('.item'); if(!li) return; setActiveMenu(li.dataset.type); selectedId=null; loadList(); });
$('#addBtn').addEventListener('click', addNew);

function escapeHtml(s){ return (s??'').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }
setActiveMenu('deal'); loadList();
