<?php
	class Form_alter
	{
		static function Select($array, $name)
		{
			$props  = array(
				"<option value=''>Ћюбое значение</option>"
			);
			$before = "<select name='{$name}'>";
			foreach ($array as $vol) {
				$selected = $_REQUEST[$name] == $vol['VALUE'] ? "selected='selected'" : '';
				$props[]  = "<option {$selected} value='{$vol['VALUE']}'>{$vol['VALUE']}</option>";
			}
			$after = "</select>";
			return $before . implode("\n", $props) . $after;
		}

		static function Checkbox($array, $name,$title, $rows = 2)
		{
			$content = '';
			$props   = array();
			$before  = "
			<tr>
			<td><span style='padding-bottom: 7px; font-weight: bold;'>{$title}</span>
			</td></tr>";
			$i       = 0;
			foreach ($array as $vol) {
				if ($i < $rows-1) {
					$i++;
					$selected = in_array($vol['VALUE'],$_REQUEST[$name]) == $vol['VALUE'] ? "checked" : '';
					$content .= "
					<td>
					<label>
					<div style='float:left;'>
					<input type='checkbox' {$selected} value='{$vol['VALUE']}' name='{$name}[]'/>
					</div>
					<span>{$vol['VALUE']}</span>
					</label>
					</td>";
				}
				else {
					$selected = in_array($vol['VALUE'],$_REQUEST[$name]) == $vol['VALUE'] ? "checked" : '';

					$content .= "
<td>
										<label>
										<div style='float:left;'>
										<input type='checkbox' {$selected} value='{$vol['VALUE']}' name='{$name}[]'/>
										</div>
										<span>{$vol['VALUE']}</span>
										</label></td>";
					$props[] = "<tr>" . $content . "</tr>";
					$i       = 0;
					$content = '';
				}
			}
			$after = "";
			return $before . implode("\n", $props) . $after;
		}

		static function Checkbox_dif_names($array,$title, $rows = 2)
		{
			$content = '';
			$props   = array();
			$before  = "
			<tr>
			<td><span style='padding-bottom: 7px; font-weight: bold;'>{$title}</span>
			</td></tr>";
			$i       = 0;
			foreach ($array as $vol=>$code) {
				if ($i < $rows-1) {
					$i++;
					$selected = $_REQUEST[$code] == "да" ? "checked" : '';
					$content .= "
					<td>
					<label>
					<div style='float:left;'>
					<input type='checkbox' {$selected} value='да' name='{$code}'/>
					</div>
					<span>{$vol}</span>
					</label>
					</td>";
				}
				else {
					$selected = $_REQUEST[$code] == "да" ? "checked" : '';

					$content .= "
<td>
										<label>
										<div style='float:left;'>
										<input type='checkbox' {$selected} value='да' name='{$code}'/>
										</div>
										<span>{$vol}</span>
										</label></td>";
					$props[] = "<tr>" . $content . "</tr>";
					$i       = 0;
					$content = '';
				}
			}
			$after = "";
			return $before . implode("\n", $props) . $after;
		}

	}