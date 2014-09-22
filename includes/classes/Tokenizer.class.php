<?php
/**
 * regular expression based tokenizer,
 * first token wins.
 */
class Tokenizer
{
    private $subject;
    private $offset = 0;
    private $tokens = array(
        'color-fgbg'  => '\x03(\d{1,2}),(\d{1,2})',
        'color-fg'    => '\x03(\d{1,2})',
        'color-reset' => '\x03',
        'style-bold'  => '\x02',
        'catch-all' => '.|\n',
    );
    public function __construct($subject)
    {
        $this->subject = (string) $subject;
    }
    public function setOffset($offset)
    {
        $this->offset = max(0, $offset);
    }
    public function getOffset()
    {
        return $this->offset;
    }
 
    /**
     * @return array|null
     */
    public function getNext()
    {
        if ($this->offset >= strlen($this->subject))
            return NULL;
 
        foreach($this->tokens as $name => $token)
        {
            if (FALSE === $r = preg_match("~$token~", $this->subject, $matches, PREG_OFFSET_CAPTURE, $this->offset))
                throw new RuntimeException('Pattern for token %s failed (regex error).', $name);
            if ($r === 0)
                continue;
            if (!isset($matches[0])) {
                var_dump(substr($this->subject, $this->offset));
                $c = 1;
            }
            if ($matches[0][1] !== $this->offset)
                continue;
            $data = array();
            foreach($matches as $match)
            {
                list($data[]) = $match;
            }
 
            $this->offset += strlen($data[0]);
            return array($name, $data);
        }
        return NULL;
    }
}
?>