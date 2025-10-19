<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //
        $notificaciones=Auth::user()->unreadNotifications;

        // Limpiar Notificaciones
        Auth::user()->unreadNotifications->markAsRead();
        return view('notificaciones.index',[
            'notificaciones' => $notificaciones
        ]);
    }
}
