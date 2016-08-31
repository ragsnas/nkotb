<?php
// src/Acme/TaskBundle/Form/DataTransformer/IssueToNumberTransformer.php
namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class RepeatTypeTransformer implements DataTransformerInterface
{
    // transforms the Issue object to a string
    public function transform($val)
    {
        if (null === $val) {
            return ['type' => null];
        }

        return ['type' => $val];
    }

    // transforms the issue number into an Issue object
    public function reverseTransform($val)
    {
        if (!is_array($val) || !isset($val['type']) ) {
            return null;
        }

        return $val['type'];
    }
}