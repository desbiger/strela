<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
} ?>
<? if (count($arResult) > 0): ?>
	<h3>Спецпредложения</h3>
	<div class = "clear"></div>
	<?
	$notifyOption = COption::GetOptionString("sale", "subscribe_prod", "");
	$arNotify     = unserialize($notifyOption);
	?>
	<? if ($arParams["FLAG_PROPERTY_CODE"] == "NEWPRODUCT"): ?>
		<h3 class = "newsale"><span></span><?= GetMessage("CR_TITLE_" . $arParams["FLAG_PROPERTY_CODE"]) ?></h3>
	<? elseif (strlen($arParams["FLAG_PROPERTY_CODE"]) > 0): ?>
		<h3 class = "hitsale"><span></span><?= GetMessage("CR_TITLE_" . $arParams["FLAG_PROPERTY_CODE"]) ?></h3>
	<?endif ?>

	<ul class = "tovar">
<?$i = 0;?>
		<?foreach ($arResult as $key => $arItem):
		$i++;
			if ($i <= 9) {
				$bPicture = CFile::REsizeImageGet($arItem['DETAIL_PICTURE'], array(
					'width' => 180,
					'height' => 180
				));
				?>
				<li>
					<? if ($bPicture): ?>
						<div class = "img_tovar">
							<a class = "link" href = "<?= $arItem["DETAIL_PAGE_URL"] ?>">
								<img class = "item_img" itemprop = "image" src = "<?= $bPicture["src"] ?>" alt = "<?= $arElement["NAME"] ?>"/>
							</a>
						</div>
					<? else: ?>
						<div class = "img_tovar">
							<a href = "<?= $arItem["DETAIL_PAGE_URL"] ?>">
								<div class = "no-photo-div-big" style = "height:130px; width:130px;">
									<img src="/include/image/zaglushka.png" >
								</div>
							</a>
						</div>
					<?endif ?>

					<p><?= $arItem["NAME"] ?></p>
					<!--	<div class="buy">
							<div class="price">--><?
					if ((bool)$arItem['PRICE']) //if product has offers
					{
							?>
							<div itemprop = "price" class = "price">
								<?
								echo $arItem["PRICE"];
								?>
							</div>
						<?
					}

					?>
					<a class = "sub_a" href = "<?= $arItem["DETAIL_PAGE_URL"] ?>">Подробно</a>
					<!--		</div>

						   </div>    -->
					<? if (!(is_array($arItem["OFFERS"]) && !empty($arItem["OFFERS"])) && !$arItem["CAN_BUY"]): ?>
						<div class = "badge notavailable"><?= GetMessage("CATALOG_NOT_AVAILABLE2") ?></div>
					<? endif ?>
				</li>
			<?
			}
		endforeach;
		?>
	</ul>

<? elseif ($USER->IsAdmin()): ?>
	<h3 class = "hitsale"><span></span><?= GetMessage("CR_TITLE_" . $arParams["FLAG_PROPERTY_CODE"]) ?></h3>
	<div class = "listitem-carousel">
		<?= GetMessage("CR_TITLE_NULL") ?>
	</div>
<?endif; ?>