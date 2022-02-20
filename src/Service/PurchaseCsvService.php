<?php

namespace App\Service;

use App\Entity\Currency;
use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\Purchase;
use App\Exception\CustomerCsvServiceException;
use App\Exception\PurchaseCsvServiceException;
use App\Helper\PurchaseStatusHelper;
use App\Lists\PurchaseStatusReference;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PurchaseCsvService
{
    const CSV_PURCHASE_FILENAME = 'purchases.csv';
    const HEADER_CSV = ["purchase_identifier", "customer_id", "product_id", "quantity", "price", "currency", "date\n"];

    /** @var CsvService */
    private $csvService;

    /** @var EntityManagerInterface */
    private $em;

    /** @var ValidatorInterface */
    private $validator;

    /** @var PurchaseStatusHelper */
    private $purchaseStatusHelper;

    public function __construct(CsvService $csvService, EntityManagerInterface $em, ValidatorInterface $validator, PurchaseStatusHelper $purchaseStatusHelper)
    {
        $this->csvService = $csvService;
        $this->em = $em;
        $this->validator = $validator;
        $this->purchaseStatusHelper = $purchaseStatusHelper;
    }

    public function start()
    {
        $handle = $this->csvService->open(CsvService::CSV_DIRECTORY, self::CSV_PURCHASE_FILENAME);

        if(false === $this->headerValidation($handle)) {
            throw new PurchaseCsvServiceException('purchase csv header is not correct.');
        }

        while (false !== ($rawString = fgets($handle))) {
            $rawString = str_replace(['"', "\n"], '', $rawString);
            $data = explode(';', $rawString);
            $this->createPurchase($data);
        }
    }

    private function createPurchase(array $data)
    {
        $purchase = $this->em->getRepository(Purchase::class)->findOneBy(['id' => $data[0]]);

        if ($purchase instanceof Purchase) {
            return;
        }

        $customer = $this->em->getRepository(Customer::class)->findOneBy(['id' => $data[1]]);
        $product = $this->em->getRepository(Product::class)->findOneBy(['id' => $data[2]]);
        $currency = $this->em->getRepository(Currency::class)->findOneBy(['reference' => str_replace('"', '', $data[5])]);

        if (null === $currency) {
            throw new PurchaseCsvServiceException(sprintf('currency is missing. (reference : %s)', $data[5]));
        }

        $date = \DateTime::createFromFormat('Y-m-d',$data[6]);

        $purchase = new Purchase();
        $purchase
            ->setId($data[0])
            ->setCurrency($currency)
            ->setCustomer($customer)
            ->setProduct($product)
            ->setQuantity($data[3])
            ->setPrice(intval($data[4]))
            ->setDate($date)
        ;

        $this->purchaseStatusHelper->setStatus(PurchaseStatusReference::WAITING_TO_BE_SENT, $purchase);

        $errors = $this->validator->validate($purchase);

        if (count($errors) > 0) {
            throw new CustomerCsvServiceException(sprintf('purchase csv errors (id : %s) -> %s', $data[0] ?? null, (string) $errors));
        }

        $this->em->persist($purchase);
        $this->em->flush();
    }

    private function createProduct(array $data)
    {
        dump($data);
    }

    private function headerValidation($handle)
    {
        $header = fgets($handle);
        foreach (explode(';', $header) as $value => $data) {
            if (utf8_encode($data) !== self::HEADER_CSV[$value]) {
                return false;
            }
        }
        return true;
    }
}