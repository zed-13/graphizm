<?php

/**
 * That class defines new controllers.
 *
 * @author Aurélien
 */
interface ControllerDefinerInterface {

    /**
     * Add to register all the templates defined.
     */
    function addToRegister();

    /**
     * Defines all the new templates within that method.
     */
    function defineTemplates();

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
    public function getProcessor($conf = array(), $type = NULL);

    /**
     * Sets a processor.
     *
     * @param string $type
     *   Type of processor. Name of class implementing GraphizmContactInterface.
     * @param array $conf
     *   Config.
     */
    public function setProcessor($type = "GraphizmContactModel", $conf = array());
}
