<?php
$this->registerCss(".sum-widget input { margin: 5px; width: 60px; }");
?>

<div class="sum-widget">
    <input type="number" id="num1" placeholder="Number 1">
    +
    <input type="number" id="num2" placeholder="Number 2">
    =
    <input type="text" id="result" readonly>
    <br>
    <button id="calcBtn">Calculate</button>
</div>
