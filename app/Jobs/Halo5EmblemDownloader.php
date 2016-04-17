<?php namespace PandaLove\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Onyx\Account;
use Illuminate\Contracts\Bus\SelfHandling;
use Onyx\Halo5\Client;

class Halo5EmblemDownloader extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var Account $account
     */
    protected $account;

    /**
     * Create a new job instance.
     *
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();
        $client->setInitialEmblem($this->account);
    }
}
