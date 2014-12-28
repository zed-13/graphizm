<?php

/**
 * Core class to get all conf vars.
 *
 * Singleton implementation.
 *
 * @author Aurélien
 */
final class GraphizmCore
{
    /**
     * @var GraphizmCore instance called.
     */
    private static $instance = NULL;

    /**
     * @var array available environments.
     */
    private static $environments = array(
        "dev",
        "prod"
    );

    /**
     * @var string fallback environement if none is passed.
     */
    private static $fallbackEnvironment = "dev";

    /**
     * @var array Config
     */
    private static $conf = array();

    /**
     * Files to include.
     * @var array Each cell is a full html code to include the file.
     */
    private $files = array("css" => array(), "js" => array());

    /**
     * @var GraphizmContact
     */
    private $contacter = NULL;

    /**
     * Constructor deactivated so that only one instance is running.
     *
     * @param string $environment
     */
    private function __construct($environment = "prod")
    {
        if (!in_array($environment, GraphizmCore::$environments)) {
            $environment = GraphizmCore::$fallbackEnvironment;
        }

        try {
            require_once 'conf/' . $environment . '/conf.php';
            GraphizmCore::$conf = $conf;
            $this->coreInitialization();
            // @TODO : config acquiral via conf.
            $this->setContacter($conf);
        } catch (\Exception $e) {
            // @TODO [Core] : Send an error 500.
        }
    }

    /**
     * Gets current instance.
     *
     * @param string $environment
     */
    public static function instance($environment = "prod")
    {
        if (empty(GraphizmCore::$instance)) {
            GraphizmCore::$instance = new GraphizmCore($environment);
        }

        return GraphizmCore::$instance;
    }

    /**
     * Main method to be called.
     *
     * @param string $action
     *   Action to do - main controller function.
     */
    public function launch($action = "gallery") {
        // @TODO.
        switch ($action) {
            case "gallery":
                $a = new GraphizmGallery();
                echo $a->displayAllGalleries(TRUE);
            break;

            case "contact":
            break;

            default:
            break;
        }
    }

    /**
     * Gets a variable.
     *
     * @param string $key
     *
     * @return mixed
     *   value of the variable or NULL.
     */
    public function gvar($key)
    {
        $r = NULL;
        if (isset(GraphizmCore::$conf[$key])) {
            $r = GraphizmCore::$conf[$key];
        }

        return $r;
    }
    
    /**
     * Adds js & css files.
     * @TODO: weight.
     * @param array $files
     *   array of arrays(
     *     'type'=> "css" | "js" | "raw_js" | "raw_css,
     *     'path' => path to the file or content if raw_*,
     *     'weight' => order of file inclusion, (min to max)
     *   );
     */
    public function addFiles($files)
    {
        foreach ($files as $a_file) {
            if (isset($a_file["type"])) {
                switch ($a_file["type"]) {
                    case "raw_css":
                        array_push($this->files["css"], '<style>' . $a_file["path"] . '</style>');
                    break;

                    case "raw_js":
                        array_push($this->files["js"], '<script>' . $a_file["path"] . '</script>');
                    break;

                    case "css":
                        array_push($this->files['css'], '<link rel="stylesheet" href="' . $a_file['path'] . '">');
                    break;

                    case "js":
                        array_push($this->files["js"], '<script src="' . $a_file["path"] . '"></script>');
                    break;
                }
            }
        }
    }

    /**
     * Get CSS files. Raw echo on each of them is ok.
     */
    public function getCSS()
    {
        return $this->files['css'];
    }

    /**
     * Get JS files. Raw echo on each of them is ok.
     */
    public function getJS()
    {
        return $this->files['js'];
    }

    /**
     * Includes all the necessary files.
     */
    private function coreInitialization()
    {
        require_once 'Translator/Translator.php';
        require_once 'Templater/GraphizmTemplater.php';
        require_once 'Gallery/GraphizmGallery.php';
        require_once 'Contact/GraphizmContact.php';
    }

    /**
     * Sets contacter.
     */
    private function setContacter($conf = array())
    {
        $this->contacter = GraphizmContact::instance($conf);
    }

    /**
     * Function used to route the application.
     */
    private function router() {
        // @TODO.
    }
}
