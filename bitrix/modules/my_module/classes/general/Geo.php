<?
    class Geo extends my
    {
        static function GetCities($as_select = true)
        {
            global $DB;
            $q = "
                SELECT *
                FROM
                  geo_cities
                  WHERE
                  geo_cities.socr IN('г','п','c')
                  ORDER BY name
            ";
            $res = self::Fetch($DB->query($q));


            if (!$as_select) {
                return $res;
            } else {
                $ar_select = array();
                foreach ($res as $vol) {
                    $ar_select[$vol['city_id']] = $vol['name'];
                }
                return $ar_select;
            }

        }

        static function GetStreets($City_id,$as_select = true){
            global $DB;
            $q = "
                SELECT *
                FROM
                  geo_streets
                  WHERE
                  geo_streets.city_id = {$City_id}
                  ORDER BY name
            ";
            $res = self::Fetch($DB->query($q));


            if (!$as_select) {
                return $res;
            } else {
                $ar_select = array();
                foreach ($res as $vol) {
                    $ar_select[$vol['cid']] = $vol['name'];
                }
                return $ar_select;
            }
        }
        static function GetHouses($street_id,$as_select = true){
            global $DB;
            $q = "
                SELECT *
                FROM
                  geo_houses
                  WHERE
                  geo_houses.street_id = {$street_id}
                  ORDER BY name
            ";
            $res = self::Fetch($DB->query($q));


            if (!$as_select) {
                return $res;
            } else {
                $ar_select = array();
                foreach ($res as $vol) {
                    $ar_select[$vol['cid']] = $vol['name'];
                }
                return $ar_select;
            }
        }
    }
