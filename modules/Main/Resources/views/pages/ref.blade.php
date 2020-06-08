@extends('front.layouts.master')

@section('content')

    <div class="container full">
        <div class="content">
            <div class="c-title">
                <div class="c-title-text">
                    <b>Реферальная система</b>
                    <span>Партнерская программа</span>
                </div>
                <div class="c-title-line"></div>
                <div class="clear"></div>
            </div>
            <div class="ref-title">Ваша реферальная ссылка</div>
            <div class="ref-content">
                <input type="text" id="ref-link" class="ref-input"
                       value="http://durak-drop.com/ref/{{$user->referral_key }}" disabled="" readonly style="cursor: pointer;">
                <button class="ref-button" id="copyButton">Копировать ссылку</button>
                <div class="clear"></div>
                <div>
                </div>
                <ul class="ref-list">
                    <li>Приглашено: <span>{{$user->referrals()->count()}}</span></li>
                    <li>Процент: <span>{{$user->getReferralFeeAttribute()}}</span></li>
                    <li>Заработано: <span>{{ $user->referral_earns_human }}</span></li>
                </ul>
            </div>
            <div class="ref-text">
                <p>Что такое реферальная система?</p>
                <p>Реферальная система - это система где её участникам даётся возможность приглашать других людей через специальную ссылку указанную у Вас в профиле, благодаря которой Вы можете зарабатывать реальные деньги себе на баланс, а приглашённый пользователь ПОЛУЧИТ БОНУСНЫЕ 10 000 кредитов на баланс.</p>
                <p>За каждого зарегистрированного пользователя по вашей ссылке, Вы можете получить до 20% от сумм пополнений вашими рефералами, чем больше людей Вы приглашаете на сайт, тем выше становиться ваш процент от пополнений. Начальный процент составляет 10%.</p>
                <p>Приглашая все больше и больше людей на сайт, Вы сможете открывать кейсы бесплатно, так как вам будут начисляться денежные средства от ваших рефералов. Мы готовы выдавать вам баланс за то, что Вы рекомендуете Durak-drop своим знакомым и друзьям.</p>
                    <p>ВАЖНАЯ ИНФОРМАЦИЯ:</p>
                    <p>– Если человек которого Вы приглашаете, ранее был зарегистрирован на сайте по другой реферальной ссылке, он не сможет стать вашим рефералом, так как он уже закреплен за другим пользователем. </p>
                    <p>▶ <b>УРОВНИ РЕФЕРАЛЬНОЙ СИСТЕМЫ:</b></p>
                    <p><b> ● Приглашая от: 0 до 5 человек - ваш процент составит 10% </b><br> <b>● Приглашая от: 5 до 10 человек - ваш процент составит 12% </b> <br> <b> ● Приглашая от: 10 до 20 человек - ваш процент составит 14% </b><br> <b> ● Приглашая от: 20 до 50 человек - ваш процент составит 15% </b><br> <b> ● Приглашая от: 50 до 250 человек - ваш процент составит 15% </b><br><b> ● Приглашая от: 250+ человек - ваш процент составит 20+% </b></p>
                    <p>Наличие приглашенных пользователей, ваш процент и сколько заработали средств с рефералом, Вы можете смотреть в личном профиле на сайте в разделе "Реферальная система"</p>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#copyButton').click(function () {
                refLink = $("#ref-link");
                refLink.removeAttr('disabled');
                copyToClipboard(document.getElementById("ref-link"));
                refLink.attr('disabled', 'disabled');
            });
        });

        function copyToClipboard(elem) {
            // create hidden text element, if it doesn't already exist
            var targetId = "_hiddenCopyText_";
            var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
            var origSelectionStart, origSelectionEnd;
            if (isInput) {
                // can just use the original source element for the selection and copy
                target = elem;
                origSelectionStart = elem.selectionStart;
                origSelectionEnd = elem.selectionEnd;
            } else {
                // must use a temporary form element for the selection and copy
                target = document.getElementById(targetId);
                if (!target) {
                    var target = document.createElement("textarea");
                    target.style.position = "absolute";
                    target.style.left = "-9999px";
                    target.style.top = "0";
                    target.id = targetId;
                    document.body.appendChild(target);
                }
                target.textContent = elem.textContent;
            }
            // select the content
            var currentFocus = document.activeElement;
            target.focus();
            target.setSelectionRange(0, target.value.length);

            // copy the selection
            var succeed;
            try {
                succeed = document.execCommand("copy");
            } catch (e) {
                succeed = false;
            }
            // restore original focus
            if (currentFocus && typeof currentFocus.focus === "function") {
                currentFocus.focus();
            }

            if (isInput) {
                // restore prior selection
                elem.setSelectionRange(origSelectionStart, origSelectionEnd);
            } else {
                // clear temporary content
                target.textContent = "";
            }
            return succeed;
        }
    </script>
@endsection
