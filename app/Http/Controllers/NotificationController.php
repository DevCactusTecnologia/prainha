<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (session()->has('page_limit')) {
                $this->limit = session()->get('page_limit');
            } else {
                $this->limit = Config::get('app.page_limit');
            }

            return $next($request);
        });
    }

    public function index()
    {
        $user = Sentinel::getUser();

        if (! $user) {
            return redirect('/')->withError('Please Login'); 
        }

        $role = $user->roles[0]->slug;
        $userId = $user->id;
        $notification = Notification::with(['user', 'appointment_user.patient', 'appointment_user.timeSlot', 'invoice_user.patient', 'user.roles'])
            ->where('to_user', $userId)
            ->orderBy('id', 'DESC')
            ->paginate($this->limit);
            
        return view('notification-list', 
            compact('notification', 'user', 'role')
        );
    }

    public function notification($id)
    {
        $notification = Notification::find($id);
        $user_role = Sentinel::getUser()->roles[0]->slug;

        if ($notification) {

            $notification->read_at = Carbon::now();
            $notification->save();
            if ($notification->notification_type_id == 1) {
                if ($user_role == 'patient') {
                    $url = '/notification-list';
                } else {
                    $url = '/appointments/status/0';
                }
            } elseif ($notification->notification_type_id == 2) {
                if ($user_role == 'patient') {
                    $url = '/notification-list';
                } else {
                    $url = '/complete-appointment';
                }
            } elseif ($notification->notification_type_id == 3) {
                if ($user_role == 'patient') {
                    $url = '/notification-list';
                } else {
                    $url = '/cancel-appointment';
                }
            } elseif ($notification->notification_type_id == 4) {
                if ($user_role == 'patient') {
                    $url = '/notification-list';
                } else {
                    $url = 'invoice/' . $notification->data;
                }
            }

            return redirect($url);
        }

        return $notification;
    }

    public function notification_top(Request $request)
    {
        if ($request->ajax()) {
            $user = Sentinel::getUser();
            $user_id = $user->id;
            $notification_count = Notification::with(['user'])
                ->where('to_user', $user_id)
                ->where('read_at', '=', null)
                ->take(10)
                ->orderBy('id', 'desc')
                ->get();
            
            return response()->json([
                'isSuccess' => true,
                'Message' => 'Notification list!',
                'options' => view('layouts.ajax-notification', compact('notification_count'))->render(),
            ]);
        }
    }
    public function notificationCount()
    {
        $user = Sentinel::getUser();

        if (! $user) {
            return response()->json(0);
        } 
        
        $user_id = $user->id;
        $notification = Notification::where(['to_user' => $user_id])
            ->where('read_at', '=', null)
            ->count();
        
        return response()->json($notification);
    }
}
