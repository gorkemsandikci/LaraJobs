<?php

namespace App\Console\Commands;

use App\Models\Job;
use Goutte\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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

        $crawler = $client->request('GET', 'https://www.make-it-in-germany.com/en/working-in-germany/job-listings');
        // HTML yapısının doğru olup olmadığını kontrol edin
        $crawler->filter('.list__item')->each(function ($node) {
            try {
                $title = $node->filter('h3.h5 a')->text();
                $jobUrl = $node->filter('h3.h5 a')->attr('href');
                $company = $node->filter('p')->text();
                $location = $node->filter('.icon--pin .element')->text();
                $date = $node->filter('.icon--calendar time')->attr('datetime');
                $category = $node->filter('.icon--user .element')->text();

                $job = Job::where('website', $jobUrl)->first();

//                dd($title, $jobUrl, $company, $location, $date, $category);
                // Veritabanına kaydet
                if ($job) {
                    $job->update([
                        'title' => $title,
                        'email' => $title, // Bu alan doğru şekilde doldurulmalı
                        'company' => $company,
                        'location' => $location,
                        'date_posted' => $date,
                        'tags' => $category,
                        'description' => 'Açıklama yok',
                        'logo' => null,
                        'user_id' => 1, // Varsayılan kullanıcı ID'si
                    ]);
                } else {
                    // Kayıt mevcut değilse yeni bir kayıt oluştur
                    Job::create([
                        'title' => $title,
                        'email' => $title, // Bu alan doğru şekilde doldurulmalı
                        'website' => $jobUrl,
                        'company' => $company,
                        'location' => $location,
                        'date_posted' => $date,
                        'tags' => $category,
                        'description' => 'Açıklama yok',
                        'logo' => null,
                        'user_id' => 1, // Varsayılan kullanıcı ID'si
                    ]);
                }
            } catch (\Exception $e) {
//                $this->error('Hata: ' . $e->getMessage());
            }
        });

        $this->info('İş ilanları başarıyla toplandı ve veritabanına kaydedildi!');
    }
}
