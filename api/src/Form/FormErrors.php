<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\FormInterface;

final class FormErrors
{
    /**
     * @return list<array{field: string, message: string}>
     */
    public static function toList(FormInterface $form): array
    {
        $out = [];
        foreach ($form->getErrors(true) as $error) {
            $origin = $error->getOrigin();
            $out[] = [
                'field' => $origin->getName(),
                'message' => $error->getMessage(),
            ];
        }

        return $out;
    }
}
