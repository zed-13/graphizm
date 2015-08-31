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

    /**
     * Sends a mail to target, with message.
     *
     * @param string $email
     *   Email (target).
     * @param string $message
     *   Message.
     *
     * @return string
     *   Json encoded array(
     *      "state" => TRUE|FALSE,
     *      "messages" => array(),
     *   );
     */
    public function send($email, $message) {
        $r = array(
            "state" => TRUE,
            "messages" => array(),
        );
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $r["messages"][] = t("Veuillez renseigner votre e-mail.");
            $r["state"] = FALSE;
        }
        if (empty($message)) {
            $r["messages"][] = t("Renseignez votre message.");
            $r["state"] = FALSE;
        }
        if ($r["state"]) {
            try {
                $message = htmlspecialchars($message);
                $from = 'From: ' . htmlspecialchars($email) . "\r\n";
                if (mail(GraphizmCore::instance()->gvar("contact-form")["to"], t("Contact Graphizm"), $message, $from)) {
                    $r["state"] = FALSE;
                    $r["messages"][] = t("Impossible d'envoyer le-mail. Réessayez plus tard.");
                } else {
                    $r["messages"][] = t("Votre message a été envoyé, j'y répondrai dans les plus brefs délais.");
                }
            } catch (\Exception $e) {
                $r["state"] = FALSE;
                $r["messages"][] = t("Une erreur inconnue est survenue lors de l'envoi de l'e-mail.");
            }
        }

        return json_encode($r);
    }
}
