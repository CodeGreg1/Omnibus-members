<?php

namespace Modules\Pages\Database\Seeders;

use Modules\Pages\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CreatePricingPageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $page = Page::create([
            'name' => 'Pricing Plan',
            'description' => 'Pricing Plan page',
            'type' => 'section',
            'slug' => 'pricing',
            'status' => 'published',
            'page_title' => 'Pricing Plan',
            'page_description' => 'Pricing Plan',
            'has_breadcrumb' => 1
        ]);

        $array = $this->getSections();

        foreach ($array as $key => $section) {
            $section['order'] = $key + 1;
            $data = '{}';
            if (isset($section['data'])) {
                $data = json_encode($section['data']);
            }
            $section['data'] = $data;
            $page->sections()->create($section);
        }
    }

    protected function getSections()
    {
        return [
            [
                "order" => 1,
                "heading" => "Our pricing",
                "background_color" => "white",
                "template" => "pricing",
                "data" => [],
            ],
            [
                "order" => 2,
                "heading" => "Faqs",
                "background_color" => "gray",
                "template" => "faq",
                "data" => [
                    "items" => [
                        [
                            "id" => 43002718764024100,
                            "title" => "I forgot my password, what should I do?",
                            "content" => "Visit the password reset page, type in your email address, and click the Reset button. Visit the password reset page, type in your email address, and click the Reset button."
                        ],
                        [
                            "id" => 10627224095772202,
                            "title" => "Can I cancel my paid subscription at any time?",
                            "content" => "Yes, you can cancel your subscription anytime, and we will not charge you after the expiration date."
                        ],
                        [
                            "id" => 367315287263683300,
                            "title" => "Is the payment secure?",
                            "content" => "Of course, we have opted for Stripe & Paypal, the safest solution for all online payments."
                        ],
                        [
                            "id" => 740244444466422800,
                            "title" => "Do you have an affiliate program?",
                            "content" => "In the account menu click \"My Affiliate\" then it will redirect to /user/affiliates URL then click the button \"Become a member\" then once the admin approves your request you will automatically use the affiliate program."
                        ],
                        [
                            "id" => 724144418100592800,
                            "title" => "Whom do I contact for other queries?",
                            "content" => "You can reach us for any queries either by using the contact us page or by emailing email@domain.com"
                        ],
                        [
                            "id" => 12347384358095716,
                            "title" => "How long do you respond to my query?",
                            "content" => "Generally, you will get replied in 1-2 business days."
                        ]
                    ]
                ],
            ]
        ];
    }
}
