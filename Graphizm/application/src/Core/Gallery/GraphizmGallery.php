<?php

/**
 * Interface Graphizm Gallery.
 *
 * @author Aurélien
 */
interface GraphizmGalleryInterface
{
    /**
     * Displays all the galleries, in the given directory.
     *
     * @return string code displaying the gallery.
     */
    function displayAllGalleries($withGen = FALSE);

    /**
     * Displays a specific gallery defined by its directory name.
     *
     * @param string $directory
     *   Directory name.
     * @param bool $with_gen
     *   TRUE if thumbnail generation.
     *
     * @return mixed
     *   HTML code.
     */
    function displayGallery($directory, $with_gen = FALSE);

    /**
     * Get thumbnail uri.
     *
     * @param string $name
     * Name of the thumbnail.
     *
     * @return string URI of the thumbnail to display.
     */
    function getThumbnail($name);

    /**
     * Gets gallery names within an array.
     *
     * @return array
     *   Indexed array with galleries names.
     */
    function getAllGalleriesNames();
}

/**
 * Graphizm Gallery Model. Processor class.
 *
 * @author Aurélien
 */
class GraphizmGalleryModel implements GraphizmGalleryInterface
{
    /**
     * String default path of the gallery.
     */
    protected $basePath;

    /**
     * String default path towards the gallery.
     */
    protected $path;

    /**
     * String Default template name of the thumbnail.
     */
    protected $template_name;

    /**
     * Int width of thumbnails.
     */
    protected $l;

    /**
     * Int height of thumbnails.
     */
    protected $h;

    /**
     * String current directory processed.
     */
    protected $name;

    /**
     * Default constructor. Do not instanciate directly, use factory !
     *
     * Use GraphizmGallery::instance()->create().
     *
     * @param array $conf
     *   Default config.
     */
    public function __construct($conf = array())
    {
        $this->basePath = GraphizmCore::instance()->gvar("galleries_basedir");
        $this->template_name = GraphizmCore::instance()->gvar("template_name_default");
        $this->setTemplate();
    }

    /**
     * Displays all the galleries, in the given directory.
     *
     * @return string
     *   Code displaying the gallery.
     */
    public function displayAllGalleries($withGen = FALSE)
    {
        $resultat = "";
        $all = $this->getAllGalleriesNames();
        $taille = sizeof($all);
        if ($taille > 0) {
            $resultat = '<div class="menu-gallery"><hr>';
            // Menu generation.
            for ($i = 0; $i < $taille; $i ++) {
                $base = htmlentities(str_replace(' ', '_', $all[$i]));
                $r = array(
                    "name_js" => $base,
                    "name_id" => $base . '_check',
                    "name_value" => $base,
                    "name_href" => $base . '_',
                    "title" => t("Voir cette galerie"),
                    "name_class" => $base,
                    "name_displayed" => htmlentities($all[$i])
                );
                $resultat .= GraphizmController::instance()->theme("gallery-menu", $r);
            }
            $resultat .= "<hr></div>";
            
            // Galleries generation.
            for ($i = 0; $i < $taille; $i ++) {
                $r = array(
                    "gallery_name" => t("Galerie") . " - " . htmlentities($all[$i]),
                    "name_id" => htmlentities(str_replace(' ', '_', $all[$i])) . "_",
                    "title" => t("Haut de page"),
                    "div_id" => htmlentities(str_replace(' ', '_', $all[$i])),
                    "a_gallery_code" => $this->displayGallery($all[$i], $withGen)
                );
                $resultat .= GraphizmController::instance()->theme("gallery-single", $r);
            }
        } else {
            $r = array(
                "no_gallery" => t("Aucune galerie n'est présente !")
            );
            $resultat = GraphizmController::instance()->theme("no-gallery", $r);
        }
        
        return $resultat;
    }

