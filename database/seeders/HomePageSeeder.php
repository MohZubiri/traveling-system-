<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class HomePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::create([
            'title' => 'الصفحة الرئيسية',
            'slug' => 'home',
            'meta_description' => 'نظام إدارة السفر - خدمات حجز وتنظيم الرحلات',
            'meta_keywords' => 'حجز, سفر, تأشيرات, رحلات',
            'sections' => [
                [
                    'type' => 'hero',
                    'content' => [
                        'title' => 'رحلتك تبدأ معنا',
                        'description' => 'نقدم لك أفضل خدمات السفر والسياحة مع باقة متكاملة من الخدمات',
                        'button_text' => 'ابدأ الآن',
                        'button_link' => '/services',
                        'image' => '/images/hero-image.jpg'
                    ]
                ]
            ],
            'status' => true
        ]);
    }
}
