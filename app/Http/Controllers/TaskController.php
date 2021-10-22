<?php

namespace App\Http\Controllers;

use App\Constants\SessionVariables;
use App\Forms\ApplicationForm;
use App\Helpers\BeanHelper;
use App\Helpers\SecurityContextHolder;
use App\Models\AjaxResponse;
use App\Models\SleepingBeautyOutput;
use App\Services\TaskRunningService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController {
    public function status(Request $request): JsonResponse {
        return response()->json(TaskRunningService::runningStatus());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function result(Request $request): JsonResponse {
        $id = $request->session()->get(SessionVariables::TASK_ID);

        if (isset($id)) {
            return response()->json(TaskRunningService::retrieveResult(SecurityContextHolder::name(), $id));
        } else {
            return response()->json(SleepingBeautyOutput::error(2, "no running task"));
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function run(Request $request): JsonResponse {
        $data = $request->json()->all();
        /** @var ApplicationForm $sessionData */
        $sessionData = $request->session()->get(SessionVariables::APPLICATION_FROM, new ApplicationForm());

        $request->session()->put(SessionVariables::APPLICATION_FROM,
            (new BeanHelper($sessionData))->updateBean($data));

        TaskRunningService::taskTryAndRun($request, $sessionData);

        return response()->json(AjaxResponse::success());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function startOver(Request $request): JsonResponse {
        $request->session()->remove(SessionVariables::APPLICATION_FROM);
        $request->session()->remove(SessionVariables::TASK_ID);

        return response()->json(AjaxResponse::success());
    }
}
