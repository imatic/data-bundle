<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Doctrine\Postgresql\Query\AST;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class ILike extends FunctionNode
{
    public $field;
    public $value;

    public function getSql(SqlWalker $sqlWalker)
    {
        return $this->field->dispatch($sqlWalker) . ' ILIKE ' . $this->value->dispatch($sqlWalker);
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
