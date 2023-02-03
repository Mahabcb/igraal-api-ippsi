<?php

namespace App\State;

use App\Service\VoucherService;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ApplyVoucher implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $processor,
        private VoucherService $voucherService)
        {
            
        }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if(!$data->getVoucher()) {
            return;
        }
        $data = $this->voucherService->applyVoucher($data->getVoucher(), $data);
        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}