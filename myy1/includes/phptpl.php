<?php

  if ( !defined('IN_MYY') ){
	 die("对不起！无效访问。<p>Sorry! This accessing is not valid! Try again.");
  }

//模板库IT.PHP开始
// +----------------------------------------------------------------------+
// | PHP version 4.0                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2001 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Ulf Wendel <ulf.wendel@phpdoc.de>                           |
// +----------------------------------------------------------------------+
//
// $Id: IT.php,v 1.4 2001/02/08 22:19:59 chagenbu Exp $

class IntegratedTemplate {

    var $err = array();
    
    var $printError = false;
    
    var $haltOnError = false;
    
    var $clearCache = false;
    
    var $openingDelimiter = "{";
    
    var $closingDelimiter     = "}";
    
    var $blocknameRegExp    = "[0-9A-Za-z_-]+";
    
    var $variablenameRegExp    = "[0-9A-Za-z_-]+";
    
    var $variablesRegExp = "";
    
    var $removeVariablesRegExp = "";
    
    var $removeUnknownVariables = true;
    
    var $removeEmptyBlocks = true;
    
    var $blockRegExp = "";
    
    var $currentBlock = "__global__";
   
    var $template = "";
        
    var $blocklist = array();
  
    var $blockdata = array();
    
    var $blockvariables = array();
   
    var $blockinner         = array();
    
    var $touchedBlocks = array();
  
    var $variableCache = array();
    
    var $clearCacheOnParse = false;
    
    var $fileRoot = "";
    
    var $flagBlocktrouble = false;
    
    var $flagGlobalParsed = false;
    
    var $flagCacheTemplatefile = true;
    
    var $lastTemplatefile = "";
    
    function IntegratedTemplate($root = "") {
    
        $this->variablesRegExp = "@" . $this->openingDelimiter . "(" . $this->variablenameRegExp . ")" . $this->closingDelimiter . "@sm";
        $this->removeVariablesRegExp = "@" . $this->openingDelimiter . "\s*(" . $this->variablenameRegExp . ")\s*" . $this->closingDelimiter . "@sm";
        
        $this->blockRegExp = '@<!--\s+BEGIN\s+(' . $this->blocknameRegExp . ')\s+-->(.*)<!--\s+END\s+\1\s+-->@sm';

        $this->setRoot($root);        
    } 
    
    function show($block = "__global__") {
        print $this->get($block);
    } 
    
    function get($block = "__global__") {

        if ("__global__" == $block && !$this->flagGlobalParsed)
            $this->parse("__global__");

        if (!isset($this->blocklist[$block])) {
            $this->halt("The block '$block' was not found in the template.", __FILE__, __LINE__);
            return true;
        }

        if ($this->clearCache) {

            $data = (isset($this->blockdata[$block])) ? $this->blockdata[$block] : "";
            unset($this->blockdata[$block]);
            return $data;

        } else {

            return (isset($this->blockdata[$block])) ? $this->blockdata[$block] : "";

        }

    } 
    
    function parse($block = "__global__", $flag_recursion = false) {

        if (!isset($this->blocklist[$block])) {
            $this->halt("The block '$block' was not found in the template.", __FILE__, __LINE__);
            return false;
        }

        if ("__global__" == $block)
            $this->flagGlobalParsed = true;
            
        $regs = array();
        $values = array();

        if ($this->clearCacheOnParse) {
            
            foreach ($this->variableCache as $name => $value) {
                $regs[] = "@" . $this->openingDelimiter . $name . $this->closingDelimiter . "@";
                $values[] = $value;
            }
            $this->variableCache = array();
        
        } else {

            foreach ($this->blockvariables[$block] as $allowedvar => $v) {
        
                if (isset($this->variableCache[$allowedvar])) {
                   $regs[]   = "@".$this->openingDelimiter . $allowedvar . $this->closingDelimiter . "@";
                   $values[] = $this->variableCache[$allowedvar];
                    unset($this->variableCache[$allowedvar]);
                }

            }        
            
        }

        $outer = (0 == count($regs)) ? $this->blocklist[$block] : preg_replace($regs, $values, $this->blocklist[$block]);
        $empty = (0 == count($values)) ? true : false;

        if (isset($this->blockinner[$block])) {
        
          foreach ($this->blockinner[$block] as $k => $innerblock) {

            $this->parse($innerblock, true);
                if ("" != $this->blockdata[$innerblock])
                    $empty = false;

                $placeholder = $this->openingDelimiter . "__" . $innerblock . "__" . $this->closingDelimiter;                
                $outer = str_replace($placeholder, $this->blockdata[$innerblock], $outer);
                $this->blockdata[$innerblock] = "";                
      }
            
    }

    if ($this->removeUnknownVariables)
            $outer = preg_replace($this->removeVariablesRegExp, "", $outer);

        if ($empty) {

            if (!$this->removeEmptyBlocks) {
            
                $this->blockdata[$block ].= $outer;
                
            } else {

                if (isset($this->touchedBlocks[$block]))
                    $this->blockdata[$block] .= $outer;
                
            }
                
        } else {
        
            $this->blockdata[$block] .= $outer;
        
        }

        return $empty;
    }

