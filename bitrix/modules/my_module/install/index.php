<?
    /**
 * Инсталляция модуля dd_blank_module
 *
 * @author  Dev2Day
 * @since   22/01/2012
 *
 * @link    http://dev2day.net/
 */
 
/**
 * Подключаем языковые константы
 */
global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-18);
@include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));
IncludeModuleLangFile($strPath2Lang."/install/index.php");
 
class my_module extends CModule {
 
    public $MODULE_ID           = 'my_module';
    public $MODULE_VERSION      = '1.0.0';
    public $MODULE_VERSION_DATE = '2012-01-22 13:00:00';
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
 
    /**
     * Инициализация модуля для страницы "Управление модулями"
     */
    public function my_module() {
        $this->MODULE_NAME           = GetMessage( 'DD_BM_MODULE_NAME' );
        $this->MODULE_DESCRIPTION    = GetMessage( 'DD_BM_MODULE_DESC' );
    }
 
 
 
    /**
     * Устанавливаем модуль
     */
    public function DoInstall() {
        if( !$this->InstallDB() || !$this->InstallEvents() || !$this->InstallFiles() ) {
            return;
        }
 
        RegisterModule( $this->MODULE_ID );
    }
 
    /**
     * Удаляем модуль
     */
    public function DoUninstall() {
        if( !$this->UnInstallDB() || !$this->UnInstallEvents() || !$this->UnInstallFiles() ) {
            return;
        }
        UnRegisterModule( $this->MODULE_ID );
    }
 
    /**
     * Добавляем почтовые события
     *
     * @return bool
     */
    public function InstallEvents() {
        return true;
    }
 
    /**
     * Удаляем почтовые события
     *
     * @return bool
     */
    public function UnInstallEvents() {
        return true;
    }
 
    /**
     * Копируем файлы административной части
     *
     * @return bool
     */
    public function InstallFiles() {
        return true;
    }
 
    /**
     * Удаляем файлы административной части
     *
     * @return bool
     */
    public function UnInstallFiles() {
        return true;
    }
 
    /**
     * Добавляем таблицы в БД
     *
     * @return bool
     */
    public function InstallDB() {
        return true;
    }
 
    /**
     * Удаляем таблицы из БД
     *
     * @return bool
     */
    public function UnInstallDB() {
        return true;
    }
}