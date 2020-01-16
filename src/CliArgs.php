<?php namespace bpopescu\CliArgs;

class CliArgs
{

    public function parse(array $arguments = null)
    {
        $whatToParse = $this->whatToParse($arguments);
        $newArguments = [];
        $cachedKey = null;
        while ($arg = array_shift($whatToParse)) {
            $dashCount = substr_count($arg, '-');
            if ($dashCount > 0) {
                list($newKey, $value) = $this->parseArg($arg);
                if ($dashCount === 1 && strlen($newKey) > 1) {
                    $this->mergeArgs($newArguments, $this->parseMultipleArgs($newKey));
                    continue;
                }

                if (is_null($value) && is_null($cachedKey)) {
                    $cachedKey = $newKey;
                    continue;
                } elseif (is_null($value) && !is_null($cachedKey)) {
                    $toAdd = [$cachedKey => true, $newKey => true];
                    $cachedKey = $newKey;
                } else {
                    $toAdd = [$newKey => $value];
                }

            } else {
                $toAdd = !empty($cachedKey) ? [$cachedKey => $arg] : [$arg];
                $cachedKey = null;
            }
            $this->mergeArgs($newArguments, $toAdd);
        }
        return $newArguments;
    }

    private function whatToParse(array $arguments): array
    {
        if(empty($arguments)){
            $serverArgs = $_SERVER['argv'] ?? [];
            array_shift($serverArgs);
            $arguments = $serverArgs;
        }
        return $arguments;
    }

    private function mergeArgs(&$arguments, array $argumentsToAdd): void
    {
        $arguments = array_merge($arguments, $argumentsToAdd);
    }

    private function parseMultipleArgs(string $arg)
    {
        $newKeys = str_split($arg, 1);
        $newValues = array_fill(0, count($newKeys), true);
        return array_combine($newKeys, $newValues);
    }

    private function parseArg(string $arg): array
    {
        $arg = str_replace(['=', ':'], ':', $arg);
        list($key, $value) = explode(':', $arg);
        if (is_string($key)) {
            $key = str_replace('-', '', $key);
        }

        return [$key, $value];
    }


}