    function parseCurrentBlock() {
        return $this->parse($this->currentBlock);
    }
    
    function setVariable($variable, $value = "") {
        
        if (is_array($variable)) {
        
            $this->variableCache = array_merge($this->variableCache, $variable);
                
        } else {
            
            $this->variableCache[$variable] = $value;
            
        }
    
    }
    
    function setCurrentBlock($block = "__global__") {
    
        if (!isset($this->blocklist[$block])) {
            $this->halt("Can't find the block '$block' in the template.", __FILE__, __LINE__);
            return false;
        }
            
        $this->currentBlock = $block;
        
        return true;
    } 
    
    function touchBlock($block) {
        
        if (!isset($this->blocklist[$block])) {
            $this->halt("Can't find the block '$block' in the template.", __FILE__, __LINE__);
            return false;
        }
        
        $this->touchedBlocks[$block] = true;
        
        return true;
    }
    
    function init() {
    
        $this->free();
        $this->findBlocks($this->template);
        $this->buildBlockvariablelist();
        
    } 
    
    function free() {
    
        $this->err = array();
        
        $this->currentBlock = "__global__";
        
        $this->variableCache    = array();        
        $this->blocklookup      = array();
        $this->touchedBlocks    = array();
        
        $this->flagBlocktrouble = false;
        $this->flagGlobalParsed = false;
        
    } 
    
    function setTemplate($template, $removeUnknownVariables = true, $removeEmptyBlocks = true) {
                
        $this->removeUnknownVariables = $removeUnknownVariables;
        $this->removeEmptyBlocks = $removeEmptyBlocks;
        
        if ("" == $template && $this->flagCacheTemplatefile) {
        
            $this->variableCache = array();
            $this->blockdata = array();
            $this->touchedBlocks = array();
            $this->currentBlock = "__global__";
            
        } else {
        
            $this->template = '<!-- BEGIN __global__ -->' . $template . '<!-- END __global__ -->';
            $this->init();
            
        }
        
        if ($this->flagBlocktrouble)
            return false;
        
        return true;
    } 
    
    function loadTemplatefile($filename, $removeUnknownVariables = true, $removeEmptyBlocks = true) {

        $template = "";        
        if (!$this->flagCacheTemplatefile || $this->lastTemplatefile != $filename)
            $template = $this->getfile($filename);
            
        $this->lastTemplatefile = $filename;
        
        return $this->setTemplate($template, $removeUnknownVariables, $removeEmptyBlocks, true);
    } 
    
    function setRoot($root) {
        
        if ("" != $root && "/" != substr($root, -1))
            $root .= "/";
        
        $this->fileRoot = $root;
        
    } 
  
    function buildBlockvariablelist() {

        foreach ($this->blocklist as $name => $content) {
            preg_match_all( $this->variablesRegExp, $content, $regs );

            if (0 != count($regs[1])) {

                foreach ($regs[1] as $k => $var)             
                    $this->blockvariables[$name][$var] = true;
                    
            } else {
            
                $this->blockvariables[$name] = array();
                
            }
                
        }    
        
    } 
    
    function getGlobalvariables() {

        $regs   = array();
        $values = array();

        foreach ($this->blockvariables["__global__"] as $allowedvar => $v) {
            
            if (isset($this->variableCache[$allowedvar])) {
                $regs[]   = "@" . $this->openingDelimiter . $allowedvar . $this->closingDelimiter."@";
                $values[] = $this->variableCache[$allowedvar];
                unset($this->variableCache[$allowedvar]);
            }
            
        }
        
        return array($regs, $values);
    } 
   
