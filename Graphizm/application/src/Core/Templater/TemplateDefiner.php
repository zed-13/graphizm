<?php

require_once 'TemplateDefinerInterface.php';

/**
 * Classes have to implements that class to define new templates.
 *
 * @author Aurélien
 */
abstract class TemplateDefiner implements TemplateDefinerInterface
{
    protected $templates = array();

    /**
     * (non-PHPdoc)
     * @see TemplateDefinerInterface::addToRegister()
     */
    public function addToRegister()
    {
        foreach ($this->templates as $a_template) {
            GraphizmTemplater::instance()->addTemplate($a_template);
        }
    }

    /**
     * (non-PHPdoc)
     * @see TemplateDefinerInterface::defineTemplates()
     */
    public abstract function defineTemplates();
}
