<?php

namespace CloudyCityCodingStandard\Sniffs\NamingConventions;

use \PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Common;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\NamingConventions\ValidVariableNameSniff as BaseValidVariableNameSniff;

class ValidVariableNameSniff extends BaseValidVariableNameSniff
{
    protected function processVariable(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $varName = ltrim($tokens[$stackPtr]['content'], '$');

        // If it's a php reserved var, then its ok.
        if (isset($this->phpReservedVars[$varName]) === true) {
            return;
        }

        // If variable is called statically, ignore it (Class::$is_okay || $object::is_okay;)
        if (isset($tokens[$stackPtr - 1]) && $tokens[$stackPtr - 1]['code'] === T_DOUBLE_COLON) {
            return;
        }

        if (Common::isCamelCaps($varName, false, true, false)) {
            return;
        }

        $error = 'Variable "%s" is not in valid camel caps format';
        $data = [$varName];
        $phpcsFile->addError($error, $stackPtr, 'NotCamelCaps', $data);
    }

    protected function processMemberVar(File $phpcsFile, $stackPtr)
    {
        // Not checking anything for member vars atm
    }
}
