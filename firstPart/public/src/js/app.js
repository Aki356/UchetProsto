const $=(s,c=document)=>c.querySelector(s);const $$=(s,c=document)=>Array.from(c.querySelectorAll(s));

let KB_TOPICS=[],selectedTopicId=null,selectedSubtopic=null;

function renderTopics(){const root=$('#topics');root.innerHTML='';for(const t of KB_TOPICS){const li=document.createElement('li');li.className='item'+(t.id===selectedTopicId?' active':'');li.textContent=t.title;li.dataset.id=String(t.id);li.setAttribute('role','button');root.appendChild(li);}}
function currentTopic(){return KB_TOPICS.find(t=>t.id===selectedTopicId)}
function currentSubtopicObj(){const t=currentTopic();return t?.subtopics.find(s=>s.code===selectedSubtopic)}
function updateContentHeader(){$('#content-topic').textContent=currentTopic()?.title??'';const s=currentSubtopicObj();$('#content-subtopic').textContent=s?s.title:''}
function renderSubtopics(){const root=$('#subtopics');root.innerHTML='';const topic=currentTopic();for(const s of (topic?.subtopics??[])){const li=document.createElement('li');li.className='item'+(s.code===selectedSubtopic?' active':'');li.textContent=s.title;li.dataset.code=s.code;li.setAttribute('role','button');root.appendChild(li);}}

function loadContent(code){loadContent.cache||=new Map();if(loadContent.cache.has(code)){('#content-body').textContent=loadContent.cache.get(code);return;}
fetch(`api.php?action=content&sid=${encodeURIComponent(code)}`).then(r=>r.json()).then(({content})=>{loadContent.cache.set(code,content);$('#content-body').textContent=content}).catch(()=>{$('#content-body').textContent='Ошибка загрузки содержимого.'})}

function loadTopics(){return fetch('api.php?action=topics').then(r=>r.json()).then(({topics,defaults})=>{KB_TOPICS=topics;selectedTopicId=defaults.topicId;selectedSubtopic=defaults.subtopicCode})}

function mountEvents(){
  $('#topics').addEventListener('click',e=>{const li=e.target.closest('.item');if(!li)return;const id=Number(li.dataset.id);if(id===selectedTopicId)return;selectedTopicId=id;selectedSubtopic=currentTopic().subtopics[0].code;renderTopics();renderSubtopics();updateContentHeader();loadContent(selectedSubtopic)});
  $('#subtopics').addEventListener('click',e=>{const li=e.target.closest('.item');if(!li)return;const code=li.dataset.code;if(code===selectedSubtopic)return;selectedSubtopic=code;renderSubtopics();updateContentHeader();loadContent(selectedSubtopic)});
}

(function init(){loadTopics().then(()=>{renderTopics();renderSubtopics();updateContentHeader();loadContent(selectedSubtopic);mountEvents();});})();
