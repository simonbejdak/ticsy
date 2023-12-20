<?php

namespace App\View\Components;

use App\Helpers\App;
use Closure;
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
        'priority_change_reason' => 'border-yellow-300',
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
        if($this->activity->event === 'comment' || $this->activity->event === 'priority_change_reason'){
            $body = '<p>' . htmlspecialchars($this->activity->description) . '</p>';
        } else {
            $body = '<table class="border-separate border-spacing-x-2 w-1/2">';

            foreach ($this->activity->changes['attributes'] as $field => $value) {
                $fieldName = App::makeDisplayName(str_replace('.name', '', $field));
                $newFieldValue = ($value !== null) ? $value : 'empty';

                $body .= '<tr class="border-spacing-y-3">';
                $body .= '<td class="text-right w-1/6">' . htmlspecialchars($fieldName) . ': </td>';
                $body .= '<td class="text-left">' . htmlspecialchars($newFieldValue);

                if ($this->activity->event === 'updated') {
                    $oldFieldValue = ($this->activity->changes['old'][$field] !== null) ? $this->activity->changes['old'][$field] : 'empty';

                    $body .= ' was ' . htmlspecialchars($oldFieldValue) . '</td>';
                }

                $body .= '</tr>';
            }

            $body .= '</table>';
        }

        return $body;
    }
}
