<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Doctrine\Postgresql\Query\AST;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

class JsonbExistsAny extends FunctionNode
{
    protected static string $function = 'jsonb_exists_any';

    /**
     * @var Node|string
     */
    protected $field;

    /**
     * @var Node|string
     */
    protected $value;

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->field = $parser->ArithmeticPrimary();

        $parser->match(TokenType::T_COMMA);
        $this->value = $parser->ArithmeticPrimary();

        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return \sprintf(
            '%s(%s, array[%s])',
            static::$function,
            $this->field->dispatch($sqlWalker),
            $this->value->dispatch($sqlWalker)
        );
    }
}
