<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Навигация по тестовому заданию</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <style>
    :root{
      /* Deep Blue Sea */
      --bg:#0B2545; --card:#13315C; --text:#EEF4ED;
      --muted:#8DA9C4; --accent:#EEF4ED; --border:#134074;
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;background:var(--bg);color:var(--text);
      font:16px/1.45 system-ui,-apple-system,Segoe UI,Roboto,Arial,"Noto Sans";
    }
    .container{max-width:980px;margin:56px auto;padding:0 16px}
    .title{margin:0 0 6px 0;font-size:32px;font-weight:800}
    .subtitle{margin:0 0 28px 0;color:var(--muted);font-size:14px}

    .grid{
      display:grid;
      grid-template-columns:repeat(2,minmax(260px,1fr));
      gap:16px;
    }
    .card{
      background:var(--card);
      border:1px solid var(--border);
      border-radius:14px;
      padding:18px;
      display:flex;flex-direction:column;justify-content:space-between;
      transition:transform .12s ease, box-shadow .12s ease, background .12s ease;
      text-decoration:none;color:inherit; position:relative; overflow:hidden;
    }
    .card:hover{ transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,.25); background:#133968; }
    .card:active{ transform:translateY(0); }
    .card h3{margin:0 0 6px 0;font-size:20px}
    .card p{margin:0 0 14px 0;color:var(--muted);font-size:14px}
    .meta{display:flex; gap:10px; color:var(--muted); font-size:12px}
    .btn{
      align-self:flex-start;
      background:var(--accent); color:var(--bg);
      border:0; border-radius:10px; font-weight:800;
      padding:10px 14px; cursor:pointer; text-decoration:none;
    }
    .btn:focus-visible{outline:2px solid var(--accent); outline-offset:2px}
    .footer{margin-top:28px;color:var(--muted);font-size:12px}
    @media (max-width:720px){ .grid{grid-template-columns:1fr} }
  </style>
</head>
<body>
  <div class="container">
    <h1 class="title">Навигация по тестовым заданиям</h1>
    <p class="subtitle">Выберите часть: первая (простая база знаний) или вторая (сделки и контакты).</p>

    <div class="grid">

      <!-- Карточка: Первая часть -->
      <a class="card" href="firstPart/public/index.php" aria-label="Открыть первую часть ТЗ">
        <div>
          <h3>Часть 1 — База знаний</h3>
          <p>Темы и подтемы с контентом. Кликабельные списки, заголовок и подзаголовок в блоке «Содержимое».</p>
          <div class="meta">
            <span>PHP · JS · HTML/CSS</span>
          </div>
        </div>
        <span class="btn">Открыть</span>
      </a>

      <!-- Карточка: Вторая часть -->
      <a class="card" href="secondPart/public/index.php" aria-label="Открыть вторую часть ТЗ">
        <div>
          <h3>Часть 2 — Сделки/Контакты</h3>
          <p>CRUD, связь многие-ко-многим, модальные формы, таблицы значений полей, палитра Deep Blue Sea.</p>
          <div class="meta">
            <span>PHP · MySQL · JS · HTML/CSS</span>
          </div>
        </div>
        <span class="btn">Открыть</span>
      </a>

    </div>
  </div>
</body>
</html>
