<?php
declare(strict_types=1);

namespace App\Interface;

/**
 *
 */
interface Executable
{
    /**
     * @return bool
     */
    public function execute(): bool;
}