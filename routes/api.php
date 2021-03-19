<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarSaleController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HistoricController;
use App\Http\Controllers\LinkMovieController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectFileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PartnerController;

Route::get('/request', function(){
    return ['response'=>true];
});

Route::get('/401', [AuthController::class, 'unauthorized'])->name('login');

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::get('/car/sale', [CarSaleController::class, 'getCarSale']);

Route::get('/event', [EventController::class, 'getEvent']);

Route::get('/historic', [HistoricController::class, 'getHistoric']);

Route::get('/link/movie', [LinkMovieController::class, 'getLinkMovie']);

Route::get('/member', [MemberController::class, 'getMember']);

Route::get('/project', [ProjectController::class, 'getProject']);

Route::get('/user', [UserController::class, 'getUser']);

Route::get('/partner', [PartnerController::class, 'getPartner']);


Route::middleware('auth:api')->group(function(){
    Route::post('/auth/validate', [AuthController::class, 'validateToken']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    //Usuário tem que estar logado para ter acesso

    // Rotas do carro para os parceiros
    Route::post('/partner/file/restrict', [PartnerController::class, 'addFilePartner']);
    Route::post('/partner/restrict', [PartnerController::class, 'addPartner']);
    Route::post('/partner/restrict/{id}', [PartnerController::class, 'setPartner']);
    Route::delete('/partner/restrict/{id}', [PartnerController::class, 'removePartner']);

    // Rotas do carro para vender
    Route::get('/car/sale/restrict/{id}', [CarSaleController::class, 'addCarSale']);
    Route::post('/car/sale/restrict', [CarSaleController::class, 'addCarSale']);
    Route::post('/car/sale/file/restrict', [CarSaleController::class, 'addFileCarSale']);
    Route::post('/car/sale/restrict/{id}', [CarSaleController::class, 'setCarSale']);
    Route::post('/car/sale/admin/restrict/{id}', [CarSaleController::class, 'setCarSaleAdmin']);
    Route::delete('/car/sale/restrict/{id}', [CarSaleController::class, 'removeCarSale']);
    Route::delete('/car/sale/admin/restrict/{id}', [CarSaleController::class, 'removeCarSaleAdmin']);

    // Rotas para os membros
    Route::post('/member/restrict', [MemberController::class, 'addMember']);
    Route::post('/member/restrict/{id}', [MemberController::class, 'setMember']);
    Route::post('/member/admin/restrict/{id}', [MemberController::class, 'setMemberAdmin']);
    Route::delete('/member/restrict/{id}', [MemberController::class, 'removeMember']);
    Route::delete('/member/admin/restrict/{id}', [MemberController::class, 'removeMemberAdmin']);
    Route::post('/member/file/restrict', [MemberController::class, 'addFileMember']);

    // Rotas para os projetos
    Route::post('/project/restrict', [ProjectController::class, 'addProject']);
    Route::post('/project/restrict/{id}', [ProjectController::class, 'setProject']);
    Route::post('/project/admin/restrict/{id}', [ProjectController::class, 'setProjectAdmin']);
    Route::post('/project/file/restrict', [ProjectController::class, 'addFileProject']);
    Route::get('/project/restrict', [ProjectController::class, 'getProjectUser']);
    Route::delete('/project/restrict/{id}', [ProjectController::class, 'removeProject']);
    Route::delete('/project/admin/restrict/{id}', [ProjectController::class, 'removeProjectAdmin']);

    // Rotas para os eventos
    Route::post('/event/restrict', [EventController::class, 'addEvent']);
    Route::post('/event/file/restrict', [EventController::class, 'addFileEvent']);
    Route::post('/event/restrict/{id}', [EventController::class, 'setEvent']);
    Route::delete('/event/restrict/{id}', [EventController::class, 'removeEvent']);

    // Rotas para link de videos
    Route::post('/link/movie/restrict', [LinkMovieController::class, 'addLinkMovie']);
    Route::put('/link/movie/restrict/{id}', [LinkMovieController::class, 'setLinkMovie']);
    Route::delete('/link/movie/restrict/{id}', [LinkMovieController::class, 'removeLinkMovie']);

    // Rotas para o histórico
    Route::post('/historic/restrict', [HistoricController::class, 'addhistoric']);
    Route::post('/historic/file/restrict', [HistoricController::class, 'addFileHistoric']);
    Route::post('/historic/restrict/{id}', [HistoricController::class, 'sethistoric']);
    Route::delete('/historic/restrict/{id}', [HistoricController::class, 'removehistoric']);

    // Rotas para os usuários
    Route::post('/user/restrict', [UserController::class, 'addUser']);
    Route::post('/user/restrict/{id}', [UserController::class, 'editUser']);
    Route::delete('/user/restrict/{id}', [UserController::class, 'removeUser']);

});
