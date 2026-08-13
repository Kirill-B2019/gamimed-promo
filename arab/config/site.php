<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Site identity (Hub API)
    |--------------------------------------------------------------------------
    */
    'key' => env('SITE_KEY', 'arab'),
    'secret' => env('SITE_SECRET'),
    'hub_url' => rtrim((string) env('HUB_URL', 'http://127.0.0.1:8000'), '/'),

    'locales' => ['ar', 'en'],
    'default_locale' => env('APP_LOCALE', 'ar'),

    /*
    |--------------------------------------------------------------------------
    | Hub client behaviour
    |--------------------------------------------------------------------------
    */
    'content_cache_ttl' => (int) env('HUB_CONTENT_CACHE_TTL', 600), // 5–15 min
    'http_retries' => (int) env('HUB_HTTP_RETRIES', 2),
    'http_timeout' => (int) env('HUB_HTTP_TIMEOUT', 5),

    /*
    |--------------------------------------------------------------------------
    | Design-only flags (not content)
    |--------------------------------------------------------------------------
    */
    'design' => [
        'rtl_default' => true,
        'font_family' => 'Cairo, Tajawal, Almarai, Montserrat, Poppins',
        'palette' => 'gold-emerald-white-black',
        'messenger' => 'whatsapp',
        'currencies' => ['USD', 'AED', 'SAR', 'QAR'],
    ],

    /*
    |--------------------------------------------------------------------------
    | SEO defaults (pages override title/description from Hub content)
    |--------------------------------------------------------------------------
    */
    'seo' => [
        'og_image' => env('SEO_OG_IMAGE', '/images/hero-arab.jpg'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Third-party analytics (optional; Hub first-party events remain source of truth)
    |--------------------------------------------------------------------------
    */
    'analytics' => [
        'ga4' => env('GA4_MEASUREMENT_ID'),
        'yandex' => env('YANDEX_METRIKA_ID'),
        'baidu' => env('BAIDU_ANALYTICS_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Settings fallback (used when Hub is unreachable)
    |--------------------------------------------------------------------------
    */
    'settings' => [
        'presale_price_usd' => 0.05,
        'fx_rates' => [
            'USD' => 1,
            'AED' => 3.67,
            'SAR' => 3.75,
            'QAR' => 3.64,
        ],
        'feature_flags' => [
            'calculator_enabled' => true,
            'whitepaper_download' => true,
            'contact_form' => true,
        ],
        'whatsapp' => [
            'url' => 'https://wa.me/971500000000',
            'label' => 'WhatsApp',
        ],
        'social' => [
            ['key' => 'x', 'url' => 'https://x.com/gamimed', 'label' => 'X'],
            ['key' => 'telegram', 'url' => 'https://t.me/gamimed', 'label' => 'Telegram'],
            ['key' => 'snapchat', 'url' => 'https://www.snapchat.com/add/gamimed', 'label' => 'Snapchat'],
            ['key' => 'linkedin', 'url' => 'https://www.linkedin.com/company/gamimed', 'label' => 'LinkedIn'],
            ['key' => 'whatsapp', 'url' => 'https://wa.me/971500000000', 'label' => 'WhatsApp'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Section fallback payloads (locale → section_key → fields)
    |--------------------------------------------------------------------------
    */
    'fallback' => [
        'ar' => [
            'hero' => [
                'brand' => 'GAMIMED',
                'headline' => 'Pre-ICO متوافق مع الشريعة لمستثمري المنطقة',
                'subheadline' => 'منصة بلوكشين إقليمية للمستثمرين المؤهلين، وفق مبادئ التمويل الإسلامي والشفافية ودعم العملات المحلية.',
                'cta_primary' => 'احسب فرصتك',
                'cta_secondary' => 'تواصل معنا',
                'image' => '/images/hero-arab.jpg',
                'whitepaper_url' => '/downloads/gamimed-whitepaper-ar.pdf',
            ],
            'about' => [
                'title' => 'عن GAMIMED',
                'body' => 'نبني منصة بلوكشين إقليمية لإطلاق Pre-ICO متوافق مع الشريعة، يخدم المستثمرين المؤهلين في الإمارات والسعودية وقطر وأسواق الشرق الأوسط وشمال أفريقيا.',
                'highlights' => [
                    ['title' => 'امتثال شرعي', 'body' => 'هيكلة تراعي التمويل الإسلامي وتوكين الأصول بما يتوافق مع الشريعة.'],
                    ['title' => 'بلوكشين إقليمي', 'body' => 'منصة مصممة لأسواق الخليج وشمال أفريقيا، مع إفصاح واضح عن التوزيع والمخاطر.'],
                    ['title' => 'مستثمرون مؤهلون', 'body' => 'المشاركة وفق KYC/AML وللمستثمرين المؤهلين حيث يلزم القانون المحلي. تواصل عبر واتساب بالعربية والإنجليزية.'],
                ],
            ],
            'tokenomics' => [
                'title' => 'التوزيع والاقتصاد',
                'body' => 'التخصيص وسعر ما قبل البيع وجدول الاستحقاق ومنفعة الرمز تُدار من لوحة التحكم المركزية.',
                'allocations' => [
                    ['label' => 'بيع عام', 'percent' => 40],
                    ['label' => 'الفريق والمستشارون', 'percent' => 20],
                    ['label' => 'النظام البيئي', 'percent' => 25],
                    ['label' => 'احتياطي', 'percent' => 15],
                ],
                'vesting' => [
                    'بيع عام: 20٪ عند الإطلاق، والباقي خطيًا على 12 شهرًا',
                    'الفريق والمستشارون: فترة انتظار 12 شهرًا ثم استحقاق خطي على 24 شهرًا',
                    'النظام البيئي: يُفتح وفق معالم معلنة',
                    'احتياطي: مقفل ويُحرَّر بموافقة مجلس الإدارة',
                ],
                'utility' => [
                    'الوصول إلى خدمات المنصة وخصم الرسوم',
                    'المشاركة في الحوكمة لحاملي الأهلية',
                    'أولوية التخصيص في الجولات القادمة',
                    'برامج النظام البيئي للتخصيصات المقفلة',
                ],
            ],
            'roadmap' => [
                'title' => 'خارطة الطريق',
                'items' => [
                    ['period' => 'Q3 2026', 'title' => 'ما قبل الإطلاق', 'body' => 'فتح الوصول المبكر لـ Pre-ICO للمستثمرين المؤهلين في المنطقة.'],
                    ['period' => 'Q4 2026', 'title' => 'الإطلاق العام', 'body' => 'توسيع الوصول مع مراجعة امتثال مستمرة.'],
                    ['period' => 'Q1 2027', 'title' => 'التوسع الإقليمي', 'body' => 'شراكات محلية ودعم متعدد العملات.'],
                    ['period' => 'Q2 2027', 'title' => 'أولى فترات الاستحقاق', 'body' => 'بدء نوافذ الفتح للتخصيصات العامة وفق الجدول.'],
                    ['period' => 'Q3 2027', 'title' => 'النظام البيئي', 'body' => 'تكاملات الشركاء وبرامج المجتمع في المنطقة.'],
                    ['period' => 'Q4 2027', 'title' => 'إطلاق المنفعة', 'body' => 'تفعيل منفعة الرمز للمشاركة في المنصة.'],
                    ['period' => 'Q2 2028', 'title' => 'التوسع التشغيلي', 'body' => 'أسواق إضافية وتعزيز الإجراءات التشغيلية.'],
                    ['period' => 'Q4 2028', 'title' => 'النضج', 'body' => 'اكتمال طرح المنفعة ومراجعة الخارطة طويلة الأمد.'],
                ],
            ],
            'team' => [
                'title' => 'الفريق',
                'members' => [
                    ['name' => 'سارة المنصوري', 'role' => 'الرئيس التنفيذي', 'bio' => 'خبرة في التمويل الإسلامي والتقنية المالية.', 'linkedin' => 'https://www.linkedin.com/in/example-sara'],
                    ['name' => 'عمر الحربي', 'role' => 'مدير المنتج', 'bio' => 'قيادة منتجات B2B في أسواق الخليج.', 'linkedin' => 'https://www.linkedin.com/in/example-omar'],
                    ['name' => 'ليلى القحطاني', 'role' => 'الامتثال', 'bio' => 'متخصصة في الأطر التنظيمية الإقليمية.', 'linkedin' => 'https://www.linkedin.com/in/example-layla'],
                ],
            ],
            'partners' => [
                'title' => 'الشركاء',
                'items' => [
                    ['name' => 'MENA Ventures'],
                    ['name' => 'Gulf FinTech Lab'],
                    ['name' => 'Regional Compliance Partners'],
                ],
            ],
            'security' => [
                'title' => 'الأمان والامتثال',
                'body' => 'التدقيق وإجراءات KYC/AML وحدود المستثمرين المؤهلين جزء من خطة الإطلاق — لا نموذج تحقق على هذا الموقع.',
                'points' => [
                    'تدقيق مستقل للعقود الذكية قبل الإطلاق',
                    'إجراءات KYC/AML عند الانضمام وفق المتطلبات الإقليمية',
                    'المشاركة للمستثمرين المؤهلين حيث يلزم القانون المحلي',
                    'حفظ آمن للمفاتيح وضوابط وصول وإجراءات تشغيل موثّقة',
                ],
            ],
            'sharia' => [
                'title' => 'الامتثال الشرعي',
                'body' => 'نهج لتوكين الأصول بما يتوافق مع الشريعة لأسواق المنطقة، مع مراجعة مستشارين في التمويل الإسلامي.',
                'principles' => [
                    ['title' => 'تجنب الربا', 'body' => 'هيكلة لا تعتمد على فوائد تقليدية في آليات التوزيع.'],
                    ['title' => 'شفافية الأصول', 'body' => 'إفصاح واضح عن استخدام الأموال وعوامل المخاطر.'],
                    ['title' => 'مراجعة مستقلة', 'body' => 'استشارة مستشارين في التمويل الإسلامي ضمن خارطة الطريق.'],
                ],
            ],
            'testimonials' => [
                'title' => 'آراء من المنطقة',
                'items' => [
                    ['quote' => 'وضوح التوزيع والامتثال الشرعي يسهّل تقييم Pre-ICO قبل المشاركة.', 'author' => 'يوسف النجار', 'role' => 'معلّق التقنية المالية — دبي'],
                    ['quote' => 'حاسبة العملات المحلية والإفصاح عن المخاطر مهمان للمستثمر المؤهل.', 'author' => 'د. أمينة الحارثي', 'role' => 'مستشارة تمويل إسلامي — الرياض'],
                ],
            ],
            'faq' => [
                'title' => 'الأسئلة الشائعة',
                'items' => [
                    ['question' => 'من يمكنه المشاركة؟', 'answer' => 'المستثمرون المؤهلون في المنطقة. قد تتطلب المشاركة KYC/AML وأن تقتصر على المستثمرين المؤهلين وفق القواعد المحلية.'],
                    ['question' => 'كيف أتواصل مع الفريق؟', 'answer' => 'عبر نموذج التواصل أو واتساب — نرد خلال أيام العمل.'],
                    ['question' => 'هل الطرح متوافق مع الشريعة؟', 'answer' => 'نعمل على توكين متوافق مع الشريعة مع مستشارين في التمويل الإسلامي؛ التفاصيل في قسم الامتثال الشرعي.'],
                ],
            ],
            'contact' => [
                'title' => 'تواصل معنا',
                'body' => 'اترك بياناتك وسنتواصل عبر واتساب أو البريد.',
            ],
            'footer' => [
                'copyright' => 'GAMIMED. جميع الحقوق محفوظة.',
                'disclaimer' => 'لأغراض معلوماتية فقط، وليس عرضًا أو استشارة استثمارية. الأصول الرقمية تنطوي على مخاطر خسارة. قد تتطلب المشاركة إجراءات KYC/AML وأن تقتصر على المستثمرين المؤهلين. قد تنطبق أطر تنظيمية إقليمية تشمل DFSA (دبي) وADGM (أبوظبي) وCMA (السعودية). لا يدّعي هذا الموقع حصوله على ترخيص من تلك الجهات.',
                'links' => [
                    ['label' => 'عن المشروع', 'url' => '#about'],
                    ['label' => 'الأسئلة الشائعة', 'url' => '#faq'],
                    ['label' => 'تواصل', 'url' => '#contact'],
                ],
            ],
        ],
        'en' => [
            'hero' => [
                'brand' => 'GAMIMED',
                'headline' => 'Sharia-compliant Pre-ICO for MENA investors',
                'subheadline' => 'A regional blockchain platform for qualified investors, with Islamic-finance alignment, transparent allocation, and local-currency access.',
                'cta_primary' => 'Calculate allocation',
                'cta_secondary' => 'Contact us',
                'image' => '/images/hero-arab.jpg',
                'whitepaper_url' => '/downloads/gamimed-whitepaper-en.pdf',
            ],
            'about' => [
                'title' => 'About GAMIMED',
                'body' => 'GAMIMED is a regional blockchain platform preparing a Sharia-compliant Pre-ICO for qualified investors across the UAE, Saudi Arabia, Qatar, and the wider MENA region.',
                'highlights' => [
                    ['title' => 'Sharia alignment', 'body' => 'Asset tokenization structured with Islamic-finance principles in mind.'],
                    ['title' => 'Regional blockchain', 'body' => 'Built for Gulf and North Africa markets, with clear allocation and risk disclosure.'],
                    ['title' => 'Qualified investors', 'body' => 'KYC/AML and qualified-investor limits apply where local rules require them. WhatsApp support in Arabic and English.'],
                ],
            ],
            'tokenomics' => [
                'title' => 'Allocation & economics',
                'body' => 'Allocation, pre-sale price, vesting, and token utility are managed from the central Hub.',
                'allocations' => [
                    ['label' => 'Public sale', 'percent' => 40],
                    ['label' => 'Team & advisors', 'percent' => 20],
                    ['label' => 'Ecosystem', 'percent' => 25],
                    ['label' => 'Reserve', 'percent' => 15],
                ],
                'vesting' => [
                    'Public sale: 20% at launch, remainder linearly over 12 months',
                    'Team & advisors: 12-month cliff, then 24-month linear vesting',
                    'Ecosystem: unlocked against published milestones',
                    'Reserve: locked pending board-approved release',
                ],
                'utility' => [
                    'Access to platform services and fee discounts',
                    'Governance participation for qualified holders',
                    'Priority allocation in future offerings',
                    'Ecosystem programs for locked allocations',
                ],
            ],
            'roadmap' => [
                'title' => 'Roadmap',
                'items' => [
                    ['period' => 'Q3 2026', 'title' => 'Pre-launch', 'body' => 'Early Pre-ICO access for qualified regional investors.'],
                    ['period' => 'Q4 2026', 'title' => 'Public launch', 'body' => 'Broader access with ongoing compliance review.'],
                    ['period' => 'Q1 2027', 'title' => 'Regional expansion', 'body' => 'Local partnerships and multi-currency support.'],
                    ['period' => 'Q2 2027', 'title' => 'First unlocks', 'body' => 'Initial vesting windows for public-sale allocations.'],
                    ['period' => 'Q3 2027', 'title' => 'Ecosystem', 'body' => 'Partner integrations and community programs.'],
                    ['period' => 'Q4 2027', 'title' => 'Utility rollout', 'body' => 'Token utility for platform participation.'],
                    ['period' => 'Q2 2028', 'title' => 'Scale', 'body' => 'Additional markets and operational hardening.'],
                    ['period' => 'Q4 2028', 'title' => 'Maturity', 'body' => 'Full utility rollout and long-term roadmap review.'],
                ],
            ],
            'team' => [
                'title' => 'Team',
                'members' => [
                    ['name' => 'Sara Al-Mansouri', 'role' => 'CEO', 'bio' => 'Islamic finance and fintech background.', 'linkedin' => 'https://www.linkedin.com/in/example-sara'],
                    ['name' => 'Omar Al-Harbi', 'role' => 'Head of Product', 'bio' => 'B2B product leadership in the Gulf.', 'linkedin' => 'https://www.linkedin.com/in/example-omar'],
                    ['name' => 'Layla Al-Qahtani', 'role' => 'Compliance', 'bio' => 'Regional regulatory frameworks.', 'linkedin' => 'https://www.linkedin.com/in/example-layla'],
                ],
            ],
            'partners' => [
                'title' => 'Partners',
                'items' => [
                    ['name' => 'MENA Ventures'],
                    ['name' => 'Gulf FinTech Lab'],
                    ['name' => 'Regional Compliance Partners'],
                ],
            ],
            'security' => [
                'title' => 'Security & compliance',
                'body' => 'Independent audit, KYC/AML, and qualified-investor limits are part of the launch plan — this page is copy, not an onboarding form.',
                'points' => [
                    'Independent smart-contract audit before launch',
                    'KYC/AML procedures at onboarding, aligned with regional requirements',
                    'Participation limited to qualified investors where local law requires it',
                    'Secure key custody, access control, and documented operating procedures',
                ],
            ],
            'sharia' => [
                'title' => 'Sharia alignment',
                'body' => 'A Sharia-compliant approach to asset tokenization for MENA markets, with Islamic-finance advisors on the roadmap.',
                'principles' => [
                    ['title' => 'No riba structures', 'body' => 'Allocation mechanics avoid conventional interest-based models.'],
                    ['title' => 'Asset transparency', 'body' => 'Clear disclosure of fund use and risk factors.'],
                    ['title' => 'Independent review', 'body' => 'Islamic finance advisors engaged on the roadmap.'],
                ],
            ],
            'testimonials' => [
                'title' => 'Regional voices',
                'items' => [
                    ['quote' => 'Clear allocation and Sharia alignment make the Pre-ICO easier to evaluate.', 'author' => 'Yousef Al-Najjar', 'role' => 'MENA fintech commentator — Dubai'],
                    ['quote' => 'Local-currency planning and named risk disclosure matter for qualified investors.', 'author' => 'Dr. Amina Al-Harthy', 'role' => 'Islamic finance advisor — Riyadh'],
                ],
            ],
            'faq' => [
                'title' => 'FAQ',
                'items' => [
                    ['question' => 'Who can participate?', 'answer' => 'Qualified investors in the region. Participation may require KYC/AML and may be limited to qualified investors under local rules.'],
                    ['question' => 'How do I reach the team?', 'answer' => 'Use the contact form or WhatsApp — we reply on business days.'],
                    ['question' => 'Is the offering Sharia-aligned?', 'answer' => 'We pursue Sharia-compliant tokenization with Islamic finance advisors; see the Sharia section for details.'],
                ],
            ],
            'contact' => [
                'title' => 'Contact us',
                'body' => 'Leave your details and we will follow up via WhatsApp or email.',
            ],
            'footer' => [
                'copyright' => 'GAMIMED. All rights reserved.',
                'disclaimer' => 'Information only — not an offer, solicitation, or investment advice. Digital assets involve a risk of loss. Participation may require KYC/AML and may be limited to qualified investors. Regional frameworks that may apply include DFSA (Dubai), ADGM (Abu Dhabi), and CMA (Saudi Arabia). This site does not claim a licence from those authorities.',
                'links' => [
                    ['label' => 'About', 'url' => '#about'],
                    ['label' => 'FAQ', 'url' => '#faq'],
                    ['label' => 'Contact', 'url' => '#contact'],
                ],
            ],
        ],
    ],
];
