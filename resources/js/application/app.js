import {notification} from "./noty.js";
import {requestHandlers} from "./handlers.js";
import {sockets} from "./sockets.js";

export const Application = {
        isRunning: false,
        isAuctionStarted: false,
        noty: notification,
        sockets,
        requestHandlers,
        methods: {
            run(href, data = {}) {
                if (Application.methods.isAuthorized()) {
                    if (!Application.isRunning) {
                        Application.isRunning = true;
                        var queryParams = {
                            size: window.legacyApp.ruletId
                        };
                        if (!$.isEmptyObject(data)) {
                            Object.keys(data).forEach(function (key) {
                                queryParams[key] = data[key];
                            });
                        }
                        axios.post(href, queryParams).then(res => {
                            if (res.data.event === 'send_message') {
                                sockets.io.emit("send message", res.data);
                            }
                            if (res.data.event === 'created_bet') {
                                if (Application.isAuctionStarted) {
                                    sockets.io.emit("create bet", res.data);
                                } else {
                                    Application.noty.error('Аукцион еще не начат. Подождите несколько секунд.');
                                }
                            }
                            if (res.data.status) {
                                window.legacyApp.clear();

                                if (res.data.event === 'lottery_start') {
                                    Application.noty.success("Вы заняли место #" + res.data.lottery_number);
                                    sockets.io.emit("take lottery place", res.data);
                                }

                                if (res.data.last_free_participation_date !== undefined && res.data.awaiting_time_for_free_paticipation_in_seconds !== undefined) {
                                    var lastFreeParticipationDate = res.data.last_free_participation_date;
                                    if (lastFreeParticipationDate === null) {
                                        lastFreeParticipationDate = '';
                                    }
                                    $('meta[name=last_free_participant_date]').prop("content", lastFreeParticipationDate);
                                    $('meta[name=awaiting_time_for_free_paticipation_in_seconds]').prop("content", res.data.awaiting_time_for_free_paticipation_in_seconds);
                                }

                                if (res.data.items && _.isArray(res.data.items)) {
                                    _.forEach(res.data.items, item => {
                                        Application.methods.shuffleRoulette();
                                        Application.methods.setItem(item.id, item.object);
                                    });
                                }

                                window.legacyApp.rulets();

                                Application.methods.renderBalance(res.data.balance, 3000);
                            } else {
                                Application.noty.error(res.data.message);
                            }
                            Application.isRunning = false;
                        }).catch(error => {
                            Application.requestHandlers.handleAxiosError(error, Application.noty);
                            Application.isRunning = false;
                        });
                    }
                } else {
                    Application.noty.error("Вы не авторизованы!");
                }
            },
            setOnline(online) {
                return $("#currentOnlineCounter").html(online);
            },
            appendLive(itemTemplate) {
                return $(".live .live-ul").prepend(itemTemplate);
            },
            setItem(itemId, itemObject) {
                let $element = $(`.rulet.rulet-${itemId} .lucky`);

                if ($element.length) {
                    const $elementType = $element.find("[data-sense=type]"),
                        $elementAmount = $element.find(".rulet-col"),
                        $elementName = $element.find(".rulet-name");

                    $elementType.prop("class", `rulet-${itemObject.type}`);
                    $elementAmount.html(itemObject.amount);
                    $elementName.html(itemObject.name);
                    $element.prop("class", `color-${itemObject.rarity} lucky`);
                } else {
                    console.warn(`Roulette element ${itemId} not found!`);
                }
            },
            renderBalance(serializedBalance, defer) {
                $("#moneyBalance").text(serializedBalance.money);

                setTimeout(() => {
                    $("#coinsBalance").text(serializedBalance.coins);
                    $("#creditsBalance").text(serializedBalance.credits);
                    $("#bonusesBalance").text(serializedBalance.bonus);
                }, defer || 0);
            },
            updateBank(bankSum, betPrice, lastBetDate, roundTimeInSeconds, timeAgo, lastBetUserName, defer, isNeedShowTimer = true) {
                var target_date = null;
                setTimeout(function () {
                    if (lastBetDate !== null) {
                        target_date = new Date(lastBetDate.date + ' ' + lastBetDate.timezone).getTime() + (1000 * roundTimeInSeconds);
                    }
                    if (isNeedShowTimer) {
                        $("#auction_timer_block").css('display', 'block');
                    }
                    $("#auction_timer_block_holder").css('display', 'none');
                    if (lastBetUserName !== null) {
                        $("#last_bet_user").text(lastBetUserName);
                    } else {
                        $("#last_bet_user").text('');
                    }
                    $("#last_bet_time_ago").text(timeAgo);
                    $("#bank_sum").text(bankSum);
                    $("#auctionBetPrice").text(betPrice).attr('data-raw', betPrice);
                    var createBetBtn = $("#create_bet_btn");
                    var $newHrefData = createBetBtn.attr('href').split('/');
                    $newHrefData[4] = betPrice;
                    createBetBtn.attr('href', $newHrefData.join('/'));
                }, defer || 0);
            },
            updatePageAfterEndAuction(data, betPrice = 50) {
                setTimeout(function () {
                    var createBetButton = $('#create_bet_btn');
                    $("#auction_timer_block").css('display', 'none');
                    createBetButton.css('display', 'none');
                    var lastAuctionWinner = $('#last_auction_winner');
                    lastAuctionWinner.text(data.message).css('display', 'block');
                    setTimeout(function () {
                        lastAuctionWinner.css('display', 'none');
                        $('#auction_timer_block_holder').css('display', 'block');
                        createBetButton.css('display', 'block');
                    }, 14000);
                    Application.methods.updateAuctionWinners(data.last_auction_winners);
                    Application.methods.updateBank(0, betPrice, null, null, 'Ставок еще не было', null, 0, false);
                }, 10);
            },
            updateTimer(seconds) {
                var countdown = $("ul.auction-timer");
                var splitedTime = Application.methods.splitCountdownTime(seconds);
                countdown.children().remove();
                countdown.append("<li>" + splitedTime[0] + "</li>" + "<li>" + splitedTime[1] + "</li>" + "<li>" + splitedTime[2] + "</li>");
            },
            realtimeUpdatePage() {
                $.get('auction/realtime-update', function (responce) {
                    $('#last_bet_time_ago').text(responce.time_ago);
                    Application.methods.updateAuctionWinners(responce.last_auction_winners);
                });
            },
            updateAuctionWinners(auctionWinners) {
                var auctionWinnersLiveBlock = $('div.auction-live');
                auctionWinnersLiveBlock.empty();
                auctionWinners.forEach(function (value) {
                    auctionWinnersLiveBlock.append('<div class="auction-live-i"><div class="auction-live-ava"><a href="http://durak.local/profile/' + value.user.provider_id + '"><img src="' + value.user.avatar + '" alt=""></a></div><div class="auction-live-name ell">' + value.user.name + '</div><div class="auction-live-text ell"><span>' + value.win_sum + ' баллов</span><em>' + value.time_ago + '</em></div></div>');
                });
            },
            shuffleRoulette() {
                let elements = $(".unlucky.performance"),
                    buffer = [];

                elements.each((id, el) => buffer.push(el));
                buffer = _.shuffle(buffer);
                elements.each((id, el) => el.outerHTML = buffer[id].outerHTML);
            },
            sendMessageToAuctionChat(data) {
                var date = new Date(data.date + ' UTC');
                var timeInFormat = date.toLocaleTimeString();
                var newMessage = '<div class="chat-i"><div class="chat-ava"><a href="' + data.avatar + '"><img src="' + data.avatar + '" alt=""></a></div><div class="chat-panel"><div class="chat-login left">' + data.name + '</div> <div class="chat-time right">' + timeInFormat + '</div> <div class="clear"></div></div><div class="chat-text">' + data.message + '</div></div>';
                $('div.chat-loop').append(newMessage);
            },
            sellItem(href) {
                axios.post(href)
                    .then(res => {
                        if (res.data.status) {
                            Application.noty.success(res.data.message);
                            Application.methods.renderBalance(res.data.balance);
                            $("#inventoryContent").html(res.data.content);
                            $("#userBoxes").html(res.data.boxes);
                            $("#userRating").html(res.data.rating);
                        } else {
                            Application.noty.error(res.data.message);
                        }
                    })
                    .catch(error => {
                        Application.requestHandlers.handleAxiosError(error, Application.noty);
                    });
            },
            isAuthorized() {
                return !!_.toInteger(
                    $("meta[name=is-authorized]").prop("content")
                );
            },
            splitCountdownTime(padTime) {
                var result = [];
                padTime = String(padTime);
                var i = null;
                for (i = 0; i < 3; i++) {
                    var tempString = padTime.substr(i, 1);
                    if (padTime.length < 3 && i === 0 || padTime.length < 2 && (i === 0 || i === 1)) {
                        result.push("0");
                    }
                    if (tempString.length === 0) {
                        tempString = "0";
                    }
                    result.push(tempString);
                }
                return result;
            },
            setLotteryItem(itemId, itemObject) {
                let $element = $('.free-rulet-i.lucky');

                if ($element.length) {
                    const $participantId = $element.find('span'),
                        $participantAvatar = $element.find('img');

                    $participantId.text('#' + itemId);
                    let newAvatar = $('<img />', {
                        src: itemObject.avatar
                    });
                    $participantAvatar.remove();
                    $participantId.before(newAvatar);
                } else {
                    console.warn(`Roulette element ${itemId} not found!`);
                }
            },
            clearLottery() {
                $('.free-rulet-ul').css({'transition': '0s', 'transform': 'translate3d(0px, 0, 0)'})
            },
            ruletsLottery() {
                Application.methods.clearLottery();

                setTimeout(function () {
                    let size = 150 * 95 - 10;
                    $('.free-rulet-ul').css({'transition': '8s', 'transform': 'translate3d(-' + size + 'px, 0, 0)'})
                }, 100);
            },
            prepareLotteryRuletBeforeLotteryEnd(allParticipants) {
                $('.free-rulet-i').remove();
                var rouletteBlock = $('div.free-rulet-ul');
                _.forEach(allParticipants, function (lotteryParticipant, key) {
                    key = key + 1;
                    var luckyClass = key === 119 ? 'lucky' : 'unlucky';
                    var perfomanceOrBackstageClass = key >= 114 && key < 124 ? 'performance' : 'backstage';
                    $('<div/>', {
                        class: 'free-rulet-i ' + luckyClass + ' ' + perfomanceOrBackstageClass
                    })
                        .append($('<img/>', {
                            src: lotteryParticipant.object.avatar,
                        }))
                        .append($('<span/>', {
                            text: '#' + lotteryParticipant.id
                        })).appendTo(rouletteBlock)
                });
            },
            updatePageBeforeLotteryEnd(data) {
                $('.free-title').css('display', 'none');
                $('.free-bg').css('display', 'none');
                $('#timer-lottery').css('display', 'none');
                var timerHolder = $('#timer-holder');
                timerHolder.css('display', 'block').text('Розыгрыш окончен! Определяем победителей');
                var multiplier = data.participants.length;
                setTimeout(function () {
                    timerHolder.text('Розыгрыш еще не начат');
                }, multiplier * 10000)
            },
            updateLastLotteryParticipants(lastLotteryParticipants) {
                if (lastLotteryParticipants.length > 30) {
                    lastLotteryParticipants = lastLotteryParticipants.slice(0, 30);
                }
                var lotteryParticipantsList = $('div.free-last');
                lotteryParticipantsList.children().remove();
                _.forEach(lastLotteryParticipants, function (lotteryParticipant) {
                    $('<div/>', {
                        class: 'free-last-i',
                        append: $('<div/>', {
                            class: 'free-last-ava',
                            append: $('<img/>', {
                                src: lotteryParticipant.object.avatar,
                            })
                        })
                            .add($('<div/>', {
                                class: 'free-last-name ell',
                                text: lotteryParticipant.object.name
                            }))
                            .add($('<div/>', {
                                class: 'free-last-num',
                                text: "#" + lotteryParticipant.id
                            }))
                    }).appendTo(lotteryParticipantsList);
                })
            },
            updateWinnersAfterLotteryEnd(lotteryWinners) {
                var lotteryWinnersList = $('div.free-last-nw');
                lotteryWinnersList.children().remove();
                _.forEach(lotteryWinners, function (lotteryWinner, key) {
                    var isNeedHideElement = key >= 3;
                    var newWinnerElement = $('<div/>', {
                        class: 'free-last-i'
                    })
                        .append($('<div/>', {
                            class: 'free-last-ava',
                            append: $('<img/>', {
                                src: lotteryWinner.user.avatar
                            })
                        }))
                        .append($('<div/>', {
                                class: 'left',
                                append: $('<div/>', {
                                    class: 'free-last-name ell',
                                    text: lotteryWinner.user.name
                                })
                            })
                                .append($('<div/>', {
                                    class: 'free-last-num',
                                    text: "#" + lotteryWinner.lottery_number
                                }))
                        )
                        .append($('<div/>', {
                            class: 'right',
                            append: $('<b/>', {
                                text: lotteryWinner.item_human_amount + " кредитов"
                            })
                        }))
                        .append($('<div/>', {
                            class: 'clear'
                        }));
                    if (isNeedHideElement) {
                        newWinnerElement.css('display', 'none').attr('data-is-need-hide', 1);
                    }
                    lotteryWinnersList.append(newWinnerElement);
                });
            },
            updatePageAfterStartLottery(data) {
                $('#timer-lottery').css('display', 'block');
                $('.free-bg').css('display', 'block');
                $('.free-title').css('display', 'block');
                $('#timer-holder').css('display', 'none');
                Application.methods.updateLastLotteryParticipants(data.last_lottery_participants);
            },
            updateLotteryTimer(seconds) {
                var timerString = Application.methods.convertSecondsLeftToHumanReadableTimeLeft(seconds);
                $('#timer-lottery').text(timerString);
            },
            lotteryEndProcess(data) {
                if (data.last_free_participation_date !== undefined && data.awaiting_time_for_free_paticipation_in_seconds !== undefined) {
                    var lastFreeParticipationDate = data.last_free_participation_date;
                    if (lastFreeParticipationDate === null) {
                        lastFreeParticipationDate = '';
                    }
                    $('meta[name=last_free_participant_date]').prop("content", lastFreeParticipationDate);
                    $('meta[name=awaiting_time_for_free_paticipation_in_seconds]').prop("content", data.awaiting_time_for_free_paticipation_in_seconds);
                }
                $('[data-participant-type="free"]').text('Занять место бесплатно');
                Application.methods.updatePageBeforeLotteryEnd(data);
                Application.methods.prepareLotteryRuletBeforeLotteryEnd(data.all_participants);
                if (data.participants && _.isArray(data.participants)) {
                    var counter = 0;
                    var countParticipants = data.participants.length;
                    var mainLotteryRuletBlock = $('#main-lottery-rulet-block');
                    mainLotteryRuletBlock.css('display', 'block');
                    var winnersBlock = $('#winners-block');
                    winnersBlock.css('display', 'none');
                    _.forEach(data.participants, function (participant, key) {
                        setTimeout(function () {
                            Application.methods.clearLottery();
                            Application.methods.shuffleRoulette();
                            Application.methods.setLotteryItem(participant.id, participant.object);
                            Application.methods.ruletsLottery();
                            var isLastParticipant = countParticipants === (key + 1);
                            if (isLastParticipant) {
                                setTimeout(function () {
                                    mainLotteryRuletBlock.css('display', 'none');
                                    winnersBlock.css('display', 'block');
                                    $('.free-last').children().remove();
                                }, 15000);
                            }
                        }, counter * 10000);
                        counter++;
                    });
                }
                Application.methods.updateWinnersAfterLotteryEnd(data.last_lottery_winners);
            },
            pad(n) {
                return (n < 10 ? '0' : '') + n;
            },
            convertSecondsLeftToHumanReadableTimeLeft(seconds) {
                var h = seconds / 3600 ^ 0;
                var m = (seconds - h * 3600) / 60 ^ 0;
                var s = seconds - h * 3600 - m * 60;
                return Application.methods.pad(h) + ':' + Application.methods.pad(m) + ':' + Application.methods.pad(s);
            }
        }
    }
