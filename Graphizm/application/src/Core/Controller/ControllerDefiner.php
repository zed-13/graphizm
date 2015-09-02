<?php

require_once 'ControllerDefinerInterface.php';

/**
 * Classes have to implements that class to define new templates.
 *
 * @author Aurélien
 */
abstract class ControllerDefiner implements ControllerDefinerInterface
{
    protected $templates = array();
    protected $factoryList = array();
    protected $processorType = "";

    /**
     * (non-PHPdoc)
     * @see ControllerDefinerInterface::addToRegister()
     */
    public function addToRegister()
    {
        foreach ($this->templates as $k => $a_template) {
            GraphizmController::instance()->addTemplate(array($k => $a_template));
        }
    }

    /**
     * (non-PHPdoc)
     * @see ControllerDefinerInterface::defineTemplates()
     */
    public abstract function defineTemplates();

    /**
     * Get processor.
     *
     * @param array $conf
     *   Config.
     * @param string $type
     *   Type of processor. Name of class implementing SpecificInterface of child class.
     *
     * @return mixed
     *   GraphizmSpecificInterface Processor.
     */
    public function getProcessor($conf = array(), $type = NULL) {
        if (empty($this->model)) {
            if (empty($type)) {
                $type = $this->processorType;
            }
            $this->setProcessor($type, $conf);
        }

        return $this->model;
    }

    /**
     * Sets a processor.
     *
     * @param string $type
     *   Type of processor. Name of class implementing specific interface.
     * @param array $conf
     *   Config.
     */
    public function setProcessor($type = NULL, $conf = array()) {
        if (in_array($type, $this->factoryList)) {
            try {
                $this->model = new $type();
            } catch (\Exception $e) {
                // @todo : error handling.
            }
        }
    }
}
