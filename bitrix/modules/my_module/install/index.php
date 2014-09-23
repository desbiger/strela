<?
    /**
 * ����������� ������ dd_blank_module
 *
 * @author  Dev2Day
 * @since   22/01/2012
 *
 * @link    http://dev2day.net/
 */
 
/**
 * ���������� �������� ���������
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
     * ������������� ������ ��� �������� "���������� ��������"
     */
    public function my_module() {
        $this->MODULE_NAME           = GetMessage( 'DD_BM_MODULE_NAME' );
        $this->MODULE_DESCRIPTION    = GetMessage( 'DD_BM_MODULE_DESC' );
    }
 
 
 
    /**
     * ������������� ������
     */
    public function DoInstall() {
        if( !$this->InstallDB() || !$this->InstallEvents() || !$this->InstallFiles() ) {
            return;
        }
 
        RegisterModule( $this->MODULE_ID );
    }
 
    /**
     * ������� ������
     */
    public function DoUninstall() {
        if( !$this->UnInstallDB() || !$this->UnInstallEvents() || !$this->UnInstallFiles() ) {
            return;
        }
        UnRegisterModule( $this->MODULE_ID );
    }
 
    /**
     * ��������� �������� �������
     *
     * @return bool
     */
    public function InstallEvents() {
        return true;
    }
 
    /**
     * ������� �������� �������
     *
     * @return bool
     */
    public function UnInstallEvents() {
        return true;
    }
 
    /**
     * �������� ����� ���������������� �����
     *
     * @return bool
     */
    public function InstallFiles() {
        return true;
    }
 
    /**
     * ������� ����� ���������������� �����
     *
     * @return bool
     */
    public function UnInstallFiles() {
        return true;
    }
 
    /**
     * ��������� ������� � ��
     *
     * @return bool
     */
    public function InstallDB() {
        return true;
    }
 
    /**
     * ������� ������� �� ��
     *
     * @return bool
     */
    public function UnInstallDB() {
        return true;
    }
}