@component('mail::message')
# Introduction
Dear {{ $task->user->name }},

This is a Task ID {{ $task->id .': '. $task->name }} reminder.

To open task click on the button bellow:
 
@component('mail::button', ['url' => $url])
View Task Now
@endcomponent

The task is due <strong> {{ \Carbon\Carbon::parse($task->due_date)->format('d-m-Y') }}
    ({{ \Carbon\Carbon::parse($task->due_date)->diffForHumans()}}) </strong>.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
