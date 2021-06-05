<?php
namespace Application;

if(!defined("INDEX")){ die("You are not authorized to access"); }

use PHPXI\Libraries\Routing\Route as Route;

Route::any("/", "Welcome@main");

Route::prefix("/admin")->group(function () {
    Route::get("/", "Admin@dashboard")->name("dashboard")->filter("Auth");
    Route::get("/login", "Admin@login")->name("login");
    Route::post("/login", "Admin@login")->filter("Login");
    Route::get("/logout", "Admin@logout");
});


/*
Route::get("/profile/@:user", function ($username) {
echo "Your Username : " . $username;
})->where("user", "[a-z]+")->name("profil")->cache(false);
 */
