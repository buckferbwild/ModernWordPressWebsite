<?php

use MWW\Routing\Route;
use MWW\Routing\Condition;

use App\Controller\Pages\Home;
use App\Controller\Pages\Page;
use App\Controller\Pages\NotFound;

Route::add( 'is_front_page', Home::class );
Route::add( Condition::match( 'is_page', 'is_single' ), Page::class );
Route::notFound( NotFound::class );
