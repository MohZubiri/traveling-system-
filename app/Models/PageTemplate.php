<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'thumbnail',
        'sections'
    ];

    protected $casts = [
        'sections' => 'array'
    ];

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? Storage::url($this->thumbnail) : null;
    }

    public static function getDefaultTemplates()
    {
        return [
            [
                'name' => 'الصفحة الرئيسية',
                'description' => 'قالب للصفحة الرئيسية مع قسم رئيسي وخدمات مميزة وإحصائيات',
                'sections' => [
                    [
                        'type' => 'hero',
                        'title' => 'مرحباً بك في نظام إدارة السفر',
                        'subtitle' => 'نقدم لك أفضل خدمات السفر والسياحة',
                        'background_color' => '#1a56db',
                        'text_color' => '#ffffff'
                    ],
                    [
                        'type' => 'features',
                        'title' => 'خدماتنا المميزة',
                        'subtitle' => 'نقدم مجموعة متكاملة من الخدمات',
                        'content' => json_encode([
                            [
                                'icon' => 'fas fa-plane',
                                'title' => 'حجز تذاكر الطيران',
                                'description' => 'حجز تذاكر الطيران بأفضل الأسعار'
                            ],
                            [
                                'icon' => 'fas fa-passport',
                                'title' => 'خدمات التأشيرات',
                                'description' => 'استخراج التأشيرات لجميع الدول'
                            ],
                            [
                                'icon' => 'fas fa-hotel',
                                'title' => 'حجز الفنادق',
                                'description' => 'حجز الفنادق في جميع أنحاء العالم'
                            ]
                        ])
                    ],
                    [
                        'type' => 'stats',
                        'title' => 'إحصائيات',
                        'subtitle' => 'نفتخر بخدمة عملائنا',
                        'content' => json_encode([
                            [
                                'value' => 1000,
                                'label' => 'عميل سعيد',
                                'suffix' => '+'
                            ],
                            [
                                'value' => 50,
                                'label' => 'وجهة سياحية',
                                'suffix' => '+'
                            ],
                            [
                                'value' => 95,
                                'label' => 'نسبة رضا العملاء',
                                'suffix' => '%'
                            ]
                        ])
                    ]
                ]
            ],
            [
                'name' => 'صفحة عن الشركة',
                'description' => 'قالب لصفحة تعريفية عن الشركة',
                'sections' => [
                    [
                        'type' => 'hero',
                        'title' => 'تعرف علينا',
                        'subtitle' => 'شريكك الموثوق في السفر والسياحة',
                        'background_color' => '#1a56db',
                        'text_color' => '#ffffff'
                    ],
                    [
                        'type' => 'content',
                        'title' => 'من نحن',
                        'content' => 'نحن شركة رائدة في مجال السفر والسياحة...'
                    ],
                    [
                        'type' => 'timeline',
                        'title' => 'مسيرتنا',
                        'content' => json_encode([
                            [
                                'date' => '2020',
                                'title' => 'تأسيس الشركة',
                                'description' => 'بداية رحلتنا في عالم السفر والسياحة'
                            ],
                            [
                                'date' => '2021',
                                'title' => 'توسيع الخدمات',
                                'description' => 'إضافة خدمات جديدة لتلبية احتياجات عملائنا'
                            ],
                            [
                                'date' => '2022',
                                'title' => 'انطلاقة جديدة',
                                'description' => 'افتتاح فروع جديدة وتطوير الخدمات'
                            ]
                        ])
                    ]
                ]
            ],
            [
                'name' => 'صفحة الخدمات',
                'description' => 'قالب لعرض الخدمات والباقات',
                'sections' => [
                    [
                        'type' => 'hero',
                        'title' => 'خدماتنا',
                        'subtitle' => 'اكتشف مجموعة خدماتنا المتميزة',
                        'background_color' => '#1a56db',
                        'text_color' => '#ffffff'
                    ],
                    [
                        'type' => 'features',
                        'title' => 'باقاتنا',
                        'content' => json_encode([
                            [
                                'icon' => 'fas fa-star',
                                'title' => 'الباقة الذهبية',
                                'description' => 'خدمات شاملة ومميزة'
                            ],
                            [
                                'icon' => 'fas fa-gem',
                                'title' => 'الباقة الماسية',
                                'description' => 'تجربة سفر فاخرة'
                            ]
                        ])
                    ],
                    [
                        'type' => 'cta',
                        'title' => 'احجز الآن',
                        'subtitle' => 'احصل على أفضل العروض',
                        'button_text' => 'احجز الآن',
                        'button_url' => '/booking'
                    ]
                ]
            ]
        ];
    }
}
