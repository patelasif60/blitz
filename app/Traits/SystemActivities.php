<?php


namespace App\Traits;

use App\Models\SystemActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait SystemActivities
{
    private $hiddenAttribute;

    private $enableFilter = false;

    /**
     * Handle model event
     */
    public static function bootSystemActivities($module = null)
    {
        /**
         * Data creating and updating event
         */
        static::saved(function ($model) use($module){
            // create or update?
            if ($model->wasRecentlyCreated) {
                static::storeLog($model, static::class, SystemActivity::CREATED, $module);
            } else {
                if (!$model->getChanges()) {
                    return;
                }
                static::storeLog($model, static::class, SystemActivity::UPDATED, $module);
            }
        });



        /**
         * Data deleting event
         */
        static::deleted(function (Model $model) use($module){
            static::storeLog($model, static::class, SystemActivity::DELETED, $module);
        });
    }

    /**
     * Specific record history
     *
     * @return mixed
     */
    public function activities()
    {
        if (!$this->enableFilter) {

            return $this->morphMany(SystemActivity::class, 'system_logable');
        } else {
            $this->getHiddenAttribute($this);
            $this->attributeToJsonParam();
            return $this->filterActivities($this);
        }
    }

    /**
     * Enable activity filter
     *
     * @return $this
     */
    public function filter($enable = true)
    {
        $this->enableFilter = $enable;
        return $this;
    }

    /**
     * Store view record
     */
    public static function bootSystemView($model, $module = null, $action = null, $recordId = null)
    {
        $modelPath      =   static::class;
        $oldValues      =   null;
        $newValues      =   null;

        $systemLog                              = new SystemActivity();
        $systemLog->system_logable_id           = $recordId == null ? 0 : $recordId;
        $systemLog->system_logable_type         = $modelPath;
        $systemLog->user_id                     = static::activeUserId();
        $systemLog->guard_name                  = static::activeUserGuard();
        $systemLog->module_name                 = $module!=null ? $module : static::getTagName($model);
        $systemLog->action                      = $action ?? SystemActivity::VIEW;
        $systemLog->old_value                   = !empty($oldValues) ? json_encode($oldValues) : null;
        $systemLog->new_value                   = !empty($newValues) ? json_encode($newValues) : null;
        $systemLog->ip_address                  = request()->ip();
        $systemLog->save();
    }

    /**
     * Custom Hidden attribute
     *
     * @param array $attribute
     * @return $this
     */
    public function hiddenAttribute(array $attribute = [])
    {
        $this->hiddenAttribute = $attribute;

        return $this;
    }

    /**
     * Filter Activities
     *
     * @return string
     */
    private function filterActivities($model)
    {
        $query = SystemActivity::with('causer')->where('system_logable_type', get_class($model))
            ->where('system_logable_id', $model->id)
            ->selectRaw("*");
        $query = $this->filterNewValue($query);
        $query = $this->filterOldValue($query);

        return $query;
    }

    /**
     * Filter new value by query
     * @param $query
     * @return mixed
     */
    private function filterNewValue($query)
    {
        if (!empty($this->hiddenAttribute)) {
            $attr = $this->attributeToJsonParam();
            return $query->selectRaw("json_remove(new_value, $attr) As new_value");
        }
        return $query;
    }

    /**
     * Filter old value by query
     * @param $query
     * @return mixed
     */
    private function filterOldValue($query)
    {
        if (!empty($this->hiddenAttribute)) {
            $attr = $this->attributeToJsonParam();
            return $query->selectRaw("json_remove(old_value, $attr) As old_value");
        }
        return $query;
    }

    /**
     * Hidden Attribute to Json Param
     *
     * @return string
     */
    private function attributeToJsonParam()
    {
        $jsonAttr = [];
        if (!empty($this->hiddenAttribute)) {
            foreach ($this->hiddenAttribute as $array) {
                $array = (string)"'$.$array'";

                array_push($jsonAttr,$array);
            }
        }
        $jsonAttr = implode(',',$jsonAttr);
        return $jsonAttr;
    }

    /**
     * Generate the model name
     * @param  Model  $model
     * @return string
     */
    private static function getTagName(Model $model)
    {
        return !empty($model->tagname) ? $model->tagname : Str::title(Str::snake(class_basename($model), ' '));
    }

    /**
     * Get hidden activity attributes
     *
     * @param Model $model
     * @return mixed
     */
    private function getHiddenAttribute(Model $model)
    {
        return $this->hiddenAttribute =  (isset($model->hiddenActivity) && empty($this->hiddenAttribute)) ? $model->hiddenActivity : $this->hiddenAttribute;
    }

    /**
     * Retrieve the current login user id
     * @return int|string|null
     */
    private static function activeUserId()
    {
        return Auth::guard(static::activeUserGuard())->id();
    }

    /**
     * Retrieve the current login user guard name
     * @return mixed|null
     */
    private static function activeUserGuard()
    {
        $guardName = 'web';
        foreach (array_keys(config('auth.guards')) as $guard) {

            if ($guard !='api') {
                if (auth()->guard($guard)->check()) {
                    $guardName = $guard;
                }
            }

        }
        return $guardName;
    }

    /**
     * Store model logs
     * @param $model
     * @param $modelPath
     * @param $action
     */
    private static function storeLog($model, $modelPath, $action, $module = null)
    {

        $newValues = null;
        $oldValues = null;
        if ($action === SystemActivity::CREATED) {
            $newValues = $model->getAttributes();
        } elseif ($action === SystemActivity::UPDATED) {
            $newValues = $model->getChanges();
        }

        if ($action !== SystemActivity::CREATED && $action !== SystemActivity::VIEW) {
            $oldValues = $model->getOriginal();
        }

        $systemLog = new SystemActivity();
        $systemLog->system_logable_id = $action == SystemActivity::VIEW ? 0 : $model->id;
        $systemLog->system_logable_type = $modelPath;
        $systemLog->user_id = static::activeUserId();
        $systemLog->guard_name = static::activeUserGuard();
        $systemLog->module_name = $module!=null ? $module : static::getTagName($model);
        $systemLog->action = $action;
        $systemLog->old_value = !empty($oldValues) ? json_encode($oldValues, JSON_UNESCAPED_SLASHES) : null;
        $systemLog->new_value = !empty($newValues) ? json_encode($newValues, JSON_UNESCAPED_SLASHES) : null;
        $systemLog->ip_address = request()->ip();
        $systemLog->save();
    }

}
