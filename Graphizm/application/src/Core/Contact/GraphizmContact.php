<?php

/**
 * Main class to handle contact form.
 *
 * @author Aurélien
 *
 */
class GraphizmContact extends ControllerDefiner
{
    protected static $instance;
    protected $model;
    protected $processorType = "GraphizmContactModel";
    protected $factoryList = array(
        "GraphizmContactModel",
    );

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

/**
 * Interface Graphizm Contact Form.
 *
 * @author Aurélien
 *
 * @todo : externalize.
 */
interface GraphizmContactInterface {

    /**
     * Sends a mail to target, with message.
     *
     * @param string $email
     *   Email (target).
     * @param string $message
     *   Message.
     * @param string $trust
     *   Anti-bot check. If not empty: bot detected.
     *
     * @return string
     *   Json encoded array(
     *      "state" => TRUE|FALSE,
     *      "messages" => array(),
     *   );
     */
    public function send($email, $message, $trust = NULL);
}

/**
 * Class Graphizm Contact Form processor.
 *
 * @author Aurélien
 *
 * @todo : externalize.
 */
class GraphizmContactModel implements GraphizmContactInterface {

    /**
     * Sends a mail to target, with message.
     *
     * @param string $email
     *   Email (target).
     * @param string $message
     *   Message.
     * @param string $trust
     *   Anti-bot check. If not empty: bot detected.
     *
     * @return string
     *   Json encoded array(
     *      "state" => TRUE|FALSE,
     *      "messages" => array(),
     *   );
     */
    public function send($email, $message, $trust = NULL) {
        $r = array(
                "state" => TRUE,
                "messages" => array(),
        );
        if ($this->canSendEmail()) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $r["messages"][] = t("Veuillez renseigner votre e-mail.");
                $r["state"] = FALSE;
            }
            if (empty($message)) {
                $r["messages"][] = t("Renseignez votre message.");
                $r["state"] = FALSE;
            }
            if (!empty($trust)) {
                $r["messages"][] = t("Il semble que vous êtes un bot.");
                $r["state"] = FALSE;
            }
            if ($r["state"]) {
                try {
                    $message = htmlspecialchars($message);
                    $from = 'From: ' . htmlspecialchars($email) . "\r\n";
                    if (!mail(GraphizmCore::instance()->gvar("contact-form")["to"], t("Contact Graphizm"), $message, $from)) {
                        $r["state"] = FALSE;
                        $r["messages"][] = t("Impossible d'envoyer l'e-mail. Réessayez plus tard.");
                    } else {
                        $_SESSION["email"] = time();
                        $r["messages"][] = t("Votre message a été envoyé, j'y répondrai dans les plus brefs délais.");
                    }
                } catch (\Exception $e) {
                    $r["state"] = FALSE;
                    $r["messages"][] = t("Une erreur inconnue est survenue lors de l'envoi de l'e-mail.");
                }
            }
        } else {
            $r["state"] = FALSE;
            $r["messages"][] = t("Vous ne pouvez envoyer d'email que toutes les 10 minutes. Réessayez plus tard.");
        }
        return json_encode($r);
    }

    /**
     * Says if can send email.
     *
     * Upgrade state of sending or not emails.
     *
     * @return bool
     *   TRUE if can send e-mail, FALSE otherwise.
     */
    protected function canSendEmail() {
        $r = TRUE;
        if (isset($_SESSION["email"])) {
            $send_time_out = $_SESSION["email"] + 600;
            if (time() >= $send_time_out) {
                unset($_SESSION["email"]);
            } else {
                $r = FALSE;
            }
        }
        return $r;
    }
}
