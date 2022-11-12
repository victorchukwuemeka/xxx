<?php 

namespace Framework\View\Engine;

use Framework\View\Engine;

/**
 * 
 */
trait UseMacro
{
    protected Manager $manager;

    public function useMacro($name, ...$value): static
    {
        if (isset($this->macros[$name])) {
        /**
         * we bind the closure so that $this
         * inside a refers to the view object
         * which means $data and $path can be used 
         * and you get back to engine
         */
        $bound = $this->macros[$name]->bindTo($this);
        return $bound(...$value);
    }
    throw new Exception("Macro isnot free '{$name}'");

    }
}
