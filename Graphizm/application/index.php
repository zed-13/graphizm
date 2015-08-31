<?php
    require_once 'src/Core/GraphizmCore.php';
    $core = GraphizmCore::instance();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <title><?php echo $core->gvar('title');?></title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="resources/css/style.css" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <header>
        <p class="subtitle fancy fancy2">
            <span>
                <img src="resources/images/header-logo.png" alt="logo-mini"/>
            </span>
        </p>
        <h1>
            <?php echo $core->gvar('sitename');?>
        </h1>
        <p class="subtitle fancy">
            <span>
                <?php echo $core->gvar('subtitle');?>
            </span>
        </p>
    </header>

    <div class="container">
        <div class="col-sm-12 col-md-12 main">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <p class="intro"><?php echo $core->gvar("introtext"); ?></p>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                    <img src="<?php echo $core->gvar("picture"); ?>" alt="logo-mini"/>
                </div>
            </div>

            <!-- Navigation menu -->
              <div class="navbar navbar-inverse " role="navigation" id="main-bar">
                  <div class="container">
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                      <a class="navbar-brand" href="#"><?php echo $core->gvar("menu");?></a>
                    </div>
                    <div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-left">
                        <?php foreach($core->gvar("menu_content") as $menu_element) :?>
                        <li>
                            <a href="<?php echo (isset($menu_element["link"]))?$menu_element["link"]:"#"; ?>"
                            <?php if (isset($menu_element["attributes"])):
                                foreach($menu_element["attributes"] as $k => $v):
                                    echo " ", $k, "='", $v, "'";
                                endforeach;
                            ?>
                            <?php endif;?>
                            >
                                <?php if (isset($menu_element["icon"])):?>
                                    <span class="<?php print $menu_element["icon"]; ?>"></span>
                                <?php endif;?>
                                <?php 
                                if (isset($menu_element["text"])):
                                    echo " " . $menu_element["text"];
                                endif;
                                ?>
                           </a>
                        </li>
                        <?php endforeach;?>
                        </ul>
                    </div>
                  </div>
              </div>
              <!-- Content zone -->
              <div id="content-zone">
                <?php
                    $core->launch();
                ?>
              </div>
        </div>
    </div>

    <div class="footer" role="navigation">
        <div class="container">
            <?php
                $f = $core->gvar("footer");
                if (isset($f)):
                    foreach($f as $zone):
                ?>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <h3 class="footer-zone">
                    <?php print $zone["title"];?>
                </h3>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <img alt="footer-img" src="<?php print $zone["picture"];?>" class="pull-left footer-div" />
                        <p class="pull-left footer-div"><?php print $zone["content"];?></p>
                    </div>
                </div>
                
            </div>
            <?php endforeach; ?>
           <?php endif; ?>
        </div>
    </div>

    <?php
      $f = $core->getCSS();
      foreach($f as $c):
        echo $c;
      endforeach;
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="vendors/js/spin.min.js"></script>
    <script src="resources/js/documentready.js" ></script>
    <?php
      $f = $core->getJS();
      foreach($f as $c):
        echo $c;
      endforeach;
    ?>
    <?php
        // Modal contact form.
        echo GraphizmTemplater::instance()->theme("contact-form", array("intro" => $core->gvar("contact-form")["intro"]));
    ?>
  </body>
</html>
