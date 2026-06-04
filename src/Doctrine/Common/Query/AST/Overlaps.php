<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Doctrine\Common\Query\AST;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

class Overlaps extends FunctionNode
{
    /**
     * @var Node|string
     */
    private $firstDateExpression;

    /**
     * @var Node|string
     */
    private $secondDateExpression;

    /**
     * @var Node|string
     */
    private $thirdDateExpression;

    /**
     * @var Node|string
     */
    private $fourthDateExpression;

    public function getSql(SqlWalker $sqlWalker): string
    {
        return \sprintf(
            '(%s, %s) OVERLAPS (%s, %s)',
            $this->firstDateExpression->dispatch($sqlWalker),
            $this->secondDateExpression->dispatch($sqlWalker),
            $this->thirdDateExpression->dispatch($sqlWalker),
            $this->fourthDateExpression->dispatch($sqlWalker)
        );
    }

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);

        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->firstDateExpression = $parser->ArithmeticPrimary();
        $parser->match(TokenType::T_COMMA);
        $this->secondDateExpression = $parser->ArithmeticPrimary();
        $parser->match(TokenType::T_COMMA);
        $this->thirdDateExpression = $parser->ArithmeticPrimary();
        $parser->match(TokenType::T_COMMA);
        $this->fourthDateExpression = $parser->ArithmeticPrimary();
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }
}
