<?php

namespace App\Models;

use App\Enums\TaskSequence;
use App\Helpers\TaskList;
use App\Interfaces\Activitable;
use App\Interfaces\Fieldable;
use App\Interfaces\Slable;
use App\Interfaces\Ticket;
use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;
use App\Traits\HasSla;
use App\Traits\TicketTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Request extends Model implements Ticket, Slable, Fieldable, Activitable
{
    use HasSla, HasFactory, TicketTrait;

    protected $guarded = [];
    protected $casts = [
        'resolved_at' => 'datetime',
        'task_sequence' => TaskSequence::class,
    ];
    protected $attributes = [
        'status_id' => self::DEFAULT_STATUS,
        'group_id' => self::DEFAULT_GROUP,
        'priority' => self::DEFAULT_PRIORITY,
    ];

    const PRIORITY_TO_SLA_MINUTES = [
        1 => 30,
        2 => 2 * 60,
        3 => 12 * 60,
        4 => 24 * 60,
    ];

    const CATEGORY_TO_ITEM = [
        [RequestCategory::COMPUTER, RequestItem::BACKUP],
        [RequestCategory::SERVER, RequestItem::ACCESS],
    ];

    function category(): BelongsTo
    {
        return $this->belongsTo(RequestCategory::class, 'category_id');
    }

    function item(): BelongsTo
    {
        return $this->belongsTo(RequestItem::class, 'item_id');
    }

    function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    function taskList(): TaskList
    {
        return $this->initializeTaskList($this->category_id, $this->item->id);
    }

    protected function initializeTaskList($category, $item): TaskList
    {
        $taskList = new TaskList();

        if($category == RequestCategory::COMPUTER){
            if($item == RequestItem::BACKUP){
                $taskList
                    ->addTask('Backup computer of user '. $this->caller->name .'. ')
                    ->addTask('Verify if the backup from previous task is restorable.');
            }
        } elseif($category == RequestCategory::SERVER){
            if($item == RequestItem::BACKUP){
                $taskList
                    ->addTask('Verify if '. $this->caller->name . ' is eligible for access to mentioned server.')
                    ->addTask('Give the access to the user')
                    ->addTask('Verify with '. $this->caller->name .', that the access works.');
            }
        }

        return $taskList;

    }

}
