<?php

use MWW\Routing\Route;
use MWW\Routing\Condition;

use App\Controller\Pages\Home_Controller;
use App\Controller\Pages\NotFound_Controller;
use App\Controller\Pages\Page_Controller;

Route::add( 'is_front_page', Home_Controller::class );
Route::add( Condition::match( 'is_page', 'is_single' ), Page_Controller::class );
Route::notFound( NotFound_Controller::class );
