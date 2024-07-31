<?php

/*
 * This file is part of the "Shared Project Timesheets Bundle" for Kimai.
 * All rights reserved by Fabian Vetter (https://vettersolutions.de).
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace KimaiPlugin\SharedProjectTimesheetsBundle\Form;

use KimaiPlugin\SharedProjectTimesheetsBundle\Entity\SharedProjectTimesheet;

class SharedCustomerFormType extends SharedProjectFormType
{
    protected function getType(): string
    {
        return SharedProjectTimesheet::TYPE_CUSTOMER;
    }
}
