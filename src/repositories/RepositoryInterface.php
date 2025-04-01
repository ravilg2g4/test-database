<?php

declare(strict_types=1);

namespace repositories;

interface RepositoryInterface
{
    public function read();
    public function write();
    public function deleteId();
    public function deleteEmail();
}