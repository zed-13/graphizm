<?php
require_once 'TemplateDefiner.php';

/**
 * This file contains all functions and design to display a template.
 * Oop oop oop oopa Drupal Style !
 * 
 * @author Aurélien
 */
final class GraphizmTemplater extends TemplateDefiner
{
    /**
     * @var GraphizmHelper Instance
     */
    private $instance = NULL;

    /**
     * @var bool Set the define template only if at FALSE.
     */
    private $initDone = FALSE;

    /**
     * Default constructor.
     */
    private function __construct()
    {
    }

    /**
     * Gets current instance.
     *
     * @param string $environment
     */
    public static function instance()
    {
        if (empty(GraphizmTemplater::$instance)) {
            GraphizmTemplater::$instance = new GraphizmTemplater();
        }

        return GraphizmTemplater::$instance;
    }

    /**
     * Main function to use in 
     *
     * @param string $name
     *   Key to get the path of the template to use. (attribute templates)
     * @param array $parameters
     *   Key based array. Eah key gets extracted so it can be used in template.
     *
     * @return string
     */
    public function theme($name, $parameters = array()) {
        extract($parameters);
        $r = '';
        if (in_array($name, array_keys($this->templates))) {
            $path = GraphizmCore::instance()->gvar("path") . DIRECTORY_SEPARATOR . $this->templates[$name];
            if (file_exists($path)) {
                try {
                    ob_start();
                    include($path);
                    $r = ob_get_clean();
                }
                catch(\Exception $e) {
                }
            }
        }
        return $r;
    }

    /**
     * Adds a template to its template attribute.
     *
     * @param array $aTemplate
     *   Key/value
     *
     * @return bool
     *   TRUE if $this->templates is updated, FALSE otherwise.
     */
    public function addTemplate ($aTemplate)
    {
        $r = FALSE;
        if (is_array($aTemplate) && !empty($aTemplate)) {
            $k = array_keys($aTemplate);
            if (isset($k[0])) {
                $k = $k[0];
                $this->templates[$k] = $aTemplate[$k];
                $r = TRUE;
            }
        }
        return $r;
    }

    /**
     * Templates defined.
     */
    public function defineTemplates()
    {
        if (!$this->initDone) {
            // @TODO : define core templates.
            $this->templates = array(
                "" => "",
                "" => "",
            );
            $this->initDone = TRUE;
        }
    }
}
