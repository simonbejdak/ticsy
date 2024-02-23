<?php

namespace App\Helpers\Strategies;

use App\Enums\TaskSequence;
use App\Helpers\TaskPlan;
use App\Models\Group;
use App\Models\Request;
use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;

class RequestStrategy extends TaskableStrategy
{
    static function create(Request $request): self
    {
        $static = new static();

        if($request->category_id == RequestCategory::COMPUTER){
            if($request->item_id == RequestItem::BACKUP){
                $static->group = Group::byName('LOCAL-6445-NEW-YORK');
                $static->tasks = [
                    'Backup computer of user '. $request->caller->name .'. ',
                    'Verify if the backup from previous task is restorable.',
                ];
            }
        } elseif($request->category_id == RequestCategory::SERVER){
            if($request->item_id == RequestItem::ACCESS){
                $static->group = Group::byName('LOCAL-6380-SKOPJE');
                $static->tasks = [
                    'Verify if '. $request->caller->name . ' is eligible for access to mentioned server.',
                    'Give the access to the user',
                    'Verify with '. $request->caller->name .', that the access works.',
                ];
            } elseif($request->item_id == RequestItem::MAINTENANCE){
                $static->group = Group::byName('LOCAL-6445-NEW-YORK');
                $static->taskSequence = TaskSequence::AT_ONCE;
                $static->tasks = [
                    'Restart database',
                    'Restart respective services',
                ];
            }
        }

        if(!isset($static->tasks)){
            $static->tasks = [$request->description];
        }

        return $static;
    }

}
