<?php

use Modules\Main\Http\Controllers\{
    MainController, GamblingController, AuctionBetController, LotteryController
};

Route::group(['middleware' => 'web'], function () {
    Route::get('', [GamblingController::class, 'index'])->name('index');

    Route::group(['prefix' => 'boxes/{box}', 'as' => 'boxes.'], function () {
        Route::get('', [GamblingController::class, 'showBox'])->name('show');
        Route::post('', [GamblingController::class, 'openBox'])->name('open');
    });

    Route::group(['prefix' => 'auction/{auctionBetPrice}', 'as' => 'auction_bets.'], function () {
        Route::post('', [AuctionBetController::class, 'createBet'])->name('create');
    });

    Route::group(['prefix' => 'auction/', 'as' => 'auction_bets.'], function () {
        Route::get('winners', [AuctionBetController::class, 'getLastWinners'])->name('getLastWinners');
        Route::get('get-last-hash', [AuctionBetController::class, 'getLastAuctionHash'])->name('getLastAuctionHash');
        Route::get('get-bank-sum', [AuctionBetController::class, 'getBankSumByAuctionHash'])->name('getBankSumByAuctionHash');
        Route::get('realtime-update', [AuctionBetController::class, 'getLastUpdatedData'])->name('getLastUpdatedData');
        Route::post('message/send', [AuctionBetController::class, 'sendMessage'])->name('sendMessage');
    });
    Route::get('auction-end', [AuctionBetController::class, 'endAuction'])->name('endAuction');

    Route::get('lottery', [LotteryController::class, 'lottery'])->name('lottery');
    Route::group(['prefix' => 'lottery/', 'as' => 'lottery.'], function () {
        Route::post('start', [LotteryController::class, 'startLottery'])->name('start');
        Route::get('end', [LotteryController::class, 'endLottery'])->name('end');
    });
    Route::get('lottery/is-started', [LotteryController::class, 'isLotteryStarted'])->name('isLotteryStarted');


    Route::get('faq', [MainController::class, 'faq'])->name('faq');
    Route::get('auction', [AuctionBetController::class, 'auction'])->name('auction');
    Route::get('auction-rules', [AuctionBetController::class, 'auctionRules'])->name('auctionRules');
    Route::get('top', [MainController::class, 'top'])->name('top');
    Route::get('rules', [MainController::class, 'rules'])->name('rules');
    Route::get('ref/{key}', [MainController::class, 'referral'])->name('referral');
    Route::get('ref', [MainController::class, 'ref'])->name('ref');
});