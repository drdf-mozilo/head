<?php if(!defined('IS_CMS')){ die();}

class head extends Plugin {

    const NAME = __CLASS__;
    const VERSION = '1.0.0';
    const AUTHOR = 'David Ringsdorf';
    const DOKU_URL = 'http://mozilo.drdf.de';
    const LICENSE = 'MIT';

    const MOZILO_VERSION = '2.0';

    const DS = DIRECTORY_SEPARATOR;
    const BASE_DIR = __DIR__; // $this->PLUGIN_SELF_DIR

    const LANG_DIR_NAME = 'sprache'; // 'sprache' ist von mozilo vorgegeben
    const LANG_FILE_SUFFIX = '.txt';
    const LANG_FILE_PREFIX_ADMIN = 'admin_';

    private $syntax;
    private $adminConf;

    private $adminLang;
    private $adminLanguage ;

    // Damit Styles nicht mitten im Code stehen. Eine Template-Datei lohnt hier nicht.
    private $style_plugin_name = '<b>%s</b> %s';
    private $style_license = '<p>%s:<br /><br /><small>%s</small></p>';

    private $code_example = '<pre><code>{head|<br />&nbsp;&nbsp;&nbsp;&nbsp;&lt;!-- head-Plugin --&gt;<br />&nbsp;&nbsp;&nbsp;&nbsp;&lt;style type="text/css"&gt; h1 ^{background-color: #f00;^} &lt;/style&gt;<br />&nbsp;&nbsp;&nbsp;&nbsp;&lt;script type="text/javascript"&gt; document.write("Hello World!") &lt;/script&gt;<br />}</code></pre>';

    /**
     * Stellt die Arbeitsgrundlage bereit
     * 
     * Muss den 'parent-constructor' aufrufen.
     * 
     * @global obj $ADMIN_CONF
     * @global obj $syntax
     */
    public function __construct(){
        parent::__construct();

        global $syntax;
        global $ADMIN_CONF;

        $this->syntax = $syntax;
        $this->adminConf = $ADMIN_CONF;

        if( is_object( $this->adminConf ) ){
            $this->adminLang = $this->adminConf->get('language');
            $this->adminLanguage = new Language(
                        self::BASE_DIR 
                        .self::DS
                        .self::LANG_DIR_NAME
                        .self::DS
                        .self::LANG_FILE_PREFIX_ADMIN
                        .$this->adminLang
                        .self::LANG_FILE_SUFFIX
                    );
        } 
    }

    /**
     * Schreibt $value ungeprueft in den head-Bereich des HTML Dokuments
     * 
     * Nutzt die Methoden 'syntax_html()' und 'insert_in_head()'
     * der mozilo-Klasse 'Syntax'
     * 
     * @param string $value Der dem Plugin uebergebene Value-String {head|value}
     */
    public function getContent($value) {
        if((bool) $value ){
            $value = $this->syntax->syntax_html( NULL, $value);
            $this->syntax->insert_in_head( $value );
        }   
    }

    /**
     * Keine Verwendung, muss aber vorhanden sein.
     */
    public function getConfig() {}

    /**
    * Gibt die Plugin-Infos als Array zurueck.
    *
    * Diese Funktion wird von mozilo verlangt.
    * Gibt die Plugin-Infos als Array zurueck. 
    * Die geforderten Infos werden aus den Objekt-Eigenschaften
    * und der Sprachdatei gewonnen.
    * Die Reihenfolge ist durch mozilo verbindlich:
    * [0] Name und Version des Plugins.
    * [1] Benoetigte moziloCMS-Version.
    * [2] Kurzbeschreibung.
    * [3] Name des Autors.
    * [4] Webseite.
    * [5] (optional) Platzhalter die im Seiten-Editor vorgeschlagen werden.
    *
    * @return array Die Reihenfolge der Array-Values muss stimmen.
    */
    public function getInfo() {
        return array(
            // [0]
            sprintf($this->style_plugin_name, self::NAME, self::VERSION)
            // [1]
            ,self::MOZILO_VERSION
            // [2]
            ,$this->adminLanguage->getLanguageValue('description')
                .$this->adminLanguage->getLanguageValue('example')
                .$this->code_example
                .sprintf(
                    $this->style_license
                    ,$this->adminLanguage->getLanguageValue('licence')
                    ,nl2br( file_get_contents( self::BASE_DIR.self::DS.'licence.txt' ))
                )
            // [3]
            ,self::AUTHOR
            // [4]
            ,array(
                self::DOKU_URL
                ,substr( self::DOKU_URL, strlen('http://') )
            )
        );
    }
}