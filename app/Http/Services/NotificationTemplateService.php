<?php 

namespace App\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Auth;
use Throwable;


class NotificationTemplateService
{
    /**
     * Create Alert Notification
     */
    public function createAlertNotification($action, $module, $sender, $reciever, $item_affected, $updated_at, $url, $notify_client, $notify_trainer, $notify_admin) 
    {
            return [
                'greeting'      => 'Hello!',
                'subject'       => ucfirst($action) . ' - ' . ucfirst($module),
                'body'          => "There has been a(n) {$action} in the {$module} module.",
                'action'        => "<strong>Action:</strong> " . ucfirst($action),
                'module_name'   => "<strong>Module:</strong> " . ucfirst($module),
                'item_affected' => "<strong>Item Affected:</strong> " . $item_affected,
                'action_by'     => "<strong>Action By:</strong> " . $sender,
                'datetime'      => "<strong>Date/Time:</strong> " . now()->format('Y-m-d H:i'),
                'actionText'    => 'View Details',
                'actionURL'     => $url,
                'thanks'        => 'Thank you for your attention!',
            ];
    }
}