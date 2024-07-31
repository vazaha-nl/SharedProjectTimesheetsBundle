<?php

namespace KimaiPlugin\SharedProjectTimesheetsBundle\Form;

use KimaiPlugin\SharedProjectTimesheetsBundle\Entity\SharedProjectTimesheet;

class SharedCustomerFormType extends SharedProjectFormType
{
    protected function getType(): string
    {
        return SharedProjectTimesheet::TYPE_CUSTOMER;
    }
}