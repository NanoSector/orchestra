<?php
declare(strict_types=1);

namespace Domain\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Domain\Enumeration\Role;
use Infrastructure\Collection\AbstractStronglyTypedArrayCollection;

/**
 * @extends AbstractStronglyTypedArrayCollection<array-key, Role>
 */
class RoleCollection extends AbstractStronglyTypedArrayCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct(Role::class, $elements);
    }

    public function asStringCollection(): ArrayCollection
    {
        return $this->map(static fn(Role $r) => $r->value);
    }

    public function unique(): RoleCollection
    {
        $result = new RoleCollection();
        foreach ($this as $item) {
            if ($result->contains($item)) {
                continue;
            }

            $result->add($item);
        }

        return $result;
    }
}