<?php
abstract class application
{
    public function getController($controller)
    {
        $controller .= 'Controller';
        include_once FILE . 'framework/controller/' . $controller . '.php';
        $this->$controller = new $controller();
    }

    public function getModel($model, $db=false)
    {
        $model .= 'Model';
        include_once FILE . 'framework/model/' . $model . '.php';
        if ($db) { $this->$model = new $model($db); }
            else { $this->$model = new $model(); }
    }

    public function getView($view)
    {
        $view .= 'View';
        include_once FILE . 'framework/view/' . $view . '.php';
        $this->$view = new $view();
    }

}
?>
