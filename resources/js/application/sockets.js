import io from "socket.io-client";
import {Application} from "./app";

export const sockets = {
    server: null,
    io: null,
    run() {
        const socket = sockets.io;
        socket.on("online", this.onOnline);
        socket.on("LiveFeed", this.onLiveFeed);
        socket.on('create bet', this.onCreateBet);
        socket.on('end auction', this.onAuctionEnd);
        socket.on('auction timer', this.onAuctionTimer);
        socket.on('lottery timer', this.onLotteryTimer);
        socket.on('send message', this.onMessageSend);
        socket.on('lottery started', this.onLotteryStarted);
        socket.on('lottery end', this.onLotteryEnd);
        socket.on('take lottery place', this.onTakeLotteryPlace);
    },
    onOnline(message) {
        window.Application.methods.setOnline(message);
    },
    onLiveFeed(message) {
        setTimeout(() => {
            window.Application.methods.appendLive(message.template);
        }, 8000);
    },
    onCreateBet(data) {
        Application.methods.updateBank(data.bank_sum, data.bet_price, data.last_bet_date, data.round_time_in_seconds, data.time_ago, data.last_bet_username, 0);
    },
    onMessageSend(data) {
        Application.methods.sendMessageToAuctionChat(data);
    },
    onAuctionEnd(data) {
        Application.isAuctionStarted = false;
        Application.methods.updatePageAfterEndAuction(data);
    },
    onAuctionTimer(seconds) {
        seconds = Application.methods.pad(seconds);
        Application.methods.updateTimer(seconds);
    },
    onTakeLotteryPlace(data) {
        console.log('take lottery place', data);
        Application.methods.updateLastLotteryParticipants(data.last_lottery_participants);
    },
    onLotteryEnd(data) {
        console.log('lottery end', data);
        Application.methods.lotteryEndProcess(data);
    },
    onLotteryTimer(seconds) {
        Application.methods.updateLotteryTimer(seconds);
    },
    onLotteryStarted(data) {
        Application.methods.updatePageAfterStartLottery(data);
    }
};

const constructSockets = function (server) {
    return io(server, {path: "/"});
};

$(function () {
    sockets.server = $("meta[name=sockets-server]").prop("content");
    sockets.io = constructSockets(sockets.server);
    sockets.run();
});
