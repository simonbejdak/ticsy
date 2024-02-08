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
            $body = '<p>' . nl2br(e(htmlspecialchars($this->activity->description))) . '</p>';
        } else {
            $body = '<table class="border-separate border-spacing-x-2 w-full">';

            foreach ($this->activity->changes['attributes'] as $field => $value) {
                $fieldName = makeDisplayName(str_replace('.name', '', $field));
                $newFieldValue = ($value !== null) ? $value : 'empty';

                $body .= '<tr>';
                $body .= '<td class="text-right w-1/6 pt-0.5 align-top">' . htmlspecialchars($fieldName) . ': </td>';
                $body .= '<td class="text-left pt-0.5">' . nl2br(e(htmlspecialchars($newFieldValue)));

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
