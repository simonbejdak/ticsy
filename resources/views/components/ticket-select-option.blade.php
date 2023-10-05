@props([
    'value' => '',
    'text' => '',
    'selected' => '',
  ])

<option {{ ($text == $selected) ? 'selected' : '' }} value="{{ $value }}">{{ $text }}</option>
