<?php

namespace App\Console\Commands;

use App\Models\Link;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PublishScheduledLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'links:publish-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish scheduled links whose published_at date has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Yayınlanması gereken linkleri bul
        // 1. disabled olan (henüz aktif edilmemiş)
        // 2. published_at alanı geçmişte olan (yayınlanma zamanı gelmiş)
        // 3. published_at alanı null olmayanlar (yayınlanma zamanı belirlenmiş)
        $links = Link::where('disabled', true)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', $now)
                    ->get();

        $count = $links->count();

        if ($count > 0) {
            foreach ($links as $link) {
                // Linki aktif et ve published_at değerini temizle
                $link->disabled = false;
                $link->published_at = null; // Aktif olduğunda published_at değerini temizle
                $link->save();

                $this->info("Scheduled link published: {$link->alias} (ID: {$link->id})");

                // Log oluştur
                Log::info("Scheduled link published by system: {$link->alias}", [
                    'link_id' => $link->id,
                    'tenant_id' => $link->tenant_id
                ]);
            }

            $this->info("Total {$count} scheduled links published.");
        } else {
            $this->info("No scheduled links found to publish.");
        }

        return Command::SUCCESS;
    }
}
