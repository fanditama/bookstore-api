<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from books");
        DB::delete("delete from inventory_books");
        DB::delete("delete from banks");
        DB::delete("delete from payments");
        DB::delete("delete from users");
    }
}
