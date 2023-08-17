<?php

use App\Http\Controllers\DriverController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('dashboard');
});

Auth::routes([
    'register' => false,
    //'logout' => false,
]);


Route::middleware(['auth', 'activeuser'])->group(function(){
    //инвок контроллер для выхода (по ссылке)
    Route::get('logout', LogoutController::class)->name('logout');

    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('getalldrivers', [HomeController::class, 'getAllDrivers'])->name('driver.getdrivers');

    //Profile
    Route::get('/profile/changepassword', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::post('/profile', [ProfileController::class, 'updatePassword'])->name('profile.update-password');


    //Driver
    Route::group(['prefix' => 'drivers'], function (){
        Route::get('/', [DriverController::class, 'index'])->name('drivers.index');
        Route::get('/add', [DriverController::class, 'add'])->name('driver.add');
        Route::get('/show/{driver}', [DriverController::class, 'show'])->name('driver.show');
        Route::get('/driver/{driver}/images', [DriverController::class, 'images'])->name('driver.images');

        Route::post('/', [DriverController::class, 'status'])->name('driver.status');
        Route::post('/availability/{driver}', [DriverController::class, 'availability'])->name('driver.availability');

        Route::post('/store', [DriverController::class, 'store'])->name('driver.store');
        Route::post('/update/{driver}', [DriverController::class, 'update'])->name('driver.update');
        Route::get('/delete/{driver}', [DriverController::class, 'delete'])->name('driver.delete');

        Route::post('/setdrivernote', [DriverController::class, 'setNote'])->name('driver.note');
        Route::get('{driver}/images', [DriverController::class, 'images'])->name('driver.images');

        Route::post('{driver}/images', [ImageController::class, 'store'])->name('image.store');

        Route::get('{driver}/images/{image}/delete/', [ImageController::class, 'delete'])->name('image.delete');
        Route::get('getimages/{driver}', [DriverController::class, 'getDriverImages'])->name('driver.getImages');
    });

    //Equipments
    Route::group(['prefix' => 'equipment'], function (){
        Route::get('/', [EquipmentController::class, 'index'])->name('equipment.index');
        Route::get('/show/{equipment}', [EquipmentController::class, 'show'])->name('equipment.show');

        Route::post('/store', [EquipmentController::class, 'store'])->name('equipment.store');
        Route::post('/update/{equipment}', [EquipmentController::class, 'update'])->name('equipment.update');
        Route::get('/delete/{equipment}', [EquipmentController::class, 'delete'])->name('equipment.delete');
    });

    //Vehicle type
    Route::group(['prefix' => 'vehicletypes'], function (){
        Route::get('/', [VehicleTypeController::class, 'index'])->name('vehicletypes.index');
        Route::get('/show/{vehicle}', [VehicleTypeController::class, 'show'])->name('vehicletype.show');

        Route::post('/store', [VehicleTypeController::class, 'store'])->name('vehicletype.store');
        Route::post('/update/{vehicle}', [VehicleTypeController::class, 'update'])->name('vehicletype.update');
        Route::get('/delete/{vehicle}', [VehicleTypeController::class, 'delete'])->name('vehicletype.delete');

    });

    //Owners
    Route::group(['prefix' => 'owners'], function (){
        Route::get('/', [OwnerController::class, 'index'])->name('owners.index');
        Route::get('/add', [OwnerController::class, 'add'])->name('owner.add');
        Route::get('/show/{owner}', [OwnerController::class, 'show'])->name('owner.show');

        Route::post('/store', [OwnerController::class, 'store'])->name('owner.store');
        Route::post('/update/{owner}', [OwnerController::class, 'update'])->name('owner.update');
        Route::get('/delete/{owner}', [OwnerController::class, 'delete'])->name('owner.delete');
        Route::post('/assigndrivers', [OwnerController::class, 'assignDrivers'])->name('owner.assign-drivers');
        Route::get('/unassigndrivers/{driver}', [OwnerController::class, 'unAssignDrivers'])->name('owner.unassign-drivers');
    });

    //Users
    Route::group(['prefix' => 'users'], function (){
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/add', [UserController::class, 'add'])->name('user.add');
        Route::get('/show/{user}', [UserController::class, 'show'])->name('user.show');

        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::post('/update/{user}', [UserController::class, 'update'])->name('user.update');
        Route::get('/delete/{user}', [UserController::class, 'delete'])->name('user.delete');
        Route::post('/status', [UserController::class, 'status'])->name('user.status');
    });

});
