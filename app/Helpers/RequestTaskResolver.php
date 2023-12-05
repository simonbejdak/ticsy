<?php

namespace App\Helpers;

use App\Enums\TaskSequence;

class RequestTaskAssigner
{
    public static function assignTasks(Request $request)
    {
        if(
            $request->category_id === RequestCategory::SAP &&
            $request->item_id === RequestCategoryItem::INSTALL
        ){
            $request->taskSequence(TaskSequence::ONE_BY_ONE);

            App::newTask($request, [
                'description' => 'Install SAP application on computer ' . $request->user->computer->hostname,
                'priority' => 3
            ]);

            App::newTask($request, [
                'description' => 'Verify after 1 week, if SAP installation works okay on computer' . $request->user->computer->hostname,
                'priority' => 3
            ]);

        }
    }
}
