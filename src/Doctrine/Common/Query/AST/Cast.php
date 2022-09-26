<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Doctrine\Common\Query\AST;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class Cast extends FunctionNode
{
    /**
     * @var Node|string
     */
    private $valueExpression;

    private string $typeString;

    public function getSql(SqlWalker $sqlWalker): string
    {
        return \sprintf(
            '%s::%s',
            $this->valueExpression->dispatch($sqlWalker),
            $this->typeString
        );
    }

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);

        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->valueExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $parser->match(Lexer::T_IDENTIFIER);
        $lexer = $parser->getLexer();
        $this->typeString = $lexer->token['value'];
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
