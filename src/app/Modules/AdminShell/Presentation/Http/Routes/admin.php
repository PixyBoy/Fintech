<?php

use Illuminate\Support\Facades\Route;
use App\Modules\AdminShell\Presentation\Livewire\Admin\Dashboard\HomePage;

Route::get('/', HomePage::class)->name('dashboard');
