const app = require("express")(),
    server = require("http").Server(app),
    io = require("socket.io")(server, {
        path: "/"
    }),
    redis = require("redis");

const axios = require('axios');
const minOnline = 100;
const redisCredentials = {
    host: "durak_redis"
};

const handlers = {};

server.listen(8000, () => {
    console.log("Listening :8000 port");
});

console.log("Server started");

var seconds;
var target_date = null;
var auctionIsStarted = true;
var isAlreadyEndAuction = false;

var lotteryEndDate = null;
var isLotteryStarted = false;

io.on("connection", function (socket) {
    console.log("onConnection");
    let redisClient;
    const getOnline = function () {
        return minOnline + io.engine.clientsCount;
    };

    //lottery
    socket.on("take lottery place", function (data) {
        if (!isLotteryStarted) {
            if (data.is_started === true && data.lottery_start_date !== null) {
                lotteryEndDate = new Date(data.lottery_start_date + ' UTC').getTime() + (1000 * data.round_time_in_seconds);
                console.log('lottery is started', data);
                isLotteryStarted = true;
                io.sockets.emit("lottery started", data);
            }
        }
        io.sockets.emit("take lottery place", data);
    });

    //auction
    socket.on("send message", function (data) {
        io.sockets.emit("send message", data);
    });

    //auction
    socket.on("create bet", function (data) {
        console.log('start auction', data);
        var lastBetDate = data.last_bet_date;
        if (lastBetDate !== null) {
            target_date = new Date(lastBetDate.date + ' ' + lastBetDate.timezone).getTime() + (1000 * data.round_time_in_seconds);
            auctionIsStarted = true;
            isAlreadyEndAuction = false;
            io.sockets.emit('create bet', data);
        }
    });

    if (target_date === null) {
        auctionIsStarted = false;
        isAlreadyEndAuction = true;
    }

    //auction and lottery timers
    setInterval(function () {
        var current_date = new Date().getTime();
        var seconds_left = (target_date - current_date) / 1000;
        seconds_left = Math.floor(seconds_left);
        if (seconds_left <= 0) {
            if (!isAlreadyEndAuction && auctionIsStarted) {
                console.log({
                    isAlreadyEndAuction: isAlreadyEndAuction,
                    auctionIsStarted: auctionIsStarted
                });
                isAlreadyEndAuction = true;
                auctionIsStarted = false;
                axios.get('https://durak-drop.com/auction-end')
                    .then(function (response) {
                        console.log(response.data);
                        io.sockets.emit('end auction', response.data);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
            seconds = 0;
        } else {
            seconds = parseInt(seconds_left);
        }
        if (auctionIsStarted) {
            io.sockets.emit("auction timer", seconds);
        }

        let currentDate = new Date().getTime();
        let secondsLeft = (lotteryEndDate - currentDate) / 1000;
        secondsLeft = Math.floor(secondsLeft);
        if (secondsLeft <= 0) {
            if (isLotteryStarted) {
                isLotteryStarted = false;
                axios.get('https://durak-drop.com/lottery/end')
                    .then(function (response) {
                        console.log('lottery is end', response.data);
                        io.sockets.emit('lottery end', response.data);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
            seconds = 0;
        } else {
            seconds = parseInt(secondsLeft);
        }
        if (isLotteryStarted) {
            io.sockets.emit("lottery timer", seconds);
        }
    }, 1000);

    console.log(`Current Online: ${getOnline()}`);
    io.sockets.emit("online", getOnline());

    try {
        redisClient = redis.createClient(redisCredentials);
        redisClient.subscribe("LiveFeed");

        console.log("Redis started");

        redisClient.on("message", (channel, content) => {
            try {
                content = JSON.parse(content);
                const data = content.data,
                    event = content.event;

                console.log(`Broadcast to ${channel}:\n`, event);
                socket.emit(channel, data);
            } catch (e) {
                console.warn(e);
            }
        });
    } catch (e) {
        console.warn(e);
    }


    socket.on("disconnect", () => {
        try {
            redisClient.quit();
        } catch (e) {
            console.warn(e);
        }

        console.log("onDisconnect");
        io.sockets.emit("online", getOnline());
    });
});
