<?php

namespace Modules\Products\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Cashier\Facades\Cashier;
use Modules\Products\Models\Product;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateDummyProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:create-dummy {count}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $count = $this->argument('count');
        $response = Http::get('https://dummyjson.com/products?limit=' . $count);
        if ($response->ok()) {
            $products = $response->json('products');
            foreach ($products as $key => $value) {
                $product = [
                    'currency' => 'USD',
                    'price' => $value['price'],
                    'title' => $value['title'],
                    'description' => $value['description'],
                    'brand' => $value['brand'],
                    'category' => $value['category'],
                    'stock' => $value['stock'],
                    'rating' => $value['rating']
                ];
                $slug = Str::slug($value['title']);
                $url = $value['images'][0];
                $contents = file_get_contents($url);
                $name = $slug . '-' . substr($url, strrpos($url, '/') + 1);
                $result = Storage::disk('public')->put('products/' . $name, $contents);
                if ($result) {
                    $product = Product::updateOrCreate(['title' => $product['title']], $product);
                    $product->addMedia('/home/glyndun/projects/starter/public/storage/products/' . $name)
                        ->toMediaCollection();
                }
            }
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['count', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}