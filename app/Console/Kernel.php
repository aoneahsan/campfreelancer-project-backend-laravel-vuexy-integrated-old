<?php

namespace App\Console;

use App\Model\Order\Order;
use App\Model\Order\OrderDelivery;
use App\Model\User\UserAccount;
use App\Model\User\UserTransactionLog;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Custom Schedules
        // This Schedule Runs Every 5 mins
        $schedule->call(function () {
            $currentTime = Carbon::now();
            $currentTimePlusThreeDays = Carbon::now()->addDays(3); // this is to auto mark order completed if buyer has not respond to order delivery in three days

            // check if order is late (delivery time is already passed)
            Order::where('order_delivery_date', '<=', $currentTime)->update([
                'is_late' => true
            ]);

            // check if order pending clearance time is done
            $orders = Order::where('status', 'completed')->where('amount_will_clear_at', '<=', $currentTime)
                ->where('is_cleared', false)
                ->get();
            $orders->update([
                'amount_cleared_at' => $currentTime,
                'amount_added_in_seller_account_at' => $currentTime,
                'is_cleared' => true
            ]);
            $logs = [];
            foreach ($orders as $order) {
                $logs[] = [
                    'user_id' => $order->seller_id,
                    'order_id' => $order->id,
                    'orcer_number' => $order->orcer_number,
                    'transaction_log_type' => 'funds_cleared',
                    'amount' => $order->seller_earning,
                    'log_created_at' => $currentTime
                ];
                $seller = UserAccount::where('user_id', $order->seller_id)->first();
                $sellerCuurentBalance = $seller->balance;
                $sellerNewBalance = $sellerCuurentBalance + $order->seller_earning;
                $seller->update([
                    'balance' => $sellerNewBalance
                ]);
            }
            if (!empty($logs)) {
                UserTransactionLog::insert($logs);
            }

            // Auto mark order as completed after three days
            Order::where('order_delivered_at', '<=', $currentTimePlusThreeDays)->update([
                'status' => 'completed'
            ]);
            OrderDelivery::where('status', 'publish')->where('delivery_placed_at', '<=', $currentTimePlusThreeDays)->update([
                'status' => 'completed'
            ]);
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
