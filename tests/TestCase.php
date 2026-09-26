<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Neutralise @vite() dans les vues pendant les tests.
        // Le manifest (public/build/) est git-ignoré : sans ceci,
        // toute vue qui utilise @vite() lève une exception 500
        // si `npm run build` n'a pas été lancé au préalable.
        $this->withoutVite();
    }
}