;

$(function () {
    $("a.rulet-btn").click(function (e) {
        e.preventDefault();
        Application.methods.run(
            $(this).prop("href")
        );
    });

    $("div.free-btn a").click(function (e) {
        e.preventDefault();
        var clickedButton = e.currentTarget;
        var participantType = clickedButton.dataset.participantType;
        var data = {
            participant_type: participantType
        };
        if (participantType === "by-money") {
            data.participant_price = clickedButton.dataset.lotteryPrice;
        }
        Application.methods.run(
            $(this).prop("href"),
            data
        );
    });

    $("div.chat-pole textarea").keyup(function (event) {
        if (!event.shiftKey && event.keyCode === 13) {
            event.preventDefault();
            var messageField = $('div.chat-pole textarea');
            var messageText = messageField.val();
            var data = {};
            data.message = messageText;
            Application.methods.run('auction/message/send', data);
            messageField.val('');
        }
    });

    $("a.auction-btn").click(function (e) {
        e.preventDefault();
        Application.isAuctionStarted = $('#last_auction_winner').css('display') === 'none';
        Application.methods.run(
            $(this).prop("href")
        );
    });

    $("div.chat-btn").click(function (e) {
        e.preventDefault();
        var messageField = $('div.chat-pole textarea');
        var messageText = messageField.val();
        var data = {};
        data.message = messageText;
        Application.methods.run('auction/message/send', data);
        messageField.val('');
    });

    const $elManagement = $("div.items-management");
    if ($elManagement.length) {
        $elManagement.on("click", ".viewn-pole1[data-action=sell]", function (e) {
            e.preventDefault();
            Application.methods.sellItem(
                $(this).prop("href")
            );
        });
    }

    setInterval(function () {
        if (window.location.href.indexOf('auction') >= 0) {
            setTimeout(function () {
                window.Application.methods.realtimeUpdatePage();
            }, 500);
        }
    }, 60000);

    $("form.form-withdraw").submit(function (e) {
        e.preventDefault();
        const data = $(this).serialize(),
            action = $(this).prop("action");

        axios.post(action, data)
            .then(res => {
                if (res.data.status) {
                    $("#modal-4").arcticmodal();
                    Application.methods.renderBalance(res.data.balance);
                } else {
                    Application.noty.error(res.data.message);
                }
            })
            .catch(error => {
                Application.requestHandlers.handleAxiosError(error, Application.noty);
            })
    });

    $("form.form-payment").submit(function (e) {
        e.preventDefault();
        const data = $(this).serialize(),
            action = $(this).prop("action");

        axios.get(`${action}?${data}`)
            .then(res => {
                if (res.data.status) {
                    const location = res.data.link;

                    if (location) {
                        Application.noty.success("Перенаправляем Вас на страницу оплаты...");

                        setTimeout(() => {
                            window.location.href = location;
                        }, 3000);
                    }
                } else {
                    Application.noty.error(
                        res.data.message
                    );
                }
            })
            .catch(error => Application.requestHandlers.handleAxiosError(error, Application.noty));
    });
});
