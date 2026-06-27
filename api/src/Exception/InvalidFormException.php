<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;
use Symfony\Component\Form\FormInterface;

final class InvalidFormException extends RuntimeException
{
    public function __construct(
        private readonly FormInterface $form,
    ) {
        parent::__construct('Invalid form data.');
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }
}
