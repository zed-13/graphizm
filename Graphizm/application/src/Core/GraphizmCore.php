<?php

/**
 * Core class to get all conf vars.
 * 
 * @author Aurélien
 */
final class GraphizmCore
{

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
     * Constructor deactivated so that only one instance is running.
     *
     * @param string $environment
     */
    protected function __construct($environment = "prod")
    {
        if (!in_array($environment, GraphizmCore::$environments)) {
            $environment = GraphizmCore::$fallbackEnvironment;
        }

        try {
            require_once 'conf/' . $environment . '/conf.php';
            GraphizmCore::$conf = $conf;
        } catch (\Exception $e) {
            // @TODO [Core] : Send an error 500.
        }
    }

    /**
     * Gets current instance
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
}
