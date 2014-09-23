<?php
    $arClasses = array(
        'MY' => 'classes/general/my.php',
//        'geo' => 'classes/general/Geo.php',
//        'soc' => 'classes/general/Soc.php',
//        'vkapi' => 'classes/general/vkapi.class.php',
        'form' => 'classes/general/Form.php',
        'Form_alter' => 'classes/general/Form_alter.php',
        'velo_properties' => 'classes/general/velo_properties.php',
        'velo_config' => 'classes/general/velo_config.php',
        'velo' => 'classes/general/Velo.php',
    );
    CModule::AddAutoloadClasses("my_module", $arClasses);