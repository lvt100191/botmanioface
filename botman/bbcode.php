<?php
class BBCode
{
    public static function convert($bbcode)
    {
        $bbcode = str_replace("[br]", "  \n", $bbcode);

        $bbcode = str_replace("[b]", "**", $bbcode);
        $bbcode = str_replace("[/b]", "**", $bbcode);

        $bbcode = str_replace("[i]", "*", $bbcode);
        $bbcode = str_replace("[/i]", "*", $bbcode);

        $bbcode = str_replace("[s]", "", $bbcode);
        $bbcode = str_replace("[/s]", "", $bbcode);

        $bbcode = str_replace("[u]", "", $bbcode);
        $bbcode = str_replace("[/u]", "", $bbcode);

        $bbcode = str_replace("[url]", "<", $bbcode);
        $bbcode = str_replace("[/url]", ">", $bbcode);
        $bbcode = preg_replace_callback('%\[url\s*=\s*("(?:[^"]*")|\A[^\']*\Z|(?:[^\'">\]\s]+))\s*(?:[^]\s]*)\]([\W\D\w\s]*?)\[/url\]%iu',
            function ($matches) {
                return "[" . $matches[2] . "](" . $matches[1] . ")";
            },
            $bbcode
        );



        // [email]
        $bbcode = preg_replace_callback('%\[email\]([\W\D\w\s]*?)\[/email\]%iu',
            function ($matches) {
                return $matches[1];
            },
            $bbcode
        );
        $bbcode = preg_replace_callback('%\[email\s*=\s*([\W\D\w\s]*?)\]([\W\D\w\s]*?)\[/email\]%iu',
            function ($matches) {
                return $matches[1];
            },
            $bbcode
        );
        // [color]
        $bbcode = preg_replace_callback('%\[color=([a-zA-Z]{3,20}|\#[0-9a-fA-F]{6}|\#[0-9a-fA-F]{3})]([\W\D\w\s]*?)\[/color\]%iu',
            function ($matches) {
                return $matches[2];
            },
            $bbcode
        );
        // [img]
        $bbcode = preg_replace_callback('%\[img\]([\W\D\w\s]*?)\[/img\]%iu',
            function ($matches) {
                return PHP_EOL . "![]" . "(" . $matches[1] . ")" . PHP_EOL;
            },
            $bbcode
        );
        $bbcode = preg_replace_callback('%\[img\s*=\s*("(?:[^"]*")|\A[^\']*\Z|(?:[^\'">\]\s]+))\s*(?:[^]\s]*)\[/img\]%iu',
            function ($matches) {
                return PHP_EOL . "![".$matches[2]."]" . "(" . $matches[1] . ")" . PHP_EOL;
            },
            $bbcode
        );
        $bbcls = new self();
        $bbcode = $bbcls->replaceLists($bbcode);
        $bbcode = $bbcls->replaceQuotes($bbcode);
        $bbcode = $bbcls->replaceCode($bbcode);
        $bbcode = $bbcls->replaceConsole($bbcode);
        $bbcode = $bbcls->replaceSpoiler($bbcode);

        return $bbcode;
    }
    
    public static function convertForTelegram($bbcode)
    {
        $bbcode = str_replace("[br]", "  \n", $bbcode);

        $bbcode = str_replace("[b]", "*", $bbcode);
        $bbcode = str_replace("[/b]", "*", $bbcode);

        $bbcode = str_replace("[i]", "_", $bbcode);
        $bbcode = str_replace("[/i]", "_", $bbcode);

        $bbcode = str_replace("[s]", "", $bbcode);
        $bbcode = str_replace("[/s]", "", $bbcode);

        $bbcode = str_replace("[u]", "", $bbcode);
        $bbcode = str_replace("[/u]", "", $bbcode);

        $bbcode = str_replace("[url]", "<", $bbcode);
        $bbcode = str_replace("[/url]", ">", $bbcode);
        $bbcode = preg_replace_callback('%\[url\s*=\s*("(?:[^"]*")|\A[^\']*\Z|(?:[^\'">\]\s]+))\s*(?:[^]\s]*)\]([\W\D\w\s]*?)\[/url\]%iu',
            function ($matches) {
                return "[" . $matches[2] . "](" . $matches[1] . ")";
            },
            $bbcode
        );



        // [email]
        $bbcode = preg_replace_callback('%\[email\]([\W\D\w\s]*?)\[/email\]%iu',
            function ($matches) {
                return $matches[1];
            },
            $bbcode
        );
        $bbcode = preg_replace_callback('%\[email\s*=\s*([\W\D\w\s]*?)\]([\W\D\w\s]*?)\[/email\]%iu',
            function ($matches) {
                return $matches[1];
            },
            $bbcode
        );
        // [color]
        $bbcode = preg_replace_callback('%\[color=([a-zA-Z]{3,20}|\#[0-9a-fA-F]{6}|\#[0-9a-fA-F]{3})]([\W\D\w\s]*?)\[/color\]%iu',
            function ($matches) {
                return $matches[2];
            },
            $bbcode
        );
        // [img]
        $bbcode = preg_replace_callback('%\[img\]([\W\D\w\s]*?)\[/img\]%iu',
            function ($matches) {
                return PHP_EOL . "![]" . "(" . $matches[1] . ")" . PHP_EOL;
            },
            $bbcode
        );
        $bbcode = preg_replace_callback('%\[img\s*=\s*("(?:[^"]*")|\A[^\']*\Z|(?:[^\'">\]\s]+))\s*(?:[^]\s]*)\[/img\]%iu',
            function ($matches) {
                return PHP_EOL . "![".$matches[2]."]" . "(" . $matches[1] . ")" . PHP_EOL;
            },
            $bbcode
        );
        $bbcls = new self();
        $bbcode = $bbcls->replaceLists($bbcode);
        $bbcode = $bbcls->replaceQuotes($bbcode);
        $bbcode = $bbcls->replaceCode($bbcode);
        $bbcode = $bbcls->replaceConsole($bbcode);
        $bbcode = $bbcls->replaceSpoiler($bbcode);

        return $bbcode;
    }

