<?php
if (! defined('IS_CMS')) {
    die();
}

/**
 *
 * @author David Ringsdorf <git@drdf.de>
 * @copyright (c) 2016, David Ringsdorf
 * @license The MIT License (MIT)
 */
class head extends Plugin
{

    const NAME = __CLASS__;

    const VERSION = '1.1.0';

    const AUTHOR = 'David Ringsdorf';

    const DOKU_URL = 'http://mozilo.davidringsdorf.de';

    const MOZILO_VERSION = '2.0';

    private $_moziloSyntaxService;

    private $_languageObject;

    private $_pluginTemplateDir;

    private $_licenceFile;

    /**
     *
     * @global Syntax $syntax
     */
    public function __construct()
    {
        parent::__construct();

        global $syntax;

        $languageFile = $this->PLUGIN_SELF_DIR . 'language' . DIRECTORY_SEPARATOR . $this->_fetchLanguageKey() . '.txt';

        $this->_moziloSyntaxService = $syntax;
        $this->_languageObject = new Language($languageFile);
        $this->_pluginTemplateDir = $this->PLUGIN_SELF_DIR . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR;
        $this->_licenceFile = $this->PLUGIN_SELF_DIR . DIRECTORY_SEPARATOR . 'licence.txt';
    }

    /**
     *
     * @param string $value
     * @return string ist immer leer
     */
    public function getContent($value)
    {
        if (is_string($value) && strlen($value) > 0) {
            $this->_moziloSyntaxService->insert_in_head($this->_moziloSyntaxService->syntax_html(NULL, $value));
        }
        return '';
    }

    /**
     *
     * @return array
     */
    public function getDefaultSettings()
    {
        return [
            'show_placeholder' => 'true'
        ];
    }

    /**
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'show_placeholder' => [
                'type' => 'checkbox',
                'description' => $this->_languageObject->getLanguageValue('config.show_placeholder.description')
            ]
        ];
    }

    /**
     *
     * @return array
     */
    public function getInfo()
    {
        $showPlaceholder = filter_var($this->settings->get('show_placeholder'), FILTER_VALIDATE_BOOLEAN);

        // Name und Version des Plugins.
        // Den Pluginnamen in `<b> ... </b>` zu fassen, wird von mozilo vorgeschlagen.
        $info[0] = '<b>' . self::NAME . '</b> ' . self::VERSION;

        // Benoetigte moziloCMS-Version.
        $info[1] = self::MOZILO_VERSION;

        // Kurzbeschreibung.
        $info[2] = $this->_template('admin_description', $this->_languageObject, [
            'licence.text' => nl2br(file_get_contents($this->_licenceFile))
        ]);

        // Name des Autors.
        $info[3] = self::AUTHOR;

        // Webseite.
        $info[4] = self::DOKU_URL;

        // (optional) Platzhalter der im Seiten-Editor vorgeschlagen wird.
        if ($showPlaceholder) {
            $info[5] = [
                '{' . __CLASS__ . '|...}' => $this->_languageObject->getLanguageValue('info.placeholder.title')
            ];
        }

        return $info;
    }

    /**
     *
     * @global Properties $ADMIN_CONF
     * @global Properties $CMS_CONF
     * @return string
     */
    private function _fetchLanguageKey()
    {
        global $ADMIN_CONF, $CMS_CONF;

        $language = $CMS_CONF->get('cmslanguage');
        if (IS_ADMIN) {
            $language = $ADMIN_CONF->get('language');
        }

        return $language;
    }

    /**
     *
     * @param string $templateName
     * @param Language $languageObject
     * @param array|NULL $param
     * @return string
     */
    private function _template($templateName, Language $languageObject = NULL, $param = NULL)
    {
        ob_start();
        $t = $languageObject;
        $p = $param;
        require $this->_pluginTemplateDir . $templateName . '.php';
        return (string) ob_get_clean();
    }
}