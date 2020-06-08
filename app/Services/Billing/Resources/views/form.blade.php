<?php /** @var \App\Services\Billing\Services\BillingService $billingService */ ?>
<form method="GET" action="http://www.free-kassa.ru/merchant/cash.php">
    <input type="hidden" name="m" value="{{ $billingService->merchantId() }}">
    <input type="hidden" name="oa" value="{{ $amount }}">
    <input type="hidden" name="o" value="{{ $orderId }}">
    <input type="hidden" name="s" value="{{ $sign }}">
    <input type="hidden" name="i" value="1">
    <input type="hidden" name="lang" value="ru">
    <input type="submit" name="pay" value="Оплатить">
</form>
