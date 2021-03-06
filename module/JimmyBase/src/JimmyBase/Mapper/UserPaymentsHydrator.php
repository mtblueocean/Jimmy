<?php

namespace JimmyBase\Mapper;

use Zend\Stdlib\Hydrator\ClassMethods;
use JimmyBase\Entity\UserPaymentsInterface as UserPaymentsEntityInterface;

class UserPaymentsHydrator extends ClassMethods
{
    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     * @throws Exception\InvalidArgumentException
     */
    public function extract($object)
    {
        if (!$object instanceof UserPaymentsEntityInterface) {
            throw new Exception\InvalidArgumentException('$object must be an instance of JimmyBase\Entity\UserPaymentsInterface');
        }
		
        /* @var $object UserInterface*/
        $data = parent::extract($object);
      return $data;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     * @return UserInterface
     * @throws Exception\InvalidArgumentException
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof UserPaymentsEntityInterface) {
            throw new Exception\InvalidArgumentException('$object must be an instance of JimmyBase\Entity\UserPaymentsInterface');
        }
		
        return parent::hydrate($data, $object);
    }

    protected function mapField($keyFrom, $keyTo, array $array)
    {
        $array[$keyTo] = $array[$keyFrom];
        unset($array[$keyFrom]);
        return $array;
    }
	
	protected function removeField($key, array $array)
    {
        unset($array[$key]);
        return $array;
    }
}
