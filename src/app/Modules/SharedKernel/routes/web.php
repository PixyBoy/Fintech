<?php
use Illuminate\Support\Facades\Route;

Route::get('/_healthz', fn () => response()->json(['ok' => true, 'ts' => now()]))->name('healthz');
Route::get('/_readyz', fn () => response()->json(['ready' => true]))->name('readyz');
