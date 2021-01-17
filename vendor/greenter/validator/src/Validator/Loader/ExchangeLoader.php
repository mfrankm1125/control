<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 08/08/2017
 * Time: 11:11 AM.
 */

namespace Greenter\Validator\Loader;

use Greenter\Validator\Metadata\LoaderMetadataInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Greenter\Validator\Constraint as MyAssert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class ExchangeLoader implements LoaderMetadataInterface
{
    public function load(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('monedaRef', [
            new Assert\NotBlank(),
            new MyAssert\Currency(),
        ]);
        $metadata->addPropertyConstraints('monedaObj', [
            new Assert\NotBlank(),
            new MyAssert\Currency(),
        ]);
        $metadata->addPropertyConstraint('factor', new Assert\NotBlank());
        $metadata->addPropertyConstraints('fecha', [
            new Assert\NotBlank(),
            new Assert\Date(),
        ]);
    }
}
