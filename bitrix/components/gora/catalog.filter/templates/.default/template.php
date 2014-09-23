<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
} ?>
<?foreach($arResult['debug']['KOL_VO_SKOROSTEY'] as $vol){
	$temp_array[(int)$vol['VALUE']] = array(
		'VALUE' => $vol['VALUE'],
		'ID' => $vol['ID'],
		'type' => $vol['type']
	);
	ksort($temp_array);
}
$arResult['FORMS']['select']['Кол-во скоростей'] = Form_alter::Select($temp_array,'KOL_VO_SKOROSTEY');
?>
<div class = "clear"></div>
<style type="text/css">
	#filter_toggle{
		background-image: url(/include/image/bg_sub1.png);
		background-repeat: repeat;
		color: #fff;
		border-radius: 5px;
		display: inline;
		font-weight: normal;
		font-size: 13px;
		padding: 6px 25px 6px 10px;
		position: relative;
	}

	#filter_toggle span {
	position: absolute;
	right: 8px;
	background-image: url(/include/image/filt_doun.gif);
	background-repeat: no-repeat;
	width: 10px;
	height: 6px;
	display: block;
	top: 13px;
	}
	#filter_toggle span.active{
			background-image: url(/include/image/filt_up.gif);
		}
</style>
<h2 id="filter_toggle" style=" cursor: pointer;">Подобрать по свойствам <span></span></h2><br><br>
<div class = "filtr" style="display: none;">
	<form action = "/velo/">
		<table class = "tab_left">
			<tr>
				<td>Стоимость</td>
				<td>
					от <input class="price_value" type="text" name="price_min" value="<?=$_REQUEST['price_min']?>">
					до <input class="price_value" type="text" name="price_max" value="<?=$_REQUEST['price_max']?>">
				</td>
			</tr>
			<? foreach ($arResult['FORMS']['select'] as $key => $vol): ?>
				<tr>
					<td><?= $key ?>:</td>
					<td>
						<?= $vol ?>
					</td>
				</tr>
			<? endforeach ?>
		</table>
		<table class = 'tab_right'>
			<? foreach ($arResult['FORMS']['checkbox'] as $vol): ?>
				<?= $vol ?>
			<? endforeach ?>
		</table>
		<table class = 'tab_right'>
			<? foreach ($arResult['FORMS']['checkbox_unic'] as $vol): ?>
				<?= $vol ?>
			<? endforeach ?>
		</table>
		<div class = "clear"></div>
		<div class = "center_sub">
			<input type = "reset" name = "reset" value = "Сбросить" class = "sub4"/>
			<input type = "submit" name = "submit" value = "Подобрать" class = "sub4"/>
		</div>
	</form>
</div>
<div class = "clear"></div>
<!--<pre>--><?// print_r($arResult['debug']) ?><!--</pre>-->
<!--<div>-->
<!--	<form action = "/velo/" method = "GET">-->
<!--		<h2>Подобрать по своствам:</h2>-->
<!--		--><? // foreach ($arResult['FORMS']['select'] as $key => $vol): ?>
<!--			<div class = "option_filter">--><?//= $key ?><!-- --><?//= $vol ?><!--</div>-->
<!--		--><? // endforeach ?>
<!--		<div style = "clear: both">-->
<!--			<input class = "sub_a" type = "submit" value = "Подобрать">-->
<!--		</div>-->
<!--	</form>-->
<!--</div>-->


