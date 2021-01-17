<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 30/07/2017
 * Time: 15:37.
 */

namespace Greenter\Validator\Loader;

use Greenter\Validator\Metadata\LoaderMetadataInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class SalePerceptionLoader implements LoaderMetadataInterface
{
    public function load(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('codReg', [
            new Assert\NotBlank(),
            new Assert\Choice([
                'choices' => ['01', '02', '03'],
            ]),
        ]);
        $metadata->addPropertyConstraint('mtoBase', new Assert\NotBlank());
        $metadata->addPropertyConstraint('mto', new Assert\NotBlank());
        $metadata->addPropertyConstraint('mtoTotal', new Assert\NotBlank());
    }
}
