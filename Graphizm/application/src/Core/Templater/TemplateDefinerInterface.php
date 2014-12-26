<?php

/**
 * That class defines new templates.
 *
 * @author Aurélien
 */
interface TemplateDefinerInterface {

    /**
     * Add to register all the templates defined.
     */
    function addToRegister();

    /**
     * Defines all the new templates within that method.
     */
    function defineTemplates();
}
