<?php

namespace App\Services;

use App\Constants\SessionVariables;
use App\Forms\ApplicationForm;
use App\Helpers\BeanHelper;
use App\Helpers\SecurityContextHolder;
use App\Models\SleepingBeautyOutput;
use App\Models\TaskStatusResponse;
use App\Tasks\SleepingBeautyTask;
use Illuminate\Http\Request;

class TaskRunningService {
    const USER_TASK_PREFIX = "user-task::";
    const TASK_ID_PREFIX = "task-id::";
    const TASK_RESULT_PREFIX = "task-result::";
    const COMMAND_PATH = 'bin/runtask.sh';

    public static function runningStatus(): TaskStatusResponse {
        if (RedisService::exists(self::taskRedisKey())) {
            return TaskStatusResponse::running();
        }
        return TaskStatusResponse::idle();
    }

    private static function taskRedisKey(): string {
        return self::USER_TASK_PREFIX . SecurityContextHolder::name();
    }

    private static function redisTaskIdKey(string $taskId, string $user): string {
        return self::TASK_ID_PREFIX . $user . "::" . $taskId;
    }

    private static function redisTaskResultKey(string $taskId, string $user): string {
        return self::TASK_RESULT_PREFIX . $user . "::" . $taskId;
    }

    public static function taskTryAndRun(Request $request, ApplicationForm $form): bool {
        if (RedisService::atomic(self::taskRedisKey(), 1, 86400)) {
            $taskId = uniqid();
            $request->session()->put(SessionVariables::TASK_ID, $taskId);

            RedisService::store(self::redisTaskIdKey($taskId, SecurityContextHolder::name()), json_encode($form), 1800);

            system(sprintf("%s %s %s %s", base_path(self::COMMAND_PATH), base_path(), SecurityContextHolder::name(), $taskId));
        }
        return false;
    }

    public static function taskDone() {
        RedisService::delete(self::taskRedisKey());
    }

    public static function runViaCommandLine(string $user, string $taskId) {
        if (($jsonString = RedisService::retrieve(self::redisTaskIdKey($taskId, $user))) !== false) {
            /** @var ApplicationForm $form */
            $form = (new BeanHelper(new ApplicationForm()))->updateBean(json_decode($jsonString, true));
            $data = SleepingBeautyTask::run($form);
            RedisService::store(self::redisTaskResultKey($taskId, $user), json_encode($data), 1800);
            RedisService::delete(self::USER_TASK_PREFIX . $user);
        }
    }

    public static function retrieveResult(string $user, string $taskId): SleepingBeautyOutput {
        if (($jsonString = RedisService::retrieve(self::redisTaskResultKey($taskId, $user))) !== false) {
            return (new BeanHelper(new SleepingBeautyOutput()))->updateBean(json_decode($jsonString, true));
        } else {
            return SleepingBeautyOutput::error(1, "no result");
        }
    }

}
