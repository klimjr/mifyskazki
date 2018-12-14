<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$frame = $this->createFrame()->begin(""); ?>
<div class="banner-block">
    <? echo $arResult["BANNER"]; ?>
</div>
<? $frame->end();