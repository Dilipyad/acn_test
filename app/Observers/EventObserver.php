<?php

namespace App\Observers;

use App\Mail\EventAddedEmail;
use App\Mail\EventDeletedEmail;
use App\Models\Event;
use App\Models\User;
class EventObserver
{
    public function created(Event $Event)
    {
        $this->sendMailable($Event, EventAddedEmail::class);
    }

    public function deleted(Event $Event)
    {
        $this->sendMailable($Event, EventDeletedEmail::class);
    }

    private function sendMailable(Event $Event, $mailable)
    {
        $teacher = User::findOrFail($Event->teacher_id)->first();
        $student = User::findOrFail($Event->student_id)->first();

        \Mail::to($teacher->email)->send(
            new $mailable($teacher, $student)
        );
    }
}