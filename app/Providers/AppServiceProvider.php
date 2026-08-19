<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;
use App\Support\UrlCodec;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('item', function (string $value) {
            return $this->resolveFromHash(Item::class, $value, withTrashed: true);
        });

        Route::bind('category', function (string $value) {
            return $this->resolveFromHash(Category::class, $value);
        });

        Route::bind('user', function (string $value) {
            if (ctype_digit($value)) {
                return User::withTrashed()->findOrFail((int) $value);
            }

            return $this->resolveFromHash(User::class, $value, withTrashed: true);
        });

        Route::bind('transaction', function (string $value) {
            $id = UrlCodec::decode($value);

            if ($id === null && ctype_digit($value)) {
                $id = (int) $value;
            }

            abort_unless($id !== null, 404);

            return Transaction::findOrFail($id);
        });
    }

    private function resolveFromHash(string $model, string $value, bool $withTrashed = false)
    {
        $id = UrlCodec::decode($value);

        abort_unless($id !== null, 404);

        $query = $model::query();

        if ($withTrashed) {
            $query->withTrashed();
        }

        return $query->findOrFail($id);
    }
}
