<div style="display:none;">
    <div class="modal" id="modal-1" style="width:433px;">
        {{ Form::open(['url' => route('billing.get_link'), 'method' => 'GET', 'class' => 'form-payment']) }}
        <div class="modal-close box-modal_close arcticmodal-close"></div>
        <div class="modal-title">Пополнение баланса</div>
        <div class="modal-text">Введите сумму на которую хотите пополнить баланс</div>
        <input type="text" class="modal-input" name="amount" value="100"/>
        <div class="line2"></div>
        <div class="modal-title">Промокод</div>
        <div class="modal-text">Если у Вас есть промокод, введите его или оставьте это поле пустым</div>
        <input type="text" class="modal-input" name="promo" placeholder="Промокод" style="width:100%;"/>
        <button class="modal-button" type="submit">Пополнить баланс</button>
        {{ Form::close() }}
    </div>
    <div class="modal" id="modal-2" style="width:433px;">
        {{ Form::open(['method' => 'POST', 'url' => route('profile_actions.withdraw_coins'), 'class' => 'form-withdraw']) }}
        <div class="modal-close box-modal_close arcticmodal-close"></div>
        <div class="modal-title">Вывод монет</div>
        <div class="modal-text">Введите сумму которую хотите вывести</div>
        <input type="text" class="modal-input" name="amount" value="100"/>
        <button class="modal-button" type="submit">Создать заявку на вывод</button>
        <div class="line2"></div>
        <div class="modal-text" style="padding:0;">
            <p>- Минимальная сумма вывода 100 монет</p>
            <p>- Вывод монет может занять от 1 мин до 24 часов</p>
            <p>- Вам нужно связаться с нашим менеджером</p>
        </div>
        {{ Form::close() }}
    </div>
    <div class="modal" id="modal-3" style="width:433px;">
        {{ Form::open(['method' => 'POST', 'url' => route('profile_actions.withdraw_credits'), 'class' => 'form-withdraw']) }}
        <div class="modal-close box-modal_close arcticmodal-close"></div>
        <div class="modal-title">Вывод кредитов</div>
        <div class="modal-text">Введите сумму которую хотите вывести</div>
        <input type="text" class="modal-input" name="amount" value="0"/>
        <button class="modal-button b2" type="submit">Создать заявку на вывод</button>
        <div class="line2"></div>
        <div class="modal-text" style="padding:0;">
            <p>- Минимальная сумма вывода 100 000 кредитов</p>
            <p>- Вывод кредитов может занять от 1 мин до 24 часов</p>
            <p>- Вам нужно связаться с нашим менеджером</p>
        </div>
        {{ Form::close() }}
    </div>
    <div class="modal" id="modal-5" style="width:433px;">
        {{ Form::open(['method' => 'POST', 'url' => route('profile_actions.withdraw_credits'), 'class' => 'form-withdraw']) }}
        <div class="modal-close box-modal_close arcticmodal-close"></div>
        <div class="modal-title">Зачем нужны баллы?</div>
        <div class="modal-text">Накопив определенное количество баллов, вы сможете открыть определенные кейсы, на которых цена указана в баллах, участвовать в аукционе, а также занимать места в бесплатном розыгрыше.</div>
        {{ Form::close() }}
    </div>
    <div class="modal" id="modal-4" style="width:433px;">
        <div class="modal-title">Заявка на вывод успешно создана!</div>
        <div class="modal-text" style="padding-bottom:0;">Свяжитесь с нашим менеджером Вконтакте</div>
        <?php
        $formActionLink = _s('settings-default', 'vkontakte-link');
        $getParameters = [];
        $formActionParams = explode('?', $formActionLink);
        $link = $formActionParams[0];
        $hasMoreOneGetParameters = count(explode('&', $formActionParams[1])) > 1;
        if (!$hasMoreOneGetParameters) {
            $getParametersFromLink[] = $formActionParams[1];
        } else {
            $getParametersFromLink = explode('&', $formActionParams[1]);
        }
        foreach ($getParametersFromLink as $getParameter) {
            $params          = explode('=', $getParameter);
            $paramName       = $params[0];
            $paramValue      = $params[1];
            $getParameters[] = [
                'name'  => $paramName,
                'value' => $paramValue,
            ];
        }
        ?>
        <form method="GET" action="{{ _s('settings-default', 'vkontakte-link') }}">
            @foreach($getParameters as $getParameter)
                <input name="{{$getParameter['name']}}" value="{{$getParameter['value']}}" hidden="hidden">
            @endforeach
            <button class="modal-button b3" style="margin-top:25px;" type="submit">Связаться через VK</button>
        </form>
        <div class="line2"></div>
        <div class="modal-text" style="padding:0;">
            <p>Свяжитесь с нашим менеджером после создания заявки, чтобы ускорить процесс вывода монет или же в течении
                24 часов наш менеджер сам с вами свяжется.</p>
        </div>
    </div>
</div>
