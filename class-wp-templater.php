<?php
namespace PiotrSzczygiel\WordPressPluginsTools\YourPluginName;

/**
 * This class will help you handling the templates in your WP extensions and finally
 * you will get rid of HTML markup from PHP code :)
 * When using, it's recommended to override current namespace, to make the class
 * unique in all your plugins, f.e you can set the namespace as:
 * PiotrSzczygiel\WordPressPluginsTools\YourPluginName;
 *
 * @author Piotr Szczygiel <psz.szczygiel@gmail.com>
 * @link https://github.com/piotr-szczygiel/wp-templater - check out latest version
 * @license MIT
 * @version 1.0.1
 */
class wp_templater
{
    /**
     * Property contains an instance of the object
     * @var wp_templater
     */
    private static $instance = null;

    /**
     * Path to the plugin directory
     * @var string
     */
    private static $plugin_path = null;

    /**
     * Default construct is blocked from outside
     */
    private function __construct(){}

    /**
     * Checks whether plugin path is correctly set
     * @throws \LogicException
     */
    private static function check_plugin_path()
    {
        if ( is_null( self::$plugin_path ) ) {
            throw new \LogicException( 'Plugin path is not set. Please do that using set_path() method.' );
        }
    }

    /**
     * Method validates whether template exists
     * @param string $path
     * @throws \InvalidArgumentException
     */
    private static function check_template_path( $path )
    {
        if ( !file_exists( $path ) ) {
            throw new \InvalidArgumentException( 'Seems like the template which you want to render doesn\'t exist. Please check a name and a path.' );
        }
    }

    /**
     * Singleton main method
     * @param sting|null $plugin_path
     * @return wp_templater
     */
    public static function get_instance( $plugin_path = null )
    {
        if ( !is_null( $plugin_path ) ) {
            self::set_path( $plugin_path );
        }

        self::check_plugin_path();

        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Method renders the template
     * @param string $template_name
     * @param array $args
     * @param string $templates_dir
     */
    public function render( $template_name, $args = array(), $templates_dir = 'templates' )
    {
        foreach( $args as $id => $value ) {
            $$id = $value;
        }

        $path = self::$plugin_path . $templates_dir . '/' . $template_name . '.php';
        self::check_template_path( $path );
        require( $path );
    }

    /**
     * Plugin path setter
     * @param string $plugin_path
     */
    public static function set_path( $plugin_path )
    {
        self::$plugin_path = $plugin_path;
    }
}