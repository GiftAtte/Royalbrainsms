<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MarksCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $report_id;
    public $students;
    public $level_id;
    public $subject_id;
    public $is_history;
    public function __construct($report_id,$students,$level_id,$subject_id, $is_history)
    {
        //
        $this->report_id=$report_id;
        $this->students=$students;
        $this->level_id=$level_id;
        $this->subject_id=$subject_id;
        $this->is_history=$is_history;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //dd('$this->report_id');
        return new Channel('students');
    }
}
