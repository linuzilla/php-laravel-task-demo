<?php

namespace App\Models;

use JetBrains\PhpStorm\Pure;

class TaskStatusResponse extends AjaxResponse {
    public string $taskStatus;

    /**
     * @param string $taskStatus
     */
    #[Pure] public function __construct(string $taskStatus) {
        parent::__construct(0, "success");
        $this->taskStatus = $taskStatus;
    }

    /**
     * @return TaskStatusResponse
     */
    #[Pure] public static function running(): TaskStatusResponse {
        return new TaskStatusResponse("running");
    }

    /**
     * @return TaskStatusResponse
     */
    #[Pure] public static function idle(): TaskStatusResponse {
        return new TaskStatusResponse("idle");
    }
}
