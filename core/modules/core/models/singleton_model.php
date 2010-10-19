<?php namespace nmvc\core;

/**
 * A singleton model is a model that only and always has exactly one instance.
 * Singleton models does not have an unlinked state.
 * Use get() to get the instance.
 */
abstract class SingletonModel extends \nmvc\AppModel {
    private $is_getting = false;

    /**
     * Returns this SingletonModel instance.
     * This function ensures that exactly one exists.
     */
    public static function get() {
        static $singleton_model_cache = array();
        $class_name = get_called_class();
        if (isset($singleton_model_cache[$class_name]))
            return $singleton_model_cache[$class_name];
        $result = static::select()->all();
        $singleton_model = \reset($result);
        if ($singleton_model === null) {
            // If there are no model instance, create new.
            $this->is_getting = true;
            $singleton_model = new $class_name();
            $this->is_getting = false;
            $singleton_model->store();
        } else {
            // If there are more than one model instance, unlink the rest.
            while (false !== ($instance = \next($result)))
                $instance->unlink();
        }
        return $singleton_model_cache[$class_name] = $singleton_model;
    }

    protected function initialize() {
        parent::initialize();
        if (!$this->getting)
            \trigger_error("Only SingletonModel may create new instance of itself. Access the instance trough the ::get() function.", \E_USER_ERROR);
    }
}
