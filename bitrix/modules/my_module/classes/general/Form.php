<?
    class Form
    {
        static function input($name, $type, $value = null, $dop = null, $string = null)
        {
            $result = '';
            $f_name = "name='{$name}'";
            $f_value = $value != null ? "value='{$value}'" : null;
            if (is_array($dop)) {
                foreach ($dop as $key => $vol) {
                    $result .= $key . "=" . $vol;
                }

            }
            return "<input type='{$type}' {$f_name} {$f_value} {$result} {$string}>";
        }

        static function select($name, $values, $select_first = false, $select = null, $options = null)
        {
            $val = '<option value=""></option>';
            if (count($values) > 0) {
                $i = 0;
                foreach ($values as $key => $vol) {
                    $i++;
                    if ($select_first && $i == 1) {
                        $selected = "selected='selected'";
                    } else {
                        $selected = $key == $select ? "selected='selected'" : "";
                    }
                    $val .= "<option value='{$key}' {$selected}>{$vol}</option>";
                }
            }
            if (is_array($options)) {
                $return = '';
                foreach ($options as $keyy => $value) {
                    $return .= $keyy . "='" . $value . "'";
                }
            }
            return "<select name='{$name}' {$return}>{$val}</select>";

        }

        static function checkbox($name, $checked = null)
        {
            return "<input type='checkbox' name='{$name}' {$checked}>";
        }

        static function radio($name, $checked = false, $value = false, $dop = null)
        {
            $str = '';
            $val = $value ? $value : '';
            $ch = $checked ? 'checked' : '';
            if (is_array($dop)) {
                foreach ($dop as $key => $vol) {
                    $str .= $key . "=" . $vol;
                }
            }
            $str = "<input name='{$name}' type='radio' value='{$val}' {$ch} {$str}>";
            return $str;
        }

    }
