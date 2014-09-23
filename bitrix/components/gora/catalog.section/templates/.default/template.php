<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$BASE_LINKS = array(
	'Велосипеды' => '/velo/',
	'Велозапчасти' => '/velo-parts/'
);
?>
<!--<pre>--><?//print_r($arResult)?><!--</pre>-->
<ul id = "nav">
	<? foreach ($arResult['ITEMS'] as $key => $item): ?>
	<li>
		<a href = "<?= key_exists($key,$BASE_LINKS)  ? $BASE_LINKS[$key] : "/" . $item . "/" ?>"><?= $key ?></a>
		<ul>
			<? if (isset($item['brands']) && !empty($item['brands']) && is_array($item)): ?>
				<li><a href = "/velo/">Все бренды</a>
					<ul>
						<? foreach ($item['brands'] as $k => $brand): ?>
						<li><a href = "/velo/<?= urlencode($brand['VALUE']) ?>/"><?= $brand['VALUE']; ?></a>
							<? endforeach; ?>
					</ul>
				</li>

			<? endif; ?>
			<?= velo_catalog::GetHtmlMenu($item['types']) ?>
		</ul>
		<? endforeach; ?>
</ul>



