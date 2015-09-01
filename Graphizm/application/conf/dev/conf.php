<?php
define("DS", DIRECTORY_SEPARATOR);
$conf = array(
    "title" => "GraphiZm | Arts graphiques by Zed",
    "sitename" => "GraphiZm",
    "menu" => "<span class='glyphicon glyphicon-home'> </span>",
    "subtitle" => "Arts graphiques by Zed",
    "picture" => "resources/images/mad1.png",
    "galleries_src" => "resources/images/galleries/",
    "path" => dirname(dirname(__DIR__)),
    "galleries_basedir" => dirname(dirname(__DIR__)) . DS . "resources" . DS . "images" . DS . "galleries" . DS,
    "path_thumbnail_template" => "src" . DS . "Core" . DS . "Gallery" . DS . "resources" . DS . "template" . DS,
    "src_thumbnail_template" => "src/Core/Gallery/resources/template/",
    "template_name_default" => "square",
    "introtext" => "GraphiZm, site personnel où j'expose mes créations et les oeuvres qui m'intéressent. Styles différents, souvent décalés, et toujours un même objectif: laisser libre cours à l'imagination. ",
    "contact-form" => array(
        "to" => "random@random.com",
        "intro" => "Un commentaire ? Un bug ? Laissez-moi votre message !",
    ),
    "menu_content" => array(
        array(
            "text" => "Mes dessins",
            "icon" => "glyphicon glyphicon-picture",
            "link" => "#"
        ),
        array(
            "text" => "Galeries",
            "icon" => "glyphicon glyphicon-th",
            "link" => "#"
        ),
        array(
            "text" => "Me contacter",
            "icon" => "glyphicon glyphicon-envelope",
            "link" => "#",
            "attributes" => array(
                "id" => "contact-button-modal",
            ),
        )
    ),
    "footer" => array(
        "zone_1" => array(
            "title" => "A propos",
            "picture" => "resources/images/footer-logo.png",
            "content" => "Copyright 2011-2015 GraphiZm",
        ),
        "zone_2" => array(
            "title" => "Technologies utilisés",
            "picture" => "resources/images/footer-logo2.png",
            "content" => "Bootstrap 3, jQuery, HTML5 shim, Respond.js",
        ),
        "zone_3" => array(
            "title" => "Dernier post",
            "picture" => "",
            "content" => "Lorem ipsum blablablabla",
        ),
    ),
    "gallery" => array(
        "conf" => array(
            "base_template_list" => "round",
            "base_template_detail" => "natural",
        ),
        "content" => array(
            "main" => array(
                array(
                    "title" => "Mes dessins",
                    "base_directory" => "resources/images/galleries/main",
                    "base_template_list" => "round",
                    "base_template_detail" => "natural",
                )
            ),
            "secondary" => array(
                array(
                    "title" => "Mes dessins",
                    "base_directory" => "resources/images/galleries/main",
                    "base_template_list" => "round",
                    "base_template_detail" => "natural",
                ),
            ),
        ),
    ),
);