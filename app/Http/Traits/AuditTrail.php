<?php
namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App\Models\EventsLogger;
use Auth;
use Carbon\Carbon;


trait AuditTrail
{     
    /**
     * Handle model event
     */
    public static function bootAuditTrail()
    {
        /**
         * Data creating and updating event
         */
        static::saved(function ($model) {
            // create or update?
            if ($model->wasRecentlyCreated) {
                static::storeLog($model, static::class, 'CREATED');
            } else {
                if (!$model->getChanges()) {
                    return;
                }
                static::storeLog($model, static::class, 'UPDATED');
            }
        });

        /**
         * Data deleting event
         */
        static::deleted(function (Model $model) {
            static::storeLog($model, static::class, 'DELETED');
        });
    }

    /**
     * Generate the model name
     * @param  Model  $model
     * @return string
     */
    public static function getTagName(Model $model)
    {
        return !empty($model->tagName) ? $model->tagName : Str::title(Str::snake(class_basename($model), ' '));
    }

    /**
     * Retrieve the current login user id
     * @return int|string|null
     */
    public static function activeUserId()
    {
        return Auth::guard(static::activeUserGuard())->id();
    }

    


    /**
     * Retrieve the current login user guard name
     * @return mixed|null
     */
    public static function activeUserGuard()
    {
        foreach (array_keys(config('auth.guards')) as $guard) {

            if (auth()->guard($guard)->check()) {
                return $guard;
            }
        }

        return null;
    }
     
    /**
     * Store model logs
     * @param $model
     * @param $modelPath
     * @param $action
     */
    public static function storeLog($model, $modelPath, $action)
    {

        if(static::activeUserId() == null){
            //no need to log this event its just registration

        }else{

            $newValues = null;
            $oldValues = null;
    
            $agent = new Agent();
            $browser = $agent->browser();
            $browser_version = $agent->version($browser);
            $platform = $agent->platform();
            $platform_version = $agent->version($platform);
    
            $client_information = "BROWSER : ".$browser."<br>";
            $client_information .= "VERSION : ".$browser_version."<br>";
            $client_information .= "PLATFORM : ".$platform."<br>";
            $client_information .= "VERSION : ".$platform_version."<br>";
    
            if ($action === 'CREATED') {
                $newValues = $model->getAttributes();
            } elseif ($action === 'UPDATED') {
                $newValues = $model->getChanges();
            }
    
            if ($action !== 'CREATED') {
                $oldValues = $model->getOriginal();
            }
    
                
            try{
    
                $eventLog = new EventsLogger();
                $eventLog->employee_code = static::activeEmployeeCode();
                $eventLog->company_code = static::activeCompanyCode();    
                $eventLog->module_section = static::getTagName($model);
                $eventLog->action = $action;
                $eventLog->event_status = 13;
                $eventLog->client_information = $client_information;
                $eventLog->created_by = static::activeEmployeeCode();
                $eventLog->updated_by = static::activeEmployeeCode();
                $eventLog->old_values = !empty($oldValues) ? $oldValues : null;
                $eventLog->new_values = !empty($newValues) ? $newValues : null;
                $eventLog->ip_address = request()->ip();
                $eventLog->save();
    
            }catch(Exception $e){
                //lets log the errors
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/event_logger-error.log')
                ])->info("Event Logger Failed for Record ID {$eventLog->id}: " . $e->getMessage());
            }

        } 
            
    }  

}