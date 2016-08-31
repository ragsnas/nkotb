<?php
// src/Acme/TaskBundle/Form/DataTransformer/IssueToNumberTransformer.php
namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class TimeTypeTransformer implements DataTransformerInterface
{
    // transforms the Issue object to a string
    public function transform($val)
    {
        $hour = 0;
        $minute = 0;
        if ($val) {
            $hour = round($val/60);
            $minute = $val - ($hour * 60);
        }

        return ['hour' => $hour, 'minute' => $minute];
    }

    // transforms the issue number into an Issue object
    public function reverseTransform($val)
    {
        if (!is_array($val) || !isset($val['hour']) || !isset($val['minute']) ) {
            return null;
        }

        return $val['hour'] * 60 + $val['minute'];
    }
}