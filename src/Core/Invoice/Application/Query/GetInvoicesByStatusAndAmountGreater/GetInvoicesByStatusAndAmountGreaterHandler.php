<?php

namespace App\Core\Invoice\Application\Query\GetInvoicesByStatusAndAmountGreater;

use App\Core\Invoice\Application\DTO\InvoiceDTO;
use App\Core\Invoice\Domain\Exception\InvoiceException;
use App\Core\Invoice\Domain\Invoice;
use App\Core\Invoice\Domain\Repository\InvoiceRepositoryInterface;
use App\Core\Invoice\Domain\Status\InvoiceStatus;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetInvoicesByStatusAndAmountGreaterHandler
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository
    ) {}

    public function __invoke(GetInvoicesByStatusAndAmountGreaterQuery $query): array
    {
        try {
            $status = InvoiceStatus::from($query->status);
        } catch (\ValueError $e) {
            throw new InvoiceException('Nieprawidłowy status faktury');
        }

        $invoices = $this->invoiceRepository->getInvoicesWithGreaterAmountAndStatus(
            $status,
            $query->amount,
        );

        return array_map(function (Invoice $invoice) {
            return new InvoiceDTO(
                $invoice->getId(),
                $invoice->getUser()->getEmail(),
                $invoice->getAmount()
            );
        }, $invoices);
    }
}
