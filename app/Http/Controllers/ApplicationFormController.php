<?php

namespace App\Http\Controllers;

use App\Constants\SessionVariables;
use App\Forms\ApplicationForm;
use App\Helpers\BeanHelper;
use App\Models\AjaxResponse;
use App\Tasks\SleepingBeautyTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationFormController extends Controller {
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse {
        if (!$request->session()->exists(SessionVariables::APPLICATION_FROM)) {
            $request->session()->put(SessionVariables::APPLICATION_FROM, new ApplicationForm());
        }
        return response()->json($request->session()->get(SessionVariables::APPLICATION_FROM));
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse {
        $data = $request->json()->all();
        $sessionData = $request->session()->get(SessionVariables::APPLICATION_FROM, new ApplicationForm());

        $request->session()->put(SessionVariables::APPLICATION_FROM,
            (new BeanHelper($sessionData))->updateBean($data));

        return response()->json(AjaxResponse::success());
    }

    public function run(Request $request) {
        $sessionData = $request->session()->get(SessionVariables::APPLICATION_FROM, new ApplicationForm());
        /** @var ApplicationForm $data */
        $data = (new BeanHelper($sessionData))->updateBean($request->all());
        return view('form-done', ['image' => SleepingBeautyTask::run($data)]);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function clear(Request $request): JsonResponse {
        $request->session()->put(SessionVariables::APPLICATION_FROM, new ApplicationForm());
        return response()->json(AjaxResponse::success());
    }
}