    function findBlocks($string) {

        $blocklist = array();

        if (preg_match_all($this->blockRegExp, $string, $regs, PREG_SET_ORDER)) {
            
            foreach ($regs as $k => $match) {
            
                $blockname         = $match[1];
                $blockcontent = $match[2];
            
                if (isset($this->blocklist[$blockname])) {
                    $this->halt("The name of a block must be unique within a template. Found '$blockname' twice. Unpredictable results may appear.", __FILE__, __LINE__);
                    $this->flagBlocktrouble = true;
                }                

                $this->blocklist[$blockname] = $blockcontent;
                $this->blockdata[$blockname] = "";

                $blocklist[] = $blockname;
                
                $inner = $this->findBlocks($blockcontent);
                foreach ($inner as $k => $name) {

                    $pattern = sprintf('@<!--\s+BEGIN\s+%s\s+-->(.*)<!--\s+END\s+%s\s+-->@sm', 
                                                    $name,
                                                    $name
                                                );

                    $this->blocklist[$blockname] = preg_replace(    $pattern, 
                                                                    $this->openingDelimiter . "__" . $name . "__" . $this->closingDelimiter, 
                                                                    $this->blocklist[$blockname]
                                                               );
                    $this->blockinner[$blockname][] = $name;
                    $this->blockparents[$name] = $blockname;
                    
                }
                
            }
            
        }

        return $blocklist;
    } 
    
    function getFile($filename) {
        
        if ("/" == $filename{0} && "/" == substr($this->fileRoot, -1)) 
            $filename = substr($filename, 1);
            
        $filename = $this->fileRoot . $filename;
        
        if (!($fh = @fopen($filename, "r"))) {
            $this->halt("Can't read '$filename'.", __FILE__, __LINE__);
            return "";
        }
    
        $content = fread($fh, filesize($filename));
        fclose($fh);
        
        return $content; 
    } 
    
    function halt($message, $file = "", $line = 0) {
        
        
        $message = sprintf("IntegratedTemplate Error: %s [File: %s, Line: %d]",
                                                            $message,
                                                            $file,
                                                            $line
                                                    );

        $this->err[] = $message;

        if ($this->printError)
            print $message;
            
        if ($this->haltOnError)
            die($message);

    }
    
} 
//模板库IT.PHP结束

//模板库ITX.PHP开始
class IntegratedTemplateExtension extends IntegratedTemplate {

    var $warn = array();
    
    var $printWarning = false;
    
    var $haltOnWarning = false;
       
    var $checkblocknameRegExp = "";
    
    var $functionPrefix = "func_";
    
    var $functionnameRegExp = "[_a-zA-Z]+[A-Za-z_0-9]*";
    
    var $functionRegExp = "";
    
    var $functions         = array();
    
    var $callback         = array();
    
    function IntegratedTemplateExtension($root = "") {
    
        $this->checkblocknameRegExp = "@" . $this->blocknameRegExp . "@";
        $this->functionRegExp = "@" . $this->functionPrefix . "(" . $this->functionnameRegExp . ")\s*\(@sm";
        
        $this->IntegratedTemplate($root);
                                                                                            
    } 
    
    function init() {
    
        $this->free();
        $this->buildFunctionlist();
        $this->findBlocks($this->template);
        $this->buildBlockvariablelist();
        
    }    
    
    function replaceBlock($block, $template) {
        if (!isset($this->blocklist[$block])) {
            $this->halt("The block '$block' does not exist in the template and thus it can't be replaced.", __FILE__, __LINE__);
            return false;
        }
        if (""==$template) {
            $this->halt("No block content given.", __FILE__, __LINE__);
            return false;
        }
        
        print "This function has not been coded yet.";
                
        return true;
    }
    
    function replaceBlockfile($block, $filename) {
        return $this->replaceBlock($block, $this->getFile($filename));    
    } 
        
