<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
#parent_popup {
  background: #000;
  height: 100%;
  opacity: 0.9;
  position: fixed;
  width: 100%;
  z-index: 100;
  top: 0;
  left: 0;
}
#popup {
  background-color: #00f;
  height: 200px;
  position: fixed;
  top: 100px;
  left: 40%;
  color: #f00;
  width: 300px;
}
</style>
</head>
<body>
<h1>Сайт</h1>
<h1>Сайт</h1>
<h1>Сайт</h1>
<h1>Сайт</h1>
<h1>Сайт</h1>
<h1>Сайт</h1>
<h1>Сайт</h1>
<h1>Сайт</h1>
<h1>Сайт</h1>
<div id="parent_popup">
  <div id="popup">
    <p style="cursor: pointer;" onclick="document.getElementById('parent_popup').style.display='none';">Закрыть</p>
    <p>Текст в окне</p>
  </div>
</div>
</body>
</html>