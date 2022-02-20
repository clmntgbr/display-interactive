<?php

namespace App\Service;

use App\Entity\Customer;
use App\Exception\CustomerCsvServiceException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CustomerCsvService
{
    const CSV_CUSTOMER_FILENAME = 'customers.csv';
    const HEADER_CSV = ["customer_id", "title", "lastname", "firstname", "postal_code", "city", "email\n"];
    const CUSTOMER_TITLE = ['1' => 'mme', '2' => 'm'];

    /** @var CsvService */
    private $csvService;

    /** @var EntityManagerInterface */
    private $em;

    /** @var ValidatorInterface */
    private $validator;

    public function __construct(CsvService $csvService, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->csvService = $csvService;
        $this->em = $em;
        $this->validator = $validator;
    }

    public function start()
    {
        $handle = $this->csvService->open(CsvService::CSV_DIRECTORY, self::CSV_CUSTOMER_FILENAME);

        if(false === $this->headerValidation($handle)) {
            throw new CustomerCsvServiceException('customer csv header is not correct.');
        }

        while (false !== ($rawString = fgets($handle))) {
            $rawString = str_replace("\n", '', $rawString);
            $data = explode(';', $rawString);
            $this->createCustomer($data);
        }
    }

    private function createCustomer(array $data)
    {
        $customer = $this->em->getRepository(Customer::class)->findOneBy(['id' => $data[0]]);

        if ($customer instanceof Customer) {
            return;
        }

        if (!array_key_exists($data[1], self::CUSTOMER_TITLE)) {
            throw new CustomerCsvServiceException('customer title is not correct.');
        }

        $customer = new Customer();
        $customer
            ->setId($data[0] ?? null)
            ->setTitle(self::CUSTOMER_TITLE[$data[1]])
            ->setLastname($data[2] ?? null)
            ->setFirstname($data[3] ?? null)
            ->setPostalCode($data[4] ?? null)
            ->setCity($data[5] ?? null)
            ->setEmail($data[6] ?? null)
        ;

        $errors = $this->validator->validate($customer);

        if (count($errors) > 0) {
            throw new CustomerCsvServiceException(sprintf('customer csv errors (id : %s) -> %s', $data[0] ?? null, (string) $errors));
        }

        $this->em->persist($customer);
        $this->em->flush();
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