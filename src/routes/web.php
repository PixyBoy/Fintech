<?php

use App\Modules\SharedKernel\Application\Validation\DynamicValidator;
use App\Modules\SharedKernel\Infrastructure\Support\Idempotency;
use App\Modules\SharedKernel\Jobs\PingJob;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
