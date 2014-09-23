<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
} ?>
<!--<pre>--><?// print_r($arResult) ?><!--</pre>-->
<?

	foreach ($arResult['PROPERIES']['CML2_TRAITS']['DESCRIPTION'] as $key => $des) {
		if ($des == 'Вес' && $arResult['PROPERIES']['CML2_TRAITS']['VALUE'][$key]) {

			array_unshift($arResult['PROPERIES'], array(
					'VALUE' => $arResult['PROPERIES']['CML2_TRAITS']['VALUE'][$key],
					'NAME' => 'Вес(нетто)'
			));
			?>
			<!--<pre>--><?//print_r($arResult['PROPERIES'])?><!--</pre>-->
		<?
		}
		elseif ($des == 'Код производителя' && $arResult['PROPERIES']['CML2_TRAITS']['VALUE'][$key]) {
			array_unshift($arResult['PROPERIES'], array(
					'VALUE' => $arResult['PROPERIES']['CML2_TRAITS']['VALUE'][$key],
					'NAME' => 'Артикул производителя'
			));
		}
	}
	$file = $_SERVER['DOCUMENT_ROOT'] . CFile::GetPath($arResult['DETAIL_PICTURE']);
	Velo::AddWatermark($file);
?>
<?$pics = CFile::ResizeImageGet($arResult['DETAIL_PICTURE'], array(
		'width' => 350,
		'height' => 370,
))?>

<div class = "main_picture">
	<div class = "big_picture">
		<a class = "big" openid = '<?= $arResult['DETAIL_PICTURE'] ?>' href = "#">
			<img id = 'small' src = "<?= $pics['src'] ?>" alt = ""/>
		</a>
	</div>
	<div style = "display: none">
		<a class = "fancy" elid = "<?= $arResult['DETAIL_PICTURE'] ?>" rel = "1" href = "<?=
			CFile::GetPath($arResult['DETAIL_PICTURE']) ?>">
		</a>
	</div>
	<br>
	<? if (is_array($arResult['PROPERIES']['MORE_PHOTO']['VALUE'])): ?>
		<?
		$small = CFile::ResizeImageGet($arResult['DETAIL_PICTURE'], array(
				'width' => 51,
				'height' => 51
		));
		$small_script = CFile::ResizeImageGet($arResult['DETAIL_PICTURE'], array(
				'width' => 350,
				'height' => 370
		))
		?>
        <style>
            .big{
                display: block;
            }
        </style>

		<div onClick = "ChangeIMG('<?= $small_script['src'] ?>', '<?= CFile::GetPath($arResult['DETAIL_PICTURE']) ?>')" class =
		"small_picture">
			<img src = "<?= $small['src'] ?>" alt = ""/>
		</div>
		<? foreach ($arResult['PROPERIES']['MORE_PHOTO']['VALUE'] as $photo): ?>
			<?
			$file = $_SERVER['DOCUMENT_ROOT'] . CFile::GetPath($photo);
			Velo::AddWatermark($file);

			$small = CFile::ResizeImageGet($photo, array(
					'width' => 51,
					'height' => 51
			)) ?>
			<?
			$small_script = CFile::ResizeImageGet($photo, array(
					'width' => 350,
					'height' => 370
			)) ?>

			<div style = "display: none">
				<a class = "fancy" rel = "1" elid = "<?= $photo ?>" href = "<?= CFile::GetPath($photo) ?>"></a>
			</div>
			<div onClick = "ChangeIMG('<?= $small_script['src'] ?>', '<?= CFile::GetPath($photo) ?>',<?= $photo ?>)" class = "small_picture">
				<img src = "<?= $small['src'] ?>" alt = ""/>
			</div>
		<? endforeach ?>
	<? endif ?>
</div>

<? $price = preg_replace("/(.*)([0-9]{3})\.[0-9]+/", "$1 $2", $arResult['OFFERS'][0]['PRICE'][0]['PRICE']) . " руб." ?>

