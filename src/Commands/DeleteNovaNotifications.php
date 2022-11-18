<?php

namespace CodeTech\NovaNotifications\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Laravel\Nova\Notifications\Notification;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 *
 */
class DeleteNovaNotifications extends Command
{
    /**
     * @var string
     */
    protected $signature = 'nova:delete-notifications {--clean} {--all}';


    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(): void
    {
        if ($this->option('all')) {
            $total = $this->deleteAllNotifications();

            $this->info($total . ' notifications deleted!');

            return;
        }
        $total = $this->deleteReadNotifications();

        $this->info('Deleted ' . $total . ' read messages');

        if ($this->option('clean')) {
            $total = $this->deleteAllReadNotifications();

            $this->info('Deleted ' . $total . ' messages');
        }
    }

    /**
     * @return int
     */
    public function deleteAllNotifications(): int
    {
        $total = Notification::count();
        Notification::truncate();

        return $total;
    }

    /**
     * @return int
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function deleteReadNotifications(): int
    {
        return Notification::where('read_at', '<', $this->getDate(config()->get('nova_notifications.delete_read')))->delete();
    }

    /**
     * @return int
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function deleteAllReadNotifications(): int
    {
        return Notification::withTrashed()->where('created_at', '<', $this->getDate(config()->get('nova_notifications.delete_all')))->forceDelete();
    }

    /**
     * @param array $times
     * @return \Illuminate\Support\Carbon
     */
    public function getDate(array $times): Carbon
    {
        $currentTime = now();
        foreach ($times as $period => $value) {
            $currentTime = $currentTime->sub($period, $value);
        }

        return $currentTime;
    }
}
