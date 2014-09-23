<?
    class soc
    {
        static function FBLikes($link)
        {
//            $link = 'http://imho.sakharov-studio.ru/';
            /* url страницы, лайки которой, собственно достаем */
            /**
             * Для получения данных из системы Facebook использует FQL
             * Facebook Query Language, который похож на SQL, лишь с несколькими
             * ньюансами, подробнее - http://developers.facebook.com/docs/reference/fql/
             */

            /* кодируем запрос на получение количества лайков+количество шейров нашей страницы */
            $fql = urlencode("SELECT total_count FROM link_stat WHERE url=\"{$link}\"");
            $fbLink = 'http://api.facebook.com/method/fql.query?query='; /* адрес api facebook'а */
            /*
             * Конкатенируем адрес api с нашим запросом и получаем результат запроса
             * http://api.facebook.com/method/fql.query?query=YOUR_FQL вернет XML с лайками,
             * в то же время, как http://graph.facebook.com/YOUR_URL вернет JSON с лайками +
             * информацией, которую система собрала из ваших Open Graph мета-тэгов
             * Так как мне нужны только лайки - я выбрал вариант #1
             */
            $response = file_get_contents($fbLink . $fql);
            $fbXML = simplexml_load_string($response); /* Из полученного xml создаем объект */
            /*
             * И собственно достаем количество лайков нашей страницы
             * здесь использовал явное преобразование типа, так как конструкция
             * $fbXML->link_stat->total_count возвращала DOMNodeList
             */
            return $fbXML;
        }
    }
