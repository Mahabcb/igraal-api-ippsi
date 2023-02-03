<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Voucher;

class VoucherService
{
    public function applyVoucher(Voucher $voucher, Order $order)
    {
        /**
         * si un voucher s'expire => on lance une erreur
         * si le montant d'un voucher est supérieur au montant de la commande => on lance une erreur
         */

        if($voucher->getExpiredAt() < new \DateTime()) {
            $voucher->setIsExpired(true);
            throw new \Exception('Voucher expired');
        }

        if($voucher->getAmount() > $order->getAmount()) {
            throw new \Exception('Voucher is not valid for this order');
        }

        $order->setAmount($order->getAmount() - $voucher->getAmount());
        $voucher->setIsExpired(true);
        $order->setVoucher($voucher);

        return $order;
    }
}