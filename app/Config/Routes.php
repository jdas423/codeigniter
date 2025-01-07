<?php
 
use App\Controllers\Auth;

$routes->post('register',[Auth::class,'createAcc']);
$routes->post('login',[Auth::class,'loginUser']);