    /**
     * Displays a specific gallery defined by its directory name.
     *
     * @param string $directory
     *   Directory name.
     * @param bool $with_gen
     *   TRUE if thumbnail generation.
     *
     * @return mixed
     *   HTML code.
     */
    public function displayGallery($directory, $with_gen = FALSE)
    {
        // Data initialization.
        $this->init_item($directory, $with_gen);
        $code = '';
        $data = $this->getAllFiles();
        $int_tdata = sizeof($data);
        if (! empty($int_tdata)) {
            for ($i = 0; $i < $int_tdata; $i ++) {
                $a = substr($data[$i], 0, - 4);
                $r = array(
                    "a_href" => GraphizmCore::instance()->gvar("galleries_src") . $this->name . "/" . $data[$i],
                    "title" => $a,
                    "shadowbox_name" => $this->name,
                    "img_thumbnail" => $this->getThumbnail($data[$i]),
                    "alt_thumbnail" => $a
                );
                $code .= GraphizmController::instance()->theme("gallery-thumbnail", $r);
            }
        } else {
            $code = GraphizmController::instance()->theme("no-gallery", array(
                "no_gallery" => t("Aucune image pour le moment dans cette catégorie !"),
                "emphase" => FALSE
            ));
        }
        
        return $code;
    }

    /**
     * Get thumbnail uri.
     *
     * @param string $name
     *   Name of the thumbnail.
     *
     * @return string
     *   URI of the thumbnail to display.
     */
    public function getThumbnail($name)
    {
        $path = GraphizmCore::instance()->gvar("galleries_basedir") . $this->name . DS . "thumbnail" . DS . $this->template_name . DS . $name;
        $r = GraphizmCore::instance()->gvar("src_thumbnail_template") . $this->template_name . "/placeholder.gif";
        if (file_exists($path)) {
            $r = GraphizmCore::instance()->gvar("galleries_src") . $this->name . "/thumbnail/" . $this->template_name . "/" . $name;
        }

        return $r;
    }

    /**
     * Gets gallery names within an array.
     *
     * @return array
     *   Indexed array with galleries names.
     */
    public function getAllGalleriesNames()
    {
        try {
            $res = array();
            if (is_dir($this->basePath)) {
                $dir = opendir($this->basePath);
                $dirname = $this->basePath;
                while ($file = readdir($dir)) {
                    if ($file != '.' && $file != '..' && is_dir($dirname . $file)) {
                        $res[] = $file;
                    }
                }
                closedir($dir);
            }
        } catch (Exception $e) {
        }
        return $res;
    }

    /**
     * Initializes all the attributes.
     *
     * @param string $directory
     *   Name of the directory to look for pictures.
     * @param bool $withGen
     *   TRUE if thumbnails are to be generated.
     */
    protected function init_item($directory, $withGen = FALSE)
    {
        $base = $this->basePath;

        // Initialization of the path to look into.
        if (file_exists($base . $directory) && is_dir($base . $directory) && is_readable($base . $directory) && is_writable($base . $directory)) {
            $this->path = $base . $directory . DS;
        } else {
            $this->path = $base;
            $this->createDirectory($directory);
            $this->path .= $directory . DS;
        }
        $this->name = $directory;

        if ($withGen) {
            $this->createAllThumbnails();
        }
    }

    /**
     * Create all thumbnails in all the directories if necessary.
     */
    protected function createAllThumbnails()
    {
        $thumb = $this->path . 'thumbnail' . DS;
        if (! file_exists($thumb) || ! is_dir($thumb)) {
            $this->createDirectory('thumbnail');
        }
        if (is_readable($thumb) && is_writable($thumb)) {
            $thumb .= $this->template_name . DS;
            if (! file_exists($thumb) || ! is_dir($thumb)) {
                $this->createDirectory($this->template_name, 'thumbnail');
            }

            // Get all images.
            $res = $this->getAllFiles();
            $t_res = sizeof($res);

            // We create the thumbnail if it doesn't exist yet.
            for ($i = 0; $i < $t_res; $i ++) {
                $this->createSingleThumbnail($res[$i]);
            }
        }
    }

