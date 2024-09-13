<?php

namespace App\Console\Commands;

use App\Models\Job;
use Goutte\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ScrapeJobsFromMakeItInGermany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:scrape-jobs-from-make-it-in-germany';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape jobs from make-it-in-germany.com';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new Client();
        $base_url = 'https://www.make-it-in-germany.com/en/working-in-germany/job-listings';

        // İlk sayfayı alarak pagination bölümünden son sayfa numarasını öğrenelim
        $crawler = $client->request('GET', $base_url);

        // Son sayfa numarasını pagination'dan çek
        $lastPage = $crawler->filter('.pagination__item--last a')->attr('href');
        preg_match('/tx_solr%5Bpage%5D=(\d+)/', $lastPage, $matches);
        $lastPage = isset($matches[1]) ? (int)$matches[1] : 1;

        $this->info('Toplam Sayfa: ' . $lastPage);

        // Sayfa sayısı belirsiz olduğu için döngüyle devam edeceğiz
        $page = 1;

        while ($page <= $lastPage) {
            // Sayfa URL'si oluşturuluyor
            $url = $base_url . '?tx_solr%5Bpage%5D=' . $page . '#list';

            // Sayfadaki ilanlar çekiliyor
            $crawler = $client->request('GET', $url);

            $crawler->filter('.list__item')->each(function ($node) {
                try {
                    $title = $node->filter('h3.h5 a')->text();
                    $job_url = $node->filter('h3.h5 a')->attr('href');
                    $company = $node->filter('p')->text();
                    $location = $node->filter('.icon--pin .element')->text();
                    $date = $node->filter('.icon--calendar time')->attr('datetime');
                    $category = $node->filter('.icon--user .element')->text();

                    // Veritabanında job URL'sine göre bir kayıt arayın
                    $job = Job::where('url', $job_url)->first();

                    if (!$job) {
                        Job::create([
                            'title' => $title,
                            'slug' => Str::of($title)->slug('-'),
                            'email' => $title,
                            'url' => $job_url,
                            'company' => $company,
                            'location' => $location,
                            'date_posted' => $date,
                            'tags' => $category,
                            'description' => 'Açıklama yok',
                            'logo' => null,
                            'user_id' => 1,
                        ]);
                    }
                } catch (\Exception $e) {
                    $this->error('Hata: ' . $e->getMessage());
                }
            });

            $this->info('Sayfa ' . $page . ' iş ilanları başarıyla toplandı.');

            // Bir sonraki sayfaya geç
            $page++;
        }

        $this->info('Tüm iş ilanları başarıyla toplandı ve veritabanına kaydedildi!');

    }
}