    function addBlock($placeholder, $blockname, $template) {
    
        if ("" == $placeholder) {
        
            $this->halt("No variable placeholder given.", __FILE__, __LINE__);
            return false;
            
        } else if ("" == $blockname || !preg_match($this->checkblocknameRegExp, $blockname) ) {
            
            print $this->checkblocknameRegExp;
            $this->halt("No or invalid blockname '$blockname' given.", __FILE__, __LINE__);
            return false;
            
        } else if ("" == $template) {
        
            $this->halt("No block content given.", __FILE__, __LINE__);
            return false;
            
        } else if (isset($this->blocklist[$blockname])) {
        
            $this->halt("The block already exists.", __FILE__, __LINE__);
            return false;
            
        }
        
       
        $parents = $this->findPlaceholderBlocks($placeholder);
        if (0 == count($parents)) {
        
            $this->halt("The variable placeholder '$placeholder' was not found in the template.", __FILE__, __LINE__);
            return false;
            
        } else if ( count($parents) > 1 ) {
            
            reset($parents);
            while (list($k, $parent) = each($parents)) 
                $msg .= "$parent, ";
            $msg = substr($parent, -2);
            
            $this->halt("The variable placeholder '$placeholder' must be unique, found in multiple blocks '$msg'.", __FILE__, __LINE__);
            return false;
                        
        }
        
        $template = "<!-- BEGIN $blockname -->" . $template . "<!-- END $blockname -->";
        $this->findBlocks($template);
        if ($this->flagBlocktrouble) 
            return false;    
        
        $this->blockinner[$parents[0]][] = $blockname;
        $this->blocklist[$parents[0]] = preg_replace(   "@" . $this->openingDelimiter . $placeholder . $this->closingDelimiter . "@", 
                                                        $this->openingDelimiter . "__" . $blockname . "__" . $this->closingDelimiter, 
                                                        $this->blocklist[$parents[0]]
                                                      );
                                                                                                
        $this->deleteFromBlockvariablelist($parents[0], $placeholder);
        $this->updateBlockvariablelist($blockname);
        
        return true;
    } 
    
    function addBlockfile($placeholder, $blockname, $filename) {
        return $this->addBlock($placeholder, $blockname, $this->getFile($filename));
    } 
    
    function placeholderExists($placeholder, $block = "") {
        
        if ("" == $placeholder) {
            $this->halt("No placeholder name given.", __FILE__, __LINE__);
            return "";
        }
        
        if ("" != $block && !isset($this->blocklist[$block])) {
            $this->halt("Unknown block '$block'.", __FILE__, __LINE__);
            return "";
        }
        
        
        $found = "";
        
        if ("" != $block) {

            if (is_array($variables = $this->blockvariables[$block])) {
            
                reset($variables);
                while (list($k, $variable) = each($variables)) 
                    if ($variable == $placeholder) {
                        $found = $block;
                        break;
                    }
                        
            }
                        
        } else {
        
            reset($this->blockvariables);
            while (list($blockname, $variables) = each($this->blockvariables)) {

                reset($variables);
                while (list($k, $variable) = each($variables)) 
                    if ($variable == $placeholder) {
                        $found = $blockname;
                        break 2;
                    }

            }
            
        }
        
        return $found;
    }
    
    function performCallback() {
    
        reset($this->functions);
        while (list($func_id, $function) = each($this->functions)) {
            
            if (isset($this->callback[$function["name"]])) {
            
                if ("" != $this->callback[$function["name"]]["object"])
                    $this->setFunctioncontent($func_id, 
                                              call_user_method( 
                                                                $this->callback[$function["name"]]["function"], 
                                                                $GLOBALS[$this->callback[$function["name"]]["object"]],
                                                                $function["args"]
                                                               )
                                                  );
                else
                    $this->setFunctioncontent(  $func_id, 
                                                call_user_func( 
                                                                $this->callback[$function["name"]]["function"], 
                                                                $function["args"]
                                                               )
                                                   );
                                                                        
            }
            
        }
        
    } 
    
    function getFunctioncalls() {
        
        return $this->functions;
        
    } 
    
    function setFunctioncontent($functionID, $replacement) {
        
        $this->variableCache["__function" . $functionID . "__"] = $replacement;
        
    } 
    
    function setCallbackFunction($tplfunction, $callbackfunction, $callbackobject = "") {
        
        if ("" == $tplfunction || "" == $callbackfunction) {
            $this->halt("No template function ('$tplfunction') and/or no callback function ('$callback') given.", __FILE__, __LINE__);
            return false;
        }
        
        $this->callback[$tplfunction] = array(
                                              "function"    => $callbackfunction,
                                              "object"        => $callbackobject
                                        );            

        return true;
    } 
    
