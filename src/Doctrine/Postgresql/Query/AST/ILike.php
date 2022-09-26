<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Doctrine\Postgresql\Query\AST;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class ILike extends FunctionNode
{
    /**
     * @var Node|string
     */
    public $field;

    /**
     * @var Node|string
     */
    public $value;

    public function getSql(SqlWalker $sqlWalker): string
    {
        return $this->field->dispatch($sqlWalker) . ' ILIKE ' . $this->value->dispatch($sqlWalker);
    }

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->field = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_COMMA);

        $this->value = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
