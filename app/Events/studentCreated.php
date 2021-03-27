<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
class studentCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $student_id;
    public $password;
    public $name;
    public $level_id;
    public $email;
    public $staff_id;
    public $type;
    public $school_id;

    public function __construct($name,$student_id,$email,$level_id,$staff_id,$type,$school_id)
    {
       $this->student_id=$student_id;
       $this->name=$name;
       $this->email=$email;
       $this->level_id=$level_id;
       $this->staff_id=$staff_id;
       $this->type=$type;
       $this->school_id=$school_id;


    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
