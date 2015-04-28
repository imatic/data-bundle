<?php

namespace Imatic\Bundle\DataBundle\Doctrine\Postgresql\Query\AST;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\Lexer;

class NotILike extends FunctionNode
{
    protected $field;
    protected $value;

    public function getSql(SqlWalker $sqlWalker)
    {
        return $this->field->dispatch($sqlWalker) . ' NOT ILIKE ' . $this->value->dispatch($sqlWalker);
    }

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->field = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_COMMA);

        $this->value = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
