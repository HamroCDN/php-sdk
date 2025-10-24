<?php

declare(strict_types=1);

arch('no dd, dump, or ray calls')
    ->expect(['dd', 'dump', 'ray'])
    ->each
    ->not
    ->toBeUsed();

arch('all classes are final')
    ->expect('HamroCDN')
    ->classes()
    ->toBeFinal();

arch('all contracts are interfaces')
    ->expect('HamroCDN\Contracts')
    ->classes()
    ->toBeInterfaces();
