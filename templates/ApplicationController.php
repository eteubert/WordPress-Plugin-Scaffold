<?php
if (!class_exists('ApplicationController')) {
  /**
  * Application Controller
  */
  abstract class ApplicationController
  {

    function __construct()
    {
      $this->derived_class_name = get_class($this);
      // "FoobarController" becomes "foobar"
      $this->controller_name = strtolower(str_replace('Controller', '', $this->derived_class_name));

      // register ajax actions
      $actions = array('index', 'new', 'create', 'delete', 'update');
      foreach ($actions as $action) {
        // fired if not logged in
        add_action("wp_ajax_nopriv_$action-$this->controller_name", array(&$this, "call_$action"));
        // fired if logged in
        add_action("wp_ajax_$action-$this->controller_name", array(&$this, "call_$action"));      
      }
    }

    /**
     * call_index
     *
     * template method for create calls
     */
    public function call_index($arg = null)
    {
      $this->index($arg);
      $this->render('index');
    }
    
    /**
     * call_new
     *
     * template method for new calls
     */
    public function call_new($arg = null)
    {
      $this->newForm($arg);
      $this->render('new');
    }

    /**
     * call_create
     *
     * template method for create calls
     */
    public function call_create($arg = null)
    {
      $this->create($arg);
      $this->render('create');
    }

    /**
     * call_delete
     *
     * template method for delete calls
     */
    public function call_delete($arg = null)
    {
      $this->delete($arg);
      $this->render('delete');
    }

    /**
     * call_update
     *
     * template method for update calls
     */
    public function call_update($arg = null)
    {
      $this->update($arg);
      $this->render('update');
    }

    /**
     * render
     * 
     * expected view location: /views/<controller>/<action>.<format>.php
     * @filter team-view-directories
     *    Use to add your own view directory.
     *    That way you can overwrite the default templates.
     *    Make sure to add your path as the first one with array_unshift()
     *    Example:
     *      function my_own_view_directory($directories)
     *      {
     *        $child_theme_view_dir = dirname(__FILE__) . '/views/';
     *        array_unshift($directories, $child_theme_view_dir);
     *        return $directories;
     *      }
     *      add_filter('team-view-directories', 'my_own_view_directory');   
     */
    private function render($action)
    {
      $formats = array('html', 'json');

      // prepend "admin." to filename so we can 
      // distinguish between admin views and frontend views
      // FIXME all ajax calls are considered as "admin"
      $admin = (is_admin()) ? 'admin.' : '';

      $directories = array();
      $directories = apply_filters('app-view-directories', $directories);

      foreach ($directories as $base_path) {
        $path = $base_path . $this->controller_name . '/' . $admin . $action;

        foreach ($formats as $format) {
          $file = "$path.$format.php";
          if (is_file($file)) {
            $this->header_by_format($format);
            include $file;
            break 2;
          }
        }
      }

      // if ajax request, exit here
      if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') { 
        exit;
      }
    }

    /**
     * header_by_format
     * 
     * send headers based on used format
     */
    private function header_by_format($format)
    {
      switch ($format) {
        case 'json':
          header("Content-Type: application/json");
          break;
      }
    }

  }
}