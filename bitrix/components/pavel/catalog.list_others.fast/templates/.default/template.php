<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
} ?>


<? CModule::IncludeModule('my_module') ?>
<!--	    <pre>--><?//print_r($arResult)?><!--</pre>-->
<? if (count($arResult['ITEMS']) > 0): ?>
	<h3><?=$_REQUEST['BRAND']?></h3>
	<div class = "clear"></div>

	<? if ($arParams["FLAG_PROPERTY_CODE"] == "NEWPRODUCT"): ?>
		<h3 class = "newsale"><span></span><?=GetMessage("CR_TITLE_" . $arParams["FLAG_PROPERTY_CODE"])?></h3>
	<? elseif (strlen($arParams["FLAG_PROPERTY_CODE"]) > 0): ?>
		<h3 class = "hitsale"><span></span><?=GetMessage("CR_TITLE_" . $arParams["FLAG_PROPERTY_CODE"])?></h3>
	<?endif ?>

	<ul class = "tovar">

		<?foreach ($arResult['ITEMS'] as $key => $arItem):
			if (is_array($arItem)) {
//				$offers   = velo::GetOffers($arItem['ID']);
				$bPicture = (bool)$arItem["DETAIL_PICTURE"];
				?>
				<li>
					<? if ($bPicture): ?>
						<div class = "img_tovar">
							<a class = "link" href = "<?= $arItem["DETAIL_PAGE_URL"] ?>">
								<?$img = CFile::ResizeImageGet($arItem['DETAIL_PICTURE'], array(
									'width' => 220,
									'height' => 150
								))?>
								<img class = "item_img" itemprop = "image" src = "<?= $img['src'] ?>" alt = "<?=
								$arElement["NAME"]
								?>"/></a>
						</div>
					<? else: ?>
						<div class = "img_tovar">
							<a href = "<?= $arItem["DETAIL_PAGE_URL"] ?>">
								<img src = "/include/image/zaglushka.png"/>
							</a>
						</div>
					<?endif ?>

					<p><?=$arItem["NAME"]?></p>
					<!--	<div class="buy">
							<div class="price">-->
					<br/>
					<a class = "sub_a" href = "<?= $arItem["DETAIL_PAGE_URL"] ?>">Подробно</a>
					<!--		</div>

						   </div>    -->
<!--					--><?// if (!(is_array($arItem["OFFERS"]) && !empty($arItem["OFFERS"])) && !$arItem["CAN_BUY"]): ?>
						<div class = "price"><?=preg_replace("/([0-9]+)([0-9]{3})\.00/", "$1 $2", $arItem['PRICE'])?> руб
<!--						</div>-->
<!--					--><?// endif ?>
				</li>
			<?
			}
		endforeach;
		?>
	</ul>

	<?= $arResult['NAV_STRING'] ?>
<? elseif ($USER->IsAdmin()): ?>
	<h3 class = "hitsale"><span></span><?=GetMessage("CR_TITLE_" . $arParams["FLAG_PROPERTY_CODE"])?></h3>
	<div class = "listitem-carousel">
		<?=GetMessage("CR_TITLE_NULL")?>
	</div>
<?endif; ?>