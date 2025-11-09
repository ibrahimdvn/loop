<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CacheService;

class CacheWarmupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warmup {--clear : Clear existing cache before warming up}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm up the application cache for better performance';

    protected $cacheService;

    /**
     * Create a new command instance.
     */
    public function __construct(CacheService $cacheService)
    {
        parent::__construct();
        $this->cacheService = $cacheService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔥 Starting cache warmup...');

        if ($this->option('clear')) {
            $this->info('🧹 Clearing existing cache...');
            $this->cacheService->clearAllCache();
        }

        $this->info('📊 Caching popular posts...');
        $this->cacheService->cachePopularPosts(20);

        $this->info('🏷️ Caching trending hashtags...');
        $this->cacheService->cacheTrendingHashtags(15);

        $this->info('👥 Caching user suggestions and stats...');
        $activeUsers = \App\Models\User::whereHas('posts')->limit(50)->get();
        $bar = $this->output->createProgressBar($activeUsers->count());
        
        foreach ($activeUsers as $user) {
            $this->cacheService->cacheUserSuggestions($user->id, 10);
            $this->cacheService->cacheUserStats($user->id);
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();

        $this->info('📈 Getting cache statistics...');
        $stats = $this->cacheService->getCacheStats();
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Memory Used', $stats['used_memory'] ?? 'N/A'],
                ['Connected Clients', $stats['connected_clients'] ?? 'N/A'],
                ['Commands Processed', $stats['total_commands_processed'] ?? 'N/A'],
                ['Cache Hits', $stats['keyspace_hits'] ?? 'N/A'],
                ['Cache Misses', $stats['keyspace_misses'] ?? 'N/A'],
            ]
        );

        $this->info('✅ Cache warmup completed successfully!');
        
        return Command::SUCCESS;
    }
}
