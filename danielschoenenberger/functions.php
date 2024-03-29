<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($composer_autoload)) {
    require_once $composer_autoload;
    $timber = new Timber\Timber();
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if (! class_exists('Timber')) {
    add_action(
        'admin_notices',
        function () {
            echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
        }
    );

    add_filter(
        'template_include',
        function ($template) {
            return get_stylesheet_directory() . '/static/no-timber.html';
        }
    );
    return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * Enqueue scripts and styles.
 */
function danielschoenenberger_theme_scripts()
{
    switch (wp_get_environment_type()) {
        case 'local':
        case 'development':
            // load assets (dev)
                wp_enqueue_script('danielschoenenberger_theme-scripts-dev', 'http://'. getenv('VIRTUAL_HOST'). ':8080/site.js', array(), null, true);
                //wp_enqueue_script('danielschoenenberger_theme-admin-scripts-dev', 'http://localhost:8080/admin.js');
          break;
        case 'staging':
            // load assets (staging)
            wp_enqueue_style('danielschoenenberger_theme-style', get_stylesheet_directory_uri() . '/dist/site.css');
            wp_enqueue_script('danielschoenenberger_theme-scripts', get_stylesheet_directory_uri() . '/dist/site.js', array(), null, true);
            //wp_enqueue_script('danielschoenenberger_theme-admin-scripts', get_stylesheet_directory_uri() . '/dist/admin.js');
          break;
        case 'production':
        default:
            // load assets (prod)
                wp_enqueue_style('danielschoenenberger_theme-style', get_stylesheet_directory_uri() . '/dist/site.css');
                wp_enqueue_script('danielschoenenberger_theme-scripts', get_stylesheet_directory_uri() . '/dist/site.js', array(), null, true);
                //wp_enqueue_script('danielschoenenberger_theme-admin-scripts', get_stylesheet_directory_uri() . '/dist/admin.js');
          break;
      }
}
add_action('wp_enqueue_scripts', 'danielschoenenberger_theme_scripts', 9999);


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site
{
    /** Add timber support. */
    public function __construct()
    {
        add_action('after_setup_theme', array( $this, 'theme_supports' ));
        add_filter('timber/context', array( $this, 'add_to_context' ));
        add_filter('timber/twig', array( $this, 'add_to_twig' ));
        add_action('init', array( $this, 'register_post_types' ));
        add_action('init', array( $this, 'register_taxonomies' ));
        parent::__construct();
    }
    /** This is where you can register custom post types. */
    public function register_post_types()
    {
    }
    /** This is where you can register custom taxonomies. */
    public function register_taxonomies()
    {
    }

    /** This is where you add some context
     *
     * @param string $context context['this'] Being the Twig's {{ this }}.
     */
    public function add_to_context($context)
    {
        $context['footerwidgetcol1'] = Timber::get_widgets('footerwidgetcol1');
        $context['footerwidgetcol2'] = Timber::get_widgets('footerwidgetcol2');
        $context['menu']  = new Timber\Menu();
        $context['site']  = $this;
        return $context;
    }

    public function theme_supports()
    {
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support(
            'html5',
            array(
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            )
        );

        /*
         * Enable support for Post Formats.
         *
         * See: https://codex.wordpress.org/Post_Formats
         */
        add_theme_support(
            'post-formats',
            array(
                'aside',
                'image',
                'video',
                'quote',
                'link',
                'gallery',
                'audio',
            )
        );

        add_theme_support('menus');

        /* Enable ci styles in Backend */
        add_theme_support('editor-styles');
        add_editor_style('dist/site.css');




        /* disable standard gutenberg block editor colors */
        add_theme_support('disable-custom-colors');
        /* add own theme colors */
        add_theme_support(
            'editor-color-palette',
            array(
                array(
                    'name'  => esc_html__('CD-1 Hellgrün', 'danielschoenenberger'),
                    'slug'  => 'cd-1-hellgruen',
                    'color' => '#7b9a62',
                ),
                array(
                    'name'  => esc_html__('CD-3 Dunkelgrün', 'danielschoenenberger'),
                    'slug'  => 'cd-3-dunkelgruen',
                    'color' => '#536657',
                ),
                array(
                    'name'  => esc_html__('P-1 Hellgrün', 'danielschoenenberger'),
                    'slug'  => 'p-1-hellgruen',
                    'color' => '#94b277',
                ),
                array(
                    'name'  => esc_html__('P-2 Mittelgrün', 'danielschoenenberger'),
                    'slug'  => 'p-2-mittelgruen',
                    'color' => '#708456',
                ),
                array(
                    'name'  => esc_html__('P-3 Dunkelgrün', 'danielschoenenberger'),
                    'slug'  => 'p-3-dunkelgruen',
                    'color' => '#477b6a',
                ),
            )
        );


        add_theme_support('disable-custom-font-sizes');
        add_theme_support(
            'editor-font-sizes',
            array(
                array(
                    'name'      => __('H1', 'danielschoenenberger'),
                    'shortName' => __('H1', 'danielschoenenberger'),
                    'size'      => 50,
                    'slug'      => 'h1'
                ),
                array(
                    'name'      => __('H2', 'danielschoenenberger'),
                    'shortName' => __('H2', 'danielschoenenberger'),
                    'size'      => 40,
                    'slug'      => 'h2'
                ),
                array(
                    'name'      => __('H3', 'danielschoenenberger'),
                    'shortName' => __('H3', 'danielschoenenberger'),
                    'size'      => 30,
                    'slug'      => 'h2'
                ),
                array(
                    'name'      => __('P', 'danielschoenenberger'),
                    'shortName' => __('P', 'danielschoenenberger'),
                    'size'      => 22,
                    'slug'      => 'p'
                ),
            )
        );
    }

    /** This Would return 'foo bar!'.
     *
     * @param string $text being 'foo', then returned 'foo bar!'.
     */
    public function myfoo($text)
    {
        $text .= ' bar!';
        return $text;
    }

    /** This is where you can add your own functions to twig.
     *
     * @param string $twig get extension.
     */
    public function add_to_twig($twig)
    {
        //$twig->addExtension(new Twig\Extension\StringLoaderExtension());
        //$twig->addFilter(new Twig\TwigFilter('myfoo', array( $this, 'myfoo' )));
        return $twig;
    }
}

new StarterSite();





// Remove Block vorlagen
function fire_theme_support()
{
    remove_theme_support('core-block-patterns');
}
add_action('after_setup_theme', 'fire_theme_support');



/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function danielschoenenberger_widgets_init()
{
    register_sidebar(
        array(
            'name'          => __('Footer 1', 'danielschoenenberger'),
            'id'            => 'footerwidgetcol1',
            'description'   => __('Add widgets here to appear in your footer.', 'danielschoenenberger'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => __('Footer 2', 'danielschoenenberger'),
            'id'            => 'footerwidgetcol2',
            'description'   => __('Add widgets here to appear in your footer.', 'danielschoenenberger'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );
}
add_action('widgets_init', 'danielschoenenberger_widgets_init');


function extend_editor_caps()
{
    // gets the editor role
    $roleObject = get_role('editor');

    if (!$roleObject->has_cap('edit_theme_options')) {
        $roleObject->add_cap('edit_theme_options');
    }
}
add_action('admin_init', 'extend_editor_caps');
