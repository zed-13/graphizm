<?php

/**
 * Main class to handle contact form.
 * 
 * @TODO : implement the class + Use Google reCaptcha
 *
 * @author Aurélien
 *
 */
class GraphizmContact extends TemplateDefiner
{
    /**
     * @var GraphizmContact instance.
     */
    protected static $instance;

    /**
     * Default constructor.
     *
     * @param array $conf
     */
    protected function __construct($conf = array())
    {
        $this->defineTemplates();
        $this->addToRegister();
    }

    /**
     * Gets current instance.
     *
     * @param string $environment
     */
    public static function instance($conf = array())
    {
        if (empty(GraphizmCore::$instance)) {
            GraphizmContact::$instance = new GraphizmContact($conf);
        }

        return GraphizmContact::$instance;
    }

    /**
     * Defined templates.
     */
    public function defineTemplates()
    {
        $this->templates = array(
            "contact-form" => "src" . DS . "Core" . DS ."Contact" . DS . "resources" . DS ."views" . DS . "form.tpl.php",
        );
    }
}
