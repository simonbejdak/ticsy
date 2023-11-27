<?php

namespace App\View\Components;

use Closure;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\Activitylog\Models\Activity;

class ActivityCard extends Component
{
    public Activity $activity;
    public string $style;
    public array|string $body;
    protected array $styleMap = [
        'comment' => 'border-black',
        'created' => 'border-slate-300',
        'updated' => 'border-slate-300',
    ];
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
        $this->style = $this->styleMap[$this->activity->event];
        $this->body = $this->setBody();
    }

    public function render(): View|Closure|string
    {
        return view('components.activity-card');
    }

    protected function setBody(): string|array{
        if($this->activity->event === 'comment'){
            $body = $this->activity->description;
        }

        elseif($this->activity->event === 'created'){
            foreach ($this->activity->changes['attributes'] as $field => $value){
                $fieldName =
                    ucfirst(
                        strtolower(
                            preg_replace('/(?<!\ )[A-Z]/', ' $0',
                                str_replace('.name', '', $field)
                            )
                        )
                    );

                $value = ($value !== null) ? $value : 'empty';

                $body[] = $fieldName . ": " . $value;
            }
        }

        elseif($this->activity->event === 'updated'){
            foreach ($this->activity->changes['attributes'] as $field => $value){
                $fieldName =
                    ucfirst(
                        strtolower(
                            preg_replace('/(?<!\ )[A-Z]/', ' $0',
                                str_replace('.name', '', $field)
                            )
                        )
                    );
                $newValue = ($value !== null) ? $value : 'empty';
                $oldValue = ($this->activity->changes['old'][$field] !== null) ? $this->activity->changes['old'][$field] : 'empty';
                $body[] = $fieldName . ': "' . $newValue . '" was "' . $oldValue . '"';
            }

        }

        else {
            throw new Exception('Activity has invalid event');
        }

        return $body;
    }
}
