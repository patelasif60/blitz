<?php

namespace App\Repositories;
use App\Models\Notification;
use App\Models\UserSupplier;
use Auth;

class NotificationRepository extends BaseRepository
{
    public function sendOrderDeliverySeprateNotification($sendAdminNotification,$sendSupplierNotification)
    {
        Notification::insert($sendAdminNotification);
        Notification::insert($sendSupplierNotification);
    }
    public function getSupplierId()
    {
        return UserSupplier::where('user_id',auth()->user()->id)->pluck('supplier_id')->first();
    }
}