    protected function replaceLists($text)
    {
        if (strpos($text,'[list') !== false) {
            $text = preg_replace_callback(
                '%\[list(?:=([1a*]))?+\]((?:[^\[]*+(?:(?!\[list(?:=[1a*])?+\]|\[/list\])\[[^\[]*+)*+|(?R))*)\[/list\]%i',
                function($matches) {
                    return $this->replaceLists($matches[2]);
                },
                $text
            );
        }
        $text = preg_replace('#\s*\[\*\](.*?)\[/\*\]\s*#s', '- $1'.PHP_EOL, trim($text));

        return PHP_EOL . $text . PHP_EOL;
    }


    protected function replaceQuotes($text) {
        $text = preg_replace("~\G(?<!^)(?>(\[quote\b[^]]*](?>[^[]++|\[(?!/?quote)|(?1))*\[/quote])|(?<!\[)(?>[^[]++|\[(?!/?quote))+\K)|\[quote\b[^]]*]\K~", '', $text);

        return preg_replace_callback('%\[quote\b[^]]*\]((?>[^[]++|\[(?!/?quote))*)\[/quote\]%i',
            function($matches) {
                $quote = preg_replace('/^\s*/mu', '', trim($matches[1]));
                return "> ".$quote . PHP_EOL . PHP_EOL;
            },
            $text
        );
    }

    protected function replaceCode($text)
    {
        return preg_replace_callback('%\[code\]([\W\D\w\s]*?)\[\/code\]%iu',
            function ($matches) {
                return PHP_EOL . "```" . PHP_EOL . trim($matches[1]) . PHP_EOL . "```" . PHP_EOL;
            },
            $text
        );
    }

    protected function replaceConsole($text)
    {
        $text = preg_replace("~\G(?<!^)(?>(\[console\b[^]]*](?>[^[]++|\[(?!/?console)|(?1))*\[/console])|(?<!\[)(?>[^[]++|\[(?!/?console))+\K)|\[console\b[^]]*]\K~", '', $text);

        return preg_replace_callback('%\[console\b[^]]*\]((?>[^[]++|\[(?!/?console))*)\[/console\]%i',
            function($matches) {
                $console = preg_replace('/^\s*/mu', '', trim($matches[1]));
                return PHP_EOL . "```" . PHP_EOL . $console . PHP_EOL . "```" . PHP_EOL;
            },
            $text
        );
    }

    protected function replaceSpoiler($text)
    {
        $text = preg_replace("~\G(?<!^)(?>(\[spoiler\b[^]]*](?>[^[]++|\[(?!/?spoiler)|(?1))*\[/spoiler])|(?<!\[)(?>[^[]++|\[(?!/?spoiler))+\K)|\[spoiler\b[^]]*]\K~", '', $text);

        return preg_replace_callback('%\[spoiler\b[^]]*\]((?>[^[]++|\[(?!/?spoiler))*)\[/spoiler\]%i',
            function($matches) {
                $spoiler = preg_replace('/^\s*/mu', '', trim($matches[1]));
                return PHP_EOL . "```" . PHP_EOL . $spoiler . PHP_EOL . "```" . PHP_EOL;
            },
            $text
        );
    }
}