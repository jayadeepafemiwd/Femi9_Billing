<?php

namespace App\Providers;

use App\Models\Product;
use App\Observers\ProductObserver;
use Illuminate\Support\ServiceProvider;
use App\Models\Customer;
use App\Observers\CustomerObserver;
use App\Models\PriceList;
use App\Observers\PriceListObserver;
use App\Models\Invoice;
use App\Observers\InvoiceObserver;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
         Customer::observe(CustomerObserver::class);
        Product::observe(ProductObserver::class);
        PriceList::observe(PriceListObserver::class);
         Invoice::observe(InvoiceObserver::class);
         Paginator::useBootstrapFive();
    }
}