    /**
     * Create a directory in the given path.
     *
     * @param string $strName
     *   Name of the directory to create.
     * @param string $strPath
     *   Path.
     *
     * @return bool
     *   TRUE if the directory is created successfully.
     */
    protected function createDirectory($strName, $strPath = '')
    {
        // Create a directory with 755 permissions.
        $p = $this->path;
        if ($strPath != '') {
            $p .= $strPath . DS;
        }
        $p .= $strName;
        
        return mkdir($p, 0755);
    }

    /**
     * Lists jpeg & png files of current directory.
     *
     * @return array Picture names.
     */
    protected function getAllFiles()
    {
        $i = 0;
        $res = array();
        $dir = opendir($this->path);
        $dirname = $this->path;
        while ($file = readdir($dir)) {
            if ($file != '.' && $file != '..' && ! is_dir($dirname . $file)) {
                $ext = pathinfo($dirname . $file, PATHINFO_EXTENSION);
                if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'gif') {
                    $res[$i] = $file;
                    $i ++;
                }
            }
        }
        closedir($dir);

        return $res;
    }

    /**
     * Create a thumbnail for the given file.
     *
     * @param string $filename
     *   File name of the image.
     *
     * @return mixed
     *   None or FALSE if thumbnail creation has failed.
     */
    protected function createSingleThumbnail($filename)
    {
        $chemin_image = $this->path . $filename;

        if ($this->mustBeCreated($this->path . 'thumbnail' . DS . $this->template_name . DS . $filename)) {
            list ($src_w, $src_h) = getimagesize($chemin_image);
            $src_x = 0;
            $src_y = 0;
            $dst_x = 0;
            $dst_y = 0;
            $dst_w = $this->l;
            $dst_h = $this->h;

            $src_r = $src_w / $src_h;
            $dst_r = $dst_w / $dst_h;

            if ($src_r > $dst_r) {
                $r = $src_h / $dst_h;
                $src_x += round(($src_w - $this->l * $r) / 2);
            } else {
                $r = $src_w / $dst_w;
                $src_y += round(($src_h - $this->h * $r) / 2);
            }

            $dst_w = round($src_w / $r);
            $dst_h = round($src_h / $r);

            $array_ext = explode('.', $chemin_image);
            $extension = strtolower($array_ext[count($array_ext) - 1]);

            if ($extension == 'jpg' || $extension == 'jpeg') {
                $img_in = imagecreatefromjpeg($chemin_image);
            } else {
                if ($extension == 'png') {
                    $img_in = imagecreatefrompng($chemin_image);
                } else {
                    if ($extension == 'gif') {
                        $img_in = imagecreatefromgif($chemin_image);
                    } else {
                        return false;
                    }
                }
            }

            $img_out = imagecreatetruecolor($this->l, $this->h);
            $background_color = imagecolorallocate($img_out, 255, 255, 255);
            imagefill($img_out, 0, 0, $background_color);
            imagecopyresampled($img_out, $img_in, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, imagesx($img_in), imagesy($img_in));
            imagejpeg($img_out, $this->path . 'thumbnail' . DS . $this->template_name . DS . $filename);
        }
    }

    /**
     * Returns TRUE if thumbnail doesn't exists or if dimensions don't fit.
     *
     * @param string $filepath
     *   File path.
     *
     * @return bool
     *   True if has to be created.
     */
    protected function mustBeCreated($filepath)
    {
        if (file_exists($filepath)) {
            list ($src_w, $src_h) = getimagesize($filepath);
            return ($src_w != $this->l || $src_h != $this->h);
        } else {
            return TRUE;
        }
    }

    /**
     * Assign a template to the gallery.
     *
     * @param int $number
     *   Number of the template.
     */
    protected function setTemplate($number = NULL)
    {
        if (empty($number)) {
            $number = GraphizmCore::instance()->gvar("template_name_default");
        }
        $path = GraphizmCore::instance()->gvar("path_thumbnail_template") . $number . '/Gallery.Template.php';
        $this->template_name = $number;
        if (file_exists($path)) {
            require_once ($path);
            $this->l = $width;
            $this->h = $height;
            GraphizmCore::instance()->addFiles(array(
                "type" => "raw_css",
                "path" => $css,
                "weight" => 100
            ));
        } else {
            GraphizmCore::instance()->addFiles(array(
                "type" => "raw_css",
                "path" => '.dark{margin:5px;}',
                "weight" => 100
            ));
            $this->l = 100;
            $this->h = 100;
        }
    }
}

