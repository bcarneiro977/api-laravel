<?php 
namespace App\Jobs;

use App\Mail\OrderCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;

class SendOrderEmailJob implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $order;
    protected $email;

    public function __construct(Order $order, $email)
    {
        $this->order = $order;
        $this->email = $email;
    }

    public function handle()
    {
        Mail::to($this->email)->send(new OrderCreated($this->order));
    }
}
