<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Http\Services\Notifications\NotificationService;

class NotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $action; 
    protected $module; 
    protected $isAdmin_module; 
    protected $sender; 
    protected $reciever; 
    protected $item_affected; 
    protected $updated_at; 
    protected $url; 
    protected $notify_client; 
    protected $notify_trainer; 
    protected $notify_admin;

    /**
     * Create a new job instance.
     */
    public function __construct($action, $module, $isAdmin_module, $sender, $reciever, $item_affected, $updated_at, $url, $notify_client, $notify_trainer, $notify_admin)
    {
        $this->action = $action;
        $this->module = $module;
        $this->isAdmin_module = $isAdmin_module;
        $this->sender = $sender;
        $this->reciever = $reciever;
        $this->item_affected = $item_affected;
        $this->updated_at = $updated_at;
        $this->url = $url;
        $this->notify_client = $notify_client;
        $this->notify_trainer = $notify_trainer;
        $this->notify_admin = $notify_admin;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         try{
               //create the notification
               $notification = $notificationService->createNotification($this->action, $this->module, $this->isAdmin_module, $this->sender, $this->reciever, $this->item_affected, $this->updated_at, $this->url, 
                                                      $this->notify_employee); 

         } catch (Throwable $e) {
               // Log error to a custom file
               Log::build([
                  'driver' => 'single',
                  'path' => storage_path('logs/notifications-job-error.log'),
               ])->error("Notifications Job Failed: " . $e->getMessage(), [
                  'exception' => $e->getTraceAsString()
               ]);
   
        }
    }
}
