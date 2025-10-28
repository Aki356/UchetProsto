<?php declare(strict_types=1); ?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>База знаний</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="public/src/css/app.css" rel="stylesheet">
</head>
<body>
<div class="container">
  <h1 class="title">База знаний</h1>

  <div class="header-row">
    <div><h3>Тема</h3></div>
    <div><h3>Подтема</h3></div>
    <div><h3>Содержимое</h3></div>
  </div>

  <div class="grid" id="app">
    <section class="card">
      <p>Нажмите для выбора темы</p>
      <ul class="list" id="topics"></ul>
    </section>

    <section class="card">
      <p>Нажмите для выбора подтемы</p>
      <ul class="list" id="subtopics"></ul>
    </section>

    <section class="card">
      <p>Описание</p>
      <div class="content" aria-live="polite">
        <h2 id="content-topic"></h2>
        <h4 id="content-subtopic"></h4>
        <p id="content-body"></p>
      </div>
    </section>
  </div>
</div>

<script src="public/src/js/app.js"></script>
</body>
</html>
