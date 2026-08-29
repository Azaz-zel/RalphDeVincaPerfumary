<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\PerfumeController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\PlaceholderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/explore', [ExploreController::class, 'index'])
    ->name('explore');

Route::get('/explore/filter', [ExploreController::class, 'filter'])
    ->name('explore.filter');

Route::view('/academy', 'pages.academy')->name('academy');
Route::view('/articles', 'pages.articles')->name('articles');
Route::view('/about', 'pages.about')->name('about');

Route::get('/perfume/{slug}', [PerfumeController::class, 'show'])
    ->name('perfume.detail');

Route::get('/notes', [NoteController::class, 'index'])
    ->name('notes.index');

Route::get('/note/{slug}', [NoteController::class, 'show'])
    ->name('note.detail');

Route::get('/brands', [BrandController::class, 'index'])
    ->name('brands.index');

Route::get('/brand/{slug}', [BrandController::class, 'show'])
    ->name('brand.detail');

Route::view('/academy/introduction', 'pages.academy-introduction')
    ->name('academy.introduction');

Route::view('/academy/notes', 'pages.academy-notes')
    ->name('academy.notes');

Route::view('/academy/families', 'pages.academy-families')
    ->name('academy.families');

Route::view('/academy/concentration', 'pages.academy-concentration')
    ->name('academy.concentration');

Route::view('/academy/performance', 'pages.academy-performance')
    ->name('academy.performance');

Route::view('/academy/seasons', 'pages.academy-seasons')
    ->name('academy.seasons');

Route::view('/academy/apply', 'pages.academy-apply')
    ->name('academy.application');

Route::view('/academy/collection', 'pages.academy-collection')
    ->name('academy.collection');

Route::get('/placeholder/brand/{slug}', [PlaceholderController::class, 'brand'])
    ->name('placeholder.brand');

Route::get('/placeholder/perfume/{slug}', [PlaceholderController::class, 'perfume'])
    ->name('placeholder.perfume');

Route::get('/placeholder/note/{slug}', [PlaceholderController::class, 'note'])
    ->name('placeholder.note');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])
    ->name('sitemap');
