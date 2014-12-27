<?php

/**
 * From a path, list all the picture in the directory.
 * 
 * Create a thumbnail for each picture, if necessary.
 * Important notice : this class can only be used with low number of pictures to display.
 * For huge volume of data, use another libray or use lazy loading.
 * 
 * @author Aurélien
 *
 */
class GraphizmGallery extends TemplateDefiner
{
    /**
     * @var bool says if we have to load js and css files.
     */
    static protected $js_and_css_loaded = FALSE;

    /**
     * @var string default path of the gallery.
     */
    protected $basePath;

    /**
     * @var string default path towards the gallery.
     */
    protected $path;

    /**
     * @var string Default template name of the thumbnail.
     */
    protected $template_name;

    /**
     * @var int width of thumbnails.
     */
    protected $l;

    /**
     * @var int height of thumbnails.
     */
    protected $h;

    /**
     * @var string current directory processed.
     */
    protected $name;

    /**
     * Default constructor.
     *
     * @param array $conf
     */
    public function __construct($conf = array())
    {
        $this->basePath = GraphizmCore::instance()->gvar("galleries_basedir");
        $this->template_name = GraphizmCore::instance()->gvar("template_name_default");
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
            "gallery-single" => "src/Core/Gallery/resources/views/Gallery-single.tpl.php",
            "gallery-menu" =>  "src/Core/Gallery/resources/views/Gallery-menu.tpl.php",
            "gallery-thumbnail" => "src/Core/Gallery/resources/views/Gallery-thumbnail.tpl.php",
            "no-gallery" => "src/Core/Gallery/resources/views/No-Gallery.tpl.php",
        );
    }

    /**
     * Displays all the galleries, in the given directory.
     * 
     * @return string
     *   code displaying the gallery.
     */
    public function displayAllGalleries($withGen = FALSE)
    {
        $resultat = "";
        $all = $this->getAllGalleriesNames();
        $taille = sizeof($all);
        if ($taille > 0) {
            $resultat = '|';
            // Menu generation.
            for ($i = 0; $i < $taille; $i ++) {
                $base = htmlentities(str_replace(' ', '_', $all[$i]));
                $r = array(
                    "name_js" => $base,
                    "name_id" => $base . '_check',
                    "name_value" => $base,
                    "name_href" => $base .'_',
                    "title" => t("Voir cette galerie"),
                    "name_class" => $base,
                    "name" => htmlentities($all[$i]),
                );
                $resultat .= GraphizmTemplater::instance()->theme('gallery-menu', $r);
            }

            // Galleries generation.
            for ($i = 0; $i < $taille; $i ++) {
                $r = array(
                    "gallery_name" => t("GALERIE") ." - " . htmlentities($all[$i]),
                    "name_id" => htmlentities(str_replace(' ', '_', $all[$i])) . "_",
                    "title" => t("Haut de page"),
                    "div_id" => htmlentities(str_replace(' ', '_', $all[$i])),
                    "a_gallery_code" => $this->displayGallery($all[$i], $withGen),
                );
                $resultat .= GraphizmTemplater::instance()->theme("gallery-single", $r);
            }
        }
        else {
            $r = array(
                "no_gallery" => t("Aucune galerie n'est présente !"),
            );
            $resultat = GraphizmTemplater::instance()->theme("no-gallery", $r);
        }

        return $resultat;
    }

    /**
     * Affiche la galerie dont le nom du répertoire a été passé au constructeur
     *
     * @param string $directory
     *            le nom du répertoire contenant les images
     * @param bool $with_gen
     *            si on doit générer les miniatures
     * @param int $retour
     *            0 pour un affichage direct, 1 pour un retour string
     * @return mixed
     *
     */
    public function displayGallery($directory, $with_gen = TRUE)
    {
        // Data initialization.
        $this->init_item($directory, $with_gen);
        $code = '';
        $data = $this->getAllFiles();
        $int_tdata = sizeof($data);
        if (!empty($int_tdata)) {
            for ($i = 0; $i < $int_tdata; $i ++) {
                // @Remark : template done. 
                $a = substr($data[$i], 0, - 4);
                $code .= "
					<a href=\"" . _Site_ . "/Galleries/" . $this->name . "/" . $data[$i] . "\" title=\"" . $a . "\" rel=\"shadowbox[" . $this->name . "]\">
					<img src=\"" . _Site_ . "/Galleries/" . $this->name . "/thumbnail/" . $this->template_name . "/" . $data[$i] . "\" alt=\"" . $a . "\" />
					</a>
					";
            }

            return $code;
        }
    }

    /**
     * Récupère tous les noms de galeries dans un tableau de string
     * 
     * @return Array
     */
    public function getAllGalleriesNames()
    {
        $res = array();
        $dir = opendir($this->basePath);
        $dirname = $this->basePath;
        while ($file = readdir($dir)) {
            if ($file != '.' && $file != '..' && is_dir($dirname . $file)) {
                $res[] = $file;
            }
        }
        closedir($dir);
        return $res;
    }

    /**
     * Initializes all the attributes.
     *
     * @param string $directory
     *   name of the directory to look for pictures.
     * @param bool $withGen
     *   TRUE if thumbnails are to be generated.
     */
    protected function init_item($directory, $withGen = TRUE)
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
     *
     * @return none
     */
    protected function createAllThumbnails()
    {
        $thumb = $this->path . 'thumbnail' . DS;

        if (!file_exists($thumb) || !is_dir($thumb)) {
            $this->createDirectory('thumbnail');
        }
        
        if (is_readable($thumb) && is_writable($thumb)) {
            
            $thumb .= $this->template_name . DS;
            if (!file_exists($thumb) || !is_dir($thumb)) {
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
     * Permet de lister les fichiers png et jpg du répertoire passé en paramètre
     *
     * @return array le nom des fichiers images
     *        
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
     *   none or FALSE if thumbnail creation has failed.
     */
    protected function createSingleThumbnail($filename)
    {
        $fc = 2;
        $chemin_image = $this->path . $filename;

        if ($this->must_be_created($this->path . 'thumbnail' . DS . $this->template_name . DS . $filename)) {
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
            }
            else {
                if ($extension == 'png') {
                    $img_in = imagecreatefrompng($chemin_image);
                }
                else { 
                    if ($extension == 'gif') {
                        $img_in = imagecreatefromgif($chemin_image);
                    }
                    else {
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
     * Retourne vrai si la miniature n'existe pas ou si elle a les mauvaises dimensions
     *
     * @param string $filepath
     *            le chemin du fichier
     * @return bool si mauvaise taille ou pas
     *        
     */
    protected function must_be_created($filepath)
    {
        if (file_exists($filepath)) {
            list ($src_w, $src_h) = getimagesize($filepath);
            return ($src_w != $this->l || $src_h != $this->h);
        } else {
            return TRUE;
        }
    }

    /**
     * Permet d'assigner un template à la galerie
     *
     * @param int $number
     *            le numéro (entier) du template
     * @return none
     */
    protected function set_template($number)
    {
        $path = GraphizmCore::instance()->gvar("path_thumbnail_template") . "/" . $number . '/Gallery.Template.php';
        $this->template_name = $number;
        if (file_exists($path)) {
            require_once ($path);
            $this->l = $l;
            $this->h = $w;
            $this->includes['style_declaration'] .= $css;
        } else {
            $this->includes['style_declaration'] .= '.dark{margin:5px;}';
            $this->l = 100;
            $this->h = 100;
        }
    }

    /**
     * Load JS and CSS files.
     */
    protected function loadJSCSS() {
        if (!GraphizmGallery::$js_and_css_loaded) {
            GraphizmCore::instance()->addFiles($this->generateFilesToLoad());
            GraphizmGallery::$js_and_css_loaded = TRUE;
        }
    }

    /**
     * Generates files to load.
     */
    protected function generateFilesToLoad() {
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
        );
    }
}

class AGallery extends GraphizmGallery
{
    protected $name;

    /**
     * Default constructor.
     *
     * @param string $template
     *            le template à utiliser
     * @return Gallery.Object
     */
    public function __construct($template = 1, $name)
    {
        $this->set_template($template);
        $this->name = $name;
    }

    /**
     * Main function.
     * 
     * @return string
     */
    public function launch()
    {
        $all = $this->getAllGalleriesNames();
        return $this->displayGallery($this->name) . '<div style="clear:both;"></div>';
    }
}
