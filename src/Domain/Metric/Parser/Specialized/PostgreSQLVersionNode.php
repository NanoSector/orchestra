<?php
declare(strict_types=1);

namespace Domain\Metric\Parser\Specialized;

use Domain\Metric\Parser\Node\RegexVersionNode;

class PostgreSQLVersionNode extends RegexVersionNode
{
    public function __construct()
    {
        parent::__construct('PostgreSQL', '/^PostgreSQL ([0-9\.]+)/');
    }
}