<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Hr\App\Models\ActionLog;

class ActionLogController extends Controller
{
    public function getAction(Request $request)
    {
        $actionLogs = ActionLog::where('model_type', $request->modelName)
            ->where('model_id', $request->modelId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($actionLogs);
    }
}
