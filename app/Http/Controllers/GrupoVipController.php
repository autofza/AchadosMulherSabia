<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Log as LogModel;

class GrupoVipController extends Controller
{
    public function redirect(Request $request)
    {
        LogModel::create([
            'action'     => 'WhatsApp click',
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->away(
            'https://chat.whatsapp.com/IsH4Li5ktHGCq0xMt2wfaY?mode=ems_copy_t'
        );
    }
}
