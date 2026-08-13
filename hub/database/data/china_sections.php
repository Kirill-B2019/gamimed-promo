<?php

/**
 * Initial CHINA site-scoped payloads (zh_CN + en). Mirrors china/config/site.php fallback.
 * Public copy must not use ICO / cryptocurrency wording.
 *
 * @return array<string, array<string, array<string, mixed>>>
 */
return [
    'zh_CN' => [
        'hero' => [
            'brand' => 'GAMIMED',
            'headline' => '新一代区块链技术，服务亚太参与者',
            'subheadline' => '以技术为核心的数字资产与实物资产通证化平台，强调实用、安全与合规，而非投机。',
            'cta_primary' => '计算份额',
            'cta_secondary' => '联系我们',
            'image' => '/images/hero-china.jpg',
            'whitepaper_url' => '/downloads/gamimed-overview-zh.pdf',
        ],
        'about' => [
            'title' => '关于 GAMIMED',
            'body' => '我们构建面向亚太的区块链技术平台，支持数字资产与实物资产通证化，服务合格参与者，兼顾创新、安全与区域合规。',
            'highlights' => [
                ['title' => '技术先行', 'body' => '以可扩展架构、安全审计与可验证流程支撑平台发布。'],
                ['title' => '数字资产通证化', 'body' => '实物资产通证化路径面向合格参与者，强调用途与透明度，而非投机。'],
                ['title' => '本地支持', 'body' => '通过微信沟通，团队支持中文与英文。'],
            ],
        ],
        'tokenomics' => [
            'title' => '分配与经济模型',
            'body' => '份额分配、预售价格、释放计划与数字单元用途由中央控制台统一管理。',
            'allocations' => [
                ['label' => '公开发售', 'percent' => 40],
                ['label' => '团队与顾问', 'percent' => 20],
                ['label' => '生态系统', 'percent' => 25],
                ['label' => '储备', 'percent' => 15],
            ],
            'vesting' => [
                '公开发售：上线时释放 20%，其余 12 个月线性释放',
                '团队与顾问：12 个月锁定期，其后 24 个月线性释放',
                '生态系统：按已公布的产品里程碑释放',
                '储备：锁定，经董事会批准后释放',
            ],
            'utility' => [
                '使用平台服务并享受费用优惠',
                '合格参与者加入平台计划',
                '后续份额的优先分配',
                '锁定份额可参与生态计划',
            ],
        ],
        'roadmap' => [
            'title' => '路线图',
            'items' => [
                ['period' => 'Q3 2026', 'title' => '预发布', 'body' => '向合格的区域参与者开放早期访问。'],
                ['period' => 'Q4 2026', 'title' => '公开发布', 'body' => '扩大访问范围，并持续进行合规审查。'],
                ['period' => 'Q1 2027', 'title' => '区域拓展', 'body' => '本地合作与多币种支持。'],
                ['period' => 'Q2 2027', 'title' => '首次释放', 'body' => '按计划开始公开发售份额的释放窗口。'],
                ['period' => 'Q3 2027', 'title' => '生态合作', 'body' => '合作伙伴集成与区域社区计划。'],
                ['period' => 'Q4 2027', 'title' => '平台用途', 'body' => '启用数字单元用于平台参与。'],
                ['period' => 'Q2 2028', 'title' => '规模化', 'body' => '拓展市场并强化运营流程。'],
                ['period' => 'Q4 2028', 'title' => '成熟运营', 'body' => '完成用途落地并进行长期路线图回顾。'],
            ],
        ],
        'team' => [
            'title' => '团队',
            'members' => [
                ['name' => '陈伟', 'role' => '首席执行官', 'bio' => '金融科技与亚太市场产品经验。', 'linkedin' => 'https://www.linkedin.com/in/example-wei-chen'],
                ['name' => '林晓', 'role' => '产品负责人', 'bio' => 'B2B 平台与增长产品管理。', 'linkedin' => 'https://www.linkedin.com/in/example-xiao-lin'],
                ['name' => '王磊', 'role' => '技术负责人', 'bio' => '分布式系统与平台安全。', 'linkedin' => 'https://www.linkedin.com/in/example-lei-wang'],
            ],
        ],
        'partners' => [
            'title' => '合作伙伴',
            'items' => [
                ['name' => 'APAC Ventures'],
                ['name' => 'Greater China Tech Lab'],
                ['name' => 'Regional Compliance Partners'],
            ],
        ],
        'security' => [
            'title' => '安全与合规',
            'body' => '独立审计、KYC/AML 与合格参与者限制是发布计划的一部分——本页为说明文本，并非核验表单。',
            'points' => [
                '上线前进行独立的平台与合约审计',
                '合格参与者办理 KYC/AML 身份核验',
                '在当地规则要求时，仅面向合格投资者开放参与',
                '密钥托管、审计日志与访问控制纳入技术架构',
            ],
        ],
        'technology' => [
            'title' => '技术架构',
            'body' => '分层技术栈，强调可扩展性、安全与可审计性，支撑数字资产与实物资产通证化。',
            'layers' => [
                ['title' => '应用层', 'body' => '面向参与者的门户、份额计算器与中英双语体验。'],
                ['title' => '接口层', 'body' => '站点通过 HTTPS JSON 与中央控制台同步内容与线索。'],
                ['title' => '数据层', 'body' => '内容、预售价格与功能开关由控制台统一管理。'],
                ['title' => '安全层', 'body' => '访问控制、审计日志与密钥管理。'],
                ['title' => '基础设施', 'body' => '区域节点部署、短时缓存与发布流程。'],
            ],
            'capabilities' => ['门户', '计算器', '微信触达', '区域托管', '审计日志'],
        ],
        'testimonials' => [
            'title' => '专家评价',
            'items' => [
                ['quote' => '技术架构说明和数字资产通证化路径比口号更有参考价值。', 'author' => '李伟', 'role' => '区块链架构师 — 上海'],
                ['quote' => '人民币测算与合规表述有助于合格参与者做技术评估。', 'author' => '张敏', 'role' => '数字资产研究员 — 新加坡'],
            ],
        ],
        'faq' => [
            'title' => '常见问题',
            'items' => [
                ['question' => '谁可以参与？', 'answer' => '符合当地合规要求的合格参与者。参与可能需要 KYC/AML 身份核验，并在适用规则下仅面向合格投资者。'],
                ['question' => '如何联系团队？', 'answer' => '通过联系表单或微信，我们会在工作日回复。'],
                ['question' => '平台基于什么技术？', 'answer' => '详见技术架构：应用、数据与安全分层，支撑数字资产与实物资产通证化。'],
            ],
        ],
        'contact' => [
            'title' => '联系我们',
            'body' => '留下您的信息，我们将通过微信或邮件跟进。',
        ],
        'footer' => [
            'copyright' => 'GAMIMED. 保留所有权利。',
            'disclaimer' => '本站介绍面向数字资产与实物资产通证化的技术平台，不构成要约、招揽或投资建议。内容强调技术与实际用途，而非投机。参与可能需要身份核验，并在适用规则下仅面向合格参与者。',
            'links' => [
                ['label' => '关于项目', 'url' => '#about'],
                ['label' => '技术架构', 'url' => '#technology'],
                ['label' => '常见问题', 'url' => '#faq'],
                ['label' => '联系', 'url' => '#contact'],
            ],
        ],
    ],
    'en' => [
        'hero' => [
            'brand' => 'GAMIMED',
            'headline' => 'Next-generation blockchain technology for APAC',
            'subheadline' => 'A technology platform for digital assets and real-world asset tokenization — focused on utility, security, and regional compliance, not speculation.',
            'cta_primary' => 'Calculate allocation',
            'cta_secondary' => 'Contact us',
            'image' => '/images/hero-china.jpg',
            'whitepaper_url' => '/downloads/gamimed-overview-en.pdf',
        ],
        'about' => [
            'title' => 'About GAMIMED',
            'body' => 'We build blockchain infrastructure for digital assets and the tokenization of real-world assets, with a technology-first approach for qualified participants across APAC.',
            'highlights' => [
                ['title' => 'Technology-first', 'body' => 'Release plan grounded in scalable architecture, security review, and auditability.'],
                ['title' => 'Digital-asset tokenization', 'body' => 'A path for real-world asset tokenization aimed at qualified participants — utility and transparency, not speculation.'],
                ['title' => 'Local support', 'body' => 'WeChat outreach and Chinese/English support.'],
            ],
        ],
        'tokenomics' => [
            'title' => 'Allocation & economics',
            'body' => 'Allocation, pre-launch price, release schedule, and digital-unit utility are managed from the central Hub.',
            'allocations' => [
                ['label' => 'Public sale', 'percent' => 40],
                ['label' => 'Team & advisors', 'percent' => 20],
                ['label' => 'Ecosystem', 'percent' => 25],
                ['label' => 'Reserve', 'percent' => 15],
            ],
            'vesting' => [
                'Public allocation: 20% at launch, remainder linearly over 12 months',
                'Team & advisors: 12-month cliff, then 24-month linear release',
                'Ecosystem: released against published product milestones',
                'Reserve: locked pending board-approved release',
            ],
            'utility' => [
                'Access to platform services and fee discounts',
                'Participation in platform programs for qualified users',
                'Priority allocation in future offerings',
                'Ecosystem programs for locked allocations',
            ],
        ],
        'roadmap' => [
            'title' => 'Roadmap',
            'items' => [
                ['period' => 'Q3 2026', 'title' => 'Pre-launch', 'body' => 'Early access for qualified regional participants.'],
                ['period' => 'Q4 2026', 'title' => 'Public launch', 'body' => 'Broader access with ongoing compliance review.'],
                ['period' => 'Q1 2027', 'title' => 'Regional expansion', 'body' => 'Local partnerships and multi-currency support.'],
                ['period' => 'Q2 2027', 'title' => 'First unlocks', 'body' => 'Initial release windows for public allocations.'],
                ['period' => 'Q3 2027', 'title' => 'Ecosystem', 'body' => 'Partner integrations and community programs.'],
                ['period' => 'Q4 2027', 'title' => 'Platform programs', 'body' => 'Digital-unit utility for platform participation.'],
                ['period' => 'Q2 2028', 'title' => 'Scale', 'body' => 'Additional markets and operational hardening.'],
                ['period' => 'Q4 2028', 'title' => 'Maturity', 'body' => 'Full utility rollout and long-term roadmap review.'],
            ],
        ],
        'team' => [
            'title' => 'Team',
            'members' => [
                ['name' => 'Wei Chen', 'role' => 'CEO', 'bio' => 'Fintech and APAC product background.', 'linkedin' => 'https://www.linkedin.com/in/example-wei-chen'],
                ['name' => 'Xiao Lin', 'role' => 'Head of Product', 'bio' => 'B2B platform and growth product leadership.', 'linkedin' => 'https://www.linkedin.com/in/example-xiao-lin'],
                ['name' => 'Lei Wang', 'role' => 'Head of Technology', 'bio' => 'Distributed systems and platform security.', 'linkedin' => 'https://www.linkedin.com/in/example-lei-wang'],
            ],
        ],
        'partners' => [
            'title' => 'Partners',
            'items' => [
                ['name' => 'APAC Ventures'],
                ['name' => 'Greater China Tech Lab'],
                ['name' => 'Regional Compliance Partners'],
            ],
        ],
        'security' => [
            'title' => 'Security & compliance',
            'body' => 'Independent audit, KYC/AML, and qualified-participant limits are part of the launch plan — this page is copy, not an onboarding form.',
            'points' => [
                'Independent platform and contract audit before launch',
                'KYC/AML identity checks for qualified participants',
                'Participation limited to qualified investors where local rules require it',
                'Key custody, audit logs, and access control in the technology stack',
            ],
        ],
        'technology' => [
            'title' => 'Technology stack',
            'body' => 'A layered stack focused on scalability, security, and auditability — supporting digital assets and real-world asset tokenization.',
            'layers' => [
                ['title' => 'Application', 'body' => 'Participant-facing portal, allocation calculator, and bilingual experience.'],
                ['title' => 'Interface', 'body' => 'The site syncs content and leads with the central Hub over HTTPS JSON.'],
                ['title' => 'Data', 'body' => 'Content, pre-sale pricing, and feature flags are managed from the Hub.'],
                ['title' => 'Security', 'body' => 'Access control, audit logs, and key management.'],
                ['title' => 'Infrastructure', 'body' => 'Regional hosting, short-lived cache, and a controlled release process.'],
            ],
            'capabilities' => ['Portal', 'Calculator', 'WeChat outreach', 'Regional hosting', 'Audit logs'],
        ],
        'testimonials' => [
            'title' => 'Expert voices',
            'items' => [
                ['quote' => 'The technology stack and tokenization path are more useful than slogans.', 'author' => 'Li Wei', 'role' => 'Blockchain architect — Shanghai'],
                ['quote' => 'CNY planning and compliance-aware wording help qualified participants evaluate the platform.', 'author' => 'Zhang Min', 'role' => 'Digital-asset researcher — Singapore'],
            ],
        ],
        'faq' => [
            'title' => 'FAQ',
            'items' => [
                ['question' => 'Who can participate?', 'answer' => 'Qualified participants, subject to local compliance. Participation may require KYC/AML identity checks and may be limited to qualified investors under applicable rules.'],
                ['question' => 'How do I reach the team?', 'answer' => 'Use the contact form or WeChat — we reply on business days.'],
                ['question' => 'What technology powers the platform?', 'answer' => 'See the technology stack for application, data, and security layers that support digital assets and real-world asset tokenization.'],
            ],
        ],
        'contact' => [
            'title' => 'Contact us',
            'body' => 'Leave your details and we will follow up via WeChat or email.',
        ],
        'footer' => [
            'copyright' => 'GAMIMED. All rights reserved.',
            'disclaimer' => 'This site describes a technology platform for digital assets and real-world asset tokenization. It is not an offer, solicitation, or investment advice. Focus is on technology and utility, not speculation. Participation may require identity checks and may be limited to qualified participants under applicable law.',
            'links' => [
                ['label' => 'About', 'url' => '#about'],
                ['label' => 'Technology', 'url' => '#technology'],
                ['label' => 'FAQ', 'url' => '#faq'],
                ['label' => 'Contact', 'url' => '#contact'],
            ],
        ],
    ],
];
