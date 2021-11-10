<?php

namespace Tests;

trait TestHelper
{

    public function assertDatabaseEmpty($table, $connection = null){
        $total = $this->getConnection($connection)->table($table)->count();
        $this->assertSame(0,$total,sprintf(
            "fail asserting the table [%s] is empty. %s %s found", $table, $total, str_plural('row', $total)
        ));
    }

    public function withData(array $custom = []){

        return array_merge($this->defaultData(), $custom);
    }

    protected function defaultData(){
        return $this->defaultData;
    }

    protected $defaultData = [
        'name' => 'Pepe',
        'email'=> 'emilio@email.es',
        'password' => '123456',
        'profession_id' => '',
        'bio' => 'Programador de Laravel y Vue.js',
        'twitter' => 'https://twitter.com/pepe',
        'role' => 'user',
    ];
}