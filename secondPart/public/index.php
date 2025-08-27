<?php declare(strict_types=1); ?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Сделки и Контакты</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="assets/css/app.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
<div class="container">
  <h1 class="title">База данных (Сделки/Контакты)</h1>

  <div class="header-row">
    <div><h3>Меню</h3></div>
    <div><h3>Список</h3></div>
    <div><h3>Содержимое</h3></div>
  </div>

  <div class="grid" id="app">
    <section class="card">
      <ul class="list" id="menu">
        <li class="item active" data-type="deal">Сделки</li>
        <li class="item" data-type="contact">Контакты</li>
      </ul>
      <button id="addBtn" class="btn">+ Добавить</button>
    </section>

    <section class="card">
      <ul class="list" id="list"></ul>
    </section>

    <section class="card">
      <div class="content" aria-live="polite" id="content"></div>
    </section>
  </div>
</div>

<div class="modal" id="modal" hidden>
  <div class="modal__dialog">
    <div class="modal__header">
      <h3 id="modalTitle"></h3>
      <button class="modal__close" id="modalClose">×</button>
    </div>
    <div class="modal__body" id="modalBody"></div>
    <div class="modal__footer">
      <button class="btn btn--ghost" id="modalCancel">Отмена</button>
      <button class="btn" id="modalSave">Сохранить</button>
    </div>
  </div>
</div>

<script src="assets/js/app.js"></script>
</body>
</html>