    function setCallbackFuntiontable($functions) {
    
        $this->callback = $functions;
    
    } 
    
    function getBlocklist() {
    
        $blocklist = array();
        foreach ($this->blocklist as $block => $content) 
            $blocklist[$block] = $block;
            
        return $blocklist;
    } 

    function blockExists($blockname) {
        return isset($this->blocklist[$blockname]);
    } 
    
    function getBlockvariables($block) {
        if (!isset($this->blockvariables[$block]))
            return array();
            
        $variables = array();
        foreach ($this->blockvariables as $variable => $v)
            $variables[$variable] = $variable;
            
        return $variables;
    } 
    
    function BlockvariableExists($block, $variable) {
        return isset($this->blockvariables[$block][$variable]);
    }
    
    function buildFunctionlist() {
        
        $this->functions = array();
        
        $template = $this->template;
        $num = 0;
        
        while (preg_match($this->functionRegExp, $template, $regs))    {
        
            $pos = strpos($template, $regs[0]);
            $template = substr($template, $pos + strlen($regs[0]));
            
            $head = $this->getValue($template, ")");
            $args = array();
            
            $this->template = str_replace($regs[0] . $head . ")", "{__function" . $num . "__}", $this->template);
            $template = str_replace($regs[0] . $head . ")", "{__function" . $num . "__}", $template);
            
            while ("" != $head && $arg = $this->getValue($head, ",")) {
                $args[] = trim($arg);
                if ($arg == $head)                                     
                    break;
                $head = substr($head, strlen($arg) + 1);
            }    

            $this->functions[$num++] = array( 
                                                "name"    => $regs[1],
                                                "args"    => $args
                                            );            
        }

    }     
    
    function getValue($code, $delimiter) {
        if ("" == $code)
            return "";
    
        if (!is_array($delimiter)) 
            $delimiter = array( $delimiter => true );
            
        $len            = strlen($code);
        $enclosed       = false;
        $enclosed_by    = "";
        
        if (isset($delimiter[$code[0]])) {
        
            $i = 1;
            
        } else {
        
            for ($i = 0; $i < $len; ++$i) {
            
                $char = $code[$i];

                if (('"' == $char || "'" == $char) && ($char == $enclosed_by || "" == $enclosed_by) && (0 == $i || ($i > 0 && "\\" != $code[$i - 1]))) {
                
                    if (!$enclosed)
                        $enclosed_by = $char;
                    else 
                        $enclosed_by = "";
                        
                    $enclosed = !$enclosed;
                    
                }
                if (!$enclosed && isset($delimiter[$char]))
                    break;                    
                    
            }
        
        }
  
        return substr($code, 0, $i);
    } 

    function deleteFromBlockvariablelist($block, $variables) {
    
        if (!is_array($variables))
            $variables = array($variables => true);
            
        reset($this->blockvariables[$block]);
        while (list($k, $varname) = each($this->blockvariables[$block])) 
            if (isset($variables[$varname])) 
                unset($this->blockvariables[$block][$k]);
                    
    }
  
    function updateBlockvariablelist($block) {
        
        preg_match_all( $this->variablesRegExp, $this->blocklist[$block], $regs );
        $this->blockvariables[$block] = $regs[1];
            
    }
    
    function findPlaceholderBlocks($variable) {
        
        $parents = array();
        
        reset($this->blocklist);
        while (list($blockname, $content) = each($this->blocklist)) {
            
            reset($this->blockvariables[$blockname]);
            while (list($k, $varname) = each($this->blockvariables[$blockname]))
                if ($variable == $varname) 
                    $parents[] = $blockname;
        }
            
        return $parents;
    }
    
    function warning($message, $file="", $line=0) {
        
        $message = sprintf("IntegratedTemplateExtension Warning: %s [File: %s, Line: %d]",
                                $message,
                                $file, 
                                $line );

        $this->warn[] = $message;
        
        if ($this->printWarning)
            print $message;
            
        if ($this->haltOnError) 
            die($message);
        
    } 
    
} 
//模板库ITX.PHP结束

?>
