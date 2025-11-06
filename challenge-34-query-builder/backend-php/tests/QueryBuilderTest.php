<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use PDO;
use Src\Grammar\MySQLGrammar;
use Src\Query\QueryBuilder;
use Src\Query\SelectQuery;
use Src\Query\InsertQuery;
use Src\Query\UpdateQuery;
use Src\Query\DeleteQuery;

class QueryBuilderTest extends TestCase
{
    private $grammar;

    protected function setUp(): void
    {
        $this->grammar = new MySQLGrammar();
    }

    public function testSelectQueryCompilation()
    {
        $query = new SelectQuery($this->createMock(PDO::class), $this->grammar);
        $sql = $query->select(['id', 'name'])->from('users')->toSql();
        
        $this->assertEquals('SELECT `id`, `name` FROM `users`', $sql);
    }

    public function testSelectWithWhereClause()
    {
        $query = new SelectQuery($this->createMock(PDO::class), $this->grammar);
        $sql = $query->select(['*'])->from('users')->where('id', 1)->toSql();
        
        $this->assertEquals('SELECT * FROM `users` WHERE `id` = ?', $sql);
    }

    public function testInsertQueryCompilation()
    {
        $query = new InsertQuery($this->createMock(PDO::class), $this->grammar);
        $sql = $query->into('users')->values(['name' => 'John', 'email' => 'john@example.com'])->toSql();
        
        $this->assertEquals('INSERT INTO `users` (`name`, `email`) VALUES (?, ?)', $sql);
    }

    public function testUpdateQueryCompilation()
    {
        $query = new UpdateQuery($this->createMock(PDO::class), $this->grammar);
        $sql = $query->table('users')->set(['name' => 'Jane'])->where('id', 1)->toSql();
        
        $this->assertEquals('UPDATE `users` SET `name` = ? WHERE `id` = ?', $sql);
    }

    public function testDeleteQueryCompilation()
    {
        $query = new DeleteQuery($this->createMock(PDO::class), $this->grammar);
        $sql = $query->from('users')->where('id', 1)->toSql();
        
        $this->assertEquals('DELETE FROM `users` WHERE `id` = ?', $sql);
    }

    public function testSelectWithJoin()
    {
        $query = new SelectQuery($this->createMock(PDO::class), $this->grammar);
        $sql = $query->select(['users.*'])->from('users')
            ->join('posts', 'users.id', '=', 'posts.user_id')
            ->toSql();
        
        $this->assertEquals('SELECT `users`.* FROM `users` inner JOIN `posts` ON `users`.`id` = `posts`.`user_id`', $sql);
    }

    public function testSelectWithOrderBy()
    {
        $query = new SelectQuery($this->createMock(PDO::class), $this->grammar);
        $sql = $query->select(['*'])->from('users')->orderBy('name', 'desc')->toSql();
        
        $this->assertEquals('SELECT * FROM `users` ORDER BY `name` DESC', $sql);
    }

    public function testSelectWithLimit()
    {
        $query = new SelectQuery($this->createMock(PDO::class), $this->grammar);
        $sql = $query->select(['*'])->from('users')->limit(10)->toSql();
        
        $this->assertEquals('SELECT * FROM `users` LIMIT 10', $sql);
    }

    public function testSelectWithGroupBy()
    {
        $query = new SelectQuery($this->createMock(PDO::class), $this->grammar);
        $sql = $query->select(['count(*) as count'])->from('users')->groupBy(['status'])->toSql();
        
        $this->assertEquals('SELECT `count(*) as count` FROM `users` GROUP BY `status`', $sql);
    }

    public function testWhereInClause()
    {
        $query = new SelectQuery($this->createMock(PDO::class), $this->grammar);
        $sql = $query->select(['*'])->from('users')->whereIn('id', [1, 2, 3])->toSql();
        
        $this->assertEquals('SELECT * FROM `users` WHERE `id` IN (?, ?, ?)', $sql);
    }

    public function testWhereNullClause()
    {
        $query = new SelectQuery($this->createMock(PDO::class), $this->grammar);
        $sql = $query->select(['*'])->from('users')->whereNull('deleted_at')->toSql();
        
        $this->assertEquals('SELECT * FROM `users` WHERE `deleted_at` IS NULL', $sql);
    }
}