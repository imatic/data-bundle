<?php
namespace Imatic\Bundle\DataBundle\Doctrine\Common\Query\AST;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class Overlaps extends FunctionNode
{
    private $firstDateExpression;
    private $secondDateExpression;
    private $thirdDateExpression;
    private $fourthDateExpression;

    public function getSql(SqlWalker $sqlWalker)
    {
        return \sprintf(
            '(%s, %s) OVERLAPS (%s, %s)',
            $this->firstDateExpression->dispatch($sqlWalker),
            $this->secondDateExpression->dispatch($sqlWalker),
            $this->thirdDateExpression->dispatch($sqlWalker),
            $this->fourthDateExpression->dispatch($sqlWalker)
        );
    }

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);

        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->firstDateExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->secondDateExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->thirdDateExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->fourthDateExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
