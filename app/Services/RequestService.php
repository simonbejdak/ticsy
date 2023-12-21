<?php

namespace App\Services;

use App\Enums\TaskSequence;
use App\Interfaces\Ticket;
use App\Models\Request;
use App\Models\User;

class RequestService
{
    public static function assignTasks(Request $request)
    {
        $taskMapper = $request->getTasksMapper();
        if(
            $request->category_id === RequestCategory::SAP &&
            $request->item_id === RequestCategoryItem::INSTALL
        ){
            $request->taskSequence(TaskSequence::ONE_BY_ONE);

            TaskService::createTask($request, [
                'description' => 'Install SAP application on computer ' . $request->user->computer->hostname
            ]);

            TaskService::createTask($request, [
                'description' => 'Verify after 1 week, if SAP installation works okay on computer' . $request->user->computer->hostname
            ]);

        }
    }
}