/**
 * From a path, list all the picture in the directory.
 *
 * Create a thumbnail for each picture, if necessary.
 * Important notice : this class can only be used with low number of pictures to display.
 * For huge volume of data, use another libray or use lazy loading.
 *
 * @TODO : create a command line to launch image generation via CLI.
 *
 * @author Aurélien
 *
 */
class GraphizmGallery extends ControllerDefiner
{
    protected static $js_and_css_loaded = FALSE;
    protected static $instance = NULL;
    protected $processorType = "GraphizmGalleryModel";
    protected $factoryList = array(
        "GraphizmGalleryModel",
    );

    /**
     * Default constructor.
     *
     * @param array $conf
     *   Default config.
     */
    static public function instance($conf = array()) {
        if (empty(self::$instance)) {
            self::$instance = new GraphizmGallery();
        }
        return self::$instance;
    }

    /**
     * Creates a graphizm gallery model instance.
     *
     * @param string $type
     *   Class instanciated.s
     * @param array $conf
     *   Config.
     *
     * @return GraphizmGalleryInterface
     *   Instance.
     */
    public function create($type = NULL, $conf = array()) {
        if (empty($type) || !in_array($type, $this->factoryList)) {
            $type = $this->processorType;
        }

        return new $type($conf);
    }

    /**
     * Default constructor.
     *
     * @param array $conf
     *   Config.
     */
    protected function __construct($conf = array())
    {
        $this->defineTemplates();
        $this->addToRegister();
        $this->loadJSCSS();
    }

    /**
     * Defined templates.
     */
    public function defineTemplates()
    {
        $this->templates = array(
            "gallery-single" => "src" . DS . "Core" . DS . "Gallery" . DS . "resources" . DS . "views" . DS . "Gallery-single.tpl.php",
            "gallery-menu" => "src" . DS . "Core" . DS . "Gallery" . DS . "resources" . DS . "views" . DS . "Gallery-menu.tpl.php",
            "gallery-thumbnail" => "src" . DS . "Core" . DS . "Gallery" . DS . "resources" . DS . "views" . DS . "Gallery-thumbnail.tpl.php",
            "no-gallery" => "src" . DS . "Core" . DS . "Gallery" . DS . "resources" . DS . "views" . DS . "No-Gallery.tpl.php",
        );
    }

    /**
     * Load JS and CSS files.
     */
    protected function loadJSCSS()
    {
        if (! GraphizmGallery::$js_and_css_loaded) {
            GraphizmCore::instance()->addFiles($this->generateFilesToLoad());
            GraphizmGallery::$js_and_css_loaded = TRUE;
        }
    }

    /**
     * Generates files to load.
     */
    protected function generateFilesToLoad()
    {
        return array(
            array(
                "type" => "js",
                "path" => "src/Core/Gallery/resources/js/documentready.js",
                "weight" => "500",
            ),
            array(
                "type" => "css",
                "path" => "src/Core/Gallery/resources/css/style.css",
                "weight" => "500",
            ),
            array(
                "type" => "js",
                "path" => "src/Core/Gallery/vendors/shadowbox/shadowbox.js",
                "weight" => "501",
            ),
            array(
                "type" => "css",
                "path" => "src/Core/Gallery/vendors/shadowbox/shadowbox.css",
                "weight" => "501",
            )
        );
    }
}