<div class = "opisanie">
	<h4><?= $arResult['NAME'] ?></h4>

	<div class = "price1"><?= $price ?></div>

	<form method = "post" action = "">
		<input type = "hidden" value = "<?= $arResult['COLORS'][0]['ID'] ? $arResult['COLORS'][0]['ID'] : $arResult['OFFERS'][0]['ID'] ?>" id
		= "tovar_id" name = "ID">
		<input type = "submit" name = "text" class = "sub2" value = "в корзину"/>
		<input type = "hidden" name = "action" value = "ADD2BASKET">
		<input type = "hidden" name = "BACK_URL" value = "<?= $_REQUEST['REQUEST_URI'] ?>">
		<?
			$count = $arResult['OFFERS'][0]['OSTATOK'];
			switch ($count) {
				case($count < 3):
					$count_class = 1;
					break;
				case($count <= 5):
					$count_class = 2;
					break;
				case($count <= 10):
					$count_class = 3;
					break;
				default:
					$count_class = 4;
					break;
			}


		?>
		<div class = "clear"></div>
		<p>В наличии <span id = "count" class = "count count<?= $count_class ?>"></span></p>


		<div id = "frame_size_content" class = "m10"></div>


		<? if (count($arResult['SIZE']) > 0): ?>
			<div class = "select_main">
				<span>Выберите размер:</span>
				<ul class = "select_size">
					<? foreach ($arResult['SIZE'] as $vol): ?>
						<li><a rel = "<?= $arResult['ID'] ?>" href = "#"><?= $vol['VALUE'] ?></a></li>
					<? endforeach ?>
				</ul>
			</div>
		<? endif ?>
		<? if (count($arResult['COLORS']) > 0): ?>
			<div class = "clear"></div>

			<div id = 'colors_select' class = "select_main">

				<ul class = "setting_color">
					<li><span>Выберите цвет:</span></li>
					<? foreach ($arResult['COLORS'] as $color): ?>
						<li>
							<a count = "<?= Velo::CountVeloByID($color['IBLOCK_ELEMENT_ID']) ?>" rel = '<?= $color['ID'] ?>' href = "#" rel = "<?= $color['ID'] ?>"><?= $color['VALUE'] ?></a>
						</li>
					<? endforeach ?>
				</ul>
			</div>
		<? endif ?>


	</form>
</div>
<div class = "clear"></div>

<table class = "option" cellpadding = "0" cellspacing = "0">
	<?
		$types = array(
				'Горные' => 'GORNYE',
				'Горные женские' => 'GORNYE_ZHENSKIE',
				'Горные двухподвесы' => 'GORNYE_DVUKHPODVESY',
				'Горные 29"' => 'GORNYE_29',
				'Дорожные' => 'DOROZHNYE',
				'Подростковые' => 'PODROSTKOVYE',
				'Циклокроссовые' => 'TSIKLOKROSSOVYE',
				'Детские' => 'DETSKIE',
				'Шоссейные' => 'SHOSSEYNYE',
				'Складные' => 'SKLADNYE',
				'Гибридные' => 'GIBRIDNYE',
				'Электро' => 'ELEKTRO',
		);
	?>
	<?
		foreach ($arResult['PROPERIES']['CML2_TRAITS']['DESCRIPTION'] as $position => $value) {
			if ($value == "Код") {
				$id = $arResult['PROPERIES']['CML2_TRAITS']['VALUE'][$position];
			}
		}

		if ($id):?>
			<tr>
				<td width = "300"><b>Код</b></td>
				<td><?= $id ?></td>
			</tr>
		<? endif ?>
	<?$not_show = array(
			'Артикул',
			'ШтрихКод',
			'Базовая единица'
	)?>
	<? foreach ($arResult['PROPERIES'] as $key => $prop): ?>
		<? if ($prop['VALUE'] != '' && !is_array($prop['VALUE']) && !in_array($prop['NAME'], $not_show) && !in_array((string)$key, $types)
		): ?>
			<tr>
				<td width = "300"><b><?= $prop['NAME'] ?></b></td>
				<td><?= $prop['VALUE'] ?></td>
			</tr>
		<? endif ?>
	<? endforeach ?>
</table>
<div class = "marg">
	<p> <?= str_replace("\n", "<br>", $arResult['DETAIL_TEXT']) ?></p>
</div>
<div class = "clear"></div>
