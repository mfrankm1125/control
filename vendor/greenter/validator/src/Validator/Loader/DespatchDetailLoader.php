<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 07/08/2017
 * Time: 23:49.
 */

namespace Greenter\Validator\Loader;

use Greenter\Validator\Metadata\LoaderMetadataInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Greenter\Validator\Constraint as MyAssert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class DespatchDetailLoader implements LoaderMetadataInterface
{
    public function load(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('codigo', [
            new Assert\Length(['max' => 16]),
        ]);
        $metadata->addPropertyConstraints('descripcion', [
            new Assert\NotBlank(),
            new Assert\Length(['max' => 250]),
        ]);
        $metadata->addPropertyConstraints('unidad', [
            new Assert\NotBlank(),
            new MyAssert\CodeUnit(),
        ]);
        $metadata->addPropertyConstraints('cantidad', [
            new Assert\NotBlank(),
            new Assert\Type(['type' => 'numeric']),
        ]);
        $metadata->addPropertyConstraint('codProdSunat', new Assert\Length(['max' => 20]));
    }
}
