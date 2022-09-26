<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Doctrine\Common\Query\AST;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class UnaccentLower extends FunctionNode
{
    protected static string $function = 'unaccent_lower';

    /**
     * @var mixed
     */
    protected $stringPrimary;

    public function getSql(SqlWalker $sqlWalker): string
    {
        return \sprintf('%s(%s)', static::$function, $sqlWalker->walkSimpleArithmeticExpression($this->stringPrimary));
    }

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->stringPrimary = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public static function setFunction(string $function): void
    {
        static::$function = $function;
    }
}
